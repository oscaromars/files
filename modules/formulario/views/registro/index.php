<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\widgets\PbSearchBox\PbSearchBox;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>

<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [                       
            'arr_unidad' => $arr_unidad,
            'arr_carrera_prog' => $arr_carrera_prog,            
            ]);
        ?>
    </form>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <?=
    $this->render('index-grid', [
        'model' => $model,             
        ]);
    ?>
</div>