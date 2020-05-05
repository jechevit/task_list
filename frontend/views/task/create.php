<?php

use core\forms\TaskForm;
use core\helpers\PriorityHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model TaskForm */
?>

<div class="site-index">
        <div class="row">
            <div class="col-lg-8">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'title')->textarea(['autofocus' => true])->label('Текст задачи') ?>

                <?= $form->field($model, 'priority')->dropDownList(PriorityHelper::priorityList())->label('Приоритет')?>

                <div class="col-md-6">
                    <div class="box box-default">
                        <div class="box-header with-border">Tags</div>
                        <div class="box-body">
                            <?= $form->field($model->tags, 'existing')->checkboxList($model->tags->tagsList()) ?>
                            <?= $form->field($model->tags, 'textNew')->textInput() ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Создать', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>
