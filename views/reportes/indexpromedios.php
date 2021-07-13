<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_form_reportepromedio', [
            'arr_estudiante' => $arr_estudiante,        
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('promedios', [
        'model' => $model,
    ]);
    ?>
</div>