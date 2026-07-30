<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .details-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .details-item {
            margin-bottom: 12px;
        }
        .details-item:last-child {
            margin-bottom: 0;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
    </style>
</head>
<body>
    <p>Dear {{ $participant->name }},</p>

    <p>Thank you for registering for {{ $event->name }}! We’re excited to have you join us for this incredible run. Below are your registration details:</p>

    <div class="details-box">
        <p class="details-item"><span class="label">Order Number:</span> {{ $participant->participant_id ?? $transaction->id }}</p>
        <p class="details-item"><span class="label">Category:</span> {{ $participant->category ?? 'N/A' }}</p>
        @if(!empty($participant->tshirt_size))
        <p class="details-item"><span class="label">T-Shirt Size:</span> {{ $participant->tshirt_size }}</p>
        @endif
        @if(!empty($participant->gender))
        <p class="details-item"><span class="label">Gender:</span> {{ ucfirst($participant->gender) }}</p>
        @endif
    </div>

    <p>Our team will be in touch soon with further details regarding the event.</p>

    <p>Best regards,</p>
</body>
</html>
