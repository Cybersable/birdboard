<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Tests\TestCase;
use App\Models\Task;

class ProjectTasksTest extends TestCase
{
    use InteractsWithDatabase;

    public function test_a_user_can_create_task_and_view_it()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $task = Task::factory()->make(['project_id' => Project::factory()->create(['owner_id' => auth()->id()])]);
        $this->post(route('projects.tasks.store', ['title' => $task->title, $task->project]))
             ->assertRedirect(route('projects.show', $task->project));
        $this->get(route('projects.show', $task->project))
             ->assertSee($task->title);
    }

    public function test_a_task_requires_a_title()
    {
        $this->signIn();
        $task = Task::factory()->make(['title' => '']);
        $this->post(route('projects.tasks.store', ['title' => $task->title, $task->project]))
             ->assertSessionHasErrors('title');
    }

//    public function test_a_task_require_a_project()
//    {
//        $task = Task::factory()->raw();
//        $this->post(route('projects.tasks.store', $task))
//             ->assertSessionHasErrors('project');
//    }
}
