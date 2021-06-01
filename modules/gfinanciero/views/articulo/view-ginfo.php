<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

$token = SearchAutocomplete::getToken();

?>
<br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="autocomplete-linea" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("lineaarticulo", "Line") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'linea',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/articulo/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putLineaData',
                        'defaultValue' => $linea_code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $linea_name ?>" id="frm_lineadesc" data-type="all" disabled="disabled" />
            </div>
        </div>
        <div class="form-group">
            <label for="autocomplete-tipo" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("tipoarticulo", "Type") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'tipo',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/articulo/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putTipoData',
                        'defaultValue' => $tipo_code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $tipo_name ?>" id="frm_tipodesc" data-type="all" disabled="disabled" />
            </div>
        </div>
        <div class="form-group">
            <label for="autocomplete-marca" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("marcaarticulo", "Mark") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'marca',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/articulo/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putMarcaData',
                        'defaultValue' => $marca_code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $marca_name ?>" id="frm_marcadesc" data-type="all" disabled="disabled" />
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_pais" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("localidad", "Country") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <?= Html::dropDownList("cmb_pais", $pais_def, $arr_pais, ["class" => "form-control", "id" => "cmb_pais", "disabled" => "disabled",]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_provincia" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("localidad", "State") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <?= Html::dropDownList("cmb_provincia", $provincia_def, $arr_provincia, ["class" => "form-control", "id" => "cmb_provincia", "disabled" => "disabled",]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_ciudad" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("localidad", "City") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <?= Html::dropDownList("cmb_ciudad", $ciudad_def, $arr_ciudad, ["class" => "form-control", "id" => "cmb_ciudad", "disabled" => "disabled",]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="autocomplete-divisa" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("divisa", "Currency") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'divisa',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/articulo/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putDivisaData',
                        'defaultValue' => $divisa_code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $divisa_name ?>" id="frm_divisadesc" data-type="all" disabled="disabled" />
            </div>
        </div>
        <div class="form-group">
            <label for="frm_ubicacion" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Location") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->UBI_FIS ?>" id="frm_ubicacion" data-type="all" disabled="disabled" placeholder="<?= financiero::t("articulo", "Location") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="autocomplete-proveedor" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("proveedor", "Provider") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'proveedor',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/articulo/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putProveedorData',
                        'defaultValue' => $proveedor_code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value='<?= $proveedor_name ?>' id="frm_proveedordesc" data-type="all" disabled="disabled" />
            </div>
        </div>
    </div> 
    <div class="col-md-6">
        <div class="form-group">
            <label for="autocomplete-inventario" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("catalogo", "Inventory") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'inventario',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/articulo/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putInventarioData',
                        'defaultValue' => $inventario_code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $inventario_name ?>" id="frm_inventariodesc" data-type="all" disabled="disabled" />
            </div>
        </div>
        <div class="form-group">
            <label for="autocomplete-venta" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("catalogo", "Sale") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'venta',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/articulo/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putVentaData',
                        'defaultValue' => $venta_code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $venta_name ?>" id="frm_ventadesc" data-type="all" disabled="disabled" />
            </div>
        </div>
        <div class="form-group">
            <label for="autocomplete-cventa" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("catalogo", "Cost of Sale") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'cventa',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/articulo/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putCVentaData',
                        'defaultValue' => $costo_code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $costo_name ?>" id="frm_cventadesc" data-type="all" disabled="disabled" />
            </div>
        </div>
        <div class="form-group">
            <label for="autocomplete-iventa" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("catalogo", "Inventory of Sale") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'iventa',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/articulo/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putIVentaData',
                        'defaultValue' => $invta_code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $invta_name ?>" id="frm_iventadesc" data-type="all" disabled="disabled" />
            </div>
        </div>
        <div class="form-group">
            <label for="autocomplete-medida" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("unidadmedida", "Measure") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'medida',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/articulo/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putMedidaData',
                        'defaultValue' => $medida_code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $medida_name ?>" id="frm_medidadesc" data-type="all" disabled="disabled" />
            </div>
        </div>
        <div class="form-group">
            <label for="frm_fexp" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Expires") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fexp',
                        'value' => date(Yii::$app->params["dateByDefault"], strtotime($model->F_E_ART)),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fexp", "disabled" => "disabled", "data-type" => "fecha", "placeholder" => financiero::t("articulo", "Expires")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_tipo" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("tipoitem", "Product Type") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <?= Html::dropDownList("cmb_tipo", $model->TIP_PRO, $arr_tipo, ["class" => "form-control", "id" => "cmb_tipo", "disabled" => "disabled"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_credit" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Credits") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->NUM_CRE ?>" id="frm_credit" data-type="number" disabled="disabled" placeholder="<?= financiero::t("articulo", "Credits") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_caracteristica" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Characteristics") ?></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="checkbox" id="chk_descon" value="" <?= ($model->I_M_DES == 1)?"checked='checked'":"" ?> disabled="disabled" /> <label for="chk_descon" class="control-label"><?= financiero::t("articulo", "Discontinued") ?></label>&nbsp;&nbsp;&nbsp;
                <input type="checkbox" id="chk_iva" value="" <?= ($model->I_M_IVA == 1)?"checked='checked'":"" ?> disabled="disabled" /> <label for="chk_iva" class="control-label"><?= financiero::t("articulo", "VAT Tax") ?></label>
            </div>
        </div>
    </div>
</div>
