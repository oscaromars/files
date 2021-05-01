<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <?=
    PbGridView::widget([
        'id' => 'Tbg_AsignarCurso',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelEduasignar",
        'fnExportPDF' => "exportPdfEdurasignar",
        'dataProvider' => $model,
        'columns' =>
        [
            [
                'attribute' => 'Periodo',
                'header' => Yii::t("formulario", "Period"),
                'value' => 'periodo',
            ],
            /*[
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Period"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (strlen($model['periodo']) > 10) {
                            $texto = '...';
                        }
                        return Html::a('<span>' . substr($model['periodo'], 0, 10) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['periodo']]);
                    },
                ],
            ],
            [
                'attribute' => 'unidad_academico',
                'header' => academico::t("Academico", "Aca. Uni."),
                'value' => 'unidad',
            ],
            [
                'attribute' => 'modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'modalidad',
            ],            
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Subject"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (strlen($model['asignatura']) > 30) {
                            $texto = '...';
                        }
                        return Html::a('<span>' . substr($model['asignatura'], 0, 20) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['asignatura']]);
                    },
                ],
            ],*/
            [
                'attribute' => 'DNI',
                'header' => Yii::t("formulario", "DNI"),
                'value' => 'identificacion',
            ],
            [
                'attribute' => 'Estudiante',
                'header' => Yii::t("formulario", "Student"),
                'value' => 'estudiante',
            ],            
            [
                'attribute' => 'Estado',
                'header' => Yii::t("formulario", 'Status'),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'value' => function ($model) {
                    if (empty($model["estado_asignado"]))
                        return '<small class="label label-danger">No Asignado</small>';                   
                    else
                        return '<small class="label label-success">Asignado</small>';
                },
            ],
            [   
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Assign"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $model) {
                        if (empty($model["estado_asignado"]))                                                  
                        return Html::checkbox("", false, ["value" => $model['est_id'], "disabled" => false]);
                        else
                        return Html::checkbox("", false, ["value" => $model['est_id'], "disabled" => true]);
                    },
                ],
            ],
        ],
    ])
    ?>
</div>