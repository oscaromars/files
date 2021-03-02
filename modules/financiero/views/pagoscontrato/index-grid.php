<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Utilities;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
admision::registerTranslations();
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <?=
    PbGridView::widget([
        'id' => 'TbG_PAGOS',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' =>
        [
            [
                'attribute' => 'solicitud',
                'header' => admision::t("Solicitudes", "Request #"),
                'value' => 'solicitud',
            ],
            [
                'attribute' => 'DNI',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'per_dni',
            ],
            [
                'attribute' => 'Nombres',
                'header' => Yii::t("formulario", "First Names"),
                'value' => 'per_nombres',
            ],
            [
                'attribute' => 'Apellidos',
                'header' => Yii::t("formulario", "Last Names"),
                'value' => 'per_apellidos',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => admision::t("Solicitudes", "Income Method"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span>' . $model['abr_metodo'] . '</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['ming_nombre']]);
                    },
                ],
            ],
            [
                'attribute' => 'modalidad',
                'header' => admision::t("Solicitudes", "Modalidad"),
                'value' => 'mod_nombre',
            ],            
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => academico::t("Academico", "Career/Program"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span>' . substr($model['carrera'], 0, 10) . '..</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['carrera']]);
                    },
                ],
            ],
            [
                'attribute' => 'beca',
                'header' =>  admision::t("Solicitudes", "Scholarship"),
                'value' => 'beca',
            ],            
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{subir} {descargar}', //
                'buttons' => [
                    'subir' => function ($url, $model) {
                        if ($model['documento'] == 0) {
                            return Html::a('<span class="glyphicon glyphicon-open"></span>', Url::to(['/financiero/pagoscontrato/cargarcontrato', 'sids' => base64_encode($model['sins_id']), 'adm_id' => base64_encode($model['adm_id']), 'per_id' => base64_encode($model['per_id'])]), ["data-toggle" => "tooltip", "title" => "Subir Contrato", "data-pjax" => 0]);
                        } else {
                            return "<span class = 'glyphicon glyphicon-open' data-toggle = 'tooltip' title ='Contrato Subido'  data-pjax = 0></span>";
                        }                         
                    },    
                    'descargar' => function ($url, $model) {
                        if ($model['documento'] > 0) {
                            return Html::a('<span class="'.Utilities::getIcon('download').'"></span>', 'javascript:', ["data-toggle" => "tooltip", "title" => Yii::t("accion","Download"), 'data-href' => Url::to(['pagoscontrato/download', 'route' => $model['ruta_contrato'], 'type' => 'down']), 'onclick' => 'downloadPdfs(this)']);
                        } else {
                            return "<span class = 'glyphicon glyphicon-download-alt' data-toggle = 'tooltip' title ='Descargar Contrato'  data-pjax = 0></span>";
                        }                         
                    },
                ],
            ],
        ],
    ])
    ?>
</div>