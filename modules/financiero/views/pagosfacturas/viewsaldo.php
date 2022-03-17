<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as Especies;
use app\modules\financiero\Module as Pagos;
//print_r($model);
Especies::registerTranslations();
Pagos::registerTranslations();
//print_r($nombregrupo['gru_nombre']);
use app\assets\DatatableAsset;
DatatableAsset::register($this);
?>
<?php if($nombregrupo['gru_nombre'] == 'Super Admin' || $nombregrupo['gru_nombre'] == 'Colecturia'){ ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <div class="pull-right">
        <div class="btn-groups">
            <button type="button" class="btn btn-default btnAccion" data-trigger="hover" onclick="subirpago()"><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;Subir Pago</button>
        </div>
    </div>
</div>
<?php } ?>
<?= Html::hiddenInput('txth_idest', $arr_persona['est_id'], ['id' => 'txth_idest']); ?>
<?= Html::hiddenInput('txth_per', @Yii::$app->session->get("PB_perid"), ['id' => 'txth_per']); ?>
<?= Html::hiddenInput('txth_per_ids', $_GET["per_ids"], ['id' => 'txth_per_ids']); ?>
<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-row">
            <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Student Data") ?></span></h4>
        </div>
    </div>
    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
        <div class="form-group">
            <label for="txt_nombres" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_nombre'] . " " . $arr_persona['per_seg_nombre'] . " " . $arr_persona['per_pri_apellido'] . " " . $arr_persona['per_seg_apellido'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
            </div>
        </div>
    </div>
    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
        <div class="form-group">
            <label for="txt_cedula" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" id="lbl_nombre1"><?= Pagos::t("Pagos", "Cell") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_cedula'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
            </div>
        </div>
    </div>
    <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
        <div class="form-group">
            <label for="txt_matricula" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Enrollment") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['est_matricula'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "Matrícula") ?>">
            </div>
        </div>
    </div>
    <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
        <div class="form-group">
            <label for="txt_categoria" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Category") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['est_categoria'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-row">
            <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Academic Data") ?></span></h4>
        </div>
    </div>
    <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
        <div class="form-group">
            <label for="cmb_ninteres" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Especies::t("Academico", "Academic unit") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_ninteres", $arr_persona['uaca_id'], array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_ninteres", "disabled" => "true"]) ?>
            </div>
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="divModalidad">
        <div class="form-group">
            <label for="cmb_modalidad" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Especies::t("Academico", "Modality") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_modalidad", $arr_persona['mod_id'], array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
            </div>
        </div>
    </div>
    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
        <div class="form-group">
            <label for="cmb_carrera" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Especies::t("Academico", "Career/Program") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_carrera", $arr_persona['eaca_id'], $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera", "disabled" => "true"]) ?>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-row">
            <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Pending Invoices Data") ?></span></h4>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <?=
        PbGridView::widget([
            'id' => 'TbgPagopendiente',
            'dataProvider' => $model,
            'columns' => [
                [
                    'attribute' => 'Cuota_pendiente',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Pending Fee"),
                    'value' => 'cuota',
                ],
                [
                    'attribute' => 'Factura',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Bill"),
                    'value' => 'NUM_NOF',
                ],
                /*[
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
                ],*/
                [
                    'attribute' => 'Fecha_factura',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Date Bill"),
                    'value' => 'F_SUS_D',
                ],
                /*[
                    'attribute' => 'Saldo',
                    'header' => Pagos::t("Pagos", "Balance"),
                    'value' => 'SALDO',
                ],*/
                [
                    'attribute' => 'valor_cuota',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Quota value"),
                    'value' => 'ccar_valor_cuota',
                ],
                [
                    'attribute' => 'vencimiento',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Expiration date"),
                    'value' => 'F_VEN_D',
                ],
                /*
                [
                    'attribute' => 'cantidad',
                    'header' => Pagos::t("Pagos", "Amount Fees"),
                    'value' => 'cantidad',
                ],
                */
                [
                    'attribute' => 'Abono',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Abono"),
                    'value' => 'abono',
                ],
                [
                    'attribute' => 'Saldo',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Saldo"),
                    'value' => 'saldo',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t("formulario", "Actions"),'contentOptions' => ['style' => 'text-align: center;'],
                    'headerOptions' => ['width' => '60'],
                    'template' => '{delete}{update}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            if($model['grupo'] == 'Super Admin' || $model['grupo'] == 'Colecturia'){
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', "#", ['onclick' => "eliminarpagcartera(" . $model['ccar_id'] . ");", "data-toggle" => "tooltip", "title" => "Eliminar Registro", "data-pjax" => 0]);
                             }else return " ";
                        },
                        'update' => function ($url, $model) {
                            if($model['grupo'] == 'Super Admin' || $model['grupo'] == 'Colecturia'){
                                if($model['abono'] == 0 ){
                                    return Html::a('<span class="glyphicon glyphicon-file"></span>', "#", ['onclick' => "modificarcuota(" . $model['ccar_id'] . ");", "data-toggle" => "tooltip", "title" => "Modificar Cuota", "data-pjax" => 0]);
                                }else{
                                    return '<span class="glyphicon glyphicon-file"></span>';
                                }
                            }else return " ";
                        },
                    ],
                ],

            ],
        ])
        ?>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        var persest  =   $('#txth_per_ids').val();
        if (persest === null || persest === '') {
            target = 5;
         }else{
            target = 7;
            }
        $('#TbgPagopendiente > table').DataTable({
            "dom": 't',
            responsive: true,
            columnDefs: [
                { targets: 0, responsivePriority: 1},
                { targets: 3, responsivePriority: 2},
                { targets: target, responsivePriority: 3},
            ],
        });
    });
</script>

<style type="text/css">
    .barexportp{
        display: none;
    }

    #TbgPagopendiente > .summary{
        display: none;
    }

    .sorting{
        text-align: center;
    }
</style>