<?php

use core\databases\Column;
use core\databases\MigrationHelper;
use core\databases\Table;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%tags}}`.
 */
class m200505_160213_create_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Table::TAGS, [
            Column::ID => $this->primaryKey(),
            Column::NAME => $this->string()->notNull(),
        ], Table::OPTIONS);

        $this->createIndex(MigrationHelper::indexName(Table::TAGS, Column::NAME), Table::TAGS, Column::NAME, true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Table::TAGS);
    }
}
