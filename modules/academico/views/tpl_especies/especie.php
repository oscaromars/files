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
        width: 50%;
        float: left;
        font-size: 10px;
        text-align: left;
    }
    .tcolr_cen {
        width: 50%;
        float: left;
        font-size: 10px;
        text-align: left;
    }
    .tcoll_cen2 {
        width: 40%;
        float: left;
        font-size: 10px;
        text-align: left;
    }
    .tcolr_cen2 {
        width: 60%;
        float: left;
        font-size: 10px;
        text-align: left;
    }
    .tcoll_ad {
        width: 30%;
        float: left;
        font-size: 10px;
        text-align: left;
    }
    .tcolr_ad {
        width: 70%;
        float: left;
        font-size: 10px;
        text-align: left;
    }
    .divDetalles{
        float: left;
        width: 100%;
        position: absolute;      
        left: 0;
        margin-top: 10px;
    }
    .divDetalleAd{
        float: left;
        width: 65%;
        position: absolute;      
        left: 0;
    }
    .divDetalleTot{  
        width: 35%;
        position: absolute;      
        right: 0;
    }
    .div_modInfoAd{
        float: left;
        width: 70%;
    }
    .div_modInfoVal{
        float: left;
        width: 100%;       
    }
    .div_modInfoDet{
        float: left;
        width: 60%;
    }
    .div_modInfoDet2{
        float: left;
        width: 75%;
    }
    .div_modInfoDet1{
        float: left;
        width: 40%;
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
</style>
<div>

    <div style="text-align: right">
        <br><br>
        <p>Guayaquil, <?php echo $cabFact['FechaDia'] ?></p><br><br><br><br>
    </div>
    <div style="text-align: right">
        <br>
        <p>Trámite: <strong><?php echo $cabFact['esp_rubro'] ?>.</strong></p><br><br>
    </div>
    
    <div style="text-align: right">
        <br>
        <p>Validez máxima: <?php echo $cabFact['esp_dia_vigencia'] ?> días. </p><br><br>
    </div>
    
    <div>
        <br><br>
        <p><b><?php echo $cabFact['Responsable'] ?></b><br>
            Decano de la <?php echo $facultaded ?> de la Modalidad <?php echo $cabFact['mod_nombre'] ?>
        </p>
        <br><br>
    </div>
    <div>
        <p>De mis consideraciones:</p><br><br>
    </div>
    <div style="text-justify: auto">
        <p>Yo, <b><?php echo $cabFact['Nombres'] ?></b> con cédula de cuidadanía No. <b><?php echo $cabFact['per_cedula'] ?></b> alumno de la <?php echo $carrera ?>: <b><?php echo $cabFact['Carrera'] ?>.</b>
        </p><br><br>
    </div>
    <div style="text-justify: auto">
        <p>Solicito a Ud, autorice a quien corresponda, el trámite de mi solicitud:<br>
        <strong><?php echo $cabFact['esp_rubro'] ?>.</strong></p><br><br>
    </div>

    <div>
        <p><?php if($cabFact['detalle'] != "") echo 'Motivo:'; ?> <?php echo $cabFact['detalle'] ?></p><br><br> 
    </div>

    <div>
        <p>Esperando una favorable acogida, quedo de Ud.</p><br><br> 
    </div>
    <div>
        <p>Atentamente.</p><br><br><br>
    </div>

    <table>
        <tbody>
            <tr>
                <td>Alumno:</td>
                <td><?php echo $cabFact['Nombres'] ?></td>
                <td></td>
            </tr>
            <tr>
                <td>C.C. No.:</td>
                <td><?php echo $cabFact['per_cedula'] ?></td>
                <td></td>
            </tr>
            <tr>
                <td>Teléfono:</td>
                <td><?php echo $cabFact['per_celular'] ?></td>                
            </tr>
        </tbody>
    </table>

</div>
