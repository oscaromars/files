<?php
/*
 * The Asgard framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by Asgard Software 
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *  - Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *  - Neither the name of Asgard Software nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Asgard is based on code by
 * Yii Software LLC (http://www.yiisoft.com) Copyright Â© 2008
 *
 * Authors:
 * 
 * Diana Lopez <dlopez@uteg.edu.ec>
 * 
 */

use yii\helpers\Url;
use yii\helpers\Html;

//print_r($respPerinteresado);
?>
<?= Html::hiddenInput('txth_ftem_id', 0, ['id' => 'txth_ftem_id']); ?>
<?= Html::hiddenInput('txth_errorFile', Yii::t("formulario", "The file extension is not valid or exceeds the maximum size in MB recommending him try again"), ['id' => 'txth_errorFile']); ?>
<div class="col-md-12  col-xs-12 col-sm-12 col-lg-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs tabsdatos col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <li class="active"><a href="#paso1" data-href="#paso1" data-toggle="tab" aria-expanded="true"><img class="" src="<?= Url::home() ?>img/users/n1.png" alt="User Image"><?= Yii::t("interesado", "Personal Information") ?></a></li>
            <li><a href="#paso2" data-toggle="tab" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n2.png" alt="User Image"><?= Yii::t("interesado", "Home Information") ?></a></li>
            <li><a href="#paso3" data-toggle="tab" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n3.png" alt="User Image"><?= Yii::t("interesado", "Academic Information") ?></a></li>
            <li><a href="#paso4" data-toggle="tab" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n4.png" alt="User Image"><?= Yii::t("interesado", "Family Information") ?></a></li>
            <li><a href="#paso5" data-toggle="tab" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n5.png" alt="User Image"><?= Yii::t("interesado", "Additional Information") ?></a></li>
        </ul>
        <div class="tab-content col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <div class="tab-pane active" id="paso1">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab1_view', [
                        'paises_nac' => $arr_pais_nac,
                        'provincias_nac' => $arr_prov_nac,
                        'cantones_nac' => $arr_ciu_nac,
                        'tipos_dni' => $tipo_dni,
                        'genero' => $genero,
                        'etnica' => $arr_etnia,
                        'tipos_sangre' => $tipos_sangre,
                        'eciv_id' => $respPerinteresado['eciv_id'],
                        'estado_civil' => $arr_civil,
                        'per_pri_nombre' => $respPerinteresado['per_pri_nombre'],
                        'per_seg_nombre' => $respPerinteresado['per_seg_nombre'],
                        'per_pri_apellido' => $respPerinteresado['per_pri_apellido'],
                        'per_seg_apellido' => $respPerinteresado['per_seg_apellido'],
                        'per_cedula' => $respPerinteresado['per_cedula'],
                        'per_genero' => $respPerinteresado['per_genero'],
                        'per_fecha_nacimiento' => $respPerinteresado['per_fecha_nacimiento'],
                        'pai_id_nacimiento' => $respPerinteresado['pai_id_nacimiento'],
                        'pro_id_nacimiento' => $respPerinteresado['pro_id_nacimiento'],
                        'can_id_nacimiento' => $respPerinteresado['can_id_nacimiento'],
                        'eciv_descripcion' => substr(strtoupper($respPerinteresado['eciv_descripcion']), 0, 3),
                        'per_correo' => $respPerinteresado['per_correo'],
                        'per_celular' => $respPerinteresado['per_celular'],
                        'tsan_id' => $respPerinteresado['tsan_id'],
                        'etn_id' => $respPerinteresado['etn_id'],
                        'per_nac_ecuatoriano' => $respPerinteresado['per_nac_ecuatoriano'],
                        'tipparent_dis' => $arr_tipparent_dis,
                        'con_celular' => $respcontacto['celular'],
                        'con_telefono' => $respcontacto['telefono'],
                        'con_parentesco' => $respcontacto['parentesco'],
                        'con_nombre' => $respcontacto['nombre'],
                        'con_direccion' => $respcontacto['direccion'],
                        'per_nacionalidad' => $respPerinteresado['per_nacionalidad'],
                        'oetn_nombre' => $respotraetnia['oetn_nombre'],
                        'area' => $area['name'],
                        'per_pasaporte' => $respPerinteresado['per_pasaporte'],
                        'modifica' => '0',
                    ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso2">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab2_view', [
                        'paises_dom' => $arr_pais_dom,
                        'provincias_dom' => $arr_prov_dom,
                        'cantones_dom' => $arr_ciu_dom,
                        'pai_id_domicilio' => $respPerinteresado['pai_id_domicilio'],
                        'pro_id_domicilio' => $respPerinteresado['pro_id_domicilio'],
                        'can_id_domicilio' => $respPerinteresado['can_id_domicilio'],
                        'per_domicilio_telefono' => $respPerinteresado['per_domicilio_telefono'],
                        'sector' => $respPerinteresado['sector'],
                        'per_domicilio_cpri' => $respPerinteresado['per_domicilio_cpri'],
                        'secundaria' => $respPerinteresado['secundaria'],
                        'per_domicilio_num' => $respPerinteresado['per_domicilio_num'],
                        'per_domicilio_ref' => $respPerinteresado['per_domicilio_ref'],
                        'area' => $area['name'],
                        'area_dom' => $area_dom['name'],
                    ]);
                    ?>                  
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso3">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab3_view', [
                        'paises_med' => $arr_pais_med,
                        'provincias_med' => $arr_prov_med,
                        'cantones_med' => $arr_ciu_med,
                        'paises_ter' => $arr_pais_ter,
                        'provincias_ter' => $arr_prov_ter,
                        'cantones_ter' => $arr_ciu_ter,
                        'paises_cuat' => $arr_pais_cuat,
                        'provincias_cuat' => $arr_prov_cuat,
                        'cantones_cuat' => $arr_ciu_cuat,
                        'tipos_institucion' => $tipos_institucion,
                        /*                         * */
                        'tip_instaca_med' => $arr_tip_instaca_med,
                        'tip_instaca_ter' => $arr_tip_instaca_ter,
                        'tip_instaca_cuat' => $arr_tip_instaca_cuat,
                        /*                           */
                        'pais_medio' => $respacademicomedio['pais'],
                        'provincia_medio' => $respacademicomedio['provincia'],
                        'canton_medio' => $respacademicomedio['canton'],
                        'tipo_institutomedio' => $respacademicomedio['tipo_instituto'],
                        'tipo_estudiomedio' => $respacademicomedio['tipo_estudio'],
                        'institutomedio' => $respacademicomedio['instituto'],
                        'titulomedio' => $respacademicomedio['titulo'],
                        'gradomedio' => $respacademicomedio['grado'],
                        /*                           */
                        'pais_tercer' => $respacademicotercer['pais'],
                        'provincia_tercer' => $respacademicotercer['provincia'],
                        'canton_tercer' => $respacademicotercer['canton'],
                        'tipo_institutotercer' => $respacademicotercer['tipo_instituto'],
                        'tipo_estudiotercer' => $respacademicotercer['tipo_estudio'],
                        'institutotercer' => $respacademicotercer['instituto'],
                        'titulotercer' => $respacademicotercer['titulo'],
                        'gradotercer' => $respacademicotercer['grado'],
                        /*                           */
                        'pais_cuarto' => $respacademicocuarto['pais'],
                        'provincia_cuarto' => $respacademicocuarto['provincia'],
                        'canton_cuarto' => $respacademicocuarto['canton'],
                        'tipo_institutocuarto' => $respacademicocuarto['tipo_instituto'],
                        'tipo_estudiocuarto' => $respacademicocuarto['tipo_estudio'],
                        'institutocuarto' => $respacademicocuarto['instituto'],
                        'titulocuarto' => $respacademicocuarto['titulo'],
                        'gradocuarto' => $respacademicocuarto['grado'],
                    ]);
                    ?>              
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso4">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab4_view', [
                        'ninstruc_mad' => $arr_ninstruc_mad,
                        'ninstruc_pad' => $arr_ninstruc_pad,
                        /*                           */
                        'instru_padre' => $respfamilia['inst_padre'],
                        'instru_madre' => $respfamilia['inst_madre'],
                        'miembro' => $respfamilia['miembro'],
                        'salario' => $respfamilia['salario'],
                    ]);
                    ?>               
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso5">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab5_view', [
                        'tipo_discap' => $arr_tip_discap,
                        'tipo_discap_fam' => $arr_tip_discap_fam,
                        'tipparent_dis' => $arr_tipparent_dis,
                        'tipparent_enf' => $arr_tipparent_enf,
                        /*                                   */
                        'tipo_discapacidadint' => $respinfodiscapacidad['tipo_discapacidad'],
                        'discapacidadinte' => $respinfodiscapacidad['discapacidad'],
                        'porcentajeint' => $respinfodiscapacidad['porcentaje'],
                        'enfermedadint' => $respinfoenfermedad['enfermedad'],
                        'parentescofadisc' => $respinfodiscapacidadf['parentescofa'],
                        'tipo_descapacidadfa' => $respinfodiscapacidadf['tipo_descapacidadfa'],
                        'porcentajefadis' => $respinfodiscapacidadf['porcentajefa'],
                        'discapacidadfa' => $respinfodiscapacidadf['discapacidadfa'],
                        'parentescoen' => $respinfoenfermedadf['parentescoen'],
                        'enfermedaden' => $respinfoenfermedadf['enfermedaden'],
                        'tipoenfermedaden' => $respinfoenfermedadf['tipoenfermedaden'],
                    ]);
                    ?> 
                </form>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->
</div><!-- /.col -->