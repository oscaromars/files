<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;

//print_r($model);
academico::registerTranslations();
financiero::registerTranslations();
?>
<?=

PbGridView::widget([
    'id' => 'TbG_PagosFacturas',
    //'showExport' => true,
    'fnExportEXCEL' => "exportExcelpago",
    'fnExportPDF' => "exportPdfpago",
    'dataProvider' => $model,
    'columns' =>
    [        
               
        [
            'attribute' => 'Método pago',
            'header' => Yii::t("formulario", "Paid form"),
            'value' => 'forma_pago',
        ],
        [
            'attribute' => 'Valor pago',
            'header' => financiero::t("Pagos", "Amount Paid"),
            'value' => 'valor_pago',
        ],    
        [
            'attribute' => 'Referencia',
            'header' => financiero::t("Pagos", "Reference"),
            'value' => 'referencia',
        ],
        [
            'attribute' => 'Fecha Registro',
            'header' => Yii::t("formulario", "Registration Date"),
            'format' => ['date', 'php:d-m-Y'],
            'value' => 'fecha_registro',
        ],       
        [
            'attribute' => 'Fecha Pago',
            'header' => financiero::t("Pagos", "Payment Date"),
            //'format' => ['date', 'php:d-m-Y'],
            'value' => 'fecha_pago',
        ],     
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{ver}',
            'buttons' => [
                'ver' => function ($url, $model) {                                         
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/financiero/pagosfacturas/detallepagosfactura', 'pfes_id' => base64_encode($model['pfes_id'])]), ["data-toggle" => "tooltip", "title" => "Ver Detalle de Factura", "data-pjax" => "0"]);                    
                },                             
            ],
        ],
    ],
])
?>