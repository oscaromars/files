<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('index-paralelosearch', [
            "model" => $model,
            "data_promo" => $data_promo,
        ]);
        ?>
    </form>
</div>
<div>       
</div>