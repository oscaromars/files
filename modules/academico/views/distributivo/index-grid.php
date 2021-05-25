<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <?=
    PbGridView::widget([
        'id' => 'Tbg_Distributivo',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        //'pajax' => false,
        'columns' =>
        [
            [
                'attribute' => 'DNI',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'per_cedula',
            ],
            [
                'attribute' => 'docente',
                'header' => academico::t("Academico", "Teacher"),
                'value' => 'docente',
            ],                     
            /*[
                'attribute' => 'título_tercer',
                'header' => academico::t("Academico", "Third level title"),
                'value' => 'titulo_tercer',
            ],
            [
                'attribute' => 'título_cuarto',
                'header' => academico::t("Academico", "Fourth level title"),
                'value' => 'titulo_cuarto',
            ],  */          
            [
                'attribute' => 'dedicacion',
                'header' => academico::t("Academico", "Dedication"),
                'value' => 'dedicacion',
            ],
            [
                'attribute' => 'unidad_academico',
                'header' => Yii::t("formulario", "Academic unit"),
                'value' => 'unidad',
            ],    
            [
                'attribute' => 'asignatura',
                'header' => Yii::t("formulario", "Subject"),
                'value' => 'asignatura',
            ],  
            [
                'attribute' => 'semestre',
                'header' => Yii::t("formulario", "Semester"),
                'value' => 'semestre',
            ],
            [
                'attribute' => 'descripcion',
                'header' => Yii::t("formulario", "Description"),
                'value' => 'dis_descripcion',
            ],            
            /*[
                'class' => 'yii\grid\ActionColumn',
                'header' => academico::t("Academico", "Career/Program"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span>' . substr($model['carrera'], 0,10)  . '..</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['carrera']]);
                    },
                ],               
            ],*/                                  
        ],
    ])
    ?>
</div>