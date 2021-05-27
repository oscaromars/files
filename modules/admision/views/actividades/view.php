<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
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

<?= Html::hiddenInput('txth_opo_id', base64_encode($oportunidad_contacto["opo_id"]), ['id' => 'txth_opo_id']); ?>
<?= Html::hiddenInput('txth_pgid', base64_encode($personalData["pges_id"]), ['id' => 'txth_pgid']); ?>
<?= Html::hiddenInput('txth_acid', base64_encode($actividad_oportunidad["bact_id"]), ['id' => 'txth_acid']); ?>
<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= Module::t("crm", "View Activity") ?></span><br/>    
</div>
<div class="col-md-12">    
    <br/>    
</div>
<div class="col-md-12  col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Data General Contact") ?></span></h4>                                  
            </div>
        </div>            
    </div> 
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $personalData['pges_pri_nombre'] . " " . $personalData['pges_seg_nombre'] ?>" id="txt_nombre1" disabled = "true" data-type="alfa" data-keydown="true"> 
                </div> 
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_apellidos" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $personalData['pges_pri_apellido'] . " " . $personalData['pges_seg_apellido'] ?>" id="txt_apellido1" disabled = "true" data-type="alfa" data-keydown="true"> 
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= $tipodoc ?></label> 
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $dni ?>" id="txt_cedula" disabled = "true" data-type="alfa" data-keydown="true"> 
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_celular" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "CellPhone") ?></label> 
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $personalData['pges_celular'] ?>" id="txt_celular" disabled = "true" data-type="alfa" data-keydown="true"> 
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_correo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Email") ?></label> 
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $personalData['pges_correo'] ?>" id="txt_correo" disabled = "true" data-type="alfa" data-keydown="true"> 
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_pais" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Country") ?></label> 
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $personalData['pais'] ?>" id="txt_pais" disabled = "true" data-type="alfa" data-keydown="true"> 
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Data Oportunity") ?></span></h4>                                  
            </div>
        </div>            
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Company") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_empresa", $oportunidad_contacto['empresa'], $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa", "disabled" => true]) ?>
                </div>
            </div>
        </div>           
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_nivelestudio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Academic unit") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_nivelestudio", $oportunidad_contacto['uaca_id'], $arr_linea_servicio, ["class" => "form-control", "id" => "cmb_nivelestudio", "disabled" => true]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Moda") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_modalidad", $oportunidad_contacto['mod_id'], $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => true]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_tipo_oportunidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Opportunity type") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_tipo_oportunidad", $oportunidad_contacto['tove_id'], $arr_tipo_oportunidad, ["class" => "form-control", "id" => "cmb_tipo_oportunidad", "disabled" => true]) ?>
                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_carrera1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Academic Study") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?php if (empty($oportunidad_contacto["eaca_id"])) { ?>
                        <?= Html::dropDownList("cmb_carrera1", $oportunidad_contacto["mest_id"], $arr_estudio, ["class" => "form-control", "id" => "cmb_carrera_estudio", "disabled" => true]) ?>
                    <?php } else {
                        ?>
                        <?= Html::dropDownList("cmb_carrera1", $oportunidad_contacto["eaca_id"], $arr_academic_study, ["class" => "form-control", "id" => "cmb_carrera_estudio", "disabled" => true]) ?>
                    <?php }
                    ?>                    
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_knowledge_channel" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Contact Channel") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_knowledge_channel", $oportunidad_contacto['ccan_id'], $arr_knowledge_channel, ["class" => "form-control", "id" => "cmb_knowledge_channel", "disabled" => true]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Data Activities") ?></span></h4>                                  
            </div>
        </div>            
    </div>     
    <?php
    $fecha_registro = $actividad_oportunidad['bact_fecha_registro'];
    $fecha_hora = explode(" ", $fecha_registro);
    $fecha = $fecha_hora[0];
    $hora = $fecha_hora[1];
    $fecha_proxima = $actividad_oportunidad['bact_fecha_proxima_atencion'];
    $fecha_hora_proxima = explode(" ", $fecha_proxima);
    $fecha_proxima = $fecha_hora_proxima[0];
    $hora_proxima = $fecha_hora_proxima[1];
    ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">          
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_fecha_atencion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Attention Date") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4 ">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_atencion',
                        'value' => $fecha,
                        'disabled' => true,
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_atencion", "data-type" => "", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Attention Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]
                    ]);
                    ?>
                </div>
                <label hidden for="txt_hora_atencion" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label"><?= Yii::t("formulario", "Attention Hour") ?> </label>            
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">                    
                    <input type="text" class="form-control PBvalidation" disabled = "true" value="<?php echo $hora; ?>" id="txt_hora_atencion" data-type="tiempo" data-keydown="true" placeholder="<?= Yii::t('formulario', 'HH:MM') ?>">
                </div> 
            </div>                    
        </div>     
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_fecha_proxima" class="col-sm-5 col-md-5 col-xs-5 col-lg-5  control-label"><?= Yii::t("formulario", "Date Next attention") ?></label>
                <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4 ">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_next',
                        'value' => $fecha_proxima,
                        'disabled' => true,
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_proxima", "data-type" => "fecha_pro", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">                    
                    <label hidden for="txt_hora_proxima" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Next Hour") ?> </label>                                    
                    <input type="text" class="form-control PBvalidation keyupmce" disabled = "true" value="<?php echo $hora_proxima ?>" id="txt_hora_proxima" data-type="tiempo" data-keydown="true" placeholder="<?= Yii::t('formulario', 'HH:MM') ?>"></div> 
            </div>                    
        </div>                 
    </div>    
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_state_opportunity" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Opportunity state") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?php 
                        $eopo=0;
                        if($actividad_oportunidad['eopo_id'] < 3){
                            $eopo=1;                         
                        }else{
                            $eopo=$actividad_oportunidad['eopo_id'];                         
                        }
                        
                    ?>
                    <?= Html::dropDownList("cmb_state_opportunity", $eopo, $arr_state_oportunidad, ["class" => "form-control", "id" => "cmb_state_opportunity", "disabled" => true])  ?>                    
                </div>
            </div>
        </div>
        <?php
        if ($actividad_oportunidad['eopo_id'] == 5)
            $enable = "display: block;";
        else
            $enable = "display: none;";
        ?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divoportunidad_perdida" style="<?php echo $enable ?>">
            <div class="form-group">
                <label for="cmb_lost_opportunity" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Lost Opportunity") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_lost_opportunity", $oportunidad_contacto['oportunidad_perdida'], $arr_oportunidad_perdida, ["class" => "form-control", "id" => "cmb_lost_opportunity", "disabled" => true]) ?>
                </div>
            </div>
        </div>        
    </div>
    <?php
        if ($oportunidad_contacto['otro_estudio'] == 13)
            $enable = "display: block;";
        else
            $enable = "display: none;";
    ?>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12' id="div_otro_estudio" style="<?php echo $enable ?>" >      
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
            <div class="form-group">
                <label for="cmb_otras_maestrias" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_descripcion"><?= Yii::t("formulario", "Other Studies") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                  
                    <?= Html::dropDownList("cmb_otras_maestrias", $oportunidad_contacto['otro_estudio'], $arr_otro_estudio, ["class" => "form-control", "id" => "cmb_otras_maestrias", "disabled" => true]) ?>                
                </div>
            </div>
        </div> 
    </div>     
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">  
            <div class="form-group">
                <label for="cmb_medio_contacto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_descripcion"><?= Yii::t("formulario", "Half Contact") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">     

                    <?= Html::dropDownList("cmb_medio_contacto", $arr_segData, $arr_seguimiento, ["class" => "multiSelects form-control", "id" => "cmb_medio_contacto", "disable"=>"disable", "name" => "cmb_medio_contacto[]", "multiple"=>"multiple"]) ?>                
                </div>
            </div>
        </div>
    </div>     
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
            <div class="form-group">
                <label for="txt_observacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_descripcion"><?= Yii::t("formulario", "Observation") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_observacion", $actividad_oportunidad["oact_id"], $arr_observacion, ["class" => "form-control", "id" => "cmb_observacion", "disabled" => true]) ?>                
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
            <div class="form-group">
                <label for="txt_descripcion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_descripcion"><?= Yii::t("formulario", "Comments") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <textarea  class="form-control keyupmce" id="txt_descripcion" rows="5"  disabled = "true"><?= $actividad_oportunidad["bact_descripcion"] ?></textarea>                  
                </div>
            </div>
        </div>
    </div>  
</form>