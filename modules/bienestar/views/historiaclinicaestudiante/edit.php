<?php


use yii\helpers\Html;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<form class="form-horizontal">
    <div class="row">  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">            
                <label for="cmb_profesor" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Teacher") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_profesor", $pro_id,  $arr_profesor , ["class" => "form-control", "id" => "cmb_profesor",]) ?>
                </div>
                <label for="cmb_unidad_dis" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_unidad_dis", $uaca_id, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad_dis",]) ?>
                </div> 
            </div>
        </div>    
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">    
                <label for="cmb_modalidad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_modalidad", $mod_id, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad",]) ?>
                </div>         
                <label for="cmb_periodo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_periodo", $paca_id,  $arr_periodo , ["class" => "form-control", "id" => "cmb_periodo",]) ?>
                </div>       
            </div>                                            
        </div>    
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">                        
                <label for="cmb_jornada" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Working day") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_jornada", $jornada, $arr_jornada, ["class" => "form-control", "id" => "cmb_jornada",]) ?>
                </div>   
                <label for="cmb_materia" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Subject") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_materia", $asi_id,  $arr_materias, ["class" => "form-control", "id" => "cmb_materia",]) ?>
                </div> 
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">                        
                <label for="cmb_horario" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Schedule") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_horario", $horario, $arr_horario, ["class" => "form-control", "id" => "cmb_horario",]) ?>
                </div>   
            </div>
        </div>
    </div>
</form>

<?= Html::hiddenInput('txth_ids', $daca_id, ['id' => 'txth_ids']); ?>