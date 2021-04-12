<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>

<div class="row">
    <form class="form-horizontal">        
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="txt_boxgrid" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("accion", "Search") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <?= 
                        PbSearchBox::widget([
                            'boxId' => 'boxgrid',
                            'type' => 'searchBoxList',
                            'placeHolder' => financiero::t("catalogo", "Code or Name Account"),
                            'controller' => Url::base() . '/' . Yii::$app->controller->module->id . '/catalogo/index?aui=true',
                            'callbackListSelected' => 'getData',
                        ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="txt_search" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= financiero::t("catalogo", "Name Account") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="" id="txt_search" placeholder="<?= financiero::t("catalogo", "Name Account") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_buscarData" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
            </div>
        </div>
    </form>
</div>
<br />
<?=
    $this->render('index-grid', ['model' => $model,]);
?>