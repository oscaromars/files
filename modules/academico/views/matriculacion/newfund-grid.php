<?php

use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use app\widgets\PbGridView\PbGridView;
use yii\helpers\Html;
use yii\helpers\Url;

admision::registerTranslations();
academico::registerTranslations();
?>
<?=Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']);?>
<div>
    <?=
PbGridView::widget([
	'id' => 'Tbg_Abe_listado_create',
	//'showExport' => false,
	'fnExportEXCEL' => "exportExcelDistpagopos",
	'fnExportPDF' => "exportPdfDispagopos",
	'dataProvider' => $model,
	//'pajax' => false,
	'columns' =>
	[
		[
			'attribute' => 'pla_periodo',
			'header' => academico::t("Academico", "Periodo Académico"),
			'value' => 'pla_periodo',
		],
		[
			'attribute' => 'DNI',
			'header' => academico::t("Academico", "Cedula"),
			'value' => 'DNI',
		],
		[
			'attribute' => 'Estudiante',
			'header' => academico::t("matriculacion", "Estudiante"),
			'value' => 'Estudiante',
		],
		[
			'attribute' => 'carrera',
			'header' => academico::t("matriculacion", "Carrera"),
			'value' => 'carrera',
		],
		[
			'attribute' => 'modalidad',
			'header' => academico::t("matriculacion", "Modalidad"),
			'value' => 'modalidad',
		],

		/*[
			                'attribute' => 'State',
			                'header' => Yii::t("formulario", 'Status'),
			                'contentOptions' => ['class' => 'text-center'],
			                'headerOptions' => ['class' => 'text-center'],
			                'format' => 'html',
			                'value' => function ($model) {
			                    if ($model["abe_id"] != '' || $model["abe_id"] != null )
			                    return '<small class="label label-success">&nbsp;&nbsp;&nbsp;Activa&nbsp;&nbsp;&nbsp;</small>';
			                    else
			                    return '<small class="label label-warning">No Activa</small>';
			                },
		*/
		[
			'class' => 'yii\grid\ActionColumn',
			'header' => Academico::t("matriculacion", "Select"),
			'contentOptions' => ['style' => 'text-align: center;'],
			'headerOptions' => ['width' => '60'],
			'template' => '{select}',
			'buttons' => [
				'select' => function ($url, $model) {
					//return Html::checkbox("", false, ["value" => $model['est_id']]);
					return Html::a('<span class="glyphicon glyphicon glyphicon-file"></span>', Url::to(['/academico/matriculacion/registroadminindex', 'per_id' => $model['per_id']]), ["data-toggle" => "tooltip", "title" => "Registrar Materias", "data-pjax" => 0]);

				},
			],
		],
		/* [
			                'class' => 'yii\grid\ActionColumn',
			                'header' => Academico::t("matriculacion", "Select"),
			                'contentOptions' => ['style' => 'text-align: center;'],
			                'headerOptions' => ['width' => '60'],
			                'template' => '{select}',
			                'buttons' => [
			                    'select' => function ($url, $model) {
			                        //return Html::checkbox("", false, ["value" => $model['est_id']]);
			                          return Html::a('<span class="glyphicon glyphicon glyphicon-file"></span>', Url::to(['/academico/matriculacion/registro', 'per_id' =>  $model['per_id']]), ["data-toggle" => "tooltip", "title" => "Crear Planificacion", "data-pjax" => 0]);

			                    },
			                ],
		*/
	],

])
?>
</div>



