<?php

use core\entities\Task;
use core\forms\TagsForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Modal::begin([
    'header' => '<h2>Выберите тег</h2>',
    'toggleButton' => [
        'label' => '<span class="glyphicon glyphicon-plus"></span>',
        'tag' => 'a',
        'class' => 'modal_a',
        'title' => 'Создать или добавить тег',
    ],
]);

/** @var $model TagsForm */
/** @var $task Task */
?>
<?php $form = ActiveForm::begin([
    'id' => 'contact-form',
    'action' => ['task/add-tag']
]); ?>

<?= $form->field($model, 'existing')->checkboxList($model->tagsList())->label('Выбрать из существующих') ?>
<?= $form->field($model, 'textNew')->textInput()->label('Создать и привязать новый тег') ?>
<?= $form->field($model, 'taskId')->hiddenInput(['value' => $task->id])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить!', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>
<?php ActiveForm::end(); ?>
<?php Modal::end() ?>