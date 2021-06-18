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

        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('created', $activity->description);
            $this->assertNull($activity->changes);
        });
    }

    public function test_updating_a_project()
    {
        $project = ProjectFactory::create();
        $originalTitle = $project->title;
        $project->update(['title' => 'Changed']);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) use($originalTitle) {
            $this->assertEquals('updated', $activity->description);

            $expected = [
                'before' => ['title' => $originalTitle],
                'after'  => ['title' => 'Changed']
            ];

            $this->assertEquals($expected, $activity->changes);
        });
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

        $this->assertCount(3, $task->activity);

        tap($task->activity->first(), function ($activity) {
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
