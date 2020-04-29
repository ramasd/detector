@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 pt-4">
                <div class="card">
                    <div class="card-header">{{ __('Log') }}</div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Project</th>
                                    <th>Data</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td><a href="{{ route('projects.show', $log->project_id) }}">{{ $log->project->name }}</a></td>
                                    <td>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
