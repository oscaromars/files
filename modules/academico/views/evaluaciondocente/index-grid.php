<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Persona;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;

admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
   <?=
    PbGridView::widget([
        'id' => 'PbEvaluacionDocente',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'profesor',
                'header' => Yii::t("formulario", "Teacher"),
                'value' => 'profesor',
            ],     
            [
                'attribute' => 'semestre',
                'header' => Yii::t("formulario", "Semester"),
                'value' => 'semestre_nombre',
            ],           
            [
                'attribute' => 'valores',
                'header' => academico::t("Academico", "Type Evaluation | Hours | Evaluation"),
                'value' => 'valores',
            ],
            [
                'attribute' => 'completa',
                'header' => academico::t("Academico", "Evaluation Completed"),
                'value' => 'evaluacion_completa',
            ],
            [
                'attribute' => 'totalhora',
                'header' => academico::t("Academico", "Total Hours"),
                'value' => 'total_hora',
            ],
            [
                'attribute' => 'totalevaluacion',
                'header' => academico::t("Academico", "Total Evaluation"),
                'value' => 'total_evaluacion',
            ],          
        ],
    ])
    ?>
</div>   
