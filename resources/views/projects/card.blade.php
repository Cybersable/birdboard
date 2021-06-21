<div class="card border-primary">
    <div class="card-header">
        Project
    </div>
    <div class="card-body">
        <h5 class="card-title">{{ $project->title }}</h5>
        <p class="card-text">{{ Illuminate\Support\Str::limit($project->description, 100) }}</p>
        <a href="{{ route('projects.show', $project) }}" class="btn btn-primary">{{ __('Open') }}</a>
    </div>

    @can('manage', $project)
        <div class="card-footer">
            <form method="POST" action="{{ route('projects.destroy', $project) }}" class="text-right w-100">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    @endcan
</div>
