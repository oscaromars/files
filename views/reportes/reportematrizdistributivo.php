<?php

use app\modules\academico\models\DistributivoAcademico;
use yii\helpers\Html;
use yii\helpers\Url;
//use kartik\grid\GridView;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php echo $this->render('reportematrizdistributivo_search', ['model' => $searchModel]); ?>


<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Reporte Matriz Distributivo Docente</h3>
    </div>
    <div>
        <?=

            PbGridView::widget([
                'id' => 'Tbg_ReporteMatrizDistributivo',
                'showExport' => true,
                'fnExportEXCEL' => "exportExcelmatrizdistributivo",
                'dataProvider' => $dataProvider,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [ 
                        'attribute' => 'codigo_ies',
                        'header' => academico::t("Academico", "Cod_IES"),
                        'value' => 'codigo_ies',
                    ],
                    [
                        'attribute' => 'tipo_identificacion',
                        'header' => academico::t("Academico", "Tipo de Identificación"),
                        'value' => 'tipo_identificacion',
                    ],
                    [
                        'attribute' => 'no_cedula',
                        'header' => academico::t("Academico", "Identificación"),
                        'value' => 'no_cedula',
                    ],
                    [
                        'attribute' => 'docente',
                        'header' => academico::t("Academico", "Docente"),
                        'value' => 'docente',
                    ],
                    [
                        'attribute' => 'pro_num_contrato',
                        'header' => academico::t("Academico", "N° Documento"),
                        'value' => 'pro_num_contrato',
                    ],
                    [//'class' => 'kartik\grid\FormulaColumn',
                        'attribute' => 'hora_clase',
                        'header' => academico::t("Academico", "Horas Clase"),
                        'value' => 'hora_clase',
                        
                    ],
                    [
                        'attribute' => 'hora_tutorias',
                        'header' => academico::t("Academico", "Horas Tutorías"),
                        'value' => 'hora_tutorias',
                    ],
                    
                      [
                        'attribute' => 'hora_administrativa',
                        'header' => academico::t("Academico", "Horas Administrativas"),
                       'value' => function ($model, $key, $index, $widget) {
                           
                            return $model['ddoc_horas'] - ($model['hora_clase'] +$model['hora_otras_actividades']+ $model['hora_ivestigacion']+ $model['hora_tutorias']+ $model['hora_vinculacion']);
                        },
                    ],
                    [
                        'attribute' => 'hora_ivestigacion',
                        'header' => academico::t("Academico", "Horas Investigación"),
                        'value' => 'hora_investigacion',
                    ],
                    [
                        'attribute' => 'hora_vinculacion',
                        'header' => academico::t("Academico", "Horas Vinculación"),
                        'value' => 'hora_vinculacion',
                    ],
                    [
                        'attribute' => 'hora_otras_actividades',
                        'header' => academico::t("Academico", "Horas Otras Actividades"),
                        'value' => 'hora_otras_actividades',
                    ],
                               
                    [
                        'attribute' => 'comun',
                        'header' => academico::t("Academico", "Horas Clase Nivel Técnico"),
                        'value' => 'comun',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'tercel_nivel',
                        'header' => academico::t("Academico", "Horas Clase Tercer Nivel"),
                        'value' => 'tercel_nivel',
                    ],
                    [
                        'attribute' => 'cuarto_nivel',
                        'header' => academico::t("Academico", "Horas Clase Cuarto Nivel"),
                        'value' => 'cuarto_nivel',
                    ],
               
                    [
                        'attribute' => 'comun',
                        'header' => academico::t("Academico", "Calificación Actividades Docencia"),
                        'value' => 'comun',
                    ],
                    [
                        'attribute' => 'comun',
                        'header' => academico::t("Academico", "Calificación Actividades Investigación"),
                        'value' => 'comun',
                    ],
                    /*[
                        'attribute' => 'comun',
                        'header' => academico::t("Academico", "Calificación Actividades Dirección Gestión Académica"),
                        'value' => 'comun',
                    ],*/
                ],
            ]);
            ?>

        </div>
    </div>