<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class ProjectTasksTest extends TestCase
{
    use InteractsWithDatabase, WithFaker;

    public function test_guest_cant_manage_tasks()
    {
        $task = Task::factory()->create();

        $this->get(route('projects.tasks.create', $task->project))->assertRedirect('/login');
        $this->post(route('projects.tasks.store', ['title' => $task->title, $task->project]))
            ->assertRedirect('/login');
    }

    public function test_only_owner_the_project_can_create_task()
    {
        $this->signIn();

        $project = Project::factory()->create();
        $this->post(route('projects.tasks.store', ['title' => $this->faker->sentence, $project]))
             ->assertStatus(403);
    }

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
