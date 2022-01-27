<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\financiero\models\OrdenPago;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
academico::registerTranslations();
financiero::registerTranslations();
//print_r($model);
?>
<?=

PbGridView::widget([
    'id' => 'TbG_PERSONAS',
    //'showExport' => true,
    //'fnExportEXCEL' => "exportExcel",
    //'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'attribute' => 'Solicitud #',
            'header' => admision::t("Solicitudes", "Request #"),
            'value' => 'num_solicitud',
        ],
        [
            'attribute' => 'Fecha Solicitud ',
            'header' => admision::t("Solicitudes", "Application date"),
            'value' => 'fecha_solicitud',
        ],
        [
            'attribute' => 'Nivel Interes',
            'header' => academico::t("Academico", "Academic unit"),
            'value' => 'nint_nombre',
        ],
        [
            'attribute' => 'Metodo Ingreso',
            'header' => admision::t("Solicitudes", "Income Method"),
            'value' => 'metodo_ingreso',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => academico::t("Academico", "Career/Program/Course"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span>' . substr($model['carrera'], 0, 20) . '... </span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['carrera']]);
                },
            ],
        ],
        [
            'attribute' => 'Estado Solicitud',
            'header' => admision::t("Solicitudes", "State Request"),
            'value' => 'estado',
        ],
        [
            'attribute' => 'Estado Pago',
            'header' => financiero::t("Pagos", "Payment status"),
            'value' => 'estado_pago',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{view} {cancel} {payments} {upload}', //
            'buttons' => [
                'view' => function ($url, $model) {
                    $mod_ordpago = new OrdenPago;
                    $result = $mod_ordpago->consultarImagenpagoexiste($model['opag_id']);
                    if ($result['existe_imagen'] == 0 && $model['estado_pago'] != 'Pagado') { // Aqui si la solicitud esta pendiente  y no ha subido pago
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/admision/solicitudes/viewsolicitud', 'id_sol' => base64_encode($model['sins_id']), 'per_id' => base64_encode($model['per_id']), 'opag_id' => base64_encode($model['opag_id'])]), ["data-toggle" => "tooltip", "title" => "Ver Solicitud", "data-pjax" => 0]);
                    } else {
                        return '<span class="glyphicon glyphicon-eye-open"></span>';
                    }
                },
                'cancel' => function ($url, $model) {
                    if ($model['estado'] == 'Pendiente' && $model['estado_pago'] == 'Pagado') { // Aqui si la solicitud esta pendiente  y no ha subido pago
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', "#", ['onclick' => "anularsolicitud(" . $model['sins_id'] . ",". $model['per_id'] .",". $model['opag_id'] .");", "data-toggle" => "tooltip", "title" => "Anular Solicitud", "data-pjax" => 0]);
                    } else {
                        return '<span class="glyphicon glyphicon-trash"></span>';
                    }
                },
                'payments' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-usd"></span>', Url::to(['/financiero/pagos/listarpagosolicitud', 'id_sol' => base64_encode($model['sins_id']), 'per_id' => $_GET['perid']]), ["data-toggle" => "tooltip", "title" => "Pago de Solicitud", "data-pjax" => 0]);
                },
                'upload' => function ($url, $model) {
                    if ($model['uaca_id'] < 3) {
                        if ($model['numDocumentos'] == 0) {
                            return Html::a('<span class="glyphicon glyphicon-folder-open"></span>', Url::to(['/admision/solicitudes/subirdocumentos', 'id_sol' => base64_encode($model['sins_id']), 'opcion' => base64_encode(1), 'uaca' => base64_encode($model['uaca_id'])]), ["data-toggle" => "tooltip", "title" => "Subir Documentos Inscrito", "data-pjax" => 0]);
                        } else {
                            return '<span class="glyphicon glyphicon-folder-open"></span>';
                        }
                    } else {
                        return '<span class="glyphicon glyphicon-folder-open"></span>';
                    }
                },
            ],
        ],
    ],
])
?>