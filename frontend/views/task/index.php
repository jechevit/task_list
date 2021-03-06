<?php

use core\entities\Task;
use core\helpers\PriorityHelper;
use core\helpers\StatusHelper;
use frontend\widgets\IndexModerationButtons;
use frontend\widgets\TagsWidget;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/** @var $tasks ActiveDataProvider */

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;

if ($tasks->totalCount > 15){
    $pages = new Pagination(['totalCount' => $tasks->totalCount  , 'pageSize' => 15]);
}
?>

<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="body-content">
        <p>
            <?= Html::a('Создать задачу', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?php Pjax::begin(); ?>
        <?php /** @var Task $task */
        foreach ($tasks->getModels() as $key => $task):?>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="panel-title pull-left">
                        <p class="panel-title">
                            Задача № <?= $task->id ?>
                            <span class="date-created">Создано: <?= Yii::$app->formatter->asDatetime($task->created_at, 'php:d-M-Y H:i')?></span>
                        </p>
                        <?= StatusHelper::statusLabel($task->getCurrentStatus()->getValue())?>
                        <?= PriorityHelper::priorityLabel($task)?>

                        <?= TagsWidget::widget(['task' => $task])?>

                    </div>
                    <div class="panel-title pull-right">
                        <?= IndexModerationButtons::widget(['task' => $task])?>
                    </div>
                </div>

                <div class="panel-body">
                    <?php if ($task->isInWork()):?>
                        <?= Html::a('<p class="list-group-item-text">' . $task->title . '</p>', ['task/update', 'id' => $task->id])?>
                    <?php else:?>
                        <?= Html::tag('div', '<p class="list-group-item-text">' . $task->title . '</p>') ?>
                    <?php endif;?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (isset($pages)):?>
            <?= LinkPager::widget([
                'pagination' => $pages,
            ]); ?>
        <?php endif;?>
        <?php Pjax::end(); ?>
    </div>
</div>
