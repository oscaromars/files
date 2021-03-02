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
?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-comments"></i></a></li>
        <li><a href="#control-sidebar-theme-demo-options-tab" data-toggle="tab"><i class="fa fa-wrench"></i></a></li>
        <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane active chat-bar" id="control-sidebar-home-tab">
            <h3 class="control-sidebar-heading">Test</h3>
            <ul class="control-sidebar-menu">
            </ul><!-- /.control-sidebar-menu -->
        </div><!-- /.tab-pane -->
        <!-- Setting2 tab content -->
        <div id="control-sidebar-theme-demo-options-tab" class="tab-pane">
            <h3 class="control-sidebar-heading">Skins</h3>
            <ul class="list-unstyled clearfix">
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-blue" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9;"></span>
                            <span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin">Blue</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-black" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix">
                            <span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe;"></span>
                            <span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #222;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin">Black</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-purple" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class=" skinSet clearfix full-opacity-hover">
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span>
                            <span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin">Purple</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-green" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span>
                            <span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin">Green</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-red" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span>
                            <span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin">Red</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-yellow" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span>
                            <span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin">Yellow</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-blue-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9;"></span>
                            <span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin" style="font-size: 12px">Blue Light</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-black-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix">
                            <span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe;"></span>
                            <span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin" style="font-size: 12px">Black Light</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-purple-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span>
                            <span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin" style="font-size: 12px">Purple Light</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-green-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span>
                            <span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin" style="font-size: 12px">Green Light</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-red-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span>
                            <span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin" style="font-size: 12px">Red Light</p>
                </li>
                <li style="float:left; width: 33.33333%; padding: 5px;">
                    <a href="javascript:;" data-skin="skin-yellow-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="skinSet clearfix full-opacity-hover">
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span>
                            <span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span>
                        </div>
                        <div>
                            <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span>
                            <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
                        </div>
                    </a>
                    <p class="text-center no-margin" style="font-size: 12px;">Yellow Light</p>
                </li>
            </ul>
        </div>
        <!-- Stats tab content -->
        <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->
        <!-- Settings tab content -->
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <form method="post">
                <h3 class="control-sidebar-heading">General Settings</h3>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Report panel usage
                        <input type="checkbox" class="pull-right" checked>
                    </label>
                    <p>
                        Some information about this general settings option
                    </p>
                </div><!-- /.form-group -->

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Allow mail redirect
                        <input type="checkbox" class="pull-right" checked>
                    </label>
                    <p>
                        Other sets of options are available
                    </p>
                </div><!-- /.form-group -->

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Expose author name in posts
                        <input type="checkbox" class="pull-right" checked>
                    </label>
                    <p>
                        Allow the user to show his name in blog posts
                    </p>
                </div><!-- /.form-group -->

                <h3 class="control-sidebar-heading">Chat Settings</h3>

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Show me as online
                        <input type="checkbox" class="pull-right" checked>
                    </label>
                </div><!-- /.form-group -->

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Turn off notifications
                        <input type="checkbox" class="pull-right">
                    </label>
                </div><!-- /.form-group -->

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Delete chat history
                        <a href="javascript::;" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                    </label>
                </div><!-- /.form-group -->
            </form>
        </div><!-- /.tab-pane -->
    </div>
</aside><!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>