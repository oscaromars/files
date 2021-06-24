<?php

use app\modules\academico\models\DistributivoAcademico;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php echo $this->render('_searchdistributivo_posgrado', ['model' => $searchModel]); ?>

<div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Reporte Distributivo Posgrado</h3>
    </div>
    <div>
      <?=

        PbGridView::widget([
            'id' => 'Tbg_ReporteDistributivo Posgrado',
            'showExport' => true,
            'fnExportEXCEL' => "exportExceldistributivoposgrado",
            'dataProvider' => $dataProvider,
            'columns' => [
               
                [
                    'attribute' => 'docente',
                    'header' => academico::t("Academico", "Profesor"),
                    'value' => 'docente',
                    //'group' => true,
                ],
                
                [
                    'attribute' => 'titulo_tercel_nivel',
                    'header' => academico::t("Academico", "3er. Nivel"),
                    'value' => 'titulo_tercel_nivel',
                    //'group' => true,
                ],
                [
                    'attribute' => '4to Nivel',
                    'header' => academico::t("Academico", "4to Nivel"),
                    'value' => 'titulo_cuarto_nivel',
                    //'group' => true,
                ],
                [
                    'attribute' => 'maestria',
                    'header' => academico::t("Academico", "Maestría "),
                    'value' => 'maestria',
                    //'group' => true,
                ],
                [
                    'attribute' => 'paralelo',
                    'header' => academico::t("Academico", "Grupo Paralelo"),
                    'value' => 'paralelo',
                    //'group' => true,
                ],
                 
                [
                    'attribute' => 'materia',
                    'header' => academico::t("Academico", "Materias"),
                    'value' => 'materia',
                  
                ],
                [
                    'attribute' => 'dias',
                    'header' => academico::t("Academico", "Días"),
                    'value' => 'dias',
                    //'group' => true, // enable grouping
                
                ],
                [
                    'attribute' => 'hora',
                    'header' => academico::t("Academico", "Hora"),
                    'value' => 'hora',
                //    'pageSummary' => 'Page Summary',
                   
                ],
                [
                    'attribute' => 'num_est',
                    'header' => academico::t("Academico", "No. Estudiantes"),
                    'value' => 'num_est',
                //    'pageSummary' => 'Page Summary',
                   
                ],
                 
                [
                    'attribute' => 'total_horas_dictar',
                    'header' => academico::t("Academico", "Total  Horas  a dictar"),
                    'value' => 'total_horas_dictar',
                //    'pageSummary' => 'Page Summary',
                   
                ],
                [
                    'attribute' => 'modalidad',
                    'header' => academico::t("Academico", "Modalidad"),
                    'value' => 'modalidad',
                   // 'pageSummary' => 'Page Summary',
                   
                ],
                [
                    'attribute' => 'aula',
                    'header' => academico::t("Academico", "Aula"),
                    'value' => 'aula',
                   // 'pageSummary' => 'Page Summary',
                   
                ],
                [
                    'attribute' => 'credito',
                    'header' => academico::t("Academico", "Total  crédito"),
                    'value' => 'credito',
                   // 'pageSummary' => 'Page Summary',
                   
                ],
            ],
        ]);
        ?>
    </div>
</div>

