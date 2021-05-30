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

    public function test_only_project_owner_can_create_a_task()
    {
        $this->signIn();
        $task = Task::factory()->make();
        $attr = [
            'title' => $task->title,
            $task->project
        ];
        $this->post(route('projects.tasks.store', $attr))
             ->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['title' => $attr['title']]);
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

    public function test_only_project_owner_can_update_a_task()
    {
        $this->signIn();
        $task = Task::factory()->create();
        $attr = [
            'title' => $this->faker->sentence,
            'completed' => !$task->completed,
            $task->project,
            $task
        ];
        $this->patch(route('projects.tasks.update', $attr))
             ->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['title' => $attr['title'], 'completed' => $attr['completed']]);
    }

    public function test_a_user_can_update_task()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $task = Task::factory()->create(['project_id' => Project::factory()->create(['owner_id' => auth()->id()])]);
        $attributes = [
            'title' => $this->faker->sentence,
            'completed' => $task->completed ? null : true,
            $task->project,
            $task
        ];
        $this->patch(route('projects.tasks.update', $attributes))
             ->assertRedirect(route('projects.show', $task->project));
        $this->assertDatabaseHas('tasks', [
            'title' => $attributes['title'],
            'completed' => $task->completed ? false : true
        ]);
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
