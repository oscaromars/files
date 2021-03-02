<?php

use PHPUnit\Framework\Exception;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cls_Global
 *
 * @author root
 */
//include('cls_Base.php');//para HTTP
class cls_Global {
    //put your code here
    public static $emp_id='1';//Empresa
    public static $est_id='1';//Establecimiento
    public static $pemi_id='1';//Punto Emision
    public static $ambt_id='1';//Ambiente de Pruebas por Defecto =1 =>2 Produccion (cambiar en caso de Pruebas)
    //public static $IdsUsu='1';//Valor por defecto(Alimenta al Autorizar el Documento)
    var $consumidorfinal='9999999999';
    var $dateStartFact='2020-08-01';
    var $datebydefault='d-m-Y';
    public static $dateXML = "d/m/Y";
    public $decimalPDF=2;
    public $SepdecimalPDF='.';
    var $limitEnv=3;
    var $limitEnvMail=1;
    public static $limitEnvAUT=2;
    var $IVAdefault=12;//Valor de Iva por Defecto en Textos
    var $Author="UTEG";
    var $rutaPDF="/home/EDOC/DOCPDF/";
    var $rutaXML="/home/EDOC/AUTORIZADO/";
    var $rutaLink='http://edoc.uteg.com';//Ruta donde se consultan los documentos
    var $tipoFacLocal='F4';
    var $tipoGuiLocal='GR';
    var $tipoRetLocal='RT';
    var $tipoNcLocal='NC';
    var $tipoNdLocal='ND';
    //FACTURA ELECTRONICA
    
    public static $seaDocXml = '/home/EDOC/GENERADO/';
    public static $seaDocFact = '/home/EDOC/FIRMADO/FACTURAS/';
    public static $seaDocRete = '/home/EDOC/FIRMADO/RETENCIONES/';
    public static $seaDocNc = '/home/EDOC/FIRMADO/NC/';
    public static $seaDocNd = '/home/EDOC/FIRMADO/ND/';
    public static $seaDocGuia = '/home/EDOC/FIRMADO/GUIAS/';
    public static $seaDocAutFact = '/home/EDOC/AUTORIZADO/FACTURAS/';
    public static $seaDocAutRete = '/home/EDOC/AUTORIZADO/RETENCIONES/';
    public static $seaDocAutNc = '/home/EDOC/AUTORIZADO/NC/';
    public static $seaDocAutNd = '/home/EDOC/AUTORIZADO/ND/';
    public static $seaDocAutGuia = '/home/EDOC/AUTORIZADO/GUIAS/';


    //function __construct() {  }
    
    public function messageSystem($status,$error,$op,$message,$data) {
        $arroout["status"] = $status;
        $arroout["error"] = $error;
        $arroout["message"] = $message;
        $arroout["data"] = $data;
        return $arroout;
    }
    
    public function buscarCedRuc($cedRuc) {
        try {
            $obj_con = new cls_Base();
            $conCont = $obj_con->conexionIntermedio();
            $rawData = array();
            $cedRuc=trim($cedRuc);
            $sql = "SELECT A.per_id Ids,A.per_nombre RazonSocial,IFNULL(B.usu_correo,'') CorreoPer
                        FROM " . $obj_con->BdIntermedio . ".persona A
                                INNER JOIN " . $obj_con->BdIntermedio . ".usuario B
                                        ON A.per_id=B.per_id AND B.usu_est_log=1
                WHERE A.per_ced_ruc='$cedRuc' AND A.per_est_log=1 ";
            //echo $sql;
            //cls_Global::putMessageLogFile($sql);
            $sentencia = $conCont->query($sql);
            if ($sentencia->num_rows > 0) {
                //Retorna Solo 1 Registro Asociado
                $rawData=$this->messageSystem('OK',null,null,null, $sentencia->fetch_assoc());  
            }else{
                $rawData=$this->messageSystem('NO_OK',null,null,null,null);  
            }
            $conCont->close();
            return $rawData;
        } catch (Exception $e) {
            //echo $e;
            $conCont->close();
            return $this->messageSystem('NO_OK', $e->getMessage(), null, null, null);
        }
    }

