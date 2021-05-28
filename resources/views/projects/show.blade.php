@extends('layouts.app')

@section('content')
    <h1>
        {{ $project->title }}
    </h1>
    <p>
        {{ $project->description }}
    </p>
    <p>
        <a href="{{ route('projects.index') }}" class="btn-link">
            {{ __('All Projects') }}
        </a>
    </p>
@endsection
