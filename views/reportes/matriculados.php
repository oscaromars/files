<?php

use app\modules\academico\models\DistributivoAcademico;
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
<?php echo $this->render('_form_Matriculados', ['model' => $searchModel]); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Reporte Matriculados</h3>
        </div>
        <div>
            <?=

                PbGridView::widget([
                    'id' => 'Tbg_Registro_matriculados',
                    'showExport' => true,
                    'fnExportEXCEL' => "exportExcelmatriculados",
                    'dataProvider' => $dataProvider,
                    'columns' => [
                       
                        [
                            'attribute' => 'estudiante',
                            'header' => academico::t("Academico", "Estudiantes"),
                            'value' => 'estudiante',
                        ],
                        [
                            'attribute' => 'cedula',
                            'header' => academico::t("Academico", "Cedula "),
                            'value' => 'cedula',
                        ],
                        [
                            'attribute' => 'semestre',
                            'header' => academico::t("Academico", "Semestre Academico "),
                            'value' => 'semestre',
                        ],
                        [
                            'attribute' => 'carrera',
                            'header' => academico::t("Academico", "Carrera"),
                            'value' => 'carrera',
                          
                        ],
                        [
                            'attribute' => 'unidad',
                            'header' => academico::t("Academico", "Unidad Academico"),
                            'value' => 'unidad',
                        ],
                         
                        [
                            'attribute' => 'modalidad',
                            'header' => academico::t("Academico", "Modalidad"),
                            'value' => 'modalidad',
                          
                        ],
                        [
                            'attribute' => 'n_matricula',
                            'header' => academico::t("Academico", "Matricula"),
                            'value' => 'n_matricula',
                        
                        ],
                    ],
                ]);
            ?>
        </div>
    </div>

  