    public function buscarCedRudDBMain($cedRuc){
        try {
            $obj_con = new cls_Base();
            $conCont = $obj_con->conexionAppWeb();
            $rawData = array();
            $cedRuc=trim($cedRuc);
            $sql = "SELECT A.per_id Ids, B.usu_user usuario
                        FROM " . $obj_con->BdAppweb . ".persona A
                                INNER JOIN " . $obj_con->BdAppweb . ".usuario B
                                        ON A.per_id=B.per_id
                WHERE (A.per_cedula='$cedRuc' OR A.per_ruc='$cedRuc' OR A.per_pasaporte='$cedRuc') AND A.per_estado_logico=1 AND A.per_estado=1 AND B.usu_estado_logico=1";
            //echo $sql;
            $sentencia = $conCont->query($sql);
            if ($sentencia->num_rows > 0) {
                //Retorna Solo 1 Registro Asociado
                $rawData=$this->messageSystem('OK',null,null,null, $sentencia->fetch_assoc());  
            }else{
                $rawData=$this->messageSystem('NO_OK',null,null,null,null);  
            }
            $conCont->close();
            return $rawData;
        } catch (Exception $e) {
            //echo $e;
            $conCont->close();
            return $this->messageSystem('NO_OK', $e->getMessage(), null, null, null);
        }
    }
    
    public function insertarUsuarioPersona($obj_con,$cabDoc,$DBTable,$i) {  
        //$obj_con = new cls_Base();
        $conUse = $obj_con->conexionIntermedio();
        try {
            $this->InsertarPersona($conUse,$cabDoc,$obj_con,$i);
            $IdPer = $conUse->insert_id;
            $keyUser=$this->InsertarUsuario($conUse, $cabDoc,$obj_con, $IdPer,$DBTable,$i);
            $conUse->commit();
            $conUse->close();
            return $this->messageSystem('OK', null, null, null, $keyUser);
        } catch (Exception $e) {
            $conUse->rollback();
            $conUse->close();
            //throw $e;
            return $this->messageSystem('NO_OK', $e->getMessage(), null, null, null);
        }   
    }

    private function InsertarPersona($con, $objEnt,$obj_con,$i) {
        $sql = "INSERT INTO " . $obj_con->BdIntermedio . ".persona
                (per_ced_ruc,per_nombre,per_genero,per_est_log,per_fec_cre)VALUES
                ('" . $objEnt[$i]['CedRuc'] . "','" . $objEnt[$i]['RazonSoc'] . "','M','1',CURRENT_TIMESTAMP()) ";
        $command = $con->prepare($sql);
        $command->execute();
    }

