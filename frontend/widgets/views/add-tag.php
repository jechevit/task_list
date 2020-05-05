<?php

use core\forms\TagForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Modal::begin([
    'header' => '<h2>Введите название тега</h2>',
    'toggleButton' => [
        'label' => '<span class="glyphicon glyphicon-plus"></span>',
        'tag' => 'a',
        'class' => 'modal_a',
    ],
]);

/** @var $model TagForm */
?>
<?php $form = ActiveForm::begin([
    'id' => 'contact-form',
    'action' => ['task/add-tag']
]); ?>

<?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить!', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>
<?php ActiveForm::end(); ?>
<?php Modal::end() ?>