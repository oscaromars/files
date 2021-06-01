<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Workload") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('carga_horaria-search', [  
            'arr_tipo' => $mod_tipo,
            'arr_semestre' => $mod_semestre,
            ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('carga_horaria-grid', [
        'model' => $model,
        ]);
    ?>
</div>