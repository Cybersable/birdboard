<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use App\Models\{Project, Task};

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
        $project = Project::factory()->create();
        $task = Task::factory()->raw(['project_id' => $project->id]);
        $this->post(route('projects.tasks.store', array_merge($task, [$project])))
             ->assertStatus(403);
        $this->assertDatabaseMissing('tasks', $task);
    }

    public function test_a_user_can_create_task_and_view_it()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $task = Task::factory()->raw(['project_id' => $project->id]);

        $this->post(route('projects.tasks.store', array_merge($task, [$project])))
             ->assertRedirect(route('projects.show', $project));
        $this->get(route('projects.show', $project))
             ->assertSee($task['title']);
    }

    public function test_only_project_owner_can_update_a_task()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();
        $task = Task::factory()->raw(['project_id' => $project->id]);

        $this->patch(route('projects.tasks.update', array_merge($task, [$project, $project->tasks[0]])))
             ->assertStatus(403);
        $this->assertDatabaseMissing('tasks', $task);
    }

    public function test_a_user_can_update_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $task = Task::factory()->raw(['project_id' => $project->id]);
        $this->actingAs($project->owner)
             ->patch(route('projects.tasks.update', array_merge($task, [$project, $project->tasks[0]])))
             ->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('tasks', $task);
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
