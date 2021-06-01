<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Promotion Program") ?>  </span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [
            'arr_programa1' => $arr_programa1,
            'arr_unidad' => $arr_unidad,
            'arr_modalidad' => $arr_modalidad
        ]);
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