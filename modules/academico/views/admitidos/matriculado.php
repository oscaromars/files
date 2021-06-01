<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Enrolled Postgraduate") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('matriculado-search', [         
            'arr_ninteres' => $arr_ninteres,
            'arr_modalidad' => $arr_modalidad,
            'arr_carrerra1' => $arr_carrerra1]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('matriculado-grid', [
        'model' => $model,
        'url' => $url]);
    ?>
</div>