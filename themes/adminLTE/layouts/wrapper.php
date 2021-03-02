<?php
/*
 * The PenBlu framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by PenBlu Software (http://www.penblu.com)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions 
 * are met:
 *
 *  - Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *  - Neither the name of PenBlu Software nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PenBlu is based on code by 
 * Yii Software LLC (http://www.yiisoft.com) Copyright © 2008
 *
 * Authors:
 *
 * Eduardo Cueva <ecueva@penblu.com>
 * 
 * Update:
 * 
 * Diana Lopez <dlopez@uteg.edu.ec>
 * 
 */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\models\ObjetoModulo;
use app\models\Modulo;
use app\models\Accion;
?>

<div class="content-wrapper">
    <?php 
    $breadcrumb = ObjetoModulo::getParentByObjModule($this->params["omod_id"], array());
    $mod = Modulo::findIdentity($this->params["mod_id"]);
    $sizeBc = count($breadcrumb);
    $posMod = $sizeBc - 1;
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $this->params["Module_name"] ?>
            <small><?= $this->params["ObjModPadre_name"] ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= Yii::$app->urlManager->createUrl([$breadcrumb[$posMod][1]]) ?>"><i class="<?= $mod->mod_dir_imagen ?>"></i> <?= $breadcrumb[$posMod][0] ?></a></li>
            <?php 
                for ($i=$posMod-1; $i>=0; $i--){
                    if($i==0)
                        echo '<li class="active"><a href="'.Yii::$app->urlManager->createUrl([$breadcrumb[$i][1]]).'">'.$breadcrumb[$i][0].'</a></li>';
                    else 
                        echo '<li><a href="'.Yii::$app->urlManager->createUrl([$breadcrumb[$i][1]]).'">'.$breadcrumb[$i][0].'</a></li>';
                }
            ?>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <span class="glyphicon glyphicon-th"></span>
                <h3 class="box-title"><?= $this->title ?></h3>
                <div class="box-tools pull-right">

                </div>
            </div>
            <div class="box-header with-border">
                <div class="btn-group"><!-- Carga de ObjetosModulos -->
                <?php
                $objModule    = new ObjetoModulo();
                $id_module    = $this->params["mod_id"];
                $id_omod      = $this->params["omod_id"];
                $id_omodpadre = $this->params["omod_padre_id"];
                $arrMod = $objModule->getObjModHijosXObjModPadre($id_module, $id_omod, $id_omodpadre);
                if(count($arrMod) > 0):
                ?>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?= $this->title ?>&nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-caret-down"></span></button>
                <?php if(count($arrMod) > 0):?>
                    <ul class="dropdown-menu">
                <?php 
                        $li = "";
                        foreach ($arrMod as $key => $value){
                            if($value['omod_id'] != $id_omod){
                                $li .= '<li><a href="'.Yii::$app->urlManager->createUrl($value['omod_entidad']).'">'.Yii::t($value['omod_lang_file'],$value['omod_nombre']).'</a></li>';
                            }
                        }
                        echo $li;
                    endif;
                ?>
                    </ul>
                <?php else: ?>
                <?php endif; ?> 
                </div>
                <div class="pull-right"><!-- Carga de Acciones -->
                <?php
                    $acciones = new Accion();
                    $arrAcc = $acciones->getAccionesXObjModulo($id_omodpadre, $id_omod);
                    if(count($arrAcc) > 0):
                ?>
                    <div class="btn-groups"> 
                <?php
                        $botones = "";
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
                                //if(!$isImg) // data-toggle="tooltip" data-placement="top" title="'.$acc_nombre.'"
                                    $botones .= '<button type="button" class="btn btn-default btnAccion" data-trigger="hover" '.$function.'><i class="'.$acc_imagen.'"></i>&nbsp;&nbsp;'.$acc_nombre.'</button>';
                            }else{
                                //if(!$isImg) // data-toggle="tooltip" data-placement="top" title="'.$acc_nombre.'"
                                    $botones .= '<a href="'.Yii::$app->urlManager->createUrl($value["oacc_cont_accion"]).'" class="btn-default"><button type="button" class="btn btn-default btnAccion" data-trigger="hover"><i class="'.$acc_imagen.'"></i>&nbsp;&nbsp;'.$acc_nombre.'</button></a>';
                            }
                        }
                        echo $botones;
                ?>
                    </div>
                <?php
                    endif;
                ?>
                </div>
            </div>
            <div class="box-body">
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

