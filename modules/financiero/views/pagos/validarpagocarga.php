<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use yii\data\ArrayDataProvider;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', $opag_id, ['id' => 'txth_ids']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 ">
    <h3><span id="lbl_Personeria"><?= financiero::t("Pagos", "Payments made") ?></span></h3>
    <br>
</div>

<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
    <div class="form-group">
        <label for="txt_solicitud" class="col-sm-4 control-label" id="lbl_solicitud"><?= admision::t("Solicitudes", "Application number") ?></label>
        <div class="col-sm-8 ">
            <?= $sins_id ?>
        </div>
    </div>
</div>  

<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
    <div class="form-group">
        <label for="txt_nombres" class="col-sm-4  control-label" id="lbl_nombres"><?= Yii::t("formulario", "Names") ?></label> 
        <div class="col-sm-8 ">
            <?= $nombres . "  " . $apellidos . " (".$respCliente["per_cedula"].")"?>
        </div>
    </div>
</div>    

<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
    <div class="form-group">
        <label for="txt_valortotal" class="col-sm-4  control-label" id="lbl_valortotal"><?= Yii::t("formulario", "Total value") ?></label>
        <div class="col-sm-8 ">
            <?= "$ " . $valortotal ?>
        </div>
    </div>
</div>   

<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
    <div class="form-group">
        <label for="txt_valoraplicado" class="col-sm-4 control-label" id="lbl_valortotal"><?= Yii::t("formulario", "Approved value") ?></label>
        <div class="col-sm-8 ">
            <?php if (!empty($valoraplicado)) { ?>
                <?= "$ " . $valoraplicado ?>
            <?php } ?>
        </div>
    </div></br>
</div>   
<?=
PbGridView::widget([
    'id' => 'TbG_Pagos', 
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'columns' =>
    [
        [
            'attribute' => 'formapago',
            'header' => financiero::t("Pagos", "Way to pay"),
            'value' => 'formapago',
        ],
        [
            'attribute' => 'valor',
            'header' => financiero::t("Pagos", "Amount Paid"),
            'value' => 'valor',
        ],
        [
            'attribute' => 'fechaprobacion',
            'header' => financiero::t("Pagos", "Payment approval"),
            'value' => 'fechapago',
        ],
        [
            'attribute' => 'fechacargo',
            'header' => financiero::t("Pagos", "Payment date load"),
            'value' => 'fechacargo',
        ],
        [
            'attribute' => 'estado',
            'header' => Yii::t("formulario", "Status"),
            'value' => 'estado',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("formulario", "Actions"),
            'template' => '{view} {descarga}', 
            'buttons' => [
                'view' => function ($url, $model) {
                    if ($model['rol'] == 5 || $model['rol'] == 6 || $model['rol'] == 7 || $model['rol'] == 8 || $model['rol'] == 15) {
                        return '<span class = "glyphicon glyphicon-eye-open">  </span>';
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['pagos/viewpagacarga', 'popup' => "true", 'fpag' => base64_encode($model['formapago']), 'ido' => base64_encode($model['orden']), 'idd' => base64_encode($model['id']), 'valorpagado' => base64_encode($model['valorpagado']), 'valortotal' => base64_encode($model['valortotal']), 'valor' => base64_encode($model['valor'])]), ["class" => "pbpopup", "data-toggle" => "tooltip", "title" => "Ver Pagos", "data-pjax" => 0]);
                    }
                },
                'descarga' => function ($url, $model) {
                    //if ($model['formapago'] == 'Transferencia' || $model['formapago'] == 'Depósito') {
                        return Html::a('<span class="glyphicon glyphicon-download-alt"></span>', Url::to(['/site/getimage', 'route' => '/uploads/documento/' . $model['per_id'] . '/' . $model['imagen']]), ["download" => $model['imagen'], "data-toggle" => "tooltip", "title" => "Descargar Pago", "data-pjax" => 0]);
                   // }
                },
            ],
        ],
    ],
])
?>
</div>
