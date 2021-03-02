<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;

//print_r($model);
academico::registerTranslations();
?>

<?=

PbGridView::widget([
    'id' => 'Tbg_Estudiantes',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Nombres',
            'header' => Yii::t("formulario", "First Names"),
            'value' => 'nombres',
        ],
        [
            'attribute' => 'Cedula',
            'header' => academico::t("diploma", "DNI"),
            'value' => 'dni',
        ],
        [
            'attribute' => 'Carrera',
            'header' => academico::t("Academico", "Academic unit"),
            'value' => 'undidad',
        ],
        [
            'attribute' => 'Modalidad',
            'header' => academico::t("matriculacion", "Modality"),
            'value' => 'modalidad',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => academico::t("Academico", "Career/Program"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    if (strlen($model['carrera']) > 30) {
                        $texto = '...';
                    }
                    return Html::a('<span>' . substr($model['carrera'], 0, 20) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['carrera']]);
                },
            ],
        ],
        [
            'attribute' => 'matricula',
            'header' => academico::t("Academico", 'Enrollment Number'),
            'value' => 'matricula',
        ],
        [
            'attribute' => 'categoria',
            'header' => Yii::t("formulario", "Category"),
            'value' => 'categoria',
        ],
        [
            'attribute' => 'fechacreacion',
            'header' => Yii::t("formulario", 'Date Create'),
            'value' => 'fecha_creacion',
        ],
        [
            'attribute' => 'estado',
            'header' => Yii::t("formulario", 'Status'),
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['class' => 'text-center'],
            'format' => 'html',
            'value' => function ($model) {
                if ($model["estado"] == 'Inactivo')
                    return '<small class="label label-danger">Inactivo</small>';
                else if ($model["estado"] == 'Activo')
                    return '<small class="label label-success">Activo</small>';
                else
                    return '<small class="label label-warning">No Estudiante</small>';
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{create} {view} {activar} {inactivar} ', // {matriculacion}
            'buttons' => [
                'create' => function ($url, $model) {
                    if ($model['est_id'] < 1) {
                        return Html::a('<span class="glyphicon glyphicon glyphicon-file"></span>', Url::to(['/academico/estudiante/new', 'per_id' => base64_encode($model['per_id'])]), ["data-toggle" => "tooltip", "title" => "Crear Estudiante", "data-pjax" => 0]);
                    } else {
                        return '<span class="glyphicon glyphicon glyphicon-file"></span>';
                    }
                },
                'view' => function ($url, $model) {
                    if ($model['est_id'] > 1 && $model["estado"] == 'Activo') {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/academico/estudiante/view', 'per_id' => base64_encode($model['per_id']), 'est_id' => base64_encode($model['est_id'])]), ["data-toggle" => "tooltip", "title" => "Ver Estudiante", "data-pjax" => 0]);
                    } else {
                        return '<span class="glyphicon glyphicon glyphicon-eye-open"></span>';
                    }
                },
                /*'matriculacion' => function ($url, $model) {
                    if ((!isset($model['registroOnline']) || $model['registroOnline'] == 0) && $model['matricula'] != "") {
                        return Html::a('<span class="fa fa-user-plus"></span>', Url::to(['/academico/matriculacion/index', 'per_id' => base64_encode($model['per_id'])]), ["data-toggle" => "tooltip", "title" => academico::t('matriculacion', 'Register Student'), "data-pjax" => 0]);
                    } else {
                        return '<span class="fa fa-user-plus"></span>';
                    }
                },*/
                'activar' => function ($url, $model) {
                    if ($model['est_id'] > 1 && $model["estado"] == 'Inactivo') {
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', "#", ['onclick' => "estadoestudiante(" . $model['est_id'] . ", '1');", "data-toggle" => "tooltip", "title" => "Activar Estudiante", "data-pjax" => 0]);
                    } else {
                        return '<span class="glyphicon glyphicon glyphicon-ok"></span>';
                    }
                },
                'inactivar' => function ($url, $model) {
                    if ($model['est_id'] > 1 && $model["estado"] == 'Activo') {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', "#", ['onclick' => "estadoestudiante(" . $model['est_id'] . ", '0');", "data-toggle" => "tooltip", "title" => "Inactivar Estudiante", "data-pjax" => 0]);
                    } else {
                        return '<span class="glyphicon glyphicon glyphicon-remove"></span>';
                    }
                }
            ],
        ],
    ],
])
?>
