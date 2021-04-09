<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gfinanciero\models\Catalogo;
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
                'attribute' => 'Id',
                'header' => financiero::t("catalogo", "Code"),
                'value' => 'Id',
            ],
            [
                'attribute' => 'Nombre',
                'header' => financiero::t("catalogo", "Account"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'TipoCta',
                'header' => financiero::t("catalogo", "Account Type"),
                //'value' => 'TipoCta',
                'value' => function ($model) {
                    $tipo = ($model['TipoCta'] != '') ? $model['TipoCta'] : 1;
                    return Catalogo::getTipoCuenta($tipo);
                }
            ],
                    [
                'attribute' => 'TipoReg',
                'header' => financiero::t("catalogo", "Record Type"),
                'value' => 'TipoReg',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/'.Yii::$app->controller->module->id.'/catalogo/view', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['Id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
