<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreController;
use App\Models\Project;

class TaskController extends Controller
{
    public function create(Project $project)
    {
        return view('projects.tasks.create', compact('project'));
    }

    public function store(TaskStoreController $request, Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }
        $project->tasks()->create($request->validated());
        return redirect()->route('projects.show', $project);
    }
}
