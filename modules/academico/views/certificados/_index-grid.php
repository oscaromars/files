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
    'id' => 'TbG_Certificados',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'attribute' => 'Nombres',
            'header' => Especies::t("Especies", "Número"),
            'value' => 'cgen_id',
        ],
        [
            'attribute' => 'Nombres',
            'header' => Especies::t("Especies", "Student"),
            'value' => 'Nombres',
        ],
        [
            'attribute' => 'Especie',           
            'header' => Especies::t("Especies", "Número Especie"),
            'value' => 'egen_numero_solicitud',            
        ],
                        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Especies::t("Especies", "Código Certificado"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span>' . substr($model['cgen_codigo'], 0, 10) . '... </span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['cgen_codigo']]);
                },
            ],
        ],
        [
            'attribute' => 'Unidad Academica',
            'header' => Especies::t("Especies", "Academic unit"),
            'value' => 'uaca_nombre',
        ],
        [
            'attribute' => 'Modalidad',
            'header' => academico::t("Academico", "Modality"),
            'value' => 'mod_nombre',
        ],
        [
            'attribute' => 'Fecha Aprobación',
            'header' => Especies::t("Especies", "Fecha Generado"),
            'format' => ['date', 'php:d-m-Y'],
            'value' => 'cgen_fecha_codigo_generado',
        ],
        [
            'attribute' => 'Estado',
            'header' => especies::t("Especies", "Certified Status"),           
            'value' => 'cgen_estado_certificado',
        ],                    
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{descarga} {certificado}', 
            'buttons' => [                
                'descarga' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-upload"></span>', Url::to(['/academico/certificados/subircertificado', 'cgen_id' => base64_encode($model['cgen_id'])]), ["data-toggle" => "tooltip", "title" => "Subir Certificado PDF", "data-pjax" => "0"]);
                },              
                'certificado' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-barcode"></span>', Url::to(['/academico/certificados/downloadcertificado', 'cod' => base64_encode($model['cgen_codigo'])]), ["data-toggle" => "tooltip", "title" => "Descargar Word Certificado", "data-pjax" => "0"]);
                },   
            ],
        ],
    ],
])
?>