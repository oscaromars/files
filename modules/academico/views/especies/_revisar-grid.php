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
            'value' => 'csol_id',
        ],
        [
            'attribute' => 'Fecha Solicitud ',
            'header' => Especies::t("Especies", "Fecha Solicitud"),
            'format' => ['date', 'php:d-m-Y'],
            'value' => 'csol_fecha_solicitud',
        ],
        [
            'attribute' => 'Nombre ',
            'header' => Yii::t("formulario", "Names"),          
            'value' => 'nombre',
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
            //'value' => 'csol_estado_aprobacion',
            'value' => function ($model) {           
                $estado=($model['csol_estado_aprobacion']!='')?$model['csol_estado_aprobacion']:1;
                return \app\modules\academico\models\Especies::getEstadoPago($estado);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{view}', //
            'buttons' => [
                'view' => function ($url, $model) {
                    if ($model['csol_estado_aprobacion'] == 3) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/academico/especies/verautorizarpago', 'est_id' => base64_encode($model['est_id']), 'ids' => base64_encode($model['csol_id'])]), ["data-toggle" => "tooltip", "title" => "Ver Solicitud Generada", "data-pjax" => "0"]);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/academico/especies/autorizarpago', 'est_id' => base64_encode($model['est_id']), 'ids' => base64_encode($model['csol_id'])]), ["data-toggle" => "tooltip", "title" => "Ver Solicitud", "data-pjax" => "0"]);
                    }
                },
                
            ],
        ],
    ],
])
?>