@extends('app')

@section('content')

    <section class="content">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create New User</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    @include('admin.users.fields')
                </form>
            </div>
        </div>

    </section>

@endsection
