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
            'attribute' => 'CodPto',
            'header' => financiero::t("establecimiento", "Code"),
            'value' => 'CodPto',
        ],
        [
            'attribute' => 'NomPto',
            'header' => financiero::t("establecimiento", "Name"),
            'value' => 'NomPto',
        ],
        [
            'attribute' => 'NomPai',
            'header' => financiero::t("localidad", "Country"),
            'value' => 'NomPai',
        ],
        [
            'attribute' => 'NomEst',
            'header' => financiero::t("localidad", "State"),
            'value' => 'NomEst',
        ],
        [
            'attribute' => 'NomCiu',
            'header' => financiero::t("localidad", "City"),
            'value' => 'NomCiu',
        ],
        [
            'attribute' => 'DirPto',
            'header' => financiero::t("establecimiento", "Address"),
            'value' => 'DirPto',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            //'header' => 'Action',
            'contentOptions' => ['style' => 'text-align: center;'],
            'headerOptions' => ['width' => '60'],
            'template' => '{view} {delete}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['/' . Yii::$app->controller->module->id . '/establecimiento/view', 'id' => $model['CodPto']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View")]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="' . Utilities::getIcon('remove') . '"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['CodPto'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion", "Delete")]);
                },
            ],
        ],
    ],
])
?>
