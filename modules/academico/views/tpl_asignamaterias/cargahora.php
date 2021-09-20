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
            Le recordamos que cualquier duda adicional con respecto al material didáctico como el syllabus, EVU o plataforma de campus virtual, o cualquier otra consulta, por favor comunicar coordinador de carrera o decano correspondiente a la modalidad asignada.
        </p>
        <br>
        <p>
            <b>FUNCIONES</b> de la docencia en las universidades de acuerdo al reglamento de escalafón de docentes. Art 7 las actividades de los docentes. <b>Artículo 7</b>.- Actividades de docencia. - La docencia en las universidades y escuelas politécnicas públicas y particulares comprende, entre otras, las siguientes actividades:
        </p>
        <br><br>
    </div>

    <table class="normal">
        <tbody>
            <tr>
                <td class="marcoCel">1. Impartición de clases presenciales, virtuales o en línea, de carácter teórico o práctico, en la institución o fuera de ella, bajo responsabilidad y dirección de la misma</td>
                <td class="marcoCel">8. Dirección y tutoría de trabajos para la obtención del título, con excepción de tesis doctorales o de maestrías de investigación</td>
            </tr>
            <tr>
                <td class="marcoCel">2. Preparación y actualización de clases, seminarios, talleres, entre otros</td>
                <td class="marcoCel">9. Dirección y participación de proyectos de experimentación e innovación docente</td>
            </tr>
            <tr>
                <td class="marcoCel">3. Diseño y elaboración de libros, material didáctico, guías docentes o syllabus</td>
                <td class="marcoCel">10. Diseño e impartición de cursos de educación continua o de capacitación y actualización</td>
            </tr>
            <tr>
                <td class="marcoCel">4. Orientación y acompañamiento a través de tutorías presenciales o virtuales, individuales o grupales; </td>
                <td class="marcoCel">11. Participación en actividades de proyectos sociales, artísticos, productivos y empresariales de vinculación con la sociedad articulados a la docencia e innovación educativa</td>
            </tr>
            <tr>
                <td class="marcoCel">5. Visitas de campo, tutorías, docencia en servicio y formación dual, en áreas como salud (formación en hospitales), derecho (litigación guiada), ciencias agropecuarias (formación en el escenario de aprendizaje), entre otras</td>
                <td class="marcoCel">12. Participación y organización de colectivos académicos de debate, capacitación o intercambio de metodologías y experiencias de enseñanza</td>
            </tr>
            <tr>
                <td class="marcoCel">6. Dirección, tutorías, seguimiento y evaluación de prácticas o pasantías pre profesionales</td>
                <td class="marcoCel">13. Uso pedagógico de la investigación y la sistematización como soporte o parte de la enseñanza</td>
            </tr>
            <tr>
                <td class="marcoCel">7. Preparación, elaboración, aplicación y calificación de exámenes, trabajos y prácticas;</td>
                <td class="marcoCel">14. Participación como profesores que impartirán los cursos de nivelación del Sistema Nacional de Nivelación y Admisión (SNNA); y, </td>
            </tr>
            <tr>
                <td class="marcoCel"></td>
                <td class="marcoCel">15. Orientación, capacitación y acompañamiento al personal académico del SNNA</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <div style="text-justify: auto">
        <p>
            En caso de incumplimiento con los horarios, asignaciones y entregas de material se sancionará conforme lo establece el reglamento interno de la Universidad.
        </p>
    </div>
    <br><br>
    <div style="text-align: center">
        Msc. Karina Muñoz<br>
        Coordinadora de Talento Humano<br>
        Universidad Tecnológica Empresarial de Guayaquil

    </div>

</div>