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
 */
$(document).ready(function () {
    $("#frm_mod_image").keyup(function () {
        if ($(this).val() != "")
            $("#iconMod").attr("class", $(this).val());
        else {
            $("#iconMod").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanModStatus").click(function(){
        if($("#frm_mod_status").val() == "1"){
            $("#iconModStatus").attr("class","glyphicon glyphicon-unchecked");
            $("#frm_mod_status").val("0");
        }else{
            $("#iconModStatus").attr("class","glyphicon glyphicon-check");
            $("#frm_mod_status").val("1");
        }
    });
});
function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit(){
    var link = $('#txth_base').val() + "/modulos/edit" + "?id=" + $("#frm_mod_id").val();
    window.location = link;
}

function update(){
    var link = $('#txth_base').val() + "/modulos/update";
    var arrParams = new Object();
    arrParams.id          = $("#frm_mod_id").val();
    arrParams.nombre      = $('#frm_modulo').val();
    arrParams.apl_id      = $('#cmb_aplicacion').val();
    arrParams.tipo        = $('#frm_mod_type').val();
    arrParams.icon        = $('#frm_mod_image').val();
    arrParams.url         = $('#frm_mod_url').val();
    arrParams.orden       = $('#frm_mod_orden').val();
    arrParams.lang        = $('#frm_mod_lang').val();
    arrParams.estado      = $('#frm_mod_status').val();
    if(!validateForm()){
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save(){
    var link = $('#txth_base').val() + "/modulos/save";
    var arrParams = new Object();
    arrParams.nombre      = $('#frm_modulo').val();
    arrParams.apl_id      = $('#cmb_aplicacion').val();
    arrParams.tipo        = $('#frm_mod_type').val();
    arrParams.icon        = $('#frm_mod_image').val();
    arrParams.url         = $('#frm_mod_url').val();
    arrParams.orden       = $('#frm_mod_orden').val();
    arrParams.lang        = $('#frm_mod_lang').val();
    arrParams.estado      = $('#frm_mod_status').val();
    if(!validateForm()){
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id){
    var link = $('#txth_base').val() + "/modulos/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        if(response.status == "OK"){
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_module_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function(){ 
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}