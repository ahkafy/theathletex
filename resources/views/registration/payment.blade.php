@extends('layouts.template')
@section('content')


<div class="container mt-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>Trx ID</th>
                <td><b>{{ $transaction->id }}</b></td>
            </tr>
            <tr>
                <th>User Name</th>
                <td>{{ $transaction->participant->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $transaction->participant->email }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $transaction->participant->phone }}</td>
            </tr>
            <tr>
                <th>Event Name</th>
                <td>{{ $transaction->event->name }}</td>
            </tr>
            <tr>
                <th>Event Date</th>
                <td>{{ $transaction->event->start_time }}</td>
            </tr>
            <tr>
                <th>Pending Amount</th>
                <td>{{ number_format($transaction->amount) }} BDT</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $transaction->status }}</td>
            </tr>
            <tr>
                <th>Action</th>
                <td>
                    @if($transaction->status !== 'Complete')
                        <form action="{{ url('pay') }}" method="POST">
                            @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                            <input type="hidden" name="name" value="{{ $transaction->participant->name }}">
                            <input type="hidden" name="email" value="{{ $transaction->participant->email }}">
                            <input type="hidden" name="phone" value="{{ $transaction->participant->phone }}">
                            <input type="hidden" name="address" value="{{ $transaction->participant->address }}">
                            <button type="submit" class="btn btn-primary">Pay Now</button>
                        </form>
                    @else
                        <span class="text-success">Payment Successful</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

</div>













@endsection
