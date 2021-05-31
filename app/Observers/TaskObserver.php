<?php

namespace App\Observers;

use App\Models\{Activity, Project, Task};

class TaskObserver
{

    public function created(Task $task)
    {
        $task->project->recordActivity('created_task');
    }

    public function updated(Task $task)
    {
        $task->project->recordActivity('updated_task');
        if ($task->completed)
            $task->project->recordActivity('completed_task');
    }

    public function deleted(Task $task)
    {
        $task->project->recordActivity('deleted_task');
    }

}
