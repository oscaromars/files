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
    'id' => 'TbG_Revisionpago',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcelrevpago",
    'fnExportPDF' => "exportPdfrevpago",
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'attribute' => 'Identificación',
            'header' => Yii::t("formulario", "DNI"),
            'value' => 'identificacion',
        ],
        [
            'attribute' => 'Nombres',
            'header' => Yii::t("formulario", "Student"),
            'value' => 'estudiante',
        ],
        [
            'attribute' => 'Unidad Academica',
            'header' => Yii::t("formulario", "Aca. Uni."),
            'value' => 'unidad',
        ],
        [
            'attribute' => 'Modalidad',
            'header' => academico::t("Academico", "Modality"),
            'value' => 'modalidad',
        ],
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
            'attribute' => 'Abono',
            'header' => financiero::t("Pagos", "Abono"),
            'value' => 'abono',
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
        /*[
            'attribute' => 'Estado',
            'header' => Yii::t("formulario", "Review Status"),
            'value' => 'estado_pago',
        ],*/
        [
            'attribute' => 'Estado',
            'header' => Yii::t("formulario", "Review Status"),
            'contentOptions' => ['class' => 'text-left'],
            'headerOptions' => ['class' => 'text-left'],
            'format' => 'html',           
            'value' => function ($model) {
                if ($model["estado_pago"] == 'Pendiente')
                    return '<small class="label label-warning">' . $model["estado_pago"] . '</small>';
                else if ($model["estado_pago"] == 'Aprobado')
                    return '<small class="label label-success">' . $model["estado_pago"] . '</small>';
                else
                    return '<small class="label label-danger">' . $model["estado_pago"] . '</small>';
            },
        ],
        /*[
            'attribute' => 'Financiero',
            'header' => financiero::t("Pagos", "Financial Status"),
            'value' => 'estado_financiero',
        ],*/
        [
            'attribute' => 'Estado',
            'header' => financiero::t("Pagos", "Financial Status"),
            'contentOptions' => ['class' => 'text-left'],
            'headerOptions' => ['class' => 'text-left'],
            'format' => 'html',           
            'value' => function ($model) {
                if ($model["estado_financiero"] == 'Pendiente')
                    return '<small class="label label-warning">' . $model["estado_financiero"] . '</small>';
                else
                    return '<small class="label label-success">' . $model["estado_financiero"] . '</small>';
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{revisar}{ver}{reversar}',
            'buttons' => [
                'revisar' => function ($url, $model) {
                    if (($model['estado_pago'] == 'Pendiente') || ($model['estado_pago'] == 'Rechazado') && ($model['estado_financiero'] == 'Pendiente')) {
                        return Html::a('<span class="glyphicon glyphicon-check"></span>', Url::to(['/financiero/pagosfacturas/revisar', 'dpfa_id' => base64_encode($model['dpfa_id'])]), ["data-toggle" => "tooltip", "title" => "Revisar Pago", "data-pjax" => "0"]);
                    } else {
                        return '<span class="glyphicon glyphicon-check"></span>';
                    }
                },
                'ver' => function ($url, $model) {
                    if ($model['estado_pago'] != 'Pendiente')  {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/financiero/pagosfacturas/consultarevision', 'dpfa_id' => base64_encode($model['dpfa_id'])]), ["data-toggle" => "tooltip", "title" => "Ver revisión de pago", "data-pjax" => "0"]);
                    } else {
                        return '<span class="glyphicon glyphicon-check"></span>';
                    }
                },
                'reversar' => function ($url, $model) {
                    if (($model['estado_pago'] == 'Aprobado') && ($model['estado_financiero'] == 'Cancelado') && $model['fpag_id'] != '1') {
                        return Html::a('<span class="glyphicon glyphicon-share"></span>', Url::to(['/financiero/pagosfacturas/reversar', 'dpfa_id' => base64_encode($model['dpfa_id'])]), ["data-toggle" => "tooltip", "title" => "Reversar Pago", "data-pjax" => "0"]);
                    } else {
                        return '<span class="glyphicon glyphicon-share"></span>';
                    }
                },
            ],
        ],
    ],
])
?>