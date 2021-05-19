<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Persona;
use app\widgets\PbGridView\PbGridView;
use yii\data\ArrayDataProvider;
use app\modules\admision\Module;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?= Html::hiddenInput('txth_ids', $codigo, ['id' => 'txth_ids']); ?>

<?=

PbGridView::widget([
    'id' => 'Pbgestion',
    //'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'columns' => [
        [
            'attribute' => 'fecha',
            'header' => Yii::t("formulario", "Activity Date"),
            'value' => 'fecha_atencion',
        ],
        [
            'attribute' => 'descripcion',
            'header' => Yii::t("formulario", "Observation"),
            'contentOptions' => ['style' => 'max-width:200px;'],
            'value' => 'observacion',
        ],
        [
            'attribute' => 'fechaproxima',
            'header' => Yii::t("formulario", "Attention Next"),
            'value' => 'proxima_atencion',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Comments"),
            'template' => '{view}',            
            'buttons' => [
                    'view' => function ($url, $model) {
                        if ($model['bact_descripcion'] != '') {
                            $texto = substr($model['bact_descripcion'], 0, 100) . '...';
                        } else {
                            $texto = '';
                        }
                        return Html::a('<span>' . $texto . '</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['bact_descripcion']]);
                    },
                ],
        ],     
        [
            'attribute' => 'Usuario',
            'header' => Yii::t("formulario", "User login"),
            'value' => 'usuario_ing',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{view} {interested}', //
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['actividades/view', 'opid' => base64_encode($model["opo_id"]), 'pgid' => base64_encode($model["pges_id"]), 'acid' => base64_encode($model["bact_id"])]), ["data-toggle" => "tooltip", "title" => "Ver Actividad", "data-pjax" => 0]);
                },
                'interested' => function ($url, $model) {
                    $mod_per = new Persona();
                    $pre_id = $mod_per->ConsultaRegistroExiste(null, $model['pges_cedula'], $model['pges_pasaporte']);
                    //$existe = isset($pre_id['existen']) ? 1 : 0;
                    if ($model['estado_oportunidad_id'] == 3) {
                        if ($pre_id['existen'] == 0) {
                            return Html::a('<span class="glyphicon glyphicon-user"></span>', "#", ["onclick" => "grabarInteresado(" . $model['opo_id'] . ");", "data-toggle" => "tooltip", "title" => "Generar Aspirante", "data-pjax" => 0]);
                        } else {
                            return "<span class = 'glyphicon glyphicon-user' data-toggle = 'tooltip' title ='Usuario Existente'  data-pjax = 0></span>";
                        }
                    } else {
                        return "<span class = 'glyphicon glyphicon-user' data-toggle = 'tooltip' title ='En espera de estado en Generar Aspirante'  data-pjax = 0></span>";
                    }
                },
            ],
        ],
    ],
])
?>