    // SOLO SE CREA USUARIO A PROVEEDORES YA QUE LOS ESTUDIANTES YA TIENEN UNA CUENTA DE USUARIO
    private function InsertarUserDBMain($objEnt,$i, $password) {
        $obj_con = new cls_Base();
        $con = $obj_con->conexionAppWeb();
        $attrDni = "per_pasaporte";
        $valDni  = $objEnt[$i]['CedRuc'];
        $attrName = "per_pri_nombre";
        $valName = $objEnt[$i]['RazonSoc'];
        $rucEmp = $objEnt[$i]['Ruc'];
        $grol_id = 36; // grol del grupo y rol de proveedor
        $usu_sha = $this->generateRandomString();
        $usu_pass = $this->generatePassword($password, $usu_sha);
        $correo = ($objEnt[$i]['CorreoPer']<>'')?$objEnt[$i]['CorreoPer']:'';//Consulta Tabla Clientes
        if(strlen($valDni)==13){
            $attrDni = "per_ruc";
        }else if(strlen($valDni)==10){
            $attrDni = "per_cedula";
        }
        try{
            // CREACION DE LA PERSONA
            $sql = "INSERT INTO " . $obj_con->BdAppweb . ".persona
                ($attrDni,$attrName,per_genero,per_estado, per_estado_logico, per_correo) VALUES
                ('" . $valDni . "','" . $valName . "','M','1','1', '".$correo."')";
            $command = $con->prepare($sql);
            $command->execute();
            $IdPer = $con->insert_id;

            // CREACION DEL USUARIO
            $sql = "INSERT INTO " . $obj_con->BdAppweb . ".usuario
                (per_id,usu_user,usu_sha,usu_password,usu_estado_logico,usu_estado) VALUES
                ($IdPer,'$correo','$usu_sha','$usu_pass','1','1') ";
            $command2 = $con->prepare($sql);
            $command2->execute();
            $IdUsu = $con->insert_id;

            // ASIGNACION DE PERSONA A EMPRESA
            $empresa = $this->getIdEmpresa($rucEmp);
            $sql = "INSERT INTO " . $obj_con->BdAppweb . ".empresa_persona
                (emp_id, per_id,eper_estado_logico,eper_estado) VALUES
                (".$empresa['data']['Id'].",$IdPer,'1','1') ";
            $command3 = $con->prepare($sql);
            $command3->execute();
            $IdEper = $con->insert_id;

            // ASIGNACION DE ROLES A USUARIO Y EMPRESA
            $sql = "INSERT INTO " . $obj_con->BdAppweb . ".usua_grol_eper
                (eper_id, usu_id, grol_id, ugep_estado_logico, ugep_estado) VALUES
                ($IdEper,$IdUsu,$grol_id,'1','1') ";
            $command3 = $con->prepare($sql);
            $command3->execute();

            $con->commit();
            $con->close();
        }catch(Exception $e){
            $con->rollback();
            $con->close();
            //throw $e; 
            return $this->messageSystem('NO_OK', $e->getMessage(), null, null, null);
        }
    }

    private function getIdEmpresa($ruc){
        try {
            $obj_con = new cls_Base();
            $conCont = $obj_con->conexionAppWeb();
            $rawData = array();
            $cedRuc=trim($cedRuc);
            $sql = "SELECT emp_id Id, emp_nombre_comercial Nombre
                    FROM " . $obj_con->BdAppweb . ".empresa 
                    WHERE emp_ruc='$ruc' AND emp_estado_logico=1 AND emp_estado=1";
            //echo $sql;
            $sentencia = $conCont->query($sql);
            if ($sentencia->num_rows > 0) {
                //Retorna Solo 1 Registro Asociado
                $rawData=$this->messageSystem('OK',null,null,null, $sentencia->fetch_assoc());  
            }else{
                $rawData=$this->messageSystem('NO_OK',null,null,null,null);  
            }
            $conCont->close();
            return $rawData;
        } catch (Exception $e) {
            //echo $e;
            $conCont->close();
            return $this->messageSystem('NO_OK', $e->getMessage(), null, null, null);
        }
    }
    
