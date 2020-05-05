<?php

namespace core\services;

use Throwable;
use Yii;

class TransactionManager
{
    /**
     * @param callable $function
     * @throws Throwable
     */
    public function wrap(callable $function): void
    {
        Yii::$app->db->transaction($function);
    }
}