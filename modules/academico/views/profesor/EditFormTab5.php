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
            <label for="txt_doc_from" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "From") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?=
                DatePicker::widget([
                    'name' => 'txt_doc_from',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidations keyupmce", "id" => "txt_doc_from", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => "yyyy-mm-dd" ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>  
            </div>
        </div>
        <div class="form-group">
            <label for="txt_doc_to" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "To") ?> </label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?=
                DatePicker::widget([
                    'name' => 'txt_doc_to',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidations keyupmce", "id" => "txt_doc_to", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => "yyyy-mm-dd" ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>  
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_doc_institucion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Institution") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_doc_institucion", "", $arr_inst, ["class" => "form-control", "id" => "cmb_doc_institucion"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="txt_denominacion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Denomination")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_denominacion" data-type="alfa" placeholder="<?= Academico::t("profesor", "Denomination") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_subjects" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Subjects") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_subjects" data-type="alfa" placeholder="<?= Academico::t("profesor", "Maths, Chemistry, etc") ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <button type="button" class="btn btn-primary" onclick="javascript:addDocencia()"><?= Academico::t('profesor', 'Add') ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
<?=
    PbGridView::widget([
        'id' => 'grid_docencia_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
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
                'attribute' => 'Desde',
                'header' => Academico::t("profesor", "From") ,
                'value' => function($value){
                    return date(Yii::$app->params["dateByDefault"], strtotime($value['Desde']));
                }
            ],
            [
                'attribute' => 'Hasta',
                'header' => Academico::t("profesor", "To"),
                'value' => function($value){
                    if(isset($value['Hasta']) && $value['Hasta'] != "" )
                        return date(Yii::$app->params["dateByDefault"], strtotime($value['Hasta']));
                    return "";
                }
            ],
            [
                'attribute' => 'Denominacion',
                'header' => Academico::t("profesor", "Denomination"),
                'value' => function($value){
                    if(isset($value['Denominacion']) && $value['Denominacion'] != "" )
                        return $value['Denominacion'];
                    return "";
                }
            ], 
            [
                'attribute' => 'Materias',
                'header' => Academico::t("profesor", "Subjects"),
                'value' => function($value){
                    if(isset($value['Materias']) && $value['Materias'] != "" )
                        return $value['Materias'];
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
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:', 'onclick' => 'javascript:removeItemDocencia(this);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
        </div>
    </div>
</form>