<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\Project;
use Tests\TestCase;

class TasksTest extends TestCase
{
    public function test_it_belongs_to_a_project()
    {
        $task = Task::factory()->create();
        $this->assertInstanceOf(Project::class, $task->project);
    }
}
