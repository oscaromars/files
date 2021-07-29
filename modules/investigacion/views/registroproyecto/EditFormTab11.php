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
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'TipoProduccion',
                'header' => Academico::t("profesor", "Type Production") ,
                'value' => function($value){
                    if(isset($value['TipoProduccion']) && $value['TipoProduccion'] != "" )
                        return $value['TipoProduccion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Titulo',
                'header' => Academico::t("profesor", "Title"),
                'value' => function($value){
                    if(isset($value['Titulo']) && $value['Titulo'] != "" )
                        return $value['Titulo'];
                    return "";
                }
            ],
            [
                'attribute' => 'Editorial',
                'header' => Academico::t("profesor", "Editorial"),
                'value' => function($value){
                    if(isset($value['Editorial']) && $value['Editorial'] != "" )
                        return $value['Editorial'];
                    return "";
                }
            ],
            [
                'attribute' => 'ISBN',
                'header' => 'ISBN/ISSN',
                'value' => function($value){
                    if(isset($value['ISBN']) && $value['ISBN'] != "" )
                        return $value['ISBN'];
                    return "";
                }
            ],
            [
                'attribute' => 'Autor',
                'header' => Academico::t("profesor", "Author"),
                'value' => function($value){
                    if(isset($value['Autor']) && $value['Autor'] != "" )
                        return $value['Autor'];
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
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:', 'onclick' => 'javascript:removeItemPublicacion(this);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
        </div>
    </div>
</form>