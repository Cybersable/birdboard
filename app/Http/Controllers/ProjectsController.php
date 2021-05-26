<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectsStoreRequest;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{

    public function index(): View
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        //
    }

    public function store(ProjectsStoreRequest $request)
    {
        Project::create($request->validated());
        return redirect('/projects');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
