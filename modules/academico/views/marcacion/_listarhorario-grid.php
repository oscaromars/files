<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;

admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'PbHorario',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelhorario",
        'fnExportPDF' => "exportPdfhorario",
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'profesor',
                'header' => Yii::t("formulario", "Teacher"),
                'value' => 'profesor',
            ],  
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Matter"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (strlen($model['materia']) > 30) {
                            $texto = '...';
                        }
                        return Html::a('<span>' . substr($model['materia'], 0, 30) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['materia']]);
                    },
                ],
            ],
            [
                'attribute' => 'Periodo',
                'header' => academico::t("Academico", "Period"),
                'value' => 'periodo',
            ],
            [
                'attribute' => 'Unidad',
                'header' => academico::t("Academico", "Aca. Uni."),
                'value' => 'unidad',
            ], 
            [
                'attribute' => 'Modalidad',
                'header' => academico::t("Academico", "Modality"),
                'value' => 'modalidad',
            ], 
            [
                'attribute' => 'Fechaclase',
                'header' => academico::t("Academico", "Class date"),
                'value' => 'fecha_clase',
            ],
            [
                'attribute' => 'Dia',
                'header' => academico::t("Academico", "Day"),
                'value' => 'dia_descripcion',
            ], 
            [
                'attribute' => 'Horaini',
                'header' => academico::t("Academico", "Hour start date"),
                'value' => 'hape_hora_entrada',
            ],            
            [
                'attribute' => 'Horafin',
                'header' => academico::t("Academico", "Hour end date"),
                'value' => 'hape_hora_salida',
            ],                        
        ],
    ])
    ?>
</div>   
