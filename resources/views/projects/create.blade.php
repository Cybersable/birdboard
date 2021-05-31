@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('projects.store') }}" method="POST">
                            @csrf
                            <div class="card-header">{{ __('New Project') }}</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="title">{{ __('Title') }}</label>
                                            <input
                                                value="{{ old('title') }}"
                                                class="form-control"
                                                name="title"
                                                type="text"
                                                placeholder="{{ __('Project title') }}"
                                                required
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label for="description">{{ __('Description') }}</label>
                                            <input
                                                value="{{ old('description') }}"
                                                class="form-control"
                                                name="description"
                                                type="text"
                                                placeholder="{{ __('Project description') }}"
                                                required
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-primary" type="submit">
                                    {{ __('Save') }}
                                </button>
                                <a href="{{ route('projects.index') }}" class="btn btn-link">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
