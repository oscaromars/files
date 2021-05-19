<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
require(__DIR__ . '/../../components/vpos_plugin.php');

$Subtotal_0 = $subtotal*100;
$Subtotal_iva = '000';
$IVA = $Subtotal_iva * Yii::$app->params["VposIVA"];
$ICE = '000';
$Total = $Subtotal_0 + $Subtotal_iva + $IVA;
$vector = Yii::$app->params["Vposvector"];

//Llave Publica Crypto de Alignet. Nota olvidar ingresar los saltos de linea detallados con el valor \n
$llaveVPOSCryptoPub = Yii::$app->params["VposllaveVPOSCryptoPub"]; // llaves de cifrado publica de Alignet
$llaveComercioFirmaPriv = Yii::$app->params["VposllaveComercioFirmaPriv"]; // llave de firma privada de su comercio

//Envío de Parametros a V-POS
$array_send['acquirerId'] = Yii::$app->params["VposacquirerId_p"]; // para pruebas ya despues se quita a la variable el _p
$array_send['commerceId'] = Yii::$app->params["VposcommerceId_p"]; // para pruebas ya despues se quita a la variable el _p
$array_send['purchaseOperationNumber']=Yii::$app->params["VpospurchaseOperationNumber_p"]; // pruebas
//$array_send['purchaseOperationNumber'] = $transaccion; para producción
//Monto incluido con impuestos
//$array_send['purchaseAmount'] = $Total; // para producción
$array_send['purchaseAmount'] = Yii::$app->params["VpospurchaseAmount_p"]; //para pruebas
$array_send['purchaseCurrencyCode'] = Yii::$app->params["VpospurchaseCurrencyCode"];
$array_send['commerceMallId'] = Yii::$app->params["VposcommerceMallId_p"];
$array_send['language'] = Yii::$app->params["Vposlanguage"];
$array_send['billingFirstName'] = $nombres;
$array_send['billingLastName'] = $apellidos;
$array_send['billingEMail'] = $email;
$array_send['billingAddress'] =  Yii::$app->params["VposbillingAddress"]; //pruebas
$array_send['billingAddress'] = $domicilio;
$array_send['billingZIP'] = Yii::$app->params["VposbillingZIP"];        //determinar si este valor es fijo o depende de cada cliente
$array_send['billingCity'] = Yii::$app->params["VposbillingCity"];            //determinar si este valor es fijo o depende de cada cliente
$array_send['billingState'] = Yii::$app->params["VposbillingState"];         //determinar si este valor es fijo o depende de cada cliente
$array_send['billingCountry'] = Yii::$app->params["VposbillingCountry"];            //determinar si este valor es fijo o depende de cada cliente
$array_send['billingPhone'] = Yii::$app->params["VposbillingPhone"];       //determinar si este valor es fijo o depende de cada cliente
$array_send['shippingAddress'] = Yii::$app->params["VposshippingAddress"];  //determinar si este valor es fijo o depende de cada cliente
$array_send['terminalCode'] = Yii::$app->params["VposterminalCode_p"]; // para pruebas ya despues se quita a la variable el _p

//Parametros Reservados Sobre Inclusión de Impuestos IVA
//Monto Neto sin incluir el valor IVA
$array_send['reserved1'] = Yii::$app->params["VposReserved1"];
//Impuesto IVA de la transacción
$array_send['reserved2'] = $Subtotal_iva; //calculo 
$array_send['reserved3'] = $IVA; //calculo

$array_send['reserved4'] = Yii::$app->params["VposReserved4"];
$array_send['reserved5'] = Yii::$app->params["VposReserved5"];

//Aquí debe enviar el valor 
$array_send['reserved9'] = Yii::$app->params["VposReserved9"];
$array_send['reserved10'] = Yii::$app->params["VposReserved10"];

//Aquí se debe enviar el monto que no se ve afectado por el impuesto IVA. Si el monto si aplica el impuesto IVA, enviar el valor 000. 
$array_send['reserved11'] = Yii::$app->params["VposReserved11"];

//Impuestos a los consumos especiales ICE. Si el monto no aplica al ICE, enviar el valor 000. 
//$array_send['reserved12'] = $ICE;

