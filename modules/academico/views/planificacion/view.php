<?php

use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;

//print_r($arr_malla[0]['cod_asignatura']);
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_pla_id', $_GET['pla_id'], ['id' => 'txth_pla_id']); ?>
<?= Html::hiddenInput('txth_per_id', $_GET['per_id'], ['id' => 'txth_per_id']); ?>
<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h4><span id="lbl_planear"><? academico::t("Academico", "See Student Planning") ?></span></h4>
</div><br><br><br>-->
<form class="form-horizontal">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_unidadest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("crm", "Academic Unit"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_unidadest", 1, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidadest", "Disabled" => "disabled"]) ?>
                </div> 
                <!-- <label for="lbl_jornadaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Working day") ?> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_jornadaest", $valorjornada, $arr_jornada, ["class" => "form-control", "id" => "cmb_jornadaest", "disabled" => "true"]) ?>
                </div> -->                
            </div>        
        </div>  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_carreraest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("crm", "Carrera"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <!-- <? Html::dropDownList("cmb_carreraest",$arr_idcarrera["eaca_id"], $arr_carrera, ["class" => "form-control", "id" => "cmb_carreraest", "Disabled" => "disabled"]) ?>-->
                    <input type="text" class="form-control" value="<?= $arr_idcarrera["pes_carrera"] ?>" disabled ="true" id="txt_carrera" placeholder="<?= Yii::t("crm", "Carrera") ?>">
                </div> 
                <label for="lbl_modalidadest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_modalidadest", $arr_cabecera["mod_id"], $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidadest", "Disabled" => "disabled"]) ?>
                </div>                       
            </div>        
        </div> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">           
            <label for="lbl_mallaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Academic Mesh"); ?> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control" value="<?= $arr_idcarrera["malla"] ?>" id="txt_buscarest" disabled = "true" placeholder="<?= Yii::t("formulario", "Search by Names") ?>">    
                </div>   
                <label for="lbl_periodoest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_periodoest", $arr_cabecera["pla_periodo_academico"], $arr_periodo, ["class" => "form-control", "id" => "cmb_periodoest", "Disabled" => "disabled"]) ?>
                </div>                  
            </div>        
        </div> 
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="txt_buscarest" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Student") ?> </label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="<?= $arr_idcarrera["pes_nombres"] ?>" id="txt_buscarest" disabled = "true" placeholder="<?= Yii::t("formulario", "Search by Names") ?>">
                </div>
            </div>
        </div> 
    </div>
</form>
<div>      
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4><span id="lbl_evaluar"><?= academico::t("Academico", "Student Planning Detail") ?></span></h4>
    </div><br><br>
    <?=
    PbGridView::widget([
        'id' => 'PbPlanificaestudianteview',
        'dataProvider' => $model_detalle,
        'pajax' => true,
        'summary' => false,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'asignatura',
                'header' => academico::t("Academico", "Subject"),
                'value'=>function ($model_detalle) {
                    return $model_detalle['cod_asignatura']  . ' - ' . $model_detalle['asignatura'];
                },
            ],  
            [
                'attribute' => 'jornada',
                'header' => academico::t("Academico", "Working day"),
                'value' => 'jor_materia',
            ],  
            [
                'attribute' => 'bloque',
                'header' => Yii::t("formulario", "Block"),
                'value' => 'Bloque 1',
            ],
            [
                'attribute' => 'modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'modalidad',
            ],
            [
                'attribute' => 'hora',
                'header' => academico::t("Academico", "Hour"),
                'value' => 'Hora 1',
            ],
        ],
    ])
    ?>
</div>
