@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h2>Send Test Email</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ url('/testmail') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Recipient Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Send Test Email</button>
    </form>
</div>
@endsection
