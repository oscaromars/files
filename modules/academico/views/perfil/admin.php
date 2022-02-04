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
<style type="text/css">
    @media only screen and (min-width: 1200px) {
      .form-group{
            display: inline-flex;
            width: 100%;
            justify-content: center;
      }
    }
</style>

<form >
    <div class="form-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_estudiante" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Student") ?><span class="text-danger">*</span></label>
                <?= Html::dropDownList("cmb_estudiante", $arr_estudiantes->id, $arr_estudiantes, ["class" => "form-control", "id" => "cmb_estudiante", "class"=>"col-xs-12 col-sm-12 col-md-7 col-lg-7"]) ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        </div>
    </div> 
    <!-- Access Information -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-row">
            <h3><?= Academico::t("perfil", "Access Information") ?></h3>
        </div>
    </div>
    
    <div class="form-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_mail_access" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Correo actual") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control " id="txt_mail_access" value="" data-type="email" autocomplete="off" placeholder="<?= Academico::t("perfil", "Correo") ?>" >
                </div>                    
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_mail_new" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Nuevo correo") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control " id="txt_mail_new" value="" data-type="email" autocomplete="off" placeholder="<?= Academico::t("perfil", "Correo") ?>">
                </div>                    
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_password" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Password") ?><span class="text-danger"></span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <div class="input-group">
                        <?= Html::passwordInput("txt_password", "", ["class" => "form-control", "autocomplete" => "off", "data-type" => "all", "id" => "txt_password", "placeholder" => Yii::t("login", "Password") ]) ?>
                        <?= Html::tag('span', Html::button(Html::tag("i", "", ['class' => 'glyphicon glyphicon-eye-open']), ['id' => "view_pass_btn", 'class' => 'btn btn-primary btn-flat',]), ["class" => "input-group-btn", "data-toggle" => "tooltip", "data-placement" => "top", "title" => Yii::t("accion", "View")]) ?>                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_confirm_password" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Confirm Password") ?><span class="text-danger"></span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="password" class="form-control" id="txt_confirm_password" data-type="all" autocomplete="off" placeholder="<?= Yii::t("login", "Confirm Password") ?>">
                </div>
            </div>
        </div>
    </div> 
    <!-- Personal data -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-row">
            <h3><?= Academico::t("perfil", "Personal Data") ?></h3>
        </div>
    </div>
    <div class="form-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_first_name" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "First Name") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control " id="txt_first_name" value="" data-type="alfa" placeholder="<?= Academico::t("perfil", "First Name") ?>" >
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_second_name" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Second Name") ?></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control" id="txt_second_name" value="" data-type="alfa" placeholder="<?= Academico::t("perfil", "Second Name") ?>" >
                </div>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_first_lastname" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "First Surname") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control " id="txt_first_lastname" value="" data-type="alfa" placeholder="<?= Academico::t("perfil", "First Surname") ?>"  >
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_second_lastname" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Second Surname") ?></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control" id="txt_second_lastname" value="" data-type="alfa" placeholder="<?= Academico::t("perfil", "Second Surname") ?>"  >
                </div>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_document_type" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Document type") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?= Html::dropDownList("cmb_document_type", "", $arr_tipo_dni, ["class" => "form-control", "id" => "cmb_document_type"]) ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_document_id" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Document ID") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control  " id="txt_document_id" value="" data-type="all" placeholder="<?= Academico::t("perfil", "Documento de identificación") ?>"  >
                </div>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_gender" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Gender") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?= Html::dropDownList("cmb_gender", "", $arr_genero, ["class" => "form-control", "id" => "cmb_gender"]) ?>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_civil_state" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Civil State") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?= Html::dropDownList("cmb_civil_state", "", $arr_estado_civil, ["class" => "form-control", "id" => "cmb_civil_state"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_ethnicity" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Ethnicity") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?= Html::dropDownList("cmb_ethnicity", "", $arr_etnia, ["class" => "form-control", "id" => "cmb_ethnicity"]) ?>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_blood_type" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Blood type") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?= Html::dropDownList("cmb_blood_type", "", $arr_tipos_sangre, ["class" => "form-control", "id" => "cmb_blood_type"]) ?>
                </div>
            </div>   
        </div>
    </div>
    <div class="form-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_address" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Yii::t("formulario", "Calle") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_address" data-lengthMax = 100  data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Calle") ?>">
                </div>          
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_cellphone" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Phone") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control " id="txt_cellphone" value="" data-type="number" data-lengthMax = 17 placeholder="<?= Yii::t("formulario", "+0000000000000000") ?>"  >
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_birth_date" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Yii::t("formulario", "Birth Date") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_birth_date',
                        //'value' => empty($persona->per_fecha_nacimiento)?'':date(Yii::$app->params["dateByDefault"],strtotime($persona->per_fecha_nacimiento)),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control  keyupmce", "id" => "txt_birth_date", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Birth Date"), "" =>""],
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
    <div class="form-row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nationality" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Nationality") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control " id="txt_nationality" value="" data-type="alfa" placeholder="<?= Academico::t("perfil", "Nationality") ?>"  >
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_country" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Birth Country") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?= Html::dropDownList("cmb_country", "", $arr_pais, ["class" => "form-control", "id" => "cmb_country"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_state" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Birth Prov/Est") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?= Html::dropDownList("cmb_state", "", $arr_pro, ["class" => "form-control", "id" => "cmb_state"]) ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_city" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Academico::t("perfil", "Birth City") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?= Html::dropDownList("cmb_city", "", $arr_can, ["class" => "form-control", "id" => "cmb_city"]) ?>
                </div>
            </div>
        </div>
    </div>  
    <div class="form-row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_ciu_nac" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Yii::t("formulario", "Nacionalidad Ecuatoriana") ?><span class="text-danger">*</span></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <label>
                        <input type="radio" name="signup-ecu" value="1" <?php
                        if ($persona->per_nac_ecuatoriano == 1) {
                            echo 'checked';
                        }
                        ?>  > Si<br>
                    </label>
                    <label>
                        <input type="radio" name="signup-ecu" value="0" <?php
                        if ($persona->per_nac_ecuatoriano == 0) {
                            echo 'checked';
                        }
                        ?>  > No<br>
                    </label>            
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            
        </div>
    </div>   
    
     
</form>
<input type="hidden" id="per_id" value="">