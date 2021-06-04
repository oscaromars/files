<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<div>
    <?=
    PbGridView::widget([
        'id' => 'Tbg_Distributivo_Aca',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        //'pajax' => false,
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
                'attribute' => 'mpp_num_paralelo',
                'header' => academico::t("Academico", "Paralelo"),
                'value' => 'mpp_num_paralelo',
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
    ])
    ?>
</div>