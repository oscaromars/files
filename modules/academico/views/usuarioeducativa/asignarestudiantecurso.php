<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('asignarestudiantecurso_search', [ 
            'arr_unidad' => $mod_unidad,
            'arr_modalidad' => $mod_modalidad,
            'arr_periodo' => $mod_periodo,
            'arr_asignatura' => $mod_asignatura,
            'arr_curso' => $arr_curso, 
            'arr_estado' => $mod_estado,            
            ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('_asignarestudiantecursogrid', [
        'model' => $model,
        ]);
    ?>
</div>