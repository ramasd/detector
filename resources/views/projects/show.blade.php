@extends('layouts.app')

@section('content')
    <div class="col-lg-12">

        <h1 class="my-4">User</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>User</th>
                    <th>Check Frequency</th>
                    <th>Last Check</th>
                    <th>Actions</th>
                </tr>
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>{{ $project->name }}</td>
                    <td><a href="{{ $project->url }}">{{ $project->url }}</a></td>
                    <td>{{ $project->status }}</td>
                    <td><a href="{{ route('users.show', $project->user->id) }}">{{ $project->user->name }}</a></td>
                    <td>{{ $project->check_frequency }}</td>
                    <td>{{ $project->last_check }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('projects.edit', $project->id) }}">Edit</a>

                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: inline">
                            @method('DELETE')
                            @csrf
                            <input type="submit" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure?')" />
                        </form>
                    </td>
                </tr>
            </thead>
        </table>

    </div>
    <!-- /.col-lg-12 -->
@endsection
