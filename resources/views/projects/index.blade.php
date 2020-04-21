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

        <h1 class="my-4">Projects</h1>
        @auth()
            <a href="{{ route('projects.create') }}" class="btn btn-primary">New Project</a>
            <br /><br />
            <a href="{{ route('projects.check') }}" class="btn btn-primary">Check Projects</a>
            <br /><br />
        @endauth

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
            @forelse($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a></td>
                    <td><a href="{{ $project->url }}">{{ $project->url }}</a></td>
                    <td>{{ $project->status }}</td>
                    <td><a href="{{ route('users.show', $project->user_id) }}">{{ $project->user->name }}</a></td>
                    <td>{{ $project->check_frequency }}</td>
                    <td>{{ $project->last_check }}</td>
                    <td>{{ $project->checked }}</td>
                    @auth
                        <td>
                            <a class="btn btn-primary" href="{{ route('projects.edit', $project->id) }}">Edit</a>

                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: inline">
                                @method('DELETE')
                                @csrf
                                <input type="submit" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure?')" />
                            </form>
                        </td>
                    @endauth
                </tr>
            @empty
                <tr><td>No Projects Found.</td></tr>
            @endforelse
            </thead>
        </table>

    </div>
    <!-- /.col-lg-12 -->
@endsection
