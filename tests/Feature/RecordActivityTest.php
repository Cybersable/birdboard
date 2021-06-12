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

        $task = Task::factory()->raw();
        $project->tasks()->create($task);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) use($task) {
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals($task['title'], $activity->subject->title);
        });
    }

    public function test_completing_a_task()
    {
        $task = Task::factory()->create(['completed' => false]);
        $upd = [
            'title' => $task->title,
            'completed' => true,
            $task->project, $task
        ];

        $this->actingAs($task->project->owner)
             ->patch(route('projects.tasks.update', $upd))
             ->assertRedirect(route('projects.show', $task->project));

        $task->refresh();

        $this->assertCount(3, $task->activity);

        tap($task->activity->last(), function ($activity) {
            $this->assertEquals('completed_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    public function test_deleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $project->tasks[0]->delete();
        $this->assertCount(3, $project->activity);
        $this->assertEquals('deleted_task', $project->activity->last()->description);
    }
}
