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

use yii\helpers\Url;
use \app\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\models\Empresa;

$empresas = Empresa::getListaEmpresasxUserID(Yii::$app->session->get("PB_iduser"));
?>

<header class="main-header" style="position: fixed; width:100%">

    <!-- Logo -->
    <a href="<?= Yii::$app->params['web'] ?>" class="logo" >
        <!-- mini logo for sidebar mini 50x50 pixels -->

        <span class="logo-mini">
            <!--b><?= Yii::$app->params['alias'] ?></b-->
            <img src="<?= Html::encode($directoryAsset . "/img/logos/logop.png") ?>" alt="logo" style="height: 100%;" />
        </span>
        <!--<img src="<?= Html::encode($directoryAsset . "/img/logos/logo-back.png") ?>" alt="logo" style="height: 100%;" /> -->
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg" style="height: 100%;">
            <!--b><?= Yii::$app->params['copyright'] ?></b-->
            <img src="<?= Html::encode($directoryAsset . "/img/logos/logop.png") ?>" alt="logo" style="height: 100%;" />
        </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="javascript:" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i class="fas fa-bars"></i>
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown notifications-menu">
                    <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">
                      <span class="hidden-xs"><?= @Yii::$app->session->get("PB_empresa") ?>&nbsp;&nbsp;<i class="glyphicon glyphicon-menu-down"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li class="header">Empresas a la que Pertenece:</li>
                      <li>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu">
                            <?php foreach($empresas as $index => $value){
                                $indexpath = ($value['id'] == @Yii::$app->session->get("PB_idempresa"))?">":"";
                                echo '<li><a href="'. Yii::$app->urlManager->createUrl(["site/changeempresa/?id=$value[id]"]) .'" data-id="'.$value['id'].'">' . $indexpath . " " . $value['name'] . '</a></li>';
                            } ?>
                        </ul>
                      </li>
                    </ul>
                </li>
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="javascript:" class="dropdown-toggle usuSession" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="<?= Url::base() . "/" . @web . "/img/user.png" ?>" class="user-image" data-user="" data-id="" data-per="" alt="User Image"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs"><?= @Yii::$app->session->get("PB_nombres") ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="<?= Url::base() . "/" . @web . "/img/user.png" ?>" class="img-circle" alt="User Image" />
                            <p>
<?= @Yii::$app->session->get("PB_nombres") ?>
                                <!--<small>Web Developer</small>-->
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                
                                <!--
<?= Html::a(Html::encode(Yii::t("perfil", "Profile")), ['/perfil/index'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']) ?>
                                -->
                            </div>
                            <div class="pull-right"><!--logout  -->
<?= Html::a(Html::encode("Salir"), ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
