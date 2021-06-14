<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\academico\Module as academico; 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php echo $this->render('_form_reportepromedio', ['model' => $searchModel]); ?>

 
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
        'heading' => 'Reporte Promedios'
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