<div class="card">
    <div class="card-header">
        {{ __('Logs') }}
    </div>
    <div class="card-body">
        @foreach($project->activity as $activity)
            <div class="d-flex flex-column">
                <div class="">
                    @include("projects.activity.{$activity->description}")
                    <span class="text-secondary"> {{ $activity->created_at->diffForHumans(null, true) }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
