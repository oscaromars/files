<style>
    .modCab{
        color: #000000;        
        line-height: 16px;
    }
    .panelUserInfo{
        /*margin: 10px 0px 18px;*/
        margin: 5px 0px 0px;
    }    
    .tcoll_cen {
        widtd: 50%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .tcolr_cen {
        widtd: 50%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .tcoll_cen2 {
        widtd: 40%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .tcolr_cen2 {
        widtd: 60%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .tcoll_ad {
        widtd: 30%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .tcolr_ad {
        widtd: 70%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .divDetalles{
        float: left;
        widtd: 100%;
        position: absolute;      
        left: 0;
        margin-top: 10px;
    }
    .divDetalleAd{
        float: left;
        widtd: 65%;
        position: absolute;      
        left: 0;
    }
    .divDetalleTot{  
        widtd: 35%;
        position: absolute;      
        right: 0;
    }
    .div_modInfoAd{
        float: left;
        widtd: 70%;
    }
    .div_modInfoVal{
        float: left;
        widtd: 100%;       
    }
    .div_modInfoDet{
        float: left;
        widtd: 60%;
    }
    .div_modInfoDet2{
        float: left;
        widtd: 75%;
    }
    .div_modInfoDet1{
        float: left;
        widtd: 40%;
    }    
    .bordeDivDet{ 
        border: 1px solid #000000;       
        -moz-border-radius: 7px;
        -webkit-border-radius: 7px;
        padding: 10px;
    }    
    .valorAlign{ 
        text-align: right !important;
    }

    .bold{
        font-weight: bold;
    }
    .blue{
        color:#002060 !important;
    }
    .tabla {       
        color:#002060 !important;
        width: 100%;
        text-align: center;
        vertical-align: top;
        border: 1px solid #002060;
        border-collapse: collapse;
        padding: 0.3em;
        caption-side: bottom;
    }  

</style>
<div>

    <div class="blue" style="text-align: center">
        <br><br>
        <p><strong><?php echo "HOJA DE VIDA"; ?></strong></p><br><br><br><br>
    </div>
    <div style="text-align: center">
        <div>                            
            <div> 
                <?php
                if (!empty($persona_model['per_foto'])) {
                    echo yii\helpers\Html::img(
                            Yii::$app->basePath . "/uploads/" . $persona_model['per_foto'], array('style' => ['width' => '150px', 'height' => '150px'], "alt" => Yii::$app->params["copyright"],));
                }
                ?>
            </div>
        </div><br><br>
    </div>

    <!--<div style="text-align: right">
        <br>
        <p></p><br><br>
    </div>-->

    <div class="blue">
        <br><br>
        <p><u><b><?php echo "1.- DATOS PERSONALES" ?></b><br></u></p><br>
        <p><?php echo "APELLIDOS Y NOMBRES:" . "      " . $persona_model['per_pri_apellido'] . ' ' . $persona_model['per_seg_apellido'] . ' ' . $persona_model['per_pri_nombre'] . ' ' . $persona_model['per_seg_apellido']; ?><br></p><br>
        <p><?php echo "CÉDULA / PASAPORTE:" . "      " . $persona_model['per_cedula'] ?><br></p><br>
        <p><?php echo "NACIONALIDAD:" . "      " . $persona_model['per_nacionalidad'] ?><br></p><br>
        <p><?php echo "FECHA DE NACIMIENTO:" . "      " . $persona_model['per_fecha_nacimiento'] ?><br></p><br>
        <p><?php echo "CIUDAD:" . "      " . $canton['ciudad'] ?><br></p><br>
        <p><?php echo "DIRECCIÓN:" . "      " . $persona_model['per_domicilio_cpri'] . ' ' . $persona_model['per_domicilio_csec'] . ' ' . $persona_model['per_domicilio_num']; ?><br></p><br>
        <p><?php echo "TELÉFONO FIJO:" . "      " . $persona_model['per_domicilio_telefono'] ?><br></p><br>
        <p><?php echo "CELULAR:" . "      " . $persona_model['per_celular'] ?><br></p><br>
        <p><?php echo "CORREO ELECTRÓNICO:" . "      " . $persona_model['per_correo'] ?><br></p><br>
        <br><br>

    </div>
    <div class="blue">
        <p><u><b><?php echo "2.- INSTRUCCIÓN" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;border: 1px solid #002060;"><th>NIVEL DE INSTRUCCIÓN</th><th>NOMBRE DE LA INSTITUCIÓN</th><th>ESPECIALIAZACIÓN</th><th>TÍTULO</th><th>REGISTRO SENESCYT</th></tr>';
        for ($i = 0; $i < count($instruccion); $i++) {
            echo '<tr style="border: 1px solid #002060;"><td>' . $instruccion[$i]['Instruccion'] . '</td><td>' . $instruccion[$i]['NombreInstitucion'] . '</td><td>' . $instruccion[$i]['Especializacion'] . '</td><td>' . $instruccion[$i]['Titulo'] . '</td><td> ' . $instruccion[$i]['Registro'] . ' </td></tr>';
        }
        if (empty(count($instruccion))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>
    <div class="blue">
        <p><u><b><?php echo "3.- EXPERIENCIA DOCENTE" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;"><th>TIEMPO DE LABOR DESDE</th><th>HASTA</th><th>UNIVERSIDAD</th><th>ASIGNATURAS</th></tr>';
        for ($i = 0; $i < count($experienciadoc); $i++) {
            echo '<tr style="border: 1px solid #002060;"><td>' . substr($experienciadoc[$i]['Desde'], 0, -9) . '</td><td>' . substr($experienciadoc[$i]['Hasta'], 0, -9) . '</td><td>' . $experienciadoc[$i]['Institucion'] . '</td><td>' . $experienciadoc[$i]['Materias'] . '</td></tr>';
        }
        if (empty(count($experienciadoc))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>
    <div class="blue">
        <p><u><b><?php echo "4.- EXPERIENCIA PROFESIONAL" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;"><th>TIEMPO DE LABOR DESDE</th><th>HASTA</th><th>EMPRESA</th><th>DENOMINACIÓN DEL PUESTO</th></tr>';
        for ($i = 0; $i < count($experienciapro); $i++) {
            echo '<tr style="border: 1px solid #002060;"><td>' . substr($experienciapro[$i]['Desde'], 0, -9) . '</td><td>' . substr($experienciapro[$i]['Hasta'], 0, -9) . '</td><td>' . $experienciapro[$i]['Institucion'] . '</td><td>' . $experienciapro[$i]['Denominacion'] . '</td></tr>';
        }
        if (empty(count($experienciapro))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>
    <div class="blue">
        <p><u><b><?php echo "5.- SUFICIENCIA DE IDIOMA" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;"><th>IDIOMA</th><th>NIVEL</th><th>INSTITUCIÓN</th></tr>';
        for ($i = 0; $i < count($idioma); $i++) {
            echo '<tr style="border: 1px solid #002060;"><td>' . $idioma[$i]['Languages'] . '</td><td>' . $idioma[$i]['NivelOral'] . '</td><td> ' . $idioma[$i]['Institucion'] . ' </td></tr>';
        }
        if (empty(count($idioma))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>
    <div class="blue">
        <p><u><b><?php echo "6.- PARTICIPACIÓN EN PROYECTOS DE INVESTIGACIÓN" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;"><th>DENOMINACIÓN DEL PROYECTO</th><th>AÑO</th></tr>';
        for ($i = 0; $i < count($investigacion); $i++) {
            echo '<tr style="border: 1px solid #002060;"><td>' . $investigacion[$i]['Denominancion'] . '</td><td> ' . $investigacion[$i]['Anio'] . ' </td></tr>';
        }
        if (empty(count($investigacion))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><</tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>
    <div class="blue">
        <p><u><b><?php echo "7.- CAPACITACIÓN ESPECÍFICA" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;"><th>NOMBRE DEL EVENTO</th><th>INSTITUCIÓN</th><th>AÑO</th><th>TIPO</th><th>DURACIÓN EN HORAS</th></tr>';
        for ($i = 0; $i < count($capacitacion); $i++) {
            echo '<tr style="border: 1px solid #002060;"><td>' . $capacitacion[$i]['Evento'] . '</td><td>' . $capacitacion[$i]['Institucion'] . '</td><td>' . $capacitacion[$i]['Anio'] . '</td><td>' . $capacitacion[$i]['Tipo'] . '</td><td> ' . $capacitacion[$i]['Duracion'] . ' </td></tr>';
        }
        if (empty(count($capacitacion))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>
    <div class="blue">
        <p><u><b><?php echo "8.- CONFERENCIAS, PONENCIAS Y EXPOSITOR" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;"><th>NOMBRE DEL EVENTO</th><th>INSTITUCIÓN</th><th>AÑO</th><th>PONENCIA</th></tr>';
        for ($i = 0; $i < count($conferencia); $i++) {
            echo '<tr style="border: 1px solid #002060;"><td>' . $conferencia[$i]['Evento'] . '</td><td>' . $conferencia[$i]['Institucion'] . '</td><td>' . $conferencia[$i]['Anio'] . '</td><td> ' . $conferencia[$i]['Ponencia'] . ' </td></tr>';
        }
        if (empty(count($conferencia))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>
    <div class="blue">
        <p><u><b><?php echo "9.- PUBLICACIONES" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;"><th>TIPO DE PUBLICACIÓN</th><th>TÍTULO</th><th>EDITORIAL</th><th>ISBN/ISSN</th><th>AUDITORIA</th></tr>';
        for ($i = 0; $i < count($publicacion); $i++) {
            echo '<tr style="border: 1px solid #002060;"><td>' . $publicacion[$i]['Instruccion'] . '</td><td>' . $publicacion[$i]['NombreInstitucion'] . '</td><td>' . $publicacion[$i]['Especializacion'] . '</td><td>' . $publicacion[$i]['Titulo'] . '</td><td> ' . $publicacion[$i]['Registro'] . ' </td></tr>';
        }
        if (empty(count($publicacion))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>
    <div class="blue">
        <p><u><b><?php echo "10.- DIRECCIÓN O CODIRECCIÓN DE TESIS DE MAESTRÍA Y PREGRADO" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;"><th>AUTOR</th><th>TEMA DE TESIS</th><th>AÑO</th><th>CARRERA / PROGRAMA</th></tr>';
        for ($i = 0; $i < count($coodirecion); $i++) {
            echo '<tr style="border: 1px solid #002060;"><td>' . $coodirecion[$i]['Estudiante'] . '</td><td>' . $coodirecion[$i]['Academico'] . '</td><td>' . $coodirecion[$i]['Anio'] . '</td><td> ' . $coodirecion[$i]['Programa'] . ' </td></tr>';
        }
        if (empty(count($coodirecion))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>
    <div class="blue">
        <p><u><b><?php echo "11.- REFERENCIAS LABORALES" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;"><th>PERSONA DE CONTACTO</th><th>TIPO DE RELACIÓN / CARGO</th><th>ORGANIZACIÓN / EMPRESA </th><th>NÚMERO DE CONTACTO</th></tr>';
        for ($i = 0; $i < count($referencia); $i++) {
            echo '<tr style="border: 1px solid #002060;"><td>' . $referencia[$i]['Nombre'] . '</td><td>' . $referencia[$i]['Cargo'] . '</td><td>' . $referencia[$i]['Organizacion'] . '</td><td> ' . $referencia[$i]['Numero'] . ' </td></tr>';
        }
        if (empty(count($referencia))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td><td>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>
    <!--<div class="blue">
        <p><u><b><?php //echo "12.- OTROS"    ?></b><br></u></p><br><br>
    <?php
    /* echo '<table class="tabla"><tbody>';
      echo '<tr style="background: #AED6F1;"><th>Nivel de Instrucción</th><th>Nombre de la Institución</th><th>Especialización</th><th>Titulo</th><th>Registro Senescyt</th></tr>';
      for ($i = 0; $i < count($instruccion); $i++) {
      echo '<tr><td>' . $instruccion[$i]['Instruccion'] . '</td><td>' . $instruccion[$i]['NombreInstitucion'] . '</td><td>' . $instruccion[$i]['Especializacion'] . '</td><td>' . $instruccion[$i]['Titulo'] . '</td><td> ' . $instruccion[$i]['Registro'] . ' </td></tr>';
      }
      echo '</tbody> </table>'; */
    ?><br><br>
    </div> -->
</div>
