<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_form_Mallas', [
            'mallaca' => $mallaca, 
            'arr_modalidad' => $arr_modalidad,   
            'carrera' => $carrera,         
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('reportemallas', [
        'dataProvider' => $dataProvider,
    ]);
    ?>
</div>