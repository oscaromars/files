<?php
	//$periodoAcad;
	//$lblTotalCost = "TOTAL COSTO";
	if($costoProgCarrera_est != $costoCarrera_est)
		$lblTotalCost .= " PERIODO" . strtoupper($periodoAcad);
 ?>
<style>
    .bold{
        font-weight: bold;
    }
    .blue{
        color:#000000 !important;
    }
    .tabla {       
        color:#000000 !important;
        width: 400px;
        /*width: 100%;*/
        text-align: center;
        vertical-align: top;
        border: 1px solid #000000;
        border-collapse: collapse;
        padding: 0.3em;
        caption-side: bottom;
        margin: 0 auto;
    }  
    .signature {
		border: 0;
		border-bottom: 1px solid #000;
		text-align: center;
		width: 200px;
	}
	table.pinfo {
		text-align: center;
        border: 1px solid #000000;
		padding: 0.3em;
		caption-side: bottom;
        margin: 0 auto;
		border-collapse:collapse;
	}
	.tr{
		border: 1px solid #000000;
		text-align: center;
	}
	.td{
		border: 1px solid #000000;
		padding: 10px;
		text-align: center;
	}
	.aleft{
		text-align: left !important;
	}
</style>
<div>
    <div style="text-align: center">
        <br><br>
        <p><span class="bold">MIAMI BUSINESS TECHNOLOGICAL UNIVERSITY</span></p>
        <br><br><br>
    </div>
     <div style="text-align: center">
        <br><br>
        
        <p><span class="bold">REPORTE DE CALIFICACIONES</span></p>
        <br><br><br>
    </div>
    <div>
    	<table class="default">
		 
		  <tr class="tr">
		  	<td colspan="5" class="td"><span class="bold">FACULTAD:</span><br>  </td>
		  </tr>
		
		  <tr class="tr">
					<td colspan="1" class="aleft td"><span class="bold">COURSE</span></td>
					<td colspan="1" class="aleft td"><span class="bold"><?= $model[0]['asi_nombre'] ?></span></td>
					<td colspan="1" class="aleft td"><span class="bold">NUMBERS</span></td>
					<td colspan="1" class="aleft td"><span class="bold"><?= $model[0]['par_nombre'] ?></span></td>
		  </tr>
		  <tr class="tr">
		    <td colspan="2" class="aleft td"><span class="bold">PERIODO:</span></td>
		    <td colspan="2" class="aleft td">    <?= $model[0]['paca_nombre'] ?>   </td>
		  </tr>
		</table>
    	<br>
    	<br>
        <table class="default">
        	 <thead>
        		<tr class="tr">
					<td  class="td"><span class="bold">NÚMERO</span></td>
					<td  class="td"><span class="bold">STUDENTS</span></td>
					<td  class="td"><span class="bold">MDTERM</span></td>
					<td  class="td"><span class="bold">FINAL</span></td>
					<td  class="td"><span class="bold">COURSE SCORE</span></td>
                </tr>
			   
		  </thead>
			<tbody>
				
				

                <?php 
                $i=0;
                foreach($model as $key => $value){ 
                	$i++;
                          
            	echo "<tr style='border: 1px solid #002060;'>
            			<td style='text-align: center' >".$i."</td>
            			<td style='text-align: center'>".$value['Nombres_completos']."</td>
            			<td style='text-align: center'>".$value['Parcial_I']."</td>
            			<td style='text-align: center'>".  $value['Parcial_II'] ."</td>
            			<td style='text-align: center'>".$value['promedio_final']."</td> 
            			</tr>";
                           
                  } ?>
               
			</tbody>
		</table>
		<br><br>
    </div>
</div>