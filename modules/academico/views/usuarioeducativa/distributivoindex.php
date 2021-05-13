<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formBuscardistributivoindex', [
          
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('distributivoindex-grid', [
        'model' => $model,
    ]);
    ?>
</div>