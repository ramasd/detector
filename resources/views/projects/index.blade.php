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

                @auth()
                    <div class="pt-2 pb-4">
                        <a href="{{ route('projects.create') }}" class="btn btn-primary">New Project</a>
                        @role('admin')
                            <a href="{{ route('projects.check', 'hJ7YF4TnVR30UkR1D8PW') }}" class="btn btn-success">Check Projects</a>
                        @endrole
                    </div>
                @endauth

                <div class="card">
                    <div class="card-header">{{ __('Projects') }}</div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    @if(count($projects))
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>URL</th>
                                            <th>Status</th>
                                            <th>User</th>
                                            <th>Check Frequency</th>
                                            <th>Last Check</th>
                                            <th>Checked</th>
                                            @auth()
                                                <th>Actions</th>
                                            @endauth
                                        </tr>
                                    @endif
                                </thead>
                                <tbody>
                                    @forelse($projects as $project)
                                        <tr>
                                            <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->id }}</a></td>
                                            <td>{{ $project->name }}</td>
                                            <td><a href="{{ $project->url }}">{{ $project->url }}</a></td>
                                            <td>{{ $project->status ? "Active" : "Inactive" }}</td>
                                            @role('admin')
                                            <td><a href="{{ route('users.show', $project->user_id) }}">{{ $project->user->name }}</a></td>
                                            @else
                                            <td>{{ $project->user->name }}</td>
                                            @endrole
                                            <td>{{ $project->check_frequency }}</td>
                                            <td>{{ $project->last_check }}</td>
                                            <td>{{ $project->checked ? "Yes" : "No" }}</td>
                                            @auth
                                                <td>
                                                    <div class="btn-toolbar" style="flex-wrap: nowrap;">
                                                        <div style="margin:0 2px;">
                                                            <a class="btn btn-primary" href="{{ route('projects.edit', $project->id) }}">Edit</a>
                                                        </div>
                                                        <div style="margin: 0 2px;">
                                                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: inline">
                                                                @method('DELETE')
                                                                @csrf
                                                                <input type="submit" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure?')" />
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endauth
                                        </tr>
                                    @empty
                                        <tr><td>No Projects Found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center pt-4">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
