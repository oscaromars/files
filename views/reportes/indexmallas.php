<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_form_Mallas', [
            'arr_unidad' => $arr_unidad,
            'arr_modalidad' => $arr_modalidad,   
            'arr_carrera' => $arr_carrera,
            'arr_malla' => $arr_malla,         
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('reportemallas', [
        'model' => $model,
    ]);
    ?>
</div>