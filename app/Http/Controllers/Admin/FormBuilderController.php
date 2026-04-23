<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin']);
    }

    public function index()
    {
        $forms = Form::withCount(['fields', 'responses'])->orderByDesc('created_at')->get();
        return view('admin.forms.index', compact('forms'));
    }

    public function create()
    {
        return view('admin.forms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'is_active'        => 'nullable|boolean',
            'payment_required' => 'nullable|boolean',
            'payment_amount'   => 'nullable|numeric|min:0',
            'payment_currency' => 'nullable|string|max:10',
            'fields'           => 'nullable|array',
            'fields.*.label'      => 'required_with:fields|string|max:255',
            'fields.*.field_type' => 'required_with:fields|string',
            'fields.*.placeholder' => 'nullable|string|max:255',
            'fields.*.options'     => 'nullable|string',
            'fields.*.is_required' => 'nullable|boolean',
        ]);

        // Generate unique slug
        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $i = 1;
        while (Form::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        $paymentRequired = $request->boolean('payment_required');

        $form = Form::create([
            'title'            => $request->title,
            'slug'             => $slug,
            'description'      => $request->description,
            'is_active'        => $request->boolean('is_active', true),
            'payment_required' => $paymentRequired,
            'payment_amount'   => $paymentRequired ? $request->payment_amount : null,
            'payment_currency' => $request->payment_currency ?: 'BDT',
        ]);

        $this->syncFields($form, $request->input('fields', []));

        return redirect()->route('admin.forms.index')->with('success', 'Form created successfully.');
    }

    public function edit(Form $form)
    {
        $form->load('fields');
        return view('admin.forms.edit', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'is_active'        => 'nullable|boolean',
            'payment_required' => 'nullable|boolean',
            'payment_amount'   => 'nullable|numeric|min:0',
            'payment_currency' => 'nullable|string|max:10',
            'fields'           => 'nullable|array',
            'fields.*.label'      => 'required_with:fields|string|max:255',
            'fields.*.field_type' => 'required_with:fields|string',
            'fields.*.placeholder' => 'nullable|string|max:255',
            'fields.*.options'     => 'nullable|string',
            'fields.*.is_required' => 'nullable|boolean',
        ]);

        $paymentRequired = $request->boolean('payment_required');

        $form->update([
            'title'            => $request->title,
            'description'      => $request->description,
            'is_active'        => $request->boolean('is_active', true),
            'payment_required' => $paymentRequired,
            'payment_amount'   => $paymentRequired ? $request->payment_amount : null,
            'payment_currency' => $request->payment_currency ?: 'BDT',
        ]);

        // Delete old fields and recreate
        $form->fields()->delete();
        $this->syncFields($form, $request->input('fields', []));

        return redirect()->route('admin.forms.index')->with('success', 'Form updated successfully.');
    }

    public function destroy(Form $form)
    {
        $form->delete();
        return redirect()->route('admin.forms.index')->with('success', 'Form deleted successfully.');
    }

    public function responses(Request $request, Form $form)
    {
        $form->load('fields');

        $query = FormResponse::where('form_id', $form->id)
            ->with('transaction')
            ->orderByDesc('created_at');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('respondent_name', 'like', "%$search%")
                  ->orWhere('respondent_email', 'like', "%$search%")
                  ->orWhere('respondent_phone', 'like', "%$search%");
            });
        }

        // Payment filter
        if ($status = $request->get('payment_status')) {
            $query->where('payment_status', $status);
        }

        // Date range
        if ($from = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $responses = $query->paginate(20)->withQueryString();

        $stats = [
            'total'        => FormResponse::where('form_id', $form->id)->count(),
            'paid'         => FormResponse::where('form_id', $form->id)->where('payment_status', 'complete')->count(),
            'pending'      => FormResponse::where('form_id', $form->id)->where('payment_status', 'pending')->count(),
            'not_required' => FormResponse::where('form_id', $form->id)->where('payment_status', 'not_required')->count(),
        ];

        return view('admin.forms.responses', compact('form', 'responses', 'stats'));
    }

    public function exportResponses(Form $form)
    {
        $form->load('fields');
        $responses = FormResponse::where('form_id', $form->id)->with('transaction')->get();

        $headers = ['#', 'Name', 'Email', 'Phone', 'Submitted At', 'Payment Status'];

        if ($form->payment_required) {
            array_push($headers, 'Amount', 'Currency', 'Payment Method', 'Bank TXN ID');
        }

        foreach ($form->fields as $field) {
            $headers[] = $field->label;
        }

        $csv = implode(',', array_map(fn($h) => '"' . $h . '"', $headers)) . "\n";

        foreach ($responses as $i => $response) {
            $row = [
                $i + 1,
                $response->respondent_name,
                $response->respondent_email,
                $response->respondent_phone ?? '',
                $response->created_at->format('Y-m-d H:i:s'),
                ucfirst($response->payment_status),
            ];

            if ($form->payment_required) {
                $trx = $response->transaction;
                $row[] = $trx ? $trx->amount : '';
                $row[] = $trx ? $trx->currency : '';
                $row[] = $trx ? ($trx->payment_method ?? '') : '';
                $row[] = $trx ? ($trx->bank_tran_id ?? '') : '';
            }

            foreach ($form->fields as $field) {
                $val = $response->response_data[$field->id] ?? '';
                if (is_array($val)) $val = implode('; ', $val);
                $row[] = $val;
            }

            $csv .= implode(',', array_map(fn($v) => '"' . str_replace('"', '""', $v) . '"', $row)) . "\n";
        }

        $filename = 'form_responses_' . $form->slug . '_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // -------------------------------------------------------
    private function syncFields(Form $form, array $fields): void
    {
        foreach ($fields as $order => $field) {
            if (empty($field['label'])) continue;

            $options = null;
            if (!empty($field['options']) && in_array($field['field_type'], ['select', 'radio', 'checkbox'])) {
                $options = array_values(array_filter(array_map('trim', explode(',', $field['options']))));
            }

            FormField::create([
                'form_id'    => $form->id,
                'label'      => $field['label'],
                'field_type' => $field['field_type'],
                'placeholder' => $field['placeholder'] ?? null,
                'options'    => $options,
                'is_required' => isset($field['is_required']) && $field['is_required'] ? true : false,
                'sort_order' => $order,
            ]);
        }
    }
}
