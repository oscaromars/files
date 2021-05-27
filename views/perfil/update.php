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

//print_r($respPersona);
?>
<?= Html::hiddenInput('txth_ftem_id', 0, ['id' => 'txth_ftem_id']); ?>
<?= Html::hiddenInput('txth_errorFile', Yii::t("formulario", "The file extension is not valid or exceeds the maximum size in MB recommending him try again"), ['id' => 'txth_errorFile']); ?>

<div class="col-md-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<div class="col-md-12">
    <div class="nav-tabs-custom">       
        <div class="tab-content col-md-12">
            <div class="tab-pane active" id="paso1">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab1', [
                        'paises_nac' => $arr_pais_nac,
                        'provincias_nac' => $arr_prov_nac,
                        'cantones_nac' => $arr_ciu_nac,
                        'tipos_dni' => $tipo_dni,
                        'genero' => $genero,
                        'etnica' => $arr_etnia,
                        'tipos_sangre' => $tipos_sangre,
                        'eciv_id' => $respPersona['eciv_id'],
                        'estado_civil' => $arr_civil,
                        'per_pri_nombre' => $respPersona['per_pri_nombre'],
                        'per_seg_nombre' => $respPersona['per_seg_nombre'],
                        'per_pri_apellido' => $respPersona['per_pri_apellido'],
                        'per_seg_apellido' => $respPersona['per_seg_apellido'],
                        'per_cedula' => $respPersona['per_cedula'],
                        'per_genero' => $respPersona['per_genero'],
                        'per_fecha_nacimiento' => $respPersona['per_fecha_nacimiento'],
                        'etn_id' => $respPersona['etn_id'],
                        'pai_id_nacimiento' => $respPersona['pai_id_nacimiento'],
                        'pro_id_nacimiento' => $respPersona['pro_id_nacimiento'],
                        'can_id_nacimiento' => $respPersona['can_id_nacimiento'],
                        'eciv_descripcion' => substr(strtoupper($respPersona['eciv_descripcion']), 0, 3),
                        'per_correo' => $respPersona['per_correo'],
                        'per_celular' => $respPersona['per_celular'],
                        'tsan_id' => $respPersona['tsan_id'],                        
                        'per_nacionalidad' => $respPersona['per_nacionalidad'],
                        'area' => $area['name'],
                        'per_pasaporte' => $respPersona['per_pasaporte'],
                        'paises_dom' => $arr_pais_dom,
                        'provincias_dom' => $arr_prov_dom,
                        'cantones_dom' => $arr_ciu_dom,
                        'pai_id_domicilio' => $respPersona['pai_id_domicilio'],
                        'pro_id_domicilio' => $respPersona['pro_id_domicilio'],
                        'can_id_domicilio' => $respPersona['can_id_domicilio'],
                        'per_domicilio_telefono' => $respPersona['per_domicilio_telefono'],
                        'sector' => $respPersona['sector'],
                        'per_domicilio_cpri' => $respPersona['per_domicilio_cpri'],
                        'secundaria' => $respPersona['secundaria'],
                        'per_domicilio_num' => $respPersona['per_domicilio_num'],
                        'per_domicilio_ref' => $respPersona['per_domicilio_ref'],
                        'area_dom' => $area_dom['name'],
                        'paises_nac' => $arr_pais_nac,                        
                        'per_corInstitucional' => $respPerCorInstitucional['pcin_correo'],
                        'cgen_nombre' => $respContGeneral['nombre'],
                        'cgen_apellido' => $respContGeneral['apellido'],
                        'cgen_celular' => $respContGeneral['celular'],
                        'tpar_id' => $respContGeneral['parentesco'],
                        'cgen_telefono' => $respContGeneral['telefono'],
                        'cgen_direccion' => $respContGeneral['direccion'],
                        'per_foto' => $respPersona['per_foto'],
                        'otraetnia' => $respotraetnia['oetn_nombre'],
                        "widthImg" => $widthImg,
                        "heightImg" => $heightImg,
                    ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->      
        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->
</div><!-- /.col -->