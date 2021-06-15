<?php

use app\modules\academico\models\DistributivoAcademico;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\academico\Module as academico;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php echo $this->render('_form_Matriculadosmateria', ['model' => $searchModel]); ?>


<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'pjax' => true,
    //  'autoXlFormat' => true,
    'showPageSummary' => true,
    'striped' => false,
    'panel' => [
        'type' => 'primary',
        'heading' => 'Reporte Matriculados por Materia'
    ],
    'exportConfig' => [
        //GridView::CSV => ['label' => 'Save as CSV'],
        // GridView::HTML => [// html settings],
        /*   GridView::PDF => [
          'label' => 'PDF',
          // 'icon' => $isFa ? 'file-pdf-o' : 'floppy-disk',
          'iconOptions' => ['class' => 'text-danger'],
          'showHeader' => true,
          'showPageSummary' => false,
          'showFooter' => true,
          'showCaption' => true,
          'filename' => 'Distributivo',

          'config' => [
          'options' => [
          'title' => "pr",
          'subject' => Yii::t('kvgrid', 'PDF export generated by kartik-v/yii2-grid extension'),
          'keywords' => Yii::t('kvgrid', 'krajee, grid, export, yii2-grid, pdf')
          ],
          ],
          ], */
        GridView::EXCEL => [
            'label' => Yii::t('kvgrid', 'Excel'),
            //'icon' => $isFa ? 'file-excel-o' : 'floppy-remove',
            'iconOptions' => ['class' => 'text-success'],
            'showHeader' => true,
           
            'showFooter' => true,
            'showCaption' => true,
            'filename' => Yii::t('kvgrid', 'grid-export'),
            'alertMsg' => Yii::t('kvgrid', 'The EXCEL export file will be generated for download.'),
            'options' => ['title' => Yii::t('kvgrid', 'Microsoft Excel 95+')],
            'mime' => 'application/vnd.ms-excel',
            'config' => [
                'worksheet' => Yii::t('kvgrid', 'ExportWorksheet'),
                'cssFile' => ''
            ]
        ],
    ],
    'export' => [
        'showConfirmAlert' => false,
        'target' => GridView::TARGET_BLANK,
    ],
    'columns' => [
    	/*[
            'attribute' => 'periodo',
            'header' => academico::t("Academico", "Periodo"),
            'value' => 'periodo',
          
        ],    */   
        [
            'attribute' => 'estudiante',
            'header' => academico::t("Academico", "Estudiantes"),
            'value' => 'estudiante',
            'group' => false,
        ],
        [
            'attribute' => 'cedula',
            'header' => academico::t("Academico", "Cedula "),
            'value' => 'cedula',
            'group' => false,
        ],
        [
            'attribute' => 'materia',
            'header' => academico::t("Academico", "Asignatura"),
            'value' => 'materia',
          
        ],
        [
            'attribute' => 'unidad',
            'header' => academico::t("Academico", "Unidad Academico"),
            'value' => 'unidad',
            'group' => false,
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
            'group' => false, // enable grouping
        
        ],
        [
            'attribute' => 'carrera',
            'header' => academico::t("Academico", "Carrera"),
            'value' => 'carrera',
          
        ],
    ],
]);
?>