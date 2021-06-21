<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use InteractsWithDatabase;

    public function test_non_owners_cannot_invite_users()
    {
        $project = ProjectFactory::create();
        $user = User::factory()->create();

        $assertInvitationForbidden = function () use ($user, $project) {
            $this->actingAs($user)
                ->post(route('projects.invitations.index', [$project]))
                ->assertStatus(403);
        };

        $assertInvitationForbidden();

        $project->invite($user);

        $assertInvitationForbidden();
    }

    public function test_project_can_invite_a_user()
    {
        $project = ProjectFactory::create();
        $userToInvite = User::factory()->create();

        $this->actingAs($project->owner)
             ->post(route('projects.invitations.store', [ 'email' => $userToInvite->email, $project ]))
             ->assertRedirect(route('projects.show', [$project]));


        $this->assertTrue($project->members->contains($userToInvite));
    }

    public function test_the_email_address_must_be_associated_with_a_valid_birdboard_account()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post(route('projects.invitations.store', [ 'email' => 'notausser@example.com', $project ]))
            ->assertSessionHasErrors([
                'email' => 'The user you are inviting must be have a Birdboard account.'
            ], null, 'invitations');
    }

    public function test_invited_users_may_update_project_details()
    {
        $project = ProjectFactory::create();

        $project->invite($user = User::factory()->create());

        $task = Task::factory()->raw(['project_id' => $project->id]);

        $this->signIn($user);
        $this->post(route('projects.tasks.store', array_merge($task, [$project])));

        $this->assertDatabaseHas('tasks', $task);
    }
}
