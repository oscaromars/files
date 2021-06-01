<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\models\ObjetoModulo;
use app\models\Modulo;
use app\models\Accion;
?>

<div class="content-wrapper content-popwrapper">
    <!-- Main content -->
    <section class="content-popup">
        <div class="box">
            <div class="box-header with-border">
                <span class="glyphicon glyphicon-th"></span>
                <h3 class="box-title"><?= $this->title ?></h3>
                <div class="box-tools pull-right">
                    <?php /*
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm" placeholder="<?= Yii::t("app", "Search") ?>" onkeyup="searchForm_<?= str_replace("/", "_", Yii::$app->controller->route) ?>()">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                    */
                    ?>
                </div>
            </div>
            <div class="box-header with-border">
                <div class="btn-group"><!-- Carga de ObjetosModulos -->
                <?php
               // $objModule    = new ObjetoModulo();
                //$id_module    = $this->params["mod_id"];
                //$id_omod      = $this->params["omod_id"];
                //$id_omodpadre = $this->params["omod_padre_id"];
                //$arrMod = $objModule->getObjModHijosXObjModPadre($id_module, $id_omod, $id_omodpadre);
                //if(count($arrMod) > 0):
                ?>
                    <!--<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?= $this->title ?>&nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-caret-down"></span></button>-->
                <?php //if(count($arrMod) > 0):?>
                    <!--<ul class="dropdown-menu">-->
                <?php 
                      /*  $li = "";
                        foreach ($arrMod as $key => $value){
                            if($value['omod_id'] != $id_omod){
                                $li .= '<li><a href="'.Yii::$app->urlManager->createUrl($value['omod_entidad']).'">'.Yii::t($value['omod_lang_file'],$value['omod_nombre']).'</a></li>';
                            }
                        }
                        echo $li;
                    endif;*/
                ?>
                    <!--</ul>-->
                <?php// else: ?>
                <?php// endif; ?> 
                </div>
                <div class="pull-right"><!-- Carga de Acciones -->
                <?php
                  /*  $acciones = new Accion();
                    $arrAcc = $acciones->getAccionesXObjModulo($id_omodpadre, $id_omod);
                    if(count($arrAcc) > 0):*/
                ?>
                    <div class="btn-groups"> 
                <?php
                      /*  $botones = "";
                        foreach($arrAcc as $key => $value){
                            $acc_imagen = $value["acc_dir_imagen"];
                            $isImg = false;
                            if(preg_match("/(\.png|\.jpeg|\.jpg)/i", $acc_imagen)){
                                $isImg = true;
                            }
                            $acc_lang_file = isset($value["acc_lang_file"])?$value["acc_lang_file"]:"menu";
                            $acc_nombre = Yii::t($acc_lang_file, $value["acc_nombre"]);
                            if(isset($value["oacc_function"]) && $value["oacc_tipo_boton"] == 1){
                                $function = 'onclick="'.$value["oacc_function"].'()"';
                                if(!$isImg) // data-toggle="tooltip" data-placement="top" title="'.$acc_nombre.'"
                                    $botones .= '<button type="button" class="btn btn-default btnAccion" data-trigger="hover" '.$function.'><i class="'.$acc_imagen.'"></i>&nbsp;&nbsp;'.$acc_nombre.'</button>';
                            }else{
                                if(!$isImg) // data-toggle="tooltip" data-placement="top" title="'.$acc_nombre.'"
                                    $botones .= '<a href="'.Yii::$app->urlManager->createUrl($value["oacc_cont_accion"]).'" class="btn-default"><button type="button" class="btn btn-default btnAccion" data-trigger="hover"><i class="'.$acc_imagen.'"></i>&nbsp;&nbsp;'.$acc_nombre.'</button></a>';
                            }
                        }
                        echo $botones;*/
                ?>
                    </div>
                <?php
                   // endif;
                ?>
                </div>
            </div>
            <div class="box-body">
                <br />
                <div>
                <?php if (Yii::$app->session->hasFlash('error')): ?>
                    <div class="alert alert-error">
                        <?= Yii::$app->session->getFlash('error') ?>
                    </div>
                    <?php endif;  ?>
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-success">
                        <?= Yii::$app->session->getFlash('success') ?>
                    </div>
                    <?php endif;  ?>
                    <?php if (Yii::$app->session->hasFlash('warning')): ?>
                    <div class="alert alert-warning">
                        <?= Yii::$app->session->getFlash('warning') ?>
                    </div>
                    <?php endif;  ?>
                </div>
                <?= $content ?>
            </div><!-- /.box-body -->
            <!--<div class="box-footer">
                Footer
            </div>--><!-- /.box-footer-->
        </div><!-- /.box -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
