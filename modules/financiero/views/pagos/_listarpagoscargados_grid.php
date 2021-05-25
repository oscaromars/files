<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use yii\data\ArrayDataProvider;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\modules\admision\Module as admision;

admision::registerTranslations();
?>

<div>
    <?=
    PbGridView::widget([  
        'id' => 'TbG_Solicitudes',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,   
        'columns' =>
        [        
            [
                'attribute' => 'solicitud',
                'header' => Yii::t("formulario", "Request #"),            
                'value' => 'solicitud',
            ],
            [
                'attribute' => 'fecha',
                'header' => admision::t("Solicitudes", "Application date"),             
                'value' => 'sins_fecha_solicitud',
            ],
            [
                'attribute' => 'dni',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'identificacion',
            ],
            [
                'attribute' => 'Nombres',
                'header' => Yii::t("formulario", "First Names"),
                'value' => 'nombres',
            ],
            [
                'attribute' => 'Apellidos',
                'header' => Yii::t("formulario", "Last Names"),
                'value' => 'apellidos',
            ],
            [
                'attribute' => 'Unidad',
                'header' => Yii::t("formulario", "Academic unit"),
                'value' => 'nivel',
            ],
//            [
//                'attribute' => 'MetodoIngreso',
//                'header' => admision::t("Solicitudes", "Income Method"),
//                'value' => 'metodo',
//            ],
            [
                'attribute' => 'estado',
                'header' => Yii::t("formulario", "Status"),
                'value' => 'estado_desc_pago',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),         
                'template' => '{view}',           
                'buttons' => [
                    'view' => function ($url, $model) {
                        if ($model['estado'] == 'P') {
                            return Html::a('<span class="glyphicon glyphicon-check"></span>', Url::to(['pagos/validarpagocarga', 'ido' => $model['orden']]), ["data-toggle" => "tooltip", "title" => "Ver Pagos", "data-pjax" => 0]);
                        } else {
                            return Html::a('<span class="glyphicon glyphicon-thumbs-up"></span>', Url::to(['pagos/validarpagocarga', 'ido' => $model['orden']]), ["data-toggle" => "tooltip", "title" => "Ver Pagos", "data-pjax" => 0]);
                        }
                    },                   
                ],
            ],
        ],
    ])
    ?>
</div>
