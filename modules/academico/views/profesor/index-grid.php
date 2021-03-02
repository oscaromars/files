<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>

<?=

PbGridView::widget([
    'id' => 'grid_profesor_list',
    'showExport' => false,
    //'fnExportEXCEL' => "exportExcel",
    //'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'PrimerNombre',
            'header' => Academico::t("profesor", "Name"),
            'value' => 'PrimerNombre',
        ],
        [
            'attribute' => 'PrimerApellido',
            'header' => Academico::t("profesor", "Surname"),
            'value' => 'PrimerApellido',
        ],
        [
            'attribute' => 'Celular',
            'header' => Academico::t("profesor", "Mobile"),
            'value' => function($data) {
                if (isset($data['Celular'])) {
                    return $data['Celular'];
                }
                return "";
            },
        ],
        [
            'attribute' => 'Correo',
            'header' => Academico::t("profesor", "Mail"),
            'value' => function($data) {
                if (isset($data['Correo'])) {
                    return $data['Correo'];
                }
                return "";
            },
        ], [
            'attribute' => 'Cedula',
            'header' => Academico::t("profesor", "Identification Card"),
            'value' => function($data) {
                if (isset($data['Cedula'])) {
                    return $data['Cedula'];
                }
                return "";
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            //'header' => 'Action',
            'contentOptions' => ['style' => 'text-align: center;'],
            'headerOptions' => ['width' => '90'],
            'template' => '{view} {delete} {download} {pdf}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['profesor/view', 'id' => $model['per_id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View")]);
                },
                'delete' => function ($url, $model) {
                    if ($model['perfil'] == 0) {
                        if ($model['estado'] == 1) {
                            return Html::a('<span class="' . Utilities::getIcon('remove') . '"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['per_id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion", "Delete")]);
                        } else {
                            return '<span class="' . Utilities::getIcon('remove') . '"></span>';
                            // glyphicon glyphicon-check
                        }
                    }
                },
                'download' => function ($url, $model) {
                    //if ($model['perfil'] == 0) {
                        //if($model['Cv'] != "")
                        //return Html::a('<span class="'.Utilities::getIcon('download').'"></span>', 'javascript:', ["data-toggle" => "tooltip", "title" => Yii::t("accion","Download"), 'data-href' => Url::to(['profesor/download', 'route' => $model['Cv'], 'type' => 'down']), 'onclick' => 'downloadPdf(this)']);
                        return Html::a('<span class="glyphicon glyphicon-download"></span>', Url::to(['/academico/profesor/curriculumpdf', 'ids' => $model['per_id']]), ["data-toggle" => "tooltip", "title" => "Descargar Curriculum", "data-pjax" => "0"]);
                    //}
                },
               /* 'pdf' => function ($url, $model) {
                    if ($model['perfil'] == 0) {
                        // if(isset($model['Cv']) && $model['Cv'] != "")
                        return Html::a('<span class="' . Utilities::getIcon('info') . '"></span>', 'javascript:', ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View Document"), 'data-href' => Url::to(['profesor/download', 'route' => $model['Cv'], 'type' => 'view']), 'onclick' => 'viewPdf(this)']);
                    }
                },*/
            ],
        ],
    ],
])
?>