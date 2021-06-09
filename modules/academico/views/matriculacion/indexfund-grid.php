<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;

//print_r($model);
academico::registerTranslations();
?>

<?=

PbGridView::widget([
    'id' => 'Tbg_Estudiantes',
    'showExport' => false,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Nombres',
            'header' => Yii::t("formulario", "First Names"),
            'value' => 'nombres',
        ],
        [
            'attribute' => 'Cedula',
            'header' => Yii::t("formulario", "SSN/Passport"),
            'value' => 'dni',
        ],
        [
            'attribute' => 'Carrera',
            'header' => academico::t("Academico", "Academic unit"),
            'value' => 'undidad',
        ],
        [
            'attribute' => 'Modalidad',
            'header' => academico::t("matriculacion", "Modality"),
            'value' => 'modalidad',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => academico::t("Academico", "Program"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    if (strlen($model['carrera']) > 45) {
                        $texto = '...';
                    }
                    return Html::a('' . substr($model['carrera'], 0, 45) . $texto . '');
                },
            ],
        ],
        
        [
            'attribute' => 'matricula',
            'header' => academico::t("Academico", 'Ron ID'),
            'value' => 'registroOnline',
        ],
         [
            'attribute' => 'personaid',
            'header' => academico::t("Academico", 'Per ID'),
            'value' => 'per_id',
        ],
       
         [
                'class' => 'yii\grid\ActionColumn',
                'header' => Academico::t("matriculacion", "Select"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $planificacion) use ($registredSuject, $cancelStatus, $modelCancelItem) {
                      
                      
                      
                    
                            if($model['registroOnline'] == '') {
                             return Html::checkbox($model['per_id'], false, ["value" => $model['nombres'], "class" => "byregister", ]); }
                             else {
                              return Html::checkbox($model['per_id'], false, ["value" => $model['nombres'], "disabled" => true,]); }
                             
                      
                    },
                ],        
           ], 
        
        
    ],
])
?>
 