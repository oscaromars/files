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
$styleBar = "";
if($esEstu) $styleBar = "display: none;";
//print_r($periodoAcademico);
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group row">
    <h3><span id="lbl_registro_online"><?= academico::t("registro", "List Enrollment") ?></span></h3>
</div>
<form class="form-horizontal">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">     
                    <label for="cmb_mod" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("matriculacion", "Modality") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?php if($grupo_id==12) : ?>
                            <?= Html::dropDownList("cmb_mod", 0, $modalidad, ["class" => "form-control", "id" => "cmb_mod"]) ?>
                        <?php else : ?>
                            <?= Html::dropDownList("cmb_mod", 0, $modalidadT, ["class" => "form-control", "id" => "cmb_mod"]) ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label for="cmb_per_acad" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("matriculacion", "Academic Period") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?= Html::dropDownList("cmb_per_acad", 0, $periodoAcademico, ["class" => "form-control", "id" => "cmb_per_acad",]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscar" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</form>

<?=
    $this->render('index-grid', ['model' => $model, 'esEstu' => $esEstu, 'costo' => $costo , 'per_id' => $per_id]);
?>

<!--
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_status" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= academico::t("matriculacion", "Status") ?></label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <?= Html::dropDownList("cmb_status", -1, $arr_status, ["class" => "form-control", "id" => "cmb_status",]) ?>
            </div>
        </div>
    </div>

-->