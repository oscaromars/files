<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as Especies;
use app\modules\financiero\Module as Pagos;

Especies::registerTranslations();
Pagos::registerTranslations();
?>

<?= Html::hiddenInput('txth_idest', $arr_persona['est_id'], ['id' => 'txth_idest']); ?>
<?= Html::hiddenInput('txth_per', @Yii::$app->session->get("PB_perid"), ['id' => 'txth_per']); ?>
<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">  
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Student Data") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_nombre'] . " " . $arr_persona['per_seg_nombre'] . " " . $arr_persona['per_pri_apellido'] . " " . $arr_persona['per_seg_apellido'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Pagos::t("Pagos", "Cell") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_cedula'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_matricula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Enrollment") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['est_matricula'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_categoria" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Category") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['est_categoria'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Academic Data") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_ninteres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Academic unit") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_ninteres", $arr_persona['uaca_id'], array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_ninteres", "disabled" => "true"]) ?>
                    </div>
                </div>  
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divModalidad">
                <div class="form-group">
                    <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Modality") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_modalidad", $arr_persona['mod_id'], array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>                             
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_carrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Career/Program") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_carrera", $arr_persona['eaca_id'], $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera", "disabled" => "true"]) ?>
                    </div>
                </div>                                        
            </div>
        </div>
    </div>
    <div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12 col-lg-12"></div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            &nbsp;&nbsp;
        </div>
    </div>  
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Pending Invoices Data") ?></span></h4> 
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <?=
        PbGridView::widget([
            'id' => 'TbgPagopendiente',
            'dataProvider' => $model,
            'columns' => [
                [
                    'attribute' => 'Factura',
                    'header' => Pagos::t("Pagos", "Bill"),
                    'value' => 'NUM_NOF',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t("formulario", "Subject"),
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (strlen($model['MOTIVO']) > 30) {
                                $texto = '...';
                            }
                            return Html::a('<span>' . substr($model['MOTIVO'], 0, 20) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['MOTIVO']]);
                        },
                    ],
                ],
                [
                    'attribute' => 'Fecha_factura',
                    'header' => Pagos::t("Pagos", "Date Bill"),
                    'value' => 'F_SUS_D',
                ],
                [
                    'attribute' => 'Saldo',
                    'header' => Pagos::t("Pagos", "Balance"),
                    'value' => 'SALDO',
                ],
                [
                    'attribute' => 'Cuota_pendiente',
                    'header' => Pagos::t("Pagos", "Pending Fee"),
                    'value' => 'cuota',
                ],
                [
                    'attribute' => 'vencimiento',
                    'header' => Pagos::t("Pagos", "Expiration date"),
                    'value' => 'F_VEN_D',
                ],
                [
                    'attribute' => 'cantidad',
                    'header' => Pagos::t("Pagos", "Amount Fees"),
                    'value' => 'cantidad',
                ],
            ],
        ])
        ?>
    </div>          
</form>