<?php

use core\databases\Column;
use core\databases\Table;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m200503_223949_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Table::TASKS, [
            Column::ID => $this->primaryKey(),
            Column::UUID => $this->char(36)->notNull(),
            Column::TITLE => $this->text()->notNull(),
            Column::PRIORITY => $this->integer()->notNull(),
            Column::CURRENT_STATUS => $this->integer()->notNull(),
            Column::STATUSES => $this->json()->notNull(),
            Column::CREATED_AT => $this->integer()->unsigned()->notNull(),
            Column::UPDATED_AT => $this->integer()->unsigned()->null(),
        ], Table::OPTIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Table::TASKS);
    }
}
