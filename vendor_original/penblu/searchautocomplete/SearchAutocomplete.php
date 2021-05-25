<?php

namespace app\vendor\penblu\searchautocomplete;

use Yii;
use app\vendor\penblu\searchautocomplete\SearchAutocompleteAsset;
use Exception;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * This is just an example.
 */
class SearchAutocomplete extends \yii\base\Widget
{
    private static $widget_name = "SearchAutocomplete";
    public $containerId = "";
    public $url = NULL; // controller url to get data by ajax
    public $token = "";
    public $max_input = 20;
    public $con = NULL;
    public $columns = ""; // {'id', 'name'}
    public $aliasCols = "";
    public $table = "";
    public $where = "";
    public $order = "";
    public $callback = NULL;
    public $colDefault = 0;
    public $limit = 20;
    public $defaultValue = "";
    public $htmlOptions = [];


    public function init() {
        parent::init();
        SearchAutocompleteAsset::register($this->getView());
    }

    public function run()
    {
        $id = $this->getId();
        $this->registerTranslations();
        $this->htmlOptions['id'] = 'autocomplete-' . ((isset($this->containerId))?($this->containerId):$id);
        $this->containerId = $this->htmlOptions['id'];
        $this->htmlOptions['class'] = (isset($this->htmlOptions['class']))?($this->htmlOptions['class'] . " pb-src-autocomplete form-control"):"pb-src-autocomplete form-control";
        $this->htmlOptions['placeholder'] = self::t('autocomplete','Type to search ...');
        $this->htmlOptions['data-value'] = $this->defaultValue;
        echo $this->render('index', [
            "id" => $this->htmlOptions['id'],
            "htmlOptions" => $this->htmlOptions,
            "defaultValue" => $this->defaultValue,
        ]);
        $this->registerClientScript($this->containerId);
        parent::run();
    }

    public function registerClientScript($id) {
        $vars = "";
        $fncall = "";
        if(isset($this->url)){
            $vars .= "url: '" . $this->url . "',
            ";
        }
        if(isset($this->callback)){
            $vars .= "fncallback: '" . $this->callback . "',
            ";
            $fncall = $this->callback . "(arr);";
        }
        $script =
        "
        jQuery('#". $id ."').ajaxlivesearch({
            loaded_at: " . time() . ",
            token: '" . $this->token . "',
            max_input: " . $this->max_input . ",
            " . $vars . "
            onResultClick: function(e, data) {
                // get the index 0 (first column) value
                var selectedOne = jQuery(data.selected).find('td').eq('".$this->colDefault."').text();
                let arr = new Array();
                var objSelect = jQuery(data.selected).find('td').each(function() {
                    arr.push($(this).text());
                });
                // set the input value
                jQuery('#". $id ."').val(selectedOne);
    
                // hide the result
                jQuery('#". $id ."').trigger('ajaxlivesearch:hide_result');
                
                ".$fncall."
            },
            onResultEnter: function(e, data) {
                // do whatever you want
                // jQuery('#". $id ."').trigger('ajaxlivesearch:search', {query: 'test'});
            },
            onAjaxComplete: function(e, data) {
    
            }
        });
        ";
        
        $view = $this->getView();
        $view->registerJs($script, View::POS_END, $id);
    }

    /**
     * @param   string $search   Text to search
     * @param   mixed $con   DB Conection. Example: Yii::$app->db
     * @param   mixed $columns  Columns to Select query to search. Example: ['c_id', 'c_name', 'c_value']
     * @param   mixed $aliasCols    Alias Columns to Select query to search. Example: ['id', 'name', 'value']
     * @param   string $table   Table Name to search data. Example: "Country"
     * @param   string $where   Query where to filter data. Example: "estado = 1 AND logic = 1"
     * @param   string $order   Query order to order data. Exmplae: "id DESC, value ASC"
     * @param   int $limitPage  Limit to records per page
     * @param   string $currentPage  Current Page number
     * @param   string $perPage  Per Page number
     * @throws \Exception
     * @return array
     */
    public static function getData($search, $con, $columns, $aliasCols, $table, $where = NULL, $order = NULL, $limitPage = 20, $currentPage, $perPage){
        try{
            // get connection
            $cols = "";
            $str_search = "";
            $search_cond = "%".$search."%";
            $start = 0;
            $limit = $limitPage;
            $where = (isset($where))?" AND $where":"";
            $order = (isset($order))?" ORDER BY $order ":"";
            $paramLbl = "param";

            for($i=0; $i<count($columns); $i++){
                $param = $paramLbl . $i;
                if(($i + 1) == count($columns)){
                    $cols .= $columns[$i] . " AS " . $aliasCols[$i];
                    $str_search .= $columns[$i] . " LIKE :".$param." ";
                }else{
                    $cols .= $columns[$i] . " AS " . $aliasCols[$i] . ", ";
                    $str_search .= $columns[$i] . " LIKE :".$param." OR ";
                }
            }

            $sql = "SELECT 
                        COUNT(*) AS CANT 
                    FROM 
                        ".$con->dbname.".".$table."
                    WHERE 
                        ($str_search)
                        $where
                    $order;";
            
            $comando = $con->createCommand($sql);
            for($i=0; $i<count($columns); $i++){
                $param = $paramLbl . $i;
                $comando->bindParam(":".$param, $search_cond, \PDO::PARAM_STR);
            }
            //Utilities::putMessageLogFile($comando->getRawSql());
            $result = $comando->queryOne();
            $resultNumber = (int) $result['CANT'];

            // initialize variables
            $HTML = '';
            $pagesNumber = 1;

            if (!empty($resultNumber) && $resultNumber !== 0) {
                if ($perPage == 0) {
                    $limit = $resultNumber;
                } else {
                    if ($resultNumber < $perPage) {
                        $pagesNumber = 1;
                    } elseif ($resultNumber > $perPage) {
                        if ($resultNumber % $perPage === 0) {
                            $pagesNumber = floor($resultNumber / $perPage);
                        } else {
                            $pagesNumber = floor($resultNumber / $perPage) + 1;
                        }
                    } else {
                        $pagesNumber = $resultNumber / $perPage;
                    }
                    $limit = $perPage;
                    $start = ($currentPage > 0) ? ($currentPage - 1) * $perPage : 0;
                }

                $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".".$table."
                WHERE 
                    ($str_search)
                    $where
                $order
                LIMIT $start, $limit;";
                
                $comando = $con->createCommand($sql);
                for($i=0; $i<count($columns); $i++){
                    $param = $paramLbl . $i;
                    $comando->bindParam(":".$param, $search_cond, \PDO::PARAM_STR);
                }
                //Utilities::putMessageLogFile($comando->getRawSql());
                $rows = $comando->queryAll();
                
                // if requested, generate column headers
                $headers = $aliasCols;

            } else {
                $headers = [];
                $rows = [];
            }

            // form the return
            return [
                'headers'           => $headers,
                'rows'              => $rows,
                'number_of_results' => (int) $resultNumber,
                'total_pages'       => $pagesNumber,
            ];
        }catch(Exception $ex){
            //Utilities::putMessageLogFile($ex->getMessage());
            return NULL;
        }
    }

