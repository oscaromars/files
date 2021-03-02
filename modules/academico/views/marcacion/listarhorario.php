<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formhorario', [
            'arr_periodo' => $arr_periodo,            
            'arr_unidad' => $arr_unidad,            
            'arr_modalidad' => $arr_modalidad,            
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('_listarhorario-grid', [
        'model' => $model,
    ]);
    ?>
</div>