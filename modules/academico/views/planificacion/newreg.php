<?php
//use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\modules\academico\Module as academico;

academico::registerTranslations();

?>

<form class="form-horizontal">

<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <div class="form-group">
        <label for="cmb_per_acad" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("matriculacion", 'Academic Period') ?></label>
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
        <?= Html::dropDownList("cmb_per_acad", 0, $arr_pla, ["class" => "form-control", "id" => "cmb_per_acad"]) ?>
        </div>
    </div>
</div>

    <br> <br><br><br>
    <div class="form-group">
        <label for="frm_fecha_ini" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("matriculacion", "Initial Date") ?></label>
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
            <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_ini',
                    'name' => 'frm_fecha_ini',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => '',
                    'options' => ["class" => "form-control PBvalidation","data-type" => "fecha", "placeholder" => academico::t("matriculacion", "Initial Date"),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => Yii::$app->params['dateByDatePicker'],
                    ]
                ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_fecha_fin" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("matriculacion", "End Date") ?></label>
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
            <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_fin',
                    'name' => 'frm_fecha_fin',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => '',
                    'options' => ["class" => "form-control PBvalidation","data-type" => "fecha", "placeholder" => academico::t("matriculacion", "End Date"),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => Yii::$app->params['dateByDatePicker'],
                    ]
                ]);
            ?>
        </div>
    </div>

    <br> <br>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
   
        <div class="form-group">
            <label for="frm_fecha_inip1" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "  Fase de Aplicaciones") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_inip1',
                    'name' => 'frm_fecha_inip1',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "frm_fecha_inip1", "placeholder" => Yii::t("formulario", "Start date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
            <label for="frm_fecha_finp1" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Finaliza en") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_finp1',
                    'name' => 'frm_fecha_finp1',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "frm_fecha_finp1", "placeholder" => Yii::t("formulario", "End date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
        </div>
    </div>



   

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
   
           <div class="form-group">
            <label for="frm_fecha_inip3" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Registro Extraordinario") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_inip3',
                    'name' => 'frm_fecha_inip3',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "frm_fecha_inip3", "placeholder" => Yii::t("formulario", "Start date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
            <label for="frm_fecha_finp3" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Finaliza en") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_finp3',
                    'name' => 'frm_fecha_finp3',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "frm_fecha_finp3", "placeholder" => Yii::t("formulario", "End date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
        </div>
    </div>


    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
   
        <div class="form-group">
            <label for="frm_fecha_inip4" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Periodo de clases") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_inip4',
                    'name' => 'frm_fecha_inip4',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "frm_fecha_inip4", "placeholder" => Yii::t("formulario", "Start date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
            <label for="frm_fecha_finp4" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Finaliza en") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_finp4',
                    'name' => 'frm_fecha_finp4',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "frm_fecha_finp4", "placeholder" => Yii::t("formulario", "End date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
        </div>
    </div>


    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    
        <div class="form-group">
            <label for="frm_fecha_inip5" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Periodo de Exámenes") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_inip5',
                    'name' => 'frm_fecha_inip5',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "frm_fecha_inip5", "placeholder" => Yii::t("formulario", "Start date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
            <label for="frm_fecha_finp5" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Finaliza en") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_finp5',
                    'name' => 'frm_fecha_finp5',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "frm_fecha_finp5", "placeholder" => Yii::t("formulario", "End date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
        </div>
    </div>


    <?php 
    /*
    <div class="form-group">
        <label for="cmb_bloque" class="col-sm-3 control-label"><?= academico::t("matriculacion", "Block") ?></label>
        <div class="col-sm-9">
        <?= Html::dropDownList("cmb_per_acad", 0, array("B1", "B2"), ["class" => "form-control", "id" => "cmb_bloque"]) ?>
        </div>
    </div>
    */
    ?>
</form>