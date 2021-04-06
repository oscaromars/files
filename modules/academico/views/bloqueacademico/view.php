<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

$this->title = $model->baca_descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Semestre', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="rol-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->baca_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Eliminar', ['delete', 'id' => $model->baca_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea eliminar el registro?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'baca_nombre',
            'baca_descripcion',
            'baca_anio',
        ],
    ])
    ?>

</div>
