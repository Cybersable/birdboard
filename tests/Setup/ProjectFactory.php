<?php

namespace Tests\Setup;

use App\Models\{User, Project, Task};

class ProjectFactory
{
    protected $tasksCount = 0;
    protected $user = null;

    public function withTasks(int $count)
    {
        $this->tasksCount = $count;
        return $this;
    }

    public function ownedBy($user)
    {
        $this->user = $user;
        return $this;
    }

    public function create()
    {
        $project = Project::factory()->create([
            'owner_id' => $this->user ?? User::factory()
        ]);
        Task::factory($this->tasksCount)->create([
            'project_id' => $project->id
        ]);
        return $project;
    }
}
