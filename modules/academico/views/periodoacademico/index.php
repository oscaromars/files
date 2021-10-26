<?php
use app\models\Utilities;
use app\modules\academico\Module as academico;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$formatter = \Yii::$app->formatter;

?>
<div class="estudioacademico-index">


    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo GridView::widget([
	'dataProvider' => $dataProvider,
	//'filterModel' => $searchModel,
	'pjax' => true,
	'panel' => [
		'type' => 'primary',
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
		'saca.saca_nombre',

		'saca.saca_anio',
		'baca.baca_nombre',

		'baca.baca_anio',
		['attribute' => 'paca_fecha_inicio',
			'value' => function ($model, $key, $index, $widget) {
				return Yii::$app->formatter->asDate($model->paca_fecha_inicio, 'yyyy-MM-dd');
			},
		],
		['attribute' => 'paca_fecha_fin',
			'value' => function ($model, $key, $index, $widget) {
				return Yii::$app->formatter->asDate($model->paca_fecha_fin, 'yyyy-MM-dd');
			},
		],
		['attribute' => 'paca_fecha_cierre_fin',
			'value' => function ($model, $key, $index, $widget) {
				return Yii::$app->formatter->asDate($model->paca_fecha_cierre_fin, 'yyyy-MM-dd');
			},
		],

		'paca_semanas_periodo',
		['attribute' => 'paca_activo',
			'contentOptions' => ['class' => 'text-center'],
			'headerOptions' => ['class' => 'text-center'],
			'format' => 'html',
			'header' => 'Estado',
			'value' => function ($model, $key, $index, $widget) {
				if ($model->paca_activo == "A") {
					return '<small class="label label-success">' . academico::t("asignatura", "Enabled") . '</small>';
				} else if ($model->paca_activo == "I") {
					return '<small class="label label-warning">' . academico::t("asignatura", "Disabled") . '</small>';
				} else {
					return '<small class="label label-danger">' . academico::t("asignatura", "Closed") . '</small>';
				}
			},

		],

		[
			'class' => 'kartik\grid\ActionColumn',
			'contentOptions' => ['style' => 'text-align: center;'],
			'headerOptions' => ['width' => '60'],
			//'format' => 'html',
			'dropdown' => false,
			'vAlign' => 'middle',
			'template' => '{update}',
			'buttons' => [
				'update' => function ($url, $model) {
					if ($model->paca_activo == "A" or $model->paca_activo == "I") {
						return Html::a('<span class="' . Utilities::getIcon('edit') . '"></span>', null, ['href' => 'update/' . $model->paca_id, "data-toggle" => "tooltip", "title" => Yii::t("accion", "Update")]);;
					}

					/*'update' => function ($url, $model) {
						                            return Html::a('<span class="' . Utilities::getIcon('update') . '"></span>', null, ['href' => 'javascript:update(\'update\',[\'' . $model->paca_id . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion", "Update")]);
						                        //else
					*/
				},

			],

		],

	],

]);
?>


</div>

