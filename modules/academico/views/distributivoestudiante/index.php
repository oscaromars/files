<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [ 
            'unidad' => $unidad,
            'modalidad' => $modalidad,
            'periodo' => $periodo,
            'materia' => $materia,
            'jornada' => $jornada,
            'horario' => $horario,
            'profesor' => $profesor,
            ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('index-grid', [
        'model' => $model,
        ]);
    ?>
</div>
<?= Html::hiddenInput('txth_ids', $daca_id, ['id' => 'txth_ids']); ?>