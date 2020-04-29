@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @if (session('success'))
                    <div class="alert  alert-success alert-dismissible fade show" style="margin-top: 10px;" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="pt-2 pb-4">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">New User</a>
                </div>
                <div class="card">
                    <div class="card-header">{{ __('Users') }}</div>

                    <div class="card-body">
                        <div class="table-responsive">
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
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td><a href="{{ route('users.show', $user->id) }}">{{ $user->id }}</a></td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->status ? "Active" : "Inactive" }}</td>
                                            <td>{{ $user->comment }}</td>
                                            <td>
                                                <div class="btn-toolbar" style="flex-wrap: nowrap;">
                                                    <div style="margin:0 2px;">
                                                        <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                                    </div>
                                                    <div style="margin: 0 2px;">
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline">
                                                            @method('DELETE')
                                                            @csrf
                                                            <input type="submit" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure?')" />
                                                        </form>
                                                    </div>
                                                    @if($user->id !== auth()->id())
                                                        <div style="margin: 0 2px;">
                                                            <a class="btn btn-warning" href="{{ route('users.loginAs', $user->id) }}">Login to user</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td>No Users Found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center pt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
