<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\marketing\Module as marketing;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>
<?=

PbGridView::widget([
    //'dataProvider' => new yii\data\ArrayDataProvider(array()),
    'id' => 'Tbg_Lista',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcelLista",
    'fnExportPDF' => "exportPdfLista",
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'attribute' => 'Lista',
            'header' => marketing::t("marketing", "List"),
            'value' => 'lis_nombre',
        ],
        [
            'attribute' => 'Programa',
            'header' => academico::t("Academico", "Career/Program/Course"),
            'value' => 'programa',
            
        ],
        [
            'attribute' => 'Subscriber number',
            'header' => marketing::t("marketing", "No. Subscribers in Mailchimp"),
            'value' => 'num_suscriptores',
        ],   
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{asignar} {editar} {eliminar} ',
            'buttons' => [                
                'asignar' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-plus"></span>', Url::to(['/marketing/email/asignar',  'lis_id' => base64_encode($model['lis_id'])]), ["data-toggle" => "tooltip", "title" => "Asignar Subscriptores", "data-pjax" => 0]);
                },     
                'editar' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['/marketing/email/edit',  'lis_id' => base64_encode($model['lis_id'])]), ["data-toggle" => "tooltip", "title" => "Editar lista", "data-pjax" => 0]);
                },   
                'eliminar' => function ($url, $model) {                    
                    return Html::a('<span class="glyphicon glyphicon-remove"></span>', "#", ['onclick' => "eliminarLista(" . $model['lis_id'] . ");", "data-toggle" => "tooltip", "title" => "Eliminar lista", "data-pjax" => 0]);                    
                },                   
            ],
        ],
    ],
])
?>