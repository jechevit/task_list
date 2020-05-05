<?php

use core\entities\Task;
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
            <?= Html::a('Создать задачу', ['create'], ['class' => 'btn btn-success'])?>
        </p>

            <div class="list-group">
                <?php /** @var Task $task */
                foreach ($tasks->getModels() as $key => $task):?>
                    <a href="<?= Url::to(['task/view', 'id' => $task->id])?>" class="list-group-item">
                        <span class="label label-primary"><?= $task->getCurrentStatus()->getValue() ?></span>
                        <span class="label label-primary"><?= $task->getCurrentPriority()->getValue() ?></span>
                        <h4 class="list-group-item-heading">Задача № <?= $key + 1 ?></h4>
                        <p class="list-group-item-text"><?= $task->title?></p>
                    </a>
                <?php endforeach;?>
            </div>

    </div>
</div>
