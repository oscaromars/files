<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\models\ObjetoModulo;
?>

<?=
    PbGridView::widget([
        'id' => 'grid_provincia_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Nombre',
                'header' => Yii::t("provincia", "Nombre de Provincia"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Pais',
                'header' => Yii::t("pais", 'Pais'),
                'value' => 'Pais',
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => Yii::t("provincia", "Estado de Provincia"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.Yii::t("provincia", "Provincia Activa").'</small>';
                    else
                        return '<small class="label label-danger">'.Yii::t("provincia", "Provincia Inactiva").'</small>';
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
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['provincia/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);                        
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
