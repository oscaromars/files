<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Persona;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
//print_r($model);
admision::registerTranslations();
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'Pbudistriutivoedu',
        'showExport' => true,
        'fnExportEXCEL' => "exportExceldistedu",
        'fnExportPDF' => "exportPdfdistedu",
        'tableOptions' => [
            'class' => 'table table-condensed',
        ],
        'options' => [
            'class' => 'table-responsive table-striped',
        ],
        //'condensed' => true,
        'dataProvider' => $model,
        'columns' => [
            /*[
                'class'=>'kartik\grid\ExpandRowColumn',
                'value'=> function ($model,$key,$index,$column) {
                return GridView::ROW_COLLAPSED;
                },
            ],*/    
            /*[
                'attribute' => 'usuario',
                'header' => Yii::t("formulario", "Users"),
                'value' => 'cedu_id',
            ],           
            [
                'attribute' => 'nombres',
                'header' => Yii::t("formulario", "Names"),
                'value' => 'daca_id',
            ],*/ 
            [
                'attribute' => 'aula',
                'header' => academico::t("Academico", "Course"),
                'value' => 'cedu_asi_nombre',
            ],  
            [
                'attribute' => 'unidad',
                'header' => academico::t("Academico", "Aca. Uni."),
                'value' => 'uaca_nombre',
            ],  
            [
                'attribute' => 'modalidad',
                'header' => academico::t("Academico", "Modality"),
                'value' => 'mod_nombre',
            ],
            [
                'attribute' => 'materia',
                'header' => academico::t("Academico", "Subject"),
                'value' => 'asi_nombre',
            ],
            [
                'attribute' => 'profesor',
                'header' => academico::t("Academico", "Teacher"),
                'value' => 'profesor',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"), 
                'template' => '{delete}', // {view}
                'buttons' => [
                   /* 'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['usuarioeducativa/viewdistributivo', 'cedi_id' => base64_encode($model["cedi_id"])]), ["data-toggle" => "tooltip", "title" => "Ver Registro", "data-pjax" => 0]);
                    },*/
                    'delete' => function ($url, $model) {
                       return Html::a('<span class="glyphicon glyphicon-trash"></span>', "#", ['onclick' => "eliminardistributivo(" . $model['cedi_id'] . ");", "data-toggle" => "tooltip", "title" => "Eliminar Registro", "data-pjax" => 0]);
                     }
                   
                ],
            ],
        ],
        //'responsiveWrap' => true,
    ])
    ?>
</div> 