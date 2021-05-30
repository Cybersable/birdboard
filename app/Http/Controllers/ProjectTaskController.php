<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectTaskStoreRequest;
use App\Http\Requests\ProjectTaskUpdateRequest;
use App\Models\Project;
use App\Models\Task;

class ProjectTaskController extends Controller
{
    public function create(Project $project)
    {
        return view('projects.tasks.create', compact('project'));
    }

    public function store(ProjectTaskStoreRequest $request, Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }
        $project->tasks()->create($request->validated());
        return redirect()->route('projects.show', $project);
    }

    public function update(ProjectTaskUpdateRequest $request, Project $project, Task $task)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }
        $task->update($request->validated());
        return redirect()->route('projects.show', $project);
    }
}
