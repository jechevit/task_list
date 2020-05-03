<?php

namespace core\repositories;

use Assert\AssertionFailedException;
use core\entities\Task;
use core\entities\TaskUuid;
use Ramsey\Uuid\Uuid;

class TasksRepository implements TaskRepository
{

    public function get(int $id): Task
    {
        if (!$task = Task::findOne($id)) {
            throw new TaskNotFoundException('Task is not found.');
        }
        return $task;
    }

    public function save(Task $task): void
    {
        if (!$task->save()) {
            throw new SaveErrorException('Saving error.');
        }
    }

    public function remove(Task $task): void
    {
        if (!$task->delete()) {
            throw new DeleteErrorException('Removing error.');
        }
    }

    /**
     * @return TaskUuid
     * @throws AssertionFailedException
     */
    public function nextUuid(): TaskUuid
    {
        return new TaskUuid(Uuid::uuid4()->toString());
    }

}