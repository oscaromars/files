<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\webservices;

use yii;
use yii\helpers\Url;

/**
 * Description of ConsumirWsdl
 *
 * @author root
 */
class WsEducativa {

    //Variables Locales
    private $usuario="";
    private $clave="";
    private $wdsl = "";

    function __construct() {
        $this->usuario = 'webservice';
        $this->clave = 'WxrrvTt8';
        $this->wdsl = "https://campusvirtual.uteg.edu.ec/soap/?wsdl=true";
    }

    private function webServiceCliente($metodo, $param = array()) {
        $options = array(
            'soap_version'=>SOAP_1_1,
            'trace' => 1,
            'exceptions' => true,
            'login' => $this->usuario,
            'password' => $this->clave
        );
        try {
            $cliente = new \SoapClient($this->wdsl, $options);
            $response = $cliente->__soapCall($metodo, $param);
            $arroout["status"] = "OK";
            $arroout["error"] = 0;
            $arroout["message"] = 'Respuesta Ok WebService: ' . $metodo;
            $arroout["data"] = $response;
            return $arroout;
        } catch (\SoapFault $e) {
            $arroout["status"] = "NO_OK";
            $arroout["error"] = $e->getCode();
            $arroout["message"] = $e->getMessage();
            $arroout["data"] = null;
            return $arroout;
        }
    }
    
    public function consultar_idioma() {
        $metodo = 'obtener_idiomas';
        return $this->webServiceCliente($metodo);
    }

    public function autenticar_usuario($usuario,$clave) {    
        $param = array(
            'id_usuario' => $usuario,
            'clave' => md5($clave)
        );
        $metodo = 'autenticar_usuario';
        return $this->webServiceCliente($metodo, array($param));
    }

}
