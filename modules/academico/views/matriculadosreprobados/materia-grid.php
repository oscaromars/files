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
            'attribute' => 'codigo',
            'header' => Yii::t("formulario", "Code"),
            'value' => 'id',
        ],
        [
            'attribute' => 'materia',
            'header' => Yii::t("formulario", "Matter"),
            'value' => 'asi_descripcion',
        ],
        /*[
            'attribute' => 'estados',
            'format' => 'raw',
            'header' => "Estado Aprobacion",
            'value' => function () {
                return Html::dropDownList("cmb_aprueba[]", 0, ["0" => "Seleccionar", "1" => "Aprobado", "2" => "Reprobado"], ["class" => "form-control", "id" => "cmb_aprueba"]);
            },
        ],*/
        [           
            'header' => academico::t("Academico", "Failed"),
            'class' => 'app\widgets\PbGridView\PbCheckboxColumn',
        ],        
    ],
])
?>
