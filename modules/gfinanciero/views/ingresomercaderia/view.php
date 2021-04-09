<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;
use yii\data\ArrayDataProvider;

financiero::registerTranslations();

?>

<form class="form-horizontal">
    <h3 class="page-header"><?= financiero::t("ingresomercaderia", "Entry") ?></h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="cmb_tipo" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Entry Type") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_tipo",$tipo, $arr_tipo, ["class" => "form-control", "disabled" => "disabled", "id" => "cmb_tipo",]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_fingreso" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Entry Date") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?=
                        DatePicker::widget([
                            'name' => 'frm_fingreso',
                            'value' => date(Yii::$app->params["dateByDefault"], strtotime($model->FEC_ING)),
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => ["class" => "form-control PBvalidation", "id" => "frm_fingreso", "disabled" => "disabled", "data-type" => "fecha", "placeholder" => financiero::t("articulo", "Expires")],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => Yii::$app->params["dateByDatePicker"],
                            ]]
                        );
                        ?>  
                </div>
            </div>
        </div>
    </div>
    <h3 class="page-header"><?= financiero::t("bodega", "Cellar") ?></h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="autocomplete-bodorig" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Cellar Origin") ?> </label>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <input type="text" class="form-control PBvalidations" value="<?= $model->COD_BOD ?>" id="autocomplete-bodorig" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Cellar Origin")?>" />
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <input type="text" class="form-control PBvalidations" value="<?= $model->NUM_ING ?>" id="frm_bodorigsec" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Cellar Origin Sec.")?>" />
                </div>
            </div>
            <div class="form-group">
                <label for="autocomplete-bodorigl" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">&nbsp;</label>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="text" class="form-control PBvalidations" value="<?= $nomBodOrg ?>" id="frm_bodorignom" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Cellar Origin") ?>" />
                </div>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                    <input type="text" class="form-control PBvalidations" value="<?= $dirBodOrg ?>" id="frm_bodorigdir" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Cellar Origin Address") ?>" />
                </div>
            </div>
        </div>
        
    </div>
    <h3 class="page-header"><?= financiero::t("ingresomercaderia", "Reference") ?></h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="autocomplete-cliente" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Provider") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                    <input type="text" class="form-control PBvalidations" value="<?= $model->COD_PRO ?>" id="autocomplete-cliente" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Client")?>" />
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="text" class="form-control PBvalidations" value="<?= $model->NOM_PRO ?>" id="frm_clientename" data-type="all" disabled="disabled" placeholder="<?= financiero::t("cliente", "Client Name") ?>"/>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h2 class="text-center">
                <span class="label label-<?= ($model->IND_UPD == 'L')?"success":"danger" ?>"><?= ($model->IND_UPD == 'L')?(financiero::t('ingresomercaderia', 'Liquidated')):(financiero::t('ingresomercaderia', 'Invalidated')) ?></span>
            </h2>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="col-sm-1 col-md-1 col-xs-1 col-lg-1"></div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2"></div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2"></div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2"></div>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3"></div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2 <?= ($model->IND_UPD == 'A')?'hidden':''  ?>" >
                    <a id="btn_anular" href="javascript:" class="btn btn-danger btn-block pull-right"> <?= financiero::t("ingresomercaderia", "Invalidate") ?></a>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="frm_cod_bod" value="<?= $model->COD_BOD ?>" />
    <input type="hidden" id="frm_type_in" value="<?= $model->TIP_ING ?>" />
    <input type="hidden" id="frm_cod_sec" value="<?= $model->NUM_ING ?>" />
    <?=
    $this->render('view-grid', [
        'model' => $modelDetalle,
    ]);
    ?>
    <br />
    <?=
    $this->render('view-footer', [
        'model' => $model,
    ]);
    ?>
</form>