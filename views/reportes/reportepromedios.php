<?php

//use app\modules\academico\models\DistributivoAcademicoEstudiante;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Utilities;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico; 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<?php echo $this->render('_form_promedios', ['estudiante' => $estudiante]);?>

 <?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<?=
PbGridView::widget([
        'id' => 'Tbg_Registro_promedios',
        //'showExport' => true,
        //'fnExportEXCEL' => "exportExcelEduregistro",
        //'fnExportPDF' => "exportPdfEduregistro",
        'dataProvider' => $dataProvider,
        'columns' =>
        [         
            
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
    ])

?>