<?php
/*
 * The Asgard framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2017 by Asgard Software
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
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>
<style>
    .thumbnail{
        background: rgba(255, 255, 255, 0.8) none repeat scroll 0 0;
        border-radius: 0px;
    }
    .caption{
        min-height: 300px;
    }
</style>
<div class="row">
    <?php foreach ($modules as $item => $values) { ?>
        <?php if($values->dash_estado == 1 && $values->dash_estado_logico == 1)  { ?>
        <div class="col-sm-6 col-md-4">
            <div class="thumbnail">
                <div class="caption">
                    <h2><?= $values->dash_title ?></h2>
                    <?php foreach($dash_items as $key => $ditem){
                        if(($ditem['dash_id'] == $values->dash_id)  && ($ditem['dite_estado'] == '1' && $ditem['dite_estado_logico'] == '1') ) :
                    ?>
                    <?php if(empty($ditem->dite_detail))  { ?>
                    <a class="btn btn-primary btn-flat margin" href="<?= (isset($ditem->dite_link) && $ditem->dite_link != "")?(Url::base() . $ditem->dite_link):"javascript:" ?>" target="_blank"><?= $ditem->dite_title ?></a>
                    <?php } if($ditem->dite_detail == 1) { ?>
                    <a class="btn btn-primary btn-flat margin" href="<?= (isset($ditem->dite_link) && $ditem->dite_link != "")?( $ditem->dite_link):"javascript:" ?>"target="_blank"><?= $ditem->dite_title ?></a>
                    <?php } ?>
                    <?php if($ditem->dite_detail == 2) { ?>
                    <?php
                        $nombre = explode("/", $ditem->dite_link);
                        echo "<a class='btn btn-primary btn-flat margin' href='" . Url::to(['/site/getimage', 'route' => "$ditem->dite_link"]) . "' download='. $nombre[2].pdf .' ><span></span>$ditem->dite_title</a>"
                    ?><?php } ?>
                    <?php endif; } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    <?php } ?>
</div>