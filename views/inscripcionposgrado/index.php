<?php

use yii\helpers\Url;
use yii\helpers\Html;

//print_r($respPerinteresado);
session_start();
$_SESSION['persona_ingresa'] = base64_decode($_GET['ids']);

?>
<?= Html::hiddenInput('txth_ftem_id', 0, ['id' => 'txth_ftem_id']); ?>
<?= Html::hiddenInput('txth_errorFile', Yii::t("formulario", "The file extension is not valid or exceeds the maximum size in MB recommending him try again"), ['id' => 'txth_errorFile']); ?>

<div class="col-md-12  col-xs-12 col-sm-12 col-lg-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs tabsdatos col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <li class="active"><a href="#paso1" data-href="#paso1" data-toggle="tab" aria-expanded="true"><img class="" src="<?= Url::home() ?>img/users/n1.png" alt="User Image">  <?= Yii::t("interesado", "Personal Information") ?></a></li>
            <li class="disabled"><a data-href="#paso2" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n2.png" alt="User Image">  <?= Yii::t("interesado", "Información Profesional") ?></a></li>
            <li class="disabled"><a data-href="#paso3" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n3.png" alt="User Image">  <?= Yii::t("interesado", "Información de Financiamiento") ?></a></li>
            <li class="disabled"><a data-href="#paso4" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n4.png" alt="User Image">  <?= Yii::t("interesado", "Subir Documentación") ?></a></li>
        </ul>
        <div class="tab-content col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <div class="tab-pane active" id="paso1">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab1', [
                        'arr_unidad' => $arr_unidad,
                        'arr_programa' => $arr_programa,
                        'arr_modalidad' => $arr_modalidad,
                        "tipos_dni" => $tipos_dni,
                        "tipos_dni2" => $tipos_dni2,
                        'per_pri_nombre' => $respPerinteresado['per_pri_nombre'],
                        'per_seg_nombre' => $respPerinteresado['per_seg_nombre'],
                        'per_pri_apellido' => $respPerinteresado['per_pri_apellido'],
                        'per_seg_apellido' => $respPerinteresado['per_seg_apellido'],
                        'per_cedula' => $respPerinteresado['per_cedula'],
                        'arr_ciudad_nac' => $arr_ciudad_nac,
                        'per_fecha_nacimiento' => $respPerinteresado['per_fecha_nacimiento'],
                        'pai_id_nacimiento' => $respPerinteresado['pai_id_nacimiento'],
                        'pro_id_nacimiento' => $respPerinteresado['pro_id_nacimiento'],
                        'can_id_nacimiento' => $respPerinteresado['can_id_nacimiento'],
                        'eciv_descripcion' => substr(strtoupper($respPerinteresado['eciv_descripcion']), 0, 3),
                        'per_correo' => $respPerinteresado['per_correo'],
                        'per_celular' => $respPerinteresado['per_celular'],
                        'per_nacionalidad' => $respPerinteresado['per_nacionalidad'],
                        'area' => $area['name'],
                        'per_pasaporte' => $respPerinteresado['per_pasaporte'],
                        'arr_nacionalidad' => $arr_nacionalidad,
                        'eciv_id' => $respPerinteresado['eciv_id'],
                        "arr_estado_civil" => $arr_estado_civil,
                        'arr_ciudad' => $arr_ciudad,
                        "arr_pais" => $arr_pais,
                        "arr_provincia" => $arr_provincia,
                        "arr_pais_reside" => $arr_pais_reside,
                        'arr_tipparentesco' => $arr_tipparentesco,
                    ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso2">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab2', [
                        'tipos_institucion' => $tipos_institucion,
                        'arr_pais_emp' => $arr_pais,
                        'arr_prov_emp' => $arr_prov_emp,
                        'arr_ciu_emp' => $arr_ciu_emp,
                        'arr_categoria' => $arr_categoria,
                        'arr_idioma' => $arr_idioma,
                        'arr_nivelidioma' => $arr_nivelidioma,
                        'arr_idioma2' => $arr_idioma2,
                        'arr_nivelidioma2' => $arr_nivelidioma2,
                        //'arr_discapacidad' => $arr_discapacidad,
                        'arr_tip_discap' => $arr_tip_discap,
                        'graduacion' => $graduacion,
                        'tipo_discap_fam' => $arr_tip_discap_fam,
                        'tipparent_dis' => $arr_tipparent_dis,
                        'tipparent_enf' => $arr_tipparent_enf,

                    ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso3">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab3', [
                        'tipo_financiamiento' => $tipo_financiamiento,
                    ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso4">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab4', [
                        'per_id' => $per_id,
                        "arr_convenio_empresa" => $arr_convenio_empresa,
                    ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->
</div><!-- /.col -->