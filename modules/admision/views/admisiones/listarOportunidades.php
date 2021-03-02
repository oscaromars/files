<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use yii\data\ArrayDataProvider;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\modules\admision\Module;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_evaluar"><?= Module::t("crm", "Sale Opportunities") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formBuscarOportunidades', [
            'arr_estgestion' => $arr_estgestion,
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('_listarOportunidadesGrid', [
        'model' => $model,
    ]);
    ?>
</div>