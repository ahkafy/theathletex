@extends('admin.layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Participants List</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name &amp; Contact</th>
                        <th>Event &amp; Reg Type</th>
                        <th>Address</th>
                        <th>Personal Info</th>
                        <th>T-shirt &amp; Kit</th>
                        <th>Payment</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participants as $index => $participant)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $participant->name }}</strong><br>
                                <span>Email: {{ $participant->email }}</span><br>
                                <span>Phone: {{ $participant->phone }}</span><br>
                                <span>Emergency: {{ $participant->emergency_phone }}</span>
                            </td>
                            <td>
                                <span>Event ID: {{ $participant->event_id }}</span><br>
                                <span>Type: {{ $participant->reg_type }}</span>
                            </td>
                            <td>
                                <span>{{ $participant->address }}</span><br>
                                <span>{{ $participant->thana }}, {{ $participant->district }}</span>
                            </td>
                            <td>
                                <span>Gender: {{ ucfirst($participant->gender) }}</span><br>
                                <span>DOB: {{ \Carbon\Carbon::parse($participant->dob)->format('d M Y') }}</span><br>
                                <span>Nationality: {{ $participant->nationality }}</span>
                            </td>
                            <td>
                                <span>Size: {{ $participant->tshirt_size }}</span><br>
                                <span>Kit: {{ ucfirst($participant->kit_option) }}</span>
                            </td>
                            <td>
                                <span>Fee: {{ $participant->fee }}</span><br>
                                <span>Method: {{ strtoupper($participant->payment_method) }}</span><br>
                                <span>Status: {{ ucfirst($participant->payment_status) }}</span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($participant->created_at)->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
