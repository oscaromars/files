<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\models\Persona;

?>

<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_cedula" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Cédula") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_cedula" value="<?= $persona_model->per_cedula ?>" data-type="alfa" placeholder="<?= Yii::t("inscripcionposgrado", "Cédula")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_pasaporte" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Pasaporte") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_pasaporte" value="<?= $persona_model->per_pasaporte ?>" data-type="alfa" placeholder="<?= Yii::t("inscripcionposgrado", "Pasaporte")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_pri_nombre" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Primer Nombre") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_pri_nombre" value="<?= $persona_model->per_pri_nombre ?>" data-type="all" placeholder="<?= Yii::t("inscripcionposgrado", "Primer Nombre")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_seg_nombre" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Segundo Nombre") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_seg_nombre" value="<?= $persona_model->per_seg_nombre ?>" data-type="alfa" placeholder="<?=Yii::t("inscripcionposgrado", "Segundo Nombre")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_pri_apellido" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Primer Apellido") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_pri_apellido" value="<?= $persona_model->per_pri_apellido ?>" data-type="alfa" placeholder="<?= Yii::t("inscripcionposgrado", "Primer Apellido")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_seg_apellido" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Segundo Apellido") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_seg_apellido" value="<?= $persona_model->per_seg_apellido ?>" data-type="alfa" placeholder="<?= Yii::t("inscripcionposgrado", "Segundo Apellido")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_ciudadEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Ciudad de Nacimiento") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_ciudadEdit", $persona_model->can_id_nacimiento, $arr_ciudad_nac, ["class" => "form-control", "id" => "cmb_ciudadEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_fecha_nacimiento" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Fecha de Nacimiento") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_fecha_nacimiento" value="<?= $persona_model->per_fecha_nacimiento ?>"  data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "Fecha de Nacimiento")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_nacionalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("perfil", "Nacionalidad") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("frm_nacionalidad", $persona_model->per_nacionalidad, $arr_nacionalidad, ["class" => "form-control", "id" => "frm_nacionalidad"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_estadocivilEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Estado Civil") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_estadocivilEdit", $persona_model->eciv_id, $arr_estado_civil, ["class" => "form-control", "id" => "cmb_estadocivilEdit"]) ?>
            </div>
        </div>
    </div>  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_paisEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Pais") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_paisEdit", $persona_model->pai_id_domicilio, $arr_pais, ["class" => "form-control", "id" => "cmb_paisEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_provinciaEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Provincia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_provinciaEdit", $persona_model->pro_id_domicilio, $arr_provincia, ["class" => "form-control", "id" => "cmb_provinciaEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_cantonEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Cantón") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_cantonEdit", $persona_model->can_id_domicilio, $arr_ciudad, ["class" => "form-control", "id" => "cmb_cantonEdit"]) ?>
            </div>
        </div>
    </div>  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_domicilio_refEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Dirección del domicilio") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_domicilio_refEdit" value="<?= $persona_model->per_domicilio_ref ?>" data-type="all" placeholder="<?= Yii::t("inscripcionposgrado", "Detallar la dirección de su domicilio")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_celEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Celular") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_celEdit" value="<?= $persona_model->per_celular ?>" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "Celular")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_phoneEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Télefono") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_phoneEdit" value="<?= $persona_model->per_domicilio_telefono ?>" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "Télefono")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_correo" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Correo") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_correo" value="<?= $persona_model->per_correo ?>" data-type="alfa" placeholder="<?= Yii::t("inscripcionposgrado", "Correo")  ?>">
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_contacto_emergenciaEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "En Caso de Emergencia") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_contacto_emergenciaEdit" value="<?= $contacto_model->pcon_nombre ?>" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Persona por contactar en caso de Emergencia") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_parentescoEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Tipo de Parentesco") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_parentescoEdit", $contacto_model->tpar_id, $arr_tipparentesco, ["class" => "form-control", "id" => "cmb_parentescoEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_telefono_emergenciaEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Phone")?><span class="text-danger">*</span></label> 
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" data-required="false" id="txt_telefono_emergenciaEdit" value="<?= $contacto_model->pcon_celular ?>" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Teléfono de la persona de contacto en caso de emergencia ") ?>">
            </div>
        </div>
    </div>
</form>