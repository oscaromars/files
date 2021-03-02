<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* Web Server */
$WS_HOST = "192.168.1.100";
$WS_PORT = "80";
$WS_USER = "test";
$WS_PASS = "test";
$WS_URI = "asgard/api/request/fe_edoc/Edoc/sendEdoc/json";

$service = "uteg-fe";
$logFile = "logs/$service.log";
$limit = 10;
$timeWait = 1; //1 Segundo

function putMessageLogFile($message) {
    GLOBAL $logFile;
    if (is_array($message))
        $message = json_encode($message);
    $message = date("Y-m-d H:i:s") . " " . $message . "\n";
    file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
}

function limpiarUTF8($cadena) {
    $String = trim($cadena);
    $String = str_replace("\u00e1", "á", $String);
    $String = str_replace("\u00c1", "Á", $String);
    $String = str_replace("\u00e9", "é", $String);
    $String = str_replace("\u00c9", "É", $String);
    $String = str_replace("\u00ed", "í", $String);
    $String = str_replace("\u00cd", "Í", $String);
    $String = str_replace("\u00f3", "ó", $String);
    $String = str_replace("\u00d3", "Ó", $String);
    $String = str_replace("\u00fa", "ú", $String);
    $String = str_replace("\u00da", "Ú", $String);
    $String = str_replace("\u00f1", "ñ", $String);
    $String = str_replace("\u00d1", "Ñ", $String);

    return $String;
}
