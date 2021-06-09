<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('new-search', [ 
            'arr_carrera' => $arr_carrera,
            'arr_pla_per' =>  $arr_pla_per,
            'arr_modalidad' =>   $arr_modalidad,
            'arr_status' =>  $arr_status,
            ]);
        ?>
    </form>
</div>

<div>
    <?=
    $this->render('new-grid', [
        'model' => $model,
        ]);
    ?>
</div>