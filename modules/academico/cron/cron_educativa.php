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
	            
	            $client = new \SoapClient("https://campusvirtual.uteg.edu.ec/soap/?wsdl=true", 
                                                  array("login" => "webservice", 
                                                  "password"    => "WxrrvTt8",
                                                  "trace"       => 1, "exceptions" => 0));

                $client->setCredentials("webservice", "WxrrvTt8","basic");

	            //El siguiente for es para revisar estudiante a estudiante
	            for ($i = 0; $i < count($estudiantes); $i++) {                        
	                
	                $est_id       = $estudiantes[$i]['est_id'];
	                $uedu_usuario = $estudiantes[$i]['uedu_usuario'];         
	                $cedu_id      = $estudiantes[$i]['cedu_id'];
	                $id_grupo     = $estudiantes[$i]['cedu_asi_id']; 
	                $pago         = $estudiantes[$i]['pago']; 
	                $ceest_id     = $estudiantes[$i]['ceest_id']; 
	                $ceest_estado_bloqueo = $estudiantes[$i]['ceest_estado_bloqueo']; 

	                putMessageLogFile("Procesando al estudiante: ".$est_id);
	                putMessageLogFile("Estado del estudiante: ".$pago);
	                putMessageLogFile("Estado Anterior: ".$ceest_estado_bloqueo);

	                if($pago == 'Autorizado'){
                		//Despues obtenemos los id de las unidades a bloquear
		                $id_unidad_array = consultarUnidadEducativaxCeduid($cedu_id);

		                //Este for es para recorrer en caso de que haya mas de una unidad
		                foreach ($id_unidad_array as $key => $value) {
		                	putMessageLogFile("Unidad a procesar: ".$value['ceuni_codigo_unidad']); 
		                	//Invocacion el primer Web Service que devuelve los id de los examenes...
		                	//a desbloquear
	                        $method = 'obtener_prg_items';
	                        $args = Array('id_grupo'     =>  $id_grupo,
	                                      'id_tipo_item' => 'EV',
	                                      'id_unidad'    => $value['ceuni_codigo_unidad']
	                        );  
	                        $result = $client->__call( $method, Array( $args ) );
	                        
	                        /*
	                        $obtener_prg_items = array();
	                        array_push($obtener_prg_items,$result);
	                        */
	                        //Obtengo el o los items
	                        //puede devolver mas de un item en caso que el examen tenga varias filas
	                        //Ej: Examen 1er parcial fila 1, Examen 1er parcial fila 2
	                        $prg_item = $result->prg_item;

	                        //Entra por if si solo tiene un item 
	                        //y entra por else en caso de tener mas de un item
	                        if(isset($prg_item->id_prg_item)){
	                            $method = 'asignar_usuarios_alcance_prg_items';

	                            $args = Array('asignar_usuario_item' => Array('id_usuario'  => $uedu_usuario, 
	                                                                          'id_prg_item' => $prg_item->id_prg_item));
	    
	                            $result = $client->__call( $method, Array( $args ) );

	                            modificarEstadobloqueo($cedu_id, $est_id, 'A', 1);
	                            putMessageLogFile("Nuevo Estado: A");
	                            if($ceest_estado_bloqueo == 'B')
	                                 registrarcambiohistorial($ceest_id, $pago, $ceest_estado_bloqueo, "A", $unidad);
	                        }else{
	                            if(count($prg_item) > 0){
	                                foreach ($prg_item as $key => $value) {
	                                    $method = 'asignar_usuarios_alcance_prg_items';
	                                    $args = Array('asignar_usuario_item' => Array('id_usuario'  => $uedu_usuario, 
	                                                                                 'id_prg_item' => $value->id_prg_item));
	    
	                                    $result = $client->__call( $method, Array( $args ) );

	                                    modificarEstadobloqueo($cedu_id, $est_id, 'A', 1);
	                                    if($ceest_estado_bloqueo == 'B')
	                                    	registrarcambiohistorial($ceest_id, $pago, $ceest_estado_bloqueo, "A", $unidad);
	                                }//foreach
	                            }//if
	                        }//else  
	                    }//foreach
		                putMessageLogFile("----*********-----");
	                }else{
	                	modificarEstadobloqueo($cedu_id, $est_id, 'B', 1);
	                	if($ceest_estado_bloqueo == 'A')
	                        registrarcambiohistorial($ceest_id, $pago, $ceest_estado_bloqueo, "B", '');
	                }              
	                //array_push($noactualizados,$est_id);
	            }//for por estudiante

	            putMessageLogFile("La información ha sido grabada.");
	            putMessageLogFile("Los no actualizados son: ");
	            //putMessageLogFile($noactualizados);
	            //putMessageLogFile("obtener_prg_items: ");
	            //putMessageLogFile($obtener_prg_items);

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

    function modificarEstadobloqueo($cedu_id, $est_id, $ceest_estado_bloqueo, $ceest_usuario_modifica) {
        GLOBAL $dsn, $dbuser, $dbpass, $dbname;
	    $con = new \PDO($dsn, $dbuser, $dbpass);

	    $sql = " UPDATE db_academico.curso_educativa_estudiante		       
	                SET ceest_estado_bloqueo     = '$ceest_estado_bloqueo',  
	                    ceest_usuario_modifica   = $ceest_usuario_modifica,
	                    ceest_fecha_modificacion = now()                          
	              WHERE cedu_id = $cedu_id 
	                AND est_id  =  $est_id 
	                AND ceest_estado = 1 
	                AND ceest_estado_logico = 1";
	    //putMessageLogFile("modificarEstadobloqueo: ".$sql);
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