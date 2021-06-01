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
                'attribute' => 'Nombre',
                'header' => Yii::t("formulario", "First Names"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Apellido',
                'header' => Yii::t("formulario", "Last Names"),
                'value' => 'Apellido',
            ],
            [
                'attribute' => 'Departamento',
                'header' => financiero::t("empleado", "Department"),
                'value' => 'Departamento',
            ],
            [
                'attribute' => 'SubDepartamento',
                'header' => financiero::t("empleado", "SubDepartment"),
                'value' => 'SubDepartamento',
            ],
            [
                'attribute' => 'Multa',
                'header' => financiero::t("novedadesmultas", "Penalty"),
                'value' => 'Multa',
            ],
            [
                'attribute' => 'FechaPago',
                'header' => financiero::t("novedadesmultas", "Payment Date"),
                'value' => 'FechaPago',
            ],
            [
                'attribute' => 'EstadoCancelado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => financiero::t("novedadesmultas", "Status"),
                'value' => function($data){
                    if($data['EstadoCancelado'] == "1"){
                        return '<span class="label label-success">'.financiero::t("novedadesmultas", "Canceled").'</span>';
                    }else{
                        return '<span class="label label-danger">'.financiero::t("novedadesmultas", "No Canceled").'</span>';
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
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/'.Yii::$app->controller->module->id.'/novedadesmultas/view', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['Id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
