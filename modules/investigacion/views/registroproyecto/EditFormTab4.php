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
            <label for="cmb_instr_level" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Instruction Level") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_instr_level", "", $arr_inst_level, ["class" => "form-control", "id" => "cmb_instr_level"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="txt_institucion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Institution")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_institucion" data-type="alfa" placeholder="<?= Academico::t("profesor", "Institution") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_career" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Career") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_career" data-type="alfa" placeholder="<?= Academico::t("profesor", "Career") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_degree" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Degree") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_degree" data-type="alfa" placeholder="<?= Academico::t("profesor", "Degree") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_senescyt" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Senescyt Registry") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_senescyt" data-type="alfa" placeholder="<?= Academico::t("profesor", "Senescyt Registry") ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <button type="button" class="btn btn-primary" onclick="javascript:addInstruccion()"><?= Academico::t('profesor', 'Add') ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
<?=
    PbGridView::widget([
        'id' => 'grid_instruccion_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Instruccion',
                'header' => Academico::t("profesor", "Instruction Level"),
                'value' => function($value){
                    if(isset($value['Instruccion']) && $value['Instruccion'] != "" )
                        return $value['Instruccion'];
                    return "";
                }
            ],
            [
                'attribute' => 'NombreInstitucion',
                'header' => Academico::t("profesor", "Institution"),
                'value' => function($value){
                    if(isset($value['NombreInstitucion']) && $value['NombreInstitucion'] != "" )
                        return $value['NombreInstitucion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Especializacion',
                'header' => Academico::t("profesor", "Career"),
                'value' => function($value){
                    if(isset($value['Especializacion']) && $value['Especializacion'] != "" )
                        return $value['Especializacion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Titulo',
                'header' => Academico::t("profesor", "Degree"),
                'value' => function($value){
                    if(isset($value['Titulo']) && $value['Titulo'] != "" )
                        return $value['Titulo'];
                    return "";
                }
            ], 
            [
                'attribute' => 'Registro',
                'header' => Academico::t("profesor", "Senescyt Registry"),
                'value' => function($value){
                    if(isset($value['Registro']) && $value['Registro'] != "" )
                        return $value['Registro'];
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
                        return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:', 'onclick' => 'javascript:removeItemInstitucion(this);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);                         
                    },
                ],
            ],
        ],
    ])
?>
        </div>
    </div>
</form>