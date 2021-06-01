<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use kartik\date\DatePicker;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module as admision;
use app\modules\repositorio\Module as repositorio;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as crm;
repositorio::registerTranslations();
academico::registerTranslations();
financiero::registerTranslations();
admision::registerTranslations();
crm::registerTranslations();
?>
<form class="form-horizontal">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="txt_buscarData" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="" id="txt_buscarData" placeholder="<?= admision::t("Solicitudes", "Search by item") ?>: <?= repositorio::t("repositorio", "Cantón") ?>, <?= repositorio::t("repositorio", "Provincia") ?>, <?= Yii::t("formulario", "Names") ?>, <?= financiero::t("Pagos", "Documento") ?> ">
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="lbl_inicio" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Start date") ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_ini',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_ini", "placeholder" => Yii::t("formulario", "Start date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
                <label for="lbl_fin" class="col-sm-2 col-md-12 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "End date") ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_fin',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_fin", "placeholder" => Yii::t("formulario", "End date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>                   
            <div class="form-group">
                <div>
                    <label for="cmb_tipo_convenio" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Company Agreement") ?>  </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3"> 
                    <?= Html::dropDownList("cmb_tipo_convenio", -1, ['-1' => Yii::t('formulario', 'Select'), '0' => Yii::t('formulario', 'No convenio')] + $arr_convenio_empresa, ["class" => "form-control", "id" => "cmb_tipo_convenio"])?>
                    </div>
                </div>
                <div>
                    <label for="cmb_grupo_introductorio" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= crm::t("crm", "Introductory Group") ?>  </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3"> 
                    <?= Html::dropDownList("cmb_grupo_introductorio", 0, ['0' => Yii::t('formulario', 'Select')] + $arr_grupo, ["class" => "form-control", "id" => "cmb_grupo_introductorio"]) ?>
                    </div>
                </div>
            </div>              
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>                   
            <div class="form-group">
                <div>
                    <label for="cmb_agente" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Executive") ?>  </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3"> 
                    <?= Html::dropDownList("cmb_agente", 0, $arr_agente, ["class" => "form-control", "id" => "cmb_agente"]) ?>
                    </div>
                </div>
            </div>              
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8"></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_buscarData" href="javascript:searchModules()" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
            </div>
        </div>
    </div>
</form>
<br />
<?=
    $this->render('index-grid', ['model' => $model,]);
?>