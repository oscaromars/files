<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\tabs\TabsX;
use kartik\detail\DetailView; 
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use yii\grid\GridView;


admision::registerTranslations();
academico::registerTranslations();
?>

<div class="table-responsive">
 <?php
 //$content1 = PbGridView::widget([ 
 //$content1 = GridView::widget([ 
 echo PbGridView::widget([ 
        'id' => 'Tbg_Distributivo_Acagra',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        //'bootstrap' => true,
        //'pajax' => false,
        'columns' =>
        [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']], 
            [
                'attribute' => 'Nombres',
                'header' => academico::t("Academico", "Teacher"),
                'value' => 'Nombres',
            ],
            [
                'attribute' => 'Cedula',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'Cedula',
            ],
            [
                'attribute' => 'UnidadAcademica',
                'header' => Yii::t("formulario", "Academic unit"),
                'value' => 'UnidadAcademica',
            ],
            [
                'attribute' => 'Modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'Modalidad',
            ],
            [
                'attribute' => 'Periodo',
                'header' => Yii::t("formulario", "Period"),
                'value' => 'Periodo',
            ],
            [
                'attribute' => 'Asignatura',
                'header' => Yii::t("formulario", "Subject"),
                'value' => 'Asignatura',
            ],
            [
                'attribute' => 'Jornada',
                'header' => academico::t("Academico", "Working day"),
                'value' => 'Jornada',
            ],
            /*
             [
                'attribute' => 'mpp_num_paralelo',
                'header' => academico::t("Academico", "Paralelo"),
                'value' => 'mpp_num_paralelo',
            ],
            */
            [
                //'class' => 'yii\grid\ActionColumn',
                'attribute' => academico::t("Academico", "Paralelo"),
                'header' => academico::t("Academico", "Paralelo"),
                'format' => 'html', 
                'value' => function ($model) {   
                        //print_r($model);die();  
                        if($model['mpp_num_paralelo'] != null)
                            return $model['mpp_num_paralelo'];
                        else
                            return $model['dhpa_paralelo'];     
                    },                             
            ],
            [
                'attribute' => 'total_est',
                'header' => academico::t("Academico", "Total Estudiantes"),
                'value' => 'total_est',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '',
                'template' => '{add}',
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['distributivoacademico/view', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    //'edit' => function ($url, $model) {
                      //  return Html::a('<span class="'.Utilities::getIcon('edit').'"></span>', Url::to(['distributivoacademico/editcab', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","Edit")]);
                    //},
                    //'delete' => function ($url, $model) {
                     //   return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['Id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    //},
                    'add' => function ($url, $model){
                        return Html::a('<span class="fa fa-user-plus"></span>', null, ['href' => 'javascript:showListStudents([\'' . $model['Id'] . '\']);',"data-toggle" => "tooltip", "title" => academico::t("distributivoacademico","Add Student")]);
                    }
                                ],
            ],
        ],

        //'responsive'=>true,
        //'hover'=>true,
        //'responsiveWrap' => true,
    ]);
 /*
 $content2 = PbGridView::widget([ 
  //$content2 =GridView::widget([
        'id' => 'Tbg_Distributivo_Acapos',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model_posgrado,
        //'pajax' => false,
        //'responsive' => true,
        'columns' =>
        [
            [
                'attribute' => 'Nombres',
                'header' => academico::t("Academico", "Teacher"),
                'value' => 'Nombres',
            ],
            [
                'attribute' => 'Cedula',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'Cedula',
            ],
            [
                'attribute' => 'UnidadAcademica',
                'header' => Yii::t("formulario", "Academic unit"),
                'value' => 'UnidadAcademica',
            ],
            [
                'attribute' => 'Modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'Modalidad',
            ],
            [
                'attribute' => 'Periodo',
                'header' => Yii::t("formulario", "Period"),
                'value' => 'Periodo',
            ],
            [
                'attribute' => 'Asignatura',
                'header' => Yii::t("formulario", "Subject"),
                'value' => 'Asignatura',
            ],
            [
                'attribute' => 'Jornada',
                'header' => academico::t("Academico", "Working day"),
                'value' => 'Jornada',
            ],
             [
                'attribute' => 'dhpa_paralelo',
                'header' => academico::t("Academico", "Paralelo"),
                'value' => 'dhpa_paralelo',
            ],

            [
                'attribute' => 'total_est',
                'header' => academico::t("Academico", "Total Estudiantes"),
                'value' => 'total_est',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '',
                'template' => '{add}',
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['distributivoacademico/view', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    //'edit' => function ($url, $model) {
                      //  return Html::a('<span class="'.Utilities::getIcon('edit').'"></span>', Url::to(['distributivoacademico/editcab', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","Edit")]);
                    //},
                    //'delete' => function ($url, $model) {
                     //   return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['Id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    //},
                    'add' => function ($url, $model){
                        return Html::a('<span class="fa fa-user-plus"></span>', null, ['href' => 'javascript:showListStudentsPosgrado([\'' . $model['Id'] . '\']);',"data-toggle" => "tooltip", "title" => academico::t("distributivoacademico","Add Student")]);
                    }
                                ],
            ],
        ],
    ]);

 $items = [
    [
        'label'=>'<i class="fa fa-graduation-cap"></i> Grado',
        'content'=>$content1,
        'active'=>true,
       // 'linkOptions'=>['data-url'=>Url::to(['distributivoacademico/test?tab=1'])]
    ],
    [
        'label'=>'<i class="fa fa-graduation-cap" style="font-size:15px;color:red"></i> Posgrado',
        'content'=>$content2,
       // 'linkOptions'=>['data-url'=>Url::to(['/site/fetch-tab?tab=2'])]
    ],

];
*/

/*
 echo TabsX::widget([
        'items'=>$items,
    'position'=>TabsX::POS_ABOVE,
    'align'=>TabsX::ALIGN_CENTER,
    'bordered'=>true,
    'encodeLabels'=>false
]);
*/

    ?>

</div>