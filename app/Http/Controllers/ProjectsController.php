<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Activity;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{

    public function index(): View
    {
        $projects = auth()->user()->accessibleProjects();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(ProjectStoreRequest $request)
    {
        $project = auth()->user()->projects()->create($request->validated());
        return redirect()->route('projects.show', compact('project'));
    }

    public function update(ProjectUpdateRequest $request)
    {
        return redirect()->route('projects.show', $request->save());
    }

    public function show(Project $project)
    {
        $this->authorize('show', $project);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);
        $project->delete();
        return redirect()->route('projects.index');
    }

}
