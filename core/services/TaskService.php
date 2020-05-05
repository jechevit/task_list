<?php

namespace core\services;

use Assert\AssertionFailedException;
use core\entities\Priority;
use core\entities\Tag;
use core\entities\Task;
use core\forms\TaskForm;
use core\repositories\TagRepository;
use core\repositories\TasksRepository;

class TaskService
{
    /**
     * @var TasksRepository
     */
    private $taskRepository;
    /**
     * @var TransactionManager
     */
    private $transaction;
    /**
     * @var TagRepository
     */
    private $tags;

    public function __construct(
        TasksRepository $taskRepository,
        TagRepository $tags,
        TransactionManager $transaction
    )
    {
        $this->taskRepository = $taskRepository;
        $this->transaction = $transaction;
        $this->tags = $tags;
    }

    /**
     * @param TaskForm $form
     * @return Task
     * @throws AssertionFailedException
     * @throws \Throwable
     */
    public function create(TaskForm $form): Task
    {
        $uuid = $this->taskRepository->nextUuid();
        $task = Task::create(
            $uuid,
            $form->title,
            new Priority($form->priority)
        );

        $this->transaction->wrap(function () use ($task, $form) {

            foreach ($form->tags->existing as $tagId) {
                $tag = $this->tags->get($tagId);
                $task->assignTag($tag->id);
            }

            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tags->findByName($tagName)) {
                    $tag = Tag::create($tagName);
                    $this->tags->save($tag);
                }
                $task->assignTag($tag->id);
            }
            $this->taskRepository->save($task);
        });

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
     * @throws AssertionFailedException
     */
    public function toLow(int $id): void
    {
        $task = $this->taskRepository->get($id);
        $task->toLow();
        $this->taskRepository->save($task);
    }

    /**
     * @param int $id
     * @throws AssertionFailedException
     */
    public function toMiddle(int $id): void
    {
        $task = $this->taskRepository->get($id);
        $task->toMiddle();
        $this->taskRepository->save($task);
    }

    /**
     * @param int $id
     * @throws AssertionFailedException
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

    /**
     * @param int $taskId
     * @param int $tagId
     */
    public function deleteTag(int $taskId, int $tagId): void
    {
        $task = $this->taskRepository->get($taskId);
        $task->revokeTag($tagId);
        $this->taskRepository->save($task);
    }
}