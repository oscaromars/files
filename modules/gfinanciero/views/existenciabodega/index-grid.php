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
            //[
                //'id' => 'chkId',
                //'class' => 'app\widgets\PbGridView\PbCheckboxColumn',
                //'cssClassExpression' => '($data["Estado"]=="2")?"disabled":""',
                //'disabled' => '($data["Estado"]=="2")?true:false',
            //],
            
            [
                'attribute' => 'Id',
                'header' => financiero::t("bodega", "Cellar"),
                'value' => 'Id',
            ],
            [
                'attribute' => 'Cod_art',
                'header' => financiero::t("bodega", "Item Code"),
                'value' => 'Cod_art',
            ],
            [
                'attribute' => 'Nombre',
                'header' => financiero::t("bodega", "Item Name"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Precio',
                'header' => financiero::t("bodega", "Price"),
                //'visible' => '0',
                //'headerOptions' => ['width' => '20'],
                //'contentOptions' => ['style' => 'text-align: center;display:none; border:none;width:8px'],
                'contentOptions' => ['style' => 'text-align: right;'],
                'value' => function ($model) {
                    //return Yii::$app->params["currency"].Yii::$app->formatter->format($model["Precio"],["decimal", 2]);
                    return Yii::$app->formatter->format($model["Precio"],["decimal", 2]);
                },
            ],
            

                        
            [
                'attribute' => 'Existencia',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => financiero::t("articulo", "Stock"),
                'value' => function($data){
                    $Existencia=Yii::$app->formatter->format($data["Existencia"],["decimal", 0]);
                    if(isset($data['Existencia']) && $data['Existencia'] > 0){
                        return '<small class="label label-success">'.$Existencia.'</small>';
                    }else{
                        return '<small class="label label-danger">'.$Existencia.'</small>';
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
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/'.Yii::$app->controller->module->id.'/existenciabodega/view', 'id' => $model['Id'],'id2' => $model['Cod_art']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['Id'] . '\', \'' . $model['Cod_art'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
