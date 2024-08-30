@extends('app')

@section('content')

    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Transactions</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td><a href="{{ route('admin.users.show', $transaction->user->id) }}">{{ $transaction->user->name }}</a></td>
                            <td>{{ $transaction->amount }}</td>
                            <td>{{ $transaction->type }}</td>
                            <td>{{ $transaction->created_at }}</td>
                            <td>
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-info">View</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $transactions->links('pagination::bootstrap-4') }}
            </div>
        </div>
        <!-- /.card -->

    </section>

@endsection
