<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\modules\investigacion\Module as investigacion;

investigacion::registerTranslations();
?>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_planear"> <i class='fas fa-user-tie'></i> <?= investigacion::t("registroproyecto", "Project Form Registration") ?></span></h3>
    </div><br><br>

    <form class="form-horizontal" enctype="multipart/form-data" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4><span id="lbl_planear"><i class="far fa-address-book"></i> <?= investigacion::t("registroproyecto", "Information - Project Director") ?></span></h4>
        </div><br><br>
    
        <!-- Informacion del Director de Proyecto -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nombre" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Name") ?></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" class="form-control PBvalidation" value="<?php echo $model_dp['nombreDirector'] ?>" disabled="disabled" id="txt_nombre" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Name") ?>">
                        </div>
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_apellido" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Surname") ?></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" class="form-control PBvalidation" value="<?php echo $model_dp['apellidoDirector'] ?>" disabled="disabled" id="txt_apellido" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Surname") ?>">
                            
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_cedula" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Identification card") ?></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" class="form-control PBvalidation" value="<?php echo $model_dp['cedula'] ?>" disabled="disabled" id="txt_cedula" data-type="cedula"  placeholder="<?= investigacion::t("registroproyecto", "Identification card") ?>">
                        </div>
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nacionalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Nationality") ?></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" maxlength="50" class="form-control PBvalidation" value="<?php echo $model_dp['nacionalidad'] ?>" disabled="disabled" id="txt_nacionalidad" data-required="false" data-type="all" placeholder="<?= investigacion::t("registroproyecto", "Nationality") ?>">
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_correo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Mail") ?></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" class="form-control PBvalidation" value="<?php echo $model_dp['correo'] ?>" disabled="disabled" id="txt_correo" data-type="email" placeholder="<?= investigacion::t("registroproyecto", "Mail") ?>">
                        </div>
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_cell" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "CellPhone") ?></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" class="form-control PBvalidation" value="<?php echo $model_dp['cell'] ?>" disabled="disabled" id="txt_cell" data-type="email" placeholder="<?= investigacion::t("registroproyecto", "CellPhone") ?>">
                        </div>
                    </div>
                </div> 
            </div>
    <br><br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4><span id="lbl_planear"><i class="fas fa-university"></i>  <?= investigacion::t("registroproyecto", "Information - Director of Research") ?></span></h4>
    </div><br><br>
    <!-- Informacion de la Entidad Educativa -->
    
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_entidad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= investigacion::t("registroproyecto", "Educational Entity") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation " value="<?php echo $model_di['entidad'] ?>" disabled="disabled" id="txt_entidad" data-type="all"  placeholder="<?= investigacion::t("registroproyecto", "Educational Entity") ?>">
                </div>
            </div>
        </div> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_departamento" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= investigacion::t("registroproyecto", "Execution area") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation " value="<?php echo $model_di['departamento'] ?>" disabled="disabled" id="txt_departamento" data-type="all"  placeholder="<?= investigacion::t("registroproyecto", "Execution area") ?>">
                </div>
            </div>
        </div> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_manager" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Area Manager") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" value="<?php echo $model_di['manager'] ?>" disabled="disabled" id="txt_manager" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Area Manager") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_correo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Mail") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" value="<?php echo $model_di['correo'] ?>" disabled="disabled" id="txt_correo" data-type="email" placeholder="<?= investigacion::t("registroproyecto", "Mail") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_direccion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Address") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" value="<?php echo $model_di['direccion'] ?>" disabled="disabled" id="txt_direccion" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Address") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cell" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "CellPhone") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" value="<?php echo $model_di['telf'] ?>" disabled="disabled" id="txt_cell" data-type="number" placeholder="<?= investigacion::t("registroproyecto", "CellPhone") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_provincia" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Province") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" value="<?php echo $model_di['provincia'] ?>" disabled="disabled" id="txt_provincia" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Province") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_canton" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Canton") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" value="<?php echo $model_di['canton'] ?>" disabled="disabled" id="txt_canton" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Canton") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_zone" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Regional Zone") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" value="<?php echo $model_di['zona'] ?>" disabled="disabled" id="txt_zone" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Regional Zone") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_region" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Region") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" value="<?php echo $model_di['region'] ?>" disabled="disabled" id="txt_region" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Region") ?>">
                    </div>
                </div>
            </div> 
        </div>

    <br><br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4><span id="lbl_planear"><i class="fas fa-chalkboard-teacher"></i> <?= investigacion::t("registroproyecto", "Information - Project Registration") ?></span></h4>
    </div><br><br>
    <!-- Informacion del Proyecto -->
        <!-- Filtro de Proyectos -->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">            
                <label for="cmb_tipoproy" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= investigacion::t("proyecto", "Type de Project") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
                    <?= Html::dropDownList("cmb_tipoproy", 0,  $arr_proy , ["class" => "form-control", "id" => "cmb_tipoproy"]) ?>
                </div>
                   
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                     <label for="cmb_linea" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("lineainvestigacion", "Line of research") ?><span class="text-danger">*</span></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <?= Html::dropDownList("cmb_linea", 0,  $arr_linv , ["class" => "form-control", "id" => "cmb_linea"]) ?>
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                     <label for="cmb_mpro" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("macroproyecto", "Macroproject") ?><span class="text-danger">*</span></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <?= Html::dropDownList("cmb_mpro", 0,  $arr_mpro , ["class" => "form-control", "id" => "cmb_mpro"]) ?>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_titulo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= investigacion::t("registroproyecto", "Project Title") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation" id="txt_titulo" data-type="all"  placeholder="<?= investigacion::t("registroproyecto", "Project Title") ?>">
                </div>
            </div>
        </div> 

       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_resumen" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= investigacion::t("registroproyecto", "Summary") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <textarea type="text" class="form-control PBvalidation" id="txt_resumen" data-type="all"  placeholder="<?= investigacion::t("registroproyecto", "Summary") ?>">
                    </textarea>
                </div>
            </div>
        </div> 

         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-6">
            </div>
            <div class="col-xs-4">
                
               <a id="register_form_btn" href="javascript:" class="btn btn-success pull-right" onclick="registerForm()" style="margin: 0px 5px; display: block;"><?= investigacion::t("registroproyecto", "Siguiente") ?></a>

            </div>
            <div class="col-xs-2">

            </div>
        </div>

    </form>
</div>