<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use App\Models\User;

class ActivityTest extends TestCase
{
    use InteractsWithDatabase;

    public function test_it_has_a_user()
    {
        $user = $this->signIn();

        $project = ProjectFactory::ownedBy($user)->create();

        $this->assertEquals($user->id, $project->activity->first()->user->id);
    }
}
