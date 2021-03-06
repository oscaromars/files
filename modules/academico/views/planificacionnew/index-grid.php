<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\models\ObjetoModulo;
?>

<?=
    PbGridView::widget([
        'id' => 'grid_planificaciones_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'PeriodoAcademico',
                'header' => 'Periodo Academico',
                'value' => 'PeriodoAcademico',
            ],
            [
                'attribute' => 'Modalidad',
                'header' => 'Modalidad',
                'value' => 'Modalidad',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Accion',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{download} {approve} {deny}',
                'buttons' => [
                    'download' => function ($url, $model) {
                        return Html::a('<span style="margin-left: 2px;margin-right: 2px;" class="glyphicon glyphicon-download"></span>', null, ["data-toggle" => "tooltip", "title" => "Descargar", "onclick" => "descargarPlanificacionestu('" . $model['id'] . "')"]);
                    },
                ],
            ],
        ],
    ])
?>
