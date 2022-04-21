<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use app\models\EmpresaPersona;
use app\models\Persona;
use app\models\UsuaGrolEper;
use app\models\Usuario;
use app\modules\admision\models\Interesado;
use app\modules\admision\models\InteresadoEmpresa;
use app\modules\financiero\models\OrdenPago;
use app\modules\financiero\models\Secuencias;
use Yii;
use yii\base\Exception;
use yii\base\Security;
use \app\modules\academico\models\Estudiante;
use \app\modules\academico\models\ModuloEstudio;
use \app\modules\admision\models\SolicitudInscripcion;

/**
 * Description of InscripcionAdmision
 *
 * @author root
 */
class InscripcionAdmision extends \yii\db\ActiveRecord {

	//put your code here
	public function insertarInscripcion($data) {
		$arroout = array();
		$con = \Yii::$app->db_captacion;
		$trans = $con->beginTransaction();
		try {
			$twin_id = $this->insertarDataInscripcion($con, $data["DATA_1"]);
			if (empty($data['opcion'])) {
				$data = $this->consultarDatosInscripcion($twin_id);
			} else {
				$data = $this->consultarDatosInscripcionContinua($twin_id);
			}
			$trans->commit();
			//RETORNA DATOS
			$arroout["status"] = TRUE;
			$arroout["error"] = null;
			$arroout["message"] = null;
			$arroout["ids"] = $twin_id;
			$arroout["data"] = $data; //$rawData;
			return $arroout;
		} catch (\Exception $e) {
			$trans->rollback();

			$arroout["status"] = FALSE;
			$arroout["error"] = $e->getCode();
			$arroout["message"] = $e->getMessage();
			$arroout["data"] = null; //$rawData;
			return $arroout;
		}
	}

	public function actualizarInscripcion($data) {
		$arroout = array();
		$con = \Yii::$app->db_captacion;
		$trans = $con->beginTransaction();
		try {
			$twin_id = $this->updateDataInscripcion($con, $data["DATA_1"]);
			$data = $this->consultarDatosInscripcionInstituto($twin_id);

			$trans->commit();
			$arroout["status"] = TRUE;
			$arroout["error"] = null;
			$arroout["message"] = null;
			$arroout["ids"] = $twin_id;
			$arroout["data"] = $data; //$rawData;
			return $arroout;
		} catch (Exception $e) {
			$trans->rollback();
			$arroout["status"] = FALSE;
			$arroout["error"] = $e->getCode();
			$arroout["message"] = $e->getMessage();
			$arroout["data"] = null; //$rawData;
			return $arroout;
		}
	}

