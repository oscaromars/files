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
    'id' => 'TbG_CertGenerado',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcelcert",
    'fnExportPDF' => "exportPdfcert",
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
            'attribute' => 'Certificado',           
            'header' => Especies::t("certificados", "Certificate Code"),
            'value' => 'cgen_codigo',            
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
            'header' => academico::t("certificados", "Authorization date"),
            'format' => ['date', 'php:d-m-Y'],
            'value' => 'cgen_fecha_autorizacion',
        ],                       
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{descarga}', 
            'buttons' => [                
                'descarga' => function ($url, $model) {                    
                return Html::a('<span class="glyphicon glyphicon-download-alt"></span>', Url::to(['/site/getimage', 'route' => '/uploads/certificados/' . $model['imagen']]), ["download" => $model['imagen'], "data-toggle" => "tooltip", "title" => "Descargar Certificado PDF", "data-pjax" => 0]);
                },                
            ],
        ],
    ],
])
?>