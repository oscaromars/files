<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gfinanciero\Module as financiero;
financiero::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_list',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'rowOptions'=>function($model){
            return ['style' => 'color:' 
            . ($model['ESTADO'] == 'A' ? 'red' : 'black')];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            
            [
                'attribute' => 'FECHA',
                'header' => financiero::t("bodega", "DATE"),
                'value' => 'FECHA',
            ],
            [
                'attribute' => 'INGRESO',
                'header' => financiero::t("bodega", "ENTRY"),
                'value' => 'INGRESO',
            ],
            [
                'attribute' => 'EGRESO',
                'header' => financiero::t("bodega", "EGRESS"),
                'value' => 'EGRESO',
            ],
            [
                'attribute' => 'CANTIDAD',
                'header' => financiero::t("bodega", "AMOUNT"),
                'value' => 'CANTIDAD',
            ],
            [
                'attribute' => 'SALDO',
                'header' => financiero::t("bodega", "BALANCE"),
                'value' => 'SALDO',
            ],
            [
                'attribute' => 'ESTADO',
                'header' => financiero::t("bodega", "STATUS"),
                'options' => ['style'=>'text-align:center'],
                'value' => 'ESTADO',
                'contentOptions' => function ($model) { 
                    //background-color   
                    return ['style' => 'color:' 
                        . ($model['ESTADO'] == 'A' ? 'red' : 'black')];
                },
            ],
            [
                'attribute' => 'REFERENCIA',
                'header' => financiero::t("bodega", "REFERENCE"),
                'value' => 'REFERENCIA',
            ],
            
          
        ],
    ])
?>
