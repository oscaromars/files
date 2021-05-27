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
    'id' => 'TbG_Pagofactura',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcelpagofactura",
    'fnExportPDF' => "exportPdfpagofactura",
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'attribute' => 'Identificación',
            'header' => Yii::t("formulario", "DNI 1"),
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
            'attribute' => 'Cuota',
            'header' => financiero::t("Pagos", "Monthly fee"),
            'value' => 'dpfa_num_cuota',
        ],
        [
            'attribute' => 'Factura',
            'header' => financiero::t("Pagos", "Bill"),
            'value' => 'dpfa_factura',
        ],
        /*[
            'attribute' => 'Fecha Pago',
            'header' => Yii::t("formulario", "Payment date"),
            'format' => ['date', 'php:d-m-Y'],
            'value' => 'dpfa_fecha_aprueba_rechaza',
        ],*/
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Date"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span>' . substr($model['dpfa_fecha_aprueba_rechaza'], 0, -9) . '</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['dpfa_fecha_aprueba_rechaza']]);
                },
            ],
        ],
        [
            'attribute' => 'Estado',
            'header' => Yii::t("formulario", "Review Status"),
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['class' => 'text-center'],
            'format' => 'html',
            // 'value' => 'estado_pago',
            'value' => function ($model) {
                if ($model["estado_pago"] == 'Pendiente')
                    return '<small class="label label-warning">' . $model["estado_pago"] . '</small>';
                else if ($model["estado_pago"] == 'Aprobado')
                    return '<small class="label label-success">' . $model["estado_pago"] . '</small>';
                else
                    return '<small class="label label-danger">' . $model["estado_pago"] . '</small>';
            },
        ],           
    ],
])
?>