@extends('app')

@section('content')

    <section class="content">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit User</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.users.fields')
                </form>
            </div>
        </div>

    </section>

@endsection
