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
       Distributivo Académico 
    </div>
    <br><br>
    <div class="divTable">
        <div class="divRow">
           
            <div class="divCell bold" style="width:9%;"><?php echo app\modules\fe_edoc\Module::t("fe", "FECHA") ?>:</div>
            <div class="divCell" style="width:35%;"><?php echo date("Y-m-d H:i:s"); ?></div>
        </div>
    </div>
    <br><br>
    
<table style="width:100%" class="divTabla">
        <tbody>
            <tr class="divRow">
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "DOCENTE") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "NO. CÉDULA") ?></span>
                </td>

                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "TÍTULO TERCER NIVEL") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "TÍTULO CUARTO NIVEL") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "CORREO ELECTRÓNICO") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "TIEMPO DE DEDICACION") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "TIPO ASIG") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "MATERIA") ?></span>
                </td>
                <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "TOTAL HORAS A DICTAR") ?></span>
                </td>
                 <td class="divCelda bold titleDetalle  ">
                    <span><?php echo app\modules\fe_edoc\Module::t("fe", "PROMEDIO") ?></span>
                </td>
            </tr>

            <?php
            for ($fil = 0; $fil < sizeof($res); $fil++) {
                     ?>
                    <tr class="fila">
                        <td class="marcoCel normal"><?php echo $res[$fil]['docente'] ?></td>
                        <td class="marcoCel normal"><?php echo $res[$fil]['no_cedula'] ?></td>
                        <td class="marcoCel normal"><?php echo $res[$fil]['titulo_tercel_nivel'] ?></td>
                        <td class="marcoCel normal"><?php echo $res[$fil]['titulo_cuarto_nivel'] ?></td>
                        <td class="marcoCel normal"><?php echo $res[$fil]['correo'] ?></td>
                        <td class="marcoCel normal"><?php echo $res[$fil]['tiempo_dedicacion'] ?></td>
                        <td class="marcoCel normal"><?php echo $res[$fil]['tdis_nombre'] ?></td>
                        <td class="marcoCel normal"><?php echo $res[$fil]['materia'] ?></td>
                        <td class="marcoCel normal"><?php echo $res[$fil]['total_horas_dictar'] ?></td>
                        <td class="marcoCel normal"><?php echo $res[$fil]['promedio'] ?></td>
                    </tr>
            <?php
               
            }

            ?>

           
        </tbody>
    </table>
</div> 