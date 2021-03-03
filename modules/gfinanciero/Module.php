<?php
namespace app\modules\gfinanciero;

use app\models\Utilities;
use Yii;

class Module extends \yii\base\Module
{
    public $db_gfinanciero;    // se debe colocar el identificador de la base que se define en el archivo de modulos: mod.php
    public $class;  // se debe colocar el la clase de la conexion de la base que se define en el archivo de modulos: mod.php
    public $controllerNamespace = 'app\modules\gfinanciero\controllers';
    private static $module_name = 'gfinanciero';
    
    public function init()
    {
        parent::init();
        //\Yii::$app->urlManager->addRules(['<module:app>/<controller:\w+>/<action:\w+>/<id:\w+>' => '<module>/<controller>/<action>']);
        Yii::configure($this, require(__DIR__ . '/config/config.php'));
        self::registerTranslations();
        //\Yii::$app->view->theme->pathMap[your_module_name.'/views'] = [your_module_name.'/themes/'.\Yii::$app->view->theme->active.'/views']; // para usar temas
    }

    public static function registerTranslations()
    {
        $fileMap = self::getMessageFileMap();
        Yii::$app->i18n->translations['modules/'. self::$module_name .'/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            //'sourceLanguage' => 'es',
            'basePath' => '@app/modules/'. self::$module_name .'/messages',
            'fileMap' => $fileMap,
            //'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation'],
        ];
    }

    public function beforeAction($action){ // funcion que edita la coneccion de la base de datos de acuerdo a la empresa
        $emp_id = Yii::$app->session->get("PB_idempresa");
        if(isset($emp_id) && $emp_id > 0){
            $mod = Yii::$app->getModule(self::$module_name);
            $dbInstance = $mod->get('db_gfinanciero');
            $arrEmp = require(__DIR__ . '/config/dbCon.php');
            Yii::$app->set('db_gfinanciero', $arrEmp[$emp_id]);
            $mod->set('db_gfinanciero', $arrEmp[$emp_id]);
        }
        return parent::beforeAction($action);
    }
    
    private static function getMessageFileMap(){
        // read directory message
        $arrLangFiles = array();
        $dir_messages = __DIR__ . DIRECTORY_SEPARATOR . "messages";
        $fileMap = array();
        $listDirs = scandir($dir_messages);
        foreach($listDirs as $dir){
            if($dir != "." && $dir != ".."){
                $langDir = scandir($dir_messages . DIRECTORY_SEPARATOR . $dir);
                foreach ($langDir as $langFile){
                    if(preg_match("/\.php$/", trim($langFile))){
                        if(!in_array($langFile, $arrLangFiles)){
                            $arrLangFiles[] = $langFile;
                            $file = str_replace(".php", "", $langFile);
                            $key = "modules/" . self::$module_name . "/" . $file;
                            $fileMap[$key] = $langFile;
                        }
                    }
                }
            }
        }
        return $fileMap;
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/'. self::$module_name .'/' . $category, $message, $params, $language);
    }
}
