<?php

namespace core\services;

use Assert\AssertionFailedException;
use core\entities\Priority;
use core\entities\Task;
use core\forms\TaskForm;
use core\repositories\TasksRepository;

class TaskService
{
    /**
     * @var TasksRepository
     */
    private $taskRepository;

    public function __construct(TasksRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param TaskForm $form
     * @return Task
     * @throws AssertionFailedException
     */
    public function create(TaskForm $form): Task
    {
        $uuid = $this->taskRepository->nextUuid();
        $task = Task::create(
            $uuid,
            $form->title,
            new Priority($form->priority)
        );
        $this->taskRepository->save($task);
        return $task;
    }

    public function update(int $id, TaskForm $form): void
    {
        $task = $this->taskRepository->get($id);
        $task->edit(
            $form->title,
            $form->priority
        );
        $this->taskRepository->save($task);
    }

    /**
     * @param int $id
     */
    public function toLow(int $id): void
    {
        $task = $this->taskRepository->get($id);
        $task->toLow();
        $this->taskRepository->save($task);
    }

    /**
     * @param int $id
     */
    public function toMiddle(int $id): void
    {
        $task = $this->taskRepository->get($id);
        $task->toMiddle();
        $this->taskRepository->save($task);
    }

    /**
     * @param int $id
     */
    public function toHigh(int $id): void
    {
        $task = $this->taskRepository->get($id);
        $task->toHigh();
        $this->taskRepository->save($task);
    }

    /**
     * @param int $id
     */
    public function remove(int $id): void
    {
        $task = $this->taskRepository->get($id);
        $this->taskRepository->remove($task);
    }

    /**
     * @param int $id
     * @throws AssertionFailedException
     */
    public function complete(int $id): void
    {
        $task = $this->taskRepository->get($id);
        $task->complete();
        $this->taskRepository->save($task);
    }
}