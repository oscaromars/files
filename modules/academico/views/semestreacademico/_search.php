<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RolSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="semestreacademico-search">

    <?php
    $form = ActiveForm::begin(['id' => 'login-form-inline',
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
                'fieldConfig' => ['options' => ['class' => 'form-group mr-2']], // spacing field groups
                'formConfig' => ['showErrors' => true],
                'options' => ['style' => 'align-items: flex-start'],
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>
    <div class="form-group row">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
            <?= $form->field($model, 'saca_nombre')->textInput(['style' => 'width:300px']) ?>

            <?= $form->field($model, 'saca_descripcion')->textInput(['style' => 'width:300px']) ?>

            <?= $form->field($model, 'saca_anio')->textInput(['style' => 'width:100px','maxlength' => 4]) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
