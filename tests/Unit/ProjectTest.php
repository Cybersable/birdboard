<?php

namespace Tests\Unit;

use Database\Factories\UserFactory;
use Facades\Tests\Setup\ProjectFactory;
use App\Models\{Project, Task, User};
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use InteractsWithDatabase, WithFaker;

    public function test_it_has_a_path()
    {
        $project = Project::factory()->create();

        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    public function test_it_belongs_to_an_owner()
    {
        $project = Project::factory()->create();
        $this->assertInstanceOf(User::class, $project->owner);
    }

    public function test_it_can_has_many_tasks()
    {
        $project = Project::factory()->create();
        $this->assertInstanceOf(Collection::class, $project->tasks);
        $task = $project->tasks()->create(Task::factory()->raw());
        $project->load('tasks');
        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    public function test_it_can_invite_a_user()
    {
        $project = ProjectFactory::create();

        $project->invite($user = User::factory()->create());

        $this->assertTrue($project->members->contains($user));
    }
}
