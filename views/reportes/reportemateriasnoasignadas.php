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
<?php echo $this->render('_searchmateriasnoasignadas', ['model' => $searchModel]); ?>


<div class="panel panel-primary">
  <div class="panel-heading">
      <h3 class="panel-title">Reporte Materias No Asignadas</h3>
  </div>
  <div>
      <?=

          PbGridView::widget([
              'id' => 'Tbg_ReporteMateriasnoAsignadas',
              'showExport' => true,
              'fnExportEXCEL' => "exportExcelmateriasnoasignadas",
              'dataProvider' => $dataProvider,
              'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                  'attribute' => 'id',
                  'header' => academico::t("Academico", "Codigo"),
                  'value' => 'id',
                   
                ],
                [
                  'attribute' => 'name',
                  'header' => academico::t("Academico", "Materia"),
                  'value' => 'name',
                  
                ],
                [
                  'attribute' => 'mpp_num_paralelo',
                  'header' => academico::t("Academico", "Paralelo"),
                  'value' => 'mpp_num_paralelo',
                  
                ],
              ],
            ]);
          ?>
        </div>
    </div>


