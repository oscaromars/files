<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\models\Matriculacion;
use app\modules\academico\Module as academico;

academico::registerTranslations();

$matriculacion_model = new Matriculacion();
$today = date("Y-m-d H:i:s");
$result_process = $matriculacion_model->checkToday($today);
$showCancellation = false;

if (count($result_process) > 0) $showCancellation = true;
?>

<?=

PbGridView::widget([
    'id' => 'grid_listadoregistrados_list',
    'showExport' => false,
    //'fnExportEXCEL' => "exportExcel",
    //'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Estudiante',
            'header' => academico::t("matriculacion", 'Student'),
            'value' => 'Estudiante',
        ],
        [
            'attribute' => 'Cedula',
            'header' => academico::t("matriculacion", "SSN/Passport"),
            'value' => 'Cedula',
        ],
        [
            'attribute' => 'Carrera',
            'header' => academico::t("matriculacion", 'Program'),
            'value' => 'Carrera',
        ],
        [
            'attribute' => 'Modalidad',
            'header' => academico::t("matriculacion", 'Modality'),
            'value' => 'Modalidad',
        ],
        [
            'attribute' => 'Periodo',
            'header' => academico::t("matriculacion", 'Academic Period'),
            'value' => 'Periodo',
        ],
        [
            'attribute' => 'Estado',
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['class' => 'text-center'],
            'format' => 'html',
            'header' => academico::t("matriculacion", "Status"),
            'value' => function($data) {
                if ($data["Estado"] == "1"){
                    //return '<small class="label label-success">' . academico::t("matriculacion", "Registered Student") . '</small>';
                    return '<small class="label label-success">' . academico::t("matriculacion",$data["DesEstado"]) . '</small>';
                }else{                 
                    return '<small class="label label-danger">' . academico::t("matriculacion",$data["DesEstado"]) . '</small>';
                }
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            //'header' => 'Action',
            'contentOptions' => ['style' => 'text-align: center;'],
            'headerOptions' => ['width' => '60'],
            'template' => '{view} {cancel}',
            'buttons' => [
                'view' => function ($url, $model) {
                    //return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['matriculacion/registry', 'id' => $model['Id'], 'popup' => "true"]), ["onclick" => "showIframePopupRef(this)", "data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    //return '<span class="'.Utilities::getIcon('view').'" onclick="showIframePopupRef(this)" data-href="'.Url::to(['matriculacion/registry', 'id' => $model['Id'], 'popup' => "true"]).'" data-toggle="tooltip" title="'.Yii::t("accion","View").'" ></span>';                        
                    if ($model["Estado"] == "1") {
                        return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['matriculacion/view', 'id' => $model['Id'], 'rama_id' => $model['rama_id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View"), "data-pjax" => 0]);
                    } else {
                        return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['matriculacion/registry', 'id' => $model['Id'], 'rama_id' => $model['rama_id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View"), "data-pjax" => 0]);
                    }
                },
                /*'cancel' => function ($url, $model) use ($showCancellation) {
                    //return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['matriculacion/registry', 'id' => $model['Id'], 'popup' => "true"]), ["onclick" => "showIframePopupRef(this)", "data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    //return '<span class="'.Utilities::getIcon('view').'" onclick="showIframePopupRef(this)" data-href="'.Url::to(['matriculacion/registry', 'id' => $model['Id'], 'popup' => "true"]).'" data-toggle="tooltip" title="'.Yii::t("accion","View").'" ></span>';                        
                    if ($showCancellation) {
                        return Html::a('<span class="fa fa-ban"></span>', Url::to(['matriculacion/anularregistro', 'ron_id' => $model['Id'], 'admin' => '1', 'per_id' => $model['per_id']]), ["data-toggle" => "tooltip", "title" => academico::t("Academico", "Cancel Registration"), "data-pjax" => 0]);
                    } else {
                        return '';
                    }
                },*/
            ],
        ],
    ],
])
?>