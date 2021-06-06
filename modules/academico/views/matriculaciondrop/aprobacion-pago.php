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
$modalidad = array_unshift($arr_modalidad, "Todos");
?>
<form class="form-horizontal">
    <h3><span id="lbl_titulo"><?= Yii::t("modulo", "Review Enrollment Payments") ?></span></h3>
    <br></br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_estudiante" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= academico::t("matriculacion", 'Student') ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 control-label search-aprobacion" style="text-align: left;">
                    <?=
                        PbSearchBox::widget([
                            'boxId' => 'boxgrid',
                            'type' => 'searchBox',
                            'placeHolder' => Yii::t("accion", "Search"),
                            'controller' => '',
                            'callbackListSource' => 'searchModules',
                            'callbackListSourceParams' => ["'boxgrid'", "'grid_paises_list'"],
                        ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_per_academico" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= academico::t("matriculacion", 'Academic Period') ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 control-label" style="text-align: left;">
                    <?= Html::dropDownList("cmb_per_academico", $pla_periodo_academico, $arr_pla, ["class" => "form-control", "id" => "cmb_per_academico"]) ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_modalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= academico::t("matriculacion", 'Modality') ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 control-label" style="text-align: left;">
                    <?= Html::dropDownList("cmb_modalidad", 0,  $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                </div>
            </div>
       </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_estado" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= academico::t("matriculacion", 'Status') ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 control-label" style="text-align: left;">
                    <?= Html::dropDownList("cmb_estado", -1,  $arr_status, ["class" => "form-control", "id" => "cmb_estado"]) ?>
                </div>
            </div>
        </div>
    </div>
<br />
<?=
    $this->render('aprobacion-pago-grid', ['pagos' => $pagos,]);
?>