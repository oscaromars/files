<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\models\ObjetoModulo;
use app\widgets\PbSearchBox\PbSearchBox;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
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
           /* [
                'attribute' => 'PeriodoAcademico',
                'header' => 'Semestre Academico ID',
                'value' => 'PeriodoAcademico',
            ],*/
            [
                'attribute' => 'Modalidad',
                'header' => 'Modalidad',
                'value' => 'Modalidad',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Descargar',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{download} {approve} {deny}',
                'buttons' => [
                    'download' => function ($url, $model) {
                        return Html::a('<span style="margin-left: 2px;margin-right: 2px;" id="frm_hasplanning" value="1" class="glyphicon glyphicon-download"></span>', null, ["data-toggle" => "tooltip", "title" => "Descargar", "onclick" => "descargarPlanificacionestu('" . $model['id'] . "')"]);
                    },
                ],
            ],
               [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actualizar',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{refresh} {approve} {deny}',
                'buttons' => [
                    'refresh' => function ($url, $model) {
                         if ($model['estado'] ==Null) {
                        return Html::a('<span style="margin-left: 2px;margin-right: 2px;" id="frm_hasplanning" value="1" class="glyphicon glyphicon-refresh"></span>', null, ["data-toggle" => "tooltip", "title" => "Alcance", "onclick" => "update()"]);}else {
                            return Html::a('<span style="margin-left: 2px;margin-right: 2px;color:gray;objLang" id="frm_hasplanning" value="1" class="glyphicon glyphicon-ban-circle"></span>', null, ["data-toggle" => "tooltip", "title" => "Deshabilitado", "onclick" => "blocked('" . $model['id'] . "')"]);

                        }
                    },
                ],
            ],
               [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Finalizar',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{close} {approve} {deny}',
                'buttons' => [
                    'close' => function ($url, $model) {
                          if ($model['estado'] ==Null) {
                        return Html::a('<span style="margin-left: 2px;margin-right: 2px;" id="frm_hasplanning" value="1" class="glyphicon glyphicon-eject"></span>', null, ["data-toggle" => "tooltip", "title" => "Cerrar Proceso", "onclick" => "closer('" . $model['id'] . "')"]);}else {
                             return Html::a('<span style="margin-left: 2px;margin-right: 2px;color:gray;objLang" id="frm_hasplanning" value="1" class="glyphicon glyphicon-ban-circle"></span>', null, ["data-toggle" => "tooltip", "title" => "Deshabilitado", "onclick" => "blocked('" . $model['id'] . "')"]);


                        }
                    },
                ],
            ],
        ],
    ])
?>
