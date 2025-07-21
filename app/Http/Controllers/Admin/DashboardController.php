<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Admin;
use App\Models\Participant;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\EventCategory;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        // Dashboard overview statistics
        $stats = [
            'total_events' => Event::count(),
            'total_participants' => Participant::count(),
            'total_revenue' => Transaction::where('status', 'complete')->sum('amount'),
            'active_events' => Event::where('status', 'open')->count(),
            'recent_transactions' => Transaction::with(['participant', 'event'])
                ->where('status', 'complete')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'upcoming_events' => Event::where('start_time', '>', now())
                ->orderBy('start_time', 'asc')
                ->limit(5)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function participants(Request $request)
    {
        $eventId = $request->get('event_id');

        // Fetch participants data with event and transaction information
        $participantsQuery = Participant::with(['event', 'transactions'])
            ->orderBy('created_at', 'desc');

        if ($eventId) {
            $participantsQuery->where('event_id', $eventId);
        }

        $participants = $participantsQuery->paginate(20);

        // Calculate statistics (filtered by event if selected)
        $statsQuery = Participant::query();
        if ($eventId) {
            $statsQuery->where('event_id', $eventId);
        }

        $stats = [
            'total_participants' => (clone $statsQuery)->count(),
            'paid_participants' => (clone $statsQuery)->whereHas('transactions', function($query) {
                $query->where('status', 'complete');
            })->count(),
            'today_registrations' => (clone $statsQuery)->whereDate('created_at', today())->count(),
            'gender_data_available' => (clone $statsQuery)->whereNotNull('gender')->count(),
        ];

        // Get all events for the filter dropdown
        $events = Event::orderBy('name')->get();
        $selectedEvent = $eventId ? Event::find($eventId) : null;

        return view('admin.reports.participants', compact('participants', 'stats', 'events', 'selectedEvent'));
    }

    public function transactions(Request $request)
    {
        $eventId = $request->get('event_id');

        // Transaction reports with filtering options
        $transactionsQuery = Transaction::with(['participant', 'event'])
            ->orderBy('created_at', 'desc');

        if ($eventId) {
            $transactionsQuery->where('event_id', $eventId);
        }

        $transactions = $transactionsQuery->paginate(20);

        // Calculate statistics (filtered by event if selected)
        $revenueQuery = Transaction::where('status', 'complete');
        $pendingQuery = Transaction::where('status', 'pending');
        $todayQuery = Transaction::where('status', 'complete')->whereDate('created_at', today());

        if ($eventId) {
            $revenueQuery->where('event_id', $eventId);
            $pendingQuery->where('event_id', $eventId);
            $todayQuery->where('event_id', $eventId);
        }

        $totalRevenue = $revenueQuery->sum('amount');
        $pendingAmount = $pendingQuery->sum('amount');
        $todayRevenue = $todayQuery->sum('amount');

        // Get all events for the filter dropdown
        $events = Event::orderBy('name')->get();
        $selectedEvent = $eventId ? Event::find($eventId) : null;

        return view('admin.reports.transactions', compact('transactions', 'totalRevenue', 'pendingAmount', 'todayRevenue', 'events', 'selectedEvent'));
    }

    public function events()
    {
        // Events report with statistics
        $events = Event::with(['fees', 'categories', 'participants', 'transactions'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $eventStats = [
            'total_events' => Event::count(),
            'scheduled' => Event::where('status', 'scheduled')->count(),
            'open' => Event::where('status', 'open')->count(),
            'closed' => Event::where('status', 'closed')->count(),
            'completed' => Event::where('status', 'complete')->count(),
        ];

        return view('admin.reports.events', compact('events', 'eventStats'));
    }

    public function revenue(Request $request)
    {
        $eventId = $request->get('event_id');

        // Revenue analytics
        $monthlyRevenueQuery = Transaction::where('status', 'complete');
        if ($eventId) {
            $monthlyRevenueQuery->where('event_id', $eventId);
        }

        $monthlyRevenue = $monthlyRevenueQuery
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        $revenueByEventQuery = Event::with('transactions')
            ->whereHas('transactions', function($query) use ($eventId) {
                $query->where('status', 'complete');
                if ($eventId) {
                    $query->where('event_id', $eventId);
                }
            });

        if ($eventId) {
            $revenueByEventQuery->where('id', $eventId);
        }

        $revenueByEvent = $revenueByEventQuery->get()
            ->map(function($event) {
                return [
                    'event_name' => $event->name,
                    'total_revenue' => $event->transactions->where('status', 'complete')->sum('amount'),
                    'participant_count' => $event->participants->count(),
                ];
            })
            ->sortByDesc('total_revenue');

        $totalRevenueQuery = Transaction::where('status', 'complete');
        $averageTransactionQuery = Transaction::where('status', 'complete');

        if ($eventId) {
            $totalRevenueQuery->where('event_id', $eventId);
            $averageTransactionQuery->where('event_id', $eventId);
        }

        $totalRevenue = $totalRevenueQuery->sum('amount');
        $averageTransactionValue = $averageTransactionQuery->avg('amount');

        // Get all events for the filter dropdown
        $events = Event::orderBy('name')->get();
        $selectedEvent = $eventId ? Event::find($eventId) : null;

        return view('admin.reports.revenue', compact('monthlyRevenue', 'revenueByEvent', 'totalRevenue', 'averageTransactionValue', 'events', 'selectedEvent'));
    }

    public function analytics()
    {
        // Advanced analytics dashboard
        $registrationTrends = Participant::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topEvents = Event::withCount('participants')
            ->orderBy('participants_count', 'desc')
            ->limit(10)
            ->get();

        $categoryStats = EventCategory::select('name', DB::raw('COUNT(*) as event_count'))
            ->groupBy('name')
            ->orderBy('event_count', 'desc')
            ->get();

        $paymentMethodStats = Transaction::where('status', 'complete')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.analytics', compact('registrationTrends', 'topEvents', 'categoryStats', 'paymentMethodStats'));
    }

    public function exportParticipants(Request $request)
    {
        $eventId = $request->get('event_id');

        // Export participants data as CSV
        $participantsQuery = Participant::with(['event', 'transactions']);

        if ($eventId) {
            $participantsQuery->where('event_id', $eventId);
        }

        $participants = $participantsQuery->get();

        // Create more detailed CSV header
        $csvData = "Name,Email,Phone,Event,Category,Registration Type,Gender,Date of Birth,T-Shirt Size,Address,Thana,District,Emergency Contact,Registration Date,Payment Status,Total Paid,Fee Amount\n";

        foreach ($participants as $participant) {
            $paymentStatus = $participant->transactions->where('status', 'complete')->count() > 0 ? 'Paid' : 'Pending';
            $totalPaid = $participant->transactions->where('status', 'complete')->sum('amount');
            $eventName = $participant->event ? $participant->event->name : 'No Event';

            $csvData .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%.2f\",\"%.2f\"\n",
                $participant->name ?: '',
                $participant->email ?: '',
                $participant->phone ?: '',
                $eventName,
                $participant->category ?: 'N/A',
                ucfirst($participant->reg_type ?: 'N/A'),
                ucfirst($participant->gender ?: 'N/A'),
                $participant->dob ? \Carbon\Carbon::parse($participant->dob)->format('Y-m-d') : 'N/A',
                $participant->tshirt_size ?: 'N/A',
                $participant->address ?: '',
                $participant->thana ?: '',
                $participant->district ?: '',
                $participant->emergency_phone ?: 'N/A',
                $participant->created_at->format('Y-m-d H:i:s'),
                $paymentStatus,
                $totalPaid,
                $participant->fee ?: 0
            );
        }

        // Generate descriptive filename
        if ($eventId) {
            $event = Event::find($eventId);
            $eventSlug = $event ? \Str::slug($event->name) : 'event_' . $eventId;
            $filename = 'participants_' . $eventSlug . '_' . date('Y-m-d_H-i-s') . '.csv';
        } else {
            $filename = 'participants_all_events_' . date('Y-m-d_H-i-s') . '.csv';
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportTransactions(Request $request)
    {
        $eventId = $request->get('event_id');

        // Export transactions data as CSV
        $transactionsQuery = Transaction::with(['participant', 'event']);

        if ($eventId) {
            $transactionsQuery->where('event_id', $eventId);
        }

        $transactions = $transactionsQuery->get();

        // Create more detailed CSV header
        $csvData = "Transaction ID,Participant Name,Participant Email,Participant Phone,Event Name,Amount,Status,Payment Method,Gateway Transaction ID,Currency,Transaction Date,Participant Registration Date\n";

        foreach ($transactions as $transaction) {
            $transactionId = $transaction->transaction_id ?: $transaction->id;
            $participantName = $transaction->participant ? $transaction->participant->name : 'Unknown Participant';
            $participantEmail = $transaction->participant ? $transaction->participant->email : 'N/A';
            $participantPhone = $transaction->participant ? $transaction->participant->phone : 'N/A';
            $eventName = $transaction->event ? $transaction->event->name : 'Unknown Event';
            $paymentMethod = $transaction->payment_method ?: 'N/A';
            $gatewayTranId = $transaction->gateway_transaction_id ?: 'N/A';
            $currency = $transaction->currency ?: 'BDT';
            $participantRegDate = $transaction->participant ? $transaction->participant->created_at->format('Y-m-d H:i:s') : 'N/A';

            $csvData .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%.2f\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $transactionId,
                $participantName,
                $participantEmail,
                $participantPhone,
                $eventName,
                $transaction->amount,
                ucfirst($transaction->status),
                $paymentMethod,
                $gatewayTranId,
                $currency,
                $transaction->created_at->format('Y-m-d H:i:s'),
                $participantRegDate
            );
        }

        // Generate descriptive filename
        if ($eventId) {
            $event = Event::find($eventId);
            $eventSlug = $event ? \Str::slug($event->name) : 'event_' . $eventId;
            $filename = 'transactions_' . $eventSlug . '_' . date('Y-m-d_H-i-s') . '.csv';
        } else {
            $filename = 'transactions_all_events_' . date('Y-m-d_H-i-s') . '.csv';
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
