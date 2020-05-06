<?php

use core\forms\TaskForm;
use core\helpers\PriorityHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model TaskForm */

?>

<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
    <div class="row">
        <div class="col-lg-9">
            <?= $form->field($model, 'title')->textarea(['autofocus' => true, 'rows' => 3])->label('Текст задачи') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'priority')->dropDownList(PriorityHelper::priorityList())->label('Приоритет') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">Добавление тега: </div>
                <div class="box-body">
                    <?= $form->field($model->tags, 'existing')->checkboxList($model->tags->tagsList())->label('Выбрать существующий') ?>
                    <?= $form->field($model->tags, 'textNew')->textInput()->label('Создать новый тег') ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="form-group">
                <?= Html::submitButton('Создать', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
