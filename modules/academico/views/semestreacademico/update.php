<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

$this->title = 'Actualizar Semestre ';
$this->params['breadcrumbs'][] = ['label' => 'Semestre', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->saca_id, 'url' => ['view', 'id' => $model->saca_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rol-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
