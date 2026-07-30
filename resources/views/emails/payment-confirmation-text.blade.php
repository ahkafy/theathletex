Dear {{ $participant->name }},

Thank you for registering for {{ $event->name }}! We’re excited to have you join us for this incredible run. Below are your registration details:

Order Number: {{ $participant->participant_id ?? $transaction->id }}

Category: {{ $participant->category ?? 'N/A' }}

@if(!empty($participant->tshirt_size))
T-Shirt Size: {{ $participant->tshirt_size }}

@endif
@if(!empty($participant->gender))
Gender: {{ ucfirst($participant->gender) }}

@endif

Our team will be in touch soon with further details regarding the event.

Best regards,
