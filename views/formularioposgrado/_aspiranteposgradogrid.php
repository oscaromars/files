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
        'id' => 'Tbg_Registro_posgrado',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelaspiranteposgrado",
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
                'attribute' => 'año',
                'header' => Yii::t("formulario", "Año"),
                'value' => 'año',
            ],
            [
                'attribute' => 'programa',
                'header' => academico::t("Academico", "Programa"),
                'value' => 'programa',
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
                'template' => '{view} {download}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['formularioposgrado/view', 'id' => $model['per_id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View")]);
                    },
                     'download' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-download-alt"></span>', Url::to(['/formularioposgrado/registerpdf', 'ids' => $model['per_id']]), ["data-toggle" => "tooltip", "title" => "Descargar Inscripcion", "data-pjax" => "0"]);
                        //}
                    },

                    
                ],
            ],
        ],
    ])
    ?>
</div>