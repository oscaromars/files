<?php

use yii\helpers\Html;
use yii\helpers\Url;


?>
<?= Html::hiddenInput('txth_pids', base64_decode($_GET['per_id']), ['id' => 'txth_pids']); ?>
<?= Html::hiddenInput('txth_perids', $_GET['per_id'], ['id' => 'txth_perids']); ?>

<div>
    

    <form class="form-horizontal">
        <?=
        $this->render('_formHistoricoEstudiante', [
            'arr_persona' => $arr_persona,
            'arr_ninteres' => $arr_ninteres,   
            'arr_modalidad' => $arr_modalidad,
            'arr_carrerra1' => $arr_carrerra1,
            
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('historicoestudiante-index-grid', [
        'model' => $model,
    ]);
    ?>
</div> 
