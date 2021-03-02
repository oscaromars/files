<?php
/*
 * The PenBlu framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by PenBlu Software (http://www.penblu.com)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *  - Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *  - Neither the name of PenBlu Software nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PenBlu is based on code by
 * Yii Software LLC (http://www.yiisoft.com) Copyright © 2008
 *
 */
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Usuario;
use app\models\Persona;
use app\models\Utilities;
use yii\helpers\Html;

/**
 * LoginForm is the model behind the login form.
 */
class ForgotpassForm extends Model {

    public $email;
    public $verifyCode;
    private $_errorSession = false;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['email'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'min' => $minEmail],
            [['email'], 'trim'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels() {
        return [
            'email' => Yii::t('login', 'Email'),
            'verifyCode' => Yii::t('register', 'Verification Code'),
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */

    /**
     * Function verificarCuenta
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function verificarCuenta() {
        if ($this->validate()) { // no hay problemas de validacion
            //$usuario = Usuario::findOne(['usu_user' => $this->email]);
            $usuario = Usuario::find()
            ->where(['usu_user' => $this->email])
            ->orderBy(['usu_id' => SORT_DESC])
            ->one();
            $passReset = new UserPassreset();
            if (isset($usuario) && $usuario->usu_estado == "1") {
                // se trata de un usuario que no recuerda su clave
                // 1. se debe generar el link de cambio de clave
                $link = $passReset->generarLinkCambioClave($usuario->usu_id);
                if ($link) {
                    // 2. se debe enviar el correo
                    $persona = Persona::findIdentity($usuario->per_id);
                    $nombres = $persona->per_pri_nombre . " " . $persona->per_pri_apellido;
                    $tituloMensaje = Yii::t("passreset", "Change Password");
                    $asunto = Yii::t("passreset", "Change Password") . " " . Yii::$app->params["siteName"];
                    $body = Utilities::getMailMessage("userpass", array("[[user]]" => $nombres, "[[username]]" => $this->email, "[[link_verification]]" => $link), Yii::$app->language);
                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$this->email => $nombres], $asunto, $body);
                    // 3. mostrat mensaje de exito
                    Yii::$app->session->setFlash('success', Yii::t("passreset", "<h4>Success</h4>To change your password you must access your email and follow the instructions in the mail"));
                    return true;
                } else {
                    // 2. Mostrar error
                    $this->setErrorSession(true);
                    $this->addError("error", Yii::t("exception", 'The above error occurred while the Web server was processing your request.'));
                    Yii::$app->session->setFlash('error', Yii::t("exception", 'The above error occurred while the Web server was processing your request.'));
                    return false;
                }
            } else {
                if (isset($usuario) && $usuario->usu_estado == "1") {
                    // se trata de un usuario que no ha activado aun su clave 
                    // generar link de verificacion
                    $link = $usuario->generarLinkActivacion();
                    $persona = Persona::findIdentity($usuario->per_id);
                    $nombres = $persona->per_pri_nombre . " " . $persona->per_pri_apellido;
                    $tituloMensaje = Yii::t("register", "User Activation");
                    $asunto = Yii::t("register", "User Activation") . " " . Yii::$app->params["siteName"];
                    $body = Utilities::getMailMessage("register", array("[[user]]" => $nombres, "[[username]]" => $this->email, "[[link_verification]]" => $link), Yii::$app->language);
                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$this->email => $nombres], $asunto, $body);
                    // se debe mostrar mensaje de alerta que indique que se ha enviado el correo
                    Yii::$app->session->setFlash('success', Yii::t("register", "<h4>Success</h4>To activate your account you must access your email and follow the instructions in the mail"));
                    return true;
                } else {
                    if (isset($usuario) && $usuario->usu_estado == "0") {
                        // es una usuario desactivado
                        $this->setErrorSession(true);
                        $this->addError("error", Yii::t("login", "<h4>Error</h4>Account is disabled. Please confirm the account with link activation in your email account or reset your password."));
                        Yii::$app->session->setFlash('error', Yii::t("login", "<h4>Error</h4>Account is disabled. Please confirm the account with link activation in your email account or reset your password."));
                        return false;
                    } else {
                        // es otro evento no manejado
                        $this->setErrorSession(true);
                        $this->addError("error", Yii::t("login", '<h4>Error</h4>Invalid Account.'));
                        Yii::$app->session->setFlash('error', Yii::t("login", '<h4>Error</h4>Invalid Account.'));
                        return false;
                    }
                }
            }
        } else { // error de validacion
            $this->setErrorSession(true);
            $this->addError("error", Yii::t("login", 'Please fill all fields'));
            Yii::$app->session->setFlash('error', Yii::t("login", 'Please fill all fields'));
            return false;
        }
    }

    /**
     * Function getErrorSession
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function getErrorSession() {
        return $this->_errorSession;
    }

    /**
     * Function setErrorSession
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function setErrorSession($error) {
        $this->_errorSession = $error;
    }

    /**
     * Function unsetAttributes
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function unsetAttributes($names = null) {
        if ($names === null)
            $names = $this->attributes();
        foreach ($names as $name)
            $this->$name = null;
    }

}
