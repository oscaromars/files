<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use app\widgets\PbVPOS\PbVPOS;
?>
<div class="input-group margin btnPago">
    <button type="button" class="btnBuy btn btn-block btn-success disabled" onclick="playOnPay('<?= $processUrl ?>')"><?= PbVPOS::t("vpos", "Buy") ?></button>
</div>
<div id="lightbox-response"></div>