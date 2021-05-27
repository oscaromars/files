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
            <label for="txt_pro_from" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "From") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?=
                DatePicker::widget([
                    'name' => 'txt_pro_from',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidations keyupmce", "id" => "txt_pro_from", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => "yyyy-mm-dd" ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>  
            </div>
        </div>
        <div class="form-group">
            <label for="txt_pro_to" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "To") ?> </label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?=
                DatePicker::widget([
                    'name' => 'txt_pro_to',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidations keyupmce", "id" => "txt_pro_to", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => "yyyy-mm-dd" ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>  
            </div>
        </div>
        <div class="form-group">
            <label for="txt_pro_empresa" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Company / Organization") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_pro_empresa" data-type="alfa" placeholder="<?= Academico::t("profesor", "Company / Organization")  ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_pro_denominacion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Denomination")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_pro_denominacion" data-type="alfa" placeholder="<?= Academico::t("profesor", "Denomination") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_pro_funciones" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Functions and Responsibilities") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_pro_funciones" data-type="alfa" placeholder="<?= Academico::t("profesor", "Functions and Responsibilities") ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <button type="button" class="btn btn-primary" onclick="javascript:addExperiencia()"><?= Academico::t('profesor', 'Add') ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
<?=
    PbGridView::widget([
        'id' => 'grid_experiencia_list',
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
                'header' => Academico::t("profesor", "Company / Organization"),
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
                'attribute' => 'Funciones',
                'header' => Academico::t("profesor", "Functions and Responsibilities"),
                'value' => function($value){
                    if(isset($value['Funciones']) && $value['Funciones'] != "" )
                        return $value['Funciones'];
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
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:', 'onclick' => 'javascript:removeItemExperiencia(this);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
        </div>
    </div>
</form>