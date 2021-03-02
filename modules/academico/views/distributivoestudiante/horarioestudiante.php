<?php

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
academico::registerTranslations();
use app\modules\admision\Module as admision;

admision::registerTranslations();

//print_r($arr_horario);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>    
    <?php //if(!empty($arr_horario)) {?>
      <?=
    PbGridView::widget([
        'id' => 'PbHorarioestudiante',
        //'showExport' => true,
        //'fnExportEXCEL' => "exportExcelhorario",
        //'fnExportPDF' => "exportPdfhorario",
        'dataProvider' => $arr_horario,
        'columns' => [
            
            [
                  'attribute' => 'profesor',
                  'header' => academico::t("Academico", "Teacher"),
                  'value' => 'profesor',
            ],
            [
                'attribute' => 'jornada',
                'header' => academico::t("Academico", "Working day"),
                'value' => 'daca_jornada',
            ],            
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => academico::t("Academico", "Subject"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $arr_horario) {
                        if (strlen($arr_horario['materia']) > 30) {
                            $texto = '...';
                        }
                        return Html::a('<span>' . substr($arr_horario['materia'], 0, 30) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $arr_horario['materia']]);
                    },
                ],
            ],
            [
                'attribute' => 'descripcion',
                'header' => Yii::t("formulario", "Description"),
                'value' => 'daho_descripcion',
            ],
        ],
    ])
    ?> 
       <?php //} else {?>
        <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 600px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Si no se visualiza los horarios, es porque no tiene cargado en el período actual.</div>
          </div>
          </div>
          </div>-->
       <?php //} ?> 
     


