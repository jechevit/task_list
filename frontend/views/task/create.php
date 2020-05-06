<?php

use core\forms\TaskForm;

/** @var $model TaskForm */

$this->title = 'Создание задачи';
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <?= $this->render('_form', ['model' => $model]);?>
</div>
