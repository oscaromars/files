<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;
academico::registerTranslations();
$styleBar = "";
if($esEstu) $styleBar = "display: none;";
//print_r($periodoAcademico);
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_registro_online"><?= academico::t("Academico", "Fechas Vencimientos Pagos") ?></span></h3>
</div><br><br><br><br><br><br>
<form class="form-horizontal">
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_mod" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= academico::t("matriculacion", "Modality") ?></label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <?= Html::dropDownList("cmb_mod", 0, $modalidad, ["class" => "form-control", "id" => "cmb_mod"]) ?>
            </div>
               
            <label for="cmb_per_acad" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= academico::t("matriculacion", "Academic Period") ?></label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <?= Html::dropDownList("cmb_per_acad", 0, $periodoAcademico, ["class" => "form-control", "id" => "cmb_per_acad",]) ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscar" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</form>
<style>
    .table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > td{
        background-color: #3c8dbc;
        border-top: 0 0 0;
        border-color: #fff;
    }
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
        border-color: #3c8dbc;
    }
</style>
<?=

PbGridView::widget([
    'id' => 'grid_vencimientos_list',
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Nombre',
            'format' => 'html',
            'header' => academico::t("matriculacion", 'Periodo Académico - Año'),
            'value' => function($model) {
                if ($model["int"] == "1")
                    return $model["periodo"].'&emsp;'.'<small class="label label-success"><a style="color:#FFF">INT</a></small>';
                else
                return $model["periodo"];
            },
        ],
        [
            'attribute' => 'Fecha',
            'format' => 'html',
            'header' => academico::t("matriculacion", 'Fecha Vencimiento Pago'),
            'value' => 'fecha',
        ],
        [
            'attribute' => 'Bloque',
            'header' => academico::t("matriculacion", 'Bloque'),
            'value' => 'bloque',
        ],
        [
            'attribute' => 'Modalidad',
            'header' => academico::t("matriculacion", 'Modalidad'),
            'value' => 'modalidad',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['class' => 'text-center'],
            'header' => 'Acciones',
            'template' => '{view}{delete}{Approbe}{Download}{Reversar}',
          //  'contentOptions' => ['class' => 'text-center'],
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['distributivoacademico/view', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View")]);
                },
                'delete' => function ($url, $model) {
                    if ($model['estado'] == 1 or $model['estado'] == 3) {
                        return Html::a('<span class="' . Utilities::getIcon('remove') . '"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['Id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion", "Delete")]);
                    } else {
                        return "<span class = 'glyphicon glyphicon-remove' data-toggle = 'tooltip' title ='Eliminar'  data-pjax = 0></span>";
                    }
                },
            ],
        ],
       
    ],
])
?>