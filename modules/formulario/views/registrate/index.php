<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\modules\formulario\Module as formulario;
formulario::registerTranslations();
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2><span id="lbl_Personeria"><?= formulario::t("ficha", "Datasheet") ?></span></h2>
</div>
<br/>
  
<div>
    <form class="form-horizontal">
        <?=
        $this->render('registro', [
            "arr_provincia" => $arr_provincia,
            "arr_ciudad" => $arr_ciudad,            
            "tipos_dni" => $tipos_dni,    
            "arr_unidad" => $arr_unidad,
            "arr_carrera_prog" => $arr_carrera_prog,
            "arr_institucion" => $arr_institucion,
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