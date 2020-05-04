<?php


namespace core\databases;


class Table
{
    const OPTIONS = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    const CASCADE = 'CASCADE';
    const SET_NULL = 'SET NULL';
    const RESTRICT = 'RESTRICT';

    const TASKS = '{{%tasks}}';
    const TAGS = '{{%tags}}';
    const TAGS_ASSIGNMENTS = '{{%tags_assignments}}';
}