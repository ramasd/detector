@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit Log') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('logs.update', $log->id) }}">
                            @method('PUT')
                            @csrf

                            <div class="form-group row">
                                <label for="project" class="col-md-4 col-form-label text-md-right">{{ __('Project') }}</label>

                                <div class="col-md-6">
                                    <select name="project_id">
                                        @if(count($projects))
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" @if($project->id == $log->project->id) selected @endif>{{ $project->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Data') }}</label>

                                <div class="col-md-6">
                                    <input id="data" type="text" class="form-control @error('data') is-invalid @enderror" name="data" value="{{ $log->data }}" autocomplete="data">

                                    @error('url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
