<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;

academico::registerTranslations();
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
            'header' => academico::t("matriculacion", "DNI"),
            'value' => 'Cedula',
        ],
        [
            'attribute' => 'Carrera',
            'header' => academico::t("matriculacion", 'Career'),
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
            'attribute' => 'Materias',
            'header' => academico::t("matriculacion", 'Number Subjects'),
            'value' => 'Materias',
        ],
        [
            'attribute' => 'Creditos',
            'header' => academico::t("matriculacion", 'Number Credits'),
            'value' => 'Creditos',
        ],
        [
            'attribute' => 'Estado',
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['class' => 'text-center'],
            'format' => 'html',
            'header' => academico::t("matriculacion", "Status"),
            'value' => function($data) {
                if ($data["Estado"] == "1")
                    return '<small class="label label-success">' . academico::t("matriculacion", "Registered Student") . '</small>';
                else
                    return '<small class="label label-danger">' . academico::t("matriculacion", "Unregistered Student") . '</small>';
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            //'header' => 'Action',
            'contentOptions' => ['style' => 'text-align: center;'],
            'headerOptions' => ['width' => '60'],
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    //return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['matriculacion/registry', 'id' => $model['Id'], 'popup' => "true"]), ["onclick" => "showIframePopupRef(this)", "data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    //return '<span class="'.Utilities::getIcon('view').'" onclick="showIframePopupRef(this)" data-href="'.Url::to(['matriculacion/registry', 'id' => $model['Id'], 'popup' => "true"]).'" data-toggle="tooltip" title="'.Yii::t("accion","View").'" ></span>';                        
                    if ($model["Estado"] == "1") {
                        return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['matriculacion/view', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View"), "data-pjax" => 0]);
                    } else {
                        return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['matriculacion/registry', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View"), "data-pjax" => 0]);
                    }
                },
            ],
        ],
    ],
])
?>