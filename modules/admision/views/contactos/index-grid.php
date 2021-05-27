<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Persona;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module;
use app\modules\admision\Module as admision;

admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'Pbcontacto',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'tableOptions' => [
            'class' => 'table table-condensed',
        ],
        'options' => [
            'class' => 'table-responsive table-striped',
        ],
        //'condensed' => true,
        'dataProvider' => $model,
        'columns' => [
            /*[
                'class'=>'kartik\grid\ExpandRowColumn',
                'value'=> function ($model,$key,$index,$column) {
                return GridView::ROW_COLLAPSED;
                },
            ],*/    
            [
                'attribute' => 'Contacto',
                'header' => Module::t("crm", "Contact"),
                'value' => 'cliente',
            ],
            [
                'attribute' => 'codigo',
                'header' => Yii::t("formulario", "Code"),
                'value' => 'pges_codigo',
            ],
            [
                'attribute' => 'Pais',
                'header' => Yii::t("formulario", "Country"),
                'value' => 'pais',
            ],
            [
                'attribute' => 'Fecha',
                'header' => Yii::t("formulario", "Date"),
                'value' => 'fecha_creacion',
            ],
            [
                'attribute' => 'unidad_academica',
                'header' => Yii::t("formulario", "Academic unit"),
                'value' => 'unidad_academica',
            ],
            [
                'attribute' => 'empresa',
                'header' => Yii::t("formulario", "Company"),
                'value' => 'empresa',
            ],
            [
                'attribute' => 'agente',
                'header' => Yii::t("formulario", "User login"),
                'value' => 'agente',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Module::t("crm", "Channel"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if ($model['canal'] != '') {
                            $texto = substr($model['canal'], 0, 7) . '...';
                        } else {
                            $texto = '';
                        }
                        return Html::a('<span>' . $texto . '</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['canal']]);
                    },
                ],
            ],           
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "User login"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if ($model['usuario_ing'] != '') {
                            $texto = substr($model['usuario_ing'], 0, 10) . '...';
                        } else {
                            $texto = '';
                        }
                        return Html::a('<span>' . $texto . '</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['usuario_ing']]);
                    },
                ],
            ],
            [
                'attribute' => 'NumOportunidadesAbiertas',
                'header' => Yii::t("formulario", "Open Opportunities"),
                'value' => 'num_oportunidad_abiertas',
            ],
            [
                'attribute' => 'NumOportunidadesCerradas',
                'header' => Yii::t("formulario", "Close Opportunities"),
                'value' => 'num_oportunidad_cerradas',
            ],
            [
                'attribute' => 'estadogestion',
                'header' => Yii::t("formulario", "Management State"),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'value' => function ($model) {
                    if ($model["gestion"] == 2)
                        return '<small class="label label-success">Gestionado</small>';                    
                    else
                        return '<small class="label label-danger">Pendiente Gestionar</small>';
                },                
            ],                            
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"), //{update} 
                'template' => '{view} {opportunities}', //    
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['contactos/view', 'codigo' => base64_encode($model["pestion_id"]), 'tper' => base64_encode($model["tipo_persona"])]), ["data-toggle" => "tooltip", "title" => "Ver Contacto", "data-pjax" => 0]);
                    },
                    //'update' => function ($url, $model) {
                    //    return Html::a('<span class="glyphicon glyphicon-edit"></span>', Url::to(['admisiones/actualizarcontacto', 'codigo' => base64_encode($model["pestion_id"]), 'tper_id' => base64_encode($model["tipo_persona"])]), ["data-toggle" => "tooltip", "title" => "Modificar Contacto", "data-pjax" => 0]);
                    //},
                    'opportunities' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-th-large"></span>', Url::to(['contactos/listaroportunidad', 'pgid' => base64_encode($model['pestion_id'])]), ["data-toggle" => "tooltip", "title" => "Lista de Oportunidades", "data-pjax" => 0]);
                    },
                ],
            ],
        ],
        //'responsiveWrap' => true,
    ])
    ?>
</div>   
