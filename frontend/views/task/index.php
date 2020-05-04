<?php

use core\entities\Task;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/** @var $tasks ActiveDataProvider */


$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="body-content">
        <p>
            <?= Html::a('Создать задачу', ['create'], ['class' => 'btn btn-success'])?>
        </p>

        <?php /** @var Task $task */
        foreach ($tasks->getModels() as $task):?>
            <div class="row">
                <div class="task"><?= $task->title?></div>
            </div>
        <?php endforeach;?>
    </div>
</div>
