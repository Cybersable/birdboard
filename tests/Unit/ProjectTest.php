<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase, InteractsWithDatabase, WithFaker;

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
}
