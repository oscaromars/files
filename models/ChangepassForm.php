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
use yii\helpers\Url;

/**
 * LoginForm is the model behind the login form.
 */
class ChangepassForm extends Model {

    public $password;
    public $password_repeat;
    public $verifyCode;
    private $_errorSession = false;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        // se debe sacar la validacion de la expresion simple
        $tpass = TipoPassword::findIdentity(1); // get Simple Password Type
        $minPass = 8;
        return [
            [['password_repeat', 'password'], 'required'],
            ['password', 'string', 'min' => $minPass,],
            ['password', 'match', 'pattern' => str_replace("VAR", $minPass, $tpass->tpas_validacion), 'message' => Yii::t('tipopassword', 'Password must be uppercase and lowercase')],
            ['password_repeat', 'safe'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels() {
        return [
            'password' => Yii::t('login', 'Password'),
            'password_repeat' => Yii::t('login', 'Confirm Password'),
            'verifyCode' => Yii::t('register', 'Verification Code'),
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */

    /**
     * Function resetearClave
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function resetearClave($link) {
        if ($this->validate()) { // no hay problemas de validacion
            // se debe buscar el id del usuario a traves del link de activacion
            if ($this->password == $this->password_repeat) {
                $user_pass = UserPassreset::findOne(['upas_link' => $link, 'upas_estado_activo' => 1]);
                $usuario = Usuario::find()
                ->where(['usu_id' => $user_pass->usu_id])
                ->orderBy(['usu_id' => SORT_DESC])
                ->one();
                //$usuario = Usuario::findIdentity($user_pass->usu_id);
                if (isset($usuario) && $usuario->usu_estado == "1" && isset($user_pass)) {
                    $usuario->generateAuthKey(); // generacion de hash
                    $usuario->setPassword($this->password);
                    $usuario->save();
                    $user_pass->upas_estado_activo = "0";
                    $user_pass->upas_estado_logico = "0";
                    $user_pass->upas_remote_ip_activo = Utilities::getClientRealIP();
                    $user_pass->upas_fecha_fin = date("Y-m-d H:i:s");
                    $user_pass->save();
                    // send email
                    $link_asgard = Url::base(true) . Utilities::getLoginUrl(); //Url::base(true);
                    $persona = Persona::findIdentity($usuario->per_id);
                    $nombres = $persona->per_pri_nombre . " " . $persona->per_pri_apellido;
                    $tituloMensaje = Yii::t("passreset", "Change Password Successfull");
                    $asunto = Yii::t("passreset", "Change Password Successfull") . " " . Yii::$app->params["siteName"];
                    $body = Utilities::getMailMessage("changepassword", array("[[user]]" => $nombres, "[[username]]" => $usuario->usu_user, "[[webmail]]" => Yii::$app->params["adminEmail"], "[[link_asgard]]" => $link_asgard), Yii::$app->language);
                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$usuario->usu_user => $nombres], $asunto, $body);
                    Yii::$app->session->setFlash('success', Yii::t("passreset", "<h4>Success</h4>Password has been updated successfully"));
                    return true;
                } else {
                    if ($user_pass->upas_estado_activo == "0") {
                        $this->addError("error", Yii::t("login", "<h4>Error</h4>Link Activacion is timed out. Please get a new password with Forgot your password link."));
                        Yii::$app->session->setFlash('error', Yii::t("login", "<h4>Error</h4>Link Activation is timed out. Please get a new password with Forgot your password link."));

                        return false;
                    } else {
                        $this->addError("error", Yii::t("login", "<h4>Error</h4>Account is disabled. Please confirm the account with link activation in your email account or reset your password."));
                        Yii::$app->session->setFlash('error', Yii::t("login", "<h4>Error</h4>Account is disabled. Please confirm the account with link activation in your email account or reset your password."));
                        return false;
                    }
                }
            } else {
                $this->addError("error", Yii::t("login", 'Please fill all fields'));
                Yii::$app->session->setFlash('error', Yii::t("login", 'Please fill all fields'));
                return false;
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
