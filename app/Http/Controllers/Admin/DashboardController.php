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

    public function getEventCategories($eventId)
    {
        $categories = EventCategory::where('event_id', $eventId)->orderBy('name')->get(['id', 'name']);
        return response()->json($categories);
    }

    public function participants(Request $request)
    {
        $eventId = $request->get('event_id');
        $eventCategoryId = $request->get('event_category_id');
        $paymentStatus = $request->get('payment_status');

        // Fetch participants data with optimized eager loading
        // Only select needed columns for better performance
        $participantsQuery = Participant::select([
                'id', 'participant_id', 'name', 'email', 'phone', 'event_id',
                'category', 'reg_type', 'gender', 'dob', 'tshirt_size',
                'address', 'thana', 'district', 'emergency_phone', 'fee',
                'additional_data', 'created_at'
            ])
            ->with([
                'event:id,name',
                'transactions:id,participant_id,amount,status'
            ])
            ->orderBy('created_at', 'desc');

        if ($eventId) {
            $participantsQuery->where('event_id', $eventId);
        }

        // Filter by event category
        if ($eventCategoryId) {
            $participantsQuery->where('category', $eventCategoryId);
        }

        // Filter by payment status
        if ($paymentStatus) {
            if ($paymentStatus === 'paid') {
                $participantsQuery->whereHas('transactions', function($query) {
                    $query->whereIn('status', ['complete', 'Complete']);
                });
            } elseif ($paymentStatus === 'pending') {
                $participantsQuery->whereDoesntHave('transactions', function($query) {
                    $query->whereIn('status', ['complete', 'Complete']);
                });
            } elseif ($paymentStatus === 'failed') {
                $participantsQuery->whereHas('transactions', function($query) {
                    $query->whereIn('status', ['failed', 'Failed']);
                })->whereDoesntHave('transactions', function($query) {
                    $query->whereIn('status', ['complete', 'Complete']);
                });
            }
        }

        $participants = $participantsQuery->paginate(20)->withQueryString();

        // Calculate statistics (filtered by event and payment status if selected)
        // Optimized to reduce database queries
        $statsQuery = Participant::query();
        if ($eventId) {
            $statsQuery->where('event_id', $eventId);
        }

        if ($eventCategoryId) {
            $statsQuery->where('category', $eventCategoryId);
        }

        // Use a single query to get multiple counts
        $baseCount = (clone $statsQuery)->count();

        $stats = [
            'total_participants' => $baseCount,
            'paid_participants' => (clone $statsQuery)->whereHas('transactions', function($query) {
                $query->whereIn('status', ['complete', 'Complete']);
            })->count(),
            'pending_participants' => (clone $statsQuery)->whereDoesntHave('transactions', function($query) {
                $query->whereIn('status', ['complete', 'Complete']);
            })->count(),
            'today_registrations' => (clone $statsQuery)->whereDate('created_at', today())->count(),
            'gender_data_available' => $baseCount > 0 ? (clone $statsQuery)->whereNotNull('gender')->count() : 0,
        ];

        // Get all events for the filter dropdown (cache for better performance)
        $events = Event::select('id', 'name')->orderBy('name')->get();
        $selectedEvent = $eventId ? Event::select('id', 'name')->find($eventId) : null;

        return view('admin.reports.participants', compact('participants', 'stats', 'events', 'selectedEvent', 'paymentStatus', 'eventCategoryId'));
    }

    public function viewParticipant($id)
    {
        $participant = Participant::with(['event', 'transactions'])->findOrFail($id);

        return view('admin.reports.participant-details', compact('participant'));
    }

    public function transactions(Request $request)
    {
        $eventId = $request->get('event_id');
        $paymentStatus = $request->get('payment_status');

        // Transaction reports with filtering options
        $transactionsQuery = Transaction::with(['participant', 'event'])
            ->orderBy('created_at', 'desc');

        if ($eventId) {
            $transactionsQuery->where('event_id', $eventId);
        }

        // Filter by payment status
        if ($paymentStatus) {
            if ($paymentStatus === 'paid') {
                $transactionsQuery->whereIn('status', ['complete', 'Complete']);
            } elseif ($paymentStatus === 'pending') {
                $transactionsQuery->whereIn('status', ['pending', 'Pending']);
            } elseif ($paymentStatus === 'failed') {
                $transactionsQuery->whereIn('status', ['failed', 'Failed']);
            }
        }

        $transactions = $transactionsQuery->paginate(20)->appends(request()->query());

        // Calculate statistics (filtered by event and payment status if selected)
        $revenueQuery = Transaction::whereIn('status', ['complete', 'Complete']);
        $pendingQuery = Transaction::whereIn('status', ['pending', 'Pending']);
        $failedQuery = Transaction::whereIn('status', ['failed', 'Failed']);
        $todayQuery = Transaction::whereIn('status', ['complete', 'Complete'])->whereDate('created_at', today());

        if ($eventId) {
            $revenueQuery->where('event_id', $eventId);
            $pendingQuery->where('event_id', $eventId);
            $failedQuery->where('event_id', $eventId);
            $todayQuery->where('event_id', $eventId);
        }

        $totalRevenue = $revenueQuery->sum('amount');
        $pendingAmount = $pendingQuery->sum('amount');
        $failedAmount = $failedQuery->sum('amount');
        $todayRevenue = $todayQuery->sum('amount');

        // Get all events for the filter dropdown
        $events = Event::orderBy('name')->get();
        $selectedEvent = $eventId ? Event::find($eventId) : null;

        return view('admin.reports.transactions', compact('transactions', 'totalRevenue', 'pendingAmount', 'failedAmount', 'todayRevenue', 'events', 'selectedEvent', 'paymentStatus'));
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
        $paymentStatus = $request->get('payment_status');
        $eventCategoryId = $request->get('event_category_id');

        // Export participants data as CSV
        $participantsQuery = Participant::with(['event', 'transactions']);

        if ($eventId) {
            $participantsQuery->where('event_id', $eventId);
        }

        if ($eventCategoryId) {
            $participantsQuery->where('category', $eventCategoryId);
        }

        // Filter by payment status
        if ($paymentStatus) {
            if ($paymentStatus === 'paid') {
                $participantsQuery->whereHas('transactions', function($query) {
                    $query->whereIn('status', ['complete', 'Complete']);
                });
            } elseif ($paymentStatus === 'pending') {
                $participantsQuery->whereDoesntHave('transactions', function($query) {
                    $query->whereIn('status', ['complete', 'Complete']);
                });
            } elseif ($paymentStatus === 'failed') {
                $participantsQuery->whereHas('transactions', function($query) {
                    $query->whereIn('status', ['failed', 'Failed']);
                })->whereDoesntHave('transactions', function($query) {
                    $query->whereIn('status', ['complete', 'Complete']);
                });
            }
        }

        $participants = $participantsQuery->get();

        // Collect all unique additional field keys from all participants
        $additionalFieldKeys = [];
        foreach ($participants as $participant) {
            if ($participant->additional_data && is_array($participant->additional_data)) {
                $additionalFieldKeys = array_merge($additionalFieldKeys, array_keys($participant->additional_data));
            }
        }
        $additionalFieldKeys = array_unique($additionalFieldKeys);
        sort($additionalFieldKeys);

        // Build CSV header with standard fields + additional fields
        $headers = [
            'Participant ID',
            'Name',
            'Email',
            'Phone',
            'Event',
            'Category',
            'Registration Type',
            'Gender',
            'Date of Birth',
            'Nationality',
            'T-Shirt Size',
            'Kit Option',
            'Address',
            'Thana',
            'District',
            'Emergency Contact',
            'Registration Date',
            'Payment Status',
            'Total Paid',
            'Fee Amount'
        ];

        // Add additional field headers
        foreach ($additionalFieldKeys as $key) {
            $headers[] = ucwords(str_replace('_', ' ', $key));
        }

        $csvData = implode(',', array_map(function($h) { return '"' . $h . '"'; }, $headers)) . "\n";

        foreach ($participants as $participant) {
            $paymentStatusText = $participant->transactions->whereIn('status', ['complete', 'Complete'])->count() > 0 ? 'Paid' : 'Pending';
            $totalPaid = $participant->transactions->whereIn('status', ['complete', 'Complete'])->sum('amount');
            $eventName = $participant->event ? $participant->event->name : 'No Event';

            $row = [
                $participant->participant_id ?: 'N/A',
                $participant->name ?: '',
                $participant->email ?: '',
                $participant->phone ?: '',
                $eventName,
                $participant->category ?: 'N/A',
                ucfirst($participant->reg_type ?: 'N/A'),
                ucfirst($participant->gender ?: 'N/A'),
                $participant->dob ? \Carbon\Carbon::parse($participant->dob)->format('Y-m-d') : 'N/A',
                $participant->nationality ?: 'N/A',
                $participant->tshirt_size ?: 'N/A',
                $participant->kit_option ?: 'N/A',
                $participant->address ?: '',
                $participant->thana ?: '',
                $participant->district ?: '',
                $participant->emergency_phone ?: 'N/A',
                $participant->created_at->format('Y-m-d H:i:s'),
                $paymentStatusText,
                number_format($totalPaid, 2),
                number_format($participant->fee ?: 0, 2)
            ];

            // Add additional field values
            foreach ($additionalFieldKeys as $key) {
                $value = 'N/A';
                if ($participant->additional_data && is_array($participant->additional_data) && isset($participant->additional_data[$key])) {
                    $value = $participant->additional_data[$key];
                    // Handle arrays (like multi-select fields)
                    if (is_array($value)) {
                        $value = implode('; ', $value);
                    }
                }
                $row[] = $value;
            }

            $csvData .= implode(',', array_map(function($v) {
                return '"' . str_replace('"', '""', $v) . '"';
            }, $row)) . "\n";
        }

        // Generate descriptive filename
        $filenameParts = ['participants'];

        if ($eventId) {
            $event = Event::find($eventId);
            $eventSlug = $event ? \Str::slug($event->name) : 'event_' . $eventId;
            $filenameParts[] = $eventSlug;
        } else {
            $filenameParts[] = 'all_events';
        }

        if ($eventCategoryId) {
            $filenameParts[] = 'category_' . $eventCategoryId;
        }

        if ($paymentStatus) {
            $filenameParts[] = $paymentStatus;
        }

        $filenameParts[] = date('Y-m-d_H-i-s');
        $filename = implode('_', $filenameParts) . '.csv';

        return response($csvData)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportTransactions(Request $request)
    {
        $eventId = $request->get('event_id');
        $paymentStatus = $request->get('payment_status');

        // Export transactions data as CSV
        $transactionsQuery = Transaction::with(['participant', 'event']);

        if ($eventId) {
            $transactionsQuery->where('event_id', $eventId);
        }

        // Filter by payment status
        if ($paymentStatus) {
            if ($paymentStatus === 'paid') {
                $transactionsQuery->whereIn('status', ['complete', 'Complete']);
            } elseif ($paymentStatus === 'pending') {
                $transactionsQuery->whereIn('status', ['pending', 'Pending']);
            } elseif ($paymentStatus === 'failed') {
                $transactionsQuery->whereIn('status', ['failed', 'Failed']);
            }
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
        $filenameParts = ['transactions'];

        if ($eventId) {
            $event = Event::find($eventId);
            $eventSlug = $event ? \Str::slug($event->name) : 'event_' . $eventId;
            $filenameParts[] = $eventSlug;
        } else {
            $filenameParts[] = 'all_events';
        }

        if ($paymentStatus) {
            $filenameParts[] = $paymentStatus;
        }

        $filenameParts[] = date('Y-m-d_H-i-s');
        $filename = implode('_', $filenameParts) . '.csv';

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function sendConfirmationEmails(Request $request)
    {
        try {
            $participantIds = $request->input('participant_ids', []);
            $sendToAll = $request->input('send_to_all', false);

            // Get filters from request for "send to all" functionality
            $eventId = $request->input('event_id');
            $eventCategoryId = $request->input('event_category_id');
            $paymentStatus = $request->input('payment_status');

            if ($sendToAll) {
                // Build query with same filters as the participants list
                $participantsQuery = Participant::query()
                    ->with(['event', 'transactions'])
                    ->whereHas('transactions', function($query) {
                        $query->whereIn('status', ['complete', 'Complete']);
                    });

                if ($eventId) {
                    $participantsQuery->where('event_id', $eventId);
                }

                if ($eventCategoryId) {
                    $participantsQuery->where('category', $eventCategoryId);
                }

                if ($paymentStatus) {
                    if ($paymentStatus === 'paid') {
                        $participantsQuery->whereHas('transactions', function($query) {
                            $query->whereIn('status', ['complete', 'Complete']);
                        });
                    } elseif ($paymentStatus === 'pending') {
                        // Don't send to pending participants
                        return redirect()->back()->with('error', 'Cannot send confirmation emails to participants with pending payments.');
                    } elseif ($paymentStatus === 'failed') {
                        // Don't send to failed participants
                        return redirect()->back()->with('error', 'Cannot send confirmation emails to participants with failed payments.');
                    }
                }

                $participants = $participantsQuery->get();
            } else {
                // Get selected participants
                $participants = Participant::with(['event', 'transactions'])
                    ->whereIn('id', $participantIds)
                    ->whereHas('transactions', function($query) {
                        $query->whereIn('status', ['complete', 'Complete']);
                    })
                    ->get();
            }

            if ($participants->isEmpty()) {
                return redirect()->back()->with('error', 'No participants with completed payments found.');
            }

            $successCount = 0;
            $failCount = 0;
            $errors = [];

            foreach ($participants as $participant) {
                try {
                    // Get the latest successful transaction for this participant
                    $transaction = $participant->transactions()
                        ->whereIn('status', ['complete', 'Complete'])
                        ->latest()
                        ->first();

                    if ($transaction) {
                        \Mail::to($participant->email)->send(new \App\Mail\PaymentConfirmation($transaction));
                        $successCount++;
                    } else {
                        $failCount++;
                        $errors[] = "No completed transaction found for {$participant->name}";
                    }
                } catch (\Exception $e) {
                    $failCount++;
                    $errors[] = "Failed to send email to {$participant->name} ({$participant->email}): " . $e->getMessage();
                    \Log::error('Failed to send confirmation email', [
                        'participant_id' => $participant->id,
                        'email' => $participant->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Prepare success message
            $message = "Successfully sent {$successCount} confirmation email(s).";
            if ($failCount > 0) {
                $message .= " {$failCount} email(s) failed to send.";
            }

            if (!empty($errors) && $failCount <= 5) {
                // Show specific errors if there are only a few
                $message .= " Errors: " . implode('; ', $errors);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Error in sendConfirmationEmails', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
