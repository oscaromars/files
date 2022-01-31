<?php

use app\modules\academico\Module as academico;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="distributivocabecera-aprobardistributivo">

    <?php echo $this->render('_aprobarsearch', ['model' => $searchModel]); ?>

    <?php
echo GridView::widget([
	'id' => 'grid',
	'dataProvider' => $dataProvider,
	//'filterModel' => $searchModel,
	'pjax' => true,
	'panel' => [
		'type' => 'primary',
		'heading' => 'Aprobar Distributivo',
	],
	'export' => [
		'showConfirmAlert' => false,
		'target' => GridView::TARGET_BLANK,
	],
	'exportConfig' => [
		//GridView::CSV => ['label' => 'Save as CSV'],
		// GridView::HTML => [// html settings],

		GridView::EXCEL => [
			'label' => Yii::t('kvgrid', 'Excel'),
			//'icon' => $isFa ? 'file-excel-o' : 'floppy-remove',
			'iconOptions' => ['class' => 'text-success'],
			'showHeader' => true,
			'showPageSummary' => false,
			'showFooter' => true,
			'showCaption' => true,
			'filename' => Yii::t('kvgrid', 'grid-export'),
			'alertMsg' => Yii::t('kvgrid', 'The EXCEL export file will be generated for download.'),
			'options' => ['title' => Yii::t('kvgrid', 'Microsoft Excel 95+')],
			'mime' => 'application/vnd.ms-excel',
			'config' => [
				'worksheet' => Yii::t('kvgrid', 'ExportWorksheet'),
				'cssFile' => '',
			],
		],
	],
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],
		[
			'class' => 'kartik\grid\ExpandRowColumn',
			'width' => '50px',
			'value' => function ($model, $key, $index, $column) {
				return GridView::ROW_COLLAPSED;
			},
			// uncomment below and comment detail if you need to render via ajax
			// 'detailUrl' => Url::to(['/site/book-details']),
			'detail' => function ($model, $key, $index, $column) {
				return Yii::$app->controller->renderPartial('detalledistributivo', ['model' => $model]);
			},
			'headerOptions' => ['class' => 'kartik-sheet-style'],
			'expandOneOnly' => true,
		],

		[
			'attribute' => 'periodo_academico',
			'header' => academico::t("Academico", "Periodo Académico"),
			'value' => 'periodo_academico',
		],
		[
			'attribute' => 'profesor',
			'header' => academico::t("Academico", "Profesor"),
			'value' => 'profesor',
		],
		[
			'attribute' => 'dcab_observacion_revision',
			'header' => academico::t("Academico", "Observación"),
			'value' => 'dcab_observacion_revision',
		],
		[
			'class' => 'kartik\grid\CheckboxColumn',
			'headerOptions' => ['class' => 'kartik-sheet-style'],
			'header' => academico::t("Academico", "Aprobar"),
		],
	],
]);
?>


</div>
