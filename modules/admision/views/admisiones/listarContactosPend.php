<?php

use yii\helpers\Html;
use app\modules\admision\Module;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
    <h3><span id="lbl_evaluar"><?= Module::t("crm", "Provisional Contacts") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formBuscarContactosPend');
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('_listarContactosPendGrid', [
        'model' => $model,
    ]);
    ?>
</div>