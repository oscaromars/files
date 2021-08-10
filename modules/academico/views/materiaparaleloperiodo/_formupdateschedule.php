<?php

use app\modules\academico\Module as academico;
use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
academico::registerTranslations();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$data = array();
$j = 0;
for ($i = $model->mpp_num_paralelo+1; $i <= 20; $i++) {
    array_push($data, $i);
    $j++;
}
?>
<?= Html::hiddenInput('mpp_num_paralelo',    $model->mpp_num_paralelo, ['id' => 'mpp_num_paralelo']); ?>
<?= Html::hiddenInput('mod_id',    $model->mod_id, ['id' => 'mod_id']); ?>
<?= Html::hiddenInput('paca_id',   $model->paca_id, ['id' => 'paca_id']); ?>
<?= Html::hiddenInput('asi_id',    $model->asi_id, ['id' => 'asi_id']); ?>
<form class="form-horizontal">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
            <div class="form-group">
                <label for="cmb_asignatura" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Asignatura") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <h5>  <?= $model->asig->asi_nombre ?>  </h5>
                </div>

            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
            <div class="form-group">
                <label for="cmb_asignatura" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Modalidad") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <h5>  <?= $model->mod->mod_nombre ?>  </h5>
                </div>

            </div>
        </div>
    </div>
    <div>
    <?=
    PbGridView::widget([
        'id' => 'TbgParalelohorario',
        'dataProvider' => $paralelohorario,
        'columns' => [
            [
                'attribute' => 'Nombre',
                'header' => academico::t("Academico", "Parallel"),
                'value' => 'mpp_num_paralelo',
            ],
            [
                'attribute' => 'Horario',
                'header' => academico::t("Academico", "Schedule"),
                'value' => 'daho_descripcion',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{actualizar_horario}',
                'buttons' => [
                    'actualizar_horario' => function ($url, $paralelohorario) {
                          return Html::a('<span class="glyphicon glyphicon-edit"></span>', Url::to(['materiaparaleloperiodo/viewhorario', 'popup' => "true", 'mpp_id' => $paralelohorario['mpp_id'], 'uaca_id' => 1, 'mod_id' => $paralelohorario['mod_id']]), ["class" => "pbpopup", "data-toggle" => "tooltip", "title" => "Ver Pagos", "data-pjax" => 0]);
                    },
                ],
            ],
        ],
    ])
    ?>
</div>
</form>