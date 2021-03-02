<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\inventario\Module as inventario;
?>
<?=
PbGridView::widget([
    //'dataProvider' => new yii\data\ArrayDataProvider(array()),
    'id' => 'Tbg_Listar',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'columns' =>
    [   
        [
            'attribute' => 'Departamento',
            'header' => inventario::t("inventario", "Department"),
            'value' => 'departamento',
        ],
        [
            'attribute' => 'Area',
            'header' => inventario::t("inventario", "Work area"),
            'value' => 'area',
        ],
        [
            'attribute' => 'Espacio',
            'header' => inventario::t("inventario", "Space"),
            'value' => 'espacio',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => inventario::t("inventario", "Category"),
            'template' => '{view}',            
            'buttons' => [
                    'view' => function ($url, $model) {
                        if ($model['categoria'] != '') {
                            $texto = substr($model['categoria'], 0, 30) . '...';
                        } else {
                            $texto = '';
                        }
                        return Html::a('<span>' . $texto . '</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['categoria']]);
                    },
                ],
        ],
        [
            'attribute' => 'Código',
            'header' => inventario::t("inventario", "Code"),
            'value' => 'afij_codigo',
        ],        
        [
            'attribute' => 'Custodio',
            'header' => inventario::t("inventario", "Custodian"),
            'value' => 'afij_custodio',
        ],         
        [
            'attribute' => 'Cantidad',
            'header' => inventario::t("inventario", "Quantity"),
            'value' => 'afij_cantidad',
        ],  
       /* [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{view} {borrar} {visor}',
            'buttons' => [
                'view' => function ($url, $model) {                                        
                    return Html::a('<span class="glyphicon glyphicon-download-alt"></span>', Url::to(['repositorio/downloadfile', 'ids' => base64_encode($model['dre_id'])]), ["data-toggle" => "tooltip", "title" => "Descargar Evidencia", "data-pjax" => 0]);                   
                },                                                            
            ],
        ],*/
    ],
])
?>