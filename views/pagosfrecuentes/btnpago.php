<?php
use yii\helpers\Url;
use app\widgets\PbVPOS\PbVPOS;
echo PbVPOS::widget([
    "id" => "VPOS",
    "referenceID" => $referenceID,
    "ordenPago" => $ordenPago,
    "tipo_orden" => $tipo_orden,
    "descripcionItem" => $descripcionItem,
    "titleBox" => $titleBox,
    "nombre_cliente" => $nombre_cliente,
    "apellido_cliente" => $apellido_cliente,
    "cedula_cliente" => $cedula_cliente,
    "tipo_documento" => $tipo_documento,
    "email_cliente" => $email_cliente,
    "total" => $total,
    "isCheckout" => (is_null($requestID)?false:true),
    "requestID" => (is_null($requestID)?"":$requestID),
    "type" => "form",
    "termsConditions" => Url::to(['pagosfrecuentes/terminos']),
    "questionsLink" => Url::to(['pagosfrecuentes/preguntas']),
]);
?>