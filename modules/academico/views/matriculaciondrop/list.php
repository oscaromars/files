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
    <h4><span id="lbl_titulo"><?= academico::t("matriculacion", "Registered List") ?></span></h4>
</div>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_estudiante" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= academico::t("matriculacion", 'Student') ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 control-label search-aprobacion" style="text-align: left;">
                <?= 
                    PbSearchBox::widget([
                        'boxId' => 'boxgrid',
                        'type' => 'searchBox',
                        'boxLabel' => Yii::t("accion","Search"),
                        'placeHolder' => Yii::t("accion","Search"),
                        'controller' => '',
                        'callbackListSource' => 'searchModulesList',
                        'callbackListSourceParams' => ["'boxgrid'","'grid_listadoregistrados_list'"],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
            </div>            
        </div>
    </div> 
                
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_carrera" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= academico::t("matriculacion", "Program") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_carrera", 0, $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera",]) ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_per_acad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= academico::t("matriculacion", "Academic Period") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_per_acad", 0, $arr_pla_per, ["class" => "form-control", "id" => "cmb_per_acad",]) ?>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_mod" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= academico::t("matriculacion", "Modality") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_mod", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_mod",]) ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_status" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= academico::t("matriculacion", "Status") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_status", -2, $arr_status, ["class" => "form-control", "id" => "cmb_status",]) ?>
                </div>
            </div>
        </div> 
    </div>
</form>

<?=
    $this->render('list-grid', ['model' => $model,]);
?>