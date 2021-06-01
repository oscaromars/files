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
        'id' => 'grid_diploma_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Nombres',
                'header' => Yii::t("formulario", "First Names"),
                'value' => 'Nombres',
            ],
            [
                'attribute' => 'Apellidos',
                'header' => Yii::t("formulario", "Last Names"),
                'value' => 'Apellidos',
            ],
            [
                'attribute' => 'Cedula',
                'header' => academico::t("diploma", "DNI"),
                'value' => 'Cedula',
            ],
            [
                'attribute' => 'Carrera',
                'header' => academico::t("matriculacion", "Career"),
                'value' => 'Carrera',
            ],
            [
                'attribute' => 'Modalidad',
                'header' => academico::t("matriculacion", "Modality"),
                'value' => 'Modalidad',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => academico::t("diploma", "Program/Course"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (strlen($model['Programa']) > 30) {
                            $texto = '...';
                        }
                        return Html::a('<span>' . substr($model['Programa'], 0, 20) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['Programa']]);
                    },
                ],
            ],
            [
                'attribute' => 'FechaInicio',
                'header' => Yii::t("formulario", "Start date"),
                'value' => 'FechaInicio',
            ],
            [
                'attribute' => 'FechaFin',
                'header' => academico::t("diploma", 'End Date'),
                'value' => 'FechaFin',
            ],
            [
                'attribute' => 'Horas',
                'header' => academico::t("diploma", 'Hours'),
                'value' => 'Horas',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{download} {revision}',
                'buttons' => [
                    'download' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-download"></span>', null, ['href' => 'javascript:downloadDiploma('.$model['Id'].')', "data-toggle" => "tooltip", "title" => Yii::t("accion","Download")]);                        
                    },
                    'revision' => function ($url, $model) {
                        if($model['Descarga'] == 1)
                            return Html::a('<span class="glyphicon glyphicon-ok"></span>', null, ['href' => 'javascript:', "data-toggle" => "tooltip", "title" => academico::t("diploma","File Downloaded")]);
                        return "";
                    },
                ],
            ],
        ],
    ])
?>
