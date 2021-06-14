<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_registro_list',
        'showExport' => false,
        'dataProvider' => $pagosDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],     
            [
                'attribute' => 'Pago',
                'header' => Academico::t("matriculacion", "Payment"),
                'value' => "0",
            ],
            [
                'attribute' => 'Vencimiento',
                'header' => Academico::t("matriculacion", "Expiration Date"),
                'value' => "1",
            ],
            [
                'attribute' => 'Porcentaje',
                'header' => Academico::t("matriculacion", "Percentage"),
                'value' => "2",
            ],
            [
                'attribute' => 'Forma de Pago Actual',
                'header' => Academico::t("matriculacion", "Unit Cost"),
                'value' => "3",
            ],
        ]
    ])
?>

<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
    <div class="form-group">
        <label for="lbl_id" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Academico::t("matriculacion", "Total") ?>: </label>
         <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <span>$<?= $valor_total ?></span>
        </div>
    </div>
</div> 