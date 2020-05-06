<?php

use core\databases\Column;
use core\databases\Table;
use core\helpers\PriorityHelper;
use core\helpers\StatusHelper;
use core\repositories\TasksRepository;
use yii\db\Migration;

/**
 * Class m200506_133513_insert_data
 */
class m200506_133513_insert_data extends Migration
{
    /**
     * @var TasksRepository
     */
    private $taskRepository;

    /**
     * m200506_133513_insert_data constructor.
     * @param TasksRepository $taskRepository
     * @param array $config
     */
    public function __construct(
        TasksRepository $taskRepository,
        $config = [])
    {
        parent::__construct($config);
        $this->taskRepository = $taskRepository;
    }


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(Table::TASKS, Column::STATUSES);
        $this->execute("SET foreign_key_checks = 0;");
        $this->truncateTable(Table::TAGS_ASSIGNMENTS);
        $this->truncateTable(Table::TAGS);
        $this->truncateTable(Table::TASKS);

        $id = 1;
        $this->batchInsert(
            Table::TASKS,
            [Column::ID, Column::UUID, Column::TITLE, Column::PRIORITY, Column::CURRENT_STATUS, Column::CREATED_AT, Column::UPDATED_AT],
            [
                [$id++, $this->taskRepository->nextUuid()->getUuid(), 'Добавить задачи', array_rand(PriorityHelper::priorityList()), array_rand(StatusHelper::statusesList()), time(), null],
                [$id++, $this->taskRepository->nextUuid()->getUuid(), 'Изменить приоритет', array_rand(PriorityHelper::priorityList()), array_rand(StatusHelper::statusesList()), time(), null],
                [$id++, $this->taskRepository->nextUuid()->getUuid(), 'Изменить статус', array_rand(PriorityHelper::priorityList()), array_rand(StatusHelper::statusesList()), time(), null],
                [$id++, $this->taskRepository->nextUuid()->getUuid(), 'Добавить тег', array_rand(PriorityHelper::priorityList()), array_rand(StatusHelper::statusesList()), time(), null],
            ]
        );
        $id1 = 1;
        $this->batchInsert(
            Table::TAGS,
            [Column::ID, Column::NAME],
            [
                [$id1++, 'tag1'],
                [$id1++, 'tag2'],
            ]
        );

        $this->batchInsert(
            Table::TAGS_ASSIGNMENTS,
            [Column::TASK_ID, Column::TAG_ID],
            [
                [1, 1],
                [3, 2],
            ]
        );

        $this->execute("SET foreign_key_checks = 1;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200506_133513_insert_data cannot be reverted.\n";
    }
}
