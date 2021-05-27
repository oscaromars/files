<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formBuscarContactos', [
            'arr_contacto' => $arr_contacto,
            'arr_canalconta' => $arr_canalconta,
            'arra_agente' => $arra_agente,
            'arr_empresa' => $arr_empresa,
            'arr_unidad' => $arr_unidad,
            'arr_gestion' => $arr_estado_gestion,
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('index-grid', [
        'model' => $model,
    ]);
    ?>
</div>