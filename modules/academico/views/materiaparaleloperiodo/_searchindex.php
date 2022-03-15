    <?php

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use kartik\select2\Select2;
    use yii\helpers\ArrayHelper;
    $var = ArrayHelper::map(app\modules\academico\models\PeriodoAcademico::find()->where(['paca_estado' => 1,'paca_estado_logico' => 1,'paca_activo' => 'A'])->all(), 'paca_id',
                    function ($model) {
                         return $model->baca->baca_nombre . '-' . $model->sem->saca_nombre . '-' . $model->sem->saca_anio;
                       // return strtoupper($model->per->per_pri_apellido . ' ' . $model->per->per_seg_apellido . ' ' . $model->per->per_pri_nombre . ' ' . $model->per->per_seg_nombre);
                    });
    ?>
    <!--<div class="semestreacademico-search">-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_periodo" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?></label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <?= Html::dropDownList("cmb_periodo", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo"]) ?>
                </div>
                 <label for="lbl_unidad" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= Yii::t("formulario", "Unidad"); ?></label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <?= Html::dropDownList("cmb_unidad", 0, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad"]) ?>
                </div>      
            </div>
        </div>
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_modalidad" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?></label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <?= Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                </div>  
                 <label for="lbl_asignaturas" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= Yii::t("formulario", "Subjects"); ?></label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <?= Html::dropDownList("cmb_asignaturas", 0, $arr_asignaturas, ["class" => "form-control", "id" => "cmb_asignaturas"]) ?>
                </div>            
            </div>
        </div>

        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="col-sm-6"></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">               
                <a id="btn_buscarDataAsignarMateriaParalelo" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
            </div>
        </div>  

        <!--<div class="form-group">
            <div class="col-sm-offset-4">
                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>-->
    
    </div>
