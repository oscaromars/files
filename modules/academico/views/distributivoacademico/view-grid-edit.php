<?php

use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use app\widgets\PbGridView\PbGridView;
use yii\helpers\Html;

admision::registerTranslations();
academico::registerTranslations();
?>
<div>
    <?=
PbGridView::widget([
	'id' => 'TbG_Data',
	//'showExport' => true,
	//'fnExportEXCEL' => "exportExcel",
	//'fnExportPDF' => "exportPdf",
	'dataProvider' => $arr_detalle,
	//'pajax' => false,
	'columns' =>
	[
		[
			'attribute' => '',
			'header' => '',
			'value' => 'Id',
			'contentOptions' => array('style' => 'display:none'),
			'headerOptions' => array('style' => 'display:none'),
		],
		[
			'attribute' => '',
			'header' => '',
			'value' => 'dcab_id',
			'contentOptions' => array('style' => 'display:none'),
			'headerOptions' => array('style' => 'display:none'),
		],
		[
			'attribute' => 'Tipo Asignación',
			'header' => academico::t("Academico", "Assignment Type"),
			'value' => 'tipo_asignacion',
		],
		[
			'attribute' => 'Asignatura',
			'header' => Yii::t("formulario", "Subject"),
			'value' => 'Asignatura',
		],
		[
			'attribute' => 'UnidadAcademica',
			'header' => Yii::t("formulario", "Academic unit"),
			'value' => 'UnidadAcademica',
		],
		[
			'attribute' => 'Modalidad',
			'header' => Yii::t("formulario", "Mode"),
			'value' => 'Modalidad',
		],
		[
			'attribute' => 'NumeroEstudiantes',
			'header' => academico::t("distributivoacademico", "Number of students"),
			'value' => 'nroEstudiantes',
		],
		[
			'attribute' => 'Jornada',
			'header' => academico::t("Academico", "Working day"),
			'value' => 'jornada',
		],
		[
			'attribute' => 'Horario',
			'header' => academico::t("Academico", "Schedule"),
			'value' => 'horario',
		],
		[
			'attribute' => 'fechaI',
			'header' => academico::t("Academico", "Fecha Inicio"),
			'value' => 'fechaI',
		],
		[
			'attribute' => 'fechaf',
			'header' => academico::t("Academico", "Fecha  Fin"),
			'value' => 'fechaf',
		],
		[
			'attribute' => 'nroHoras',
			'header' => academico::t("Academico", "#Horas Actividades"),
			'value' => 'nroHoras',
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'header' => '',
			'template' => '{delete}',
			'contentOptions' => ['class' => 'text-center'],
			'buttons' => [
				'delete' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, ['href' => 'javascript:confirmDelete(\'eliminarItems\',[\'' . $model['Id'] . '\',\'' . $model['dcab_id'] . '\',\'TbG_Data\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion", "Delete")]);
				},
			],
		],

	],
])
?>
</div>