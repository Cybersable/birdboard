<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, InteractsWithDatabase;

    public function test_guests_cannot_manage_projects()
    {
        $project = Project::factory()->create();

        $this->get('/projects')->assertRedirect('/login');
        $this->get('/projects/create')->assertRedirect('/login');
        $this->get($project->path())->assertRedirect('/login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    public function test_a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $attributes = [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'notes' => 'General notes',
            'owner_id' => auth()->id()
        ];

        $response = $this->post(route('projects.store', $attributes));
        $project = Project::where($attributes)->first();

        $response->assertRedirect(route('projects.show', $project));
        $this->assertDatabaseHas('projects', $attributes);
        $this->get(route('projects.show', $project))
            ->assertSee($attributes['title'])
            ->assertSee($attributes['notes'])
            ->assertSee($attributes['description']);
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
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $attr = Project::factory()->raw(['owner_id' => auth()->id()]);
        $this->patch(route('projects.update', array_merge($attr, [$project])))
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
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->get(route('projects.show', $project))->assertStatus(200);
    }

    public function test_an_auth_users_cant_view_others_projects()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => User::factory()->create()->id]);
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
