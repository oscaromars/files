<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */


$this->params['breadcrumbs'][] = ['label' => 'distributivohorarioparalelo', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dhpa_id, 'url' => ['view', 'dhpa_id' => $model->dhpa_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="distributivohorarioparalelo-update">

    <h3 align="center" ><?= Html::encode($this->title) ?></h3>

    <?= $this->render('create', [
        'model' => $model,
    ]) ?>

</div>
