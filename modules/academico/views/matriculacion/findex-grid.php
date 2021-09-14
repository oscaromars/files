<?php

use app\modules\academico\models\CancelacionRegistroOnline;
use app\modules\academico\models\CancelacionRegistroOnlineItem;
use app\modules\academico\Module as academico;
use app\widgets\PbGridView\PbGridView;
use yii\helpers\Html;
academico::registerTranslations();

$modelCancelItem = array();

//print_r($planificacion);die();

//print_r($data_student['mod_id']);die();

//print_r($planificacion);

$modelCancelRon = CancelacionRegistroOnline::findOne(['ron_id' => $ron_id, 'cron_estado' => '1', 'cron_estado_logico' => '1']);
if ($modelCancelRon) {
	//$cancelStatus = $modelCancelRon->cron_estado_cancelacion;
	$modelCancelItem = CancelacionRegistroOnlineItem::find()
		->select(['r.roi_materia_cod as code'])
		->join('INNER JOIN', 'registro_online_item as r', 'r.roi_id = cancelacion_registro_online_item.roi_id')
		->where(['cron_id' => $modelCancelRon->cron_id, 'croi_estado' => '1', 'croi_estado_logico' => '1'])
		->asArray()
		->all();
}
//

?>
 <div class="noBar" style="overflow: scroll; /*border: 1px solid;*/ scrollbar-width: none;margin-left: 0 !important;">
<style>
	.table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > td {
    border-top: 0;
    vertical-align: top;

    -ms-overflow-style: none; /* for Internet Explorer, Edge */
    scrollbar-width: none; /* for Firefox */
	}
	.noBar{
		-ms-overflow-style: none !important; /* for Internet Explorer, Edge */
  		scrollbar-width: none !important; /* for Firefox */
  		overflow-x: scroll !important;
	}
	.nopBar::-webkit-scrollbar {
		display: none !important; /* for Chrome, Safari, and Opera */
	}
</style>
<?=
PbGridView::widget([
	'id' => 'grid_registro_list',
	'showExport' => false,
	//'fnExportEXCEL' => "exportExcel",
	//'fnExportPDF' => "exportPdf",
	/* 'dataProvider' => $model, */
	'dataProvider' => $planificacion,
	'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
	'pajax' => true,
	'columns' => [
		['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10', 'border' => '1px solid !important']],
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
			'attribute' => 'Block',
			'header' => Academico::t("matriculacion", "Block"),
			'value' => 'Block',
		],
		[
			'attribute' => 'Hour',
			'header' => Academico::t("matriculacion", "Hour"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'Hour',
		],
		[
			'attribute' => 'Parallel',
			'header' => Academico::t("matriculacion", "Paralelo"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'Parallel',
		],
		[
			'attribute' => 'Credit',
			'header' => Academico::t("matriculacion", "Credit"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => 'Credit',
		],
		/*[
			                'attribute' => 'Cost',
			                'header' => Academico::t("matriculacion", "Unit Cost"),
			                'value' => function($data){
			                    return '$' . number_format($data['Cost'],2 );
			                },
		*/
		[
			'attribute' => 'CostSubject',
			'header' => Academico::t("matriculacion", "Cost Subject"),
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'value' => function ($data) {
				return '$' . number_format((empty($data['Cost']) ? 0 : $data['Cost']) * (empty($data['Credit']) ? 0 : $data['Credit']), 2);
			},
		],
		[
			'attribute' => 'Status',
			'header' => Academico::t("matriculacion", "Status"),
			'contentOptions' => ['style' => 'text-align: center;'],
			'headerOptions' => ['width' => '60'],
			'format' => 'html',
			'value' => function ($data) use ($cancelStatus, $modelCancelItem) {
				if ($cancelStatus == '1' || $cancelStatus == '2') {
					$code = $data['Code'];
					foreach ($modelCancelItem as $key => $value) {
						if ($value['code'] == $code) {
							if ($cancelStatus == 2) {
								return "<span class='label label-success'>" . Academico::t('matriculacion', 'Cancelled') . "</span>";
							}

							return "<span class='label label-warning'>" . Academico::t('matriculacion', 'Cancellation in process') . "</span>";
						}
					}
					return "<span class='label label-success'>" . Academico::t("matriculacion", "OK") . "</span>";
				} else {
					return "<span class='label label-success'>" . Academico::t("matriculacion", "Periodo de Registro") . "</span>";
				}
			},
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'header' => Academico::t("matriculacion", "Select"),
			'contentOptions' => ['style' => 'text-align: center;'],
			'headerOptions' => ['width' => '60'],
			'template' => '{select}',
			'buttons' => [
				'select' => function ($url, $model) {
					if ($model['roi_id'] > 0) {
						return Html::checkbox($model['Code'], false, ["value" => $model['Subject'], "disabled" => true, "class" => "chequeado"]);
					} else {
						return Html::checkbox($model['Code'], false, ["value" => $model['Subject'], "class" => "byregister"]);
					}

				},

			],
		],

	],
])
?>
</div>