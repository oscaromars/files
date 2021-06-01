<?php

namespace app\components;

use Yii;
use yii\base\InlineAction;
use yii\helpers\Url;
use app\models\Error;
use app\models\Utilities;
use app\models\Accion;
use app\models\Usuario;
use app\models\ObjetoModulo;
use app\models\Modulo;

/**
 * Description of CController
 *
 * @author eduardocueva
 */
class CController extends \yii\web\Controller {

    public $freportini = "";
    public $freportend = "";
    public $modulo = array();
    public $botones = array();
    public $id_modulo = 0;
    public $id_objeto_modulo = 0;
    public $id_moduloPadre = 0;
    
    public function init() {
		if(!is_dir(Yii::getAlias('@bower')))
			Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }

    /***** FUNCION QUE REALIZA EL PROCESO DE VERIFICACION DE PERMISOS
    public function behaviors()
    {
        $route = $this->route;
        $arr_behaviors = Accion::generateBehaviorByActions($route);
        return $arr_behaviors;
    }
    */

    /**
     * Function ajaxResponse
     * @author  Eduardo Cueva <ecueva@penblu.com>
     * @param      
     * @return  
     */
    public function runAction($id, $params = []) {
        $session = Yii::$app->session;
        $isUser = $session->get('PB_isuser', FALSE);
        $route = $this->getRoute() . "/login";
        $usu = new Usuario;
        
        //$usu->regenerateSession();
        if ($isUser == FALSE && $route != 'site/login' ) {
            $this->redirect(Yii::$app->urlManager->createUrl([Utilities::getLoginUrl()]));
        }
        return parent::runAction($id, $params);
    }
    
    public function beforeAction($action)
    {
        $this->createMenuModule();
        if (parent::beforeAction($action)) {
            $request = array_merge($_GET, $_POST);
            if(isset($request['pdf']) && $request['pdf'] == true){
                $this->layout = '@themes/' . Yii::$app->getView()->theme->themeName . '/layouts/pdf';
            }
            $error = Yii::$app->getErrorHandler()->exception;
            if ($error !== null) {
                $this->layout = '@themes/' . Yii::$app->getView()->theme->themeName . '/layouts/error';
                if (Yii::$app->request->isAjax) {
                    return $error['statusCode'];//$error['message'];
                } else {
                    $error = new Error(404, "", "");
                    return $this->render('@themes/' . Yii::$app->getView()->theme->themeName . '/layouts/error.php',["mensaje" => $error->getMessageByError()]);
                }
            }
            return true;  // or false if needed
        } else {
            return false;
        }
        
        //
        // Esto es para cambiar el tema antes de devolver el render
        // $this->getView()->theme = Yii::createObject([
        //      'class' => '\yii\base\Theme',
        //      'pathMap' => ['@app/views' => '@app/themes/basic'],
        //      'baseUrl' => '@web/themes/basic',
        //  ]);
        // 
        
    }
    
    public function createMenuModule(){
        $ctr = Yii::$app->controller->id;
        $acc = Yii::$app->controller->action->id;
        $mod = Yii::$app->controller->module->id;
        $route = $this->route;
        $session = Yii::$app->session;
        $objModule = ObjetoModulo::findIdentityByEntity(trim($route));
        $objModPadre = ObjetoModulo::findIdentity($objModule->omod_padre_id);
        $module = Modulo::findIdentity($objModule->mod_id);
        $session->set('PB_module_id', $objModule->mod_id);
        $session->set('PB_objmodule_id', $objModule->omod_id);
        
        $this->getView()->title = ($objModule->omod_lang_file != "")?Yii::t($objModule->omod_lang_file,$objModule->omod_nombre):$objModule->omod_nombre;
        $this->getView()->params["Module_name"]    = ($module->mod_lang_file != "")?Yii::t($module->mod_lang_file,$module->mod_nombre):$module->mod_nombre;
        $this->getView()->params["ObjModPadre_name"] = ($objModPadre->omod_lang_file != "")?Yii::t($objModPadre->omod_lang_file,$objModPadre->omod_nombre):$objModPadre->omod_nombre;
        $this->getView()->params["ObjModule_name"] = ($objModule->omod_lang_file != "")?Yii::t($objModule->omod_lang_file,$objModule->omod_nombre):$objModule->omod_nombre;
        $this->getView()->params["mod_id"] = $module->mod_id;
        $this->getView()->params["omod_id"] = $objModule->omod_id;
        $this->getView()->params["omod_padre_id"] = $objModule->omod_padre_id;

    }

}
