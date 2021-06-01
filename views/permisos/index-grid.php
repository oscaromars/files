<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\models\GrupObmo;

?>

<?= PbGridView::widget([
        'id' => 'grid_omod_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Grupo',
                'header' => Yii::t("grupo", "Name of Group"),
                'value' => 'Grupo',
            ],
            [
                'attribute' => 'Rol',
                'header' => Yii::t("rol", 'Name of Role'),
                'value' => 'Rol',
            ],
            [
                'attribute' => 'GrupoId',
                'header' => Yii::t("modulo", "SubModule Name"),
                'value' => function($data){
                    $arr_model = new GrupObmo();
                    $arr_data = $arr_model->getObjModuleNameByGroup($data['id']);
                    $out = "";
                    foreach ($arr_data as $key => $value) {
                        $out .= $value['ObjNombre'] . " - ";
                    }
                    return substr($out, 0, -3);
                },
            ],
            /*[
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => Yii::t("modulo", "Status"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.Yii::t("modulo", "Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.Yii::t("modulo", "Disabled").'</small>';
                },
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['permisos/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
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