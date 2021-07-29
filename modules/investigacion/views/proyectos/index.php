<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\investigacion\Module as investigacion;

investigacion::registerTranslations();

?>
<div>
    <h3><?= investigacion::t("proyecto", "Administration - Type of Project") ?></h3>
    <br></br>
<?=
    $this->render('index-grid', [
        'model' => $model,
    ]);
?>
</div>