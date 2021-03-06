<style>
    body {
        width: 100%;
        font-family: Arial;
        font-size: 7pt;
        margin: 0;
        padding: 0;
    }

    .marcoDiv {
        border: 1px solid #165480;
        padding: 2mm;
    }

    .marcoCel {
        border: 1px solid #0000;
        padding: 1mm;
    }

    .bold {
        font-weight: bold;
    }

    .normal {
        border: 1px solid #000;
        border-collapse: collapse;
    }

    .normal th,
    .normal td {
        border: 1px solid #000;
    }


    .divTabla {
        display: table;
        width: 100%;
        /*background-color:#eee;
        border:1px solid  #666666;*/
        border: 1px solid #0000;
        padding: 2mm;
        border-spacing: 5px;
        /*cellspacing:poor IE support for  this*/
        /* border-collapse:separate;*/
    }

    .divTable {
        display: table;
        width: 100%;
        /*background-color:#eee;
        border:1px solid  #666666;*/
        /*border:1px solid  #0000;
        padding: 2mm;*/
        border-spacing: 5px;
        /*cellspacing:poor IE support for  this*/
        /* border-collapse:separate;*/
    }

    .fila {
        border-bottom: 1px;
        border-width: 1px;
        border-bottom-style: solid;

    }

    .divRow {
        display: table-row;
        width: auto;
    }

    .divCell {
        float: left;
        /*fix for  buggy browsers*/
        display: table-column;
        /*width:200px;*/
        border: 1px solid #0000;
        /*background-color:#ccc;*/
        padding: 2mm;
    }

    .divCelda {
        float: left;
        /*fix for  buggy browsers*/
        display: table-column;
        border: 1px solid #0000;
        background-color: #ccc;
        padding: 2mm;
    }

    .tabDetalle {
        border-spacing: 1;
        border-collapse: collapse;
    }

    .titleDetalle {
        text-align: center;
    }
