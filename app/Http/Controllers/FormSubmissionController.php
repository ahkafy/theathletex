<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormResponse;
use App\Models\FormTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormSubmissionController extends Controller
{
    /**
     * Display the public form.
     */
    public function show($slug)
    {
        $form = Form::where('slug', $slug)->where('is_active', true)->with('fields')->firstOrFail();
        return view('forms.show', compact('form'));
    }

    /**
     * Handle form submission.
     */
    public function submit(Request $request, $slug)
    {
        $form = Form::where('slug', $slug)->where('is_active', true)->with('fields')->firstOrFail();

        // Build validation rules
        $rules = [
            'respondent_name'  => 'required|string|max:255',
            'respondent_email' => 'required|email|max:255',
            'respondent_phone' => 'nullable|string|max:30',
        ];

        foreach ($form->fields as $field) {
            $fieldKey = 'field_' . $field->id;
            $rule = [];

            if ($field->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            if ($field->field_type === 'email') {
                $rule[] = 'email';
            } elseif ($field->field_type === 'number') {
                $rule[] = 'numeric';
            } elseif ($field->field_type === 'date') {
                $rule[] = 'date';
            } elseif ($field->field_type === 'checkbox') {
                $rule[] = 'array';
            }

            $rules[$fieldKey] = implode('|', $rule);
        }

        $request->validate($rules);

        // Collect field responses
        $responseData = [];
        foreach ($form->fields as $field) {
            $responseData[$field->id] = $request->input('field_' . $field->id);
        }

        // Determine payment status
        $paymentStatus = $form->payment_required ? 'pending' : 'not_required';

        // Save response
        $response = FormResponse::create([
            'form_id'           => $form->id,
            'respondent_name'   => $request->respondent_name,
            'respondent_email'  => $request->respondent_email,
            'respondent_phone'  => $request->respondent_phone,
            'response_data'     => $responseData,
            'payment_status'    => $paymentStatus,
        ]);

        if ($form->payment_required) {
            // Create a pending form_transaction record
            $sslTranId = 'FRM-' . $form->id . '-' . $response->id . '-' . time();

            FormTransaction::create([
                'form_id'          => $form->id,
                'form_response_id' => $response->id,
                'ssl_tran_id'      => $sslTranId,
                'amount'           => $form->payment_amount,
                'currency'         => $form->payment_currency ?: 'BDT',
                'status'           => 'pending',
            ]);

            // Redirect to payment initiation page
            return redirect()->route('form.payment.pay', [
                'slug'       => $form->slug,
                'responseId' => $response->id,
            ]);
        }

        // No payment — show thank-you
        return redirect()->route('form.thankyou', ['slug' => $form->slug])
            ->with('success', 'Your response has been submitted successfully!');
    }

    /**
     * Thank-you page after successful (no-payment) submission.
     */
    public function thankYou($slug)
    {
        $form = Form::where('slug', $slug)->firstOrFail();
        return view('forms.thank-you', compact('form'));
    }
}
