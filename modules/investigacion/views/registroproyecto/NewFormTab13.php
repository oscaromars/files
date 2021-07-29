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
            <label for="txt_eva_periodo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Evaluation Period")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_eva_periodo" data-type="alfa" placeholder="<?= Academico::t("profesor", "Evaluation Period") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_eva_institucion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Evaluating Institution")?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_eva_institucion" data-type="alfa" placeholder="<?= Academico::t("profesor", "Evaluating Institution") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_eva_evaluacion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Evaluation Obtained") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations" id="txt_eva_evaluacion" data-type="alfa" placeholder="<?= Academico::t("profesor", "Evaluation Obtained") ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <button type="button" class="btn btn-primary" onclick="javascript:addEvaluacion()"><?= Academico::t('profesor', 'Add') ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
<?=
    PbGridView::widget([
        'id' => 'grid_evaluacion_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Periodo',
                'header' => Academico::t("profesor", "Evaluation Period") ,
                'value' => 'Periodo',
            ],
            [
                'attribute' => 'Institucion',
                'header' => Academico::t("profesor", "Evaluating Institution"),
                'value' => 'Institucion',
            ],
            [
                'attribute' => 'Evaluacion',
                'header' => Academico::t("profesor", "Evaluation Obtained"),
                'value' => 'Evaluacion',
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