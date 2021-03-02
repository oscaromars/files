<?php

//use Yii;
use yii\helpers\Html;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<form class="form-horizontal">
    <div class="row">  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
            <h3><label id="lbl_profesor"><?= Yii::t("formulario", "Data Teacher") ?></label></h3>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">            
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_periodo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Period") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_cabecera['periodo'] ?>" id="txt_periodo" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "Period") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "DNI Document") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_cabecera['per_cedula'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">            
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_cabecera['nombres'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellidos" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellidos"><?= Yii::t("formulario", "Last Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_cabecera['apellidos'] ?>" id="txt_apellidos" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
            <h3><label id="lbl_profesor"><?= Yii::t("formulario", "Asignación") ?></label></h3>
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">            
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_estado" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_estado"><?= Yii::t("formulario", "Review Status") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_estado", $arr_cabecera['estado'], $arr_estados, ["class" => "form-control", "id" => "cmb_estado", "disabled" => "false"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_observacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_observacion"><?= Yii::t("formulario", "Observation") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <!--<textarea  cols="100" rows="5" value="<?php echo $arr_cabecera['observacion'] ?>" id="c" disabled ></textarea> -->
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_cabecera['observacion'] ?>" id="txt_observacion" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "Observation") ?>">
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</form>

<?= Html::hiddenInput('txth_ids', $daca_id, ['id' => 'txth_ids']); ?>
