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
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'attribute' => 'Número',
            'header' => Especies::t("Especies", "Número"),
            'value' => 'egen_numero_solicitud',
        ],  
          [
            'attribute' => 'Tramite',
            'header' => Especies::t("Especies", "Procedure"),
            'value' => 'tramite',
        ],      
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Especies::t("Especies", "Tipo Especie"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span>' . substr($model['esp_rubro'], 0, 30) . '... </span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['esp_rubro']]);
                },
            ],
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
            'template' => '{view}{download}', //
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-download"></span>', Url::to(['/academico/especies/generarespeciespdf', 'ids' => base64_encode($model['egen_id'])]), ["data-toggle" => "tooltip", "title" => "Descargar Especie", "data-pjax" => "0"]);
                },  
                'download' => function ($url, $model) {
                    if ($model['cgen_estado_certificado']==3) {
                        return Html::a('<span class="glyphicon glyphicon-download-alt"></span>', Url::to(['/site/getimage', 'route' => '/uploads/certificados/' . $model['imagen']]), ["download" => $model['imagen'], "data-toggle" => "tooltip", "title" => "Descargar Certificado PDF", "data-pjax" => 0]);   
                    }                    
                },                         
            ],
        ],
    ],
])
?>