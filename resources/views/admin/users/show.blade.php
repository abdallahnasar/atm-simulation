@extends('app')

@section('content')

    <section class="content">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Details</h3>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Debit Card Number:</strong> {{ $user->debit_card_number }}</p>
                <p><strong>Balance:</strong> {{ $user->balance }}</p>
                <p><strong>Created At:</strong> {{ $user->created_at }}</p>
            </div>
        </div>

    </section>

@endsection
