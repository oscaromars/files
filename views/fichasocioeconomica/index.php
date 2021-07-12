<?php

use yii\helpers\Url;
use yii\helpers\Html;

//print_r($respPerinteresado);
//session_start();
//$_SESSION['persona_ingresa'] = base64_decode($_GET['ids']);

?>

<div class="col-md-12  col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<div class="col-md-12  col-xs-12 col-sm-12 col-lg-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs tabsdatos col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <li class="active"><a href="#paso1" data-href="#paso1" data-toggle="tab" aria-expanded="true"><img class="" src="<?= Url::home() ?>img/users/n1.png" alt="User Image">  <?= Yii::t("interesado", "Personal Information") ?></a></li>
            <li class="disabled"><a data-href="#paso2" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n2.png" alt="User Image">  <?= Yii::t("interesado", "Academic Information") ?></a></li>
            <li class="disabled"><a data-href="#paso3" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n3.png" alt="User Image">  <?= Yii::t("interesado", "Información de Financiamiento") ?></a></li>
            <li class="disabled"><a data-href="#paso4" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n4.png" alt="User Image">  <?= Yii::t("interesado", "Información de Idiomas") ?></a></li>
            <li class="disabled"><a data-href="#paso5" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n5.png" alt="User Image">  <?= Yii::t("interesado", "Información Laboral") ?></a></li>
            <li class="disabled"><a data-href="#paso6" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n6.png" alt="User Image">  <?= Yii::t("interesado", "Infomación de Becas") ?></a></li>
        </ul>
        <div class="tab-content col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <div class="tab-pane active" id="paso1">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab1', [
                        'per_genero' => $per_genero,
                        'genero' => $genero,
                        'arr_nacionalidad' => $arr_nacionalidad,
                        "arr_pais" => $arr_pais,
                        "arr_pais_reside" => $arr_pais_reside,
                        "arr_etnia" => $arr_etnia,
                        'arr_tip_discap' => $arr_tip_discap,


                        /*'paises_nac' => $arr_pais_nac,
                        'provincias_nac' => $arr_prov_nac,
                        'cantones_nac' => $arr_ciu_nac,
                        'tipos_dni' => $tipo_dni,
                        'tipos_sangre' => $tipos_sangre,
                        'eciv_id' => $respPerinteresado['eciv_id'],
                        'estado_civil' => $arr_civil,
                        'per_pri_nombre' => $respPerinteresado['per_pri_nombre'],
                        'per_seg_nombre' => $respPerinteresado['per_seg_nombre'],
                        'per_pri_apellido' => $respPerinteresado['per_pri_apellido'],
                        'per_seg_apellido' => $respPerinteresado['per_seg_apellido'],
                        'per_cedula' => $respPerinteresado['per_cedula'],
                        //'per_genero' => $respPerinteresado['per_genero'],
                        'per_fecha_nacimiento' => $respPerinteresado['per_fecha_nacimiento'],
                        'etn_id' => $respPerinteresado['etn_id'],
                        'pai_id_nacimiento' => $respPerinteresado['pai_id_nacimiento'],
                        'pro_id_nacimiento' => $respPerinteresado['pro_id_nacimiento'],
                        'can_id_nacimiento' => $respPerinteresado['can_id_nacimiento'],
                        'eciv_descripcion' => substr(strtoupper($respPerinteresado['eciv_descripcion']), 0, 3),
                        'per_correo' => $respPerinteresado['per_correo'],
                        'per_celular' => $respPerinteresado['per_celular'],
                        'tsan_id' => $respPerinteresado['tsan_id'],
                        'tipparent_dis' => $arr_tipparent_dis,
                        'per_nacionalidad' => $respPerinteresado['per_nacionalidad'],
                        'area' => $area['name'],
                        'per_pasaporte' => $respPerinteresado['per_pasaporte'],*/
                    ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso2">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab2', [
                        'tipos_institucion' => $tipos_institucion,
                        'arr_pais_col' => $arr_pais_col,
                        'arr_prov_col' => $arr_prov_col,
                        'arr_ciu_col' => $arr_ciu_col,
                        'graduacion' => $graduacion,
                        


                        /*'paises_dom' => $arr_pais_dom,
                        'provincias_dom' => $arr_prov_nac,
                        'cantones_dom' => $arr_ciu_nac,
                        'pai_id_domicilio' => $respPerinteresado['pai_id_domicilio'],
                        'pro_id_domicilio' => $respPerinteresado['pro_id_domicilio'],
                        'can_id_domicilio' => $respPerinteresado['can_id_domicilio'],
                        'per_domicilio_telefono' => $respPerinteresado['per_domicilio_telefono'],
                        'sector' => $respPerinteresado['sector'],
                        'per_domicilio_cpri' => $respPerinteresado['per_domicilio_cpri'],
                        'secundaria' => $respPerinteresado['secundaria'],
                        'per_domicilio_num' => $respPerinteresado['per_domicilio_num'],
                        'per_domicilio_ref' => $respPerinteresado['per_domicilio_ref'],
                        'area_dom' => $area_dom['name'],
                        'paises_nac' => $arr_pais_nac,
                        'pai_id_nacimiento' => $respPerinteresado['pai_id_nacimiento'],*/
                    ]);
                    ?>                  
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso3">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab3', [
                        'tipo_financiamiento' => $tipo_financiamiento,

                        /*'paises_med' => $arr_pais_med,
                        'provincias_med' => $arr_prov_med,
                        'cantones_med' => $arr_ciu_med,
                        'paises_ter' => $arr_pais_ter,
                        'provincias_ter' => $arr_prov_ter,
                        'cantones_ter' => $arr_ciu_ter,
                        'paises_cuat' => $arr_pais_cuat,
                        'provincias_cuat' => $arr_prov_cuat,
                        'cantones_cuat' => $arr_ciu_cuat,
                        'tipos_institucion' => $tipos_institucion,*/
                        /*                         * */
                        //'tip_instaca_med' => $arr_tip_instaca_med,
                        //'tip_instaca_ter' => $arr_tip_instaca_ter,
                        //'tip_instaca_cuat' => $arr_tip_instaca_cuat,
                    ]);
                    ?>              
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso4">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab4', [
                        //'arr_idiomas' => $arr_idiomas,

                        //'ninstruc_mad' => $arr_ninstruc_mad,
                        //'ninstruc_pad' => $arr_ninstruc_pad,
                    ]);
                    ?>               
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso5">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab5', [
                        'tipo_discap' => $arr_tip_discap,
                        'tipo_discap_fam' => $arr_tip_discap_fam,
                        'tipparent_dis' => $arr_tipparent_dis,
                        'tipparent_enf' => $arr_tipparent_enf,
                    ]);
                    ?> 
                </form>
            </div>
            <div class="tab-pane" id="paso6">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab6', [
                        'tipo_discap' => $arr_tip_discap,
                        'tipo_discap_fam' => $arr_tip_discap_fam,
                        'tipparent_dis' => $arr_tipparent_dis,
                        'tipparent_enf' => $arr_tipparent_enf,
                    ]);
                    ?> 
                </form>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->
</div><!-- /.col -->