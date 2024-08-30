@extends('app')

@section('content')

    <section class="content">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Details</h3>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $transaction->id }}</p>
                <p><strong>User:</strong> {{ $transaction->user->name }}</p>
                <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
                <p><strong>Type:</strong> {{ $transaction->type }}</p>
                <p><strong>Created At:</strong> {{ $transaction->created_at }}</p>
            </div>
        </div>

    </section>

@endsection
