<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="form-group">
            <label for="cmb_idioma2Edit" class="col-sm-3 control-label"><?= Yii::t("formulario", "Idioma") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-7">
                <?= Html::dropDownList("cmb_idioma2Edit", 0, $arr_idioma, ["class" => "form-control", "id" => "cmb_idioma2Edit"]) ?>
            </div>
        </div>
 
        <div class="form-group" id="Dividiomas">
            <label for="cmb_nivelidioma2Edit" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nivel Idioma") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-7">
                <?= Html::dropDownList("cmb_nivelidioma2Edit", 0, $arr_nivelidioma, ["class" => "form-control", "id" => "cmb_nivelidioma2Edit"]) ?>
            </div>
        </div>

        <div style="display: none;" id="Divotroidioma">
            <label for="txt_nombreidiomaEdit" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nombre del Idioma") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" maxlength="10" class="form-control PBvalidation keyupmce" id="txt_nombreidiomaEdit" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nombre del Idioma") ?>">
            </div>
        </div>
        <div style="display: none;" id="Divotronivelidioma">
            <label for="cmb_nivelotroidiomaEdit" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nivel Idioma") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-7">
                <?= Html::dropDownList("cmb_nivelotroidiomaEdit", 0, $arr_nivelidioma, ["class" => "form-control", "id" => "cmb_nivelotroidiomaEdit"]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <button type="button" class="btn btn-primary" onclick="javascript:addIdioma()"><?= Yii::t('formulario', 'Add') ?></button>
            </div>
        </div>
    </div>
    

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-secondary">
            <div>
            <?=
                PbGridView::widget([
                    'id' => 'grid_idiomas_list',
                    'showExport' => false,
                    //'fnExportEXCEL' => "exportExcel",
                    //'fnExportPDF' => "exportPdf",
                    'dataProvider' => $model,
                    'pajax' => true,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
                        [
                            'attribute' => 'idioma',
                            'header' => Yii::t("inscripcionposgrado", "Idioma"),
                            'value' => function($value){
                                if($value['idi'] == 3 ){
                                    return $value['idioma'];
                                }else if($value['idi'] != 3 ){
                                    return $value['nombre_idioma'];
                                }
                            }
                        ],
                        [
                            'attribute' => 'nivel_idioma',
                            'header' => Yii::t("inscripcionposgrado", "Nivel de Idioma"),
                            'value' => function($value){
                                if(isset($value['nivel_idioma']) && $value['nivel_idioma'] != "" )
                                    return $value['nivel_idioma'];
                                return "";
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            //'header' => 'Action',
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'headerOptions' => ['width' => '60'],
                            'template' => '{delete}',
                            'buttons' => [
                                'delete' => function ($url, $model) {
                                     return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:', 'onclick' => 'javascript:removeItemIdioma(this);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                                },
                            ],
                        ],
                    ],
                ])
            ?>
            </div>
        </div>
    </div>
</form>