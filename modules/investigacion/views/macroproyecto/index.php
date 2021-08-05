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
    <h3><?= investigacion::t("macroproyecto", "Administration - Macroproject") ?></h3>
    <br></br>
</div>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formIndex', [
            'arr_linv' => $arr_linv,
        ]);
        ?>
    </form>
</div>

<div>
<?=
    $this->render('index-grid', [
        'model' => $model,
    ]);
?>
</div>