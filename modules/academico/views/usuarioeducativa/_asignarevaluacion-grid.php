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
<!--<div></br></div>-->
<div>
    <?=
    PbGridView::widget([
        'id' => 'Tbg_Asignar_Evaluacion',
        'showExport' => true,
        // 'fnExportEXCEL' => "exportExcelEduregistro",
        // 'fnExportPDF' => "exportPdfEduregistro",
        'dataProvider' => $model,
        'columns' =>
        [         
            [
                'attribute' => 'Período',
                'header' => academico::t("Academico", "Period"),
                'value' => 'Periodo',
            ],
            [
                'attribute' => 'Nombre',
                'header' => Yii::t("formulario", "Complete Names"),
                'value' => 'Nombre',
            ], 
            [
                'attribute' => 'Modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'Modalidad',
            ],            
            [
                'attribute' => 'Aula',
                'header' => Yii::t("formulario", "Subject"),
                'value' => 'Aula',
            ],
            [
                'attribute' => 'Unidad',
                'header' => Yii::t("formulario", "Unit"),
                'value' => 'Unidad',
            ],
            [
                'attribute' => 'Evaluacion',
                'header' => Yii::t("formulario", "Evaluation"),
                'format' => 'html',
                'value' => function ($model) {
                    if (isset($model["evaluacion"]))
                        return $model["evaluacion"];                   
                    else
                        return '<small class="label label-danger">No Asignado</small>';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Academico::t("matriculacion", "Select"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $model) {
                        //echo(print_r($model['ceest_estado_bloqueo']));
                        if($model['ceest_estado_bloqueo'] == 'B')
                            return Html::checkbox("", false, ["value" => $model['ceest_id']]);
                        else
                            return '';
                    },
                ],
            ],
        ],
    ])
    ?>
</div>