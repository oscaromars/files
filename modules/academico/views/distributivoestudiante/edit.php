<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('edit-search', [ 
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
    $this->render('edit-grid', [
        'model' => $model,
        ]);
    ?>
</div>

<?= Html::hiddenInput('txth_ids', $daca_id, ['id' => 'txth_ids']); ?>
<?= Html::hiddenInput('txth_uids', $uaca_id, ['id' => 'txth_uids']); ?>
<?= Html::hiddenInput('txth_esid', '', ['id' => 'txth_esid']); ?>