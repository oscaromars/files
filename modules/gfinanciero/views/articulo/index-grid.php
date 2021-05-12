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
                'attribute' => 'Id',
                'header' => financiero::t("articulo", "Code"),
                'value' => 'Id',
            ],
            [
                'attribute' => 'Nombre',
                'header' => financiero::t("articulo", "Article Name"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'NomLinea',
                'header' => financiero::t("lineaarticulo", "Line Name"),
                'value' => 'NomLinea',
            ],
            [
                'attribute' => 'NomTipo',
                'header' => financiero::t("tipoarticulo", "Type Name"),
                'value' => 'NomTipo',
            ],
            [
                'attribute' => 'NomMarca',
                'header' => financiero::t("marcaarticulo", "Mark Name"),
                'value' => 'NomMarca',
            ],
            [
                'attribute' => 'TipoItem',
                'header' => financiero::t("tipoitem", "Product Type"),
                'value' => 'TipoItem',
            ],
            [
                'attribute' => 'Precio',
                'header' => financiero::t("articulo", "Price"),
                'value' => 'Precio',
            ],
            [
                'attribute' => 'Existencia',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => financiero::t("articulo", "Stock"),
                'value' => function($data){
                    if(isset($data['Existencia']) && $data['Existencia'] > 0){
                        return '<small class="label label-success">'.$data['Existencia'].'</small>';
                    }else{
                        return '<small class="label label-danger">'.$data['Existencia'].'</small>';
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/'.Yii::$app->controller->module->id.'/articulo/view', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['Id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
