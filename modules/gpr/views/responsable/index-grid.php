<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Nombre',
                'header' => yii::t("formulario", "Names"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Empresa',
                'header' => yii::t("formulario", 'Company'),
                'value' => 'Empresa',
            ],
            [
                'attribute' => 'Unidad',
                'header' => gpr::t("unidad", 'Unity Name'),
                'value' => 'Unidad',
            ],
            [
                'attribute' => 'Nivel',
                'header' => gpr::t("responsablesubunidad", 'Level'),
                'value' => 'Nivel',
            ],
            [
                'attribute' => 'IsAdmin',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("responsablesubunidad", 'Is Auditor'),
                'value' => function($data){
                    if($data["IsAdmin"] == "1")
                        return '<small class="label label-success">'.gpr::t("responsablesubunidad", "Auditor").'</small>';
                    else
                        return '<small class="label label-warning">'.gpr::t("responsablesubunidad", "No Auditor").'</small>';
                },
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("responsablesubunidad", "Responsible Status"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.gpr::t("responsablesubunidad", "Responsible Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.gpr::t("responsablesubunidad", "Responsible Disabled").'</small>';
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
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/gpr/responsable/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
