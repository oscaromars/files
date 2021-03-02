<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\models\ObjetoModulo;
?>

<?=
    PbGridView::widget([
        'id' => 'grid_omodule_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Nombre',
                'header' => Yii::t("modulo", "SubModule Name"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Modulo',
                'header' => Yii::t("modulo", "Module Name"),
                'value' => 'Modulo',
            ],
            [
                'attribute' => 'Padre',
                'header' => Yii::t("modulo", "SubModule Main"),
                'value' => function($data){
                    $omod_model = ObjetoModulo::findOne($data['Padre']);
                    return $omod_model->omod_nombre;
                },
            ],
            /*[
                'attribute' => 'Aplicacion',
                'header' => Yii::t("aplicacion", "Application Name"),
                'value' => 'Aplicacion',
            ],*/
            [
                'attribute' => 'Tipo',
                'header' => Yii::t("modulo", "Type of SubModule"),
                'value' => 'Tipo',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            /*[
                'attribute' => 'Orden',
                'header' => Yii::t("modulo", 'Position SubModule'),
                'value' => 'Orden',
                'contentOptions' => ['class' => 'text-center'],
            ],*/
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => Yii::t("modulo", "Status SubModule"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.Yii::t("modulo", "SubModule Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.Yii::t("modulo", "SubModule Disabled").'</small>';
                },
            ],
            /*[
                'attribute' => 'Visibilidad',
                'format' => 'html',
                'header' => Yii::t("modulo", "Visibility SubModule"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.Yii::t("modulo", "Visibility SubModule Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.Yii::t("modulo", "Visibility SubModule Disabled").'</small>';
                },
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['objetomodulos/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                        //return  Html::a('Action', Url::to(['mceformulariotemp/solicitudpdf','ids' => 1],['class' => 'btn btn-default',"target" => "_blank"]));
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
