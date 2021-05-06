<?php

use yii\helpers\Html;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formBuscarusuarioindex', [
          
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('usuarioindex-grid', [
        'model' => $model,
    ]);
    ?>
</div>