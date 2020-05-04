<?php

use core\helpers\PriorityHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="site-index">
        <div class="row">
            <div class="col-lg-8">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'title')->textarea(['autofocus' => true])->label('Текст задачи') ?>

                <?= $form->field($model, 'priority')->dropDownList(PriorityHelper::priorityList())->label('Приоритет')?>

                <div class="form-group">
                    <?= Html::submitButton('Создать', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>
