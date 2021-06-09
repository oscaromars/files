<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;

academico::registerTranslations();

$template = '{view}';
$buttons = [
    'view' => function ($url, $model) {          
        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['registro/viewcancel', 'id' => $model['Id'],]), ["data-toggle" => "tooltip", "title" => academico::t("registro", "View Detail"), "data-pjax" => 0]);
    },
];
if($refund){
    $template .= ' {confirm}';
    $buttons['confirm'] = function ($url, $model) {       
        if($model["Estado"] == 1)
            return Html::a('<span class="fa fa-send"></span>', 'javascript:confirmarDevolucion('.$model['Id'].')', ["data-toggle" => "tooltip", "title" => academico::t("registro", "To confirm Refund"), "data-pjax" => 0]);
    };
}else{
    $template .= ' {approve}';
    $buttons['approve'] = function ($url, $model) {       
        if($model["Estado"] != 1 && $model["Estado"] != 2)
            return Html::a('<span class="fa fa-check-circle"></span>', 'javascript:aprobarCancelacion('.$model['Id'].')', ["data-toggle" => "tooltip", "title" => academico::t("registro", "To Approve"), "data-pjax" => 0]);                                    
    };
}

?>

<?=

PbGridView::widget([
    'id' => 'grid_cancel_list',
    'showExport' => false,
    //'fnExportEXCEL' => "exportExcel",
    //'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        
        [
            'attribute' => 'Estudiante',
            'header' => academico::t("matriculacion", 'Student'),
            'value' => 'Estudiante',
        ],
        [
            'attribute' => 'Cedula',
            'header' => Yii::t("formulario", 'SSN/Passport'),
            'value' => 'Cedula',
        ],
        [
            'attribute' => 'Carrera',
            'header' => academico::t("Academico", 'Career'),
            'value' => 'Carrera',
        ],
        [
            'attribute' => 'UnidadAcademica',
            'header' => academico::t("Academico", 'Academic unit'),
            'value' => 'UnidadAcademica',
        ],
        [
            'attribute' => 'Programa',
            'header' => academico::t("Academico", 'Program'),
            'value' => 'Programa',
        ],
        [
            'attribute' => 'Periodo',
            'header' => academico::t("matriculacion", 'Academic Period'),
            'value' => 'Periodo',
        ],
        [
            'attribute' => 'Modalidad',
            'header' => academico::t("matriculacion", 'Modality'),
            'value' => 'Modalidad',
        ],
        [
            'attribute' => 'Estado',
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['class' => 'text-center'],
            'format' => 'html',
            'header' => academico::t("matriculacion", "Status"),
            'value' => function($data) {
              if  ($data["TipoPago"] == "2"){
                if ($data["Estado"] == "2")
                    return '<small class="label label-success">' . academico::t("registro", "Refund Applied") . '</small>';
                elseif($data["Estado"] == "1")
                    return '<small class="label label-info">' . academico::t("registro", "To confirm Refund") . '</small>';
                    elseif($data["Estado"] == "0")
                    return '<small class="label label-warning">' . academico::t("registro", "To be Approved") . '</small>';         
                }   else {
                    if ($data["Estado"] == "2")
                    return '<small class="label label-success">' . academico::t("registro", "Approved") . '</small>';
                elseif($data["Estado"] == "1")
                    return '<small class="label label-success">' . academico::t("registro", "Approved") . '</small>';
                    elseif($data["Estado"] == "0")
                    return '<small class="label label-warning">' . academico::t("registro", "To be Approved") . '</small>';                    
                }
                   

           
                },
        ],       

        [
            'class' => 'yii\grid\ActionColumn',
            //'header' => 'Action',
            'contentOptions' => ['style' => 'text-align: center;'],
            'headerOptions' => ['width' => '90'],
            'template' => $template,
            'buttons' => $buttons,
        ],

    ],
])
?>