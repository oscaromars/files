<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\modules\academico\Module as Especies;
use app\modules\financiero\Module as Financiero;
use app\modules\academico\Module as academico;

Especies::registerTranslations();
Financiero::registerTranslations();
academico::registerTranslations();

?>
<?= Html::hiddenInput('txth_dpfa_id', base64_decode($_GET["dpfa_id"]), ['id' => 'txth_dpfa_id']); ?>
<form class="form-horizontal" enctype="multipart/form-data" id="formver"> 
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">        
        <h3>Factura: <span id="lbl_num_solicitud"><?= $model['dpfa_factura'] ?></span></h3>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Financiero::t("Pagos", "Student Data") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $model['estudiante'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "Names") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1">Cédula</label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $model['identificacion'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>                    
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_ninteres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= academico::t("Academico", "Academic unit") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_ninteres", $model['uaca_id'], array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_ninteres", "disabled" => "true"]) ?>
                    </div>
                </div>  
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divModalidad">
                <div class="form-group">
                    <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= academico::t("Academico", "Modality") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_modalidad", $model['mod_id'], array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>                        
        </div>
        
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_carrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= academico::t("Academico", "Career/Program") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $model['carrera'] ?>" id="txt_carrera" disabled data-type="alfa" placeholder="<?= academico::t("Academico", "Career/Program") ?>">
                    </div>
                </div>
            </div>            
        </div>
    </div>  
    <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
        <div class="form-group">
            <h4><span id="lbl_general2"><?= Financiero::t("Pagos", "Payment Data") ?></span></h4> 
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>   
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_cuota" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Financiero::t("Pagos", "Monthly fee") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?php echo $model['dpfa_num_cuota'] ?>" id="txt_cuota" disabled data-type="alfa" placeholder="<?= financiero::t("Pagos", "Monthly fee") ?>">
                </div>
            </div>
        </div>    
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_valor_cuota" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Financiero::t("Pagos", "Quota value") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7 ">                
                   <input type="text" class="form-control keyupmce" value="<?php echo $model['valor_cuota'] ?>" id="txt_valor_cuota" disabled data-type="alfa" placeholder="<?= Financiero::t("Pagos", "Quota value") ?>">
                </div>
            </div>
        </div> 
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>   
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_forma_pago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Paid form") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?php echo $model['forma_pago'] ?>" id="txt_forma_pago" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "Paid form") ?>">
                </div>
            </div>
        </div>    
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_val_pago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= financiero::t("Pagos", "Amount Paid") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7 ">                
                   <input type="text" class="form-control keyupmce" value="<?php echo $model['valor_pago'] ?>" id="txt_val_pago" disabled data-type="alfa" placeholder="<?= financiero::t("Pagos", "Amount Paid") ?>">
                </div>
            </div>
        </div> 
    </div>

    <?php if ($model['pago_id'] == 4 or $model['pago_id'] == 5) { ?>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>   
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_referencia" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_referencia"><?= financiero::t("Pagos", "Reference") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $model['referencia'] ?>" id="txt_referencia" disabled data-type="alfa" placeholder="<?= financiero::t("Pagos", "Reference") ?>">
                    </div>
                </div>
            </div>    
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_banco" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= financiero::t("Pagos", "Bank") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7 ">                
                    <input type="text" class="form-control keyupmce" value="<?php echo $model['ban_nombre'] ?>" id="txt_banco" disabled data-type="alfa" placeholder="<?= financiero::t("Pagos", "Bank") ?>">
                    </div>
                </div>
            </div> 
        </div>
    <?php } ?>

    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>           
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txth_doc_pago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Especies::t("Especies", "Payment") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7 ">                
                    <?php 
                        if($model['forma_pago_id'] == '1')
                            echo "<a href='" . $model['imagen'] . "' target='_blank' ><span class='glyphicon glyphicon-download-alt'></span> Ver Pago</a>";
                        else
                            echo "<a href='" . Url::to(['/site/getimage', 'route' => "/uploads/pagosfinanciero/" . $model['imagen']]) . "' download='" . $model['imagen'] . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pago</a> ";
                    ?>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
        <div class="form-group">
            <h4><span id="lbl_general2"><?= Especies::t("Especies", "Resultado Revisión") ?></span></h4> 
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" ">
            <div class="form-group">
                <label for="cmb_estado" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Result") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_estado", $model['estado'], $arrEstados, ["class" => "form-control", "id" => "cmb_estado", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>      
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" id="Divobservacion" style="display: block;">
            <div class="form-group">
                <label for="cmb_observacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Observations") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_observacion", $model['observacion'], $arrObservacion, ["class" => "form-control", "id" => "cmb_observacion", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>            
    </div>     
    
</form>