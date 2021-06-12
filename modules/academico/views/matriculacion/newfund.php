<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('newfund-search', [ 
            'arr_periodo' => $mod_periodo,
    
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