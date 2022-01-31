<?php

use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\widgets\PbGridView\PbGridView;
use yii\helpers\Html;
use yii\helpers\Url;
academico::registerTranslations();
print_r($model, true);
?>

<?=

//$count = $this->dataProvider->getCount();

PbGridView::widget([
	'id' => 'Tbg_Calificaciones',
	//'showExport' => true,
	//'fnExportEXCEL' => "exportExcel",
	//'fnExportPDF' => "exportPdf",
	'tableOptions' => [
		'class' => 'table table-striped',
	],
	'options' => [
		'class' => 'table-responsive',
	],
	'dataProvider' => $model,
	'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
	'pajax' => true,
	'columns' => [
		['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
		/*[
			            'attribute' => 'Matricula',
			            'header' => academico::t("Academico", "Enrollment Number"),
			            'value' => 'est_matricula',
			        ],
			        [
			            'attribute' => 'Nombres Completos',
			            'header' => academico::t("Academico", "Student"),
			            'value' => 'nombre',
		*/
		[
			'attribute' => 'Periodo',
			'header' => academico::t("Academico", "Period"),
			'value' => 'periodo',
		],
		[
			'attribute' => 'Materia',
			'header' => academico::t("Academico", "Materia"),
			'value' => 'materia',
		],
		[
			'attribute' => 'Profesor',
			'header' => academico::t("Academico", "Teacher"),
			'value' => 'profesor',
		],
		[
			'attribute' => 'PartialI',
			'header' => academico::t("Academico", "Parcial I"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'parcial_1',
		],
		[
			'attribute' => 'PartialII',
			'header' => academico::t("Academico", "Parcial II"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'parcial_2',
		],
		[
			'attribute' => 'Supletorio',
			'header' => academico::t("Academico", "Supletorio"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'supletorio',
		],
		[
			'attribute' => 'PromedioFinal',
			'header' => academico::t("formulario", "Promedio Final"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'promedio_final',
		],
		/*[
			            'attribute' => 'Estado',
			            'header' => Yii::t("formulario", 'Estado Acad.'),
			            'contentOptions' => ['class' => 'text-center'],
			            'headerOptions' => ['class' => 'text-center'],
			            'format' => 'html',
			            'value' => function ($model) {
			                if ($model["estado"] == 'Aprobado')
			                    return '<small class="label label-success">Aprobado</small>';
			                else if ($model["estado"] == 'Reprobado')
			                    return '<small class="label label-danger">Reprobado</small>';
			                else
			                    return '<small class="label label-warning">Pendiente</small>';
			            },
		*/
		[
			'attribute' => 'AsistenciaFinal',
			'header' => academico::t("Academico", "Asistencia Final"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => function ($model) {
				return number_format($model['asistencia_final'], 2) . '%';
			},
		],
		[
			'attribute' => 'Estado Académico',
			'header' => Yii::t("formulario", 'Estado Académico'),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'format' => 'html',
			'value' => function ($model) {
				if ($model["estado_academico"] == 'Aprobado') {
					return '<small class="label label-success">Aprobado</small>';
				} else if ($model["estado_academico"] == 'Reprobado') {
					return '<small class="label label-danger">Reprobado</small>';
				} else {
					return '<small class="label label-warning">Pendiente</small>';
				}

			},
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'header' => academico::t("Academico", "Acciones"),
			'template' => '{view}',
			'buttons' => [
				'view' => function ($url, $model) {
					if (
						($model['paca_id'] > 0 && $model['paca_id'] != null) &&
						($model['est_id'] > 0 && $model['paca_id'] != null) &&
						($model['asi_id'] > 0 && $model['paca_id'] != null) &&
						($model['pro_id'] > 0 && $model['paca_id'] != null) &&
						$model['parcial_1'] != '0'
					) {

						if ($model['asistencia_parcial_1'] == '0') {
							$model['asistencia_parcial_1'] = 0;
						}
						if ($model['asistencia_parcial_2'] == '0') {
							$model['asistencia_parcial_2'] = 0;
						}

						return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['calificacionestudiante/view',
							'paca_id' => base64_encode($model['paca_id']),
							'est_id' => base64_encode($model['est_id']),
							'asi_id' => base64_encode($model['asi_id']),
							'pro_id' => base64_encode($model['pro_id']),
							'asistencia_parcial_1' => base64_encode($model['asistencia_parcial_1']),
							'asistencia_parcial_2' => base64_encode($model['asistencia_parcial_2']),
						]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View")]);
					} else {
						return Html::a('<span style ="color: gray" class="glyphicon glyphicon-eye-close"></span>');
					}
				},
			],
		],
	],
])
?>
