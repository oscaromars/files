 <?php
	$periodoAcad;
	$lblTotalCost = "TOTAL COSTO";
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
	}
	.td{
		border: 1px solid #000000;
		padding: 10px;
	}
	.aleft{
		text-align: left !important;
	}
</style>
<div>
    <div style="text-align: center">
        <br><br>
        <p><span class="bold">ACUERDO DE INSCRIPCIÓN</span></p>
        <br><br><br>
    </div>
    <div>
        <p>
			<span class="bold">Universidad::</span> <?= $nombre_uni ?><br>
			<span class="bold">Teléfono:</span> <?= $telefono_uni ?><br>
			<span class="bold">Dirección:</span> <?= $direccion_uni ?><br>
        </p><br><br>
        <span class="bold">Formulario</span><br><br>
        <table class="pinfo">
			<tbody>
				<tr class="tr"><td colspan="7" class="td"><span class="bold">INFORMACIÓN PERSONAL</span></td></tr>
				<tr class="tr">
					<td colspan="3" class="aleft td"><span class="bold">APELLIDOS</span><br><?= $apellidos_est ?></td>
					<td colspan="3" class="aleft td"><span class="bold">NOMBRES</span><br><?= $nombres_est ?></td>
					<td colspan="1" class="aleft td"><span class="bold">MI</span><br><?= $mi_est ?></td>
				</tr>
				<tr class="tr">
					<td colspan="4" class="aleft td"><span class="bold">DIRECCIÓN</span><br><?= $direccion_est ?></td>
					<td rowspan="2" class="aleft td"><span class="bold">CIUDAD</span><br><?= $ciudad_est ?></td>
					<td rowspan="2" class="aleft td"><span class="bold">ESTADO</span><br><?= $estado_est ?></td>
					<td rowspan="2" class="aleft td"><span class="bold">CÓDIGO POSTAL</span><br><?= $zipcode_est ?></td>
				</tr>
				<tr class="tr">
					<td class="td"><span class="bold">GÉNERO</span><br></td>
					<td class="td"><span class="bold">MASCULINO</span><br> (<?= (strtolower($sexo_est) == "m")?"X":" "; ?>)</td>
					<td class="td"><span class="bold">FEMENINO</span><br> (<?= (strtolower($sexo_est) == "f")?"X":" "; ?>)</td>
					<td class="td"><span class="bold">OTRO</span><br> (<?= (strtolower($sexo_est) != "m" && strtolower($sexo_est) != "f")?" ":"X"; ?>)</td>
				</tr>
				<tr class="tr">
					<td colspan="1" class="aleft td"><span class="bold">CIUDADANÍA</span></td>
					<td colspan="2" class="aleft td"><?= $ciudadania_est ?></td>
					<td rowspan="3" colspan="4" class="td"><span class="bold">NIVEL DE ESTUDIOS</span><br><?= $educacion_est ?></td>
				</tr>
				<tr class="tr">
					<td colspan="1" class="aleft td"><span class="bold">FECHA DE NACIMIENTO</span></td>
					<td colspan="2" class="aleft td"><?= date('F j, Y', strtotime($fnacimiento_est)) ?></td>
				</tr>
				<tr class="tr">
					<td colspan="1" class="aleft td"><span class="bold">LUGAR DE NACIMIENTO</span></td>
					<td colspan="2" class="aleft td"><?= $lnacimiento_est ?></td>
				</tr>
				<tr class="tr">
					<td colspan="4" class="aleft td"><span class="bold">EMAIL</span><br><?= $correo_est ?></td>
					<td colspan="3" class="aleft td"><span class="bold">NÚMERO TELEFÓNICO</span><br><?= $telefono_est ?></td>
				</tr>
				<tr class="tr"><td colspan="7" class="td"><span class="bold">INSCRIPCIÓN AL PROGRAMA</span></td></tr>
				<tr class="tr">
					<td colspan="4" class="td"><span class="bold">NOMBRE DEL PROGRAMA</span></td>
					<td colspan="1" class="td"><span class="bold">HORAS CRÉDITO</span></td>
					<td colspan="2" class="td"><span class="bold">GRADO</span></td>
				</tr>
				<tr class="tr">
					<td colspan="4" class="td"><span><?= $programa_est ?></span></td>
					<td colspan="1" class="td"><span><?= $creditos_est ?></span></td>
					<td colspan="2" class="td"><span><?= $titulo_est ?></span></td>
				</tr>
				<tr class="tr"><td colspan="7" class="aleft td"><span class="bold">COSTO ESTIMADO DEL PROGRAMA:</span><br><?= "$".(number_format($costoProgCarrera_est, 2, '.', ',')) ?></td></tr>
				<tr class="tr"><td colspan="7" class="aleft td"><span class="bold">FECHAS DE INICIO Y DE GRADUACIÓN PLANIFICADAS DEL ESTUDIANTE:</span><br>
				    <?= $finicio_est ?> - <?= $fgraduacion_est ?>
				</td></tr>
			
				<?php if($pagoPrograma_est > 1): ?>
				<tr class="tr">
					<td colspan="1" class="td"><span class="bold">NÚMERO DE PAGOS</span></td>
					<td colspan="2" class="td"><span class="bold">MONTO DE PAGO</span></td>
					<td colspan="2" class="td"><span class="bold">FECHAS DE PAGO</span></td>
					<td colspan="2" class="td"><span class="bold">STATUS</span></td>
                </tr>
				<?php if(isset($cuota1)): ?>
				<tr class="tr">
					<td colspan="1" class="td"><span>1</span></td>
					<td colspan="2" class="td"><span><?= "$".(number_format($cuota1, 2, '.', ','))?></span></td>
					<td colspan="2" class="td"><span><?= $vencimiento1 ?></span></td>
					<td colspan="2" class="td"><span>PAGADO</span></td>
                </tr>
				<?php endif; ?>
                <?php if(isset($cuota2)): ?>
				<tr class="tr">
					<td colspan="1" class="td"><span>2</span></td>
					<td colspan="2" class="td"><span><?= "$".(number_format($cuota2, 2, '.', ','))?></span></td>
					<td colspan="2" class="td"><span><?= $vencimiento2 ?></span></td>
					<td colspan="2" class="td"><span>PENDIENTE</span></td>
                </tr>
                <?php endif; ?>
                <?php if(isset($cuota3)): ?>
				<tr class="tr">
					<td colspan="1" class="td"><span>3</span></td>
					<td colspan="2" class="td"><span><?= "$".(number_format($cuota3, 2, '.', ',')) ?></span></td>
					<td colspan="2" class="td"><span><?= $vencimiento3 ?></span></td>
					<td colspan="2" class="td"><span>PENDIENTE</span></td>
                </tr>
                <?php endif; ?>
                <?php if(isset($cuota4)): ?>
				<tr class="tr">
					<td colspan="1" class="td"><span>4</span></td>
					<td colspan="2" class="td"><span><?= "$".(number_format($cuota4, 2, '.', ',')) ?></span></td>
					<td colspan="2" class="td"><span><?= $vencimiento4 ?></span></td>
					<td colspan="2" class="td"><span>PENDIENTE</span></td>
                </tr>
                <?php endif; ?>
                <?php if(isset($cuota5)): ?>
				<tr class="tr">
					<td colspan="1" class="td"><span>5</span></td>
					<td colspan="2" class="td"><span><?= "$".(number_format($cuota5, 2, '.', ',')) ?></span></td>
					<td colspan="2" class="td"><span><?= $vencimiento5 ?></span></td>
					<td colspan="2" class="td"><span>PENDIENTE</span></td>
                </tr>
                <?php endif; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<br><br>
    </div>
    <div>
		<p>
			<span class="bold">COSTOS</span><br>
			<span class="bold">TARIFAS Y COLEGIATURA</span><br>
			<span class="bold">Colegiatura:</span><br><br>
			El costo por hora crédito por programa es el siguiente::<br><br>
			<div class="blue">
				<table class="tabla">
					<tbody>
						<tr style="background: #AED6F1;border: 1px solid #002060;">
							<th>PROGRAMA</th>
							<th>COLEGIATURA</th>
						</tr>
						<?php foreach($itemsPrices as $key => $value){ 
                            if(array_search($value['Id'], $idPriceTuition) !== FALSE){
                                echo "<tr style='border: 1px solid #002060;'><td>".$value['Nombre']."</td><td>"."$".(number_format($value['Precio'], 2, '.', ','))."</td></tr>";
                            }
                        } ?>
                        <tr style="border: 1px solid #002060;">
							<td>Asociado</td>
							<td>$160.00</td>
						</tr>
						<tr style="border: 1px solid #002060;">
							<td>Licenciatura</td>
							<td>$200.00</td>
						</tr>
						<tr style="border: 1px solid #002060;">
							<td>Master</td>
							<td>$380.00</td>
						</tr>
                        

					</tbody>
				</table>
			</div>
			<br><br>
			<span class="bold">Tarifa de Aplicación</span><br>
			<div class="blue">
				<table class="tabla">
					<tbody>
						<tr style="background: #AED6F1;border: 1px solid #002060;">
							<th>PROGRAMA</th>
							<th>TARIFA</th>
                        </tr>
                        <?php foreach($itemsPrices as $key => $value){ 
                            if(array_search($value['Id'], $idPriceFees) !== FALSE){
                                echo "<tr style='border: 1px solid #002060;'><td>".$value['Nombre']."</td><td>"."$".(number_format($value['Precio'], 2, '.', ','))."</td></tr>";
                            }
                        } ?>
					</tbody>
				</table>
			</div>
			<br><br>
		</p>
		<p>
			<span class="bold">MÉTODO DE PAGO</span><br>
			MBTU ofrece a sus estudiantes dos métodos de pago por los cursos tomados en cada período académico o semestre::<br><br>
		</p>
		<ol>
			<li>1. Pago total de la matrícula total del semestre, que deberá realizarse antes del inicio de dicho período académico. * Hay un 5% de descuento para aquellos estudiantes que paguen el programa en su totalidad.</li>
			<li>2. Método de financiamiento directo en el cual el costo total de los créditos tomados por el estudiante en un semestre se dividirá en cuatro pagos durante ese período académico y deberá pagarse dentro de los primeros 5 días de cada mes.</li>
		</ol><br>
		<p>
			* Todos los pagos deben realizarse en la contabilidad bancaria de la Universidad Tecnológica Empresarial de Miami, que es la siguiente: <br><br>
			<span class="bold">Nombre de la cuenta:</span> Miami Business Technological University<br>
			<span class="bold">Número de cuenta:</span> 063100277<br>
			<span class="bold">SWIFT:</span> BOFAAUF3N<br>
		</p>
		<p>
			<span class="bold">TERMINACIÓN O CANCELACIÓN POR PARTE DE LA UNIVERSIDAD O ESTUDIANTE</span><br><br>
			<span class="bold">UNIVERSIDAD</span><br>
			MBTU se reserva el derecho a rescindir el Contrato y dar de baja al estudiante de la Universidad:<br><br>
		</p>
		<ul>
			<li>Si el decano determina que ha fallado en su programa. También debe tener en cuenta que su progreso en su programa y su título no están garantizados y dependen de su rendimiento académico;</li>
			<li>no inscribirse, por falta de pago de deudas de matrícula, o por asistencia o desempeño académico inadecuado en su programa, de acuerdo con la información contenida en el catálogo general y con las políticas y procedimientos pertinentes;</li>
			<li>si se considera que ha infringido las reglas del contrato de la Universidad;</li>
		</ul><br>
		<p>
		La Institución realizará un análisis periódico cada 3 semanas de clases. Los alumnos que presenten un avance de plataforma del 10% o menos en ese momento, ingresarán a un proceso de seguimiento donde se les informará de la obligación de realizar trabajos y actividades para cada curso inscrito. Si tras esta comunicación el alumno reaparece en el informe académico de las tres semanas siguientes, la universidad considerará que el alumno se ha dado de baja del período académico actual.
		</p><br><br>
		<p>
			<span class="bold">ESTUDIANTE</span><br>
			El estudiante es libre de decidir cuándo o por qué dejar MBTU. No obstante, solicitamos al alumno que envíe una carta escrita o correo electrónico explicando los motivos o circunstancias de por qué ha decidido abandonar sus estudios en nuestra universidad. Además, si este abandono escolar ocurre durante un semestre de clases, el estudiante estará sujeto a la política de reembolso que acordó al ingresar a MBTU. <br><br>
		</p>
		<div style="text-align: center">
        <br>
        <p><span class="bold">POLÍTICA DE REEMBOLSO</span></p>
        <br><br>
        </div>
        <p>
			<span class="bold">POLÍTICA DE ELIMINAR /AGREGAR </span><br>
			MBTU tiene un período para eliminar / agregar durante el cual los estudiantes pueden inscribirse en nuevos cursos y abandonar los cursos para los que estaban inscritos previamente sin incurrir en sanciones académicas ni financieras. Cualquier cambio realizado después del período de Eliminar/ Agregar no será aprobado y los costos de matrícula no serán reembolsados.
 			Los estudiantes que se den de baja / agreguen cursos deben cumplir con los siguientes procedimientos de autorización:<br><br>
		</p>
		<ul>
			<li>Informar a la oficina del decano sobre su interés en Eliminar / Agregar cursos.</li>
			<li>Solicitar, completar y entregar en la Oficina de Admisiones el Formulario de Eliminar / Agregar Cursos.</li>
		</ul><br>
		<p>
			La oficina del Decano responderá a la solicitud de los estudiantes dentro de los cinco (5) días hábiles.<br><br>
			Los estudiantes pueden solicitar eliminar / agregar cursos dentro de las primeras dos (2) semanas después del inicio de las clases.<br><br>
			Los estudiantes que pagaron la matrícula completa y desean agregar cursos durante el período Eliminar / Agregar, deben pagar la diferencia de costo al momento de la aprobación de su solicitud.<br><br>
			Para los estudiantes que pagaron la matrícula completa y desean abandonar los cursos durante el período Eliminar / Agregar, la Universidad reembolsará el 100% del costo de los cursos eliminados, dentro de los 30 días hábiles posteriores al día en que la universidad determine que el estudiante se ha retirado.<br><br>
			Para aquellos estudiantes que abandonen / agreguen cursos mientras usan la opción de financiamiento directo ofrecida por MBTU, la Universidad actualizará el costo final y los pagos mensuales de esos estudiantes una vez que se apruebe la solicitud.<br><br>
		</p>
		<p>
			<span class="bold">Cancelación / modiﬁcaciones patrocinadas por el programa MBTU</span><br>
			MBTU se reserva el derecho de cancelar un programa antes de que haya comenzado. En tales circunstancias, las tarifas del programa se reembolsan según la política de reembolso. MBTU y las instituciones anfitrionas asociadas se reservan el derecho de realizar cambios, modificaciones o sustituciones en el programa en caso de cambios en las ubicaciones del sitio anfitrión o en interés del programa y sus participantes.<br><br>
		</p>
		<p>
			<span class="bold">TARIFAS NO REEMBOLSABLES:</span><br><br>
		</p>
		<ul>
			<li>Tarifa de Solicitud de Aplicación</li>
		</ul><br>
		<p>
			<span class="bold">POLÍTICA DE ELIMINAR/AGREGAR</span><br>
			Se requiere que los estudiantes y sus padres o tutor legal (si son menores de 18 años) lean detenidamente todas las reglas y procedimientos de MBTU. Cualquier pregunta o inquietud sobre dicho documento debe dirigirse a un representante de MBTU. <br><br>
			Posteriormente, los estudiantes deben proceder a la firma y aprobación del acuerdo de inscripción.<br><br>
			Los estudiantes deben firmar la siguiente declaración: Reconozco que he leído y recibido una copia del acuerdo de inscripción y el catálogo de MBTU.<br><br>
		</p><br><br><br>
		<div>
			<div style="float: left; width: 50%; text-align: center;"><span class="bold">Estudiante: </span><input type="text" class="signature" value="<?= $firma_est ?>" /></div>
			<div style="text-align: center;"><span class="bold">Fecha: </span><input type="text" class="signature" value="<?= date('F j, Y', strtotime($ffirma_est)) ?>" /></div>
		</div>
		<div style="clear: both;"></div>
    </div>
</div>