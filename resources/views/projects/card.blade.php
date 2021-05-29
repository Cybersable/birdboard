<div class="card border-primary">
    <div class="card-header">
        Project
    </div>
    <div class="card-body">
        <h5 class="card-title">{{ $project->title }}</h5>
        <p class="card-text">{{ Illuminate\Support\Str::limit($project->description, 100) }}</p>
        <a href="{{ route('projects.show', $project) }}" class="btn btn-primary">{{ __('Open') }}</a>
    </div>
</div>
