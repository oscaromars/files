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
use app\modules\academico\Module as academico;
academico::registerTranslations();
admision::registerTranslations(); // trae las traducciones del modulo
?>

<div>
    <?=
    PbGridView::widget([
        'id' => 'TbG_Solicitudes',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelPagos",
        'fnExportPDF' => "exportPdfpagos",
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
                'attribute' => 'UnidadAcademica',
                'header' => academico::t("Academico", "Aca. Uni."),
                'value' => 'nivel',
            ],
            [
                'attribute' => 'MetodoIngreso',
                'header' => admision::t("Solicitudes", "Income Method"),
                'value' => 'metodo',
            ],
            [
                'attribute' => 'estado',
                'header' => Yii::t("formulario", "Status"),
                'value' => 'estado_desc_pago',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{view} {upload} {uploadFact} {downloadFact}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if ($model['estado'] != 'P') {
                            return Html::a('<span class="glyphicon glyphicon-thumbs-up"></span>', Url::to(['pagos/validarpagocarga', 'ido' => $model['orden']]), ["data-toggle" => "tooltip", "title" => "Ver Pagos", "data-pjax" => 0]);
                        }
                    },
                    'upload' => function ($url, $model) {
                        if ($model['rol'] == 1) {
                            return Html::a('<span class="glyphicon glyphicon-download-alt"></span>', Url::to(['pagos/cargardocpagos', 'ids' => base64_encode($model['orden']), 'estado' => base64_encode($model['estado_desc_pago']), 'vista' => 'adm']), ["data-toggle" => "tooltip", "title" => "Subir Documento", "data-pjax" => 0]);
                        }
                    },
                    'uploadFact' => function ($url, $model) {
                        //if ($model['rol'] == 1) {
                            return Html::a('<span class="glyphicon glyphicon-hdd"></span>', Url::to(['/financiero/pagos/cargardocfact', 'ids' => base64_encode($model['sins_id'])]), ["data-toggle" => "tooltip", "title" => "Subir Factura", "data-pjax" => 0]);
                        //}
                    },
                    'downloadFact' => function ($url, $model) {
                        $ruta= \app\modules\financiero\models\OrdenPago::consultarRutaFile($model['sins_id']);
                        if ($ruta !== 0) {
                            return Html::a('<span class="glyphicon glyphicon-download-alt"></span>', Url::to(['/financiero/pagos/descargafactura', 'ids' => base64_encode($model['sins_id']) ]), ["data-toggle" => "tooltip", "title" => "Descargar Factura", "data-pjax" => 0]);
                        }
                    },
                ],
            ],

        ],
    ])
    ?>
</div>
