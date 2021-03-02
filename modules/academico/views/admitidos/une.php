<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "List UNE letters") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('une-search', [
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('une-grid', [
        'model' => $model,
        'url' => $ur]);
    ?>
</div>