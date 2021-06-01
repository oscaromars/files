<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

$this->title = 'Actualizar Estudio Academico ';
$this->params['breadcrumbs'][] = ['label' => 'Estudio Academico', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->eaca_id, 'url' => ['view', 'id' => $model->eaca_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rol-update">

    <h3 align="center"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
 

