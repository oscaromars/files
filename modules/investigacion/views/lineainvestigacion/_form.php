<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\investigacion\models\LineaInvestigacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="linea-investigacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'linv_descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'linv_usuario_ingreso')->textInput() ?>

    <?= $form->field($model, 'linv_usuario_modifica')->textInput() ?>

    <?= $form->field($model, 'linv_estado')->textInput() ?>

    <?= $form->field($model, 'linv_fecha_creacion')->textInput() ?>

    <?= $form->field($model, 'linv_fecha_modificacion')->textInput() ?>

    <?= $form->field($model, 'linv_estado_logico')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
