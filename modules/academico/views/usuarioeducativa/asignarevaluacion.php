<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_asignarevaluacion-search', [ 
            'arr_modalidad' => $arr_modalidad,
            'arr_periodo' => $arr_periodo,
            'arr_aula' => $arr_aula,
            'arr_unidadeduc' => $arr_unidadeduc,
            'arr_evaluacion' => $arr_evaluacion
            ]);
        ?>
    </form>
</div>

<div>
    <?=
    $this->render('_asignarevaluacion-grid', [
        'model' => $model,
        ]);
    ?>
</div>