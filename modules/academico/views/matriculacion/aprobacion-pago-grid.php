<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\models\ObjetoModulo;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>

<?=

PbGridView::widget([
    'id' => 'grid_pagos_list',
    'showExport' => false,
    //'fnExportEXCEL' => "exportExcel",
    //'fnExportPDF' => "exportPdf",
    'dataProvider' => $pagos,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Estudiante',
            'header' => academico::t("matriculacion", 'Student'),
            'value' => 'Estudiante',
        ],
        [
            'attribute' => '',
            'header' => academico::t("matriculacion", 'Payment'),
            'value' => function($data) {
                return academico::t("matriculacion", 'Registration payment');
            },
        ],
        [
            'attribute' => 'EstadoAprobacion',
            'header' => academico::t("matriculacion", 'Payment state'),
            'value' => function($data) {
                if ($data["EstadoAprobacion"] == "0")
                    return academico::t("matriculacion", 'Not reviewed');
                else if ($data["EstadoAprobacion"] == "1")
                    return academico::t("matriculacion", 'Approved');
                else
                    return academico::t("matriculacion", 'Rejected');
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => academico::t("matriculacion", 'Action'),
            'contentOptions' => ['style' => 'text-align: center;'],
            'headerOptions' => ['width' => '60'],
            'template' => '{download} {approve} {deny}',
            'buttons' => [
                'download' => function ($url, $pagos) {
                    return Html::a('<span style="margin-left: 2px;margin-right: 2px;" class="glyphicon glyphicon-download"></span>', null, ["data-toggle" => "tooltip", "title" => academico::t("matriculacion", 'Download'), "onclick" => "descargarPago('" . $pagos['id'] . "')"]);
                },
                'approve' => function ($url, $pagos) {
                    if ($pagos["EstadoAprobacion"] == "0") {
                        return Html::a('<span style="margin-left: 2px;margin-right: 2px;" class="glyphicon glyphicon-thumbs-up"></span>', null, ['href' => 'javascript:confirmDelete(\'estadoPago\',[\'' . $pagos['id'] . '\',\'1\'],\'' . academico::t("matriculacion", 'Are your sure to approve the payment?') . '\',\'' . academico::t("matriculacion", 'Approve') . '\');', "data-toggle" => "tooltip", "title" => academico::t("matriculacion", 'Approve')]);
                    } else {
                        return "<span class = 'glyphicon glyphicon-thumbs-up' data-toggle = 'tooltip' title ='Aprobar'  data-pjax = 0></span>";
                    }
                },
                'deny' => function ($url, $pagos) {
                    if ($pagos["EstadoAprobacion"] == "0") {
                        return Html::a('<span style="margin-left: 2px;margin-right: 2px;" class="glyphicon glyphicon-thumbs-down"></span>', null, ['href' => 'javascript:confirmDelete(\'estadoPago\',[\'' . $pagos['id'] . '\',\'2\'],\'' . academico::t("matriculacion", 'Are your sure to reject the payment?') . '\',\'' . academico::t("matriculacion", 'Reject') . '\');', "data-toggle" => "tooltip", "title" => academico::t("matriculacion", 'Reject')]);
                    } else {
                        return "<span class = 'glyphicon glyphicon-thumbs-down' data-toggle = 'tooltip' title ='Aprobar'  data-pjax = 0></span>";
                    }
                },
            ],
        ],
    ],
])
?>
