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
<?php echo $this->render('_form_Inscritos', ['model' => $searchModel]); ?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Reporte Inscritos</h3>
    </div>
    <div>
        <?=
        PbGridView::widget([
            'id' => 'Tbg_Registro_inscritos',
            'showExport' => true,
            'fnExportEXCEL' => "exportExcelinscritosreporte",
            'dataProvider' => $dataProvider,
            'columns' => [
            	[
                    'attribute' => 'nombres',
                    'header' => academico::t("Academico", "Nombre Completo"),
                    'value' => 'nombres',
                  
                ],       
                [
                    'attribute' => 'cedula',
                    'header' => academico::t("Academico", "Cédula"),
                    'value' => 'cedula',
                ],
                [
                    'attribute' => 'correo',
                    'header' => academico::t("Academico", "Correo "),
                    'value' => 'correo',                ],
                [
                    'attribute' => 'telefono',
                    'header' => academico::t("Academico", "Teléfono"),
                    'value' => 'telefono',
                  
                ],
                [
                    'attribute' => 'matricula',
                    'header' => academico::t("Academico", "Matrícula"),
                    'value' => 'matricula',
                ],
                [
                    'attribute' => 'unidad',
                    'header' => academico::t("Academico", "Unidad Academica"),
                    'value' => 'unidad',
                
                ],
                [
                    'attribute' => 'modalidad',
                    'header' => academico::t("Academico", "Modalidad"),
                    'value' => 'modalidad',
                  
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