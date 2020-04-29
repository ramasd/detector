@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 pt-4">
                <div class="card">
                    <div class="card-header">{{ __('User') }}</div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Comment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $user->id }}</td>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
