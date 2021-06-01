<?php

namespace app\modules\gfinanciero\components;

use Yii;
use app\models\Utilities;
use app\modules\gfinanciero\Module as financiero;
use Exception;

financiero::registerTranslations();

class CMsgException {
 
    /* Properties */
    public string $message ;
    public int $code ;
    public string $file ;
    public int $line ;
    public Exception $ex;

    /* Methods */
    public function __construct (Exception $ex = null ){
        $this->message = $ex->getMessage();
        $this->code = $ex->getCode();
        $this->ex = $ex;
    }

    public function getMsgLang($inHtml = true){
        $code = $this->getCode();
        $msg = $this->getMessage();
        $txtMsg = '';
        if($code == '9999999'){
            $msg = json_decode($msg, true); 
            foreach($msg as $key => $value){
                $txtMsg .= $value;
                if($inHtml) $txtMsg .= '<br/>';
            }
        }else if(str_contains($msg, 'SQLSTATE')){ // es Error de base de datos
            if(isset($code) && $code != '' && $code > 0){
                $txtMsg = financiero::t('dbexception', $code);
            } 
        }else{
            //$txtMsg = $msg;
        }
        return $txtMsg;
    }

    public function getMessage(){
        return $this->ex->getMessage();
    }

    public function getPrevious(){
        return $this->ex->getPrevious();
    }

    public function getCode(){
        return $this->ex->getCode();
    }

    public function getFile(){
        return $this->ex->getFile();
    }

    public function getLine(){
        return $this->ex->getLine();
    }

    public function getTrace(){
        return $this->ex->getTrace();
    }

    public function getTraceAsString(){
        return $this->ex->getTraceAsString();
    }

    public function printExceptionLog(){
        $arr_error = [
            'error' => $this->getMessage(),
            'line' => $this->getLine(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
        ];
        Utilities::putMessageLogFile('Exception Error');
        Utilities::putMessageLogFile($arr_error);
    }
}
