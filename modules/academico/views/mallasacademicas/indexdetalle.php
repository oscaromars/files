<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formIndexdetalle', [
            'arr_cabecera' => $cabecera,            
        ]);
        ?>
    </form>
</div>
 <div>
    <?=
    $this->render('indexdetalle-grid', [
        'model' => $model,
        'malla' => $malla,
    ]);
    ?>
</div>