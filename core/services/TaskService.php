<?php

namespace core\services;

use Assert\AssertionFailedException;
use core\entities\Priority;
use core\entities\Tag;
use core\entities\Task;
use core\forms\TaskForm;
use core\repositories\TagRepository;
use core\repositories\TasksRepository;
use Throwable;

/**
 * Class TaskService
 * @package core\services
 */
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
     * @throws Throwable
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
                $this->assignTag($task, $tagId);
            }

            foreach ($form->tags->newNames as $tagName) {
               $this->workWithTag($task, $tagName);
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

    /**
     * @param int $id
     * @param string $tagName
     * @param array|null $tagsId
     * @throws Throwable
     */
    public function addTag(int $id, string $tagName = null, array $tagsId = null): void
    {
        $task = $this->taskRepository->get($id);

        if ($tagName){
            $this->transaction->wrap(function () use ($task, $tagName) {
                $this->workWithTag($task, $tagName);

            });
        }

        if (isset($tagsId)){
            $this->transaction->wrap(function () use ($task, $tagsId) {
                foreach ($tagsId as $tagId){
                    $this->assignTag($task, $tagId);
                }
            });
        }
        $this->taskRepository->save($task);
    }

    /**
     * @param Task $task
     * @param string $tagName
     */
    private function workWithTag(Task $task, string $tagName): void
    {
        if (!$tag = $this->tags->findByName($tagName)) {
            $tag = Tag::create($tagName);
            $this->tags->save($tag);
        }
        $task->assignTag($tag->id);
    }

    /**
     * @param Task $task
     * @param int $tagId
     */
    private function assignTag(Task $task, int $tagId): void
    {
        $tag = $this->tags->get($tagId);
        $task->assignTag($tag->id);
    }
}