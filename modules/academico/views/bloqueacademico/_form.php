<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */
/* @var $form yii\widgets\ActiveForm */
if ($model->baca_id == null) {
//Ingresa un nuevo
	$model->baca_estado = '1';
	$model->baca_estado_logico = '1';
	$model->baca_usuario_ingreso = Yii::$app->session->get("PB_iduser");
	// $model->baca_usuario_modifica = Yii::$app->session->get("PB_iduser");
	$model->baca_fecha_creacion = date("Y-m-d H:i:s");
	//$model->baca_fecha_registro = date("Y-m-d H:i:s");
} else {
//Modifica
	$model->baca_usuario_modifica = Yii::$app->session->get("PB_iduser");
	$model->baca_fecha_modificacion = date("Y-m-d H:i:s");
}
?>

<div class="semestreacademico-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal',
	'fieldConfig' => [
		'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
		'horizontalCssClasses' => [
			'label' => 'col-sm-4',
			'offset' => 'col-sm-offset-4',
			'wrapper' => 'col-sm-8',
			'error' => '',
			'hint' => '',
		],
	],
]);?>

    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'baca_nombre')->textInput(['maxlength' => true, 'style' => 'width:300px'])?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'baca_descripcion')->textInput(['style' => 'width:300px'])?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'baca_anio')->textInput(['style' => 'width:100px', 'maxlength' => 4])?>
        </div>
    </div>

    <div class="form-group">
        <!--<div class="col-sm-offset-4">
            <?=Html::submitButton('Guardar', ['class' => 'btn btn-success'])?>
        </div>-->
    </div>
        <?=$form->field($model, 'baca_estado')->hiddenInput()?>
        <?=$form->field($model, 'baca_estado_logico')->hiddenInput()?>
         <?=$form->field($model, 'baca_usuario_ingreso')->hiddenInput()?>
        <?=$form->field($model, 'baca_usuario_modifica')->hiddenInput()?>
        <?=$form->field($model, 'baca_fecha_creacion')->hiddenInput()?>
        <?=$form->field($model, 'baca_fecha_modificacion')->hiddenInput()?>

    <?php ActiveForm::end();?>

</div>
