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
        'id' => 'Tbg_CargaHoraria',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelHoras",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        //'pajax' => false,
        'columns' =>
        [
            [
                'attribute' => 'DNI',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'per_cedula',
            ],
            [
                'attribute' => 'docente',
                'header' => academico::t("Academico", "Teacher"),
                'value' => 'docente',
            ],    
            [
                'attribute' => 'semestre',
                'header' => Yii::t("formulario", "Semester"),
                'value' => 'semestre',
            ],    
            [
                'attribute' => 'Docencia',
                'header' => academico::t("Academico", "Teaching"),
                'value' => 'docencia',
            ], 
            [
                'attribute' => 'Tutoria',
                'header' => academico::t("Academico", "Tutorial"),
                'value' => 'tutoria',
            ], 
            [
                'attribute' => 'Investigación',
                'header' => academico::t("Academico", "Investigation"),
                'value' => 'investigacion',
            ], 
            [
                'attribute' => 'Vinculación',
                'header' => academico::t("Academico", "Bonding"),
                'value' => 'vinculacion',
            ], 
            [
                'attribute' => 'Administrativa',
                'header' => academico::t("Academico", "Administrative"),
                'value' => 'administrativa',
            ],            
            [
                'attribute' => 'Administrativa',
                'header' => academico::t("Academico", "Other activities"),
                'value' => 'otras',
            ],    
            [
                'attribute' => 'Administrativa',
                'header' => academico::t("Academico", "Total Hours"),
                'value' => 'total',
            ],      
                    
            /*[
                'class' => 'yii\grid\ActionColumn',
                'header' => academico::t("Academico", "Career/Program"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span>' . substr($model['carrera'], 0,10)  . '..</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['carrera']]);
                    },
                ],               
            ],*/                                  
        ],
    ])
    ?>
</div>