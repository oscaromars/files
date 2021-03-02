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
    'id' => 'TbG_MATERIAS',
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'attribute' => 'materia',
            'header' => Yii::t("formulario", "Matter"),
            'value' => 'materia',
        ],
        [
            'attribute' => 'horario',
            'header' => academico::t("Academico", "Schedule"),
            'value' => 'horario',
        ],
        [
            'attribute' => 'inicio',
            'header' => academico::t("Academico", "Hour start date"),
            'value' => 'inicio',
        ],
        [
            'attribute' => 'fin',
            'header' => academico::t("Academico", "Hour end date"),
            'value' => 'salida',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'contentOptions' => ['style' => 'max-width:130px;'],
            'template' => '{iniciar} {finalizar} {control}',
            'buttons' => [
                'iniciar' => function ($url, $model) {                    
                    return Html::a('<button type="button" class="btn btn-primary btn-sm">Iniciar</button>', "#", ["onclick" => "Marcacion(" . $model['id'] . ",'" . $model['horario'] . "', 'E', ". $model['dia'] . ", ". $model['profesor'] . ");", "data-toggle" => "tooltip", "title" => "Iniciar Marcación", "data-pjax" => 0]);
                    
                },
                'finalizar' => function ($url, $model) {                    
                    return Html::a('<button type="button" class="btn btn-primary btn-sm">Finalizar</button>', "#", ["onclick" => "Marcacion(" . $model['id'] . ",'" . $model['horario'] . "', 'S', ". $model['dia'] . ", ". $model['profesor'] . ");", "data-toggle" => "tooltip", "title" => "Finalizar Marcación", "data-pjax" => 0]);
                },
                'control' => function ($url, $model) {                    
                    return Html::a('<button type="button" class="btn btn-primary btn-sm">Control</button>', Url::to(['/academico/controlcatedra/new', 'hape_id' => $model['id']]), ["data-toggle" => "tooltip", "title" => "Control Cátedra", "data-pjax" => 0]);
                },
            ],
        ],
    ],
])
?>