    private function InsertarUsuario($con, $objEnt,$obj_con, $IdPer,$DBTable,$i) {
        $emp_id=cls_Global::$emp_id;
        $usuNombre = $objEnt[$i]['CedRuc'];
        $RazonSoc = $objEnt[$i]['RazonSoc'];
        //$correo = ($objEnt[$i]['CorreoPer']<>'')?$objEnt[$i]['CorreoPer']:$this->buscarCorreoERP($obj_con,$usuNombre,$DBTable);//Consulta Tabla Clientes
        $correo = ($objEnt[$i]['CorreoPer']<>'')?$objEnt[$i]['CorreoPer']:'';//Consulta Tabla Clientes
        $pass =$this->generarCodigoKey(8);// $objEnt[$i]['CedRuc'];
        //Inserta Datos Tabla USUARIO
        $sql = "INSERT INTO " . $obj_con->BdIntermedio . ".usuario
                (per_id,usu_nombre,usu_alias,usu_correo,usu_password,usu_est_log,usu_fec_cre)VALUES
                ($IdPer,'$usuNombre','$RazonSoc','$correo',MD5('$pass'),'1',CURRENT_TIMESTAMP()) ";
        $command = $con->prepare($sql);
        $command->execute();
        
        //Inserta Datas en la tabla USUARIO_EMPRESA con Su Rol
        $UsuId = $con->insert_id;
        //$RolId = $this->retornaRolUser($DBTable);//Retorna el Rol segunta tabla Roles
        /*
         * OPCIONAL SI SE NECESITA CONFIGURAR USUAIRO EMPRESA PARA EL ACCESO
        $sql = "INSERT INTO " . $obj_con->BdIntermedio . ".usuario_empresa
                (emp_id,usu_id,rol_id,est_log)VALUES
                ($emp_id,$UsuId,$RolId,1) ";
        $command = $con->prepare($sql);
        $command->execute();*/
        
        //Retorna el Pass y el Correo Guardado
        $arroout["Clave"] = $pass;
        $arroout["CorreoPer"] = $correo;

        // CREACION DE USUARIO EN SISTEMA ASGARD
        $existPersona = $this->buscarCedRudDBMain($usuNombre);
        if($existPersona['status'] == 'NO_OK')
            $this->InsertarUserDBMain($objEnt,$i, $pass);
        return $arroout;
    }
    //Retrona ROL SEGUN TABLA ROLES
    private function retornaRolUser($tabla) {
        $IdsRol = 6; //ROL DE USER NORMAL POR DEFECTO
        switch ($tabla) {
            Case "MG0031":
                $IdsRol = 4; //CLIENTE
                break;
            Case "MG0032":
                $IdsRol = 5; //PROVEEDOR
                break;
            default:
                $IdsRol = 6; //USUARIO NORMAL
        }
        return $IdsRol;
    }

