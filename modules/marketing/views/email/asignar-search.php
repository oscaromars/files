<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\modules\marketing\Module as marketing;
?>
<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Datos de la Lista") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombre" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_nombre"><?= Yii::t("formulario", "Name") ?></label>
                    <span for="txt_nombre" class="col-sm-6 col-md-6 col-xs-6 col-lg-6 control-label" id="lbl_nombre"><?= $arr_lista['lis_nombre'] ?> </span> 
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_sus" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="txt_no_subs"><span  class="label label-success"><?=  marketing::t("marketing", "Subscribers") ?>:</span></label>
                    <span for="txt_sus" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="txt_no_subs"><?= $num_suscr ?> </span> 
                </div>
            </div> 
        </div>
    </div> 
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12"> 
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_estado" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label"><?= Yii::t("formulario", "Estado") ?></label>
                    <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6 control-label">
                        <?= Html::dropDownList("cmb_suscrito", 0, $arr_estado, ["class" => "form-control", "id" => "cmb_suscrito"]) ?>
                    </div>
                </div>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_no_sus" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="txt_no_subs"><span  class="label label-danger"><?=  marketing::t("marketing", "No Subscribers") ?></span></label>
                    <span for="txt_no_sus" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="txt_no_subs"><?= $noescritos ?> </span> 
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_mailchimp" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="txt_no_subs"><span  class="label label-warning"><?=  marketing::t("marketing", "Mailchimp") ?></span></label>
                    <span for="txt_mailchimp" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="txt_no_subs"><?= $num_suscr_chimp ?> </span> 
                </div>
            </div> 
        </div>        
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="col-sm-8"></div>
            <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">
                <a id="btn_buscarDataListaSus" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
            </div>
        </div>        
    </div> 
</div> 
<div><br><br></div>



