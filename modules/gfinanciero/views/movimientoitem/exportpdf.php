
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$cols = count($arr_head);
$j = 0;
?>
<style>
    html, body, div, span, applet, object, iframe,
    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
    a, abbr, acronym, address, big, cite, code,
    del, dfn, em, img, ins, kbd, q, s, samp,
    small, strike, strong, sub, sup, tt, var,
    b, u, i, center,
    dl, dt, dd, ol, ul, li,
    fieldset, form, label, legend,
    table, caption, tbody, tfoot, thead, tr, th, td,
    article, aside, canvas, details, embed, 
    figure, figcaption, footer, header, hgroup, 
    menu, nav, output, ruby, section, summary,
    time, mark, audio, video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
    }
    body{
        font-family: Arial;
    }
    h2, .h2{
        font-size: 20px;
    }
    .th2{
        font-size: 18px;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
    }
    /* Css para las tablas */
    table{
        border-collapse: collapse;
        width: 100%;/*732px;*/
        height: auto;
        margin: 0px;
        padding: 0px;
    }
    table thead tr, .thcol{
        background-color: #9b9b9c;
        height: 20px;
    }
    table thead tr th, table tfoot tr th {
        text-transform: uppercase;
        font-weight: bold;
        color: #ffffff;
        text-align: left;
        font-size: 12px;
        font-family: Arial;
        padding: 7px;
    }
    /*table tbody tr:nth-child(odd){ background-color:#dcdce1; }*/
    /*table tbody tr:nth-child(even){ background-color:#ffffff; }*/
    table tbody tr td, table tfoot tr td {
        vertical-align: middle;
        text-align: left;
        padding: 7px;
        font-size: 12px;
        font-family: Arial;
        /*font-weight:normal;*/
        /*color:#000000;*/
    }
    table tbody tr:last-child td{
        border-width: 0px 1px 0px 0px;
    }
    table tbody tr td:last-child{
        border-width: 0px 0px 1px 0px;
    }
    table tbody tr:last-child td:last-child{
        border-width: 0px 0px 0px 0px;
    }
    table tbody tr:first-child td:first-child{
        border-width:0px 0px 1px 0px;
    }
    table tbody tr:first-child td:last-child{
        border-width:0px 0px 1px 1px;
    }
    table tfoot tr td.outPad {
        padding-top: 0px;
        padding-bottom: 0px;
    }
    .tboderTop{
        border-top: 1px solid #9b9b9c ;
    }
    .tboderbottom{
        border-bottom: 1px solid #9b9b9c;
    }
    .tborder{
        border: 1px solid #9b9b9c;
    }
    .talign-left{
        text-align: left;
    }
    .talign-right{
        text-align: right;
    }
    .talign-center{
        text-align: center;
    }
    .tvertical-top{
        vertical-align: top;
    }
    .tleft{
        float: left;
    }
    .tright{
        float: right;
    }
    .fsets{
        border-radius: 10px;
        border: 1px solid #9b9b9c;
        padding: 7px;
        font-size: 12px;
        font-family: Arial;
        margin: 2px;
    }
    .fsets legend span{
        padding: 10px;
    }
    .tclear{
        clear: both;
    }
    .tbold{
        font-weight: bold !important;
    }
    .tcbackdanger {
        background-color: #dd4b39;
    }
    .tcbacksuccess {
        background-color: #00a65a;
    }
    .tcolorW{
        color: #FFFFFF;
    }
    .tbagde{
        padding: 5px;
        border-radius: 5px;
        margin: 2px;
    }
    .tbagde.tcbackdanger {
        border: 1px solid #dd4b39;
    }
    .tbagde.tcbacksuccess {
        border: 1px solid #00a65a;
    }
</style>
<div>
    <div class="tleft" style="width: 50%;">
        <fieldset class="fsets tborder">
            <legend class="tbold th2"><span><?= $arr_head['lbl_titulo'] ?></span></legend>
            <span class="tbold"><?= $arr_head['lbl_bodega']?>: </span><span><?= $arr_head['txt_bodega'] ?></span><br/>
            <span class="tbold"><?= $arr_head['lbl_articulo']?>: </span><span><?= $arr_head['txt_articulo'] ?></span><br/>
            <span class="tbold"><?= $arr_head['lbl_articulo']?>: </span><span><?= $arr_head['txt_articulo'] ?></span><br/>
            <span class="tbold"><?= $arr_head['lbl_fecdes']?>: </span><span><?= $arr_head['txt_fecdes'] ?></span><br/>
            <span class="tbold"><?= $arr_head['lbl_fechas']?>: </span><span><?= $arr_head['txt_fechas'] ?></span><br/>
            <br/>

        </fieldset>
    </div>
    <div class="tleft" style="width: 50%">
        
    </div>
</div>
<div class="tclear"></div>
<br />
 
<table>
    <thead>
        <tr>
        <?php
            foreach($arr_cols as $key1 => $value1){
                echo "<th>" . strtoupper($value1) . "</th>";
            }
        ?>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($arr_body as $key2 => $value2){
                echo "<tr>";
                foreach($value2 as $key3 => $value3){
                    if($j < $cols) echo "<td>$value3</td>";
                    $j++;
                }
                $j=0;
                echo "</tr>";
            }
        ?>
    </tbody>
    <!--<tfoot>
        <tr><td></td></tr>
    </tfoot>-->
</table>