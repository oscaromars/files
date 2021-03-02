<?php

use yii\helpers\Html;
use app\modules\repositorio\Module as repositorio;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_titulo"><?= repositorio::t("repositorio", "List Repository of Evidence") ?></span></h3>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [
            'arr_modelo' => $arr_modelo,
            'arr_componente' => $arr_componente,
            'arr_categoria' => $arr_categoria,
            'arr_estandar' => $arr_estandar,      
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