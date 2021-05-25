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
 * Authors:
 * 
 * Diana Lopez <dlopez@uteg.edu.ec>
 * 
 */
?>
<style>
    .thumbnail{
        background: rgba(255, 255, 255, 0.8) none repeat scroll 0 0;
        border-radius: 0px;
    }
</style>
<div class="row">
    <?php foreach ($modules as $item => $values) { ?>
        <div class="col-sm-6 col-md-4">
            <div class="thumbnail">
                <div class="caption">
                    <h3><?= $values->dash_title ?></h3>
                    <p><?= $values->dash_detail ?></p>
                    <p class="text-right">
                        <a href="<?= ($values->dash_link == '[[BASE_VIDEO]]')?$url_video:(($values->dash_link == '[[FIRST_MODULE]]')?$url_asgard:(($values->dash_link == '[[LOGIN_EDUCATIVA]]')?$url_educativa:$values->dash_link)) ?>" target='<?= $values->dash_target ?>' class="btn btn-primary btn-flat" role="button">Ir&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>
                    </p>
                </div>
            </div>
        </div>
    <?php } ?>
</div>