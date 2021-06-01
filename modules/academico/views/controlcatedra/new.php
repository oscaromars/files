<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\academico\Module as academico;

//print_r($arr_datahorario);
?>
<?= Html::hiddenInput('txth_hape_id', $_GET['hape_id'], ['id' => 'txth_hape_id']); ?>
<form class="form-horizontal" enctype="multipart/form-data" > 
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">   
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <h4><span id="lbl_general"><?= academico::t("Academico", "Cathedra control") ?></span></h4> 
                </div>
            </div>            
        </div> 
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                <div class="form-group">
                    <label for="txt_asignatura" class="col-sm-2 control-label" id="lbl_cupo"><?= Yii::t("formulario", "Subject") ?></label>
                    <div class="col-md-10 col-sm-10 col-xs-10 col-lg-10">
                        <input type="text" class="form-control PBvalidation" value="<?= $arr_datahorario[0]['materia'] ?>" id="txt_asignatura" data-keydown="true" disabled = "true" placeholder="<?= Yii::t("formulario", "Subject") ?>">
                    </div>
                </div>
            </div>           
        </div> 
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_docente" class="col-sm-4 control-label" id="lbl_unidad"><?= academico::t("Academico", "Teacher") ?></label>
                    <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
                        <input type="text" class="form-control PBvalidation" value="<?= $arr_datahorario[0]['docente'] ?>" id="txt_docente" data-keydown="true" disabled = "true" placeholder="<?= academico::t("Academico", "Teacher") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_unidad" class="col-sm-4 control-label" id="lbl_unidad"><?= academico::t("Academico", "Academic unit") ?></label>
                    <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
                        <?= Html::dropDownList("cmb_unidad", $arr_datahorario[0]['unidad'], $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>  

        </div> 
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_modalidad" class="col-sm-4 control-label" id="lbl_modalidad"><?= academico::t("Academico", "Modality") ?></label>
                    <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
                        <?= Html::dropDownList("cmb_modalidad", $arr_datahorario[0]['modalidad'], $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>  
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_carrera" class="col-sm-4 control-label" id="lbl_programa"><?= academico::t("Academico", "Career/Program") ?></label>
                    <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
                        <?= Html::dropDownList("cmb_carrera", 0, $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera"]) ?>
                    </div>
                </div>
            </div>  

        </div> 
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_periodo" class="col-sm-4 control-label" id="lbl_cupo"><?= academico::t("Academico", "Period") ?></label>
                    <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
                        <input type="text" class="form-control PBvalidation" value="<?= $arr_datahorario[0]['periodo'] ?>" id="txt_periodo" data-keydown="true" disabled = "true" placeholder="<?= academico::t("Academico", "Period") ?>">
                    </div>
                </div>
            </div>  
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_fecha" class="col-sm-4 control-label" id="lbl_cupo"><?= academico::t("Academico", "Session Date") ?></label>
                    <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
                        <input type="text" class="form-control PBvalidation" value="<?= date('Y-m-d') ?>" id="txt_fecha" data-keydown="true" disabled = "true" placeholder="<?= academico::t("Academico", "Session Date") ?>">
                    </div>
                </div>
            </div>  
        </div> 
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_entrada" class="col-sm-4 control-label" id="lbl_cupo"><?= academico::t("Academico", "Hour enter") ?></label>
                    <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
                        <input type="text" class="form-control PBvalidation" value="<?= $arr_datahorario[0]['entrada'] ?>" id="txt_entrada" data-keydown="true" disabled = "true" placeholder="<?= academico::t("Academico", "Period") ?>">
                    </div>
                </div>
            </div>  
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_salida" class="col-sm-4 control-label" id="lbl_cupo"><?= academico::t("Academico", "Hour exit") ?></label>
                    <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
                        <input type="text" class="form-control PBvalidation" value="<?= $arr_datahorario[0]['salida'] ?>" id="txt_salida" data-keydown="true" disabled = "true" placeholder="<?= academico::t("Academico", "Session Date") ?>">
                    </div>
                </div>
            </div>  
        </div> 
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
                <div class="form-group">
                    <label for="txt_titulo" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_titulo"><?= academico::t("Academico", "Unit Title") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <textarea  class="form-control PBvalidation keyupmce" rows="3" id="txt_titulo"></textarea>                  
                    </div>
                </div>
            </div>     
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
                <div class="form-group">
                    <label for="txt_tema" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_tema"><?= academico::t("Academico", "Topic") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <textarea  class="form-control PBvalidation keyupmce" rows="3" id="txt_tema"></textarea>                  
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
                <div class="form-group">
                    <label for="txt_trabajo" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_trabajo"><?= academico::t("Academico", "Freelance practical work") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <textarea  class="form-control PBvalidation keyupmce" rows="3" id="txt_trabajo"></textarea>                  
                    </div>
                </div>
            </div>     
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
                <div class="form-group">
                    <label for="txt_logro" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_logro"><?= academico::t("Academico", "Learning Achievements") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <textarea  class="form-control PBvalidation keyupmce" rows="3" id="txt_logro"></textarea>                  
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
                <div class="form-group">
                    <label for="txt_actividad" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_activadad"><?= academico::t("Academico", "Activities / Form of Evaluation") ?> </label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <?php
                        for ($i = 0; $i < count($arr_actividad); $i++) {
                            ?>
                            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                                <div class="form-group">
                                    <input type="checkbox" id="<?= "chka_" . $arr_actividad[$i]['id'] ?>" data-type="alfa" data-keydown="true" placeholder="<?= $arr_actividad[$i]['value'] ?>"><?php echo "   " . $arr_actividad[$i]['value'] ?>
                                </div>
                            </div>                
                            <?php
                        }
                        ?>            
                    </div>
                </div>
            </div>     
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
                <div class="form-group">
                    <label for="txt_valor" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_valor"><?= academico::t("Academico", "Securities to develop") ?> </label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <?php
                        for ($j = 0; $j < count($arr_valor); $j++) {
                            ?>
                            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                                <div class="form-group">
                                    <input type="checkbox" id="<?= "chkv_" . $arr_valor[$j]['id'] ?>" data-type="alfa" data-keydown="true" placeholder="<?= $arr_valor[$j]['value'] ?>"><?php echo "   " . $arr_valor[$j]['value'] ?>
                                </div>
                            </div>                
                            <?php
                        }
                        ?>     
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
                <div class="form-group">
                    <label for="txt_otro" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_otro"><?= academico::t("Academico", "Other") ?></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <textarea  class="form-control keyupmce" rows="3" id="txt_otro"></textarea>                  
                    </div>
                </div>
            </div>    
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
                <div class="form-group">
                    <label for="txt_otrovalor" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_otrovalor"><?= academico::t("Academico", "Other") ?></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <textarea  class="form-control keyupmce" rows="3" id="txt_otrovalor"></textarea>                  
                    </div>
                </div>
            </div>            
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>           
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">            
                <div class="form-group">
                    <label for="txt_observacion" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_observacion"><?= Yii::t("formulario", "Observation") ?></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <textarea  class="form-control keyupmce" rows="3" id="txt_observacion"></textarea>                  
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="col-sm-8"></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_control" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?></a>
            </div>
        </div>
    </div>  
</form>
