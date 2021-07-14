<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\academico\Module as academico;

academico::registerTranslations();

//print_r($respPerinteresado);
session_start();
$_SESSION['persona_ingresa'] = base64_decode($_GET['ids']);

?>
<?= Html::hiddenInput('txth_ftem_id', 0, ['id' => 'txth_ftem_id']); ?>
<?= Html::hiddenInput('txth_errorFile', Yii::t("formulario", "The file extension is not valid or exceeds the maximum size in MB recommending him try again"), ['id' => 'txth_errorFile']); ?>
<div class="col-md-12  col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<div class="col-md-12  col-xs-12 col-sm-12 col-lg-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs tabsdatos col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <li class="active"><a href="#paso1" data-href="#paso1" data-toggle="tab" aria-expanded="true"><img class="" src="<?= Url::home() ?>img/users/n1.png" alt="User Image">  <?= Yii::t("interesado", "Personal Information") ?></a></li>
            <li class="disabled"><a data-href="#paso2" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n2.png" alt="User Image">  <?= Yii::t("interesado", "Academic Information") ?></a></li>
            
        </ul>
        
        <div class="tab-content col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <div class="tab-pane active" id="paso1">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab1', [
                        "arr_unidad" => $arr_unidad,
                        "arr_carrera" => $arr_carrera,
                        "arr_modalidad" => $arr_modalidad,
                        "arr_periodo" => $arr_periodo,
                        "tipos_dni" => $tipos_dni,
                        "tipos_dni2" => $tipos_dni2,
                        'per_pri_nombre' => $respPerinteresado['per_pri_nombre'],
                        'per_seg_nombre' => $respPerinteresado['per_seg_nombre'],
                        'per_pri_apellido' => $respPerinteresado['per_pri_apellido'],
                        'per_seg_apellido' => $respPerinteresado['per_seg_apellido'],
                        'per_cedula' => $respPerinteresado['per_cedula'],
                        'per_genero' => $respPerinteresado['per_genero'],
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
                        'per_pasaporte' => $respPerinteresado['per_pasaporte'],
                        /*"arr_nacionalidad" => $arr_nacionalidad,
                        "arr_estado_civil" => $arr_estado_civil,
                        "arr_pais" => $arr_pais,
                        "arr_provincia" => $arr_provincia,*/
                        "arr_ciudad" => $arr_ciudad,
                        //"arr_malla" => $arr_malla,
                        "arr_metodos" => $arr_metodos,
                    ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso2">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab2', [
                        //'per_id' => $per_id,
                        "arr_convenio_empresa" => $arr_convenio_empresa,
                    ]);
                    ?>                  
                </form>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->
</div><!-- /.col -->