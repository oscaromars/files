  <?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
?>
<?php if (Yii::$app->session->hasFlash('is_transfer')): ?>
    <div class="alert alert-success alert-dismissable" >
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
         <h4><i class="icon fa fa-check"></i>Se ha transferido exitosamente las asignaturas</h4>
         <?= Yii::$app->session->getFlash('is_transfer') ?>
    </div>
<?php endif; ?> 
<?php if (Yii::$app->session->hasFlash('msg')): ?>
    <div class="alert alert-warning alert-dismissable" >
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
         <h4><i class="icon fa fa-exclamation"></i>Proceso no terminado</h4>
         <?= Yii::$app->session->getFlash('msg') ?>
    </div>
<?php endif; ?> 
<?php if (Yii::$app->session->hasFlash('msgne')): ?>
    <div class="alert alert-warning alert-dismissable" >
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
         <h4><i class="icon fa fa-exclamation"></i>No hay alumnos nuevos</h4>
         <?= Yii::$app->session->getFlash('msgne') ?>
    </div>
<?php endif; ?> 
<?php if (Yii::$app->session->hasFlash('msgok')): ?>
    <div class="alert alert-success alert-dismissable" >
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
         <h4><i class="icon fa fa-check"></i>Exito</h4>
         <?= Yii::$app->session->getFlash('msgok') ?>
    </div>
<?php endif; ?> 

<form class="form-horizontal">    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="txt_planificaciones" class="col-sm-3 control-label">Planificaciones</label>         
            </div>
            <div class="form-group">
                <label for="cmb_per_academico" class="col-sm-3 control-label">Periodo Academico</label>
                <div class="col-sm-9">
                    <?= Html::dropDownList("cmb_per_academico", $pla_periodo_academico, $arr_pla, ["class" => "form-control", "id" => "cmb_per_academico"]) ?>
                </div>
            </div>
            <div class="form-group">
                <label for="cmb_modalidad" class="col-sm-3 control-label">Modalidad</label>
                <div class="col-sm-9">
                    <?= Html::dropDownList("cmb_modalidad", $mod_id, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                </div>
            </div>
        </div>
    </div>
</form>
<br />
<?=
    $this->render('index-grid', ['model' => $model,]);
?>