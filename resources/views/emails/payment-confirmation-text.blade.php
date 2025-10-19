Thank you for being with us.

Hi {{ $participant->name }},

Just to let you know — we've received your order #{{ $transaction->id }} ({{ $event->name }}), and it is now being processed:

[Order #{{ $transaction->id }} ({{ strtolower(str_replace(' ', '-', $event->name)) }}-{{ date('dmY', strtotime($transaction->created_at)) }})]

Participant ID: {{ $participant->participant_id }}
Event Category: {{ $participant->category ?? 'N/A' }}
Total: ৳ {{ number_format($transaction->amount, 2) }}

PARTICIPANT INFORMATION:
@if($participant->dob)
Date of Birth: {{ date('d-m-Y', strtotime($participant->dob)) }}
@endif
@if($participant->gender)
Gender: {{ ucfirst($participant->gender) }}
@endif
@if(isset($participant->blood_group) && $participant->blood_group)
Blood Group: {{ $participant->blood_group }}
@endif
@if(isset($participant->nid) && $participant->nid)
NID / passport / BIRTH Certificate No.: {{ $participant->nid }}
@endif
@if($participant->tshirt_size)
T-Shirt Size: {{ $participant->tshirt_size }}
@endif
@if($participant->emergency_phone)
Emergency Contact Number: {{ $participant->emergency_phone }}
@endif

BILLING ADDRESS:
{{ $participant->name }}
{{ $participant->address }}, {{ $participant->thana }}, {{ $participant->district }}, Bangladesh
{{ $participant->phone }}
{{ $participant->email }}

Thank you for being with us.

---
This is an automated email. Please do not reply to this email address.
