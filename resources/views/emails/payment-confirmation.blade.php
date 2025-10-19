<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .order-details {
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .participant-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .billing-address {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        h3 {
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Thank you for being with us.</h2>
    </div>

    <p>Hi {{ $participant->name }},</p>

    <p>Just to let you know — we've received your order #{{ $transaction->id }} ({{ $event->name }}), and it is now being processed:</p>

    <div class="order-details">
        <h3>[Order #{{ $transaction->id }} ({{ strtolower(str_replace(' ', '-', $event->name)) }}-{{ date('dmY', strtotime($transaction->created_at)) }})]</h3>

        <p><strong>Participant ID:</strong> <span style="color: #007bff; font-weight: bold; font-size: 16px;">{{ $participant->participant_id }}</span></p>
        <p><strong>Event Category:</strong> {{ $participant->category ?? 'N/A' }}</p>
        <p class="total-amount"><strong>Total: ৳ {{ number_format($transaction->amount, 2) }}</strong></p>
    </div>

    <div class="participant-info">
        <h3>Participant Information</h3>
        @if($participant->dob)
        <p><strong>Date of Birth:</strong> {{ date('d-m-Y', strtotime($participant->dob)) }}</p>
        @endif
        @if($participant->gender)
        <p><strong>Gender:</strong> {{ ucfirst($participant->gender) }}</p>
        @endif
        @if(isset($participant->blood_group) && $participant->blood_group)
        <p><strong>Blood Group:</strong> {{ $participant->blood_group }}</p>
        @endif
        @if(isset($participant->nid) && $participant->nid)
        <p><strong>NID / passport / BIRTH Certificate No.:</strong> {{ $participant->nid }}</p>
        @endif
        @if($participant->tshirt_size)
        <p><strong>T-Shirt Size:</strong> {{ $participant->tshirt_size }}</p>
        @endif
        @if($participant->emergency_phone)
        <p><strong>Emergency Contact Number:</strong> {{ $participant->emergency_phone }}</p>
        @endif
    </div>

    <div class="billing-address">
        <h3>Billing Address</h3>
        <p>{{ $participant->name }}</p>
        <p>{{ $participant->address }}, {{ $participant->thana }}, {{ $participant->district }}, Bangladesh</p>
        <p>{{ $participant->phone }}</p>
        <p>{{ $participant->email }}</p>
    </div>

    <p><strong>Thank you for being with us.</strong></p>

    <hr>
    <p style="font-size: 12px; color: #666;">
        This is an automated email. Please do not reply to this email address.
    </p>
</body>
</html>
