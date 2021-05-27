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
use app\modules\Academico\Module as Academico;
Academico::registerTranslations();
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_pub_produccion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Type Production")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_tipo_produccion", "", $arr_tipo_publicacion, ["class" => "form-control", "id" => "cmb_tipo_produccion"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="txt_pub_titulo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Title")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_pub_titulo" data-type="alfa" placeholder="<?= Academico::t("profesor", "Title") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_pub_editorial" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Editorial") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_pub_editorial" data-type="alfa" placeholder="<?= Academico::t("profesor", "Editorial") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_pub_isbn" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label">ISBN/ISSN <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_pub_isbn" data-type="alfa" placeholder="ISBN/ISSN">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_pub_autoria" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Author") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_pub_autoria" data-type="alfa" placeholder="<?= Academico::t("profesor", "Author") ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <button type="button" class="btn btn-primary" onclick="javascript:addPublicacion()"><?= Academico::t('profesor', 'Add') ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
<?=
    PbGridView::widget([
        'id' => 'grid_publicacion_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'TipoProduccion',
                'header' => Academico::t("profesor", "Type Production") ,
                'value' => 'TipoProduccion',
            ],
            [
                'attribute' => 'Titulo',
                'header' => Academico::t("profesor", "Title"),
                'value' => 'Titulo',
            ],
            [
                'attribute' => 'Editorial',
                'header' => Academico::t("profesor", "Editorial"),
                'value' => 'Editorial',
            ],
            [
                'attribute' => 'ISBN',
                'header' => 'ISBN/ISSN',
                'value' => 'ISBN',
            ],
            [
                'attribute' => 'Autor',
                'header' => Academico::t("profesor", "Author"),
                'value' => 'Autor',
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