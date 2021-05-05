<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formBuscarusuarioindex', [
            //'arr_periodoAcademico' => $arr_periodoAcademico, 
            //'arr_asignatura' => $arr_asignatura,           
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('usuarioindex-grid', [
        'model' => $model,
    ]);
    ?>
</div>