	private function insertarDataInscripcion($con, $data) {
		$ruta_doc_titulo = '';
		$ruta_doc_dni = '';
		$ruta_doc_certvota = '';
		$ruta_doc_foto = '';
		$ruta_doc_certificado = '';
		$twin_mensaje1 = 0;
		$twin_mensaje2 = 0;

		$sql = "INSERT INTO " . $con->dbname . ".temporal_wizard_inscripcion
            (twin_nombre,twin_apellido,twin_dni,twin_numero,twin_correo,twin_empresa,twin_pais,twin_celular,uaca_id,
             mod_id,car_id,twin_metodo_ingreso,conuteg_id,ruta_doc_titulo, ruta_doc_dni, ruta_doc_certvota,
             ruta_doc_foto,ruta_doc_certificado, twin_mensaje1,twin_mensaje2,twin_nivel_instruccion,twin_redes_sociales,twin_encontramos,twin_estado,twin_fecha_creacion,twin_estado_logico,twin_item )VALUES
            (:twin_nombre,:twin_apellido,:twin_dni,:twin_numero,:twin_correo,:twin_empresa,:twin_pais,:twin_celular,:uaca_id,
             :mod_id,:car_id,:twin_metodo_ingreso,:conuteg_id,:ruta_doc_titulo,:ruta_doc_dni,:ruta_doc_certvota,
             :ruta_doc_foto,:ruta_doc_certificado,:twin_mensaje1,:twin_mensaje2,:twin_nivel_instruccion,:twin_redes_sociales,:twin_encontramos,1,CURRENT_TIMESTAMP(),1,:twin_item)";

		$met_ing = 0;
		if (empty($data[0]['ming_id'])) {
			$met_ing = 0;
		} else {
			$met_ing = $data[0]['ming_id'];
		}
		\app\models\Utilities::putMessageLogFile('identificacion:' . $data[0]['pges_cedula']);
		$command = $con->createCommand($sql);
		$command->bindParam(":twin_nombre", $data[0]['pges_pri_nombre'], \PDO::PARAM_STR);
		$command->bindParam(":twin_apellido", $data[0]['pges_pri_apellido'], \PDO::PARAM_STR);
		$command->bindParam(":twin_dni", $data[0]['tipo_dni'], \PDO::PARAM_STR);
		$command->bindParam(":twin_numero", $data[0]['pges_cedula'], \PDO::PARAM_STR);
		$command->bindParam(":twin_empresa", ucfirst(mb_strtolower($data[0]['pges_empresa'], 'UTF-8')), \PDO::PARAM_STR);
		$command->bindParam(":twin_correo", $data[0]['pges_correo'], \PDO::PARAM_STR);
		$command->bindParam(":twin_pais", $data[0]['pais'], \PDO::PARAM_STR);
		$command->bindParam(":twin_celular", $data[0]['pges_celular'], \PDO::PARAM_STR);
		$command->bindParam(":uaca_id", $data[0]['unidad_academica'], \PDO::PARAM_STR);
		$command->bindParam(":mod_id", $data[0]['modalidad'], \PDO::PARAM_STR);
		$command->bindParam(":car_id", $data[0]['carrera'], \PDO::PARAM_STR);
		$command->bindParam(":twin_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
		$command->bindParam(":conuteg_id", $data[0]['conoce'], \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_titulo", $ruta_doc_titulo, \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_dni", $ruta_doc_dni, \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_certvota", $ruta_doc_certvota, \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_foto", $ruta_doc_foto, \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_certificado", $ruta_doc_certificado, \PDO::PARAM_STR);
		$command->bindParam(":twin_mensaje1", $twin_mensaje1, \PDO::PARAM_STR);
		$command->bindParam(":twin_mensaje2", $twin_mensaje2, \PDO::PARAM_STR);
		$command->bindParam(":twin_nivel_instruccion", $data[0]['nivinstrucion'], \PDO::PARAM_INT);
		$command->bindParam(":twin_redes_sociales", $data[0]['redes'], \PDO::PARAM_INT);
		$command->bindParam(":twin_encontramos", $data[0]['encontramos'], \PDO::PARAM_STR);
		$command->bindParam(":twin_item", $data[0]['item'], \PDO::PARAM_INT);
		$command->execute();
		return $con->getLastInsertID();
	}

	private function updateDataInscripcion($con, $data) {
		$sql = "UPDATE " . $con->dbname . ".temporal_wizard_inscripcion
                SET twin_nombre=:twin_nombre,twin_apellido=:twin_apellido,twin_dni=:twin_dni,twin_numero=:twin_numero,
                    twin_correo=:twin_correo,twin_empresa=:twin_empresa,twin_pais=:twin_pais,twin_celular=:twin_celular,uaca_id=:uaca_id,
                    mod_id=:mod_id,car_id=:car_id,twin_metodo_ingreso=:twin_metodo_ingreso,conuteg_id=:conuteg_id,ruta_doc_titulo=:ruta_doc_titulo,
                    ruta_doc_dni=:ruta_doc_dni, ruta_doc_certvota=:ruta_doc_certvota,ruta_doc_foto=:ruta_doc_foto,
                    ruta_doc_hojavida=:ruta_doc_hojavida,ruta_doc_certificado=:ruta_doc_certificado,
                    ruta_doc_aceptacion=:ruta_doc_aceptacion, cemp_id=:cemp_id, ruta_doc_pago=:ruta_doc_pago, twin_tipo_pago=:forma_pago,
                    twin_mensaje1=:twin_mensaje1,twin_mensaje2=:twin_mensaje2,
                    twin_nivel_instruccion=:twin_nivel_instruccion,twin_redes_sociales=:twin_redes_sociales,
                    twin_encontramos=:twin_encontramos,twin_item = :twin_item, twin_fecha_modificacion=CURRENT_TIMESTAMP()
                 WHERE twin_id =:twin_id ";
		$met_ing = 0;
		if (empty($data[0]['ming_id'])) {
			$met_ing = 0;
		} else {
			$met_ing = $data[0]['ming_id'];
		}
		$command = $con->createCommand($sql);
		$command->bindParam(":twin_id", $data[0]['twin_id'], \PDO::PARAM_STR);
		$command->bindParam(":twin_nombre", $data[0]['pges_pri_nombre'], \PDO::PARAM_STR);
		$command->bindParam(":twin_apellido", $data[0]['pges_pri_apellido'], \PDO::PARAM_STR);
		$command->bindParam(":twin_dni", $data[0]['tipo_dni'], \PDO::PARAM_STR);
		$command->bindParam(":twin_numero", $data[0]['pges_cedula'], \PDO::PARAM_STR);
		$command->bindParam(":twin_empresa", ucfirst(mb_strtolower($data[0]['pges_empresa'], 'UTF-8')), \PDO::PARAM_STR);
		$command->bindParam(":twin_correo", strtolower($data[0]['pges_correo']), \PDO::PARAM_STR);
		$command->bindParam(":twin_pais", $data[0]['pais'], \PDO::PARAM_STR);
		$command->bindParam(":twin_celular", $data[0]['pges_celular'], \PDO::PARAM_STR);
		$command->bindParam(":uaca_id", $data[0]['unidad_academica'], \PDO::PARAM_STR);
		$command->bindParam(":mod_id", $data[0]['modalidad'], \PDO::PARAM_STR);
		$command->bindParam(":car_id", $data[0]['carrera'], \PDO::PARAM_STR);
		$command->bindParam(":twin_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
		$command->bindParam(":conuteg_id", $data[0]['conoce'], \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_titulo", basename($data[0]['ruta_doc_titulo']), \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_dni", basename($data[0]['ruta_doc_dni']), \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_certvota", basename($data[0]['ruta_doc_certvota']), \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_foto", basename($data[0]['ruta_doc_foto']), \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_hojavida", basename($data[0]['ruta_doc_hojavida']), \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_certificado", basename($data[0]['ruta_doc_certificado']), \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_aceptacion", basename($data[0]['ruta_doc_aceptacion']), \PDO::PARAM_STR);
		$command->bindParam(":cemp_id", basename($data[0]['cemp_id']), \PDO::PARAM_INT);
		$command->bindParam(":twin_mensaje1", $data[0]['twin_mensaje1'], \PDO::PARAM_STR);
		$command->bindParam(":twin_mensaje2", $data[0]['twin_mensaje2'], \PDO::PARAM_STR);
		$command->bindParam(":twin_nivel_instruccion", $data[0]['nivinstrucion'], \PDO::PARAM_INT);
		$command->bindParam(":twin_redes_sociales", $data[0]['redes'], \PDO::PARAM_INT);
		$command->bindParam(":twin_encontramos", $data[0]['encontramos'], \PDO::PARAM_STR);
		$command->bindParam(":ruta_doc_pago", basename($data[0]['ruta_doc_pago']), \PDO::PARAM_STR);
		$command->bindParam(":forma_pago", $data[0]['forma_pago'], \PDO::PARAM_STR);
		$command->bindParam(":twin_item", $data[0]['item'], \PDO::PARAM_INT);
		$command->execute();

		return $data[0]['twin_id'];
	}

	/**
	 * Function addLabelTimeDocumentos renombra el documento agregando una varible de tiempo
	 * @author  Developer Uteg <developer@uteg.edu.ec>
	 * @param   int     $sins_id        Id de la solicitud
	 * @param   string  $file           Uri del Archivo a modificar
	 * @param   int     $timeSt         Parametro a agregar al nombre del archivo
	 * @return  $newFile | FALSE (Retorna el nombre del nuevo archivo o false si fue error).
	 */
	public static function addLabelTimeDocumentos($sins_id, $file, $timeSt) {
		$arrIm = explode(".", basename($file));
		$typeFile = strtolower($arrIm[count($arrIm) - 1]);
		$baseFile = Yii::$app->basePath;
		$search = ".$typeFile";
		$replace = "_$timeSt" . ".$typeFile";
		$newFile = str_replace($search, $replace, $file);
		if (file_exists($baseFile . $file)) {
			if (rename($baseFile . $file, $baseFile . $newFile)) {
				return $newFile;
			}
		} else {
			return $newFile;
		}
	}

	public static function addLabelFechaDocPagos($sins_id, $file, $FechaTime) {
		$arrIm = explode(".", basename($file));
		$typeFile = strtolower($arrIm[count($arrIm) - 1]);
		$baseFile = Yii::$app->basePath;
		$search = ".$typeFile";
		$replace = "-$FechaTime" . ".$typeFile";
		$newFile = str_replace($search, $replace, $file);
		if (file_exists($baseFile . $file)) {
			if (rename($baseFile . $file, $baseFile . $newFile)) {
				return $newFile;
			}
		} else {
			return $newFile;
		}
	}

	/**
	 * Function consultarDatosInscripcion
	 * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
	 * @param
	 * @return  $resultData (Obtiene los datos de inscripción y el precio de la solicitud.)
	 */
	public function consultarDatosInscripcion($twin_id) {
		$con = \Yii::$app->db_captacion;
		$con2 = \Yii::$app->db_facturacion;
		$con1 = \Yii::$app->db_academico;
		$estado = 1;
		$estado_precio = 'A';

		$sql = "
                SELECT  ua.uaca_nombre unidad,
                        m.mod_nombre modalidad,
                        ea.eaca_nombre carrera,
                        ea.eaca_id as id_carrera,
                        mi.ming_nombre metodo,
                        ip.ipre_precio as precio,
                        twin_nombre,
                        twin_apellido,
                        twin_numero,
                        twin_empresa,
                        twin_correo,
                        twin_pais,
                        twin_celular,
                        twi.uaca_id,
                        twi.mod_id,
                        twi.car_id,
                        twin_metodo_ingreso,
                        conuteg_id,
                        ruta_doc_titulo,
                        ruta_doc_dni,
                        -- 96 as ddit_valor,-- ddit.ddit_valor,
                        ruta_doc_certvota,
                        ruta_doc_foto,
                        ruta_doc_certificado,
                        ruta_doc_hojavida,
                        twin_dni,
                        ruta_doc_aceptacion,
                        twi.cemp_id,
                        twin_tipo_pago,
                        ruta_doc_pago
                FROM " . $con->dbname . ".temporal_wizard_inscripcion twi inner join db_academico.unidad_academica ua on ua.uaca_id = twi.uaca_id
                     inner join " . $con1->dbname . ".modalidad m on m.mod_id = twi.mod_id
                     inner join " . $con1->dbname . ".estudio_academico ea on ea.eaca_id = twi.car_id
                     left join " . $con->dbname . ".metodo_ingreso mi on mi.ming_id = twi.twin_metodo_ingreso
                     left join " . $con2->dbname . ".item_metodo_unidad imi on (imi.ming_id =  twi.twin_metodo_ingreso and imi.uaca_id = twi.uaca_id and imi.mod_id = twi.mod_id)
                     left join " . $con2->dbname . ".item_precio ip on ip.ite_id = imi.ite_id
                     left join " . $con2->dbname . ".descuento_item as ditem on ditem.ite_id=imi.ite_id
                     left join " . $con2->dbname . ".detalle_descuento_item as ddit on ddit.dite_id=ditem.dite_id
                WHERE twi.twin_id = :twin_id AND
                     -- ip.ipre_estado_precio = :estado_precio AND
                     ua.uaca_estado = :estado AND
                     ua.uaca_estado_logico = :estado AND
                     m.mod_estado = :estado AND
                     m.mod_estado_logico = :estado AND
                     ea.eaca_estado = :estado AND
                     ea.eaca_estado_logico = :estado
                     -- imi.imni_estado = :estado AND
                     -- imi.imni_estado_logico = :estado
                     -- AND ip.ipre_estado = :estado AND
                     -- ip.ipre_estado_logico = :estado";
		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":twin_id", $twin_id, \PDO::PARAM_INT);
		$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
		$resultData = $comando->queryOne();
		return $resultData;
	}

	public function insertaOriginal($twinIds, $dataReg) {
		$con = \Yii::$app->db_asgard;
		$con1 = \Yii::$app->db_captacion;
		$con2 = \Yii::$app->db_facturacion;
		$usuario_ingreso = @Yii::$app->session->get("PB_iduser");
		$transaction = $con->beginTransaction();
		$transaction1 = $con1->beginTransaction();
		$transaction2 = $con2->beginTransaction();
		try {
			//Se consulta la información grabada en la tabla temporal.
			$mod_inscripcion = new InscripcionAdmision();
			if (empty($dataReg["empresa"])) {
				$resp_datos = $mod_inscripcion->consultarDatosInscripcion($twinIds);
			} else {
				$resp_datos = $mod_inscripcion->consultarDatosInscripcionContinua($twinIds);
			}
			// He colocado al inicio la informacion para que cargue al principio
			if ($resp_datos) {
				if (isset($resp_datos['twin_numero']) && strlen($resp_datos['twin_numero']) > 0) {
					$identificacion = $resp_datos['twin_numero'];
				} else {
					$identificacion = $resp_datos['twin_numero'];
				}
				if (isset($identificacion) && strlen($identificacion) > 0) {
					$id_persona = 0;
					$mod_persona = new Persona();
					$keys_per = [
						'per_pri_nombre', 'per_seg_nombre', 'per_pri_apellido', 'per_seg_apellido',
						'per_cedula', 'etn_id', 'eciv_id', 'per_genero', 'pai_id_nacimiento',
						'pro_id_nacimiento', 'can_id_nacimiento', 'per_fecha_nacimiento',
						'per_celular', 'per_correo', 'tsan_id', 'per_domicilio_sector',
						'per_domicilio_cpri', 'per_domicilio_csec', 'per_domicilio_num',
						'per_domicilio_ref', 'per_domicilio_telefono', 'per_trabajo_nombre', 'pai_id_domicilio',
						'pro_id_domicilio', 'can_id_domicilio', 'per_nac_ecuatoriano',
						'per_nacionalidad', 'per_foto', 'per_usuario_ingresa', 'per_estado', 'per_estado_logico',
					];
					$parametros_per = [
						ucwords(strtolower($resp_datos['twin_nombre'])), null,
						ucwords(strtolower($resp_datos['twin_apellido'])), null,
						$resp_datos['twin_numero'], null, null, null, null, null,
						null, null, $resp_datos['twin_celular'], strtolower($resp_datos['twin_correo']),
						null, null, null, null,
						null, null, null, $resp_datos['twin_empresa'],
						null, null, null,
						null, null, null, $usuario_ingreso, 1, 1,
					];
					$id_persona = $mod_persona->consultarIdPersona($resp_datos['twin_numero'], $resp_datos['twin_numero'], $resp_datos['twin_correo'], $resp_datos['twin_celular']);
					if ($id_persona == 0) {
						$id_persona = $mod_persona->insertarPersona($con, $parametros_per, $keys_per, 'persona');
					}
					if ($id_persona > 0) {
						\app\models\Utilities::putMessageLogFile('se crea persona.');
						//Modifificaion para Mover Imagenes de temp a Persona
						//self::movePersonFiles($twinIds,$id_persona);
						$concap = \Yii::$app->db_captacion;
						$mod_emp_persona = new EmpresaPersona();
						if (!empty($dataReg["empresa"])) {
							$emp_id = $dataReg["empresa"];
						} else {
							$emp_id = 1;
						}
						$keys = ['emp_id', 'per_id', 'eper_estado', 'eper_estado_logico'];
						$parametros = [$emp_id, $id_persona, 1, 1];
						$emp_per_id = $mod_emp_persona->consultarIdEmpresaPersona($id_persona, $emp_id);
						if ($emp_per_id == 0) {
							$emp_per_id = $mod_emp_persona->insertarEmpresaPersona($con, $parametros, $keys, 'empresa_persona');
						}
						if ($emp_per_id > 0) {
							$usuario = new Usuario();
							$usuario_id = $usuario->consultarIdUsuario($id_persona, $resp_datos['twin_correo']);
							if ($usuario_id == 0) {
								$security = new Security();
								$hash = $security->generateRandomString();
								$passencrypt = base64_encode($security->encryptByPassword($hash, $resp_datos['twin_numero']));
								$keys = ['per_id', 'usu_user', 'usu_sha', 'usu_password', 'usu_estado', 'usu_estado_logico'];
								$parametros = [$id_persona, $resp_datos['twin_correo'], $hash, $passencrypt, 1, 1];
								$usuario_id = $usuario->crearUsuarioTemporal($con, $parametros, $keys, 'usuario');
							}
							if ($usuario_id > 0) {
								$mod_us_gr_ep = new UsuaGrolEper();
								$grol_id = 30;
								$keys = ['eper_id', 'usu_id', 'grol_id', 'ugep_estado', 'ugep_estado_logico'];
								$parametros = [$emp_per_id, $usuario_id, $grol_id, 1, 1];
								$us_gr_ep_id = $mod_us_gr_ep->consultarIdUsuaGrolEper($emp_per_id, $usuario_id, $grol_id);
								if ($us_gr_ep_id == 0) {
									$us_gr_ep_id = $mod_us_gr_ep->insertarUsuaGrolEper($con, $parametros, $keys, 'usua_grol_eper');
								}

								if ($us_gr_ep_id > 0) {
									$mod_interesado = new Interesado(); // se guarda con estado_interesado 1
									$interesado_id = $mod_interesado->consultaInteresadoById($id_persona);
									$keys = ['per_id', 'int_estado_interesado', 'int_usuario_ingreso', 'int_estado', 'int_estado_logico'];
									$parametros = [$id_persona, 1, $usuario_id, 1, 1];
									if ($interesado_id == 0) {
										$interesado_id = $mod_interesado->insertarInteresado($concap, $parametros, $keys, 'interesado');
									}
									if ($interesado_id > 0) {
										$mod_inte_emp = new InteresadoEmpresa(); // se guarda con estado_interesado 1
										$iemp_id = $mod_inte_emp->consultaInteresadoEmpresaById($interesado_id, $emp_id);
										if ($iemp_id == 0) {
											$iemp_id = $mod_inte_emp->crearInteresadoEmpresa($interesado_id, $emp_id, $usuario_id);
										}
										if ($iemp_id > 0) {
											$eaca_id = NULL;
											$mest_id = NULL;
											if ($emp_id == 1) {
//Uteg
												$eaca_id = $resp_datos['car_id'];
											} elseif ($emp_id == 2 || $emp_id == 3) {
												$mest_id = $resp_datos['car_id'];
											}
											$num_secuencia = Secuencias::nuevaSecuencia($con, $emp_id, 1, 1, 'SOL');
											$sins_fechasol = date(Yii::$app->params["dateTimeByDefault"]);
											$rsin_id = 1; //Solicitud pendiente
											$solins_model = new SolicitudInscripcion();
											//$mensaje = 'intId: ' . $interesado_id . '/uaca: ' . $pgest['unidad_academica'] . '/modalidad: ' . $pgest['modalidad'] . '/ming: ' . $pgest['ming_id'] . '/eaca: ' . $eaca_id . '/mest: ' . $mest_id . '/empresa: ' . $emp_id . '/secuencia: ' . $num_secuencia . '/rsin_id: ' . $rsin_id . '/sins_fechasol: ' . $sins_fechasol . '/usuario_id: ' . $usuario_id;
											$ming = null;
											if ($resp_datos['uaca_id'] == 1) {
												$ming = null;
											} else {
												if ($resp_datos['twin_metodo_ingreso'] == 0) {
													$ming = null;
												} else {
													$ming = $resp_datos['twin_metodo_ingreso'];
												}
											}
											if ($resp_datos['cemp_id'] == 0) {
												$cemp = null;
											} else {
												$cemp = $resp_datos['cemp_id'];
											}
											$sins_id = $solins_model->insertarSolicitud($interesado_id, $resp_datos['uaca_id'], $resp_datos['mod_id'], $resp_datos['twin_metodo_ingreso'], $eaca_id, $mest_id, $emp_id, $num_secuencia, $rsin_id, $sins_fechasol, $usuario_id, $cemp);
											//grabar los documentos
											if ($sins_id) {
												if (($resp_datos['ruta_doc_titulo'] != "") || ($resp_datos['ruta_doc_dni'] != "") || ($resp_datos['ruta_doc_certvota'] != "") || ($resp_datos['ruta_doc_foto'] != "") || ($resp_datos['ruta_doc_certificado'] != "") || ($resp_datos['ruta_doc_hojavida'] != "")) {
													$subidaDocumentos = 1;
												} else {
													$subidaDocumentos = 0;
												}
												if ($resp_datos['ruta_doc_titulo'] != "") {
													$arrIm = explode(".", basename($resp_datos['ruta_doc_titulo']));
													$arrTime = explode("_", basename($resp_datos['ruta_doc_titulo']));
													$timeSt = $arrTime[4];
													$typeFile = strtolower($arrIm[count($arrIm) - 1]);
													$rutaTitulo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_titulo_per_" . $id_persona . "_" . $timeSt;
													$resulDoc1 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 1, $rutaTitulo, $usuario_id);
													/* if (!($resulDoc1)) {
														                                                      throw new Exception('Error doc Titulo no creado.');
													*/
												}
												if ($resp_datos['ruta_doc_dni'] != "") {
													$arrIm = explode(".", basename($resp_datos['ruta_doc_dni']));
													$arrTime = explode("_", basename($resp_datos['ruta_doc_dni']));
													$timeSt = $arrTime[4];
													$typeFile = strtolower($arrIm[count($arrIm) - 1]);
													$rutaDni = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_dni_per_" . $id_persona . "_" . $timeSt;
													$resulDoc2 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 2, $rutaDni, $usuario_id);
													/* if (!($resulDoc2)) {
														                                                      throw new Exception('Error doc Titulo no creado.');
													*/
												}
												if ($resp_datos['ruta_doc_certvota'] != "") {
													$arrIm = explode(".", basename($resp_datos['ruta_doc_certvota']));
													$arrTime = explode("_", basename($resp_datos['ruta_doc_certvota']));
													$timeSt = $arrTime[4];
													$typeFile = strtolower($arrIm[count($arrIm) - 1]);
													$rutaCertvota = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_certvota_per_" . $id_persona . "_" . $timeSt;
													$resulDoc3 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 3, $rutaCertvota, $usuario_id);
													/* if (!($resulDoc3)) {
														                                                      throw new Exception('Error doc Cert.Votación no creado.');
													*/
												}
												if ($resp_datos['ruta_doc_foto'] != "") {
													$arrIm = explode(".", basename($resp_datos['ruta_doc_foto']));
													$arrTime = explode("_", basename($resp_datos['ruta_doc_foto']));
													$timeSt = $arrTime[4];
													$typeFile = strtolower($arrIm[count($arrIm) - 1]);
													$rutaFoto = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_foto_per_" . $id_persona . "_" . $timeSt;
													$resulDoc4 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 4, $rutaFoto, $usuario_id);
													/* if (!($resulDoc4)) {
														                                                      throw new Exception('Error doc Foto no creado.');
													*/
												}
												if ($resp_datos['twin_metodo_ingreso'] == 4) {
													if ($resp_datos['ruta_doc_certificado'] != "") {
														$arrIm = explode(".", basename($resp_datos['ruta_doc_certificado']));
														$arrTime = explode("_", basename($resp_datos['ruta_doc_certificado']));
														$timeSt = $arrTime[4];
														$typeFile = strtolower($arrIm[count($arrIm) - 1]);
														$rutaCertificado = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_certificado_per_" . $id_persona . "_" . $timeSt;
														$resulDoc5 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 6, $rutaCertificado, $usuario_id);
														/* if (!($resulDoc5)) {
															                                                          throw new Exception('Error doc Certificado no creado.');
														*/
													}
													if ($resp_datos['ruta_doc_hojavida'] != "") {
														$arrIm = explode(".", basename($resp_datos['ruta_doc_hojavida']));
														$arrTime = explode("_", basename($resp_datos['ruta_doc_hojavida']));
														$timeSt = $arrTime[4];
														$typeFile = strtolower($arrIm[count($arrIm) - 1]);
														$rutaHojaVida = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_hojavida_per_" . $id_persona . "_" . $timeSt;
														$resulDoc6 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 7, $rutaHojaVida, $usuario_id);
														/* if (!($resulDoc6)) {
															                                                          throw new Exception('Error doc Hoja de Vida no creado.');
														*/
													}
												}
												if ($resp_datos['ruta_doc_aceptacion'] != "") {
													$arrIm = explode(".", basename($resp_datos['ruta_doc_aceptacion']));
													$arrTime = explode("_", basename($resp_datos['ruta_doc_aceptacion']));
													$timeSt = $arrTime[4];
													$typeFile = strtolower($arrIm[count($arrIm) - 1]);
													$rutaDocAceptacion = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_aceptacion_per_" . $id_persona . "_" . $timeSt;
													$resulDoc7 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 8, $rutaDocAceptacion, $usuario_id);
													/* if (!($resulDoc6)) {
														                                                      throw new Exception('Error doc Hoja de Vida no creado.');
													*/
												}
												if ($resp_datos['ruta_doc_pago'] != "") {
													$arrIm = explode(".", basename($resp_datos['ruta_doc_pago']));
													$arrTime = explode(" ", basename($resp_datos['ruta_doc_pago']));
													$timeSt = $arrTime[1];
													$typeFile = strtolower($arrIm[count($arrIm) - 1]);
													$fecha = date(Yii::$app->params["dateByDefault"]);
													$rutaDocPago = Yii::$app->params["documentFolder"] . "documento/" . $id_persona . "/pago_" . $id_persona . "-" . $fecha . " " . $timeSt;
													$archivo = basename($rutaDocPago);
												}
												//Obtener el precio de la solicitud.
												if ($beca == "1") {
													$precio = 0;
												} else {
													$resp_precio = $solins_model->ObtenerPrecio($resp_datos['twin_metodo_ingreso'], $resp_datos['uaca_id'], $resp_datos['mod_id'], $eaca_id);
													if ($resp_precio) {
														/* if ($resp_datos['uaca_id'] == 2) {
															                                                          $ite_id = 10;
														*/
														$ite_id = $resp_precio['ite_id'];
														//}
														$precio = $resp_precio['precio'];
													} else {
														$mensaje = 'No existe registrado ningún precio para la unidad, modalidad y método de ingreso seleccionada.';
													}
												}
												$mod_ordenpago = new OrdenPago();
												$val_descuento = 0;
												//Se verifica si seleccionó descuento.
												//descuento para grado online y posgrado no tiene descuento, caso contrario es 96 dol
												/* if ($resp_datos['uaca_id'] == 1) {
	                                                  if (($resp_datos['mod_id'] == 2) or ( $resp_datos['mod_id'] == 3) or ( $resp_datos['mod_id'] == 4)) {
	                                                  $val_descuento = 96;
	                                                  }
*/
												//Generar la orden de pago con valor correspondiente. Buscar precio para orden de pago.
												if ($precio == 0) {
													$estadopago = 'S';
												} else {
													$estadopago = 'P';
												}
												$val_total = $precio - $val_descuento;
												$resp_opago = $mod_ordenpago->insertarOrdenpago($sins_id, null, $val_total, 0, $val_total, $estadopago, $usuario_id);
												if ($resp_opago) {
													//insertar desglose del pago
													$fecha_ini = date(Yii::$app->params["dateByDefault"]);
													$resp_dpago = $mod_ordenpago->insertarDesglosepago($resp_opago, $ite_id, $val_total, 0, $val_total, $fecha_ini, null, $estadopago, $usuario_id);
													if ($resp_dpago) {
														//Grabar documento de registro de pago por depósito o transferencia.
														if (($dataReg["forma_pago"] == 3) or ($dataReg["forma_pago"] == 4)) {
															//(($resp_datos['twin_tipo_pago'] == 3) or ( $resp_datos['twin_tipo_pago'] == 4)) {
															if ($dataReg["forma_pago"] == 3) { //depósito
																$fpag_id = 5; //depósito
															} else {
																$fpag_id = 4; //transferencia
															}
															$fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
															\app\models\Utilities::putMessageLogFile('ruta222: ' . $ruta_doc_pago["ruta_doc_pago"]);
															\app\models\Utilities::putMessageLogFile('ruta333: ' . $archivo);
															$creadetalle = $mod_ordenpago->insertarCargaprepago($resp_opago, $fpag_id, $val_total, $archivo, 'PE', '', $dataReg["observacion"], $dataReg["num_transaccion"], $dataReg["fecha_transaccion"], $fecha_registro);
															if ($creadetalle) {
																//\app\models\Utilities::putMessageLogFile('despues de insertar Cargar pago');
																$detalle = 'S';
															}
														} else {
															$detalle = 'S';
														}
														//Grabar datos de factura
														if ($detalle == 'S') {
															$resdatosFact = $solins_model->crearDatosFacturaSolicitud($sins_id, $dataReg["nombres_fact"], $dataReg["apellidos_fact"], $dataReg["tipo_dni_fac"], $dataReg["dni"], $dataReg["direccion_fact"], $dataReg["telefono_fac"], $dataReg["correo"]);
															if ($resdatosFact) {
																$exito = 1;
															}
														}
													}
												}
											}
										} else {
											$error_message .= Yii::t("formulario", "The enterprise interested hasn't been saved");
											$error++;
										}
									} else {
										$error_message .= Yii::t("formulario", "The interested person hasn't been saved");
										$error++;
									}
								} else {
									$error_message .= Yii::t("formulario", "The rol user have not been saved");
									$error++;
								}
							} else {
								$error_message .= Yii::t("formulario", "The user have not been saved");
								$error++;
							}
						} else {
							$error_message .= Yii::t("formulario", "The enterprise interested hasn't been saved");
							$error++;
						}
					} else {
						$error++;
						$error_message .= Yii::t("formulario", "The person have not been saved");
					}
				} else {
					$error_message .= Yii::t("formulario", "Update DNI to generate interested");
					$error++;
				}
			} else {
				$error_message .= Yii::t("formulario", "No existen datos para registrar.");
				$error++;
			}
			if ($exito == 1) {
				//$transaction->commit();
				//$transaction1->commit();
				$transaction2->commit();
				//Envío de correo.
				$tituloMensaje = Yii::t("interesado", "UTEG - Registration Online");
				$asunto = Yii::t("interesado", "UTEG - Registration Online");
				$link = "https://www.asgard.uteg.edu.ec/asgard";
				$body = Utilities::getMailMessage("credentials", array("[[usuario]]" => $resp_datos['twin_nombre'] . " " . $resp_datos['twin_apellido'], "[[username]]" => $resp_datos['twin_correo'], "[[clave]]" => $resp_datos['twin_numero'], "[[link]]" => $link), Yii::$app->language);
				Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$resp_datos['twin_correo'] => $resp_datos['twin_apellido'] . " " . $resp_datos['twin_nombre']], $asunto, $body);
				Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["admisiones"] => "Jefe"], $asunto, $body);
				Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $body);
				//\app\models\Utilities::putMessageLogFile('después del tercer sendMail');
				$message = array(
					"wtmessage" => Yii::t("formulario", "The information have been saved and the information has been sent to your email"),
					"title" => Yii::t('jslang', 'Success'),
				);
				//Modifificaion para Mover Imagenes de temp a Persona
				if ($subidaDocumentos == 1) {
					self::movePersonFiles($twinIds, $id_persona);
				}
				if (($dataReg["forma_pago"] == 3) or ($dataReg["forma_pago"] == 4)) {
					self::movePersonFilesPago($twinIds, $id_persona);
				}
				//return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
				$arroout["status"] = TRUE;
				$arroout["error"] = null;
				$arroout["message"] = $message;
				$arroout["data"] = $resp_datos; //$rawData;
				$arroout["dataext"] = $sins_id;
				return $arroout;
			} else {
				//$transaction->rollback();
				//$transaction1->rollback();
				$transaction2->rollback();
				$message = array(
					"wtmessage" => Yii::t("formulario", "Mensaje1: " . $mensaje), //$error_message
					"title" => Yii::t('jslang', 'Bad Request'),
				);
				$arroout["status"] = FALSE;
				$arroout["error"] = null;
				$arroout["message"] = $message;
				$arroout["data"] = null;
				$arroout["dataext"] = null;
				return $arroout;
				//return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
			}
		} catch (Exception $ex) {
			//$transaction->rollback();
			//$transaction1->rollback();
			$transaction2->rollback();
			$message = array(
				"wtmessage" => Yii::t("formulario", "Mensaje2: " . $mensaje), //$error_message
				"title" => Yii::t('jslang', 'Bad Request'),
			);
			$arroout["status"] = FALSE;
			$arroout["error"] = $ex->getCode();
			$arroout["message"] = $ex->getMessage();
			$arroout["data"] = null;
			$arroout["dataext"] = null;
			return $arroout;
			//return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
		}
		return;
	}

	public function insertaFinInstituto($twinIds, $dataReg) {
		$con = \Yii::$app->db_asgard;
		$con1 = \Yii::$app->db_captacion;
		$con2 = \Yii::$app->db_facturacion;
		$usuario_ingreso = 1; //@Yii::$app->session->get("PB_iduser");
		$transaction = $con->beginTransaction();
		$transaction1 = $con1->beginTransaction();
		$transaction2 = $con2->beginTransaction();
		$id_persona = 0;
		try {
			//Se consulta la información grabada en la tabla temporal.
			$mod_inscripcion = new InscripcionAdmision();
			$resp_datos = $mod_inscripcion->consultarDatosInscripcionInstituto($twinIds);
			$curso = $resp_datos["carrera"];
			// He colocado al inicio la informacion para que cargue al principio
			\app\models\Utilities::putMessageLogFile('twin_numero: ' . $resp_datos['twin_numero']);
			if ($resp_datos) {
				if (isset($resp_datos['twin_numero']) && strlen($resp_datos['twin_numero']) > 0) {
					$identificacion = $resp_datos['twin_numero'];
				} else {
					$identificacion = $resp_datos['twin_numero'];
				}
				if (isset($identificacion) && strlen($identificacion) > 0) {
					$id_persona = 0;
					$mod_persona = new Persona();
					$keys_per = [
						'per_pri_nombre', 'per_seg_nombre', 'per_pri_apellido', 'per_seg_apellido',
						'per_cedula', 'etn_id', 'eciv_id', 'per_genero', 'pai_id_nacimiento',
						'pro_id_nacimiento', 'can_id_nacimiento', 'per_fecha_nacimiento',
						'per_celular', 'per_correo', 'tsan_id', 'per_domicilio_sector',
						'per_domicilio_cpri', 'per_domicilio_csec', 'per_domicilio_num',
						'per_domicilio_ref', 'per_domicilio_telefono', 'per_trabajo_nombre', 'pai_id_domicilio',
						'pro_id_domicilio', 'can_id_domicilio', 'per_nac_ecuatoriano',
						'per_nacionalidad', 'per_foto', 'per_usuario_ingresa', 'per_estado', 'per_estado_logico',
					];
					$parametros_per = [
						ucwords(strtolower($resp_datos['twin_nombre'])), null,
						ucwords(strtolower($resp_datos['twin_apellido'])), null,
						$resp_datos['twin_numero'], null, null, null, null, null,
						null, null, $resp_datos['twin_celular'], strtolower($resp_datos['twin_correo']),
						null, null, null, null,
						null, null, null, $resp_datos['twin_empresa'],
						null, null, null,
						null, null, null, $usuario_ingreso, 1, 1,
					];
					$id_personaexite = $mod_persona->consultarIdPersonaICP($resp_datos['twin_numero'], $resp_datos['twin_numero'], $resp_datos['twin_correo']);
					\app\models\Utilities::putMessageLogFile('id_persona xx: ' . $id_persona['per_id']);
					\app\models\Utilities::putMessageLogFile('id_persona yy: ' . $id_persona);
					if (empty($id_personaexite)) {
						$id_persona = $mod_persona->insertarPersona($con, $parametros_per, $keys_per, 'persona');
					}
					if ($id_persona > 0) {
						\app\models\Utilities::putMessageLogFile('se crea persona.');
						//Se registran otros datos de persona
						$mod_PersonaOtro = new PersonaOtrosDatos();
						\app\models\Utilities::putMessageLogFile('id persona:' . $id_persona);
						\app\models\Utilities::putMessageLogFile('nivel:.' . $resp_datos['nivel']);
						\app\models\Utilities::putMessageLogFile('red:.' . $resp_datos['red_social']);
						\app\models\Utilities::putMessageLogFile('encontramos:.' . $resp_datos['twin_encontramos']);
						\app\models\Utilities::putMessageLogFile('usuario:.' . $usuario_ingreso);
						$datos = array(
							'per_id' => $id_persona,
							'nins_id' => $resp_datos['nivel'],
							'bseg_id' => $resp_datos['red_social'],
							'poda_contacto_red_social' => $resp_datos['twin_encontramos'],
							'usuario' => $usuario_ingreso,
						);

						\app\models\Utilities::putMessageLogFile('id data:' . print_r($datos, true));
						$existe = $mod_PersonaOtro->consultar($id_persona);
						/*if (empty($existe)) {
						$respPerOtros = $mod_PersonaOtro->insertar($id_persona,$resp_datos['nivel'],$resp_datos['red_social'],$resp_datos['twin_encontramos'],$usuario_ingreso);
						*/
						if ($id_persona > 0) {
							//self::movePersonFiles($twinIds,$id_persona);
							$concap = \Yii::$app->db_captacion;
							$mod_emp_persona = new EmpresaPersona();
							if (!empty($dataReg["empresa"])) {
								$emp_id = $dataReg["empresa"];
							} else {
								$emp_id = 1;
							}
							$keys = ['emp_id', 'per_id', 'eper_estado', 'eper_estado_logico'];
							$parametros = [$emp_id, $id_persona, 1, 1];
							$emp_per_id = $mod_emp_persona->consultarIdEmpresaPersona($id_persona, $emp_id);
							if ($emp_per_id == 0) {
								$emp_per_id = $mod_emp_persona->insertarEmpresaPersona($con, $parametros, $keys, 'empresa_persona');
							}
							if ($emp_per_id > 0) {
								$usuario = new Usuario();
								$usuario_id = $usuario->consultarIdUsuario($id_persona, $resp_datos['twin_correo']);
								if ($usuario_id == 0) {
									$security = new Security();
									$hash = $security->generateRandomString();
									$passencrypt = base64_encode($security->encryptByPassword($hash, $resp_datos['twin_numero']));
									$keys = ['per_id', 'usu_user', 'usu_sha', 'usu_password', 'usu_estado', 'usu_estado_logico'];
									$parametros = [$id_persona, $resp_datos['twin_correo'], $hash, $passencrypt, 1, 1];
									$usuario_id = $usuario->crearUsuarioTemporal($con, $parametros, $keys, 'usuario');
								}
								if ($usuario_id > 0) {
									$mod_us_gr_ep = new UsuaGrolEper();
									$grol_id = 30;
									$keys = ['eper_id', 'usu_id', 'grol_id', 'ugep_estado', 'ugep_estado_logico'];
									$parametros = [$emp_per_id, $usuario_id, $grol_id, 1, 1];
									$us_gr_ep_id = $mod_us_gr_ep->consultarIdUsuaGrolEper($emp_per_id, $usuario_id, $grol_id);
									if ($us_gr_ep_id == 0) {
										$us_gr_ep_id = $mod_us_gr_ep->insertarUsuaGrolEper($con, $parametros, $keys, 'usua_grol_eper');
									}

									if ($us_gr_ep_id > 0) {
										$mod_interesado = new Interesado(); // se guarda con estado_interesado 1
										$interesado_id = $mod_interesado->consultaInteresadoById($id_persona);
										$keys = ['per_id', 'int_estado_interesado', 'int_usuario_ingreso', 'int_estado', 'int_estado_logico'];
										$parametros = [$id_persona, 1, $usuario_id, 1, 1];
										if ($interesado_id == 0) {
											$interesado_id = $mod_interesado->insertarInteresado($concap, $parametros, $keys, 'interesado');
										}
										if ($interesado_id > 0) {
											$mod_inte_emp = new InteresadoEmpresa(); // se guarda con estado_interesado 1
											$iemp_id = $mod_inte_emp->consultaInteresadoEmpresaById($interesado_id, $emp_id);
											if ($iemp_id == 0) {
												$iemp_id = $mod_inte_emp->crearInteresadoEmpresa($interesado_id, $emp_id, $usuario_id);
											}
											if ($iemp_id > 0) {
												$eaca_id = NULL;
												$mest_id = NULL;
												if ($emp_id == 1) {
													//Uteg
													$eaca_id = $resp_datos['car_id'];
												} elseif ($emp_id == 2 || $emp_id == 3) {
													$mest_id = $resp_datos['car_id'];
												}
												$num_secuencia = Secuencias::nuevaSecuencia($con, $emp_id, 1, 1, 'SOL');
												$sins_fechasol = date(Yii::$app->params["dateTimeByDefault"]);
												$rsin_id = 1; //Solicitud pendiente
												$solins_model = new SolicitudInscripcion();
												//$mensaje = 'intId: ' . $interesado_id . '/uaca: ' . $pgest['unidad_academica'] . '/modalidad: ' . $pgest['modalidad'] . '/ming: ' . $pgest['ming_id'] . '/eaca: ' . $eaca_id . '/mest: ' . $mest_id . '/empresa: ' . $emp_id . '/secuencia: ' . $num_secuencia . '/rsin_id: ' . $rsin_id . '/sins_fechasol: ' . $sins_fechasol . '/usuario_id: ' . $usuario_id;
												$ming = null;
												if ($resp_datos['uaca_id'] == 1) {
													$ming = null;
												} else {
													if ($resp_datos['twin_metodo_ingreso'] == 0) {
														$ming = null;
													} else {
														$ming = $resp_datos['twin_metodo_ingreso'];
													}
												}
												if ($resp_datos['cemp_id'] == 0) {
													$cemp = null;
												} else {
													$cemp = $resp_datos['cemp_id'];
												}
												$sins_id = $solins_model->insertarSolicitud($interesado_id, $resp_datos['uaca_id'], $resp_datos['mod_id'], $resp_datos['twin_metodo_ingreso'], $eaca_id, $mest_id, $emp_id, $num_secuencia, $rsin_id, $sins_fechasol, $usuario_id, $cemp);
												//grabar los documentos
												if ($sins_id) {
													$subidaDocumentos = 0;
													if ($resp_datos['ruta_doc_pago'] != "") {
														$arrIm = explode(".", basename($resp_datos['ruta_doc_pago']));
														$arrTime = explode(" ", basename($resp_datos['ruta_doc_pago']));
														$timeSt = $arrTime[1];
														$typeFile = strtolower($arrIm[count($arrIm) - 1]);
														$fecha = date(Yii::$app->params["dateByDefault"]);
														$rutaDocPago = Yii::$app->params["documentFolder"] . "documento/" . $id_persona . "/pago_" . $id_persona . "-" . $fecha . "." . $typeFile;
														$archivo = basename($rutaDocPago);
													}
													//Obtener el precio de la solicitud.
													$ite_id = $resp_datos['ite_id'];
													$precio = $resp_datos["precio"];
													$fpag_id = $dataReg["forma_pago"];

													$mod_ordenpago = new OrdenPago();
													$val_descuento = 0;
													//Generar la orden de pago con valor y estado según la forma de pago correspondiente.
													if ($fpag_id == 1) {
														$estadopago = 'S';
													} else {
														$estadopago = 'P';
													}
													$val_total = $precio - $val_descuento;
													if (($fpag_id == 5) or ($fpag_id == 4)) {
														$resp_opago = $mod_ordenpago->insertarOrdenpago($sins_id, null, $val_total, 0, $val_total, $estadopago, $usuario_id);
													} else if (($fpag_id == 1)) {
														$fecha_pago = date(Yii::$app->params["dateByDefault"]);
														$resp_opago = $mod_ordenpago->insertarOrdenpago($sins_id, null, $val_total, 0, $val_total, $estadopago, $usuario_id, $fecha_pago, $val_total);
													}
													if ($resp_opago) {
														//insertar desglose del pago
														$fecha_ini = date(Yii::$app->params["dateByDefault"]);
														\app\models\Utilities::putMessageLogFile('OrdenPago:');
														$resp_dpago = $mod_ordenpago->insertarDesglosepago($resp_opago, $ite_id, $val_total, 0, $val_total, $fecha_ini, null, $estadopago, $usuario_id);
														if ($resp_dpago) {
															//Grabar documento de registro de pago por depósito o transferencia.
															if (($fpag_id == 5) or ($fpag_id == 4)) {
																$fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
																\app\models\Utilities::putMessageLogFile('$rutaDocPago: '. $rutaDocPago);
																\app\models\Utilities::putMessageLogFile('ruta444: ' . $ruta_doc_pago["ruta_doc_pago"]);
															\app\models\Utilities::putMessageLogFile('ruta555: ' . $archivo);
																$creadetalle = $mod_ordenpago->insertarCargaprepago($resp_opago, $fpag_id, $val_total, $archivo, 'PE', NULL, $dataReg["observacion"], $dataReg["num_transaccion"], $dataReg["fecha_transaccion"], $fecha_registro);
																if ($creadetalle) {
																	$detalle = 'S';
																}
															} else if (($fpag_id == 1)) {
																// se crea en registro_pago con estado de aprobado porque el pago es tarjeta.
																$rpag_imagen = null;
																\app\models\Utilities::putMessageLogFile('infocargaprepago:');
																\app\models\Utilities::putMessageLogFile('resp_opago:' . $resp_opago);
																\app\models\Utilities::putMessageLogFile('fpag_id:' . $fpag_id);
																\app\models\Utilities::putMessageLogFile('val_total:' . $val_total);
																\app\models\Utilities::putMessageLogFile('archivo:' . $archivo);
																\app\models\Utilities::putMessageLogFile('observacion:' . $dataReg["observacion"]);
																\app\models\Utilities::putMessageLogFile('num_transaccion:' . $dataReg["num_transaccion"]);
																\app\models\Utilities::putMessageLogFile('fecha_transaccion:' . $dataReg["fecha_transaccion"]);
																\app\models\Utilities::putMessageLogFile('fecha_registro:' . $fecha_registro);
																$fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
																\app\models\Utilities::putMessageLogFile('ruta666: ' . $ruta_doc_pago["ruta_doc_pago"]);
															\app\models\Utilities::putMessageLogFile('ruta777: ' . $archivo);
																$creadetalle = $mod_ordenpago->insertarCargaprepago($resp_opago, $fpag_id, $val_total, $rpag_imagen, 'RE', 'AP', $rpag_imagen, 0, $fecha_registro, $fecha_registro);
																if ($creadetalle) {

																	$resp_rpagos = $mod_ordenpago->insertarRegistropago($resp_dpago, $fpag_id, $val_total, $fecha_ini, $rpag_imagen, 0, $fecha_ini, 0, "AP", $usuario_id, "RE");
																}
																if ($resp_rpagos) {
																	$detalle = 'S';
																	$mod_Estudiante = new Estudiante();
																	$mod_Modestuni = new ModuloEstudio();
																	if ($resp_rpagos) {
																		\app\models\Utilities::putMessageLogFile('persona:');
																		// Guardar en tabla esdudiante
																		$fecha = date(Yii::$app->params["dateTimeByDefault"]);
																		// Consultar el estudiante si no ha sido creado
																		\app\models\Utilities::putMessageLogFile('id persona:' . $id_persona);
																		$per_id = $id_persona;
																		$resp_estudianteid = $mod_Estudiante->getEstudiantexperid($per_id);

																		if ($resp_estudianteid["est_id"] == "") {
																			\app\models\Utilities::putMessageLogFile('estudiante:');
																			\app\models\Utilities::putMessageLogFile('usuario_ingreso:' . $usuario_ingreso);
																			$resp_estudiante = $mod_Estudiante->insertarEstudiante($per_id, null, $usuario_ingreso, null, $fecha, null);
																		} else {
																			$resp_estudiante = $resp_estudianteid["est_id"];
																		}
																		\app\models\Utilities::putMessageLogFile('uaca_id:' . $resp_datos["uaca_id"]);
																		\app\models\Utilities::putMessageLogFile('mod_id:' . $resp_datos["mod_id"]);
																		\app\models\Utilities::putMessageLogFile('car_id:' . $resp_datos["car_id"]);
																		$est_cp = Estudiante::findOne(['per_id' => $per_id, 'est_estado' => 1, 'est_estado_logico' => 1]);
																		\app\models\Utilities::putMessageLogFile('est_id:' . $est_cp["est_id"]);
																		if ($est_cp['est_id']) {
																			// Obtener el meun_id con lo con el uaca_id, mod_id y eaca_id, el est_id
																			\app\models\Utilities::putMessageLogFile('Entro ecpr');
																			$resp_mestuni = $mod_Modestuni->consultarModalidadestudiouni($resp_datos["uaca_id"], $resp_datos["mod_id"], $resp_datos["car_id"]);
																			if ($resp_mestuni) {
																				//consultar si no esta guardado en estudiante_carrera_programa
																				\app\models\Utilities::putMessageLogFile('estudiante_carrera:');
																				$resp_estucarrera = $mod_Estudiante->consultarEstcarreraprogrma($resp_estudiante);
																				if ($resp_estucarrera["ecpr_id"] == "") {
																					// Guardar en tabla estudiante_carrera_programa
																					$resp_estudcarreprog = $mod_Estudiante->insertarEstcarreraprog($est_cp['est_id'], $resp_mestuni["meun_id"], $fecha, $usuario_ingreso, $fecha);
																				}
																			}
																		}
																	}

																}

															}
															//Grabar datos de factura
															if ($detalle == 'S') {
																$resdatosFact = $solins_model->crearDatosFacturaSolicitud($sins_id, $dataReg["nombres_fact"], $dataReg["apellidos_fact"], $dataReg["tipo_dni_fac"], $dataReg["dni"], $dataReg["direccion_fact"], $dataReg["telefono_fac"], $dataReg["correo"]);
																if ($resdatosFact) {
																	$exito = 1;
																}
															}
														}
													}
												}
											} else {
												$error_message .= Yii::t("formulario", "The enterprise interested hasn't been saved");
												$error++;
											}
										} else {
											$error_message .= Yii::t("formulario", "The interested person hasn't been saved");
											$error++;
										}
									} else {
										$error_message .= Yii::t("formulario", "The rol user have not been saved");
										$error++;
									}
								} else {
									$error_message .= Yii::t("formulario", "The user have not been saved");
									$error++;
								}
							} else {
								$error_message .= Yii::t("formulario", "The enterprise interested hasn't been saved");
								$error++;
							}
						} else {
							$error++;
							$error_message .= Yii::t("formulario", "Other personal data has not been registered");
						}
					} else { //AQUI
						$error++;
						$error_message .= Yii::t("formulario", "Ya existe una persona con la misma cedula o correo");
					}
				} else {
					$error_message .= Yii::t("formulario", "Update DNI to generate interested");
					$error++;
				}
			} else {
				$error_message .= Yii::t("formulario", "No existen datos para registrar.");
				$error++;
			}
			if ($exito == 1) {
				\app\models\Utilities::putMessageLogFile('inscripciones exito ' );
				//$transaction->commit();
				//$transaction1->commit();
				$transaction2->commit();
				//Envío de correo.
				$tituloMensaje = Yii::t("interesado", "Inscripción - ICP - UTEG");
				$asunto = Yii::t("interesado", "Inscripción - ICP - UTEG");
				$link = "https://www.asgard.uteg.edu.ec/asgard";
				$body = Utilities::getMailMessage("register_icp", array("[[nombres_completos]]" => $resp_datos['twin_nombre'] . " " . $resp_datos['twin_apellido'], "[[curso]]" => $curso), Yii::$app->language);
				$body1 = Utilities::getMailMessage("register_icp_admisiones", array("[[nombres_completos]]" => $resp_datos['twin_nombre'] . " " . $resp_datos['twin_apellido'], "[[curso]]" => $curso), Yii::$app->language);
				Utilities::sendEmailicp($tituloMensaje, Yii::$app->params["adminEmail"], [$resp_datos['twin_correo'] => $resp_datos['twin_apellido'] . " " . $resp_datos['twin_nombre']], $asunto, $body);
				Utilities::sendEmailicp($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["admisiones"] => "Jefe"], $asunto, $body1);
				Utilities::sendEmailicp($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["secretariaicp"] => "ICP"], $asunto, $body1);
				//\app\models\Utilities::putMessageLogFile('después del tercer sendMail');
				$message = array(
					"wtmessage" => Yii::t("formulario", "The information have been saved and the information has been sent to your email"),
					"title" => Yii::t('jslang', 'Success'),
				);
				//Modifificaion para Mover Imagenes de temp a Persona
				if ($subidaDocumentos == 1) {
					self::movePersonFiles($twinIds, $id_persona);
				}
				if (($dataReg["forma_pago"] == 5) or ($dataReg["forma_pago"] == 4)) {
					self::movePersonFilesPago($twinIds, $id_persona);
				}
				//return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
				$arroout["status"] = TRUE;
				$arroout["error"] = null;
				$arroout["message"] = $message;
				$arroout["data"] = $resp_datos; //$rawData;
				$arroout["dataext"] = $sins_id;
				return $arroout;
			} else {
				//$transaction->rollback();
				//$transaction1->rollback();
				$transaction2->rollback();
				$message = array(
					"wtmessage" => Yii::t("formulario", "Mensaje1: " . $mensaje), //$error_message
					"title" => Yii::t('jslang', 'Bad Request'),
				);
				$arroout["status"] = FALSE;
				$arroout["error"] = null;
				$arroout["message"] = $message;
				$arroout["data"] = null;
				$arroout["dataext"] = null;
				return $arroout;
				//return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
			}
		} catch (Exception $ex) {
			//$transaction->rollback();
			//$transaction1->rollback();
			$transaction2->rollback();
			$message = array(
				"wtmessage" => Yii::t("formulario", "Mensaje2: " . $mensaje), //$error_message
				"title" => Yii::t('jslang', 'Bad Request'),
			);
			$arroout["status"] = FALSE;
			$arroout["error"] = $ex->getCode();
			$arroout["message"] = $ex->getMessage();
			$arroout["data"] = null;
			$arroout["dataext"] = null;
			return $arroout;
			//return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
		}
		return;
	}

	public static function movePersonFiles($temp_id, $per_id) {
		$folder = Yii::$app->basePath . "/" . Yii::$app->params["documentFolder"] . "solicitudadmision/$temp_id/";
		$destinations = Yii::$app->basePath . "/" . Yii::$app->params["documentFolder"] . "solicitudinscripcion/$per_id/";
		if (Utilities::verificarDirectorio($destinations)) {
			$files = scandir($folder);
			foreach ($files as $file) {
				if (trim($file) != "." && trim($file) != "..") {
					$arrExt = explode(".", $file);
					$type = $arrExt[count($arrExt) - 1];
					$newFile = str_replace("_" . $temp_id . "_", "_" . $per_id . "_", $file);
					if (file_exists($folder . $file)) {
						if (!rename($folder . $file, $destinations . $newFile)) {
							return false;
						}
					}
				}
			}
			rmdir($folder);
		} else {
			return false;
		}

		return true;
	}

	public static function movePersonFilesPago($temp_id, $per_id) {
		$folder = Yii::$app->basePath . "/" . Yii::$app->params["documentFolder"] . "documentoadmision/$temp_id/";
		$destinations = Yii::$app->basePath . "/" . Yii::$app->params["documentFolder"] . "documento/$per_id/";
		if (Utilities::verificarDirectorio($destinations)) {
			$files = scandir($folder);
			foreach ($files as $file) {
				if (trim($file) != "." && trim($file) != "..") {
					$arrExt = explode(".", $file);
					$type = $arrExt[count($arrExt) - 1];
					$newFile = str_replace("_" . $temp_id . "-", "_" . $per_id . "-", $file);
					if (file_exists($folder . $file)) {
						if (!rename($folder . $file, $destinations . $newFile)) {
							return false;
						}
					}
				}
			}
			rmdir($folder);
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Function consultarDatosInscripcionContinua
	 * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
	 * @param
	 * @return  $resultData (Obtiene los datos de inscripción y el precio de la solicitud.)
	 */
	public function consultarDatosInscripcionContinua($twin_id) {
		$con = \Yii::$app->db_captacion;
		$con2 = \Yii::$app->db_facturacion;
		$con1 = \Yii::$app->db_academico;
		$estado = 1;
		$estado_precio = 'A';

		$sql = "
                SELECT  ua.uaca_nombre unidad,
                        m.mod_nombre modalidad,
                        mest.mest_nombre carrera,
                        mest.mest_id as id_carrera,
                        ip.ipre_precio as precio,
                        twin_nombre,
                        twin_apellido,
                        twin_numero,
                        twin_correo,
                        twin_pais,
                        twin_celular,
                        twi.uaca_id,
                        twi.mod_id,
                        twi.car_id,
                        twin_metodo_ingreso,
                        conuteg_id,
                        ruta_doc_titulo,
                        ruta_doc_dni,
                        ruta_doc_certvota,
                        ruta_doc_foto,
                        ruta_doc_certificado,
                        ruta_doc_hojavida,
                        twin_dni,
                        ruta_doc_aceptacion,
                        twi.cemp_id,
                        twin_tipo_pago,
                        ruta_doc_pago
                FROM  " . $con->dbname . ".temporal_wizard_inscripcion twi inner join " . $con1->dbname . ".unidad_academica ua on ua.uaca_id = twi.uaca_id
                     inner join " . $con1->dbname . ".modalidad m on m.mod_id = twi.mod_id
                     inner join " . $con1->dbname . ".modulo_estudio mest on mest.mest_id = twi.car_id
                     left join " . $con2->dbname . ".item_metodo_unidad imi on (imi.uaca_id = twi.uaca_id and imi.mod_id = twi.mod_id and imi.mest_id = twi.car_id)
                     left join  " . $con2->dbname . ".item_precio ip on ip.ite_id = imi.ite_id
                     left join  " . $con2->dbname . ".descuento_item as ditem on ditem.ite_id=imi.ite_id
                     left join  " . $con2->dbname . ".detalle_descuento_item as ddit on ddit.dite_id=ditem.dite_id
                WHERE twi.twin_id = :twin_id and
                     ip.ipre_estado_precio = 'A' AND
                     ua.uaca_estado = :estado AND
                     ua.uaca_estado_logico = :estado AND
                     m.mod_estado = :estado AND
                     m.mod_estado_logico = :estado AND
                     mest.mest_estado = :estado AND
                     mest.mest_estado_logico = :estado AND
                     imi.imni_estado = :estado AND
                     imi.imni_estado_logico = :estado AND
                     ip.ipre_estado = :estado AND
                     ip.ipre_estado_logico = '1'";
		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":twin_id", $twin_id, \PDO::PARAM_INT);
		$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
		$resultData = $comando->queryOne();
		return $resultData;
	}

	/**
	 * Function consultarDatosInscripcion
	 * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
	 * @param
	 * @return  $resultData (Obtiene los datos de inscripción y el precio de la solicitud.)
	 */
	public function consultarDatosInscripcionInstituto($twin_id) {
		$con = \Yii::$app->db_captacion;
		$con2 = \Yii::$app->db_facturacion;
		$con1 = \Yii::$app->db_academico;
		$estado = 1;
		$estado_precio = 'A';

		$sql = "
                SELECT  ua.uaca_nombre unidad,
                        m.mod_nombre modalidad,
                        ea.eaca_nombre carrera,
                        ea.eaca_id as id_carrera,
                        -- mi.ming_nombre metodo,
                        i.ite_id,
                        p.ipre_precio as precio,
                        twin_nombre,
                        twin_apellido,
                        twin_numero,
                        twin_empresa,
                        twin_correo,
                        twin_pais,
                        twin_celular,
                        twi.uaca_id,
                        twi.mod_id,
                        twi.car_id,
                        twin_metodo_ingreso,
                        conuteg_id,
                        twin_dni,
                        twi.cemp_id,
                        twin_tipo_pago,
                        ruta_doc_pago,
                        twin_nivel_instruccion nivel,
                        twin_redes_sociales red_social,
                        twin_encontramos
                FROM " . $con->dbname . ".temporal_wizard_inscripcion twi inner join db_academico.unidad_academica ua on ua.uaca_id = twi.uaca_id
                     inner join " . $con1->dbname . ".modalidad m on m.mod_id = twi.mod_id
                     inner join " . $con1->dbname . ".estudio_academico ea on ea.eaca_id = twi.car_id
                     inner join " . $con2->dbname . ".item i on i.ite_id = twi.twin_item
                     inner join " . $con2->dbname . ".item_precio p on p.ite_id = i.ite_id
                WHERE twi.twin_id = :twin_id AND
                     p.ipre_estado_precio = :estado_precio AND
                     ua.uaca_estado = :estado AND
                     ua.uaca_estado_logico = :estado AND
                     m.mod_estado = :estado AND
                     m.mod_estado_logico = :estado AND
                     ea.eaca_estado = :estado AND
                     ea.eaca_estado_logico = :estado AND
                     i.ite_estado = :estado AND
                     i.ite_estado_logico = :estado AND
                     p.ipre_estado = :estado AND
                     p.ipre_estado_logico = :estado";
		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":twin_id", $twin_id, \PDO::PARAM_INT);
		$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
		$resultData = $comando->queryOne();
		return $resultData;
	}

}
