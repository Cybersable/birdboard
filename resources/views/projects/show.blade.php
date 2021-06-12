@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="d-flex justify-content-start align-items-center justify-content-between">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        {{--                <li class="breadcrumb-item"><a href="#">Library</a></li>--}}
                        <li class="breadcrumb-item active" aria-current="page">{{ $project->title }}</li>
                    </ol>
                </nav>
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary ml-3">
                    {{ __('Edit Project') }}
                </a>
            </div>
        </div>
        <div class="col-lg-4 d-flex justify-content-end align-items-center">
            <a href="{{ route('projects.create') }}" class="btn btn-primary ml-3">
                {{ __('Invite to Project') }}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-4">
                <h2>
                    {{ __('Tasks') }}
                </h2>
                @forelse($project->tasks as $task)
                    <div class="card mb-3">
                        <div class="card-body">
                            <form
                                action="{{ route('projects.tasks.update', [$project, $task]) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')
                                <div class="d-flex">
                                    <input
                                        type="text"
                                        class="form-control border-0 mr-3 {{ $task->completed ? 'text-black-50' : '' }}"
                                        value="{{ $task->title }}"
                                        name="title"
                                        placeholder="{{ __('Task title') }}"
                                    >
                                    <div class="input-group-text">
                                        <input
                                            type="checkbox"
                                            name="completed"
                                            onchange="this.form.submit()"
                                            {{ $task->completed ? 'checked' : ''}}
                                        >
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>
                        No tasks yet.
                    </p>
                @endforelse
                <div class="card mb-3">
                    <div class="card-body">
                        <form action="{{ route('projects.tasks.store', $project) }}" method="POST">
                            @csrf
                            <input
                                type="text"
                                class="form-control border-0"
                                name="title"
                                placeholder="{{ __('Add new task...') }}">
                        </form>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <h2>
                    General Notes
                </h2>
                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @method('PATCH')
                    @csrf
                    <textarea
                        class="w-100 p-3 mb-3"
                        rows="10"
                        name="notes"
                    >{{ $project->notes }}</textarea>
                    <button class="btn btn-primary">
                        Save
                    </button>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="mb-3">
                @include('projects.card')
            </div>

            @include('projects.activity.card')

        </div>
    </div>
@endsection
