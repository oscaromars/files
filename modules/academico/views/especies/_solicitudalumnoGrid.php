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
use app\modules\academico\Module as Especies;

academico::registerTranslations();
financiero::registerTranslations();
Especies::registerTranslations();
?>
<?=

PbGridView::widget([
    'id' => 'TbG_Solicitudes',
    //'showExport' => true,
    //'fnExportEXCEL' => "exportExcel",
    //'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'attribute' => 'Solicitud',
            'header' => Especies::t("Especies", "Solicitud"),
            //'visible' => FALSE,
            //'htmlOptions' => array('style' => 'display:none; border:none;'),
            //'contentOptions' => ['class' => 'bg-red','style' => 'display:none; border:none;'],     // HTML attributes to customize value tag
            //'captionOptions' => ['tooltip' => 'Tooltip'], 
            'value' => 'csol_id',
        ],
        [
            'attribute' => 'Fecha Solicitud ',
            'header' => Especies::t("Especies", "Fecha Solicitud"),
            'format' => ['date', 'php:d-m-Y'],
            'value' => 'csol_fecha_solicitud',
        ],
        [
            'attribute' => 'Unidad Academica',
            'header' => Especies::t("Especies", "Academic unit"),
            'value' => 'uaca_nombre',
        ],
        [
            'attribute' => 'Modalidad',
            'header' => Especies::t("Especies", "Modalidad"),
            'value' => 'mod_nombre',
        ],
        [
            'attribute' => 'F.Pago',
            'header' => Especies::t("Especies", "Way to pay"),
            'value' => 'fpag_nombre',
        ],
        [
            'attribute' => 'Total',
            'header' => Especies::t("Especies", "Total"),
            'value' => 'csol_total',
        ],
        [
            'attribute' => 'Estado Solicitud',
            'header' => Especies::t("Especies", "Estado Solicitud"),
            'value' => function ($model) {
                $estado = ($model['csol_estado_aprobacion'] != '') ? $model['csol_estado_aprobacion'] : 1;
                return \app\modules\academico\models\Especies::getEstadoPago($estado);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{view} {especies}',
            'buttons' => [
                'view' => function ($url, $model) {
                    if ($model["csol_estado_aprobacion"] == "3") {
                        return Html::a('<span class="glyphicon glyphicon-upload"></span>', Url::to(['/academico/especies/verpago', 'ids' => base64_encode($model['csol_id'])]), ["data-toggle" => "tooltip", "title" => "Ver Pago", "data-pjax" => "0"]);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-upload"></span>', Url::to(['/academico/especies/cargarpago', 'ids' => base64_encode($model['csol_id'])]), ["data-toggle" => "tooltip", "title" => "Subir Pago", "data-pjax" => "0"]);
                    }
                },
                'especies' => function ($url, $model) {
                    if ($model["csol_estado_aprobacion"] == "3") {
                        return Html::a('<span class="glyphicon glyphicon-file"></span>', Url::to(['/academico/especies/especiesgeneradasxest', 'ids' => base64_encode($model['csol_id'])]), ["data-toggle" => "tooltip", "title" => "Ver Especies Valoradas", "data-pjax" => "0"]);
                    } else {
                        return "<span class = 'glyphicon glyphicon-file' data-toggle = 'tooltip' title ='Ver Especies Valoradas'  data-pjax = 0></span>";
                    }
                },
            /* 'download' => function ($url, $model) use ($generadas) {
              return Html::a('<span class="glyphicon glyphicon-download"></span>', Url::to(['/academico/especies/generarespeciespdf', 'ids' => base64_encode($generadas)]), ["data-toggle" => "tooltip", "title" => "Descargar Especie", "data-pjax" => "0"]);
              }, */
            ],
        ],
    ],
])
?>