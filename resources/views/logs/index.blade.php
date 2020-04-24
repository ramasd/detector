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

        <h1 class="my-4">Logs</h1>

        <table class="table">
            <thead>
            @if(count($logs))
                <tr>
                    <th>ID</th>
                    <th>Project</th>
                    <th>Data</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            @endif
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td><a href="{{ route('projects.show', $log->project_id) }}">{{ $log->project->name }}</a></td>
                    <td>{{ $log->data }}</td>
                    <td>{{ $log->created_at }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('logs.edit', $log->id) }}">Edit</a>

                        <form action="{{ route('logs.destroy', $log->id) }}" method="POST" style="display: inline">
                            @method('DELETE')
                            @csrf
                            <input type="submit" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure?')" />
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td>No Logs Found.</td></tr>
            @endforelse
            </thead>
        </table>

    </div>
    <!-- /.col-lg-12 -->
@endsection
