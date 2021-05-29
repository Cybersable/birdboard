@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-3 justify-content-between">
        <span>
            My Projects
        </span>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            {{ __('Add Project') }}
        </a>
    </div>

    <div class="row">
        @forelse($projects as $project)
            <div class="col-12 col-sm-6 col-lg-4 mb-3">
                @include('projects.card')
            </div>
        @empty
            <p>
                {{ __('No projects yet') }}
            </p>
        @endforelse
    </div>
@endsection
