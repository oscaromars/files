<?php

use app\modules\academico\models\BloqueAcademico;
use app\modules\academico\models\SemestreAcademico;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Notas */
/* @var $form yii\widgets\ActiveForm */
if ($model->paca_id == null) {
//Ingresa un nuevo
	$model->paca_estado = '1';
	$model->paca_estado_logico = '1';
	$model->paca_usuario_ingreso = Yii::$app->session->get("PB_iduser");
	$model->paca_usuario_modifica = NULL;
	$model->paca_fecha_creacion = date("Y-m-d H:i:s");
	$model->paca_fecha_modificacion = NULL;
} else {
//Modifica
	$model->paca_usuario_modifica = Yii::$app->session->get("PB_iduser");
	$model->paca_fecha_modificacion = date("Y-m-d H:i:s");
}
?>

<div class="estudioacademico-form">


    <?php
$form = ActiveForm::begin([
	'layout' => 'horizontal',
	'fieldConfig' => [
		'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
		'horizontalCssClasses' => [
			'label' => 'col-sm-4',
			'offset' => 'col-sm-offset-4',
			'wrapper' => 'col-sm-8',
			'error' => '',
			'hint' => '',
			'button' => 'col-sm-4',
		],
	],
]);
?>
    <?=
$form->field($model, 'saca_id')->widget(Select2::classname(), [
	'data' => ArrayHelper::map(SemestreAcademico::find()->all(), 'saca_id', 'saca_nombre', 'saca_anio'),
	'size' => Select2::MEDIUM,
	'options' => ['placeholder' => 'Seleccione  Semestre Académico ...', 'multiple' => false],
	'pluginOptions' => [
		'allowClear' => true,
		'width' => '295px',
	],
]);
?>
    <?=
$form->field($model, 'baca_id')->widget(Select2::classname(), [
	'data' => ArrayHelper::map(BloqueAcademico::find()->all(), 'baca_id', 'baca_descripcion', 'baca_anio', 'baca_nombre'),
	'size' => Select2::MEDIUM,
	'options' => ['placeholder' => 'Seleccione Bloque Académico ...', 'multiple' => false],
	'pluginOptions' => [
		'allowClear' => true,
		'width' => '295px',
	],
]);
?>


    <?=
$form->field($model, 'paca_fecha_inicio')->widget(DatePicker::className(), ['type' => DatePicker::TYPE_INPUT, 'pluginOptions' => [
	'autoclose' => true,
	'format' => Yii::$app->params["dateByDatePicker"],
	'options' => ["class" => "form-control", "id" => "txt_fecha_ini", 'style' => 'width:300px', "placeholder" => Yii::t("formulario", "Start date")],
]])
?>
    <?=
$form->field($model, 'paca_fecha_fin')->widget(DatePicker::className(), ['type' => DatePicker::TYPE_INPUT, 'pluginOptions' => [
	'autoclose' => true,
	'options' => ["class" => "form-control", "id" => "txt_fecha_fin", 'style' => 'width:300px', "placeholder" => Yii::t("formulario", "Start date")],
	'format' => Yii::$app->params["dateByDatePicker"],
]])
?>

<?=
$form->field($model, 'paca_fecha_cierre_ini')->widget(DatePicker::className(), ['type' => DatePicker::TYPE_INPUT, 'pluginOptions' => [
	'autoclose' => true,
	'format' => Yii::$app->params["dateByDatePicker"],
	'options' => ["class" => "form-control", "id" => "txt_fecha_cierre_ini", 'style' => 'width:295px', "placeholder" => Yii::t("formulario", "Fecha cierre Inicio")],
]])
?>
    <?=
$form->field($model, 'paca_fecha_cierre_fin')->widget(DatePicker::className(), ['type' => DatePicker::TYPE_INPUT, 'pluginOptions' => [
	'autoclose' => true,
	'options' => ["class" => "form-control", "id" => "txt_fecha_cierre_fin", 'style' => 'width:295px', "placeholder" => Yii::t("formulario", "Fecha cierre fin")],
	'format' => Yii::$app->params["dateByDatePicker"],
]])
?>

    <?=$form->field($model, 'paca_semanas_periodo')->textInput(['maxlength' => true, 'style' => 'width:300px'])?>


     <?=
$form->field($model, 'paca_activo')->widget(Select2::classname(), [
	'data' => ["A" => "Activo", "I" => "Inactivo", "C" => "Cerrado"],
	'size' => Select2::MEDIUM,
	'options' => ['placeholder' => 'Seleccione Estado ...', 'multiple' => false],
	'pluginOptions' => [
		'allowClear' => true,
		'width' => '295px',
	],
]);
?>



    <div class="form-group">
        <div class="col-sm-offset-4">
<?=Html::submitButton('Guardar', ['class' => 'btn btn-success'])?>
        </div>
    </div>
</div>
<?=$form->field($model, 'paca_usuario_ingreso')->hiddenInput()?>
<?=$form->field($model, 'paca_usuario_modifica')->hiddenInput()?>
<?=$form->field($model, 'paca_fecha_modificacion')->hiddenInput()?>
<?=$form->field($model, 'paca_estado_logico')->hiddenInput()?>
<?=$form->field($model, 'paca_fecha_creacion')->hiddenInput()?>
<?=$form->field($model, 'paca_estado')->hiddenInput()?>

<?php ActiveForm::end();?>

</div>
</div>
