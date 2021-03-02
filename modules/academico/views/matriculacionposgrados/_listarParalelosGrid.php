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
        'id' => 'TbG_PROGRAMA',
        //'showExport' => true,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        //'pajax' => false,
        'columns' =>
        [
            [
                'attribute' => 'cupo',
                'header' => academico::t("Academico", "Quota"),
                'value' => 'pppr_cupo',
            ],
            [
                'attribute' => 'actual',
                'header' => academico::t("Academico", "Quota Available"),
                'value' => 'pppr_cupo_actual',
            ],            
            [
                'attribute' => 'fecha',
                'header' => Yii::t("formulario", "Registration Date"),
                'value' => 'pppr_fecha_creacion',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{view} {delete}', //        
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Url::to(['/academico/matriculacionposgrados/editparalelo', 'popup' => "true", 'parid' => base64_encode($model['pppr_id']), 'proid' => base64_encode($model['ppro_id'])]), ["class" => "pbpopup", "data-toggle" => "tooltip", "title" => "Modificar Paralelo", "data-pjax" => 0]);                        
                    }, 
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', "#", ['onclick' => "eliminarParalelo(" . $model['pppr_id'] . "," . $model['ppro_id'] .");", "data-toggle" => "tooltip", "title" => "Eliminar Paralelo", "data-pjax" => 0]);
                    }, 
                ],
            ],
        ],
    ])
    ?>
</div>