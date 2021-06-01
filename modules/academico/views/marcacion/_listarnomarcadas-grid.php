<?php

use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;

admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'PbNomarcacion',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelNoMarcadas",
        'fnExportPDF' => "exportPdfNoMarcadas",
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'periodo',
                'header' => Yii::t("formulario", "Period"),
                'value' => 'periodo',
            ],  
            [
                'attribute' => 'profesor',
                'header' => Yii::t("formulario", "Teacher"),
                'value' => 'profesor',
            ],  
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Matter"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (strlen($model['materia']) > 30) {
                            $texto = '...';
                        }
                        return Html::a('<span>' . substr($model['materia'], 0, 30) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['materia']]);
                    },
                ],
            ],
            [
                'attribute' => 'Fecha',
                'header' => Yii::t("formulario", "Date"),
                'value' => 'fecha',
            ],            
            [
                'attribute' => 'Horainipon',
                'header' => academico::t("Academico", "Hour start date") . ' ' . academico::t("Academico", "Expected"),
                'value' => 'hora_inicio',
            ],           
            [
                'attribute' => 'Horafinpon',
                'header' => academico::t("Academico", "Hour end date") . ' ' . academico::t("Academico", "Expected"),
                'value' => 'hora_salida',
            ],           
        ],
    ])
    ?>
</div>   
