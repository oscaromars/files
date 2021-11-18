<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;

academico::registerTranslations();

?>

<?=

//$count = $this->dataProvider->getCount();

PbGridView::widget([
	'id' => 'Tbg_Calificaciones',
	'showExport' => false,
	'fnExportEXCEL' => "exportExcel",
	'fnExportPDF' => "exportPdf",
	'dataProvider' => $model,
	'pajax' => true,
	'columns' => [
		['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
		[
			'attribute' => 'Matricula',
			'header' => academico::t("Academico", "Enrollment Number"),
			'value' => 'est_matricula',
		],
		[
			'attribute' => 'Nombres Completos',
			'header' => academico::t("Academico", "Names"),
			'value' => 'Nombres_completos',
		],
		[
			'attribute' => 'Materia',
			'header' => academico::t("Academico", "Course"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'asi_nombre',
		],
		/*[
			            'attribute' => 'Paralelo',
			            'header' => academico::t("Academico", "Paralelo"),
			            'value' => 'par_nombre',
		*/
		[
			'attribute' => 'Promedio Parcial I',
			'header' => academico::t("Academico", "Parcial I"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'parcial_1',
		],
		[
			'attribute' => 'Promedio Parcial II',
			'header' => academico::t("Academico", "Parcial II"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'parcial_2',
		],
		/*[
			            'attribute' => 'Promedio',
			            'header' => academico::t("Academico", "Average"),
			            'value' => 'promedio_',
		*/
		[
			'attribute' => 'Supletorio',
			'header' => academico::t("Academico", "Extension"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'supletorio',
		],
		[
			'attribute' => 'Promedio Final',
			'header' => academico::t("Academico", "Final average"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'promedio_final',
		],
		[
			'attribute' => 'Estado',
			'header' => Yii::t("formulario", 'Estado Acad.'),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'format' => 'html',
			'value' => function ($model) {
				if ($model["estado"] == 'Aprobado') {
					return '<small class="label label-success">Aprobado</small>';
				} else if ($model["estado"] == 'Reprobado') {
					return '<small class="label label-danger">Reprobado</small>';
				} else {
					return '<small class="label label-warning">Pendiente</small>';
				}

			},
		],
		[
			'attribute' => 'Asistencia Parcial I',
			'header' => academico::t("Academico", "Asist. I"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => function ($model) {
				return number_format($model['asistencia_parcial_1'], 2) . '%';
			},
		],
		[
			'attribute' => 'Asistencia Parcial I',
			'header' => academico::t("Academico", "Asist. II"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => function ($model) {
				return number_format($model['asistencia_parcial_2'], 2) . '%';
			},
		],
		[
			'attribute' => 'Asistencia Final',
			'header' => academico::t("Academico", "Asistencia Final"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => function ($model) {
				return number_format($model['asistencia_final'], 2) . '%';
			},
		],
		[
			'attribute' => 'Estado',
			'header' => Yii::t("formulario", 'Estado Asist.'),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'format' => 'html',
			'value' => function ($model) {
				if ($model["estado_asist"] == 'Aprobado') {
					return '<small class="label label-success">Aprobado</small>';
				} else if ($model["estado_asist"] == 'Reprobado') {
					return '<small class="label label-danger">Reprobado</small>';
				} else {
					return '<small class="label label-warning">Pendiente</small>';
				}

			},
		],
		/*[
			            'class' => 'yii\grid\ActionColumn',
			            'header' => Yii::t("Formulario", "Acciones"),
			            'template' => '{view}',
			            'buttons' => [
			                'view' => function ($url, $model) {
			                    if (strlen($model['carrera']) > 30) {
			                        $texto = '...';
			                    }
			                    return Html::a('<span>' . substr("carrera", 0, 20) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => "Carrera"]);
			                },
			            ],
		*/
		/*[
			            'attribute' => 'categoria',
			            'header' => Yii::t("formulario", "Category"),
			            'value' => 'categoria',
		*/

	],
])
?>
