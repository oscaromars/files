<?php

use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
use kartik\select2\Select2;
academico::registerTranslations();
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h4><span id="lbl_planear"><?= academico::t("Academico", "Headboard Student Planning") ?></span></h4>
</div><br><br><br>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_unidadest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("crm", "Academic Unit"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_unidadest", 0, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidadest", "Disabled" => "true"]) ?>
                </div> 
                <!-- <label for="lbl_jornadaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Working day") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_jornadaest", 0, $arr_jornada, ["class" => "form-control", "id" => "cmb_jornadaest"]) ?>
                </div>  -->              
            </div>        
        </div>  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_carreraest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("crm", "Carrera"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_carreraest", 0, $arr_carrera, ["class" => "form-control", "id" => "cmb_carreraest"]) ?>
                </div> 
                <label for="lbl_modalidadest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_modalidadest", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidadest"]) ?>
                </div>  
            </div>        
        </div>   
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group"> 
            <label for="lbl_mallaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Academic Mesh"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_malladoest", 0, $arr_malla, ["class" => "form-control", "id" => "cmb_malladoest"]) ?>
                </div>           
                <label for="lbl_periodoest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_periodoest", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodoest"]) ?>
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
            <h4><span id="lbl_evaluar"><?= Yii::t("formulario", "Detalle Planificación Estudiante") ?></span></h4>
        </div><br><br><br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="lbl_asignaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Subject"); ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <?= Html::dropDownList("cmb_asignaest", 0, $arr_materia, ["class" => "form-control", "id" => "cmb_asignaest"]) ?>
                    </div>   
                    <label for="lbl_jornadaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Working day") ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <?= Html::dropDownList("cmb_jornadaest", 0, $arr_jornada, ["class" => "form-control", "id" => "cmb_jornadaest"]) ?>
                    </div>                   
                </div>        
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label for="lbl_bloqueest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Block"); ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_bloqueest", 0, $arr_bloque, ["class" => "form-control", "id" => "cmb_bloqueest"]) ?>
                    </div>                     
                    <label for="lbl_modalidadesth" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <?= Html::dropDownList("cmb_modalidadesth", 0, $arr_modalidadh, ["class" => "form-control", "id" => "cmb_modalidadesth"]) ?>
                    </div>                      
                </div>        
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">                   
                    <label for="lbl_horaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Hour"); ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <?= Html::dropDownList("cmb_horaest", 0, $arr_hora, ["class" => "form-control", "id" => "cmb_horaest"]) ?>
                    </div> 
                </div>        
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2 text-center">
                    <a id="btn_AgregarItemat" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Add") ?></a>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="box-body table-responsive no-padding">
                    <table  id="PbPlanificaestudiantnew" class="table table-hover">
                        <thead>
                            <tr>
                                <th style="display:none; border:none;"><?= Yii::t("formulario", "pla_id") ?></th>
                                <th style="display:none; border:none;"><?= Yii::t("formulario", "per_id") ?></th>
                                <th><?= academico::t("Academico", "Subject") ?></th>
                                <th><?= academico::t("Academico", "Working day") ?></th>
                                <th><?= Yii::t("formulario", "Block") ?></th>                            
                                <th><?= Yii::t("formulario", "Mode") ?></th>
                                <th><?= academico::t("Academico", "Hour") ?></th>  
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div></br></br></br></br>
        <!-- <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
            <div class="col-sm-10 col-md-10 col-xs-8 col-lg-10"></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_saveplanificacion" href="javascript:" class="btn btn-primary btn-block"> <? Yii::t("formulario", "Save") ?></a>
            </div>        
        </div> -->
    </div>
    </form>     