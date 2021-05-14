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
 * Authors:
 *
 * Eduardo Cueva <ecueva@penblu.com>
 */

namespace app\controllers;

use Yii;
use app\components\CController;
use app\models\Usuario;
use app\models\Grupo;
use app\models\Rol;
use app\models\Empresa;
use app\models\Persona;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Security;
use app\models\UsuaGrolEper;

class UsuarioController extends CController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                $arr_usuarios = Usuario::listadoUsuarios($data["search"]);
                return $this->renderPartial('index-grid',[
                    "model" => $arr_usuarios,
                ]);
            }
        }
        $arr_usuarios = Usuario::listadoUsuarios();
        return $this->render('index',[
            "model" => $arr_usuarios,
        ]);
    }

    public function actionNew()
    {
        /*$arr_genero = Persona::genero();
        $data = new ArrayDataProvider(array());
        $_SESSION['JSLANG']['Password are differents. Please enter passwords again.'] = Yii::t('passreset', 'Password are differents. Please enter passwords again.');
        $_SESSION['JSLANG']['Delete'] = Yii::t('accion', 'Delete');
        return $this->render('new', [           
            'data' => $data,
                ]);*/
        
        $model = new Usuario();
        //$perADO = new Persona();
        //$paises = Pais::getPaises();
        $provincias = array();
        $cantones = array();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            
        }
        
        return $this->render('new', [
                    "model" => $model,
                    //"persona" => json_encode($perData), 
                    "genero" => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                    "cantones" => $cantones]);
    }
    
    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $model = new Usuario();
            $data = Yii::$app->request->post();
            $accion = isset($data['ACCION']) ? $data['ACCION'] : "";
            if ($accion == "Create") {
                //Nuevo Registro
                $resul = $model->insertarUsuarioEmpRolGru($data);
            }else if($accion == "Update"){
                //Modificar Registro
                $resul = $model->actualizarUsuarioEmpRolGru($data);                
            }
            if ($resul['status']) {
                $message = ["info" => Yii::t('exception', '<strong>Well done!</strong> your information was successfully saved.')];
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message,$resul);
            }else{
                $message = ["info" => Yii::t('exception', 'The above error occurred while the Web server was processing your request.')];
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
            }
            return;
        }   
    }
    
    
    public function actionView()
    {
        return $this->render('view');
    }

    public function actionEdit()
    {
        return $this->render('edit');
    }
    
    
    public function actionAddempresa(){
        $user_id    = Yii::$app->session->get('PB_iduser', FALSE);
        $empresas = Empresa::getListaEmpresasxUserID($user_id);
        $grupos   = Grupo::getAllGrupos();
        $roles    = Rol::getAllRoles();
        $arr_empresas = ["0" => Yii::t("empresa", "-- Select Company --")];
        $arr_grupos   = ["0" => Yii::t("grupos", "-- Select Group --")];
        $arr_roles    = ["0" => Yii::t("roles", "-- Select Role --")];
        
        if(count($empresas) > 0)
            $arr_empresas = array_merge($arr_empresas, ArrayHelper::map($empresas, "id", "name"));
        
        if(count($grupos) > 0)
            $arr_grupos = array_merge($arr_grupos, ArrayHelper::map($grupos, "id", "name"));
        
        if(count($roles) > 0)
            $arr_roles = array_merge($arr_roles, ArrayHelper::map($roles, "id", "name"));
                
        return $this->render('addempresa', [
            "arr_empresas" => $arr_empresas,
            "arr_grupos"   => $arr_grupos,
            "arr_roles"    => $arr_roles,
            ]);
    }
    
    public function actionUpdate() {
        $usuADO = new Usuario();
        $Ids = isset($_GET['id']) ? base64_decode($_GET['id']) : NULL;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $usuData = $usuADO->consultarUsuario($Ids);      
        $usuEGR = $usuADO->consultarEmpGruRol($usuData[0]["per_id"]);
        return $this->render('update', [
            "model" => $usuData,
            "usuEGR" => json_encode($usuEGR),
            "rol" => Rol::getAllRoles(),
            "grupo" => Grupo::getAllGrupos()]);
    }
    
    public function actionDelete() {
        $usuADO = new Usuario();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $resul = $usuADO->eliminarUsuarioGruRol($data);
            if ($resul) {
                $message = ["info" => Yii::t('exception', '<strong>Well done!</strong> your information was successfully saved.')];
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }else{
                $message = ["info" => Yii::t('exception', 'The above error occurred while the Web server was processing your request.')];
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
            }
            return;
        }
    }
    
    /* Grace Viteri */
    public function actionGeneraclaves() {
        $usuario = new Usuario();     
        $security = new Security();
        $dataInicial = 251;
        $dataFinal = 251;
        $resul = $usuario->consultarDataUsuario($dataInicial, $dataFinal);
        if (count($resul)>0) {            
            for ($i=0; $i<count($resul); $i++) {                
                $usu_sha = $security->generateRandomString();
                $usu_pass= base64_encode($security->encryptByPassword($usu_sha, $resul[$i]["per_cedula"]));                                
                /*\app\models\Utilities::putMessageLogFile('usu_sha:' . $usu_sha);
                \app\models\Utilities::putMessageLogFile('usu_pass:' . $usu_pass);                */
                $respUsu = $usuario->actualizarDataUsuario($usu_sha, $usu_pass, $resul[$i]["usu_id"]);                   
            }
        }
        return $this->render('generaclaves', [
                    ]);
    }

    /* Grace Viteri */
    public function actionActualizarolpassestado() {
        $usuario = new Usuario();     
        $security = new Security();
        
        $resul = $usuario->consultarUsuarioNuevo();
        if (count($resul)>0) {            
            for ($i=0; $i<count($resul); $i++) {                
                $usu_sha = $security->generateRandomString();
                $usu_pass= base64_encode($security->encryptByPassword($usu_sha, $resul[$i]["per_cedula"]));                                
                /*\app\models\Utilities::putMessageLogFile('usu_sha:' . $usu_sha);
                \app\models\Utilities::putMessageLogFile('usu_pass:' . $usu_pass);                */
                $respUsu = $usuario->actualizarDataUsuario($usu_sha, $usu_pass, $resul[$i]["usu_id"]);              
                if ($respUsu) {
                    // modificar e grupo y rol
                    $usugrol_model = UsuaGrolEper::findOne(["ugep_id" => $resul[$i]["ugep_id"]]);    
                    $usugrol_model->grol_id = 37;
                    $usugrol_model->save();
                }                
            }
        }
        return $this->render('generaclaves', [
                    ]);
    }
}

