<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\modules\Academico\Module as Academico;
Academico::registerTranslations();

$tipodoc = "CED";
?>

<form class="form-horizontal">
<!-- Personal data -->
    <h3><?= Academico::t("perfil", "Personal Data") ?></h3>  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_first_name" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "First Name") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation" id="txt_first_name" value="<?= $persona->per_pri_nombre ?>" data-type="alfa" placeholder="<?= Academico::t("perfil", "First Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_second_name" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Second Name") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control" id="txt_second_name" value="<?= $persona->per_seg_nombre ?>" data-type="alfa" placeholder="<?= Academico::t("perfil", "Second Name") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_first_lastname" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "First Surname") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation" id="txt_first_lastname" value="<?= $persona->per_pri_apellido ?>" data-type="alfa" placeholder="<?= Academico::t("perfil", "First Lastname") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_second_lastname" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Second Surname") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control" id="txt_second_lastname" value="<?= $persona->per_seg_apellido ?>" data-type="alfa" placeholder="<?= Academico::t("perfil", "Second Surname") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_document_type" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Document type") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_document_type", $tipodoc, $arr_tipo_dni, ["class" => "form-control", "id" => "cmb_document_type"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_document_id" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Document ID") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation " id="txt_document_id" value="<?= $persona->per_cedula ?>" data-type="all" placeholder="<?= Academico::t("perfil", "Identification Card") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_gender" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Gender") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_gender", $persona->per_genero, $arr_genero, ["class" => "form-control", "id" => "cmb_gender"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_civil_state" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Civil State") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_civil_state", $persona->eciv_id, $arr_estado_civil, ["class" => "form-control", "id" => "cmb_civil_state"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_ethnicity" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Ethnicity") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_ethnicity", $persona->etn_id, $arr_etnia, ["class" => "form-control", "id" => "cmb_ethnicity"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_other_ethnicity" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Other ethnicity") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control" id="txt_other_ethnicity" value="<?= $otra_etnia ?>" data-type="alfa" placeholder="<?= Academico::t("perfil", "Other ethnicity") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_cellphone" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Phone") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation" id="txt_cellphone" value="<?= $persona->per_celular ?>" data-type="all" placeholder="<?= Academico::t("perfil", "Phone") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_mail" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Mail") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation" id="txt_mail" value="<?= $persona->per_correo ?>" data-type="email" placeholder="<?= Academico::t("perfil", "Mail") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_blood_type" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Blood type") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_blood_type", $persona->tsan_id, $arr_tipos_sangre, ["class" => "form-control", "id" => "cmb_blood_type"]) ?>
                </div>
            </div>   
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_birth_date" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "Birth Date") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_birth_date',
                        'value' => date(Yii::$app->params["dateByDefault"],strtotime($persona->per_nacionalidad)),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_birth_date", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Birth Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nationality" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Nationality") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation" id="txt_nationality" value="<?= $persona->per_nacionalidad ?>" data-type="alfa" placeholder="<?= Academico::t("perfil", "Nationality") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_ciu_nac" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Nac. Ecuadorian") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <label>
                        <input type="radio" name="signup-ecu" value="1" <?php
                        if ($persona->per_nac_ecuatoriano == 1) {
                            echo 'checked';
                        }
                        ?>> Si<br>
                    </label>
                    <label>
                        <input type="radio" name="signup-ecu" value="0" <?php
                        if ($persona->per_nac_ecuatoriano == 0) {
                            echo 'checked';
                        }
                        ?>> No<br>
                    </label>            
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_country" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Birth Country") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_country", $persona->pai_id_nacimiento, $arr_pais, ["class" => "form-control", "id" => "cmb_country"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_state" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Birth Prov/Est") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_state", $persona->pro_id_nacimiento, $arr_pro, ["class" => "form-control", "id" => "cmb_state"]) ?>
                </div>
            </div>
        </div>
    </div>  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_city" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Birth City") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_city", $persona->can_id_nacimiento, $arr_can, ["class" => "form-control", "id" => "cmb_city"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            
        </div>
    </div>   
    
    <!-- Access Information -->
    <h3><?= Academico::t("perfil", "Access Information") ?></h3>      
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_mail_access" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Mail") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation" id="txt_mail_access" value="<?= $user->usu_user ?>" data-type="email" placeholder="<?= Academico::t("perfil", "Mail") ?>" disabled>
                </div>                    
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            
        </div>  
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_password" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Password") ?><span class="text-danger">*</span></label>
                <div class="col-sm-9 ">
                    <div class="input-group">
                        <?= Html::passwordInput("txt_password", "", ["class" => "form-control PBvalidation", "data-type" => "all", "id" => "txt_password", "placeholder" => Yii::t("login", "Password") ]) ?>
                        <?= Html::tag('span', Html::button(Html::tag("i", "", ['class' => 'glyphicon glyphicon-eye-open']), ['id' => "view_pass_btn", 'class' => 'btn btn-primary btn-flat',]), ["class" => "input-group-btn", "data-toggle" => "tooltip", "data-placement" => "top", "title" => Yii::t("accion", "View")]) ?>                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_confirm_password" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("perfil", "Confirm Password") ?><span class="text-danger">*</span></label>
                <div class="col-sm-9 ">
                <input type="password" class="form-control PBvalidation" id="txt_confirm_password" data-type="all" placeholder="<?= Yii::t("login", "Confirm Password") ?>">
                </div>
            </div>
        </div>
    </div>  
</form>
<input type="hidden" id="per_id" value="<?= $persona->per_id ?>">
<br />