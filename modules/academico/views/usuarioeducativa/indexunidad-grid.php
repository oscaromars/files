<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Persona;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'Pbunidad',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelunidad",
        'fnExportPDF' => "exportPdfunidad",
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
            [
                'attribute' => 'Aula',
                'header' => academico::t("Academico", "Course"),
                'value' => 'cedu_asi_nombre',
            ],   
            [
                'attribute' => 'codigo',
                'header' => Yii::t("formulario", "Code"). ' '. Yii::t("formulario", "Unit"),
                'value' => 'ceuni_codigo_unidad',
            ],           
            [
                'attribute' => 'descripcion',
                'header' => Yii::t("formulario", "Description"),
                'value' => 'ceuni_descripcion_unidad',
            ],
                     
                                                  
            /*[
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"), 
                'template' => '{view} {delete}', 
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['usuarioeducativa/view', 'cedu_id' => base64_encode($model["cedu_id"])]), ["data-toggle" => "tooltip", "title" => "Ver Curso", "data-pjax" => 0]);
                    },
                    'delete' => function ($url, $model) {
                       return Html::a('<span class="glyphicon glyphicon-trash"></span>', "#", ['onclick' => "eliminarcurso(" . $model['cedu_id'] . ");", "data-toggle" => "tooltip", "title" => "Eliminar Estudiante", "data-pjax" => 0]);
                     }
                   
                ],
            ],*/
        ],
        //'responsiveWrap' => true,
    ])
    ?>
</div>   
