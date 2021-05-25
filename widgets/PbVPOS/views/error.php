<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use app\widgets\PbVPOS\PbVPOS;
use yii\helpers\Html;
?>
<?php if($reloadDB): ?>
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-success"></i> <?= PbVPOS::t("vpos", "Notification") ?>!</h4>
    <?= PbVPOS::t("vpos", "Your payment has already been executed. Click on the button to update the page.") ?>
</div>
<div class="input-group margin btnPago">
    <button type="button" class="btn btn-block btn-success" onclick="reloadFn()"><?= PbVPOS::t("vpos", "Reload") ?></button>
</div>
<?= Html::hiddenInput('vpos_execute_data', $data, ["id" => "vpos_execute_data"]) ?>
<?= Html::hiddenInput('vpos_execute', "1", ["id" => "vpos_execute"]) ?>
<?php elseif($reloadDB ===  false): ?>
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-success"></i> <?= PbVPOS::t("vpos", "Notification") ?>!</h4>
    <?= PbVPOS::t("vpos", "Your payment is pending payment. Please wait a few minutes to try to update your payment.") ?>
</div>
<div class="input-group margin btnPago">
    <button type="button" class="btn btn-block btn-success" onclick="reloadFn()"><?= PbVPOS::t("vpos", "Reload") ?></button>
</div>
<?= Html::hiddenInput('vpos_execute_data', $data, ["id" => "vpos_execute_data"]) ?>
<?= Html::hiddenInput('vpos_execute', "2", ["id" => "vpos_execute"]) ?>
<?php else: ?>
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-warning"></i> <?= PbVPOS::t("vpos", "Error") ?>!</h4>
    <?= PbVPOS::t("vpos", "Problems with Payment Method") ?>
</div>
<?php endif; ?>