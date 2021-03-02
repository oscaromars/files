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
use app\modules\financiero\Module as financiero;
?>

<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= financiero::t("Pagos", "Registration Payments for Collections") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_form_Buscarindexdm', [
            ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('_indexadm_grid', [
        'model' => $model,
        'url' => $url]);
    ?>
</div>