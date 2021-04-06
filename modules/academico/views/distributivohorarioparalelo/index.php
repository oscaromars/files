<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\academico\Module as academico;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="rol-index">





    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    echo  GridView::widget([
        'dataProvider' => $dataProvider,
        //  'filterModel' => $searchModel,
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
            'dhpa_paralelo',
            'daho.uaca.uaca_descripcion',
            ['attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => 'Estado',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->dhpa_estado == "1")
                        return '<small class="label label-success">' . academico::t("asignatura", "Enabled") . '</small>';
                    else
                        return '<small class="label label-danger">' . academico::t("asignatura", "Disabled") . '</small>';
                },
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => false,
                'vAlign' => 'middle',
                'template' => '{update}',
            ],
        ],
    ]);
    ?>


</div>
