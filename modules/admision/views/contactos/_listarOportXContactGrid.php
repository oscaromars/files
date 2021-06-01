<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Persona;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module;

?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'Pbcontacto',
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'Number',
                'header' => Module::t("crm", "No Opportunity"),
                'value' => 'codigo',
            ],
            [
                'attribute' => "Unidad",
                'header' => Yii::t("formulario", "Academic unit"),
                'value' => 'linea_servicio',
            ],
            [
                'attribute' => 'Modalidad',
                'header' => Module::t("crm", "Moda"),
                'value' => 'modalidad',
            ],
            [
                'attribute' => 'empresa',
                'header' => Yii::t("formulario", "Company"),
                'value' => 'empresa',
            ],
            [
                'attribute' => 'Tipo Oportunidad',
                'header' => Module::t("crm", "Opportunity type"),
                'value' => 'tipo_oportunidad',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Module::t("crm", "Career/Program/Course"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span>' . substr($model['curso'], 0, 30) . '... </span>', Url::to("#"), ["data-toggle" => "tooltip", "title" => $model['curso']]);
                    },
                ],
            ],
            [
                'attribute' => 'Observacion',
                'header' => Yii::t("formulario", "Observation"),
                'value' => 'observa',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{view} {activities} {interested}', //    
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['oportunidades/view', 'opor_id' => base64_encode($model["id"]), 'pges_id' => base64_encode($model["pges_id"])]), ["data-toggle" => "tooltip", "title" => "Ver Oportunidad", "data-pjax" => 0]);
                    },
                    'activities' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-th-large"></span>', Url::to(['actividades/listaractividadxoportunidad', 'opor_id' => base64_encode($model["id"]), 'pges_id' => base64_encode($model["pges_id"])]), ["data-toggle" => "tooltip", "title" => "Ver Actividades", "data-pjax" => 0]);
                    },
                    'interested' => function ($url, $model) {
                        $mod_per = new Persona();
                        $pre_id = $mod_per->ConsultaRegistroExiste(null, $model['identificacion'], $model['pasaporte']);
                        //$existe = isset($pre_id['existen']) ? 1 : 0;
                        if ($model['estado_oportunidad_id'] == 3) {
                            if ($pre_id['existen'] == 0) {
                                return Html::a('<span class="glyphicon glyphicon-user"></span>', "#", ["onclick" => "grabarInteresado(" . $model['id'] . ");", "data-toggle" => "tooltip", "title" => "Generar Aspirante", "data-pjax" => 0]);
                            } else {
                                return "<span class = 'glyphicon glyphicon-user' data-toggle = 'tooltip' title ='Usuario Existente'  data-pjax = 0></span>";
                            }
                        } else {
                            return "<span class = 'glyphicon glyphicon-user' data-toggle = 'tooltip' title ='En espera de estado en Generar Aspirante'  data-pjax = 0></span>";
                        }
                    },
                ],
            ],
        ],
    ])
    ?>
</div>   
