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
        'id' => 'Pbg_Historialestudiante',
        
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelEstudiante",
        'fnExportPDF' => "exportPdfEstudiante",
	'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'dataProvider' => $model,
        'columns' => [
            
            [
                'attribute' => 'Semestre',
                'header' => Yii::t("formulario", "Semestres"),
                'value' => 'semestre',
            ],

            [
                'attribute' => 'Codigo Asignatura',
                'header' => Yii::t("formulario", "Codigo Asignatura"),
                'value' => 'made_codigo_asignatura',
            ],

            [
                'attribute' => 'Periodo',
                'header' => Yii::t("formulario", "Periodo Academico"),
                'value' => 'periodo',
            ],

            [
                'attribute' => 'Bloque Academico',
                'header' => Yii::t("formulario", "Bloque Academico"),
                'value' => 'baca_nombre',
            ],

            [
                'attribute' => 'Materia',
                'header' => Yii::t("formulario", "Asignatura"),
                'value' => 'asi_nombre',
            ],
                        
            [
                'attribute' => 'Nota',
                'header' => Yii::t("formulario", "Promedio"),
                'value' => 'pmac_nota',
            ],
            [
                'attribute' => 'Estado',
                'header' => Yii::t("formulario", 'Status'),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'value' => function ($model) {
                    if ($model["enac_asig_estado"] == 'Aprobado')
                        return '<small class="label label-success">Aprobado</small>';
                    else if ($model["enac_asig_estado"] == 'Reprobado')
                        return '<small class="label label-danger">Reprobado</small>';
                    else if ($model["enac_asig_estado"] == 'En curso')
                        return '<small class="label label-primary">En Curso</small>';
                    else
                        return '<small class="label label-warning">Pendiente</small>';
                },

            ],
            
            
        ],
    ])
    ?>
</div> 
