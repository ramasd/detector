@extends('layouts.app')

@section('content')
    <div class="col-lg-12">
        @if (session('success'))
            <div class="alert  alert-success alert-dismissible fade show" style="margin-top: 10px;" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <h1 class="my-4">Users</h1>

        <a href="{{ route('users.create') }}" class="btn btn-primary">New User</a>
        <br /><br />

        <table class="table">
            <thead>
            @if(count($users))
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Comment</th>
                    <th>Actions</th>
                </tr>
            @endif
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->status }}</td>
                    <td>{{ $user->comment }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}">Edit</a>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline">
                            @method('DELETE')
                            @csrf
                            <input type="submit" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure?')" />
                        </form>

                        @if($user->id !== auth()->id())
                            <a class="btn btn-warning" href="{{ route('users.loginAs', $user->id) }}">Login As</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td>No Users Found.</td></tr>
            @endforelse
            </thead>
        </table>

    </div>
    <!-- /.col-lg-12 -->
@endsection
