@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 pt-4">
                @if (session('success'))
                    <div class="alert  alert-success alert-dismissible fade show" style="margin-top: 10px;" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">{{ __('Logs') }}</div>

                    <div class="card-body">
                        <div class="table-responsive">
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
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                        <tr>
                                            <td><a href="{{ route('logs.show', $log->id) }}">{{ $log->id }}</a></td>
                                            <td><a href="{{ route('projects.show', $log->project_id) }}">{{ $log->project->name }}</a></td>
                                            <td>
                                                @if($log->data)
                                                    @foreach($log->data as $key => $value)
                                                        <b>{{ $key }}</b> =
                                                        @if ($value === true)
                                                            {{ "true" }}
                                                        @elseif ($value === false)
                                                            {{ "false" }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                        <br />
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ $log->created_at }}</td>
                                            <td>
                                                <div class="btn-toolbar" style="flex-wrap: nowrap;">
                                                    <div style="margin:0 2px;">
                                                        <a class="btn btn-primary" href="{{ route('logs.edit', $log->id) }}">Edit</a>
                                                    </div>
                                                    <div style="margin: 0 2px;">
                                                        <form action="{{ route('logs.destroy', $log->id) }}" method="POST" style="display: inline">
                                                            @method('DELETE')
                                                            @csrf
                                                            <input type="submit" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure?')" />
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td>No Logs Found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center pt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
