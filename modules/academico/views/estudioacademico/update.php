<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

$this->title = 'Actualizar Estudio Académico ';
$this->params['breadcrumbs'][] = ['label' => 'Estudio', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->eaca_id, 'url' => ['view', 'id' => $model->eaca_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rol-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<input type="hidden" id="frm_eaca_id" value="<?= $model->eaca_id ?>">
