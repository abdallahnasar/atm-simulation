@extends('app')

@section('content')

    <section class="content">

        <!-- todo: to be changed to select2 -->
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="mb-3" style="margin-top: -32px">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_id"></label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">All Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ (request()->get('user_id') == $user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-0" style="margin-top: 24px">
                    <button type="submit" class="btn btn-primary float-right">Filter</button>
                </div>
            </div>
        </form>

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
                    @forelse($transactions as $transaction)
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
                    @empty
                        <tr>
                            <td colspan="6">No transactions found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $transactions->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
        </section>

@endsection
