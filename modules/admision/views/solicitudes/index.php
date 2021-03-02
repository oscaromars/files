<?php

use yii\helpers\Html;
use app\modules\admision\Module as admision;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_Personeria"><?= admision::t("Solicitudes", "List Requests") ?></span></h3>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [
            'arrCarreras' => $arrCarreras,
            'arrEstados' => $arrEstados,
            'arr_ninteres' => $arr_ninteres,
            'arr_modalidad' => $arr_modalidad,
            'arr_carrerra1' => $arr_carrerra1]);
        ?>
    </form>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <?=
    $this->render('index-grid', [
        'model' => $model,
        'url' => $url]);
    ?>
</div>
