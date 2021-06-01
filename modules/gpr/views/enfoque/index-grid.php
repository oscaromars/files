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
                'header' => gpr::t("enfoque", "Focus Name"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Descripcion',
                'header' => gpr::t("enfoque", 'Focus Description'),
                'value' => 'Descripcion',
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("enfoque", "Focus Status"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.gpr::t("enfoque", "Focus Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.gpr::t("enfoque", "Focus Disabled").'</small>';
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
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/gpr/enfoque/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                        //return  Html::a('Action', Url::to(['mceformulariotemp/solicitudpdf','ids' => 1],['class' => 'btn btn-default',"target" => "_blank"]));
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
