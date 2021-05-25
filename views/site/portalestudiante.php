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

use app\themes\adminLTE\assets\plugins\filetree\FiletreeAsset;

$assetsFileTree = FiletreeAsset::register($this);
?>

<div class="row">
    <div class="accordion" id="accordion">
        <div class="card">
            <div class="card-header" id="headingTab1">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTab1" aria-expanded="true" aria-controls="collapseTab1">
                        Vídeos Tutoriales 
                    </button>
                </h5>
            </div>
            <div id="collapseTab1" class="collapse" aria-labelledby="headingTab1" data-parent="#accordion">
                <div class="row">
                    <?php foreach ($modules_1 as $item => $values) { ?>
                        <div class="col-sm-6 col-md-4">
                            <div class="thumbnail">               
                                <div class="caption">
                                    <h4><?= $values["title"] ?></h4>
                                    <h5><?= $values["sub_title"] ?></h5>
                                    <p><iframe allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" 
                                               webkitallowfullscreen="webkitallowfullscreen" frameborder="0" height="100%" 
                                               src=<?= $values["detail"] ?> width="100%"></iframe></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTab2">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTab2" aria-expanded="true" aria-controls="collapseTab2">
                        Instructivos Generales - Guía General de la Facultad Online 
                    </button>
                </h5>
            </div>
            <div id="collapseTab2" class="collapse" aria-labelledby="headingTab2" data-parent="#accordion">
                <div class="row">

                    <?php
                    $this->registerJs(/* <<< EOT_JS_CODE */

                            "$(document).ready( function() { 
                        $('.treeviewc').fileTree(
                            { 
                            root: '" . $rootfolder . "', 
                            script: '" . $script . "' 
                            }, function(file) 
                                    {
                                        var downFile = $('#txth_base').val() + '/site/portalestudiante?dfile=' + file;
                                        console.log(downFile);
                                        window.location = downFile;
                                    });
                                } );"
                            /* EOT_JS_CODE */
                    );
                    ?>
                    <script>

                    </script>
                    <div style="margin: 5px 20px;"><div class="treeviewc"></div></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTab3">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTab3" aria-expanded="true" aria-controls="collapseTab3">
                        Vídeos de Bienvenida CAN
                    </button>
                </h5>
            </div>
            <div id="collapseTab3" class="collapse" aria-labelledby="headingTab3" data-parent="#accordion">
                <div class="row">
                    
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTab4">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTab4" aria-expanded="true" aria-controls="collapseTab4">
                        Vídeos de Bienvenida - BLOQUE 
                    </button>
                </h5>
            </div>
            <div id="collapseTab4" class="collapse" aria-labelledby="headingTab4" data-parent="#accordion">
                <div class="row">
                    
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTab5">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTab5" aria-expanded="true" aria-controls="collapseTab5">
                        Metodologías de estudio 
                    </button>
                </h5>
            </div>
            <div id="collapseTab5" class="collapse" aria-labelledby="headingTab5" data-parent="#accordion">
                <div class="row">
                 
                </div>
            </div>
        </div>
        
        
    </div>
</div>

