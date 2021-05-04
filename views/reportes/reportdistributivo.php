<?php

use app\modules\academico\models\DistributivoAcademico;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\academico\Module as academico;
use kartik\mpdf\Pdf;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



?>
<?php echo $this->render('_searchdistributivo', ['model' => $searchModel]); ?>


<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'pjax' => true,
    //  'autoXlFormat' => true,
    'showPageSummary' => true,
    // 'striped' => false,
    'panel' => [
         'type' => GridView::TYPE_PRIMARY,
        'heading' => 'Reporte Distributivo'
    ],

    /*'exportConfig' => [
                GridView::PDF => [
                    'label' => 'PDF',
                    'filename' => 'Distributivo',
                    'title' => 'Distributivo',                        
                    'options' => ['title' => 'Distributivo Académico','author' => 'UTEG'],                        
                ],
        ],
    'export' => [
                'PDF' => [
                    'options' => [
                        'title' => 'UTEG',
                        'subject' => 'UTEG',
                        'author' => 'UTEG',
                        'keywords' => 'NYCSPrep, preceptors, pdf'
                    ]
                ],
            ],*/
    'columns' => [
        // [ 'class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'docente',
            'header' => academico::t("Academico", "DOCENTE"),
            'value' => 'docente',
            'group' => true,
        ],
        [
            'attribute' => 'no_cedula',
            'header' => academico::t("Academico", "NO. CÉDULA"),
            'value' => 'no_cedula',
            'group' => true,
        ],
        [
            'attribute' => 'titulo_tercel_nivel',
            'header' => academico::t("Academico", "TÍTULO TERCER NIVEL"),
            'value' => 'titulo_tercel_nivel',
            'group' => true,
        ],
        [
            'attribute' => 'titulo_cuarto_nivel',
            'header' => academico::t("Academico", "TÍTULO CUARTO NIVEL"),
            'value' => 'titulo_cuarto_nivel',
            'group' => true,
        ],
        [
            'attribute' => 'correo',
            'header' => academico::t("Academico", "CORREO ELECTRÓNICO"),
            'value' => 'correo',
            'group' => true,
        ],
        [
            'attribute' => 'tiempo_dedicacion',
            'header' => academico::t("Academico", "TIEMPO DE DEDICACION"),
            'value' => 'tiempo_dedicacion',
            'group' => true,
        ],
        [
            'attribute' => 'tdis_nombre',
            'header' => academico::t("Academico", "TIPO ASIG"),
            'value' => 'tdis_nombre',
            'group' => true, // enable grouping
        ],
        [
            'attribute' => 'materia',
            'header' => academico::t("Academico", "MATERIA"),
            'value' => 'materia',
        ],
        [
            'attribute' => 'total_horas_dictar',
            'header' => academico::t("Academico", "TOTAL HORAS A DICTAR"),
            'value' => 'total_horas_dictar',
        //    'pageSummary' => 'Page Summary',
        ],
        [
            'attribute' => 'promedio',
            'header' => academico::t("Academico", "PROMEDIO"),
            'value' => 'promedio',
        // 'pageSummary' => 'Page Summary',
        ],
    ],
    ]);
?>

