<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
use yii\data\ArrayDataProvider;
use yii\widgets\DetailView;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\Utilities;
use app\modules\repositorio\Module as repositorio;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as crm;

academico::registerTranslations();
financiero::registerTranslations();
crm::registerTranslations();

//print_r($arr_consinscrito);
?>
<?= Html::hiddenInput('txth_imae_id', base64_encode($imae_id), ['id' => 'txth_imae_id']); ?>
<div class="col-md-12">    
    <h3><span id="lbl_Personeria"><?= crm::t("crm", "Register Magisters") ?></span>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>        
</div>
<form class="form-horizontal">
    <div class="col-md-12">    
        <h4><span id="lbl_datos_inscrito"><?= crm::t("crm", "Register Data") ?></span> </h4>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_tipo_documento" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= Yii::t("formulario", "Type DNI") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_tipo_documento", $arr_consinscrito['tipo_documento'], ['0' => Yii::t('formulario', 'Select')] + $arr_tipos_dni, ["class" => "form-control", "id" => "cmb_tipo_documento", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_documento" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_documento"><?= crm::t("crm", "Number Document") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['documento'] ?>" id="txt_documento" disabled= "true" data-type="alfa" placeholder="<?= crm::t("crm", "Number Document") ?>">
                </div> 
            </div>
        </div>                 
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nombres1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "First Name") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['nombre'] ?>"  disabled= "true" id="txt_nombres1" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nombres2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Middle Name") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['sgo_nombre'] ?>" id="txt_nombres2"  disabled= "true" data-type="alfa" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
                </div>
            </div>
        </div> 
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_apellidos1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Name") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['apellido'] ?>" id="txt_apellidos1" disabled= "true" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_apellidos2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Last Second Name") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['sgoapellido'] ?>" id="txt_apellidos2" disabled= "true" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
                </div>
            </div>
        </div> 
    </div>     
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" >
            <div class="form-group">            
                <label for="cmb_pais" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= Yii::t("formulario", "Country") ?><span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_pais", $arr_consinscrito['pais'], ['0' => Yii::t('formulario', 'Select')] + $arr_pais_dom, ["class" => "form-control", "id" => "cmb_pais", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_provincia" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= Yii::t("formulario", "State") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_provincia", $arr_consinscrito['provincia'], ['0' => Yii::t('formulario', 'Select')] + $arr_prov_dom, ["class" => "form-control", "id" => "cmb_provincia", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_canton" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= Yii::t("formulario", "City") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_canton", $arr_consinscrito['canton'], ['0' => Yii::t('formulario', 'Select')] + $arr_ciu_dom, ["class" => "form-control", "id" => "cmb_canton", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>   
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txt_matricula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= crm::t("crm", "Enrollment") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['matricula'] ?>" id="txt_matricula" disabled= "true" data-type="alfa" placeholder="<?= crm::t("crm", "Enrollment") ?>">
                </div>
            </div>
        </div>           
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txt_titulo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_titulo"><?= crm::t("crm", "Title") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['titulo'] ?>" id="txt_titulo" disabled= "true" data-type="alfa" placeholder="<?= crm::t("crm", "Title") ?>">
                </div>
            </div>
        </div>   
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_institucion" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= crm::t("crm", "Institution") ?> </label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_institucion", $arr_consinscrito['institucion'], ['0' => Yii::t('formulario', 'Select')] + $arr_institucion, ["class" => "form-control", "id" => "cmb_institucion", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>           
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txt_correo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_correo"><?= Yii::t("formulario", "Email") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['correo'] ?>" id="txt_correo" disabled= "true" data-type="email" placeholder="<?= Yii::t("formulario", "Email") ?>">
                </div>
            </div>
        </div>   
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txt_celular" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_celular"><?= Yii::t("formulario", "CellPhone") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['celular'] ?>" id="txt_celular" disabled= "true" data-type="celular_sin" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                </div>
            </div>
        </div>           
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txt_telefono" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_telefono"><?= Yii::t("formulario", "Phone") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['telefono'] ?>" id="txt_telefono" disabled= "true" data-type="telefono_sin" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                </div>
            </div>
        </div>   
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txt_ocupacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_ocupacion"><?= crm::t("crm", "Occupation") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" disabled = "true" value="<?= $arr_consinscrito['ocupacion'] ?>" id="txt_ocupacion" data-type="alfa" placeholder="<?= crm::t("crm", "Occupation") ?>">
                </div>
            </div>
        </div> 
    </div>     
    <div class="col-md-12">    
        <h4><span id="lbl_Datos_Inscripcion"><?= crm::t("crm", "Registration Data") ?></span> </h4>
    </div>    
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_unidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= academico::t("Academico", "Academic unit") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_unidad", $arr_consinscrito['unidad'], ['0' => Yii::t('formulario', 'Select')] + $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad", "disabled" => "true"]) ?>
                </div>
            </div>  
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divModalidad">
            <div class="form-group">
                <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= academico::t("Academico", "Modality") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_modalidad", $arr_consinscrito['modalidad'], ['0' => Yii::t('formulario', 'Select')] + $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_carrera" id="lbl_carrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= academico::t("Academico", "Career/Program") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_carrera", $arr_consinscrito['carrera'], ['0' => Yii::t('formulario', 'Select')] + $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera", "disabled" => "true"]) ?>
                </div>
            </div>            
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6"> 
            <div class="form-group"> 
                <label for="cmb_tipo_convenio" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= Yii::t("formulario", "Company Agreement") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_tipo_convenio", $arr_consinscrito['convenio_empresa'], ['0' => Yii::t('formulario', 'No convenio')] + $arr_convenio_empresa, ["class" => "form-control", "id" => "cmb_tipo_convenio", "disabled" => "true"]) ?>
                </div>
            </div>   
        </div>       
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_grupo_introductorio" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= crm::t("crm", "Introductory Group") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_grupo_introductorio", $arr_consinscrito['grupo_introductorio'], ['0' => Yii::t('formulario', 'Select')] + $arr_grupo, ["class" => "form-control", "id" => "cmb_grupo_introductorio", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_cumple_requisito" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= crm::t("crm", "Meets Requirement") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_cumple_requisito", $arr_consinscrito['cumple_requisito'], ['0' => Yii::t('formulario', 'Select')] + $arr_cumple_requisito, ["class" => "form-control", "id" => "cmb_cumple_requisito", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>  
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_agente" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Executive") ?><span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_agente", $arr_consinscrito['agente'], $arr_agente, ["class" => "form-control", "id" => "cmb_agente", "disabled" => "true"]) ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txt_fecha_inscripcion" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= crm::t("crm", "Registration Date") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_inscripcion',
                        'value' => $arr_consinscrito['fecha_inscripcion'],
                        'disabled' => 'true',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_inscripcion", "placeholder" => crm::t("crm", "Registration Date")],
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">            
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_revision" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= crm::t("crm", "Revision") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                    
                    <textarea  class="form-control keyupmce" rows="5"  disabled= "true" id="txt_revision"><?= $arr_consinscrito['revision'] ?></textarea>
                </div>
            </div>
        </div> 
    </div>

    <div class="col-md-12">    
        <h4><span id="lbl_Datos_Pago"><?= crm::t("crm", "Payment Data") ?></span> </h4>
    </div>    

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                    
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_pago_inscripcion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= crm::t("crm", "Payment Registration") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['pago_inscripcion'] ?>" disabled= "true" id="txt_pago_inscripcion" data-type="alfa" placeholder="<?= crm::t("crm", "Payment Registration") ?>">
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_pago_total" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Pay Total") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_consinscrito['pago_total'] ?>" id="txt_pago_total" disabled= "true" data-type="alfa" placeholder="<?= Yii::t("formulario", "Pay Total") ?>">
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txt_fecha_pago" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= Yii::t("formulario", "Date") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_pago',
                        'value' => $arr_consinscrito['fecha_pago'],
                        'disabled' => 'true',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_pago", "placeholder" => Yii::t("formulario", "Date")],
                        'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
            </div>
        </div>        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_metodo_pago" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= crm::t("crm", "Payment Method") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_metodo_pago", $arr_consinscrito['metodo_pago'], ['0' => Yii::t('formulario', 'Select')] + $arr_forma_pago, ["class" => "form-control", "id" => "cmb_metodo_pago", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>  
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_estado_pago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Payment Status") ?><span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_estado_pago", $arr_consinscrito['estado_pago'], ['0' => Yii::t('formulario', 'Select')] + $arr_estado_pago, ["class" => "form-control", "id" => "cmb_estado_pago", "disabled" => "true"]) ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txt_convenio_listo" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= crm::t("crm", "Ready Agreement") ?> </label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <textarea  class="form-control keyupmce" rows="5" disabled= "true" id="txt_convenio_listo"><?= $arr_consinscrito['convenio'] ?></textarea>
                </div>
            </div>
        </div>   
    </div> 
    <br/>   
    <br/>       
</form>

