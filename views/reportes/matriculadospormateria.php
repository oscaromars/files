<?php

use app\modules\academico\models\DistributivoAcademico;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php echo $this->render('_form_Matriculadosmateria', ['model' => $searchModel]); ?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Reporte Matriculados por Materia</h3>
    </div>
    <div>
        <?=
        PbGridView::widget([
            'id' => 'Tbg_Registro_matriculadospormateria',
            'showExport' => true,
            'fnExportEXCEL' => "exportExcel",
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'periodo',
                    'header' => academico::t("Academico", "Período"),
                    'value' => 'periodo',
                  
                ],    
                [
                    'attribute' => 'estudiante',
                    'header' => academico::t("Academico", "Estudiantes"),
                    'value' => 'estudiante',
                ],
                [
                    'attribute' => 'cedula',
                    'header' => academico::t("Academico", "Cédula "),
                    'value' => 'cedula',
                ],
                [
                    'attribute' => 'materia',
                    'header' => academico::t("Academico", "Asignatura"),
                    'value' => 'materia',
                  
                ],
                [
                    'attribute' => 'unidad',
                    'header' => academico::t("Academico", "Unidad Académica"),
                    'value' => 'unidad',
                ],
                [
                    'attribute' => 'modalidad',
                    'header' => academico::t("Academico", "Modalidad"),
                    'value' => 'modalidad',
                  
                ],
                [
                    'attribute' => 'n_matricula',
                    'header' => academico::t("Academico", "Matrícula"),
                    'value' => 'n_matricula',
                
                ],
                [
                    'attribute' => 'carrera',
                    'header' => academico::t("Academico", "Carrera"),
                    'value' => 'carrera',
                  
                ],
            ],
        ]);
        ?>
    </div>
</div>