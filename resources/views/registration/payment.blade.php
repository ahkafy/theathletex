@extends('layouts.template')
@section('content')


<div class="container mt-4">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Pending Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $transaction->participant->name }}</td>
                <td>{{ $transaction->participant->email }}</td>
                <td>{{ $transaction->participant->phone }}</td>
                <td>{{ $transaction->event->name }}</td>
                <td>{{ $transaction->event->start_time }}</td>
                <td>{{ number_format($transaction->amount) }} BDT</td>
                <td>{{ $transaction->status }} </td>

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
