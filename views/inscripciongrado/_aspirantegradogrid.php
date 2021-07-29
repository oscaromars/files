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
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<!--<div></br></div>-->
<div>
    <?=
    PbGridView::widget([
        'id' => 'Tbg_Registro_aspirante',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelEduregistro",
        //'fnExportPDF' => "exportPdfEduregistro",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' =>
        [   
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Cedula',
                'header' => Yii::t("formulario", "DNI"),
                'value' => 'Cedula',
            ],
            [
                'attribute' => 'estudiante',
                'header' => Yii::t("formulario", "Complete Names"),
                'value' => 'estudiante',
            ],     
            [
                'attribute' => 'periodo',
                'header' => Yii::t("formulario", "Period"),
                'value' => 'periodo',
            ],
            [
                'attribute' => 'carrera',
                'header' => academico::t("Academico", "Carrera"),
                'value' => 'carrera',
            ],
            [
                'attribute' => 'modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'modalidad',
            ],            
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '90'],
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['inscripciongrado/view', 'id' => $model['per_id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View")]);
                    },
                    
                ],
            ],
        ],
    ])
    ?>
</div>