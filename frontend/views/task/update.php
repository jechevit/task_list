<?php

use core\forms\TaskForm;

/** @var $model TaskForm */

$this->title = 'Изменение задачи';
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model])?>
