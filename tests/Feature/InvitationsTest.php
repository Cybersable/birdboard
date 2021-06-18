<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use InteractsWithDatabase;

    public function test_a_project_can_invite_a_user()
    {
        $project = ProjectFactory::create();

        $project->invite($user = User::factory()->create());

        $task = Task::factory()->raw(['project_id' => $project->id]);

        $this->signIn($user);
        $this->post(route('projects.tasks.store', array_merge($task, [$project])));

        $this->assertDatabaseHas('tasks', $task);
    }
}
