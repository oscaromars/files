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
use app\modules\Academico\Module as Academico;
Academico::registerTranslations();
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cor_alumno" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Student Name")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_cor_alumno" data-type="alfa" placeholder="<?= Academico::t("profesor", "Student Name") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_cor_programa" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Thesis topic")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_cor_programa" data-type="alfa" placeholder="<?= Academico::t("profesor", "Thesis topic") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_cor_academico" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Academic Program") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_cor_academico" data-type="alfa" placeholder="<?= Academico::t("profesor", "Academic Program") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_cor_institucion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Institution") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_cor_institucion", "", $arr_inst, ["class" => "form-control", "id" => "cmb_cor_institucion"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="txt_cor_anio" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Year of Approval") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_cor_anio" data-type="alfa" placeholder="<?= Academico::t("profesor", "Year of Approval") ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <button type="button" class="btn btn-primary" onclick="javascript:addCoordinacion()"><?= Academico::t('profesor', 'Add') ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
<?=
    PbGridView::widget([
        'id' => 'grid_coordinacion_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Estudiante',
                'header' => Academico::t("profesor", "Student Name") ,
                'value' => function($value){
                    if(isset($value['Estudiante']) && $value['Estudiante'] != "" )
                        return $value['Estudiante'];
                    return "";
                }
            ],
            [
                'attribute' => 'Programa',
                'header' => Academico::t("profesor", "Thesis topic"),
                'value' => function($value){
                    if(isset($value['Programa']) && $value['Programa'] != "" )
                        return $value['Programa'];
                    return "";
                }
            ],
            [
                'attribute' => 'Academico',
                'header' => Academico::t("profesor", "Academic Program"),
                'value' => function($value){
                    if(isset($value['Academico']) && $value['Academico'] != "" )
                        return $value['Academico'];
                    return "";
                }
            ],
            [
                'attribute' => 'Institucion',
                'header' => Academico::t("profesor", "Institution"),
                'value' => function($value){
                    if(isset($value['Institucion']) && $value['Institucion'] != "" )
                        return $value['Institucion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Anio',
                'header' => Academico::t("profesor", "Year of Approval"),
                'value' => function($value){
                    if(isset($value['Anio']) && $value['Anio'] != "" )
                        return $value['Anio'];
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
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:', 'onclick' => 'javascript:removeItemCoordinacion(this);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
        </div>
    </div>
</form>