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
    'id' => 'TbG_Pagos',  
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
            'attribute' => 'Cuota',
            'header' => financiero::t("Pagos", "Monthly fee"),
            'value' => 'dpfa_num_cuota',
        ],
        [
            'attribute' => 'Factura',
            'header' => financiero::t("Pagos", "Bill"),
            'value' => 'dpfa_factura',
        ],
        [
            'attribute' => 'Fecha Registro',
            'header' => Yii::t("formulario", "Registration Date"),
            'format' => ['date', 'php:d-m-Y'],
            'value' => 'pfes_fecha_registro',
        ],
        [
            'attribute' => 'Estado',
            'header' => Yii::t("formulario", "Review Status"),
            'value' => 'estado_pago',
        ],
        [
            'attribute' => 'Financiero',
            'header' => financiero::t("Pagos", "Financial Status"),
            'value' => 'estado_financiero',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{ver} {update}',
            'buttons' => [
                'ver' => function ($url, $model) {                      
                    if ($model['estado_pago'] != 'Pendiente')  {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/financiero/pagosfacturas/consultarevision', 'dpfa_id' => base64_encode($model['dpfa_id'])]), ["data-toggle" => "tooltip", "title" => "Ver Detalle de pago", "data-pjax" => "0"]);
                    } else {
                        return '<span class="glyphicon glyphicon-check"></span>';
                    }
                }, 
                'update' => function ($url, $model) {                      
                    if ($model['estado_pago'] == 'Rechazado')  {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['/financiero/pagosfacturas/updatepago', 'dpfa_id' => base64_encode($model['dpfa_id'])]), ["data-toggle" => "tooltip", "title" => "Modificar pago", "data-pjax" => "0"]);
                    } else {
                        return '<span class="glyphicon glyphicon-pencil"></span>';
                    }
                },
            ],
        ],
    ],
])
?>