<?php

use yii\helpers\Html;
use app\modules\admision\Module;

if (!empty($personalData['pges_cedula'])) {
    $tipodoc = "Cédula";
    $dni = $personalData['pges_cedula'];
} else {
    if (!empty($personalData['pges_pasaporte'])) {
        $tipodoc = "Pasaporte";
        $dni = $personalData['pges_pasaporte'];
    } else {
        $tipodoc = "Cédula";
        $dni = $personalData['pges_cedula'];
    }
}
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<?= Html::hiddenInput('txth_opid', base64_encode($oportuniData['opo_id']), ['id' => 'txth_opid']); ?>
<?= Html::hiddenInput('txth_pgid', base64_encode($personalData['pges_id']), ['id' => 'txth_pgid']); ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_evaluar"><?= Yii::t("formulario", "List Activity for Oportunity") ?></span></h3>
</div>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
    <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
        <div class="form-group">
            <h4><span id="lbl_general"><?= Yii::t("formulario", "Data Contact") ?></span></h4> 
        </div>
    </div>

    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nombre1" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                <span for="txt_nombre1" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label" id="lbl_nombre1"><?= $personalData['pges_pri_nombre'] . " " . $personalData['pges_seg_nombre'] ?> </span> 
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_apellido1" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?> </label>
                <span for="txt_apellido1" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label" id="lbl_apellido1"><?= $personalData['pges_pri_apellido'] . " " . $personalData['pges_seg_apellido'] ?> </span> 
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_cedula" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= $tipodoc ?></label> 
                <span for="txt_cedula" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label"><?= $dni ?> </span> 
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_celular" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= Yii::t("formulario", "CellPhone") ?></label> 
                <span for="txt_celular" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label"><?= $personalData['pges_celular'] ?> </span> 
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_correo" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= Yii::t("formulario", "Email") ?></label> 
                <span for="txt_correo" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label"><?= $personalData['pges_correo'] ?> </span> 
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_pais" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= Yii::t("formulario", "Country") ?></label> 
                <span for="txt_pais" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label"><?= $personalData['pais'] ?> </span> 
            </div>
        </div>
    </div>
    <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
        <div class="form-group">
            <h4><span id="lbl_general"><?= Yii::t("formulario", "Opportunity Data") ?></span></h4> 
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_linea" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Academic unit") ?> </label>
                <span for="txt_linea" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label" id="lbl_nombre1"><?= $oportuniData['unidad_academica'] ?> </span> 
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_modalidad" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Mode") ?></label>
                <span for="txt_modalidad" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label" id="lbl_apellido1"><?= $oportuniData['modalidad'] ?> </span> 
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_tioportunidad" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><? Yii::t("formulario", "Opportunity") ?></label> 
                <span for="txt_tioportunidad" class="col-sm-8 col-md-8 col-xs-8 col-lg-8 control-label"><? $oportuniData['tipo_oportunidad'] ?></span> 
            </div>
        </div>-->
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_estoportunidad" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= Yii::t("formulario", "Status") ?></label> 
                <span for="txt_estoportunidad" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label"><?= $oportuniData['estado_oportunidad'] ?> </span> 
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-md-10"></div>
       <!-- <div class="col-md-2">
            <?php 
            $eopor = $oportuniData['eopo_id'];
            if ($eopor == 4 || $eopor == 5) {
                ?>
            <?php
            if ($eopor == 4)
                $estado_oportunidad = "Oportunidad Ganada";
            else if ($eopor == 5)
                $estado_oportunidad = "Oportunidad Perdida";
            ?>
            <a id="#" href="javascript:" disabled="true" class="btn btn-primary btn-block" data-toggle = "tooltip" title = "<?php echo $estado_oportunidad ?>" data-pjax = 0 > <?= Yii::t("formulario", "New Activity") ?></a> 
                <?php 
            } else { ?>
            <a id="btn_crearactividad" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "New Activity") ?></a> 
            <?php 
        } ?>
        </div>-->
    </div>  
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
    <div>
        <?=
        $this->render('listaractividad-grid', [
            'model' => $model,
        ]);
        ?>
    </div>
</div>