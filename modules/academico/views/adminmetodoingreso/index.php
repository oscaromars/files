<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Períodos Académicos por Método de Ingreso") ?></span></h3>
</div>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [
            ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('index-grid', [
        'mod_periodo' => $mod_periodo,
        'url' => $url]);
    ?>
</div>


