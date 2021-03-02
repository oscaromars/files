<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;    
use yii\helpers\Url;
?>
 <div style="text-align: center;"><img src="<?= Url::base() . "/" . @web . "/img/logos/logo.png" ?>"></div>
 <div style="text-align: center;"><h3><span id="lbl_respuesta"><?= Yii::t("formulario", "UTEG - Online Purchase Result") ?></span></h3></div><br/>
<h4>
<?php $mensajes = "Estimado(a):" . "<br/><br/>";  
 if ($bandera==1) {
    $mensajes .= "Usted se ha registrado exitosamente al " . $metodo . " a partir del ". $fecha . " fecha en que inicia " . $leyenda . " usted podrá acceder al módulo campus virtual.";
?> 
<?php $mensajes .= "<br/><br/>"?> 
<a href="<?= Html::encode(\Yii::$app->params['web']) ?>" target="_blank"></a>
<!--<a href="http://www.uteg.edu.ec" target="_blank"><?= $link ?></a>-->
<?php $mensajes .= "<br/>"."Gracias"; echo $mensajes; } else {
   echo  $mensajes . $mensaje . "<br/><br/>". "Gracias";
}
?>
</h4>
