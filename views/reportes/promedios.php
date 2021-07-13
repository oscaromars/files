<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use kartik\grid\GridView;
use app\modules\academico\Module as academico; 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="panel panel-primary">
  <div class="panel-heading">
      <h3 class="panel-title">Reporte Promedios</h3>
  </div>
  <div>
 
    <?=

    PbGridView::widget([
        'id' => 'Tbg_Registro_promedios',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelpromedios",
        'dataProvider' => $model,
        'columns' => [
           
            [
                'attribute' => 'carrera',
                'header' => academico::t("Academico", "Carrera"),
                'value' => 'carrera',
              //'group' => false,
            ],
            [
                'attribute' => 'estudiante',
                'header' => academico::t("Academico", "Nombres Completos"),
                'value' => 'estudiante',
                //'group' => false, // enable grouping
            ],
            [
                'attribute' => 'asignatura',
                'header' => academico::t("Academico", "Asignatura"),
                'value' => 'asignatura',
                //'group' => false, // enable grouping
            ],           
            [
                'attribute' => 'promedio',
                'header' => academico::t("Academico", "Promedio"),
                'value' => 'promedio',
              
            ],
        ],
    ]);
    ?>
  </div>
</div>