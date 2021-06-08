<?php
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\modules\academico\Module as academico;

academico::registerTranslations();

?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="cmb_per_acad" class="col-sm-3 control-label"><?= academico::t("matriculacion", 'Academic Period') ?></label>
        <div class="col-sm-9">
        <?= Html::dropDownList("cmb_per_acad", $pla_id, $arr_pla, ["class" => "form-control", "id" => "cmb_per_acad"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_fecha_ini" class="col-sm-3 control-label"><?= academico::t("matriculacion", "Initial Date") ?></label>
        <div class="col-sm-9">
            <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_ini',
                    'name' => 'frm_fecha_ini',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => date(Yii::$app->params['dateByDefault'],strtotime($model->rco_fecha_inicio)),
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
        <label for="frm_fecha_fin" class="col-sm-3 control-label"><?= academico::t("matriculacion", "End Date") ?></label>
        <div class="col-sm-9">
            <?=
                DatePicker::widget([
                    'id' => 'frm_fecha_fin',
                    'name' => 'frm_fecha_fin',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => date(Yii::$app->params['dateByDefault'],strtotime($model->rco_fecha_fin)),
                    'options' => ["class" => "form-control PBvalidation","data-type" => "fecha", "placeholder" => academico::t("matriculacion", "End Date"),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => Yii::$app->params['dateByDatePicker'],
                    ]
                ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_bloque" class="col-sm-3 control-label"><?= academico::t("matriculacion", "Block") ?></label>
        <div class="col-sm-9">
        <?= Html::dropDownList("cmb_per_acad", $bloque, array("B1", "B2"), ["class" => "form-control", "id" => "cmb_bloque",]) ?>
        </div>
    </div>
</form>

<input type="hidden" id="frm_rco_id" value="<?= $rco_id ?>"