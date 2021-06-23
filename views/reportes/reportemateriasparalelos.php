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
<?php echo $this->render('_searchreportemateriasparalelos', ['model' => $searchModel]); ?>


<div class="panel panel-primary">
  <div class="panel-heading">
      <h3 class="panel-title">Reporte Materias Paralelo</h3>
  </div>
  <div>
      <?=

          PbGridView::widget([
              'id' => 'Tbg_ReporteMateriasParalelo',
              'showExport' => true,
              'fnExportEXCEL' => "exportExcelmateriasparalelo",
              'dataProvider' => $dataProvider,
              'columns' => [
                 
                  [
                      'attribute' => 'docente',
                      'header' => academico::t("Academico", "Docente"),
                      'value' => 'docente',
                  ],
                  [
                      'attribute' => 'asignatura',
                      'header' => academico::t("Academico", "Materia"),
                      'value' => 'asignatura',
                  ],
                  [
                      'attribute' => 'dhpa_paralelo',
                      'header' => academico::t("Academico", "Paralelo"),
                      'value' => 'dhpa_paralelo',
                  ],
                  [
                      'attribute' => 'daho_descripcion',
                      'header' => academico::t("Academico", "Horario"),
                      'value' => 'daho_descripcion',
                  ],
                  
              ],
          ]);
          ?>
      </div>
    </div>

