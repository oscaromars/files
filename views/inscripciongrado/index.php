<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\academico\Module as academico;

academico::registerTranslations();

/*//print_r($model);
session_start();
$_SESSION['persona_ingresa'] = base64_decode($_GET['ids']);*/

?>
<?= Html::hiddenInput('txth_ftem_id', 0, ['id' => 'txth_ftem_id']); ?>
<?= Html::hiddenInput('txth_errorFile', Yii::t("formulario", "The file extension is not valid or exceeds the maximum size in MB recommending him try again"), ['id' => 'txth_errorFile']); ?>

<div class="col-md-12  col-xs-12 col-sm-12 col-lg-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs tabsdatos col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <li class="active"><a href="#paso1" data-href="#paso1" data-toggle="tab" aria-expanded="true"><img class="" src="<?= Url::home() ?>img/users/n1.png" alt="User Image">  <?= Yii::t("interesado", "Personal Information") ?></a></li>
            <li class="disabled"><a data-href="#paso2" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n2.png" alt="User Image">  <?= Yii::t("interesado", "Subir Documentación") ?></a></li>
            
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
                        'per_pri_nombre' => $model['per_pri_nombre'],
                        'per_seg_nombre' => $model['per_seg_nombre'],
                        'per_pri_apellido' => $model['per_pri_apellido'],
                        'per_seg_apellido' => $model['per_seg_apellido'],
                        'per_cedula' => $model['per_cedula'],
                        'per_genero' => $model['per_genero'],
                        'per_fecha_nacimiento' => $model['per_fecha_nacimiento'],
                        'etn_id' => $model['etn_id'],
                        'pai_id_nacimiento' => $model['pai_id_nacimiento'],
                        'pro_id_nacimiento' => $model['pro_id_nacimiento'],
                        'can_id_nacimiento' => $model['can_id_nacimiento'],
                        'pai_id_domicilio' => $model['pai_id_domicilio'],
                        'pro_id_domicilio' => $model['pro_id_domicilio'],
                        'can_id_domicilio' => $model['can_id_domicilio'],
                        'eciv_descripcion' => substr(strtoupper($model['eciv_descripcion']), 0, 3),
                        'per_correo' => $model['per_correo'],
                        'per_celular' => $model['per_celular'],
                        'tsan_id' => $model['tsan_id'],
                        'tipparent_dis' => $arr_tipparent_dis,
                        'per_nacionalidad' => $model['per_nacionalidad'],
                        'area' => $area['name'],
                        'per_pasaporte' => $model['per_pasaporte'],
                        "arr_nacionalidad" => $arr_nacionalidad,
                        'eciv_id' => $model['eciv_id'],
                        "arr_estado_civil" => $arr_estado_civil,
                        "arr_pais" => $arr_pais,
                        "arr_provincia" => $arr_provincia,
                        "arr_ciudad" => $arr_ciudad,
                        //"arr_malla" => $arr_malla,
                        'arr_tipparentesco' => $arr_tipparentesco,
                        "arr_metodos" => $arr_metodos,
                    ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso2">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab2', [
                        'per_id' => $per_id,
                        "arr_convenio_empresa" => $arr_convenio_empresa,
                    ]);
                    ?>                  
                </form>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->
</div><!-- /.col -->