<?php 
	//Archivo de Log 
	$logFile   = dirname(__FILE__) . "/../../../runtime/logs/webservice.log";

	//Conexion a la base de datos y creacion de la cadena de conexion.
	$dataDB   = include_once(dirname(__FILE__) . "/../config/mod.php");
	$dbname   = 'db_academico';
	$dbuser   = $dataDB["academico"]["db_academico"]["username"];
	$dbpass   = $dataDB["academico"]["db_academico"]["password"];
	$port     = "443";
	$dbserver = "127.0.0.1"; //$dataDB["marketing"]["db_mailing"]["dbserver"];
	$dbport   = 3306;
	$dsn      = "mysql:host=$dbserver;dbname=$dbname;port=$dbport";

	//Invocamos al Proceso que actualiza los estudiantes al dia
	putMessageLogFile('----- Inicio Proceso CRON de Educativa ------');
	procesoautorizacion();
	putMessageLogFile('----- Fin Proceso CRON de Educativa ------');

	function putMessageLogFile($message) {
	    global $logFile;
	    if (is_array($message))
	        $message = json_encode($message);
	    $message = date("Y-m-d H:i:s") . " " . $message . "\r\n";
	    if ((filesize($logFile) / pow(1024, 2)) > 100) { // si el log es mayor a 100 MB entonces se debe limpiar el archivo
	        file_put_contents($logFile, $message, LOCK_EX);
	    } else {
	        file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
	    }
	}//function putMessageLogFile

	function procesoautorizacion() {
	    try {
	    	//Invocamos a los datos para crear la conexion
	        GLOBAL $dsn, $dbuser, $dbpass, $dbname;
	        $pdo = new \PDO($dsn, $dbuser, $dbpass);

	        //Obtenemos los estudiantes autorizados
	        //Esta consulta devolvera: 
	        //est_id, uedu_usuario, cedu_id, cedu_asi_id, cedu_asi_nombre, paca_id
	        //y tambien devuelve pago que puede ser "Autorizado" y  "No Autorizado"
	        $estudiantes = traerestudiantesautorizados();

	        //Si existe estudiantes autorizados recorro el array
	        if (count($estudiantes) > 0) {
	            
	            putMessageLogFile('Cantidad de Estudiantes:'.count($estudiantes));
	            
	    $wsdl = 'https://campusvirtual.uteg.edu.ec/soap/?wsdl=true';
         
         $client = new \SoapClient($wsdl, [
         "soap_version" => SOAP_1_1,
         "login"    => "webservice", 
         "password" => "WxrrvTt8",
            "trace"    => 1,
         "exceptions" => 0,
         "cache_wsdl" => WSDL_CACHE_NONE,
         "stream_context" => stream_context_create(
         [
         'ssl' => [
         'verify_peer' => false,
         'verify_peer_name' => true,
         'allow_self_signed' => true,
         ]])]);

                $client->setCredentials("webservice", "WxrrvTt8","basic");

	            //El siguiente for es para revisar estudiante a estudiante
	            for ($i = 0; $i < count($estudiantes); $i++) {                        
	                
	                $est_id       = $estudiantes[$i]['est_id'];
	                $uedu_usuario = $estudiantes[$i]['uedu_usuario'];         
	                $cedu_id      = $estudiantes[$i]['cedu_id'];
	                $id_grupo     = $estudiantes[$i]['cedu_asi_id']; 
	                $pago         = $estudiantes[$i]['pago']; 
	                $ceest_id     = $estudiantes[$i]['ceest_id']; 
	                $ceest_estado_bloqueo    = $estudiantes[$i]['ceest_estado_bloqueo']; 
	                $ceest_codigo_evaluacion = $estudiantes[$i]['ceest_codigo_evaluacion'];
	                $unidad = $estudiantes[$i]['ceest_codigo_evaluacion'];

	                putMessageLogFile("Procesando al estudiante: ".$est_id);
	                putMessageLogFile("Estado del estudiante: ".$pago);
	                putMessageLogFile("Estado Anterior: ".$ceest_estado_bloqueo);

	                if($pago == 'Autorizado' && $ceest_codigo_evaluacion != ''){
                		$method = 'asignar_usuarios_alcance_prg_items';

                        $args = Array('asignar_usuario_item' => Array('id_usuario'  => $uedu_usuario, 
                                                                      'id_prg_item' => $ceest_codigo_evaluacion));

                        $result = $client->__call( $method, Array( $args ) );

                        modificarEstadobloqueo($ceest_id, 'A', 1);
                        putMessageLogFile("Nuevo Estado: A");
                        
                        if($ceest_estado_bloqueo == 'B')
                             registrarcambiohistorial($ceest_id, $pago, $ceest_estado_bloqueo, "A", $unidad);
		               
		                putMessageLogFile("----*********-----");
	                }else{
	                	modificarEstadobloqueo($ceest_id, 'B', 1);
	                	if($ceest_estado_bloqueo == 'A')
	                        registrarcambiohistorial($ceest_id, $pago, $ceest_estado_bloqueo, "B", '');
	                }              
	                //array_push($noactualizados,$est_id);
	            }//for por estudiante

	            putMessageLogFile("La información ha sido grabada.");
	            putMessageLogFile("Los no actualizados son: ");

	        }else{
	            putMessageLogFile("No hay registros por insertar.");
	        }   
	    } catch (PDOException $e) {
	        putMessageLogFile('Error: ' . $e->getMessage());
	        exit;
	    }               
	}//function procesoautorizacion

	function traerestudiantesautorizados() {
		GLOBAL $dsn, $dbuser, $dbpass, $dbname;
	    $con = new \PDO($dsn, $dbuser, $dbpass);   

	    //Este query saca los estudiantes autorizados que existen en curso_educativa_estudiante
	    //en los periodos que esten activos.
	    $sql = "SELECT distinct ccar.est_id
			          ,uedu.uedu_usuario
			          ,ceest.ceest_codigo_evaluacion
			          ,ceest.cedu_id
			          ,cedu.cedu_asi_id
			          ,cedu.cedu_asi_nombre
			          ,paca.paca_id
			          ,ceest.ceest_id
			          ,ceest.ceest_estado_bloqueo
			          ,CASE WHEN ccar.ccar_fecha_vencepago <= NOW() 
			                THEN ifnull((CASE WHEN ccar.ccar_estado_cancela = 'C'
											  THEN 'Autorizado'
											  ELSE 'No Autorizado' 
										 END),'No Autorizado')
							WHEN ccar.ccar_fecha_vencepago >= NOW() 
			                THEN ifnull((CASE WHEN ccar.ccar_estado_cancela = 'N' or ccar.ccar_estado_cancela = 'C'
											  THEN 'Autorizado'
											  ELSE 'No Autorizado' 
										 END ),'No Autorizado')						 
							ELSE 'No Autorizado'
							END as pago 
			      FROM db_academico.periodo_academico paca
			INNER JOIN db_academico.curso_educativa cedu
					ON cedu.paca_id = cedu.paca_id
				   AND cedu.cedu_estado = 1
			       AND cedu.cedu_estado_logico = 1 
			INNER JOIN db_academico.curso_educativa_estudiante ceest
					ON ceest.cedu_id = cedu.cedu_id
				   AND ceest.ceest_estado = 1
			       AND ceest.ceest_estado_logico = 1
			INNER JOIN db_facturacion.carga_cartera ccar
			        ON ccar.est_id = ceest.est_id 
				   AND ccar.ccar_fecha_vencepago <= now()
				   AND ccar.ccar_estado = 1 
			       AND ccar.ccar_estado_logico = 1
			INNER JOIN db_academico.usuario_educativa uedu
					ON uedu.est_id = ceest.est_id
			       AND uedu.uedu_estado = 1
			       AND uedu.uedu_estado_logico = 1
				 WHERE paca.paca_activo = 'A'
				   AND paca.paca_estado = 1
			       AND paca.paca_estado_logico = 1";

	    $comando = $con->prepare($sql);
	    $comando->execute();
	    return $comando->fetchAll(\PDO::FETCH_ASSOC);
	}//function traerestudiantesautorizados

	function consultarUnidadEducativaxCeduid($cedu_id) {
        GLOBAL $dsn, $dbuser, $dbpass, $dbname;
	    $con = new \PDO($dsn, $dbuser, $dbpass);     

        $sql = " SELECT ceuni.cedu_id,
					    ceuni.ceuni_id,
						cedu.cedu_asi_nombre,                         
						ceuni.ceuni_codigo_unidad,
						ceuni.ceuni_descripcion_unidad,
						ceuni.ceuni_fecha_inicio,
                        ceuni.ceuni_fecha_fin
				   FROM db_academico.curso_educativa_unidad ceuni
			 INNER JOIN db_academico.curso_educativa cedu 
					 ON cedu.cedu_id = ceuni.cedu_id
				  WHERE ceuni.cedu_id = $cedu_id
					AND ceuni.ceuni_estado = 1
					AND ceuni.ceuni_estado_logico = 1
					AND NOW() BETWEEN ceuni.ceuni_fecha_inicio AND ceuni.ceuni_fecha_fin";
		     //AQUI VA EL CAMBIO CUANDO SE PREGUNTE POR LA FECHA DE LA UNIDAD

        $comando = $con->prepare($sql);
        $comando->execute();
        return $comando->fetchAll(\PDO::FETCH_ASSOC);
    }//function consultarUnidadEducativaxCeduid

    function modificarEstadobloqueo($ceest_id, $ceest_estado_bloqueo, $ceest_usuario_modifica) {
        GLOBAL $dsn, $dbuser, $dbpass, $dbname;
	    $con = new \PDO($dsn, $dbuser, $dbpass);

	    $sql = "UPDATE db_academico.curso_educativa_estudiante               
                   SET ceest_estado_bloqueo     = '$ceest_estado_bloqueo',  
                       ceest_usuario_modifica   = $ceest_usuario_modifica,
                       ceest_fecha_modificacion = now()                          
                 WHERE ceest_id            = $ceest_id
                   AND ceest_estado        = 1 
                   AND ceest_estado_logico = 1";

	    $comando = $con->prepare($sql);
        $comando->execute();
        return $comando->fetchAll(\PDO::FETCH_ASSOC);
    }//function modificarEstadobloqueo
	
	function registrarcambiohistorial($ceest_id, $ceeh_estado_pago, $ceeh_est_bloqueo_anterior, $ceeh_est_bloqueo, $unidad) {
        GLOBAL $dsn, $dbuser, $dbpass, $dbname;
	    $con = new \PDO($dsn, $dbuser, $dbpass);

	    $sql = "INSERT INTO `db_academico`.`curso_educativa_estudiante_historial`
							(`ceest_id`,
							`ceeh_estado_pago`,
							`ceeh_est_bloqueo_anterior`,
							`ceeh_est_bloqueo`,
							`ceeh_unidad`,
							`ceeh_usuario_creacion`,
							`ceeh_estado`,
							`ceeh_fecha_creacion`,
							`ceeh_estado_logico`)
					 VALUES($ceest_id,
							'$ceeh_estado_pago',
							'$ceeh_est_bloqueo_anterior',
							'$ceeh_est_bloqueo',
							'$unidad',
							1,
							1,
							now(),
							1)";
		putMessageLogFile("registrarcambiohistorial: ".$sql);
	    $comando = $con->prepare($sql);
        $comando->execute();
        return $comando->fetchAll(\PDO::FETCH_ASSOC);
    }//function modificarEstadobloqueo
?>