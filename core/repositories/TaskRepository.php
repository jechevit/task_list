<?php


namespace core\repositories;


use core\entities\Task;

interface TaskRepository
{
    public function get(int $id): Task;
    public function save(Task $task): void;
    public function remove(Task $task): void;
}