<?php

use core\databases\Column;
use core\databases\MigrationHelper;
use core\databases\Table;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks_tags_assignments}}`.
 */
class m200505_160458_create_tasks_tags_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Table::TAGS_ASSIGNMENTS, [
            Column::TASK_ID => $this->integer()->notNull(),
            Column::TAG_ID => $this->integer()->notNull(),
        ], Table::OPTIONS);

        $this->addPrimaryKey(MigrationHelper::primaryKeyName(Table::TAGS_ASSIGNMENTS), Table::TAGS_ASSIGNMENTS, [Column::TASK_ID, Column::TAG_ID]);

        $this->createIndex(MigrationHelper::indexName(Table::TAGS_ASSIGNMENTS, Column::TASK_ID), Table::TAGS_ASSIGNMENTS, Column::TASK_ID);
        $this->createIndex(MigrationHelper::indexName(Table::TAGS_ASSIGNMENTS, Column::TAG_ID), Table::TAGS_ASSIGNMENTS, Column::TAG_ID);


        $this->addForeignKey(MigrationHelper::foreignKeyName(Table::TAGS_ASSIGNMENTS, Column::TAG_ID),Table::TAGS_ASSIGNMENTS, Column::TAG_ID, Table::TAGS, Column::ID, Table::CASCADE, Table::RESTRICT);
        $this->addForeignKey(MigrationHelper::foreignKeyName(Table::TAGS_ASSIGNMENTS, Column::TASK_ID),Table::TAGS_ASSIGNMENTS, Column::TASK_ID, Table::TASKS, Column::ID, Table::CASCADE, Table::RESTRICT);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Table::TAGS_ASSIGNMENTS);
    }
}
