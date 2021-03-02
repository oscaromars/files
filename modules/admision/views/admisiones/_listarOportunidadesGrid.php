<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module;

?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'Pbgestion',
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'Number',
                'header' => Module::t("crm", "No Opportunity"),
                'value' => 'opo_codigo',
            ],
            [
                'attribute' => "Contacto",
                'header' => Module::t("crm", "Contact"),
                'value' => 'contacto',
            ],
            [
                'attribute' => 'Unidad',
                'header' => Yii::t("formulario", "Academic unit"),
                'value' => 'des_unidad',
            ],              
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Module::t("crm", "Career/Program/Course"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span>' . substr($model['des_estudio'], 0, 30) . '... </span>', Url::to(['listaroportunidades']), ["data-toggle" => "tooltip", "title" => $model['des_estudio']]);
                    },
                ],
            ],
            [
              'attribute' => 'Modalidad',
              'header' => Module::t("crm", "Moda"),
              'value' => 'des_modalidad',
            ],
            [
                'attribute' => 'Estado Oportunidad',
                'header' => Yii::t("formulario", "Status"),
                'value' => 'des_estado',
            ],
            [
                'attribute' => 'Fecha',
                'header' => Yii::t("formulario", "Date"),
                'value' => 'fecha',
            ],
            [
                'attribute' => 'Agente',
                'header' => Yii::t("formulario", "Agent"),
                'value' => 'padm_codigo',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{view} {update}',//    
                'buttons' => [                    
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['admisiones/veroportunidad', 'opor_id' => base64_encode($model["opo_id"]), 'pges_id' => base64_encode($model["pges_id"])]), ["data-toggle" => "tooltip", "title" => "Ver Oportunidad", "data-pjax" => 0]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-th-large"></span>', Url::to(['admisiones/listaractixoport', 'opor_id' => base64_encode($model["opo_id"]), 'pges_id' => base64_encode($model["pges_id"])]), ["data-toggle" => "tooltip", "title" => "Ver Actividades", "data-pjax" => 0]);
                    },
                ],
            ],
        ],
    ])
    ?>
</div>   
