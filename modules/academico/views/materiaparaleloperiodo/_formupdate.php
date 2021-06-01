<?php

use app\modules\academico\Module as academico;
use yii\helpers\Html;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$data = array();
$j = 0;
for ($i = $model->mpp_num_paralelo+1; $i <= 20; $i++) {
    array_push($data, $i);
    $j++;
}
?>
<?= Html::hiddenInput('mpp_num_paralelo',    $model->mpp_num_paralelo, ['id' => 'mpp_num_paralelo']); ?>
<?= Html::hiddenInput('mod_id',    $model->mod_id, ['id' => 'mod_id']); ?>
<?= Html::hiddenInput('paca_id',   $model->paca_id, ['id' => 'paca_id']); ?>
<?= Html::hiddenInput('asi_id',    $model->asi_id, ['id' => 'asi_id']); ?>
<form class="form-horizontal">
    <div class="row"> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
            <div class="form-group">            
                <label for="cmb_asignatura" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Asignatura") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <h5>  <?= $model->asig->asi_nombre ?>  </h5>
                </div>   

            </div>
        </div> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
            <div class="form-group">            
                <label for="cmb_asignatura" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Modalidad") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <h5>  <?= $model->mod->mod_nombre ?>  </h5>
                </div>   

            </div>
        </div> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
            <div class="form-group">            
                <label for="cmb_num_paralelo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "# Paralelo") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_num_paralelo", 0, $data, ["class" => "form-control", "id" => "cmb_num_paralelo","width" => "10px"]) ?>
                </div>   

            </div>
        </div> 
    </div> 
    
     
</form>