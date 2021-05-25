<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

$this->title = 'Actualizar Periodo Academico ';
$this->params['breadcrumbs'][] = ['label' => 'Periodo Academico', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->paca_id, 'url' => ['view', 'id' => $model->paca_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rol-update">

    <h2 align="center"><?= Html::encode($this->title) ?></h2>
    <br/>
    <br/>
   
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
 

