<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;
academico::registerTranslations();

?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h4><span id="lbl_titulo"><?= Yii::t("modulo", "List Cancellations of Registration") ?></span></h4>
</div>
<br />
<form class="form-horizontal">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" style="<?= $styleBar ?>">
        <div class="form-group">
            <label for="txt_buscarData" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarData" placeholder="<?= Yii::t("solicitud_ins", "Search by SSN/Passport or Names") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_mod" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= academico::t("matriculacion", "Modality") ?></label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <?= Html::dropDownList("cmb_mod", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_mod",]) ?>
            </div>
               
            <label for="cmb_per_acad" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= academico::t("matriculacion", "Academic Period") ?></label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <?= Html::dropDownList("cmb_per_acad", 0, $periodoAcademico, ["class" => "form-control", "id" => "cmb_per_acad",]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_status" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= academico::t("matriculacion", "Status") ?></label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <?= Html::dropDownList("cmb_status", -1, $arr_status, ["class" => "form-control", "id" => "cmb_status",]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarLis" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</form>

<?=
    $this->render('cancel-grid', ['model' => $model, 'refund' => $refund]);
?>