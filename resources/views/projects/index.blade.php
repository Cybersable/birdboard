@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('projects.create') }}" class="btn-link">
            {{ __('New Project') }}
        </a>
    </div>
    <ul>
        @forelse($projects as $project)
            <li>
                <a href="{{ $project->path() }}">
                    {{ $project->title }}
                </a>
            </li>
        @empty
            {{ __('No projects yet') }}
        @endforelse
    </ul>
@endsection
