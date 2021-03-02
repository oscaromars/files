<?php 

$mensaje='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title></title>
        <style>
            .titleLabel{
                /*font-size:7pt;*/
                color:black;
                font-weight: bold ;
            }
            .titleName{
                font-size:12pt;
                color:black;
                font-weight: bold ;
            }
        </style>
    </head>
    <body>
        <div style="width: 600px; margin-left: auto; margin-right: auto;">
            <div style="background-color: #222d32;">
                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                    <tr>
                        <td valign="top">
                            <div>
                                <strong>
                                    <span style="color: #ffffff; font-family: Helvetica,sans-serif; font-size: 11px; line-height: 13px;">&nbsp;</span>
                                </strong>
                            </div>
                        </td>
                        <td valign="top" width="180">
                            <div style="text-align: right;">
                                <strong>
                                    <a href="#" style="color: #ffffff; text-align: right; font-family: Helvetica,sans-serif; font-size: 11px; line-height: 13px; text-decoration: none;" target="_blank">&nbsp;</a>
                                </strong>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="text-align: center;"><img alt="banner" src="'. 
                'data:image/' . pathinfo(__DIR__ . '/layouts/images/banner_'.$objEmp["Ruc"].'.jpg', PATHINFO_EXTENSION) . ';base64,' . 
                base64_encode(file_get_contents(__DIR__ . '/layouts/images/banner_'.$objEmp["Ruc"].'.jpg')) .'"></div>
            <br /><br />
            <div style="text-align: center; font-family: Arial; padding: 10px 50px 0px;">
                <span style="color:#9a4d9d; font-size:35px;">
                    Generación de documento electrónico
                </span>
            </div>
            <!-- body message begin -->
            <div style="margin: 30px;">
                <div style="font-family: helvetica, sans-serif; color: #282828; font-size: 12px; text-align: justify;">
                    <div id="div-table">
                        <div class="trow">
                            <p>
                                <label class="titleLabel">Estimado(a):</label><br><span class="titleName">'.utf8_encode($cabDoc[$i]["RazonSoc"]).'</span><br> 
                                Ha recibido un documendo electronico de <label class="titleLabel">'.strtoupper($objEmp["RazonSocial"]) .'</label>
                            </p>
                        </div>
                        <div class="trow">
                            <div class="tcol-td form-group">
                                <label class="titleLabel">Documento Nº :</label>
                                <span>'.$cabDoc[$i]["NumDocumento"].'</span>
                            </div>
                        </div>
                        <div class="trow">
                            <div class="tcol-td form-group">
                                <label class="titleLabel">Autorizaci&oacute;n Nº :</label>
                                <span>'.$cabDoc[$i]["AutorizacionSRI"].'</span>
                            </div>
                        </div>
                        <div class="trow">
                            <div class="tcol-td form-group">
                                <label class="titleLabel">Fecha Emisi&oacute;n :</label>
                                <span>'.$cabDoc[$i]["FechaAutorizacion"].'</span>
                            </div>
                        </div>';
                        if($cabDoc[$i]["Clave"]<>''){//Adjunta Clave en Caso de Ser un Usuario Nuevo
                            $mensaje.='<div class="trow">
                                            <div class="tcol-td form-group">
                                                <label class="titleLabel">Usuario :</label>
                                                <span>'.((isset($cabDoc[$i]["CorreoPer"]) && $cabDoc[$i]["CorreoPer"] != "")?$cabDoc[$i]["CorreoPer"]:$cabDoc[$i]["CedRuc"]).'</span><br> 
                                                <label class="titleLabel">Clave :</label>
                                                <span>'.$cabDoc[$i]["Clave"].'</span>
                                            </div>
                                        </div>';
                        }
                        //Datos de prueba
                        /*$DataCorreos = explode(";",$cabDoc[$i]["CorreoPer"]);
                        for ($icor = 0; $icor < count($DataCorreos); $icor++) {
                            $mensaje.='<div class="trow">
                                            <div class="tcol-td form-group">
                                                <span> Correo :'.trim($DataCorreos[$icor]).'- Para :'.trim($cabDoc[$i]["RazonSoc"]).'</span><br>
                                            </div>
                                        </div>';
                        }*/
                        //Adem&aacute;s puede realizar la impresi&oacute;n su documento accediendo a nuestro portal <a target="_blank" href="'.$obj_var->rutaLink.'">aqui</a>.<br>
                        $mensaje.='<div class="trow">
                            <div class="tcol-td form-group">
                                <p>
                                    Adem&aacute;s puede realizar la impresi&oacute;n su documento accediendo a nuestro portal <a target="_blank" href="'.$obj_var->rutaLink.'">aqui</a>.<br><br>
                                    <label class="titleLabel">Atentamente</label>,<br>
                                    <label class="titleLabel">'.strtoupper($objEmp["RazonSocial"]) .'</label>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- body message end -->
            <div style="line-height: 23px; text-align: center; height: 10px; border-bottom: 2px solid #D3D3D3; margin: 15px 30px 30px;">&nbsp;</div>
            <br />
            <div style="background-color: #222d32;">
                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                    <tr>
                        <td valign="middle" width="370">
                            <div>
                                <span style="color: #ffffff; font-family: Helvetica; font-size: 11px; line-height: 16px;">Todos los derechos reservados '.$objEmp["RazonSocial"].' </span>
                            </div>
                        </td>
                        <td valign="middle" width="170">
                            <div>
                                <div style="text-align: right;">';
                                    $mensaje.='
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>';
