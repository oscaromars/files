<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_id" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("articulo", "Code") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->COD_ART ?>" id="frm_id" disabled="disabled" data-type="all" placeholder="<?= financiero::t("articulo", "Code") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("articulo", "Article Name") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->DES_COM ?>" disabled="disabled"  id="frm_name" data-type="all" placeholder="<?= financiero::t("articulo", "Article Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_altname" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("articulo", "Alternative Name") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->DES_NAT ?>" id="frm_altname" disabled="disabled" data-type="all" placeholder="<?= financiero::t("articulo", "Alternative Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><?= financiero::t("articulo", "General Info") ?></a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><?= financiero::t("articulo", "Prices") ?></a></li>
                        <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="true"><?= financiero::t("articulo", "Stock") ?></a></li>
                        <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="true"><?= financiero::t("articulo", "Observations") ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <?= $this->render('view-ginfo', [
                                'model' => $model, 
                                'arr_pais' => $arr_pais, 
                                'arr_provincia' => $arr_provincia, 
                                'arr_ciudad' => $arr_ciudad, 
                                'arr_tipo' => $arr_tipo,
                                'pais_def' => $pais_def,
                                'provincia_def' => $provincia_def,
                                'ciudad_def' => $ciudad_def,
                                'inventario_code' => $inventario_code,
                                'inventario_name' => $inventario_name,
                                'venta_code' => $venta_code,
                                'venta_name' => $venta_name,
                                'costo_code' => $costo_code,
                                'costo_name' => $costo_name,
                                'invta_code' => $invta_code,
                                'invta_name' => $invta_name,
                                'medida_code' => $medida_code,
                                'medida_name' => $medida_name,
                                'linea_code' => $linea_code,
                                'linea_name' => $linea_name,
                                'tipo_code' => $tipo_code,
                                'tipo_name' => $tipo_name,
                                'marca_code' => $marca_code,
                                'marca_name' => $marca_name,
                                'divisa_code' => $divisa_code,
                                'divisa_name' => $divisa_name,
                                'proveedor_code' => $proveedor_code,
                                'proveedor_name' => $proveedor_name,
                            ]); ?>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <?= $this->render('view-prices', ['model' => $model,]); ?>
                        </div>
                        <div class="tab-pane" id="tab_3">
                            <?= $this->render('view-stock', ['model' => $model,]); ?>
                        </div>
                        <div class="tab-pane" id="tab_4">
                            <?= $this->render('view-observations', ['model' => $model,]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_id" value="<?= $model->COD_ART ?>">