    /**
     * @param   string $search   Text to search
     * @param   mixed $con   DB Conection. Example: Yii::$app->db
     * @param   mixed $columns  Columns to Select query to search. Example: ['c_id', 'c_name', 'c_value']
     * @param   mixed $aliasCols    Alias Columns to Select query to search. Example: ['id', 'name', 'value']
     * @param   mixed $colsVisible    Columns to show query to search. Example: ['id', 'name', 'value']
     * @param   string $table   Table Name to search data. Example: "Country"
     * @param   string $where   Query where to filter data. Example: "estado = 1 AND logic = 1"
     * @param   string $order   Query order to order data. Exmplae: "id DESC, value ASC"
     * @param   int $limitPage  Limit to records per page
     * @param   string $currentPage  Current Page number
     * @param   string $perPage  Per Page number
     *
     * @return array
     * @throws \Exception
     */
    public static function renderView($search, $con, $columns, $aliasCols, $colsVisible, $table, $where = NULL, $order = NULL, $limitPage = 20, $currentPage, $perPage)
    {
        $result = self::getData($search, $con, $columns, $aliasCols, $table, $where, $order, $limitPage, $currentPage, $perPage);
        $response = [];
        $status = "failed";
        $message = self::t('autocomplete','Failed Request');
        if(isset($result)){
            $headers = $result['headers'];
            $rows = $result['rows'];
            //$view = Yii::$app->getView();
            $html = \Yii::$app->view->renderFile(Yii::getAlias('@vendor') . "/penblu/" . strtolower(self::$widget_name) . "/views/default.php", [
                "query" => $search,
                "headers" => $headers,
                "colsVisible" => $colsVisible,
                "rows" => $rows,
                "noResult" => self::t('autocomplete','There is no result for'),
            ]);

            $response = [
                'html' => $html,
                'number_of_results' => $result['number_of_results'],
                'total_pages'       => $result['total_pages'],
            ];
            $status = "success";
            $message = self::t('autocomplete','Successful Request');
        }
        
        $cssClass = ($status === 'failed') ? 'error' : 'success';
        $message = "<tr><td class='$cssClass'>$message</td></tr>";
        return json_encode([
            'status'  => $status,
            'message' => $message,
            'result'  => $response
        ]);
    }

    /**
     * returns a 32 bits token and resets the old token if exists
     *
     * @return string
     */
    public static function getToken()
    {
        // create a form token to protect against CSRF
        return $_SESSION['ls_session']['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
    /**
     * @return string
     */
    public static function getJavascriptAntiBot()
    {
        return $_SESSION['ls_session']['anti_bot'] = 'ajaxlivesearch_guard';
    }

    /**
     * receives a posted variable and checks it against the same one in the session
     *
     * @param  $sessionParameter
     * @param  $sessionValue
     * @return bool
     */
    public static function verifySessionValue($sessionParameter, $sessionValue)
    {
        $whiteList = ['token', 'anti_bot'];

        if (in_array($sessionParameter, $whiteList) && isset($_SESSION['ls_session']) &&
            $_SESSION['ls_session'][$sessionParameter] === $sessionValue
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Calculate the timestamp difference between the time page is loaded
     * and the time searching is started for the first time in seconds
     *
     * @param  $pageLoadedAt
     * @return bool
     */
    public static function verifyBotSearched($pageLoadedAt)
    {
        // if searching starts less than start time offset it seems it's a Bot
        return (time() - $pageLoadedAt < 2) ? false : true;
    }

    public function registerTranslations()
    {
        $fileMap = $this->getMessageFileMap();
        $i18n = Yii::$app->i18n;
        $i18n->translations['vendor/penblu/'.strtolower(self::$widget_name).'/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            //'sourceLanguage' => 'en-US',
            'basePath' => '@app/vendor/penblu/'.strtolower(self::$widget_name).'/messages',
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
                            $key = "vendor/penblu/" . strtolower(self::$widget_name) . "/" . $file;
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
        return Yii::t('vendor/penblu/'. strtolower(self::$widget_name) .'/' . $category, $message, $params, $language);
    }
}
