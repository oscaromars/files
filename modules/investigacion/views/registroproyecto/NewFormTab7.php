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
use app\modules\investigacion\Module as investigacion;

investigacion::registerTranslations();
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_idiomas" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Languages") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_idiomas", "", $arr_languages, ["class" => "form-control", "id" => "cmb_idiomas"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="txt_idio_escrito" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Written Level")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_nivel_escrito", "", $arr_nivel_ingles, ["class" => "form-control", "id" => "cmb_nivel_escrito"]) ?>                
            </div>
        </div>
        <div class="form-group">
            <label for="txt_idio_oral" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Oral Level") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_nivel_oral", "", $arr_nivel_ingles, ["class" => "form-control", "id" => "cmb_nivel_oral"]) ?>                
            </div>
        </div>
        <div class="form-group">
            <label for="txt_idio_certificado" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Certificate of Sufficiency") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_certificado", "", $arr_certificado, ["class" => "form-control", "id" => "cmb_certificado"]) ?>                
            </div>
        </div>
        <div class="form-group">
            <label for="txt_idio_institucion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Institution") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_idio_institucion" data-type="alfa" placeholder="<?= Academico::t("profesor", "Institution") ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <button type="button" class="btn btn-primary" onclick="javascript:addIdioma()"><?= Academico::t('profesor', 'Add') ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
<?=
    PbGridView::widget([
        'id' => 'grid_idioma_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Languages',
                'header' => Academico::t("profesor", "Languages"),
                'value' => 'Languages',
            ],
            [
                'attribute' => 'NivelEscrito',
                'header' => Academico::t("profesor", "Written Level"),
                'value' => 'NivelEscrito',
            ],
            [
                'attribute' => 'NivelOral',
                'header' => Academico::t("profesor", "Oral Level"),
                'value' => 'NivelOral',
            ],
            [
                'attribute' => 'Certificado',
                'header' => Academico::t("profesor", "Certificate of Sufficiency"),
                'value' => 'Certificado',
            ], 
            [
                'attribute' => 'Institucion',
                'header' => Academico::t("profesor", "Institution"),
                'value' => 'Institucion',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['per_id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
        </div>
    </div>
</form>