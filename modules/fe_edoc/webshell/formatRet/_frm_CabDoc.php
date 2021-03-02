<?php
$cabDocPDF = '<div>
    <table style="width:100mm" class="marcoDiv">
        <tbody>
            <tr>
                <td>
                    <span class="titleLabel">R.U.C.:</span>
                    <span class="titleNum_Ruc">'. $cabFact[0]["Ruc"] .'</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="titleLabel titleDocumento">'.$cabFact[0]["NombreDocumento"] .'</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="titleLabel">Nº</span>
                    <span class="titleNum_Ruc">'. $cabFact[0]["NumDocumento"] .'</span>
                </td>
              
            </tr>
            <tr>
                <td>
                    <span class="titleLabel">NÚMERO DE AUTORIZACIÓN</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span >'.$cabFact[0]["AutorizacionSRI"] .'</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="titleLabel">FECHA Y HORA AUTORIZACIÓN</span>
                    <span>'. $cabFact[0]["FechaAutorizacion"] .'</span>
                </td>
                
            </tr>
            <tr>
                <td>
                    <span class="titleLabel">AMBIENTE</span>
                    <span>'; $cabDocPDF .= ($cabFact[0]["Ambiente"]=="1")? "PRUEBA":"PRODUCCIÓN"; $cabDocPDF .= '</span>
                </td>
               
            </tr>
            <tr>
                <td>
                    <span class="titleLabel">EMISIÓN</span>
                    <span>'; $cabDocPDF .= ($cabFact[0]["TipoEmision"]=="1")?"NORMAL":"INDISPONIBILIDAD DEL SISTEMA"; $cabDocPDF .= '</span>
                </td>
                
            </tr>
            <tr>
                <td >
                    <span class="titleLabel">CLAVE DE ACCESO</span>
                </td>
            </tr>
            <tr>
                <td style="width:50%">';
                            $filename = sys_get_temp_dir() . "/" . $cabDoc[0]['CedRuc'] . ".png";
                            $barcode = new barCodeGenrator($code_number,1,$filename, 280, 150, false, true);    
                            $imgTxt = $barcode->getBase64Image();
                            $cabDocPDF .= $imgTxt;
$cabDocPDF .=   '</td>
            </tr>
            <tr>
                <td>
                    <span>'. $cabFact[0]["ClaveAcceso"] .'</span>
                </td>
            </tr>
        </tbody>
        
    </table>
</div>';
?>