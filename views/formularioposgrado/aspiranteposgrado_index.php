<?php

use yii\helpers\Html;
use app\widgets\PbSearchBox\PbSearchBox;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('aspiranteposgrado_search', [
            'arr_unidad' => $arr_unidad,
            'arr_programa' => $arr_programa,
            'arr_modalidad' => $arr_modalidad,
            ]);
        ?>
    </form>
</div>

<div>
    <?=
    $this->render('_aspiranteposgradogrid', [
        'model' => $model,
        ]);
    ?>
</div>