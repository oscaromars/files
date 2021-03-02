<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "List parallels") ?></span></h3>
</div>

<div>    
</div>
<div>
    <?=
    $this->render('_listarParaleloGrid', [
        'mod_paralelo' => $mod_paralelo,
        'periodo' => $periodo,
        'url' => $url]);
    ?>
</div>


