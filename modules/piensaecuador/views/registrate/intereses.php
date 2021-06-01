<?php
/*
 * Authors:
 * Grace Viteri <analistadesarrollo01@uteg.edu.ec> 
 * Kleber Loayza <analistadesarrollo03@uteg.edu.ec> /
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Interests") ?></span></h3>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">    
    <?php
        for ($i = 0; $i < count($arr_interes); $i++) {
    ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="checkbox" id="<?= "chk_" . $arr_interes[$i]['id'] ?>" data-type="alfa" data-keydown="true" placeholder="<?= $arr_interes[$i]['value'] ?>"><?php echo "   ". $arr_interes[$i]['value'] ?>
                </div>
            </div>                
    <?php
        }
    ?>
    </div>  
</form>