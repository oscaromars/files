<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [
            'arr_empresa' => $arr_empresa,
            'arr_tipo_bien' => $arr_tipo_bien,
            'arr_categoria' => $arr_categoria,
            'arr_departamento' => $arr_departamento,
            'arr_area' => $arr_area,
             ]);             
        ?>
    </form>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <?=
    $this->render('index-grid', [
        'model' => $model,
        ]);
    ?>
</div>