//Parametros de Solicitud de Autorización a Enviar
$array_get['XMLREQ'] = "";
$array_get['DIGITALSIGN'] = "";
$array_get['SESSIONKEY'] = "";

if (VPOSSend($array_send,$array_get,$llaveVPOSCryptoPub,$llaveComercioFirmaPriv,$vector)){
    \app\models\Utilities::putMessageLogFile('resultado:'.print_r($array_send,true));
}else{
    die('error...');
}

?>
<form class="form-horizontal" name="frmVPOS" method="POST" action="https://integracion.alignetsac.com/VPOS/MM/transactionStart20.do">  
    <div style="text-align: center;"><img src="<?= Url::base() . "/" . @web . "/img/logos/logo.png" ?>"></div>
    <div style="text-align: center;"><h3><span id="lbl_respuesta"><?= Yii::t("formulario", "Payment Form") ?></span></h3></div>
 
    <div class="col-md-12">
        <h4><span id="lbl_Personeria"><?= Yii::t("formulario", "Transaction Data") ?></span></h4>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="txt_solicitud" class="col-sm-2 control-label" id="lbl_solicitud"><?= Yii::t("formulario", "Request #") ?></label> 
            <div class="col-sm-8">
                <input type="text" class="form-control PBvalidation keyupmce" value ="<?= $solicitud ?>" id="txt_transaccion" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Transaction") ?>" disabled="true">        
            </div>
        </div>
    </div>   
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="txt_transaccion" class="col-sm-2 control-label"><?= Yii::t("formulario", "Transaction") ?></label>
            <div class="col-sm-8">
                <input type="text" class="form-control PBvalidation keyupmce" value ="<?= $array_send['purchaseOperationNumber'] ?>" id="txt_transaccion" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Transaction") ?>" disabled="true">
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <h4><span id="lbl_Personeria"><?= Yii::t("formulario", "Applicant Data") ?></span></h4>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="txt_nombres" class="col-sm-2 control-label"><?= Yii::t("formulario", "Names") ?></label>
            <div class="col-sm-8">
                <input type="text" class="form-control PBvalidation keyupmce" value ="<?= $nombres ?>" id="txt_nombres" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Names") ?>"disabled="true">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="txt_apellidos" class="col-sm-2 control-label"><?= Yii::t("formulario", "Last Names") ?></label>
            <div class="col-sm-8">
                <input type="text" class="form-control PBvalidation keyupmce" value ="<?= $apellidos ?>" id="txt_apellidos" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Names") ?>"disabled="true">
            </div>
        </div>
    </div>   
    
    <div class="col-md-12">
        <h4><span id="lbl_Personeria"><?= Yii::t("formulario", "Details of Payment") ?></span></h4>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="txt_curso" class="col-sm-2 control-label"><?= Yii::t("formulario", "Course") ?></label>
            <div class="col-sm-8">
                <input type="text" class="form-control PBvalidation keyupmce" value ="<?= $curso ?>" id="txt_curso" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Course") ?>"disabled="true">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="txt_precio" class="col-sm-2 control-label"><?= Yii::t("formulario", "Price") ?></label>
            <div class="col-sm-8">
                <input type="text" class="form-control PBvalidation keyupmce" value ="<?= $precio ?>" id="txt_precio" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Price") ?>"disabled="true">
            </div>
        </div>
    </div>
    <INPUT TYPE="hidden" NAME="IDACQUIRER" value="<?php echo $array_send['acquirerId']; ?>">
    <INPUT TYPE="hidden" NAME="IDCOMMERCE" value="<?php echo $array_send['commerceId']; ?>">
    <INPUT TYPE="hidden" NAME="XMLREQ" value="<?php echo $array_get['XMLREQ']; ?>">
    <INPUT TYPE="hidden" NAME="DIGITALSIGN" value="<?php echo $array_get['DIGITALSIGN']; ?>">
    <INPUT TYPE="hidden" NAME="SESSIONKEY" value="<?php echo $array_get['SESSIONKEY']; ?>">
    <div class="row">           
        <div class="col-md-2">            
            <input class="btn btn-primary btn-block" type="submit" name="envio" id="envio" value="Enviar" />            
        </div>
    </div>
</form>

