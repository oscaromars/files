<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gfinanciero\Module as financiero;
financiero::registerTranslations();
//IG.COD_BOD as CodBodegaOrigen, BG.NOM_BOD as BodegaOrigen, IG.NUM_ING as NumIngreso,
// IG.TIP_ING as Tipo, IG.FEC_ING as Fecha,
//IG.COD_PRO as CodProveedor, IG.NOM_PRO as NomProveedor, IG.T_I_ING as CantIngresadas, 
//IG.TOT_COS as TotalIngresado,IG.IND_UPD as Estado ";
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
                'header' => financiero::t("ingresomercaderia", "Cellar Code Org."),
                'value' => 'CodBodegaOrigen',
            ],
            [
                'attribute' => 'BodegaOrigen',
                'header' => financiero::t("ingresomercaderia", "Cellar"),
                'value' => 'BodegaOrigen',
            ],
            [
                'attribute' => 'Tipo',
                'header' => financiero::t("ingresomercaderia", "Entry Type"),
                'value' => 'Tipo',
            ],
            [
                'attribute' => 'NumIngreso',
                'header' => financiero::t("ingresomercaderia", "Entry Number"),
                'value' => 'NumIngreso',
            ],
            [
                'attribute' => 'Fecha',
                'header' => financiero::t("ingresomercaderia", "Entry Date"),
                'value' => 'Fecha',
            ],
            [
                'attribute' => 'CodProveedor',
                'header' => financiero::t("ingresomercaderia", "Provider Code"),
                'value' => 'CodProveedor',
            ],
            [
                'attribute' => 'NomProveedor',
                'header' => financiero::t("ingresomercaderia", "Provider Name"),
                'value' => 'NomProveedor',
            ],
            /*[
                'attribute' => 'CantEgresado',
                'header' => financiero::t("ingresomercaderia", "Amount of Egress"),
                'value' => 'CantEgresado',
            ],*/
            [
                'attribute' => 'TotalIngresado',
                'header' => financiero::t("ingresomercaderia", "Total of Entry"),
                'value' => 'TotalIngresado',
            ],
            
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => financiero::t("ingresomercaderia", "Status"),
                'value' => function($data){
                    if($data['Estado'] == "L"){
                        return '<span class="label label-success">'.financiero::t("ingresomercaderia", "Liquidated").'</span>';
                    }else{
                        return '<span class="label label-danger">'.financiero::t("ingresomercaderia", "Invalidated").'</span>';
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
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/'.Yii::$app->controller->module->id.'/ingresomercaderia/view', 'cod' => $model['CodBodegaOrigen'], 'ing' => $model['NumIngreso'], 'tip' => $model['Tipo']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                ],
            ],
        ],
    ])
?>
