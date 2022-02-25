
<?php
use yii\helpers\Url;
use yii\helpers\Html;

?>
<?= Html::hiddenInput('txth_twin_id', 0, ['id' => 'txth_twin_id']); ?>
<?= Html::hiddenInput('txth_ftem_id', 0, ['id' => 'txth_ftem_id']); ?>
<?= Html::hiddenInput('txth_errorFile', Yii::t("formulario", "The file extension is not valid or exceeds the maximum size in MB recommending him try again"), ['id' => 'txth_errorFile']); ?>

<div class="col-md-12  col-xs-12 col-sm-12 col-lg-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs tabsdatos col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <li class="active"><a href="#paso1" data-href="#paso1" data-toggle="tab" aria-expanded="true"><img class="" src="<?= Url::home() ?>img/users/n1.png" alt="User Image">  <?= Yii::t("formulario", "Record your data") ?></a></li>
            <li class="disabled"><a data-href="#paso2" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n2.png" alt="User Image">  <?= Yii::t("formulario", "Upload documents") ?></a></li>
            <li class="disabled"><a data-href="#paso3" data-toggle="none" aria-expanded="false"><img class="" src="<?= Url::home() ?>img/users/n3.png" alt="User Image">  <?= Yii::t("formulario", "Summary") ?></a></li>
        </ul>
        <div class="tab-content col-md-12  col-xs-12 col-sm-12 col-lg-12">
            <div class="tab-pane active" id="paso1">
                <form class="form-horizontal">
                    <?=
                        $this->render('_form_tab1', [
                                 "tipos_dni" => $tipos_dni,
                                 "tipos_dni2" => $tipos_dni2,
                                 "txth_extranjero" => $txth_extranjero,
                                 "arr_pais_dom" => $arr_pais_dom,
                                 "arr_prov_dom" => $arr_prov_dom,
                                 "arr_ciu_dom" => $arr_ciu_dom,
                                 "arr_ninteres" => $arr_ninteres,
                                 "arr_medio" => $arr_medio,
                                 "arr_modalidad" => $arr_modalidad,
                                 "arr_conuteg" => $arr_conuteg,
                                 "arr_carrerra1" => $arr_carrerra1,
                                 "arr_metodos" => $arr_metodos,
                         ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso2">
                <form class="form-horizontal">
                    <?=
                        $this->render('_form_tab3', [
                            'per_id' => $per_id,
                            "arr_convenio_empresa" => $arr_convenio_empresa,
                        ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="paso3">
                <form class="form-horizontal">
                    <?=
                    $this->render('_form_tab2', [
                        'per_id' => $per_id,
                        "arr_convenio_empresa" => $arr_convenio_empresa,
                    ]);
                    ?>
                </form>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->
</div><!-- /.col -->