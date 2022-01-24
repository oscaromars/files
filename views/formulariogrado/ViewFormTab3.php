<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\modules\Academico\Module as Academico;
Academico::registerTranslations();
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_trabajo_direccion" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Dirección de Trabajo") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_trabajo_direccion" value="<?= $persona_model->per_trabajo_direccion ?>" data-type="all" disabled="disabled" placeholder="<?= Academico::t("profesor", "Dirección de Trabajo")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_contc_emergencias" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Contacto en caso de Emergencia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_contc_emergencias" value="<?= $contacto_model->pcon_nombre ?>" data-type="all" disabled="disabled" placeholder="<?= Academico::t("profesor", "Nombre de Contacto en caso de Emergencia")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_parentesco" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Parentesco") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_parentesco", $contacto_model->tpar_id, $arr_tipparentesco, ["class" => "form-control", "id" => "cmb_parentesco", "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_cel" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "CellPhone") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_cel" value="<?= $contacto_model->pcon_celular ?>" data-required="false" disabled="disabled" data-type="number"  placeholder="<?= Yii::t("perfil", "CellPhone")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_direccion_cont" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Dirección de Persona en Caso de Emergencia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_direccion_cont" value="<?= $contacto_model->pcon_direccion ?>" disabled="disabled" data-required="false" data-type="number"  placeholder="<?= Yii::t("formulario", "Dirección de Persona en Caso de Emergencia") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_viewfinanciamiento" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Tipo Financiamiento") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_viewfinanciamiento", $datos_financiamiento->igra_financiamiento, $arr_financiamiento, ["class" => "form-control", "id" => "cmb_viewfinanciamiento", "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>
    <?php
      if ($datos_financiamiento->igra_financiamiento == 3)
      {
        $bloqueo = "block";
      }else{
        $bloqueo = "none";
      }
    ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="divinstitutoEdit" style="display: <?php echo $bloqueo;?>">
        <div class="form-group">
            <label for="frm_viewinstituto" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Institución Beca") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control" id="txt_viewinstituto" value= "<?= $datos_financiamiento->igra_institucion_beca ?>"" data-type="all" disabled="disabled" placeholder="<?= Academico::t("profesor", "Institución Beca")  ?>">
            </div>
        </div>
    </div>
</form>