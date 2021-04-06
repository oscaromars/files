<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use app\modules\academico\Module as academico;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Estudio Academico';
$this->params['breadcrumbs'][] = $this->title;
$gridColumns =['eaca_nombre','teac.teac_nombre','eaca_descripcion','eaca_alias_resumen'];
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i>Información!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>
<div class="estudioacademico-index">


    <div style="float: right;">

        <p>
            <?= Html::a('Nuevo Estudio Academico', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
    <h3><?= Html::encode($this->title) ?></h3>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
<?php echo 
    GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'panel' => [
            'type' => 'primary'
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
                    'cssFile' => ''
                ]
            ],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'eaca_nombre',
            'teac.teac_nombre',
            'eaca_descripcion',
            'eaca_alias',
            ['attribute' => 'eaca_alias_resumen',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->eaca_alias_resumen == null) {
                        return 'N/A';
                    } else {
                        return $model->eaca_alias_resumen;
                    }
                },
            ],
             ['attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => 'Estado',
                'value' => function ($model, $key, $index, $widget) {
                    if($model->eaca_estado == "1")
                        return '<small class="label label-success">'.academico::t("asignatura", "Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.academico::t("asignatura", "Disabled").'</small>';
                },
            ],           
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {create}',
            ],
        ],
    ]);
    ?>


</div>

