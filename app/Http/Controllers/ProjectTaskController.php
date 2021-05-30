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
        $this->authorize('update', $project);
        return view('projects.tasks.create', compact('project'));
    }

    public function store(ProjectTaskStoreRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        $project->tasks()->create($request->validated());
        return redirect()->route('projects.show', $project);
    }

    public function update(ProjectTaskUpdateRequest $request, Project $project, Task $task)
    {
        $this->authorize('update', $project);
        $task->update($request->validated());
        return redirect()->route('projects.show', $project);
    }
}
