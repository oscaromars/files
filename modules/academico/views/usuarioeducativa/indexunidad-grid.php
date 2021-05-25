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
                                                  
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"), 
                'template' => '{view} {delete}', 
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['usuarioeducativa/viewunidad', 'ceuni_id' => base64_encode($model["ceuni_id"])]), ["data-toggle" => "tooltip", "title" => "Ver Unidad", "data-pjax" => 0]);
                    },
                    'delete' => function ($url, $model) {
                       return Html::a('<span class="glyphicon glyphicon-trash"></span>', "#", ['onclick' => "eliminarunidad(" . $model['ceuni_id'] . ");", "data-toggle" => "tooltip", "title" => "Eliminar Unidad", "data-pjax" => 0]);
                     }
                   
                ],
            ],
        ],
        //'responsiveWrap' => true,
    ])
    ?>
</div>   