    //Genera un Codigo para Pass
    private function generarCodigoKey($longitud) {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern) - 1;
        for ($i = 0; $i < $longitud; $i++)
            $key .= $pattern{mt_rand(0, $max)};
        return $key;
    }
    //Consulta en la Tablas del ERP si existe un correo
    private function buscarCorreoERP($obj_con,$CedRuc, $DBTable) {
        //$obj_con = new cls_Base();
        //Nota debe extraer los Correos del SIstema ERP
        $conCont = $obj_con->conexionIntermedio();
        $rawData='';
        $sql = "SELECT IFNULL(CORRE_E,'') CorreoPer  FROM " . $obj_con->BdIntermedio . ".$DBTable "
                . "WHERE CED_RUC='$CedRuc' AND CORRE_E<>'' ";
        //echo $sql;
        $sentencia = $conCont->query($sql);
        if ($sentencia->num_rows > 0) {
            $fila = $sentencia->fetch_assoc();
            $rawData= str_replace(",", ";", $fila["CorreoPer"]);//Remplaza las "," por el ";" Para poder enviar.
        }
        $conCont->close();
        return $rawData;
    }
    
    public function actualizaEnvioMailRAD($docDat,$tipDoc) {
        $obj_con = new cls_Base();
        //$conCont = $obj_con->conexionVsRAd();
        $conCont = $obj_con->conexionIntermedio();
        try {
            for ($i = 0; $i < sizeof($docDat); $i++) {
                $Estado=$docDat[$i]['EstadoEnv'];//Contine el IDs del Tabla Autorizacion
                $Ids=$docDat[$i]['Ids'];
                switch ($tipDoc) {
                    Case "FA"://FACTURAS
                        $sql = "UPDATE " . $obj_con->BdIntermedio . ".NubeFactura SET EstadoEnv='$Estado' WHERE IdFactura='$Ids';";
                        break;
                    Case "GR"://GUIAS DE REMISION
                        $sql = "UPDATE " . $obj_con->BdIntermedio . ".NubeGuiaRemision SET EstadoEnv='$Estado' WHERE IdGuiaRemision='$Ids';";
                        break;
                    Case "RT"://RETENCIONES
                        $sql = "UPDATE " . $obj_con->BdIntermedio . ".NubeRetencion SET EstadoEnv='$Estado' WHERE IdRetencion='$Ids';";
                        break;
                    Case "NC"://NOTAS DE CREDITO
                        $sql = "UPDATE " . $obj_con->BdIntermedio . ".NubeNotaCredito SET EstadoEnv='$Estado' WHERE IdNotaCredito='$Ids';";
                        break;
                    Case "ND"://NOTAS DE DEBITO
                        //$sql = "UPDATE " . $obj_con->BdIntermedio . ".NubeFactura SET EstadoEnv='$Estado' WHERE IdFactura='$Ids';";
                        break;
                }
                $command = $conCont->prepare($sql);
                $command->execute();
            }
            $conCont->commit();
            $conCont->close();
            return true;
        } catch (Exception $e) {
            $conCont->rollback();
            $conCont->close();
            throw $e;
            return false;
        }
    }
    
    public function limpioCaracteresXML($cadena) {
        //$search = array("<", ">", "&", "'","ñ","Ñ");
        //$replace = array("&lt;", "&gt", "&amp;", "&apos","n","N");
        //$final = str_replace($search, $replace, $cadena);
        //return $final;
        
        $String = trim($cadena);
        
        $String = str_replace(array('á','à','â','ã','ª','ä'),"a",$String);
        $String = str_replace(array('Á','À','Â','Ã','Ä'),"A",$String);
        $String = str_replace(array('Í','Ì','Î','Ï'),"I",$String);
        $String = str_replace(array('í','ì','î','ï'),"i",$String);
        $String = str_replace(array('é','è','ê','ë'),"e",$String);
        $String = str_replace(array('É','È','Ê','Ë'),"E",$String);
        $String = str_replace(array('ó','ò','ô','õ','ö','º'),"o",$String);
        $String = str_replace(array('Ó','Ò','Ô','Õ','Ö'),"O",$String);
        $String = str_replace(array('ú','ù','û','ü'),"u",$String);
        $String = str_replace(array('Ú','Ù','Û','Ü'),"U",$String);
        
        $String = str_replace(array('[','^','´','`','¨','~',']'),"",$String);
  
        //Implementado Byron 14-12-2016
        $String = str_replace("<","&lt;",$String);
        $String = str_replace(">","&gt",$String);
        $String = str_replace("&","&amp;",$String);
        //$String = str_replace("&","&#38;",$String);
        $String = str_replace("'","&apos",$String);
        
        $String = str_replace("ç","c",$String);
        $String = str_replace("Ç","C",$String);
        $String = str_replace("ñ","n",$String);
        $String = str_replace("Ñ","N",$String);
        $String = str_replace("Ý","Y",$String);
        $String = str_replace("ý","y",$String);
        
        

        $String = str_replace("&aacute;","a",$String);
        $String = str_replace("&Aacute;","A",$String);
        $String = str_replace("&eacute;","e",$String);
        $String = str_replace("&Eacute;","E",$String);
        $String = str_replace("&iacute;","i",$String);
        $String = str_replace("&Iacute;","I",$String);
        $String = str_replace("&oacute;","o",$String);
        $String = str_replace("&Oacute;","O",$String);
        $String = str_replace("&uacute;","u",$String);
        $String = str_replace("&Uacute;","U",$String);
        
        return $String;
        
    }
    
    public function limpioCaracteresSQL($cadena) {
        $search = array("'");
        $replace = array("`");
        $final = str_replace($search, $replace, $cadena);
        return $final;
    }
    
    public static function retornaTarifaDelIva($tarifa) {
         //TABLA 18 FICHA TECNICA
        $codigo=0;
        switch (floatval($tarifa)) {
            Case 0:
                $codigo=0;
                break;
            Case 12:
                $codigo=2;
                break;
            Case 14:
                $codigo=3;
                break;
            Case 6:
                $codigo=6;//NO OBJETO DE IVA
                break;
            default:
                $codigo=7;//EXEPTO DE IVA
        }
        return $codigo;
     }
     
     public static function buscarDocAutorizacion($tipDoc) {
        try {
            $obj_con = new cls_Base();
            $obj_var = new cls_Global();
            $conCont = $obj_con->conexionIntermedio();
            $rawData = array();
            $fechaIni=$obj_var->dateStartFact;
            $limitEnv=$obj_var->limitEnv;
            switch ($tipDoc) {
                    Case "FA"://FACTURAS                        
                        $sql = "SELECT IdFactura Ids,ClaveAcceso,AutorizacionSri 
                                        FROM " . $obj_con->BdIntermedio . ".NubeFactura 
                                WHERE Estado=2 AND DATE(FechaCarga)>='$fechaIni' LIMIT $limitEnv ";                        
                        break;
                    Case "GR"://GUIAS DE REMISION
                        $sql = "UPDATE " . $obj_con->BdIntermedio . ".NubeGuiaRemision SET EstadoEnv='$Estado' WHERE IdGuiaRemision='$Ids';";
                        break;
                    Case "RT"://RETENCIONES
                        $sql = "UPDATE " . $obj_con->BdIntermedio . ".NubeRetencion SET EstadoEnv='$Estado' WHERE IdRetencion='$Ids';";
                        break;
                    Case "NC"://NOTAS DE CREDITO
                        $sql = "UPDATE " . $obj_con->BdIntermedio . ".NubeNotaCredito SET EstadoEnv='$Estado' WHERE IdNotaCredito='$Ids';";
                        break;
                    Case "ND"://NOTAS DE DEBITO
                        //$sql = "UPDATE " . $obj_con->BdIntermedio . ".NubeFactura SET EstadoEnv='$Estado' WHERE IdFactura='$Ids';";
                        break;
                }
            
            //echo $sql;
            $sentencia = $conCont->query($sql);
            if ($sentencia->num_rows > 0) {
                while ($fila = $sentencia->fetch_assoc()) {//Array Asociativo
                    $rawData[] = $fila;
                }
            }
            $conCont->close();
            return $rawData;
        } catch (Exception $e) {
            echo $e;
            $conCont->close();
            return false;
        }
    }
    
    public static function putMessageLogFile($message) {
        $rutaLog= __DIR__ . '/log/Errorlog.log';//$this->logfile;
        if (is_array($message))
            $message = json_encode($message);
        $message = date("Y-m-d H:i:s") . " " . $message . "\n";
        if (!is_dir(dirname($rutaLog))) {
            mkdir(dirname($rutaLog), 0777, true);
            chmod(dirname($rutaLog), 0777);
            touch($rutaLog);
        }
        //se escribe en el fichero
        file_put_contents($rutaLog, $message, FILE_APPEND | LOCK_EX);
    }
    
    public static function formatoDecXML($valor){
        $obj_var = new cls_Global();
        return number_format($valor, $obj_var->decimalPDF, $obj_var->SepdecimalPDF, '');
    }



    /************************** FUNCIONES DE CIFRADO ********************************** */


    private function generateRandomString($length = 32){
        $bytes = "";
        if (function_exists('random_bytes')) {
            $bytes = random_bytes($length);
        }
        return substr(strtr(base64_encode($bytes), '+/', '-_'), 0, $length);
    }

    private function generatePassword($password, $hash){
        //$hash = $this->generateRandomString();
        return base64_encode($this->encrypt($hash, true, $password, null));
    }

    private function encrypt($data, $passwordBased, $secret, $info)
    {
        $cipher = 'AES-128-CBC';
        $kdfHash = 'sha256';
        $authKeyInfo = 'AuthorizationKey';
        $macHash = 'sha256';
        $derivationIterations = 100000;
        $allowedCiphers = [
            'AES-128-CBC' => [16, 16],
            'AES-192-CBC' => [16, 24],
            'AES-256-CBC' => [16, 32],
        ];
        if (!extension_loaded('openssl')) {
            throw new Exception('Encryption requires the OpenSSL PHP extension');
        }
        if (!isset($allowedCiphers[$cipher][0], $allowedCiphers[$cipher][1])) {
            throw new Exception($cipher . ' is not an allowed cipher');
        }

        list($blockSize, $keySize) = $allowedCiphers[$cipher];

        $keySalt = $this->generateRandomString($keySize);
        if ($passwordBased) {
            $key = $this->pbkdf2($kdfHash, $secret, $keySalt, $derivationIterations, $keySize);
        } else {
            $key = $this->hkdf($kdfHash, $secret, $keySalt, $info, $keySize);
        }

        $iv = $this->generateRandomString($blockSize);

        $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new Exception('OpenSSL failure on encryption: ' . openssl_error_string());
        }

        $authKey = $this->hkdf($kdfHash, $key, null, $authKeyInfo, $keySize);
        $hashed = hash_hmac($macHash, $iv . $encrypted, $authKey, FALSE) . $iv . $encrypted;

        /*
         * Output: [keySalt][MAC][IV][ciphertext]
         * - keySalt is KEY_SIZE bytes long
         * - MAC: message authentication code, length same as the output of MAC_HASH
         * - IV: initialization vector, length $blockSize
         */
        return $keySalt . $hashed;
    }

    private function hkdf($algo, $inputKey, $salt = null, $info = null, $length = 0)
    {
        if (function_exists('hash_hkdf')) {
            $outputKey = hash_hkdf($algo, $inputKey, $length, $info, $salt);
            if ($outputKey === false) {
                throw new Exception('Invalid parameters to hash_hkdf()');
            }

            return $outputKey;
        }

        $test = @hash_hmac($algo, '', '', true);
        if (!$test) {
            throw new Exception('Failed to generate HMAC with hash algorithm: ' . $algo);
        }
        $hashLength = mb_strlen($test, '8bit');
        if (is_string($length) && preg_match('{^\d{1,16}$}', $length)) {
            $length = (int) $length;
        }
        if (!is_int($length) || $length < 0 || $length > 255 * $hashLength) {
            throw new Exception('Invalid length');
        }
        $blocks = $length !== 0 ? ceil($length / $hashLength) : 1;

        if ($salt === null) {
            $salt = str_repeat("\0", $hashLength);
        }
        $prKey = hash_hmac($algo, $inputKey, $salt, true);

        $hmac = '';
        $outputKey = '';
        for ($i = 1; $i <= $blocks; $i++) {
            $hmac = hash_hmac($algo, $hmac . $info . chr($i), $prKey, true);
            $outputKey .= $hmac;
        }

        if ($length !== 0) {
            $outputKey = mb_substr($outputKey, 0, $length === null ? mb_strlen($outputKey, '8bit') : $length, '8bit');
        }

        return $outputKey;
    }

    private function pbkdf2($algo, $password, $salt, $iterations, $length = 0)
    {
        if (function_exists('hash_pbkdf2')) {
            $outputKey = hash_pbkdf2($algo, $password, $salt, $iterations, $length, true);
            if ($outputKey === false) {
                throw new Exception('Invalid parameters to hash_pbkdf2()');
            }

            return $outputKey;
        }

        // todo: is there a nice way to reduce the code repetition in hkdf() and pbkdf2()?
        $test = @hash_hmac($algo, '', '', true);
        if (!$test) {
            throw new Exception('Failed to generate HMAC with hash algorithm: ' . $algo);
        }
        if (is_string($iterations) && preg_match('{^\d{1,16}$}', $iterations)) {
            $iterations = (int) $iterations;
        }
        if (!is_int($iterations) || $iterations < 1) {
            throw new Exception('Invalid iterations');
        }
        if (is_string($length) && preg_match('{^\d{1,16}$}', $length)) {
            $length = (int) $length;
        }
        if (!is_int($length) || $length < 0) {
            throw new Exception('Invalid length');
        }
        $hashLength = mb_strlen($test, '8bit');
        $blocks = $length !== 0 ? ceil($length / $hashLength) : 1;

        $outputKey = '';
        for ($j = 1; $j <= $blocks; $j++) {
            $hmac = hash_hmac($algo, $salt . pack('N', $j), $password, true);
            $xorsum = $hmac;
            for ($i = 1; $i < $iterations; $i++) {
                $hmac = hash_hmac($algo, $hmac, $password, true);
                $xorsum ^= $hmac;
            }
            $outputKey .= $xorsum;
        }

        if ($length !== 0) {
            $outputKey = mb_substr($outputKey, 0, $length === null ? mb_strlen($outputKey, '8bit') : $length, '8bit');
        }

        return $outputKey;
    }

    

}
