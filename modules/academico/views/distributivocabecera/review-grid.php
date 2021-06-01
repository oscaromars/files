<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use kartik\grid\GridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<div>

    <?=
    GridView::widget([
        'id' => 'Tbg_DistribAca_Profesor',
        //'showExport' => true,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $arr_detalle,
        //'pajax' => false,
        'showPageSummary' => true,
        'panel' => [
        'type' => 'primary',
        'heading' => ''
         ], 
        'toolbar' =>  [
        [
            
        ],
       
       
    ],
        'columns' =>    [[
        'class' => 'kartik\grid\SerialColumn',
        'pageSummary' => 'Total/Promedio Horas',
        'pageSummaryOptions' => ['colspan' => 4],
            ],
            [
                'attribute' => 'Profesor',
                'header' => academico::t("Academico", "Teacher"),
                'value' => 'profesor',
            ],
            [
                'attribute' => 'Tipo Asignación',
                'header' => academico::t("Academico", "Assignment Type"),
                'value' => 'tipo_asignacion',
            ],
            [
                'attribute' => 'UnidadAcademica',
                'header' => Yii::t("formulario", "Academic unit"),
                'value' => 'UnidadAcademica',
            ],
            [
                'attribute' => 'Modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'Modalidad',
            ],
            [
                'attribute' => 'Asignatura',
                'header' => Yii::t("formulario", "Subject"),
                'value' => 'Asignatura',
            ],
            [
                'attribute' => 'mpp_num_paralelo',
                'header' => Yii::t("formulario", "# Paralelo"),
                'value' => 'mpp_num_paralelo',
            ],
            [
                'attribute' => 'nroEstudiantes',
                'header' => Yii::t("formulario", "# Estudiante"),
                'value' => 'nroEstudiantes',
            ],
            
            [
                'attribute' => 'total_horas',
                'header' => academico::t("Academico", "Total Horas"),
                'value' => 'total_horas',
                'pageSummary' => true,
            ],
            [
                'attribute' => 'promedio',
                'header' => academico::t("Academico", "Promedio"),
                'value' => 'promedio',
                'pageSummary' => true,
            ],
            ['class' => 'kartik\grid\FormulaColumn',
                'attribute' => 'Horario',
                'header' => academico::t("Academico", "Schedule"),
                'value' => 'horario',
            ],
            
        ],
    ])
    ?>
</div>