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
                <a href="{{ route('projects.tasks.create', $project) }}" class="btn btn-primary ml-3">
                    {{ __('Add Task') }}
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
                            {{ $task->title }}
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
                            <input type="text" class="form-control" name="title" placeholder="{{ __('Add new task') }}">
                        </form>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <h2>
                    General Notes
                </h2>
                <textarea class="w-100 p-3" rows="10">This is some text within a card body.</textarea>
            </div>
        </div>
        <div class="col-lg-4">
            @include('projects.card')
        </div>
    </div>
@endsection
