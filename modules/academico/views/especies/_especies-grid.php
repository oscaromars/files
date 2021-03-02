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
//print_r($model);
academico::registerTranslations();
financiero::registerTranslations();
Especies::registerTranslations();
?>
<?=

PbGridView::widget([
    'id' => 'TbG_Solicitudes',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Especies::t("Especies", "Número"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span>' . substr($model['egen_numero_solicitud'], 0, 10) . '... </span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['egen_numero_solicitud']]);
                },
            ],
        ],
        [
            'attribute' => 'Tramite',
            'header' => Especies::t("Especies", "Trámite"),
            'value' => 'tramite',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Especies::t("Especies", "Tipo Especie"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span>' . substr($model['esp_rubro'], 0, 20) . '... </span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['esp_rubro']]);
                },
            ],
        ],
        [
            'attribute' => 'Nombres',
            'header' => Especies::t("Especies", "Alumno"),
            'value' => 'Nombres',
        ],
        [
            'attribute' => 'Cédula',
            'header' => Especies::t("Especies", "Cédula"),
            'value' => 'per_cedula',
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
            'attribute' => 'Fecha Aprobación',
            'header' => Especies::t("Especies", "Fecha Aprobación"),
            'format' => ['date', 'php:d-m-Y'],
            'value' => 'fecha_aprobacion',
        ],
        [
            'attribute' => 'Fecha Validez',
            'header' => Especies::t("Especies", "Fecha Validez"),
            'format' => ['date', 'php:d-m-Y'],
            'value' => 'egen_fecha_caducidad',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{view} {descarga} {certificado}', //
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-download"></span>', Url::to(['/academico/especies/generarespeciespdf', 'ids' => base64_encode($model['egen_id'])]), ["data-toggle" => "tooltip", "title" => "Descargar Especie", "data-pjax" => "0"]);
                },
                'descarga' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-download-alt"></span>', Url::to(['/academico/especies/descargarimagen', 'espgen_id' => base64_encode($model['egen_id'])]), ["data-toggle" => "tooltip", "title" => "Descargar Especie/Justificación", "data-pjax" => "0"]);
                },
                'certificado' => function ($url, $model) {
                        if ($model["egen_certificado"] == "SI" && $model["codigo_generado"] == " ") { // tambien preguntar si ya no se ha generado el codigo ne la tabla certificados_generados
                            return Html::a('<span class="glyphicon glyphicon-barcode"></span>', "#", ["onclick" => "generarCodigocer(" . $model['egen_id'] . ",'" . $model["egen_numero_solicitud"] . "','" . $model['per_cedula'] . "');", "data-toggle" => "tooltip", "title" => "Generar Código Certificado", "data-pjax" => 0]);
                        } else {
                            return "<span class = 'glyphicon glyphicon-barcode' data-toggle = 'tooltip' title ='Especie no genera código o ya fue generado'  data-pjax = 0></span>";
                        }
                },
            ],
        ],
    ],
])
?>