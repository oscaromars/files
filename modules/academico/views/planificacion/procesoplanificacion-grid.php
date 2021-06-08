<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Utilities;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module as admision;

admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'PbPlanificaestudiante',
        'showExport' => false,
        'fnExportEXCEL' => "exportExcelplanifica",
        'fnExportPDF' => "exportPdfplanifica",
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'Asignatura',
                'header' => Yii::t("formulario", "Asignatura"),
                'options' => ['width' => '590'],
                'value' => 'asignatura',
            ],
            [
                'attribute' => 'Jornada',
                'header' => Yii::t("formulario", "Jornada"),
                'value' => 'jor_materia',
            ],
            [
                'attribute' => 'Bloque',
                'header' => Yii::t("formulario", "Bloque"),
                'value' => 'Bloque 1',
            ],
            [
                'attribute' => 'Modalidad',
                'header' => Yii::t("formulario", "Modalidad"),
                'value' => 'modalidad',
            ],
            [
                'attribute' => 'Horario',
                'header' => Yii::t("formulario", "Horario"),
                'value' => 'Hora 1',
            ],
           
            /*[
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("crm", "Carrera"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (strlen($model['pes_carrera']) > 30) {
                            $texto = '...';
                        }
                        return Html::a('<span>' . substr($model['pes_carrera'], 0, 30) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['pes_carrera']]);
                    },
                ],
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Accion',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{delete}',
                'buttons' => [
                    
                    'delete' => function ($url, $model) {
                    //if ($model['est_id'] > 1 && $model["estado"] == 'Activo') {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', "#", ['onclick' => "deleteplanestudiante(" . $model['pla_id'] . ", " . $model['per_id'] . ");", "data-toggle" => "tooltip", "title" => "Eliminar Planificación", "data-pjax" => 0]);
                    /*} else {
                        return '<span class="glyphicon glyphicon glyphicon-remove"></span>';
                    }*/
                }
                ],
            ],
        ],
    ])
    ?>
</div>   
