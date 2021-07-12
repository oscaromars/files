<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;

use app\modules\academico\Module as academico; 

//print_r($arr_modalidad);
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Reporte Malla Academica</h3>
    </div>
    <div>
 
        <?=
            PbGridView::widget([
                'id' => 'Tbg_Registro_mallas',
                'showExport' => true,
                'fnExportEXCEL' => "exportExcelmallas",
                'dataProvider' => $model,
                'columns' => [
                    [
                        'attribute' => 'malla',
                        'header' => academico::t("Academico", "Malla Academica"),
                        'value' => 'malla',                
                    ],
                    [
                        'attribute' => 'asignatura',
                        'header' => academico::t("Academico", "Asignatura "),
                        'value' => 'asignatura',
                    ],
                    [
                        'attribute' => 'unidad',
                        'header' => academico::t("Academico", "Unidad "),
                        'value' => 'unidad',
                    ],
                    [
                        'attribute' => 'modalidad',
                        'header' => academico::t("Academico", "Modalidad "),
                        'value' => 'modalidad',
                    ],
                    [
                        'attribute' => 'semestre',
                        'header' => academico::t("Academico", "Semestre"),
                        'value' => 'semestre',
                      
                    ],
                    [
                        'attribute' => 'credito',
                        'header' => academico::t("Academico", "Crédito"),
                        'value' => 'credito',
                    ],
                     
                    /*[
                        'attribute' => 'unidad_estudio',
                        'header' => academico::t("Academico", "Unidad Estudio"),
                        'value' => 'unidad_estudio',
                      
                    ],*/
                    [
                        'attribute' => 'formacion_malla_academica',
                        'header' => academico::t("Academico", "Formacion Malla Academica"),
                        'value' => 'formacion_malla_academica',                
                    ],
                    [
                        'attribute' => 'materia_requisito',
                        'header' => academico::t("Academico", "Materia Requisito"),
                        'value' => 'materia_requisito',                  
                    ],
                    /*[
                        'attribute' => 'carrera',
                        'header' => academico::t("Academico", "Carrera"),
                        'value' => 'carrera',                  
                    ],*/
                ],
            ]);
        ?>
    </div>
</div>