<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbSearchBox\PbSearchBox;
use app\widgets\PbGridView\PbGridView;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\data\ArrayDataProvider;
use app\models\Utilities;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;

admision::registerTranslations();
academico::registerTranslations();
?>
<?=

PbGridView::widget([
    'id' => 'TbG_Admitido',
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'name' => 'rb_admitido',
            'class' => 'yii\grid\RadioButtonColumn',
            'radioOptions' => function ($model) {
                return [
                    'value' => $model['adm_id'].'_'.$model['sins_id'],
                    'checked' => $model['adm_id'] == $model['adm_id']
                ];
            }
        ],
        [
            'attribute' => 'nombre',
            'header' => Yii::t("formulario", "Name"),
            'value' => 'per_pri_nombre',
        ],
        [
            'attribute' => 'apellido',
            'header' => Yii::t("formulario", "Last Name1"),
            'value' => 'per_pri_apellido',
        ],
        [
            'attribute' => 'dni',
            'header' => Yii::t("formulario", "DNI 1"),
            'value' => 'per_cedula',
        ],
        // ESTE PERIODO PIDE AL GUARDAR PILAS REVISAR ESO
        /*[
            'attribute' => 'periodo',
            'header' => academico::t("Academico", "Period"),
            'value' => 'pami_codigo',
        ],*/
        /*[
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Email"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span>' . substr($model['per_correo'], 0, 15) . '... </span>', Url::to("#"), ["data-toggle" => "tooltip", "title" => $model['per_correo']]);
                },
            ],
        ],*/
        [
            'attribute' => 'celular',
            'header' => Yii::t("formulario", "Phone"),
            'value' => 'per_celular',
        ],
        [
            'attribute' => "Unidad",
            'header' => Yii::t("formulario", "Aca. Uni."),
            'value' => 'uaca_nombre',
        ],
        [
            'attribute' => 'Modalidad',
            'header' => Yii::t("formulario", "Mode"),
            'value' => 'mod_nombre',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => academico::t("Academico", "Career"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span>' . substr($model['carrera'], 0, 15) . '... </span>', Url::to("#"), ["data-toggle" => "tooltip", "title" => $model['carrera']]);
                },
            ],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => admision::t("Solicitudes", "Income Method"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span>' . $model['abr_metodo'] . '</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['ming_nombre']]);
                },
            ],
        ],
    ],
])
?>
