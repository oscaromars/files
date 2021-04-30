Yii Search Autocomplete Extension
=================
Extension de Search Autocomplete creada por PenBlu

Search Autocomplete is a library creade to use in Yii Projects

Reference Plugin: https://github.com/iranianpep/ajax-live-search


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist penblu/searchautocomplete "*"
```

or add

```
"penblu/searchautocomplete": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```
CONTROLLER: 

public function actionTest(){
    $data = Yii::$app->request->post();
    if($data['ls_query_id'] == "autocomplete-test"){
        $query = $data['ls_query'];
        $con = Yii::$app->db_gfinanciero;
        $table = "COSCENTRO";
        $cols = [
            'COD_CEN', 
            'NOM_CEN'];
        $aliasCols = [
            'Id', 
            financiero::t('centro', 'Name')];
        $where = "EST_LOG = 1 and EST_DEL = 1";
        $order = "COD_CEN DESC";
        $limitPages = 20;
        $currentPage = $data['ls_current_page'];
        $perPage = $data['ls_items_per_page'];
        return app\vendor\penblu\searchautocomplete\SearchAutocomplete::renderView($query, $con, $cols, $aliasCols, $table, $where, $order, $limitPages, $currentPage, $perPage);
    }
    return $this->render('test', [
        
    ]);
}


VIEW:

<?php

use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use yii\helpers\Url;

$token = SearchAutocomplete::getToken();
?>
<?=
    SearchAutocomplete::widget([
        'containerId' => 'test',
        'token' => $token,
        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/test/test'
    ]);

?>
```
