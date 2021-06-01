<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as aspirante;
use app\modules\academico\Module as certificados;

aspirante::registerTranslations();
admision::registerTranslations();
certificados::registerTranslations();

?>
<?= Html::hiddenInput('txth_per_id', base64_encode($personalData['per_id']), ['id' => 'txth_per_id']); ?>

<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <form class="form-horizontal">
        <?=
        $this->render('_index-search_pendiente', [                       
            'arr_unidad' => $arr_unidad,
            'arr_modalidad' => $arr_modalidad,            
            ]);
        ?>
    </form>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <?=
    $this->render('_index-grid_pendiente', [
        'model' => $model,
        //'url' => $url,         
        ]);
    ?>
</div>

