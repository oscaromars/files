<?php

use yii\helpers\Html;
$this->title = Yii::t('app', 'Resumen de Planificacion');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formacademicoestudiante', [
            'arr_periodo' => $arr_periodo,
            'arr_unidad' => $arr_unidad,
            'arr_modalidad' => $arr_modalidad,
            'arr_carrera' => $arr_carrera,
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('academicoestudiante-grid', [
        'model' => $model,
    ]);
    ?>
</div> 