</style>
<div>

    <div class="bold" style="text-align: center">
        ASIGNACION DE MATERIAS CORRESPONDIENTES AL BLOQUE <?php echo $cabDist[0]['baca_descripcion'] ?> <?php echo $cabDist[0]['baca_anio'] ?>.
    </div>
    <br><br>
    <div class="divTable">
        <div class="divRow">
            <div class="divCell bold" style="width:10%;"><?php echo app\modules\fe_edoc\Module::t("fe", "DOCENTE") ?>:</div>
            <div class="divCell" style="width:35%;"><?php echo $cabDist[0]['Nombres'] ?></div>
            <div class="divCell bold" style="width:9%;"><?php echo app\modules\fe_edoc\Module::t("fe", "FECHA") ?>:</div>
            <div class="divCell" style="width:35%;"><?php echo $FechaDia ?></div>
        </div>
    </div>
    <br><br>


    <table style="width:100%" class="divTabla">
        <tbody>
            <tr class="divRow">
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "MATERIAS ASIGNADAS") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "DIAS - HORAS") ?></span>
                </td>

                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "UNIDAD ACADEMICA") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "MODALIDAD") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "FECHA DE INICIO") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "FECHA DE FIN") ?></span>
                </td>
            </tr>

            <?php
            for ($fil = 0; $fil < sizeof($detDist); $fil++) {
                     ?>
                    <tr class="fila">
                        <td class="marcoCel normal"><?php echo $detDist[$fil]['asi_nombre'] ?></td>
                        <td class="marcoCel normal"><?php echo $detDist[$fil]['HORAS'] ?></td>

                        <td class="marcoCel normal"><?php echo $detDist[$fil]['uaca_nombre'] ?></td>
                        <td class="marcoCel normal"><?php echo $detDist[$fil]['mod_nombre'] ?></td>
                        <td class="marcoCel normal"><?php echo $detDist[$fil]['paca_fecha_inicio'] ?></td>
                        <td class="marcoCel normal"><?php echo $detDist[$fil]['paca_fecha_fin'] ?></td>
                    </tr>
            <?php

            }

            ?>


        </tbody>
    </table>
    <br><br>
    <div class="divCelda bold titleDetalle " style="text-align: center">
        Carga Horario Bloque <?php echo $cabDist[0]['baca_descripcion'] ?> <?php echo $cabDist[0]['baca_anio'] ?>.
    </div>

    <table style="width:100%" class="divTabla">
        <tbody>

            <tr>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "DETALLE") ?></span>
                </td>
                <td class="divCelda bold titleDetalle">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "TOTAL HORAS") ?></span>
                </td>
                <td class="divCelda bold titleDetalle">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "DETALLE") ?></span>
                </td>
                <td class="divCelda bold titleDetalle">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "TOTAL HORAS") ?></span>
                </td>
            </tr>



                <tr class="fila">
                    <td class="marcoCel"><?php echo 'HORAS CARGA DOCENTE BLOQUE' ?></td>
                    <td class="marcoCel"><?php if ((/*$sumaHoras[0]['total_docente']*/ $horas_carga_docente_bloque == null)) {
                                                echo 'N/A';
                                            } else {
                                                //echo $sumaHoras[0]['total_docente']+ $sumaHoras[0]['total_docente_author'];
                                                echo $horas_carga_docente_bloque;
                                            } ?></td>
                    <td class="marcoCel"><?php echo 'HORAS DE PREPARACION DOCENTE' ?></td>
                    <td class="marcoCel"><?php if (($sumaHoras[0]['total_docente'] == null) ) {
                                                echo 'N/A';
                                            } else {
                                                echo /*$sumaHoras[0]['total_docente']*/round($horas_carga_docente_bloque * 0.30);
                                            } ?></td>
                </tr>
                <tr class="fila">
                    <td class="marcoCel"><?php echo 'HORAS INVESTIGACION Y VINCULACION ' ?></td>
                    <td class="marcoCel"><?php if ($sumaHoras[0]['total_inve_vincu'] == null)  {
                                                echo 'N/A';
                                            } else {
                                                echo $sumaHoras[0]['total_inve_vincu'];
                                            } ?></td>
                    <td class="marcoCel"><?php echo 'HORAS DE TUTORIAS' ?></td>
                    <td class="marcoCel"><?php if ($sumaHoras[0]['total_tutorias'] == null) {
                                                echo 'N/A';
                                            } else {
                                                echo $sumaHoras[0]['total_tutorias'];
                                            }  ?></td>
                </tr>

        </tbody>
    </table>
    <br><br>
    <div class="divCelda bold titleDetalle " style="text-align: center">

        TOTAL, PROMEDIO HORAS SEMANALES: <h1><?php echo $promedio ?></h1>
    </div>
    <br><br>
    <br><br>

    <div style="text-justify: auto">
        <p>
            Le recordamos que cualquier duda adicional con respecto al material did??ctico como el syllabus, EVU o plataforma de campus virtual, o cualquier otra consulta, por favor comunicar coordinador de carrera o decano correspondiente a la modalidad asignada.
        </p>
        <br>
        <p>
            <b>FUNCIONES</b> de la docencia en las universidades de acuerdo al reglamento de escalaf??n de docentes. Art 7 las actividades de los docentes. <b>Art??culo 7</b>.- Actividades de docencia. - La docencia en las universidades y escuelas polit??cnicas p??blicas y particulares comprende, entre otras, las siguientes actividades:
        </p>
        <br><br>
    </div>

    <table class="normal">
        <tbody>
            <tr>
                <td class="marcoCel">1. Impartici??n de clases presenciales, virtuales o en l??nea, de car??cter te??rico o pr??ctico, en la instituci??n o fuera de ella, bajo responsabilidad y direcci??n de la misma</td>
                <td class="marcoCel">8. Direcci??n y tutor??a de trabajos para la obtenci??n del t??tulo, con excepci??n de tesis doctorales o de maestr??as de investigaci??n</td>
            </tr>
            <tr>
                <td class="marcoCel">2. Preparaci??n y actualizaci??n de clases, seminarios, talleres, entre otros</td>
                <td class="marcoCel">9. Direcci??n y participaci??n de proyectos de experimentaci??n e innovaci??n docente</td>
            </tr>
            <tr>
                <td class="marcoCel">3. Dise??o y elaboraci??n de libros, material did??ctico, gu??as docentes o syllabus</td>
                <td class="marcoCel">10. Dise??o e impartici??n de cursos de educaci??n continua o de capacitaci??n y actualizaci??n</td>
            </tr>
            <tr>
                <td class="marcoCel">4. Orientaci??n y acompa??amiento a trav??s de tutor??as presenciales o virtuales, individuales o grupales; </td>
                <td class="marcoCel">11. Participaci??n en actividades de proyectos sociales, art??sticos, productivos y empresariales de vinculaci??n con la sociedad articulados a la docencia e innovaci??n educativa</td>
            </tr>
            <tr>
                <td class="marcoCel">5. Visitas de campo, tutor??as, docencia en servicio y formaci??n dual, en ??reas como salud (formaci??n en hospitales), derecho (litigaci??n guiada), ciencias agropecuarias (formaci??n en el escenario de aprendizaje), entre otras</td>
                <td class="marcoCel">12. Participaci??n y organizaci??n de colectivos acad??micos de debate, capacitaci??n o intercambio de metodolog??as y experiencias de ense??anza</td>
            </tr>
            <tr>
                <td class="marcoCel">6. Direcci??n, tutor??as, seguimiento y evaluaci??n de pr??cticas o pasant??as pre profesionales</td>
                <td class="marcoCel">13. Uso pedag??gico de la investigaci??n y la sistematizaci??n como soporte o parte de la ense??anza</td>
            </tr>
            <tr>
                <td class="marcoCel">7. Preparaci??n, elaboraci??n, aplicaci??n y calificaci??n de ex??menes, trabajos y pr??cticas;</td>
                <td class="marcoCel">14. Participaci??n como profesores que impartir??n los cursos de nivelaci??n del Sistema Nacional de Nivelaci??n y Admisi??n (SNNA); y, </td>
            </tr>
            <tr>
                <td class="marcoCel"></td>
                <td class="marcoCel">15. Orientaci??n, capacitaci??n y acompa??amiento al personal acad??mico del SNNA</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <div style="text-justify: auto">
        <p>
            En caso de incumplimiento con los horarios, asignaciones y entregas de material se sancionar?? conforme lo establece el reglamento interno de la Universidad.
        </p>
    </div>
    <br><br>
    <div style="text-align: center">
        Msc. Karina Mu??oz<br>
        Coordinadora de Talento Humano<br>
        Universidad Tecnol??gica Empresarial de Guayaquil

    </div>

</div>