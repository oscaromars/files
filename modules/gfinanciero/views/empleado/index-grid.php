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
                'header' => financiero::t("empleado", "Code"),
                'value' => 'Id',
            ],
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
                'attribute' => 'dni',
                'header' => Yii::t("formulario", "DNI"),
                'value' => 'dni',
            ],
            [
                'attribute' => 'Genero',
                'header' => Yii::t("formulario", "Gender"),
                'value' => 'Genero',
            ],
            [
                'attribute' => 'ECivil',
                'header' => Yii::t("formulario", "Marital Status"),
                'value' => 'ECivil',
            ],
            [
                'attribute' => 'FIngreso',
                'header' => financiero::t("empleado", "Date of Admission"),
                'value' => 'FIngreso',
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
                'attribute' => 'Discapacidad',
                'header' => Yii::t("formulario", "Discapacity"),
                'value' => 'Discapacidad',
            ],
            [
                'attribute' => 'PDiscapacidad',
                'header' => financiero::t("empleado", "Discapacity Percentage"),
                'value' => 'PDiscapacidad',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/'.Yii::$app->controller->module->id.'/empleado/view', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['Id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
