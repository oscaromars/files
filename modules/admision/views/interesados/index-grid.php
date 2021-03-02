<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>
<?=

PbGridView::widget([
    'id' => 'TbG_Interesado',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'dataProvider' => $model,
    'fnExportPDF' => "exportPdfAspirante",
    'columns' =>
    [
        [
            'attribute' => 'DNI',
            'header' => Yii::t("formulario", "DNI"),
            'value' => 'DNI',
        ],
        [
            'attribute' => 'Fecha',
            'header' => Yii::t("formulario", "Date"),
            'value' => 'fecha_interes',
        ],
        [
            'attribute' => 'Nombres',
            'header' => Yii::t("formulario", "Name"),
            'value' => 'nombres',
        ],
        [
            'attribute' => 'Apellidos',
            'header' => Yii::t("formulario", "Last Names"),
            'value' => 'apellidos',
        ],
        [
            'attribute' => 'Empresa',
            'header' => Yii::t("formulario", "Company"),
            'value' => 'empresa',
        ],
        [
            'attribute' => 'Agente',
            'header' => Yii::t("formulario", "User login"),
            'value' => 'Agente',
        ],
        [
            'attribute' => 'unidad_academica',
            'header' => Yii::t("formulario", "Academic unit"),
            'value' => 'unidad',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => academico::t("Academico", "Career/Program/Course"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    if ($model['carrera'] != '') {
                        $texto = substr($model['carrera'], 0, 20). '...';
                    }
                    else{
                        $texto = '';
                    }                        
                    return Html::a('<span>' . $texto . '</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['carrera']]);
                },
            ],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{solicitudes} {ficha}', //
            'buttons' => [
                'solicitudes' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-th-large"></span>', Url::to(['/admision/solicitudes/listarsolicitudxinteresado', 'id' => base64_encode($model['id']), 'perid' => base64_encode($model['per_id'])]), ["data-toggle" => "tooltip", "title" => "Mostrar Solicitudes", "data-pjax" => 0]);
                },
                'ficha' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-user"></span>', Url::to(['/academico/ficha/update', 'perid' => base64_encode($model['per_id'])]), ["data-toggle" => "tooltip", "title" => "Ficha Aspirante", "data-pjax" => 0]);
                },
            ],
        ],
    ],
])
?>

