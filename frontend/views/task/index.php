<?php

use core\entities\Task;
use core\helpers\PriorityHelper;
use core\helpers\StatusHelper;
use frontend\widgets\IndexModerationButtons;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $tasks ActiveDataProvider */


$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="body-content">
        <p>
            <?= Html::a('Создать задачу', ['create'], ['class' => 'btn btn-success']) ?>
        </p>


        <?php /** @var Task $task */
        foreach ($tasks->getModels() as $key => $task):?>
            <div class="panel panel-default">

                <div class="panel-heading clearfix">
                    <div class="panel-title pull-left">
                        <p class="panel-title">Задача № <?= $task->id ?></p>
                        <?= StatusHelper::statusLabel($task->getCurrentStatus()->getValue())?>
                        <?= PriorityHelper::priorityLabel($task)?>
                    </div>
                    <div class="panel-title pull-right">
                        <?= IndexModerationButtons::widget(['task' => $task])?>
                    </div>
                </div>

                <div class="panel-body">
                    <a href="<?= Url::to(['task/view', 'id' => $task->id]) ?>">
                        <p class="list-group-item-text"><?= $task->title ?></p>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
