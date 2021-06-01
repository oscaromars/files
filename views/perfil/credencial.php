<?php
/*
 * The Asgard framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by Asgard Software 
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
 *  - Neither the name of Asgard Software nor the names of its
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
 * Asgard is based on code by
 * Yii Software LLC (http://www.yiisoft.com) Copyright Â© 2008
 *
 * Authors:
 * 
 * Diana Lopez <dlopez@uteg.edu.ec>
 * 
 */

use app\models\Utilities;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Security;
use CodeItNow\BarcodeBundle\Utils\QrCode;

$security = new Security();
$idper  = Yii::$app->session->get('PB_perid', FALSE);
$idemp  = Yii::$app->session->get('PB_idempresa', FALSE);
$dataId = ["per_id" => $idper, "emp_id" => $idemp];
$dataIdCipr = Utilities::base64_url_encode($security->encryptByPassword(json_encode($dataId), Yii::$app->params['keywordEncription']));
$urlQr = Url::base(true) . "/site/getcredencial?id=".$dataIdCipr;

$qrCode = new QrCode();
$qrCode
    ->setText($urlQr)
    ->setSize(300)
    ->setPadding(10)
    ->setErrorCorrection('high')
    ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
    ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
    ->setLabel(Yii::t("formulario", "Scan QR Code"))
    ->setLabelFontSize(16)
    ->setImageType(QrCode::IMAGE_TYPE_PNG);

 ?>
<div>
    <div class="col-md-4">
        <div class="bg-aqua carnet">
            <div>
                <div class="margin-credencial"><img class="imgCred" src="<?= $img_front ?>" alt="Credencial" /></div>
                <?php /*<div class="margin-credencial"><img src="<?= $img_back ?>" alt="Credencial" /></div>*/ ?>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="bg-aqua carnet">
            <div class="col-md-12">
                <div style="text-align: center;">
                    <?= '<img class="imgQR" src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />'; ?>
                </div>
                <br /><br />
                <div>
                    <div class="table-responsive">
                        <?php if($isEstudiante): ?>
                        <table class="table">
                            <tbody><tr>
                                <th style="width:50%"><?= Yii::t('formulario','Student') ?>:</th>
                                <td><?= $nombre_estudiante ?></td>
                            </tr>
                            <tr>
                                <th><?= Yii::t('academico', 'Career') ?>:</th>
                                <td><?= $carrera ?></td>
                            </tr>
                            <?php /*
                            <tr>
                                <th><?= Yii::t('formulario','Study mode') ?>:</th>
                                <td><?= $modalidad ?></td>
                            </tr>
                            */
                            ?>
                            <tr>
                                <th><?= Yii::t('formulario', 'Register') ?>:</th>
                                <td><?= $matricula ?></td>
                            </tr>
                            </tbody></table>
                        <?php elseif($isDocente): ?>
                        <table class="table">
                            <tbody><tr>
                                <th style="width:50%"><?= Yii::t('formulario','Teacher') ?>:</th>
                                <td><?= $nombre_estudiante ?></td>
                            </tr>
                        </tbody></table>
                        <?php else: ?>
                        <table class="table">
                            <tbody><tr>
                                <th style="width:50%"><?= Yii::t('formulario','Names') ?>:</th>
                                <td><?= $nombre_estudiante ?></td>
                            </tr>
                            <tr>
                                <th><?= Yii::t('formulario', 'Charges Play') ?>:</th>
                                <td><?= $cargo ?></td>
                            </tr>
                        </tbody></table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

