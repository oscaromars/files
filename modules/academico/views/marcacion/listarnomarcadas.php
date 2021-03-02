<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formnomarcadas', [
            'arr_periodo' => $arr_periodo,   
            'arr_unidad' => $arr_unidad,            
            'arr_modalidad' => $arr_modalidad,  
            'arr_tipo' => $arr_tipo,
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('_listarnomarcadas-grid', [
        'model' => $model,
    ]);
    ?>
</div>