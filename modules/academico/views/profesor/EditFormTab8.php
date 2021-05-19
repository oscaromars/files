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
            <label for="txt_re_denominacion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Project Denomination")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_re_denominacion" data-type="alfa" placeholder="<?= Academico::t("profesor", "Project Denomination") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_re_ambit" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Ambit")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_re_ambit" data-type="alfa" placeholder="<?= Academico::t("profesor", "Ambit") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_re_respon" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Resposability")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_re_respon" data-type="alfa" placeholder="<?= Academico::t("profesor", "Resposability") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_re_reali" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Realization Entity")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_re_reali" data-type="alfa" placeholder="<?= Academico::t("profesor", "Realization Entity") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_re_year" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Year") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_re_year" data-type="alfa" placeholder="<?= Academico::t("profesor", "Year") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_re_duration" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Time Duration") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_re_duration" data-type="alfa" placeholder="<?= Academico::t("profesor", "Time Duration") ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <button type="button" class="btn btn-primary" onclick="javascript:addInvestigacion()"><?= Academico::t('profesor', 'Add') ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
<?=
    PbGridView::widget([
        'id' => 'grid_investigacion_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Denominancion',
                'header' => Academico::t("profesor", "Project Denomination") ,
                'value' => function($value){
                    if(isset($value['Denominancion']) && $value['Denominancion'] != "" )
                        return $value['Denominancion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Ambito',
                'header' => Academico::t("profesor", "Ambit"),
                'value' => function($value){
                    if(isset($value['Ambito']) && $value['Ambito'] != "" )
                        return $value['Ambito'];
                    return "";
                }
            ],
            [
                'attribute' => 'Resposabilidad',
                'header' => Academico::t("profesor", "Resposability"),
                'value' => function($value){
                    if(isset($value['Resposabilidad']) && $value['Resposabilidad'] != "" )
                        return $value['Resposabilidad'];
                    return "";
                }
            ],
            [
                'attribute' => 'Entidad',
                'header' => Academico::t("profesor", "Realization Entity"),
                'value' => function($value){
                    if(isset($value['Entidad']) && $value['Entidad'] != "" )
                        return $value['Entidad'];
                    return "";
                }
            ], 
            [
                'attribute' => 'Anio',
                'header' => Academico::t("profesor", "Year"),
                'value' => function($value){
                    if(isset($value['Anio']) && $value['Anio'] != "" )
                        return $value['Anio'];
                    return "";
                }
            ],
            [
                'attribute' => 'Duracion',
                'header' => Academico::t("profesor", "Time Duration"),
                'value' => function($value){
                    if(isset($value['Duracion']) && $value['Duracion'] != "" )
                        return $value['Duracion'];
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
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:', 'onclick' => 'javascript:removeItemInvestigacion(this);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
        </div>
    </div>
</form>