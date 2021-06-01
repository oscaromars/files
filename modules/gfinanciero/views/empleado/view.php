<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

$token = SearchAutocomplete::getToken();

$urlFoto = '';
if($per_foto != ""){
    $urlFoto = Url::base(). '/site/getimage/?route=' . $per_foto;
}
?>

<form class="form-horizontal" enctype="multipart/form-data" id="newform" method="post">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><?= Yii::t("formulario", "Data Personal") ?></a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true"><?= Yii::t("formulario", "Address Information") ?></a></li>
                        <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="true"><?= financiero::t("empleado", "Financial Information") ?></a></li>
                        <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="true"><?= financiero::t("empleado", "Account Information") ?></a></li>
                        <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="true"><?= financiero::t("articulo", "Observations") ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <?= $this->render('view-ginfo', [
                                'arr_genero' => $arr_genero,
                                'arr_civil' => $arr_civil,
                                'tipos_sangre' => $tipos_sangre,
                                'arr_pais_nac' => $arr_pais_nac,
                                "pai_nac_id" => $pai_nac_id,
                                "pro_nac_id" => $pro_nac_id,
                                "can_nac_id" => $can_nac_id,
                                'arr_prov_nac' => $arr_prov_nac,
                                'arr_ciu_nac' => $arr_ciu_nac,
                                'etnica' => $etnica,
                                'per_foto' => $per_foto,
                                'urlFoto' => $urlFoto,
                                'widthImg' => $widthImg,
                                'heightImg' => $heightImg,
                                'modelPer' => $modelPer,
                            ]); ?>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <?= $this->render('view-domicilio', [
                                "pai_dom_id" => $pai_dom_id,
                                "pro_dom_id" => $pro_dom_id,
                                "can_dom_id" => $can_dom_id,
                                'arr_pais_dom' => $arr_pais_dom,
                                'arr_prov_dom' => $arr_prov_dom,
                                'arr_ciu_dom' => $arr_ciu_dom,
                                'modelPer' => $modelPer,
                            ]); ?>
                        </div>
                        <div class="tab-pane" id="tab_3">
                            <?= $this->render('view-financiero', [
                                'arr_banco' => $arr_banco,
                                'tipo_pago' => $tipo_pago,
                                'cuenta_code' => $cuenta_code,
                                'cuenta_name' => $cuenta_name,
                                'arr_departamento' => $arr_departamento,
                                'arr_subdepartamento' => $arr_subdepartamento,
                                'arr_tipoContrato' => $arr_tipoContrato,
                                'arr_tipoContribuyente' => $arr_tipoContribuyente,
                                'arr_discapacidad' => $arr_discapacidad,
                                'arr_t_empleado' => $arr_t_empleado,
                                'dep_id' => $dep_id,
                                'sdep_id' => $sdep_id,
                                'model' => $model,
                                'arr_cargo' => $arr_cargo,
                                'salario' => $salario,
                                'cargo' => $cargo,
                            ]); ?>
                        </div>
                        <div class="tab-pane" id="tab_4">
                            <?= $this->render('view-cuenta', [
                                'arr_grupo' => $arr_grupo,
                                'arr_rol' => $arr_rol,
                                'long_pass' => $long_pass,
                                'reg_pass' => $reg_pass,
                                'modelUser' => $modelUser,
                                'userGroup' => $userGroup,
                                'userRol' => $userRol,
                            ]); ?>
                        </div>
                        <div class="tab-pane" id="tab_5">
                            <?= $this->render('view-observations', [
                                'modelGemp' => $modelGemp,
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="frm_id" value="<?= $model->empl_codigo ?>" />
    <input type="hidden" id="per_id" value="<?= $model->per_id ?>" />
    <input type="hidden" id="frm_filesize" value="<?= $limitSizeFile ?>" />
    <input type="hidden" id="ctr_rqImg" value="<?= Url::base(). '/site/getimage/?route=' ?>" />
    <input type="hidden" id="reg_pass" value="<?= $reg_pass ?>" />
    <input type="hidden" id="long_pass" value="<?= $long_pass ?>" />
    <input type="hidden" id="desc_pass" value="<?= $desc_pass ?>" />
</form>