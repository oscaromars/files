<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>

<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <div class="box box-widget widget-user">
            <div class="widget-user-header bg-red">
                <h3 class="widget-user-username"><?= financiero::t('puntoemision', 'Select an Establishment and Emission Point.') ?></h3>
            </div>
            <div class="box-footer no-paddings">
                <form class="form-horizontal">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="cmb_establecimiento" class="col-md-3 control-label"><?= financiero::t("establecimiento", "Establishment") ?></label>
                            <div class="col-md-9">
                                <?= Html::dropDownList("cmb_establecimiento", $pEstablecimiento, $arr_establecimiento, ["class" => "form-control", "id" => "cmb_establecimiento"]) ?>
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="cmb_pemision" class="col-md-3 control-label"><?= financiero::t("puntoemision", "Emission Point") ?></label>
                            <div class="col-md-9">
                                <?= Html::dropDownList("cmb_pemision", $pEmision, $arr_pemision, ["class" => "form-control", "id" => "cmb_pemision"]) ?>
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <a id="btn_save" href="javascript:" class="btn btn-danger btn-block pull-right"> <?= Yii::t("accion", "Save") ?></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<input type="hidden" id="codEst" value="<?= $pEstablecimiento ?>" />
<input type="hidden" id="codEmi" value="<?= $pEmision ?>" />