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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'CodBodegaOrigen',
                'header' => financiero::t("egresomercaderia", "Cellar Code Org."),
                'value' => 'CodBodegaOrigen',
            ],
            [
                'attribute' => 'BodegaOrigen',
                'header' => financiero::t("egresomercaderia", "Cellar Origin"),
                'value' => 'BodegaOrigen',
            ],
            [
                'attribute' => 'Tipo',
                'header' => financiero::t("egresomercaderia", "Egress Type"),
                'value' => 'Tipo',
            ],
            [
                'attribute' => 'NumEgreso',
                'header' => financiero::t("bodega", "Egress Number"),
                'value' => 'NumEgreso',
            ],
            [
                'attribute' => 'Fecha',
                'header' => financiero::t("egresomercaderia", "Egress Date"),
                'value' => 'Fecha',
            ],
            [
                'attribute' => 'CodCliente',
                'header' => financiero::t("egresomercaderia", "Client Code"),
                'value' => 'CodCliente',
            ],
            [
                'attribute' => 'NomCliente',
                'header' => financiero::t("egresomercaderia", "Client Name"),
                'value' => 'NomCliente',
            ],
            /*[
                'attribute' => 'CantEgresado',
                'header' => financiero::t("egresomercaderia", "Amount of Egress"),
                'value' => 'CantEgresado',
            ],*/
            [
                'attribute' => 'TotalEgresado',
                'header' => financiero::t("egresomercaderia", "Total of Egress"),
                'value' => 'TotalEgresado',
            ],
            [
                'attribute' => 'CodBodegaDst',
                'header' => financiero::t("egresomercaderia", "Cellar Code Dst."),
                'value' => 'CodBodegaDst',
            ],
            [
                'attribute' => 'BodegaDst',
                'header' => financiero::t("egresomercaderia", "Cellar Destiny"),
                'value' => 'BodegaDst',
            ],
            [
                'attribute' => 'NumTransfer',
                'header' => financiero::t("bodega", "Income Number"),
                'value' => 'NumTransfer',
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => financiero::t("egresomercaderia", "Status"),
                'value' => function($data){
                    if($data['Estado'] == "L"){
                        return '<span class="label label-success">'.financiero::t("egresomercaderia", "Liquidated").'</span>';
                    }else{
                        return '<span class="label label-danger">'.financiero::t("egresomercaderia", "Invalidated").'</span>';
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/'.Yii::$app->controller->module->id.'/egresomercaderia/view', 'cod' => $model['CodBodegaOrigen'], 'egr' => $model['NumEgreso'], 'tip' => $model['Tipo']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                ],
            ],
        ],
    ])
?>
