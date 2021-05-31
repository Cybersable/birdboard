<?php

namespace Tests\Feature;

use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class RecordActivityTest extends TestCase
{
    use InteractsWithDatabase;

    public function test_creating_a_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
    }

    public function test_updating_a_project()
    {
        $project = ProjectFactory::create();
        $project->update(['title' => 'Changed']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated', $project->activity->last()->description);
    }

    public function test_creating_a_task()
    {
        $project = ProjectFactory::create();

        $project->tasks()->create(Task::factory()->raw());

        $this->assertCount(2, $project->activity);
        $this->assertEquals('created_task', $project->activity->last()->description);
    }

    public function test_completing_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $attr = [
            'title' => $project->tasks->first(),
            'completed' => true,
            $project,
            $project->tasks->first()
        ];
        $this->actingAs($project->owner)
             ->patch(route('projects.tasks.update', $attr));


        $this->assertCount(4, $project->activity);
        $this->assertEquals('completed_task', $project->activity->last()->description);
    }

    public function test_deleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $project->tasks[0]->delete();
        $this->assertCount(3, $project->activity);
        $this->assertEquals('deleted_task', $project->activity->last()->description);
    }
}
