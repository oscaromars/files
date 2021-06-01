<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Persona;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;

admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'PbCatedra',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'profesor',
                'header' => Yii::t("formulario", "Teacher"),
                'value' => 'nombres',
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
                'attribute' => 'Unidad',
                'header' => Yii::t("formulario", "Academic unit"),
                'value' => 'unidad',
            ],
            [
                'attribute' => 'Modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'modalidad',
            ],
            [
                'attribute' => 'Periodo',
                'header' => Yii::t("formulario", "Period"),
                'value' => 'periodo',
            ],
            [
                'attribute' => 'Fecha',
                'header' => Yii::t("formulario", "Date"),
                'value' => 'fecha',
            ],
           
        ],
    ])
    ?>
</div>   
