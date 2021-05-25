<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admision\Module;
?>

<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= Module::t("Agentes", "Executive Reassignment") ?></span>
</div>
<?= Html::hiddenInput('txth_idop', base64_decode($_GET["opor_id"]), ['id' => 'txth_idop']); ?>

<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_interesado" class="col-sm-6 control-label" id="lbl_interesado"><?= Yii::t("formulario", "Complete Names") ?></label>
                <div class="col-sm-6">
                    <input type="text" value="<?= $arr_datosp ["primer_nombre"] . ' ' . $arr_datosp ["primer_apellido"] ?>" class="form-control" id="txt_interesado" disabled="true" placeholder="<?= Yii::t("formulario", "Complete Names") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_agente" class="col-sm-6 control-label" id="lbl_interesado"><?= Module::t("Agentes", "Current Executive") ?></label>
                <div class="col-sm-6">
                    <input type="text" value="<?= $arr_oportunidad["agente"] ?>" class="form-control" id="txt_interesado" disabled="true" placeholder="<?= Module::t("Agentes", "Current Executive") ?>">                
                </div>
            </div>
        </div>
    </div>      
    <div class="col-md-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_agente" class="col-sm-6 control-label keyupmce"><?= Module::t("Agentes", "New Executive") ?></label>
                <div class="col-sm-6">                
                    <?= Html::dropDownList("cmb_agente", 0, $arr_agente, ["class" => "form-control PBvalidation", "id" => "cmb_agente"]) ?>
                </div>
            </div>
        </div>
    </div>    
</form>

