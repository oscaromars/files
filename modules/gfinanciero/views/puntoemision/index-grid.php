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
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'IdCaja',
                'header' => financiero::t("puntoemision", "Emission Point Code"),
                'value' => 'IdCaja',
            ],
            [
                'attribute' => 'CodPunto',
                'header' => financiero::t("establecimiento", "Establishment"),
                'value' => 'CodPunto',
            ],
            [
                'attribute' => 'NomCaj',
                'header' => financiero::t("puntoemision", "Name"),
                'value' => 'NomCaj',
            ],
            [
                'attribute' => 'UbiCaj',
                'header' => financiero::t("puntoemision", "Location"),
                'value' => 'UbiCaj',
            ],
            [
                'attribute' => 'CajFec',
                'header' => financiero::t("puntoemision", "Date"),
                'value' => 'CajFec',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['/' . Yii::$app->controller->module->id . '/puntoemision/view', 'codpto' => $model['IdPunto'], 'codcaj' => $model['IdCaja']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View")]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="' . Utilities::getIcon('remove') . '"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['IdPunto'] . '\',\'' . $model['IdCaja'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion", "Delete")]);
                },
            ],
            ],
        ],
    ])
?>
