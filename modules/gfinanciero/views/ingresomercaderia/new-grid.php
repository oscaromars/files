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
                'attribute' => 'Cod',
                'header' => financiero::t("ingresomercaderia", "Code"),
                'value' => 'Cod',
            ],
            [
                'attribute' => 'Articulo',
                'header' => financiero::t("articulo", "Article"),
                'value' => 'Articulo',
            ],
            [
                'attribute' => 'Cant',
                'header' => financiero::t("ingresomercaderia", "Amount"),
                'value' => 'Cant',
            ],
            [
                'attribute' => 'PLista',
                'header' => financiero::t("articulo", "Provider Price"),
                'value' => 'PLista',
            ],
            [
                'attribute' => 'PCosto',
                'header' => financiero::t("articulo", "Reference Price"),
                'value' => 'PCosto',
            ],
            [
                'attribute' => 'TCosto',
                'header' => financiero::t("articulo", "Total"),
                'value' => 'TCosto',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/'.Yii::$app->controller->module->id.'/egresomercarderia/view', 'cod' => $model['CodBodegaOrigen'], 'egr' => $model['NumEgreso'], 'tip' => $model['Tipo']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['CodBodegaOrigen'] . '\', \'' . $model['NumEgreso'] . '\', \'' . $model['Tipo'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
<?php
$this->registerJs(
    "loadSessionCampos('grid_imerca', '', '', '');",
    $this::POS_END);
?>