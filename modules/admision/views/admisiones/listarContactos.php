<?php

use yii\helpers\Html;
use app\modules\admision\Module;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_evaluar"><?= Module::t("crm", "Contacts") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formBuscarContactos', [            
            'arr_contacto' => $arr_contacto,
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('_listarContactosGrid', [
        'model' => $model,
    ]);
    ?>
</div>
