<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formBuscarindexunidad', [
            'arr_periodoAcademico' => $arr_periodoAcademico, 
            'arr_asignatura' => $arr_asignatura, 
            'arr_curso' => $arr_curso,           
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('indexunidad-grid', [
        'model' => $model,
    ]);
    ?>
</div>