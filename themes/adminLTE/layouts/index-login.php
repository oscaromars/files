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
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('login', 'Log In Session');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6 logintext">
    <h1 class="font-bold"><?= Yii::t("login", "") ?></h1><br />
    <p><?= Yii::t("login", "") ?></p>
</div>
<div class="login-box-body col-md-6">
    <div class="login-logo">
        <a href="<?= Html::encode(Yii::$app->params['web']) ?>"><img src="<?= Html::encode($directoryAsset . "/img/logos/logo.png") ?>" alt="logo" /></a>
    </div><!-- /.login-logo -->
        <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-error">
        <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    <p class="login-box-msg" style="font-size: 20px; display: none;"><?= Html::encode(Yii::t('login', 'Log In Session')) ?></p>
    <?php
    $form = ActiveForm::begin([
                'id' => 'login-form',
    ]);
    ?>
    <?=
    $form->field($model, 'username', [
        'inputOptions' => ['placeholder' => Html::encode(Yii::t('login', 'Email'))],
        'template' => "<div class=\"form-group has-feedback\">{input}\n<span class=\"glyphicon glyphicon-envelope form-control-feedback\"></span>\n{error}</div>"
            ,])
    ?>
<?=
$form->field($model, 'password', [
    'inputOptions' => ['placeholder' => Html::encode(Yii::t('login', 'Password'))],
    'template' => "<div class=\"form-group has-feedback\">{input}\n<span class=\"glyphicon glyphicon-lock form-control-feedback\"></span>\n{error}</div>"
        ,])->passwordInput()
?>

    <div class="row">
        <div class="col-xs-8">
            <a href="<?= Yii::$app->urlManager->createUrl(["site/forgotpass"]) ?>"><?= Html::encode(Yii::t('login', 'I forgot my password')) ?></a><br>
        </div><!-- /.col -->
        <div class="col-xs-4">
<?= Html::submitButton(Html::encode(Yii::t('login', 'Sign In')) . "&nbsp;<i class='fa fa-arrow-circle-right'></i>", ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button', 'style' => 'margin-top: 4px;']) ?>
        </div><!-- /.col -->
    </div>
<?php ActiveForm::end(); ?>
</div>
<style>
    .checkbox {
        margin-top: 0px !important;
    }
    .checkbox label {
        padding-left: 0px !important;
    }
</style>
