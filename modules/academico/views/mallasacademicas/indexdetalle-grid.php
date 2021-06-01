<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;

//print_r($model);
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_malla_id', $malla, ['id' => 'txth_malla_id']); ?>
<?=

PbGridView::widget([
    'id' => 'Tbg_DetalleMallas',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Código',
            'header' => academico::t("Academico", "Subject Code"),
            'value' => 'made_codigo_asignatura',
        ],
        [
            'attribute' => 'Asignatura',
            'header' => academico::t("Academico", "Subject"),
            'value' => 'asi_nombre',
        ],
        [
            'attribute' => 'Semestre',
            'header' => academico::t("Academico", "Semester"),
            'value' => 'made_semestre',
        ],
        [
            'attribute' => 'Créditos',
            'header' => academico::t("Academico", "Credits"),
            'value' => 'made_credito',
        ],        
        [
            'attribute' => 'Unidad Estudio',
            'header' => academico::t("Academico", "Unidad Estudio"),
            'value' => 'uest_nombre',
        ],   
        [
            'attribute' => 'Formación',
            'header' => academico::t("Academico", "Training"),
            'value' => 'fmac_nombre',
        ],   
        [
            'attribute' => 'Materia requisito',
            'header' => academico::t("Academico", "Subject Requirement"),
            'value' => 'materia_requisito',
        ],   
       /*[
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{view}',
            'buttons' => [               
                'view' => function ($url, $model) {                    
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/academico/estudiante/view', 'per_id' => base64_encode($model['per_id']), 'est_id' => base64_encode($model['est_id'])]), ["data-toggle" => "tooltip", "title" => "Ver Detalle de Malla", "data-pjax" => 0]);
                   
                },
               
            ],
        ],*/
    ],
])
?>
