<?php

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
    "email_cliente" => $email_cliente,
    "total" => $total,
    "isCheckout" => (is_null($requestID)?false:true),
    "requestID" => (is_null($requestID)?"":$requestID),
    "type" => "form",
]);