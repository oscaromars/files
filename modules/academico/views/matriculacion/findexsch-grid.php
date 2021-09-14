<?php

use app\modules\academico\Module as academico;
use app\widgets\PbGridView\PbGridView;
use yii\helpers\Html;
academico::registerTranslations();
?>

<?=
PbGridView::widget([
	'id' => 'grid_registro_list',
	'showExport' => false,
	'dataProvider' => $planificacion,
	'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
	'pajax' => true,
	'columns' => [
		['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
		[
			'attribute' => 'Subject',
			'header' => Academico::t("matriculacion", "Subject"),
			'value' => 'Subject',
		],
		[
			'attribute' => 'Code',
			'header' => Academico::t("matriculacion", "Subject Code"),
			'value' => 'Code',
		],
		[
			'attribute' => 'Credit',
			'header' => Academico::t("matriculacion", "Credit"),
			'value' => 'Credit',
		],
		[
			'attribute' => 'Cost',
			'header' => Academico::t("matriculacion", "Unit Cost"),
			'value' => function ($data) {
				//return '$' . (number_format(($data['Cost'] * $data['Credit']), 2, '.', ','));
				return "0.00";
			},
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'header' => Academico::t("matriculacion", "Seleccionada"),
			'contentOptions' => ['style' => 'text-align: center;'],
			'headerOptions' => ['width' => '60'],
			'template' => '{select}',
			'buttons' => [
				'select' => function ($url, $planificacion) {
					return Html::checkbox($planificacion['Code'], true, ["value" => $planificacion['Subject'], "disabled" => true]);
					/* return Html::checkbox("", false, ["value" => $planificacion['Subject'], "onchange" => "handleChange(this)"]); */
				},
			],
		],
		/* [
			                'header' => academico::t("Academico", "Seleccionar"),
			                'class' => 'app\widgets\PbGridView\PbCheckboxColumn',
		*/
		/* [
			                'header' => academico::t("Academico", "Seleccionar"),
			                'class' => 'yii\grid\CheckboxColumn',
			                'checkboxOptions' => function($planificacion) {
			                    if(!$planificacion->status){
			                       return ['disabled' => true];
			                    }else{
			                       return [];
			                    }
			                 },
		*/
	],
])
?>