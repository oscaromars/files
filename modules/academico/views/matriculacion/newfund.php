<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('newfund-search', [ 
            'arr_pla' => $mod_pla,
            //'arr_estado_matricula' => $arr_estado_matricula,
            ]);
        ?>
    </form>
</div>

<div>
    <?=
    $this->render('newfund-grid', [
        'model' => $model,
        ]);
    ?>
</div>