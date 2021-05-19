<?php

namespace app\vendor\penblu\jcrop;

use Yii;
use app\vendor\penblu\jcrop\JCropAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * This is just an example.
 */
class JCrop extends \yii\base\Widget
{
    private static $widget_name = "JCrop";
    public $containerId = "";
    public $htmlOptions = [];
    public $fadeEvent = "true"; // Permite que aparezca la opacidad como background
    public $opacity = ".2"; // Opacidad por defecto .2
    public $url = '';
    public $showSeletedBox = true; // Permite que aparezca el marco del area a recortar
    public $selectBoxPosX = "0"; // Posicion en X del primer punto
    public $selectBoxPosY = "0"; // Posicion en Y del primer punto
    public $selectBoxPosW = "0"; // Ancho o posicion desde el punto x,y
    public $selectBoxPosH = "0"; // Altura o posicion desde el punto x,y
    public $callBackFn = "";
    public $showFnSelect = false; // Muestra una funcion que muestra el cuadro seleccionado

    public function init() {
        parent::init();
        JCropAsset::register($this->getView());
    }
    
    public function run()
    {
        $id = $this->getId();
        $this->htmlOptions['id'] = 'jcrop-' . ((isset($this->containerId))?($this->containerId):$id);
        $this->containerId = $this->htmlOptions['id'];
        //echo '<div ' . Html::renderTagAttributes($this->htmlOptions) . '></div>';
        echo $this->render('index', [
            "htmlOptions" => $this->htmlOptions,
            'url' => $this->url,
        ]);
        $this->registerClientScript($this->containerId);
        parent::run();
    }

    public function registerClientScript($id) {
        $setSelect = "";
        $callBackFn = "";
        if($this->showSeletedBox){
            $setSelect = "setSelect: [ ".$this->selectBoxPosX.", ".$this->selectBoxPosY.", ".$this->selectBoxPosW.", ".$this->selectBoxPosH." ],";
        }
        if(isset($this->callBackFn) && $this->callBackFn != ""){
            $callBackFn = "function " . $this->callBackFn . "(){ return selectCoords(); }";
        }
        $script = 
        "
        var jcrop_api;
        $('#".$id."').Jcrop({
            bgFade: ".$this->fadeEvent.",
            bgOpacity: ".$this->opacity.",
            onSelect: updateCoordsJCrop,
            $setSelect
          },function(){
            jcrop_api = this;
        });
        function updateCoordsJCrop(c)
          {
            $('#x-jcrop').val(c.x);
            $('#y-jcrop').val(c.y);
            $('#w-jcrop').val(c.w);
            $('#h-jcrop').val(c.h);
        }
        function selectCoords(){
            let objCropImg = new Object();
            objCropImg.x = $('#x-jcrop').val();
            objCropImg.y = $('#y-jcrop').val();
            objCropImg.w = $('#w-jcrop').val();
            objCropImg.h = $('#h-jcrop').val();
            return objCropImg;
        }
        function setSelect(){
            let current = selectCoords();
            let arrPoints = [
                parseInt(current.x), 
                parseInt(current.y), 
                parseInt(current.x) + parseInt(current.w), 
                parseInt(current.y) + parseInt(current.h)
            ];
            jcrop_api.setSelect(arrPoints);
        }
        $callBackFn
        ";
        $css = "
            .jcrop-holder{ margin: 0 auto; }
        ";
        $view = $this->getView();
        $view->registerJs($script, View::POS_END, $id);
        $view->registerCss($css);
    }

    public function registerTranslations()
    {
        $fileMap = $this->getMessageFileMap();
        $i18n = Yii::$app->i18n;
        $i18n->translations['widgets/'.self::$widget_name.'/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            //'sourceLanguage' => 'en-US',
            'basePath' => '@app/widgets/'.self::$widget_name.'/messages',
            'fileMap' => $fileMap,
        ];
    }
    
    private function getMessageFileMap(){
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
                            $key = "widgets/" . self::$widget_name . "/" . $file;
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
        return Yii::t('widgets/'.self::$widget_name.'/' . $category, $message, $params, $language);
    }
}
