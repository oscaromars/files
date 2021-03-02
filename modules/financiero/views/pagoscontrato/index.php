<?php

use yii\helpers\Html;
use app\modules\financiero\Module as financiero;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= financiero::t("Pagos", "Payment Contract") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [         
            'arr_ninteres' => $arr_ninteres,
            'arr_modalidad' => $arr_modalidad,
            'arr_carrerra1' => $arr_carrerra1]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('index-grid', [
        'model' => $model,
        'url' => $url]);
    ?>
</div>