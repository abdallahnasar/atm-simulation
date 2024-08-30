@extends('app')

@section('content')

    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users</h3>

                <div class="card-tools">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success">Add New User</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                    <tr>
                        <th style="width: 2%">ID</th>
                        <th style="width: 20%">Name</th>
                        <th style="width: 20%">Debit Card Number</th>
                        <th style="width: 20%">Balance</th>
                        <th style="width: 20%">Created At</th>
                        <th>Transactions</th>
                        <th style="width: 20%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->debit_card_number }}</td>
                            <td>{{ $user->balance }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td><a href="{{ route('admin.transactions.index', ['user_id' => $user->id]) }}">Transactions</a></td>
                            <td class="project-actions text-right">
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.users.show', $user->id) }}">
                                    <i class="fas fa-folder"></i> View
                                </a>
                                <a class="btn btn-info btn-sm" href="{{ route('admin.users.edit', $user->id) }}">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal-{{ $user->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="card-footer clearfix">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
            </div>
        @foreach($users as $user)
            <div class="modal fade" id="deleteUserModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete user <strong>{{ $user->name }}</strong>? This will also delete all their transactions.</p>
                            <p class="text-danger">This action is irreversible.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </section>

@endsection
