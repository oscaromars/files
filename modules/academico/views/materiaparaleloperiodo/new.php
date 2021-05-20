<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Semestre', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semestreacademico-create">
    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>