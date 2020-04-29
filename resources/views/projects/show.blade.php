@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 pt-4">
                <div class="card">
                    <div class="card-header">{{ __('Project') }}</div>

                    <div class="card-body">
                        <div class="table-responsive">
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
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $project->id }}</td>
                                        <td>{{ $project->name }}</td>
                                        <td><a href="{{ $project->url }}">{{ $project->url }}</a></td>
                                        <td>{{ $project->status ? "Active" : "Inactive" }}</td>
                                        <td><a href="{{ route('users.show', $project->user->id) }}">{{ $project->user->name }}</a></td>
                                        <td>{{ $project->check_frequency }}</td>
                                        <td>{{ $project->last_check }}</td>
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
