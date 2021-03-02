<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as aspirante;

aspirante::registerTranslations();
admision::registerTranslations();

?>

<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <form class="form-horizontal">
        <?=
        $this->render('_index-search_revisionpago', [                       
            'arr_unidad' => $arr_unidad,
            'arr_modalidad' => $arr_modalidad,   
            'arr_estado' => $arr_estado,
            'arr_estado_financiero' => $arr_estado_financiero,
            ]);
        ?>
    </form>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <?=
    $this->render('_index-grid_revisionpago', [
        'model' => $model,       
        ]);
    ?>
</div>

