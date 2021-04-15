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
        'id' => 'Tbg_Registro_educativa',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelEduregistro",
        'fnExportPDF' => "exportPdfEduregistro",
        'dataProvider' => $model,
        'columns' =>
        [
            /*[
                'attribute' => 'Periodo',
                'header' => Yii::t("formulario", "Period"),
                'value' => 'periodo',
            ],*/
            [
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
            ],
            [
                'attribute' => 'DNI',
                'header' => Yii::t("formulario", "DNI"),
                'value' => 'identificacion',
            ],
            [
                'attribute' => 'Estudiante',
                'header' => Yii::t("formulario", "Complete Names"),
                'value' => 'estudiante',
            ],           
            [
                'attribute' => 'Estado',
                'header' => Yii::t("formulario", 'Status'),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'value' => function ($model) {
                    if ($model["pago"] == 'No Autorizado')
                        return '<small class="label label-danger">No Autorizado</small>';                   
                    else
                        return '<small class="label label-success">Autorizado</small>';
                },
            ],
            [
                'attribute' => 'Estado_educativa',
                'header' => Yii::t("formulario", 'Status').' Educativa',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'value' => ' ' /*function ($model) {
                    if ($model["pago"] == 'No Autorizado') // Cambiar al estado de bloqueo
                        return '<small class="label label-danger">No Autorizado</small>';                   
                    else
                        return '<small class="label label-success">Autorizado</small>';
                },*/
            ], 
            [   // SE DEBE ANALIZAR BIEN QUE VALUE SE CAPTURA EN EL CHECK PARA EL DESBLOQUEO
                'class' => 'yii\grid\ActionColumn',
                'header' => Academico::t("matriculacion", "Select"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $model) {
                        return Html::checkbox("", false, ["value" => $model['est_id']]);
                    },
                ],
            ],
        ],
    ])
    ?>
</div>