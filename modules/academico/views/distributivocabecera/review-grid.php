<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use kartik\grid\GridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
//print_r($arr_detalle);
admision::registerTranslations();
academico::registerTranslations();
//print_r(round($promajustado));
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
        //'showPageSummary' => true,
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
        'pageSummary' => 'Total/Promedio Ajustado',
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
                //'pageSummary' => false,
            ],
            [
                'attribute' => 'promedio',
                'header' => academico::t("Academico", "Promedio"),
                'value' => 'promedio',
                //'hidden' => false,
                /*'value' => function($data){
                    return (empty($data['promedioajustado'])?0:$data['promedioajustado'])/2;
                },
                'pageSummary' => true,*/
            ],
            ['class' => 'kartik\grid\FormulaColumn',
                'attribute' => 'Horario',
                'header' => academico::t("Academico", "Schedule"),
                'value' => 'horario',
            ],
        ],
     ])
     ?>
     <?php
     if (!empty($promajustado)){ ?>
     <!-- <tr><td style="width:50px;"><h4><b>Total/Promedio Ajustado</b></h4></td><td></td><td></td><td></td><td></td><td></td><td><?php echo round($promajustado); ?></td></tr>-->
     <table class="tg">
        <thead>
        <tr>
            <td class="tg-0pky" colspan="2"><h4><b>Total/Promedio Ajustado</b></h4></td>
            <td class="tg-0lax">&nbsp;&nbsp</td>
            <td class="tg-0lax" colspan="2"><h4><?php echo round($promajustado); ?></h4></td>
        </tr>
    </thead>
    </table>
    <?php }?>
</div>
