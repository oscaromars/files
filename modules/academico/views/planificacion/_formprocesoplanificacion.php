<?php

use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\academico\Module as academico;
use app\modules\admision\Module;
?> 

<div class="row">

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h4><span id="lbl_planear"><?= academico::t("Academico", "Headboard Student Planning") ?></span></h4>
</div><br><br><br>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_unidadest2" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("crm", "Academic Unit"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_unidadest2", 0, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidadest2", "Disabled" => "true"]) ?>
                </div> 
                             
            </div>        
        </div>  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_carreraest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("crm", "Carrera"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_carreraest", 0, $arr_carrera, ["class" => "form-control", "id" => "cmb_carreraest2"]) ?>
                </div> 
                <label for="lbl_modalidadest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_modalidadest", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidadest2"]) ?>
                </div>  
            </div>        
        </div>   
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group"> 
            <label for="lbl_mallaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Academic Mesh"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_malladoest", 0, $arr_malla, ["class" => "form-control", "id" => "cmb_malladoest2"]) ?>
                </div>           
                <label for="lbl_periodoest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_periodoest", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodoest2"]) ?>
                </div>                                
            </div>        
        </div> 
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
             <div class="form-group">
                  <label for="txt_buscarest" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Student") ?> <span class="text-danger">*</span> </label>
                 <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                <!--    <input type="text" class="form-control" value="" id="txt_buscarest" placeholder="<?= Yii::t("formulario", "Search by Names") ?>"> -->
                <?php //echo '<label class="control-label">Tag Single</label>';
                 echo Select2::widget([
                'name' => 'cmb_buscarest',
                'id' => 'cmb_buscarest',
                'value' => '0', // initial value
                'data' => $arr_alumno,
                'options' => ['placeholder' => 'Seleccionar'],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 50
                ],
            ]); ?>
                </div>                 
            </div>                 
        </div>      
    </div> 
 

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="col-sm-8"></div>
                <div class="col-sm-2">                
                    <a id="btn_grabarPlanestudiante" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Grabar") ?></a>
                </div>
                </div></br>
            </div>
        </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_buscarDataplanifica" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-xs-8 col-md-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarDataPlanifica" placeholder="<?= Yii::t("formulario", "Search by Names") . ', ' . Yii::t("formulario", "DNI 1") . ' ' . Yii::t("formulario", "Student") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            
            <label for="lbl_modalidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidades", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidades"]) ?>
            </div>  
        </div>        
    </div>  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_carrera" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Career"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_carreras", 0, $arr_carrera, ["class" => "form-control", "id" => "cmb_carreras"]) ?>
            </div>  
            <label for="lbl_periodo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodo", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo"]) ?>
            </div>                  
        </div>        
    </div>        
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_buscarPlanestudiante" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div></br>
