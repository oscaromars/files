<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formIndex', [
            
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