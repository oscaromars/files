<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

$this->title = 'Actualizar Horario ';
$this->params['breadcrumbs'][] = ['label' => 'Horario', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->daho_id, 'url' => ['view', 'id' => $model->daho_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rol-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<input type="hidden" id="frm_daho_id" value="<?= $model->daho_id ?>">