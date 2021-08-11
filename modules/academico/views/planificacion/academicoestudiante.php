<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Utilities;
use app\widgets\PbGridView\PbGridView;
$this->title = Yii::t('app', 'Resumen de Planificacion');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div class="row">
   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
        <div class="form-group">
            <div class="col-lg-6 col-md-6">
                <label for="lbl_modalidad" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 control-label"><?= Yii::t("formulario", "Mode"); ?></label>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <?= Html::dropDownList("cmb_modalidadesacad", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidadesacad"]) ?>
                </div> 
            </div>
            <div class="col-lg-6 col-md-6">
                <label for="lbl_periodo" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 control-label"><?= Yii::t("formulario", "Period"); ?></label>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <?= Html::dropDownList("cmb_periodoacad", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodoacad"]) ?>
                </div>                  
            </div>                  
        </div>        
    </div>     
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">  
        <div class="form-group">
            <div class="col-lg-6 col-md-6">
                <label for="lbl_bloque"class="col-lg-6 col-md-6 col-sm-12 col-xs-12 control-label"><?= Yii::t("formulario", "Bloque"); ?></label>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <?= Html::dropDownList("cmb_bloqueacad", 0, $arr_bloque, ["class" => "form-control", "id" => "cmb_bloqueacad"]) ?>
                </div> 
            </div> 
            <div class="col-lg-6 col-md-6">            
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"  style="float:right">            
                <a id="btn_buscarPlanestudiante" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">        
    <?=
    PbGridView::widget([
        'id' => 'PbPlanificaestudiante',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelplanificacion",
        'fnExportPDF' => "exportPdfplanificacion",
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'Materia',
                'header' => Yii::t("formulario", "Materia"),
                'value' => 'Materia',
            ],
            [
                'attribute' => 'Paralelo',
                'header' => Yii::t("formulario", "Paralelo"),
                'value' => 'Paralelo',
            ],
            [
                'attribute' => 'Cantidad',
                'header' => Yii::t("formulario", "Cantidad"),
                'value' => 'total',
            ],
            [
                'attribute' => 'Bloque',
                'header' => Yii::t("formulario", "Bloque"),
                'value' => 'bloque',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'header' => 'Acciones',
                //'template' => '{view}{delete}{Approbe}{Download}{Reversar}',
                'template' => '{view}',
              //  'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['/academico/planificacion/academicoestudianteview', 
                                                                                                            'id' => $model['id'],
                                                                                                            'modalidad' => $model['mod_id'],
                                                                                                            'periodo' => $model['saca_id'],
                                                                                                            'bloque' => substr($model['bloque'],-1),
                                                                                                            'materia' => $model['Materia'],
                                                                                                            'mpp_id' => $model['mpp_id'],
                                                                                                            ]));
                    },
                ],
            ],
        ],
    ])
    ?>
</div>   
