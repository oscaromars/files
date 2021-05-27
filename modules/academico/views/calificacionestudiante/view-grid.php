<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>

<div class="table-responsive">

</div>

<div>
    <?=
    PbGridView::widget([
        'id' => 'Tbg_Calificaciones_Por_Periodo',
        'showExport' => true,
        // 'fnExportEXCEL' => "exportExcel",
        // 'fnExportPDF' => "exportPdf",
        'tableOptions' => [
            'class' => 'table table-striped',
        ],
        'options' => [
            'class' => 'table-responsive',
        ],
        'dataProvider' => $notas_estudiante,
        // 'pajax' => true,
        'columns' =>
        [            
            [
                'attribute' => 'Parcial',
                'header' => academico::t("Academico", "Partial"),
                'value' => 'parcial',
            ],
            [
                'attribute' => 'Codigo',
                'header' => academico::t("estudiantes", "Asynchronous"),
                'value' => '0',
            ],
            [
                'attribute' => 'Paralelo',
                'header' => academico::t("estudiantes", "Synchronous"),
                'value' => '1',
            ],
            [
                'attribute' => 'Horario',
                'header' => academico::t("estudiantes", "Autonomous"),
                'value' => '2',
            ],
            [
                'attribute' => 'Carga',
                'header' => academico::t("estudiantes", "Evaluation"),
                'value' => '3',
            ],
            [
                'attribute' => 'Carga',
                'header' => academico::t("estudiantes", "Exam"),
                'value' => '4',
            ],
            [
                'attribute' => 'Carga',
                'header' => academico::t("estudiantes", "Grade"),
                'value' => 'promedio',
            ],
            [
                'attribute' => 'Asistencia',
                'header' => academico::t("estudiantes", "Asistencia"),
                'value' => 'asistencia',
            ]
        ],
    ])
    ?>
</div>

<br>
<?php if($supletorio): ?>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-4 control-label"><?= academico::t("estudiantes", "Extension") ?></label>
            <p class="col-lg-10 col-md-7 col-sm-8 col-xs-8 control-label"><?= $supletorio ?></p> 
        </div>       
    </div> 
<?php endif; ?>