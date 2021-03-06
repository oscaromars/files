<?php

use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioSearch */
/* @var $form yii\widgets\ActiveForm */

$var = ArrayHelper::map(app\modules\academico\models\PeriodoAcademico::find()->where(['paca_estado' => 1, 'paca_estado_logico' => 1, 'paca_activo' => 'A'])->all(), 'paca_id',
	function ($model) {
		return $model->baca->baca_nombre . '-' . $model->sem->saca_nombre . '-' . $model->sem->saca_anio;
		// return strtoupper($model->per->per_pri_apellido . ' ' . $model->per->per_seg_apellido . ' ' . $model->per->per_pri_nombre . ' ' . $model->per->per_seg_nombre);
	});
?>
<div class="usuario-search">

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
	'action' => ['reportdistributivoposgrado'],
	'method' => 'get',
]);
?>

    <?=
$form->field($model, 'paca_id')->label('Período:')->widget(Select2::classname(), [
	//  'data' => ArrayHelper::map(app\modules\academico\models\PeriodoAcademico::find()->all(), 'paca_id', 'sem.saca_nombre'),
	'data' => $var,
	'size' => Select2::MEDIUM,
	'options' => ['placeholder' => 'Seleccione Período ...', 'multiple' => false],
	'pluginOptions' => [
		'allowClear' => true,
		'width' => '295px',
	],
]);
?>

    <?=
$form->field($model, 'tdis_id')->label('Tipo Asignación:')->widget(Select2::classname(), [
	'data' => ArrayHelper::map(app\modules\academico\models\TipoDistributivo::find()->where(['tdis_estado' => '1', 'tdis_estado_logico' => '1'])->all(), 'tdis_id', 'tdis_nombre'),
	// 'data' => $var,
	'size' => Select2::MEDIUM,
	'options' => ['placeholder' => 'Seleccione Distributivo ...', 'multiple' => false],
	'pluginOptions' => [
		'allowClear' => true,
		'width' => '295px',
	],
]);
?>

    <?=
$form->field($model, 'mod_id')->label('Modalidad:')->widget(Select2::classname(), [
	'data' => ArrayHelper::map(app\modules\academico\models\Modalidad::find()->where(['mod_estado_logico' => '1', 'mod_estado' => '1'])->all(), 'mod_id', 'mod_nombre'),
	// 'data' => $var,
	'size' => Select2::MEDIUM,
	'options' => ['placeholder' => 'Seleccione Modalidad ...', 'multiple' => false],
	'pluginOptions' => [
		'allowClear' => true,
		'width' => '295px',
	],
]);
?>


	<?=
$form->field($model, 'pame_id')->label('Mes:')->widget(Select2::classname(), [
	'data' => ArrayHelper::map(app\modules\academico\models\PeriodoAcademicoMensualizado::find()->where(['pame_estado_logico' => '1', 'pame_estado' => '1'])->all(), 'pame_id', 'pame_mes'),
	// 'data' => $var,
	'size' => Select2::MEDIUM,
	'options' => ['placeholder' => 'Seleccione Mes ...', 'multiple' => false],
	'pluginOptions' => [
		'allowClear' => true,
		'width' => '295px',
	],
]);
?>

    <div class="form-group">
        <div class="col-sm-offset-4">
            <?=Html::submitButton('Buscar', ['class' => 'btn btn-primary', 'id' => 'btn_buscar'])?>
        </div>
    </div>

<?php ActiveForm::end();?>

</div>


