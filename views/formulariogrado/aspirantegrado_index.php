<?php

use yii\helpers\Html;
use app\widgets\PbSearchBox\PbSearchBox;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('aspirantegrado_search', [ 
            'arr_periodo' => $arr_periodo,
            'arr_unidad' => $arr_unidad,
            'arr_carrera' => $arr_carrera,
            'arr_modalidad' => $arr_modalidad,
            ]);
        ?>
    </form>
</div>

<div>
    <?=
    $this->render('_aspirantegradogrid', [
        'model' => $model,
        ]);
    ?>
</div>