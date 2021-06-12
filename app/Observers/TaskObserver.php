<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{

    public function created(Task $task)
    {
        $task->recordActivity('created_task');
    }

    public function updated(Task $task)
    {
        $task->recordActivity('updated_task');
        if ($task->wasChanged(['completed'])) {
            $task->recordActivity($task->completed ? 'completed_task' : 'incompleted_task');
        }
    }

    public function updating(Task $task)
    {
//        if ($task->isDirty(['completed'])) {
//            $task->recordActivity($task->completed ? 'completed_task' : 'incompleted_task');
//        }
    }

    public function deleted(Task $task)
    {
        $task->recordActivity('deleted_task');
    }

}
