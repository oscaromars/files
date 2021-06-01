<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();
?>

<?=

PbGridView::widget([
    'id' => 'grid_list',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
//        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'class' => 'app\widgets\PbGridView\PbCheckboxColumn',
        ],
    
       /*  [
            'attribute' => 'CodPunto',
            'header' => financiero::t("tipodocumento", "Point Code"),
            'value' => 'CodPunto',
        ], */
        [
            'attribute' => 'CodCaja',
            'header' => financiero::t("tipodocumento", "Box Code"),
            'value' => 'CodCaja',
        ],
        [
            'attribute' => 'TipNof',
            'header' => financiero::t("tipodocumento", "Code"),
            'value' => 'TipNof',
        ],
        [
            'attribute' => 'NumNof',
            'header' => financiero::t("tipodocumento", "Document Number"),
            'value' => 'NumNof',
        ],
        [
            'attribute' => 'NomNof',
            'header' => financiero::t("tipodocumento", "Document Name"),
            'value' => 'NomNof',
        ],
        [
            'attribute' => 'FecAut',
            'header' => financiero::t("tipodocumento", "Document Date"),
            'value' => 'FecAut',
        ],
         
        // [
        //     'class' => 'yii\grid\ActionColumn',
        //     //'header' => 'Action',
        //     'contentOptions' => ['style' => 'text-align: center;'],
        //     'headerOptions' => ['width' => '60'],
        //     'template' => '{view} {delete}',
        //     'buttons' => [
        //         'view' => function ($url, $model) {
        //             return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['/' . Yii::$app->controller->module->id . '/tipodocumento/view', 'codpto' => $model['IdPunto'], 'codcaj' => $model['IdCaja'], 'tipnof' => $model['TipNof']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View")]);
        //         },
        //         'delete' => function ($url, $model) {
        //             return Html::a('<span class="' . Utilities::getIcon('remove') . '"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['IdPunto'] . '\',\'' . $model['IdCaja'] . '\',\'' . $model['TipNof'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion", "Delete")]);
        //         },
        //     ],
        // ],
    ],
])
?>