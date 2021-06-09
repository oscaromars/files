<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;

academico::registerTranslations();
//print_r($model);
?>

<?=

PbGridView::widget([
    'id' => 'grid_registropay_list',
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
            'attribute' => 'Cant',
            'header' => academico::t("registro", 'Subjects'),
            'value' => 'Cant',
        ],
        [
            'attribute' => 'Creditos',
            'header' => academico::t("registro", 'Credits'),
            'value' => 'Creditos',
        ],
        [
            'attribute' => 'Costo',
            'header' => academico::t("registro", 'Cost'),
            'value' => function($data){
                return "$".(number_format($data['Costo'], 2, '.', ','));
                  
            },
        ],
        [
            'attribute' => 'Refund',
            'header' => academico::t("registro", 'Refund'),
            'value' => function($data){
                if(isset($data['Refund']))
                    return "$".(number_format($data['Refund'], 2, '.', ','));
                return "-";
            },
        ],
        /*[
            'attribute' => 'Enroll',
            'header' => academico::t("registro", 'Enroll'),
            'format' => 'raw',
            'value' => function($data){
                if(isset($data['Enroll'])){
                    return "<a href='javascript:downloadEnrollment(".$data['Enroll'].", ".$data['rpm_id'].")'>" . academico::t("registro", "Download") . "</a>";
                }
                return "-";
            },
        ],*/
        [
            'attribute' => 'Estado',
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['class' => 'text-center'],
            'format' => 'html',
            'header' => academico::t("matriculacion", "Status"),
            'value' => function($model) {
                if ($model["Aprobacion"] == "1")
                    return '<small class="label label-success">' . academico::t("registro", "Paid Registration") . '</small>';
                elseif($model["Aprobacion"] == "0")
                    return '<small class="label label-warning">' . academico::t("registro", "To Check") . '</small>';
                else
                    return '<small class="label label-danger">' . academico::t("registro", "To Pay") . '</small>';
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            //'header' => 'Action',
            'contentOptions' => ['style' => 'text-align: center;'],
            'headerOptions' => ['width' => '60'],
            'template' => '{pay} {download}',
            'buttons' => [
                'pay' => function ($url, $model) use ($esEstu) {   
                    $perInt=null;       
                    if ($model["Estado"] != "2" && $model["Estado"] != "1" && $esEstu == TRUE) {                    
                        if($model['Aprobacion'] == "2")
                            return Html::a('<span class="glyphicon glyphicon-usd"></span>', Url::to(['registro/new', 'id' => $model['per_id'], 'rama_id' => $model['rama_id'],'costo' => $model['Costo'],'periodo' => $perInt?1:0,'pla_id'=>$model['pla_id']]), ["data-toggle" => "tooltip", "title" => academico::t("registro", "To Pay"), "data-pjax" => 0]);
                        else
                           if ($model['Cant'] > 0)
                            return Html::a('<span class="glyphicon glyphicon-usd"></span>', Url::to(['registro/new', 'id' => $model['per_id'], 'rama_id' => $model['rama_id'], 'costo' => $model['Costo'],'periodo' => $perInt?1:0,'pla_id'=>$model['pla_id']]), ["data-toggle" => "tooltip", "title" => academico::t("registro", "To Pay"), "data-pjax" => 0]);
                    } else {
                        return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['registro/view', 'id' => $model['Id'], 'rama_id' => $model['rama_id'],'periodo' => $perInt?1:0,'pla_id'=>$model['pla_id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View"), "data-pjax" => 0]);
                    }
                },
                'download' => function ($url, $model) {
                    //if ($model['perfil'] == 1 ) {// Procesado
                        return Html::a('<span class="glyphicon glyphicon-download"></span>', Url::to(['/academico/registro/inscripcionpdf', 'ids' => $model['per_id'], 'rama_id' => $model['rama_id']]), ["data-toggle" => "tooltip", "title" => "Descargar Hoja de Inscripción", "data-pjax" => "0"]);
                    //}
                },
            ],
        ],
    ],
])
?>