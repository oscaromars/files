<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2><span id="lbl_Personeria"><?= Yii::t("formulario", "Formulario de Registro") ?></span></h2>
</div>
<br/>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display:none">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_evento" class="col-sm-5 control-label"><?= Yii::t("formulario", "Event Name") ?></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_evento", 1, $arr_evento, ["class" => "form-control", "id" => "cmb_evento", ""]) ?>
                </div>
            </div>
        </div>        
    </div>

</form>    
<div>
    <form class="form-horizontal">
        <?=
        $this->render('registro', [
            "arr_provincia" => $arr_provincia,
            "arr_ciudad" => $arr_ciudad,
            "arr_genero" => $arr_genero,
            "arr_nivel" => $arr_nivel,
            "arr_evento" => $arr_evento,
            "tipos_dni" => $tipos_dni,
            "arr_ocupaciones" => $arr_ocupaciones,
        ]);
        ?>
    </form>
</div>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('intereses', [
            "arr_interes" => $arr_interes,
        ]);
        ?>
    </form>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">          
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"></div>
    <div class="col-sm-2">
        <a id="registrar" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?> </a>
    </div>        
</div> 