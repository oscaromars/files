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
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        /* 'dataProvider' => $model, */
        'dataProvider' => $matDataProvider,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Code',
                'header' => Academico::t("matriculacion", "Subject Code"),
                'value' => 'Code',
            ],     
            [
                'attribute' => 'Subject',
                'header' => Academico::t("matriculacion", "Subject"),
                'value' => 'Subject',
            ],
            [
                'attribute' => 'Block',
                'header' => Academico::t("matriculacion", "Block"),
                'value' => 'Block',
            ],
            [
                'attribute' => 'Hour',
                'header' => Academico::t("matriculacion", "Hour"),
                'value' => 'Hour',
            ],
            [
                'attribute' => 'Cost',
                'header' => Academico::t("matriculacion", "Cost"),
                'value' => 'Cost',
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