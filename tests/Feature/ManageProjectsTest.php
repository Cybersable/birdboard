<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, InteractsWithDatabase;

    public function test_guests_cannot_manage_projects()
    {
        $project = Project::factory()->create();

        $this->get('/projects')->assertRedirect('/login');
        $this->get('/projects/create')->assertRedirect('/login');
        $this->get('/projects/edit')->assertRedirect('/login');
        $this->get($project->path())->assertRedirect('/login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    public function test_a_user_can_create_a_project()
    {
        $this->signIn();
        $attr = Project::factory()->raw(['owner_id' => auth()->id()]);

        $response = $this->post(route('projects.store', $attr));

        $project = Project::where($attr)->first();
        $response->assertRedirect(route('projects.show', $project));

        $this->get(route('projects.show', $project))
            ->assertSee($attr['title'])
            ->assertSee($attr['notes'])
            ->assertSee($attr['description']);
    }

    public function test_unauthorized_cannot_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->delete(route('projects.destroy', $project))
             ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('projects.destroy', $project))
             ->assertStatus(403);
    }

    public function test_a_user_can_see_all_projects_they_have_been_invited_to_their_dashboard()
    {
        $project = tap(ProjectFactory::create())->invite($this->signIn());

        $this->get(route('projects.index'))
             ->assertSee($project->title);
    }

    public function test_a_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete(route('projects.destroy', $project))
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    public function test_only_project_owner_can_update_a_project()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $attr = Project::factory()->raw();
        $this->patch(route('projects.update', array_merge($attr, [$project])))
             ->assertStatus(403);
        $this->assertDatabaseMissing('projects', $attr);
    }

    public function test_a_user_can_update_a_project()
    {
        $project = ProjectFactory::create();
        $attr = Project::factory()->raw(['owner_id' => $project->owner]);
        $this->actingAs($project->owner)
             ->patch(route('projects.update', array_merge($attr, [$project])))
             ->assertRedirect(route('projects.show', $project));
        $this->assertDatabaseHas('projects', $attr);
        $this->get(route('projects.show', $project))
             ->assertSee([
                $attr['title'],
                $attr['notes'],
                $attr['description']
             ]);
    }

    public function test_a_user_can_view_their_project()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();
        $this
            ->actingAs($project->owner)
            ->get(route('projects.show', $project))->assertStatus(200);
    }

    public function test_an_auth_users_cant_view_others_projects()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->get(route('projects.show', $project))->assertStatus(403);
    }

    public function test_a_project_requires_a_title()
    {
        $this->signIn();
        $attributes = Project::factory()->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_a_project_requires_a_description()
    {
        $this->signIn();
        $attributes = Project::factory()->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
