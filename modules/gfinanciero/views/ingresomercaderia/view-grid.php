<?php

use app\models\Utilities;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\gfinanciero\Module as financiero;
financiero::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_imerca',
        'showExport' => false,
        'dataProvider' => $model,
        'pajax' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'CodArt',
                'header' => financiero::t("ingresomercaderia", "Code"),
                'value' => 'CodArt',
            ],
            [
                'attribute' => 'NomArt',
                'header' => financiero::t("articulo", "Article"),
                'value' => 'NomArt',
            ],
            [
                'attribute' => 'CantItems',
                'header' => financiero::t("ingresomercaderia", "Amount"),
                'value' => 'CantItems',
            ],
            [
                'attribute' => 'PProveedor',
                'header' => financiero::t("articulo", "Provider Price"),
                'value' => 'PProveedor',
            ],
            [
                'attribute' => 'PReferencia',
                'header' => financiero::t("articulo", "Reference Price"),
                'value' => 'PReferencia',
            ],
            [
                'attribute' => 'TCosto',
                'header' => financiero::t("articulo", "Total"),
                'value' => 'TCosto',
            ],
        ],
    ])
?>