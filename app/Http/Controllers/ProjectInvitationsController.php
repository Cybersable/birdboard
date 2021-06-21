<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\User;

class ProjectInvitationsController extends Controller
{

    public function index(Project $project)
    {
        $this->authorize('update', $project);
    }

    public function store(ProjectInvitationRequest $request, Project $project)
    {
        $user = User::whereEmail(request('email'))->first();
        $project->invite($user);

        return redirect()->route('projects.show', [ $project ]);
    }
}
