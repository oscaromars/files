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
AQUI EL GRID CAMBIAR CONSULTA
 <div>        
    <?=
    PbGridView::widget([
        'id' => 'Pbcurso',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelcurso",
        'fnExportPDF' => "exportPdfcurso",
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
                'attribute' => 'usuario',
                'header' => Yii::t("formulario", "User"),
                'value' => 'uedu_usuario',
            ],            
            [
                'attribute' => 'nombres',
                'header' => Yii::t("formulario", "Names"),
                'value' => 'nombres',
            ],
            [
                'attribute' => 'cedula',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'uedu_cedula',
            ],  
            [
                'attribute' => 'matricula',
                'header' => Yii::t("formulario", "Enrollment"),
                'value' => 'uedu_matricula',
            ],  
            [
                'attribute' => 'correo',
                'header' => Yii::t("formulario", "Email"),
                'value' => 'uedu_correo',
            ],                                    
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"), 
                'template' => '{view} {delete}', 
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['usuarioeducativa/viewusuario', 'uedu_usuario' => base64_encode($model["uedu_usuario"])]), ["data-toggle" => "tooltip", "title" => "Ver Usario", "data-pjax" => 0]);
                    },
                    'delete' => function ($url, $model) {
                       return Html::a('<span class="glyphicon glyphicon-trash"></span>', "#", ['onclick' => "eliminarusuario(" . $model['uedu_usuario'] . ");", "data-toggle" => "tooltip", "title" => "Eliminar Usuario", "data-pjax" => 0]);
                     }
                   
                ],
            ],
        ],
        //'responsiveWrap' => true,
    ])
    ?>
</div>  
