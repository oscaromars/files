<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('listarestudiantespago_search', [ 
            'arr_unidad' => $mod_unidad,
            'arr_modalidad' => $mod_modalidad,
            'arr_periodo' => $mod_periodo,
            'arr_asignatura' => $mod_asignatura,
            'arr_estado' => $mod_estado,
            'arr_jornada' => $mod_jornada,
            ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('_listarestudiantespagogrid', [
        'model' => $model,
        ]);
    ?>
</div>