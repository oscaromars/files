<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('asignar-search', [ 
            'arr_unidad' => $mod_unidad,
            'arr_modalidad' => $mod_modalidad,
            'arr_periodo' => $mod_periodo,
            'arr_materias' => $mod_materias,
            'arr_jornada' => $mod_jornada,
            ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('asignar-grid', [
        'model' => $model,
        ]);
    ?>
</div>