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
use yii\helpers\Url;
use app\assets\FontAwesomeAsset;
use app\assets\AppAsset;
use app\themes\adminLTE\resources\AdminLTEAsset;
use app\models\Menu;
use odaialali\yii2toastr\ToastrAsset;
use app\vendor\penblu\blockui\BlockuiAsset;
use app\vendor\penblu\magnificpopup\MagnificPopupAsset;

Menu::getScripts($this, Yii::$app->controller->id, Yii::$app->controller->module->id);
/* @var $this \yii\web\View */
/* @var $content string */

$assetsAdminLTE = AdminLTEAsset::register($this);
$assetsApp = AppAsset::register($this);
$assetsFont = FontAwesomeAsset::register($this);
$assetsToastr = ToastrAsset::register($this);
$assetsBlockui = BlockuiAsset::register($this);
$assetsPopup = MagnificPopupAsset::register($this);

//$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@bower') . '/admin-lte';
$directoryAsset = $assetsAdminLTE->baseUrl;
$session = Yii::$app->session;
$isUser = FALSE;
if ($session->isActive){
    $isUser = ($session->get('PB_isuser'))?TRUE:FALSE;
}
/******************************************************************
* ini - cambio por: Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
* ****************************************************************/
if(!$isUser){
    //return Yii::$app->response->redirect(Url::to(['site/login', 'id' => id]));
    
    //return Yii::$app->response->redirect(Url::to(['site/login']));
    //return Yii::$app->response->redirect(Url::base(true).'site/login');
    return Yii::$app->response->redirect(Url::home(false).'site/login');
}
/*
else{
    //echo(Url::to(['site/login']));
    //echo(Url::base(true).'/site/login'); 
    echo( Url::home(false).'site/login' ); 
    die();
}*/
/*************************************************
* fin 
* ***********************************************/

if (Yii::$app->controller->action->id === 'login' && $isUser) {
    echo $this->render(
        'login',
        ['content' => $content]
    );
} elseif(isset($_GET['popup']) || isset($_POST['popup'])) {
    require_once("popup.php");
}else {
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="shortcut icon" href="<?= $directoryAsset; ?>/img/logos/favicon.ico" type="image/x-icon" />
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <?php Menu::generateJSLang("messages", Yii::$app->language); ?>
    </head>
    <!--
    BODY TAG OPTIONS:
    =================
    Apply one or more of the following classes to get the 
    desired effect
    |---------------------------------------------------------|
    | SKINS         | skin-blue                               |
    |               | skin-blue-light                         |
    |               | skin-black                              |
    |               | skin-black-light                        |
    |               | skin-purple                             |
    |               | skin-purple-light                       |
    |               | skin-yellow                             |
    |               | skin-yellow-light                       |
    |               | skin-red                                |
    |               | skin-red-light                          |
    |               | skin-green                              |
    |               | skin-green-light                        |
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |  
    |---------------------------------------------------------|

    -->
    <body class="hold-transition skin-blue sidebar-mini body-system">
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <!-- Main Header -->
            <?= $this->render('header.php', ['directoryAsset' => $directoryAsset]) ?>

            <!-- Left side column. contains the logo and sidebar -->
            <?= $this->render('left.php', ['directoryAsset' => $directoryAsset]) ?>

            <!-- Content Wrapper. Contains page content -->
            <?= $this->render('wrapper.php', ['directoryAsset' => $directoryAsset, 'content' => $content]) ?>

            <!-- Main Footer -->
            <?= $this->render('footer.php', ['directoryAsset' => $directoryAsset, 'footerClass' => 'main-footer']) ?>

            <!-- SideBar Content -->
            <?php // $this->render('sidebar.php', ['directoryAsset' => $directoryAsset]); ?>
        </div>
        <!-- Modal -->
        <?= $this->render('modal.php', ['directoryAsset' => $directoryAsset]); ?>
        <?= $this->render('hiddenVars.php', ['directoryAsset' => $directoryAsset]); ?>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
<?php } ?>
