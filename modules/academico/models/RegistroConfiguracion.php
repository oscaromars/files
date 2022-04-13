<?php

namespace app\modules\academico\models;

use Yii;
use yii\base\Exception;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "registro_configuracion".
 *
 * @property int $rco_id
 * @property int $pla_id
 * @property string $rco_fecha_inicio
 * @property string $rco_fecha_fin
 * @property string $rco_fecha_ini_aplicacion
 * @property string $rco_fecha_fin_aplicacion
 * @property string $rco_fecha_ini_registro
 * @property string $rco_fecha_fin_registro
 * @property string $rco_fecha_ini_periodoextra
 * @property string $rco_fecha_fin_periodoextra
 * @property string $rco_fecha_ini_clases
 * @property string $rco_fecha_fin_clases
 * @property string $rco_fecha_ini_examenes
 * @property string $rco_fecha_fin_examenes
 * @property int $rco_num_bloques
 * @property string $rco_estado
 * @property string $rco_fecha_creacion
 * @property int $rco_usuario_modifica
 * @property string $rco_fecha_modificacion
 * @property string $rco_estado_logico
 *
 * @property Planificacion $pla
 */
class RegistroConfiguracion extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'registro_configuracion';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb() {
		return Yii::$app->get('db_academico');
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['pla_id', 'rco_num_bloques', 'rco_fecha_fin', 'rco_fecha_inicio', 'rco_estado', 'rco_estado_logico'], 'required'],
			[['pla_id', 'rco_num_bloques', 'rco_usuario_modifica'], 'integer'],
			[['rco_fecha_inicio', 'rco_fecha_fin', 'rco_fecha_creacion', 'rco_fecha_modificacion'], 'safe'],
			[['rco_estado', 'rco_estado_logico'], 'string', 'max' => 1],
			[['pla_id'], 'exist', 'skipOnError' => true, 'targetClass' => Planificacion::className(), 'targetAttribute' => ['pla_id' => 'pla_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'rco_id' => 'Rco ID',
			'pla_id' => 'Pla ID',
			'rco_fecha_inicio' => 'Rco Fecha Inicio',
			'rco_fecha_fin' => 'Rco Fecha Fin',
			'rco_fecha_ini_aplicacion' => 'Rco Fecha Inicio Aplicacion',
			'rco_fecha_fin_aplicacion' => 'Rco Fecha Fin aplicacion',
			'rco_fecha_ini_registro' => 'Rco Fecha Inicio registro',
			'rco_fecha_fin_registro' => 'Rco Fecha Fin Registro',
			'rco_fecha_ini_periodoextra' => 'Rco Fecha Inicio Periodoextra',
			'rco_fecha_fin_periodoextra' => 'Rco Fecha Fin Periodoextra',
			'rco_fecha_ini_clases' => 'Rco Fecha Inicio Clases',
			'rco_fecha_fin_clases' => 'Rco Fecha Fin Clases',
			'rco_fecha_ini_examenes' => 'Rco Fecha Inicio Examenes',
			'rco_fecha_fin_examenes' => 'Rco Fecha Fin Examenes',
			'rco_num_bloques' => 'Num Bloques',
			'rco_estado' => 'Ron Estado',
			'rco_fecha_creacion' => 'Ron Fecha Creacion',
			'rco_usuario_modifica' => 'Ron Usuario Modifica',
			'rco_fecha_modificacion' => 'Ron Fecha Modificacion',
			'rco_estado_logico' => 'Ron Estado Logico',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPla() {
		return $this->hasOne(Planificacion::className(), ['pla_id' => 'pla_id']);
	}

	public function getRegistroConfList($periodo_academico, $mod_id, $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		$where = "";

		if ($mod_id != 0) {
			$where .= "m.mod_id =:mod_id AND ";
		}

		if (isset($periodo_academico) && $periodo_academico != "") {
			$where .= "p.pla_periodo_academico =:periodo AND ";
		}

		$sql = "
            SELECT
                r.rco_id AS id,
                p.pla_periodo_academico AS PeriodoAcademico,
                m.mod_nombre AS Modalidad,
                r.rco_fecha_inicio AS Inicio,
                r.rco_fecha_fin AS Fin,
                r.rco_num_bloques AS Bloque
            FROM
                " . $con->dbname . ".registro_configuracion as r
                INNER JOIN " . $con->dbname . ".planificacion as p ON r.pla_id = p.pla_id
                INNER JOIN " . $con->dbname . ".modalidad as m ON m.mod_id = p.mod_id
            WHERE
                $where
                r.rco_estado =:estado AND r.rco_estado_logico =:estado AND
                p.pla_estado =:estado AND p.pla_estado_logico =:estado AND
                m.mod_estado =:estado AND m.mod_estado_logico =:estado
            ORDER BY
                r.rco_fecha_inicio DESC;
        ";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		if ($mod_id != 0) {
			$comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
		}

		if (isset($periodo_academico) && $periodo_academico != "") {
			$comando->bindParam(":periodo", $periodo_academico, \PDO::PARAM_STR);
		}

		$resultData = $comando->queryAll();

		if ($onlyData) {
			return $resultData;
		}

		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $resultData,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => ['Periodo', 'Modalidad'],
			],
		]);
		return $dataProvider;
	}

	public function registrarCargaCartera($est_id, $cedula, $secuencial, $forma_pago, $fecha, $in, $numero_cuota,
		$valor_cuota, $total, $id_user, $estadoCancelado) {
		$con = \Yii::$app->db_facturacion;
		$estado = 1;
		\app\models\Utilities::putMessageLogFile('modelo...: ' . $in . ' cuota');
		//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('modelo...: '.$in.' cuota');
		\app\models\Utilities::putMessageLogFile($est_id . '-' . $cedula . '-' . $secuencial . '-' . $forma_pago . '-' . $fecha . '-' . $in . '-' . $numero_cuota . '-' .
			$valor_cuota . '-' . $total . '-' . $id_user);
		//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera($est_id.'-'.$cedula.'-'.$secuencial.'-'.$forma_pago.'-'.$fecha.'-'.$in.'-'.$numero_cuota.'-'.
		//$valor_cuota.'-'.$total.'-'.$id_user);
		$trans = $con->getTransaction(); // se obtiene la transacción actual
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
		if ($in == 0) {
			$cuota = substr($numero_cuota, -8);
			$in == 1;
			$abono = $total;
		} else {
			if ($numero_cuota >= 10) {
				if ($in <= 10) {
					$in = '0' . $in;
				}
				$cuota = $in . ' / ' . $numero_cuota;
			} else {
				$cuota = '0' . $in . ' / 0' . $numero_cuota;
			}
			$abono = 0;
		}

		try {
			$sql = "INSERT INTO " . $con->dbname . ".carga_cartera
        (
                est_id,
                ccar_tipo_documento,
                ccar_numero_documento,
                ccar_documento_identidad,
                ccar_forma_pago,
                ccar_num_cuota,
                ccar_fecha_factura,
                ccar_fecha_vencepago,
                ccar_dias_plazo,
                ccar_valor_cuota,
                ccar_valor_factura,
                ccar_fecha_pago,
                ccar_retencion_fuente,
                ccar_retencion_iva,
                ccar_numero_retencion,
                ccar_valor_iva,
                ccar_estado_cancela,
                ccar_codigo_cobrador,
                ccar_fecha_aprueba_rechaza,
                ccar_usu_aprueba_rechaza,
                ccar_usu_ingreso,
                ccar_usu_modifica,
                ccar_estado,
                ccar_abono,
                ccar_fecha_creacion,
                ccar_estado_logico)
                VALUES ( :est_id,
                    'FE', :secuencial,
                    :cedula,
                     '$forma_pago',
                     :cuota, :fecha_transaccion,
                    '$fecha', NULL,
                    :valor_cuota, :total, NULL, NULL, NULL, NULL, NULL, :estadoCancelado, NULL, NULL, NULL, '$id_user', NULL,:estado,$abono, :fecha_transaccion ,:estado);";

			$comando = $con->createCommand($sql);
			$comando->bindParam(":est_id", $est_id, \PDO::PARAM_STR);
			$comando->bindParam(":secuencial", $secuencial, \PDO::PARAM_STR);
			$comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);
			$comando->bindParam(":cuota", $cuota, \PDO::PARAM_STR);
			$comando->bindParam(":fecha_transaccion", $fecha_transaccion, \PDO::PARAM_STR);
			$comando->bindParam(":valor_cuota", $valor_cuota, \PDO::PARAM_STR);
			$comando->bindParam(":total", $total, \PDO::PARAM_STR);
			$comando->bindParam(":estadoCancelado", $estadoCancelado, \PDO::PARAM_STR);
			$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

			\app\models\Utilities::putMessageLogFile(' registrarCargaCartera :' . $comando->getRawSql());
			$resultData = $comando->execute();
			if ($trans !== null) {
				$trans->commit();
			}

			\app\models\Utilities::putMessageLogFile($con->getLastInsertID($con->dbname . '.carga_cartera') . ' - ResultData Carga Cartera...: ' . $resultData);
			return $con->getLastInsertID($con->dbname . '.carga_cartera');
			//return $resultData;
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();
			}

			\app\models\Utilities::putMessageLogFile('ResultData Error Carga Cartera...: ' . $ex->getMessage());
			return FALSE;
		}
	}

	public function getSecuencialCargaCartera() {
		$con = \Yii::$app->db_facturacion;
		$estado = 1;

		$sql = "SELECT substring(concat('00000000',count(ccar_id)),-8) as 'secuencial'
                from " . $con->dbname . ".carga_cartera;";

		$comando = $con->createCommand($sql);

		$resultData = $comando->queryOne();
		return $resultData;
	}

	public function getCuotaActual($in, $saca_id) {
		$con = \Yii::$app->db_academico;
		$estado = 1;

		$sql = "SELECT fvpa_fecha_vencimiento as 'fecha'
                from " . $con->dbname . ".fechas_vencimiento_pago
                where fvpa_cuota = $in
                and saca_id = $saca_id
                and fvpa_estado = 1
                and fvpa_estado_logico = 1";

		$comando = $con->createCommand($sql);
		\app\models\Utilities::putMessageLogFile('getCuotaActual: ' . $comando->getRawSql());

		$resultData = $comando->queryOne();

		return $resultData;
	}

	public function setFinProcesoRegistro($per_id) {
		$con = \Yii::$app->db_academico;
		$estado = 1;

		$sql = "UPDATE " . $con->dbname . ".registro_pago_matricula
                SET rpm_estado_generado = 1
                WHERE per_id = $per_id ";

		$comando = $con->createCommand($sql);

		$resultData = $comando->queryOne();

		return $resultData;
	}

	public function getRegistrosDuplicados($ron_id) {
		$con = \Yii::$app->db_academico;

		$estado = 1;
		try {
			$sql = "SELECT count(cfca_ron_id) as 'repetidos'
                from " . $con->dbname . ".cuotas_facturacion_cartera
                where cfca_ron_id = $ron_id
                and cfca_estado = 1
                and cfca_estado_logico = 1";

			$comando = $con->createCommand($sql);
			\app\models\Utilities::putMessageLogFile('getRegistrosDuplicados: ' . $comando->getRawSql());

			$resultData = $comando->queryOne();

			return $resultData;
		} catch (Exception $ex) {
			\app\models\Utilities::putMessageLogFile('modelo KO...: - KO - ' . $ex->getMessage());
			return 1;
		}
	}

	public function registrarRelacionCartera($est_id, $ron_id, $secuencial) {
		$con = \Yii::$app->db_academico;
		$con2 = \Yii::$app->db_academico;
		$estado = 1;
		\app\models\Utilities::putMessageLogFile('modelo N...: ' . $est_id . '-' . $ron_id . '-' . $secuencial);
		$trans = $con->getTransaction(); // se obtiene la transacción actual
		$trans2 = $con2->getTransaction();
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
		try {
			$sql = "INSERT INTO " . $con->dbname . ".cuotas_facturacion_cartera
            (
                cfca_ron_id,
                cfca_numero_documento,
                cfca_est_id,
                cfca_estado,
                cfca_fecha_creacion,
                cfca_estado_logico )
                    VALUES ( :rama_id,
                        :secuencial,
                        :est_id,
                        :estado,
                        :fecha_transaccion,
                        1
                        );";
			\app\models\Utilities::putMessageLogFile('modelo X...: ' . $sql);
			$aux = $ron_id;
			$comando = $con->createCommand($sql);
			$comando->bindParam(":rama_id", $ron_id, \PDO::PARAM_INT);
			$comando->bindParam(":secuencial", $secuencial, \PDO::PARAM_STR);
			$comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
			$comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
			$comando->bindParam(":fecha_transaccion", $fecha_transaccion, \PDO::PARAM_STR);

			$resultData = $comando->execute();
			if ($trans !== null) {
				$trans->commit();
				\app\models\Utilities::putMessageLogFile('modelo OK...: cuotas_facturacion_cartera COMMIT - OK');}
/*
$sql2="SELECT cfca_id FROM " . $con->dbname . ".cuotas_facturacion_cartera
WHERE cfca_rama_id = :rama_id and cfca_estado = 1;";
\app\models\Utilities::putMessageLogFile('modelo X...: '.$sql2);
$comando2 = $con2->createCommand($sql2);
$comando2->bindParam(":rama_id", $aux, \PDO::PARAM_INT);
$resultData2 = $comando2->queryOne();
if ($trans2 !== null){
$trans2->commit();
\app\models\Utilities::putMessageLogFile('modelo OK...: cuotas_facturacion_cartera SELECT - OK');}*/
			return $resultData;
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();}
			if ($trans2 !== null) {
				$trans2->rollback();}
			\app\models\Utilities::putMessageLogFile('modelo KO...: - KO - ' . $ex->getMessage());
			return FALSE;
		}
	}

	public function registrarPagoMatricula($usu, $per_id, $pla_id, $ron_id, $total, $tipo_pago) {
		$con = \Yii::$app->db_academico;
		$con2 = \Yii::$app->db_academico;
		$estado = 1;
		\app\models\Utilities::putMessageLogFile('modelo Pago Matricula...: ');
		$trans = $con->getTransaction(); // se obtiene la transacción actual
		$trans2 = $con2->getTransaction(); // se obtiene la transacción actual
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
		try {
			$sql = "INSERT INTO " . $con->dbname . ".registro_pago_matricula
            (
                per_id,
                pla_id,
                ron_id,
                rpm_estado_aprobacion,
                rpm_estado_generado,
                rpm_acepta_terminos,
                rpm_tipo_pago,
                rpm_usuario_apruebareprueba,
                rpm_fecha_transaccion,
                rpm_total,
                rpm_estado,
                rpm_fecha_creacion,
                rpm_estado_logico,
                rpm_archivo
                 )
                    VALUES ( :per_id,
                        :pla_id,
                        :ron_id,
                        :estado,
                        :estado,
                        :estado,
                        :tipo_pago,
                        :usu,
                        :fecha_transaccion,
                        :total,
                        :estado,
                        :fecha_transaccion,
                        :estado,'');";
			\app\models\Utilities::putMessageLogFile('modelo Pago Matricula FIN...: ' . $sql);
			$aux1 = $per_id;
			$aux2 = $pla_id;
			$aux3 = $ron_id;
			$comando = $con->createCommand($sql);
			$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
			$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
			$comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
			$comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
			$comando->bindParam(":tipo_pago", $tipo_pago, \PDO::PARAM_INT);
			$comando->bindParam(":usu", $usu, \PDO::PARAM_INT);
			$comando->bindParam(":fecha_transaccion", $fecha_transaccion, \PDO::PARAM_STR);
			$comando->bindParam(":total", $total, \PDO::PARAM_STR);
			$comando->bindParam(":fecha_transaccion", $fecha_transaccion, \PDO::PARAM_STR);

			$resultData = $comando->execute();
			if ($trans !== null) {
				$trans->commit();
				\app\models\Utilities::putMessageLogFile('modelo OK...: registro_pago_matricula COMMIT - OK');}
			$last_id = $con->getLastInsertID($con->dbname . '.registro_pago_matricula');
			\app\models\Utilities::putMessageLogFile('modelo OK...: registro_pago_matricula COMMIT - OK' . $last_id);
			return $last_id;
			//return $resultData;
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();}
			if ($trans2 !== null) {
				$trans2->rollback();}
			\app\models\Utilities::putMessageLogFile('modelo KO...:  - KO - ' . $ex->getMessage());
			return 1;
		}
	}

	public function registroOnlineCuota($ron_id, $rpm_id, $in, $fecha_vencimiento, $porcentaje, $costo) {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		\app\models\Utilities::putMessageLogFile('modelo Online Cuota...: ' . $in);
		$trans = $con->getTransaction(); // se obtiene la transacción actual
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
		try {
			$sql = "INSERT INTO " . $con->dbname . ".registro_online_cuota
            (
                ron_id,
                rpm_id,
                roc_num_cuota,
                roc_vencimiento,
                roc_porcentaje,
                roc_costo,
                roc_estado,
                roc_fecha_creacion,
                roc_estado_logico,
                roc_estado_pago
                 )
                    VALUES ( :ron_id,
                        :rpm_id,
                        :in,
                        :fecha_vencimiento,
                        FORMAT(:porcentaje,2),
                        :costo,
                        :estado,
                        :fecha_transaccion,
                        :estado,
                        '');";
			//\app\models\Utilities::putMessageLogFile('modelo Online Cuota FIN...: '.$sql);
			$comando = $con->createCommand($sql);
			$comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
			$comando->bindParam(":rpm_id", $rpm_id, \PDO::PARAM_INT);
			$comando->bindParam(":in", $in, \PDO::PARAM_INT);
			$comando->bindParam(":fecha_vencimiento", $fecha_vencimiento, \PDO::PARAM_STR);
			$comando->bindParam(":porcentaje", $porcentaje, \PDO::PARAM_STR);
			$comando->bindParam(":costo", $costo, \PDO::PARAM_STR);
			$comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
			$comando->bindParam(":fecha_transaccion", $fecha_transaccion, \PDO::PARAM_STR);
			$comando->bindParam(":estado", $estado, \PDO::PARAM_INT);

			$resultData = $comando->execute();
			if ($trans !== null) {
				$trans->commit();
				\app\models\Utilities::putMessageLogFile('modelo OK...pago: registro_online_cuota COMMIT - OK');}
			return $con->getLastInsertID($con->dbname . '.registro_online_cuota');
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();}
			\app\models\Utilities::putMessageLogFile('modelo KO...Pago: - KO - ' . $ex->getMessage());
			return FALSE;
		}
	}

	public function updateAdicionalMateria($rama_id, $rpm_id) {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		\app\models\Utilities::putMessageLogFile('registro adicional materia...: ');
		//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('registro adicional materia...: '.$rpm_id.' - rama: '.$rama_id);
		$trans = $con->getTransaction(); // se obtiene la transacción actual
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
		try {
			$sql = " UPDATE " . $con->dbname . ".registro_adicional_materias
                    SET rpm_id = $rpm_id
                    WHERE rama_id = $rama_id;";
			$comando = $con->createCommand($sql);
			\app\models\Utilities::putMessageLogFile('updateAdicionalMateria: ' . $comando->getRawSql());
			\app\models\Utilities::putMessageLogFile('modelo Online Cuota FIN...: ' . $sql);
			//$comando->bindParam(":rpm_id", $rpm_id, \PDO::PARAM_INT);
			// $comando->bindParam(":rama_id", $rama_id, \PDO::PARAM_INT);

			$resultData = $comando->execute();
			if ($trans !== null) {
				$trans->commit();
				\app\models\Utilities::putMessageLogFile('rpm OK...: registro_adicional_materias COMMIT - OK');}
			//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('rpm OK...: registro_adicional_materias COMMIT - OK');
			return $con->getLastInsertID($con->dbname . '.registro_adicional_materias');
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();}
			\app\models\Utilities::putMessageLogFile('rpm KO...: - KO - ' . $ex->getMessage());
			//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('rpm KO...: - KO - '.$ex->getMessage());
			return FALSE;
		}
	}

	public function getRonPes($per_id) {
		$con = \Yii::$app->db_academico;

		$sql = "SELECT pes.pes_id as 'pes_id', ro.ron_id as 'ron_id'
                FROM " . $con->dbname . ".planificacion_estudiante pes
                inner join " . $con->dbname . ".registro_online ro on ro.per_id = pes.per_id
                inner join " . $con->dbname . ".registro_adicional_materias rama on rama.ron_id = ro.ron_id
                where pes.per_id = $per_id
                and rama.rpm_id is NULL
                and pes.pes_estado_logico = 1
                and pes.pes_estado = 1 order by ro.ron_id desc limit 0,1";
		$comando = $con->createCommand($sql);

		$resultData = $comando->queryOne();
		\app\models\Utilities::putMessageLogFile('ron_id ' . $resultData['ron_id'] . ' pes_id ' . $resultData['pes_id']);
		return $resultData;
	}
	public function getRonPesPdf($per_id) {
		$con = \Yii::$app->db_academico;

		$sql = "SELECT pes.pes_id as 'pes_id', ro.ron_id as 'ron_id'
                FROM " . $con->dbname . ".planificacion_estudiante pes
                inner join " . $con->dbname . ".registro_online ro on ro.per_id = pes.per_id
                where pes.per_id = $per_id
                and pes.pes_estado_logico = 1
                and pes.pes_estado = 1 order by ro.ron_id desc limit 0,1";
		$comando = $con->createCommand($sql);

		$resultData = $comando->queryOne();
		\app\models\Utilities::putMessageLogFile('ron_id ' . $resultData['ron_id'] . ' pes_id ' . $resultData['pes_id']);
		return $resultData;
	}

	public function getRpmId($rama_id) {
		$con = \Yii::$app->db_academico;
		$sql = "SELECT rpm_id as rpm_id FROM " . $con->dbname . ".registro_adicional_materia
                WHERE rama_id = :rama_id ";
		/*
			                and pla_id = :pla_id
			                and ron_id = :ron_id
			                and rpm_estado = 1
			                order by rpm_id desc
		*/
		$comando = $con->createCommand($sql);
		$comando->bindParam(":rama_id", $ron_id, \PDO::PARAM_INT);
		$resultData = $comando->queryOne();

		\app\models\Utilities::putMessageLogFile('modelo OK...: getRpmId SELECT - OK');
		return $resultData;
	}

	public function updatePendiente($ron_id) {
		$con = \Yii::$app->db_academico;
		$sql = "UPDATE " . $con->dbname . ".registro_online
                set ron_valor_gastos_pendientes = 0
                WHERE ron_id = :ron_id ";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
		$resultData = $comando->execute();

		\app\models\Utilities::putMessageLogFile('modelo OK...: getRpmId SELECT - OK');
		return $resultData;
	}
	public function updatePagoTC($rama_id, $pfes_id) {
		$con = \Yii::$app->db_academico;
		$sql = "UPDATE " . $con->dbname . ".registro_adicional_materias
                set pfes_id = :pfes_id
                WHERE rama_id = :rama_id ";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":pfes_id", $pfes_id, \PDO::PARAM_INT);
		$comando->bindParam(":rama_id", $rama_id, \PDO::PARAM_INT);
		$resultData = $comando->execute();
		//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('update pfes_id');
		return $resultData;
	}

	public function updatePlanAnual($id, $fechaini, $fechafin, $fechaini1, $fechafin1, /* $fechaini2, $fechafin2,*/ $fechaini3, $fechafin3, $fechaini4, $fechafin4, $fechaini5, $fechafin5) {
		$con = \Yii::$app->db_academico;
		$usu_id = @Yii::$app->session->get("PB_iduser");
		$fecha = date(Yii::$app->params["dateTimeByDefault"]);
		$trans = $con->getTransaction(); // se obtiene la transacción actual
		if ($trans !== null) {
			$trans = null; // si existe la transacción entonces no se crea una
		} else {
			$trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
		}

		$param_sql = "rco_estado";
		$bdet_sql = "1";

		$param_sql .= ", rco_estado_logico";
		$bdet_sql .= ", 1";

		$param_sql .= ", rco_num_bloques";
		$bdet_sql .= ", 0";

		if (isset($fechaini)) {
			$param_sql .= ", rco_fecha_inicio";
			$bdet_sql .= ", :rco_fecha_inicio";
		}
		if (isset($fechafin)) {
			$param_sql .= ", rco_fecha_fin";
			$bdet_sql .= ", :rco_fecha_fin";
		}

		if (isset($fechaini1)) {
			$param_sql .= ", rco_fecha_ini_aplicacion";
			$bdet_sql .= ", :rco_fecha_ini_aplicacion";
		}

		if (isset($fechafin1)) {
			$param_sql .= ", rco_fecha_fin_aplicacion";
			$bdet_sql .= ", :rco_fecha_fin_aplicacion";
		}

		if (isset($fechaini3)) {
			$param_sql .= ", rco_fecha_ini_periodoextra";
			$bdet_sql .= ", :rco_fecha_ini_periodoextra";
		}

		if (isset($fechafin3)) {
			$param_sql .= ", rco_fecha_fin_periodoextra";
			$bdet_sql .= ", :rco_fecha_fin_periodoextra";
		}

		if (isset($fechaini4)) {
			$param_sql .= ", rco_fecha_ini_clases";
			$bdet_sql .= ", :rco_fecha_ini_clases";
		}

		if (isset($fechafin4)) {
			$param_sql .= ", rco_fecha_fin_clases";
			$bdet_sql .= ", :rco_fecha_fin_clases";
		}

		if (isset($fechaini5)) {
			$param_sql .= ", rco_fecha_ini_examenes";
			$bdet_sql .= ", :rco_fecha_ini_examenes";
		}

		if (isset($fechafin5)) {
			$param_sql .= ", rco_fecha_fin_examenes";
			$bdet_sql .= ", :rco_fecha_fin_examenes";
		}

		try {
			\app\models\Utilities::putMessageLogFile('entro sdesdf...: ');
			$sql = "UPDATE " . $con->dbname . ".registro_configuracion SET
              rco_fecha_inicio = :rco_fecha_inicio,
              rco_fecha_fin = :rco_fecha_fin,
              rco_fecha_ini_aplicacion = :rco_fecha_ini_aplicacion,
              rco_fecha_fin_aplicacion = :rco_fecha_fin_aplicacion,
              rco_fecha_ini_periodoextra = :rco_fecha_ini_periodoextra,
              rco_fecha_fin_periodoextra = :rco_fecha_fin_periodoextra,
              rco_fecha_ini_clases = :rco_fecha_ini_clases,
              rco_fecha_fin_clases = :rco_fecha_fin_clases,
              rco_fecha_ini_examenes = :rco_fecha_ini_examenes,
              co_fecha_fin_examenes = :rco_fecha_fin_examenes,
              rco_usuario_modifica   = $usu_id,
              rco_fecha_modificacion = $fecha
              WHERE rco_id = :id and rco_estado = 1 and rco_estado_logico = 1";

			// Hacer al query un comando
			$comando = $con->createCommand($sql);

			if (isset($id)) {
				$comando->bindParam(':id', $id, \PDO::PARAM_INT);
			}

			if ($fechaini != "" && $fechafin != "") {
				$fecha_ini = $fechaini . " 00:00:00";
				$fecha_fin = $fechafin . " 23:59:59";
				$comando->bindParam(":rco_fecha_inicio", $fecha_ini, \PDO::PARAM_STR);
				$comando->bindParam(":rco_fecha_fin", $fecha_fin, \PDO::PARAM_STR);

			}

			if ($fechaini1 != "" && $fechafin1 != "") {
				$fecha_ini1 = $fechaini1 . " 00:00:00";
				$fecha_fin1 = $fechafin1 . " 23:59:59";
				$comando->bindParam(":rco_fecha_ini_aplicacion", $fecha_ini1, \PDO::PARAM_STR);
				$comando->bindParam(":rco_fecha_fin_aplicacion", $fecha_fin1, \PDO::PARAM_STR);

			}

			if ($fechaini3 != "" && $fechafin3 != "") {
				$fecha_ini3 = $fechaini3 . " 00:00:00";
				$fecha_fin3 = $fechafin3 . " 23:59:59";
				$comando->bindParam(":rco_fecha_ini_periodoextra", $fecha_ini3, \PDO::PARAM_STR);
				$comando->bindParam(":rco_fecha_fin_periodoextra", $fecha_fin3, \PDO::PARAM_STR);

			}

			if ($fechaini4 != "" && $fechafin4 != "") {
				$fecha_ini4 = $fechaini4 . " 00:00:00";
				$fecha_fin4 = $fechafin4 . " 23:59:59";
				$comando->bindParam(":rco_fecha_ini_clases", $fecha_ini4, \PDO::PARAM_STR);
				$comando->bindParam(":rco_fecha_fin_clases", $fecha_fin4, \PDO::PARAM_STR);

			}

			if ($fechaini5 != "" && $fechafin5 != "") {
				$fecha_ini5 = $fechaini5 . " 00:00:00";
				$fecha_fin5 = $fechafin5 . " 23:59:59";
				$comando->bindParam(":rco_fecha_ini_examenes", $fecha_ini5, \PDO::PARAM_STR);
				$comando->bindParam(":rco_fecha_fin_examenes", $fecha_fin5, \PDO::PARAM_STR);

			}

			$comando->execute();

			if ($trans !== null) {
				$trans->commit();
			}

			\app\models\Utilities::putMessageLogFile('updatePlanAnual: ' . $comando->getRawSql());

			return TRUE;
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();
			}

			return FALSE;
		}
	}

	public function insertarPlanAnual(
		$pla_id,
		$fechaini,
		$fechafin,
		$fechaini1,
		$fechafin1,
		/* $fechaini2, $fechafin2,*/
		$fechaini3,
		$fechafin3,
		$fechaini4,
		$fechafin4,
		$fechaini5,
		$fechafin5
	) {
		$con = \Yii::$app->db_academico;
		$transaction = $con->beginTransaction();
		$date = date(Yii::$app->params["dateTimeByDefault"]);
		// se obtiene la transacción actual
		if ($fechaini != "" && $fechafin != "") {
			$fecha_ini = $fechaini . " 00:00:00";
			$fecha_fin = $fechafin . " 23:59:59";
		}

		if ($fechaini1 != "" && $fechafin1 != "") {
			$fecha_ini1 = $fechaini1 . " 00:00:00";
			$fecha_fin1 = $fechafin1 . " 23:59:59";

		}

		/*
			        if ($fechaini2 != "" && $fechafin2 != "") {
			            $fecha_ini2 = $fechaini2 . " 00:00:00";
			            $fecha_fin2 = $fechafin2 . " 23:59:59";
			            $comando->bindParam(":rco_fecha_ini_registro", $fecha_ini2, \PDO::PARAM_STR);
			            $comando->bindParam(":rco_fecha_fin_registro", $fecha_fin2, \PDO::PARAM_STR);

			        }

		*/
		if ($fechaini3 != "" && $fechafin3 != "") {
			$fecha_ini3 = $fechaini3 . " 00:00:00";
			$fecha_fin3 = $fechafin3 . " 23:59:59";

		}

		if ($fechaini4 != "" && $fechafin4 != "") {
			$fecha_ini4 = $fechaini4 . " 00:00:00";
			$fecha_fin4 = $fechafin4 . " 23:59:59";

		}

		if ($fechaini5 != "" && $fechafin5 != "") {
			$fecha_ini5 = $fechaini5 . " 00:00:00";
			$fecha_fin5 = $fechafin5 . " 23:59:59";
		}

		try {
			\app\models\Utilities::putMessageLogFile('entro insert...: ');
			$sql = "INSERT INTO " . $con->dbname . ".registro_configuracion
                (pla_id,
                rco_fecha_inicio,
                rco_fecha_fin,
                rco_fecha_ini_aplicacion,
                rco_fecha_fin_aplicacion,
                rco_fecha_ini_registro,
                rco_fecha_fin_registro,
                rco_fecha_ini_periodoextra,
                rco_fecha_fin_periodoextra,
                rco_fecha_ini_clases,
                rco_fecha_fin_clases,
                rco_fecha_ini_examenes,
                rco_fecha_fin_examenes,
                rco_num_bloques,
                rco_estado,
                rco_fecha_creacion,
                rco_usuario_modifica,
                rco_fecha_modificacion,
                rco_estado_logico
                ) VALUES(
                    $pla_id,
                    '$fecha_ini',
                    '$fecha_fin',
                    '$fecha_ini1',
                    '$fecha_fin1',
                    '$fecha_ini',
                    '$fecha_fin',
                    '$fecha_ini3',
                    '$fecha_fin3',
                    '$fecha_ini4',
                    '$fecha_fin4',
                    '$fecha_ini5',
                    '$fecha_fin5',
                    0,
                    1,
                    '$date',
                    Null,
                    Null,
                    1

                )";
			$comando = $con->createCommand($sql);
			$comando->execute();
			\app\models\Utilities::putMessageLogFile('insertRegConf: ' . $comando->getRawSql());

			if ($transaction !== null) {
				$transaction->commit();
			}

			return TRUE;
		} catch (Exception $ex) {
			if ($transaction !== null) {
				$transaction->rollback();
			}

			return FALSE;
		}
	}

	public function registrarCuotasFacturacionCartera($rama_id, $numDoc, $ron_id, $est_id) {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		$trans = $con->getTransaction(); // se obtiene la transacción actual
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);

		try {
			$select = ("select cc.ccar_id as 'id' from db_facturacion.carga_cartera cc where cc.ccar_numero_documento = '$numDoc' order by 1 desc limit 0,1;");
			$comando = $con->createCommand($select);
			\app\models\Utilities::putMessageLogFile('select creado: ' . $comando->getRawSql());
			$resultData = $comando->queryOne();
			$ccar_id = $resultData['id'];
			\app\models\Utilities::putMessageLogFile('select creado: ' . $resultData['id']);

			$sql = "INSERT INTO " . $con->dbname . ".cuotas_facturacion_cartera
        (
        cfca_rama_id,
        cfca_numero_documento,
        cfca_ron_id,
        cfca_est_id,
        cfca_ccar_id,
        cfca_estado,
        cfca_fecha_creacion,
        cfca_estado_logico)
        value($rama_id,'$numDoc',$ron_id,$est_id,
        $ccar_id,1,'$fecha_transaccion',1);";

			$comando = $con->createCommand($sql);
			\app\models\Utilities::putMessageLogFile('registrarCuotasFacturacionCartera: ' . $comando->getRawSql());
			$resultData = $comando->execute();
			if ($trans !== null) {
				$trans->commit();
			}

			\app\models\Utilities::putMessageLogFile('ResultData Cuotas Cartera...: ' . $resultData);
			return $resultData;
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();
			}

			\app\models\Utilities::putMessageLogFile('ResultData Error Cuotas Cartera...: ' . $ex->getMessage());
			return FALSE;
		}
	}
	public function detalleMateriasMatricula($ron_id) {
		$con = \Yii::$app->db_academico;

		try {

			$sql = "SELECT distinct concat(smad.sasi_id,'-',made.made_credito,'-',replace(pcc.pccr_costo_credito,'.','_')) as detalle
                from " . $con->dbname . ".registro_online_item roi
            inner join " . $con->dbname . ".planificacion_estudiante pes
            inner join " . $con->dbname . ".planificacion pla on pes.pla_id = pla.pla_id and pla.pla_estado = 1
            inner join " . $con->dbname . ".registro_online ro on roi.ron_id = ro.ron_id and ro.per_id = pes.per_id and ro.ron_id = $ron_id
            inner join " . $con->dbname . ".asignatura a on a.asi_id in (TRIM(LEADING '0' FROM substr(roi.roi_materia_cod,10,4)))
            inner join " . $con->dbname . ".malla_academico_estudiante maca on maca.per_id = pes.per_id and maca.asi_id = a.asi_id
            inner join " . $con->dbname . ".malla_academica_detalle made on made.maca_id = maca.maca_id and made.asi_id = a.asi_id
            inner join " . $con->dbname . ".siga_malla_academica_detalle smad on smad.asi_id = a.asi_id and smad.asi_id = maca.asi_id and smad.maca_id = maca.maca_id
            inner join " . $con->dbname . ".estudiante e on e.per_id = pes.per_id
			inner join " . $con->dbname . ".malla_unidad_modalidad mum on mum.maca_id = maca.maca_id
            inner join " . $con->dbname . ".modalidad_estudio_unidad meun on meun.meun_id = mum.meun_id
            inner join " . $con->dbname . ".programa_costo_credito pcc on pcc.maca_id = maca.maca_id  and pcc.pccr_categoria = e.est_categoria and pcc.pccr_creditos = made.made_credito
            and pcc.eaca_id = meun.eaca_id and pcc.mod_id = pla.mod_id";

			$comando = $con->createCommand($sql);
			\app\models\Utilities::putMessageLogFile('detalleMateriasMatricula: ' . $comando->getRawSql());
			$resultData = $comando->queryAll();
			return $resultData;
		} catch (Exception $ex) {
			\app\models\Utilities::putMessageLogFile('detalleMateriasMatricula: ' . $ex->getMessage());
			return FALSE;
		}
	}
	public function getFlujoSiga($per_id, $mod_id) {
		$con = \Yii::$app->db_academico;

		try {
			if ($mod_id == 1) {$tabla = 'sa_alumno_flujo_onl';} else { $tabla = 'sa_alumno_flujo_mod';}

			$sql = "SELECT max(sa.id) as id, sm.macs_id as carrera FROM db_siga.$tabla sa
                    inner join " . $con->dbname . ".siga_estudiante si on si.sest_id = sa.id_alumno and si.per_id = $per_id
                    inner join " . $con->dbname . ".siga_malla_academica sim on sim.macs_id = sa.id_flujo
                    inner join " . $con->dbname . ".malla_academico_estudiante maca on maca.per_id = si.per_id
                    inner join " . $con->dbname . ".siga_malla_academica sm on sm.maca_id = maca.maca_id
                    group by sa.id_alumno
                    having max(sa.id)
                    order by 1 desc limit 0,1";

			$comando = $con->createCommand($sql);
			\app\models\Utilities::putMessageLogFile('getFlujoSiga: ' . $comando->getRawSql());
			$resultData = $comando->queryOne();
			return $resultData;
		} catch (Exception $ex) {
			\app\models\Utilities::putMessageLogFile('getFlujoSiga: ' . $ex->getMessage());
			return FALSE;
		}
	}

	public function consultarEstudiantematriculado($search = NULL, $isEstud = NULL, $mod_id = NULL, $estado = NULL, $periodo = NULL, $dataProvider = false, $per_id, $grupo_id, $onlyData = false, $f_ini = NULL, $f_fin = NULL) {
		$con_academico = \Yii::$app->db_academico;
		$con = \Yii::$app->db;
		if ($per_id == Null) {$per_id = Yii::$app->session->get("PB_perid");}
		//$per_id = Yii::$app->session->get("PB_perid");
		$search_cond = "%" . $search . "%";
		$condition = "";
		$str_search = "";

		if (isset($search) && $search != "") {
			$str_search = "(pe.pes_nombres like (%:search%) OR ";
			$str_search .= "pe.pes_dni like (%:search%)) AND ";
		}
		if (isset($mod_id) && $mod_id != "" && $mod_id != 0) {
			$condition .= "me.mod_id = :mod_id AND ";
		}
		/*if (isset($estado) && $estado != "" && $estado != -1) {
			$condition .= "reg.rpm_estado_generado = :estado AND ";
		}*/
		\app\models\Utilities::putMessageLogFile('A1' . $periodo);
		if (isset($periodo) && $periodo != "" && $periodo != 0) {
			//$periodo = "%" . $periodo . "%";
			$condition .= "p.saca_id = :periodo AND ";
			//$condition .= "p.pla_id = :periodo AND ";
			//$condition .= "p.pla_periodo_academico like :periodo AND ";
		}
		if ($f_ini != "" && $f_fin != "") {
			$condition .= " reg.rpm_fecha_transaccion between :fec_ini AND :fec_fin AND ";
		}
		if ($grupo_id == 12) {
			$condition .= "per.per_id = :per_id AND ";
		}

		$sql = "SELECT distinct
                    CONCAT(ifnull(TRIM(per.per_pri_nombre),''),' ',ifnull(TRIM(per.per_seg_nombre),''),' ',ifnull(TRIM(per.per_pri_apellido),''),' ',ifnull(TRIM(per.per_seg_apellido),''),'') as Estudiante,
                    per.per_cedula						as 'Cedula',
                    p.pla_periodo_academico             as 'Periodo Academico',
                    date_format(reg.rpm_fecha_transaccion, '%Y-%m-%d')            as 'Fecha transacción',
                    mo.mod_nombre                       as 'Modalidad',
                    tmp.Cant                            as 'Materias',
                    reg.rpm_total                       as 'Costo',
                    ifnull(rf.Refund, '0.00')           as 'Reembolso',
                    case ifnull(reg.rpm_estado_generado,0)
                    when 1 then 'Inscripcion Realizada'
                    when 0 then 'Pendiente de Pago'
                    end  as 'Estado'
                FROM " . $con_academico->dbname . ".planificacion_estudiante                AS pe
                    INNER JOIN " . $con_academico->dbname . ".planificacion                 AS p on p.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online               AS r on r.pes_id = pe.pes_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online_cuota         AS roc on roc.ron_id = r.ron_id
                    INNER JOIN " . $con->dbname . ".persona                                 AS per on per.per_id = pe.per_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante                    AS est on est.per_id = per.per_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante_carrera_programa   AS ec ON est.est_id = ec.est_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad_estudio_unidad      AS me ON ec.meun_id = me.meun_id
                    INNER JOIN " . $con_academico->dbname . ".estudio_academico             AS ea ON ea.eaca_id = me.eaca_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica              AS ua ON me.uaca_id = ua.uaca_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad                     AS mo ON mo.mod_id = me.mod_id
                    LEFT JOIN " . $con_academico->dbname . ".registro_adicional_materias    AS ram on ram.ron_id = r.ron_id

                    LEFT JOIN (
                        SELECT
                            r.pes_id as pes_id,
                            rm.rama_id as rama_id,
                            COUNT(*) as Cant,
                            SUM(it.roi_costo) as Costo,
                            r.ron_valor_gastos_adm as costo_adm,
                            SUM(it.roi_creditos) as Creditos
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS it ON it.ron_id = r.ron_id
                            LEFT JOIN " . $con_academico->dbname . ".registro_adicional_materias AS rm ON rm.ron_id = r.ron_id
                            AND (
                            rm.roi_id_1 = it.roi_id OR
                            rm.roi_id_2 = it.roi_id OR
                            rm.roi_id_3 = it.roi_id OR
                            rm.roi_id_4 = it.roi_id OR
                            rm.roi_id_5 = it.roi_id OR
                            rm.roi_id_6 = it.roi_id OR
                            rm.roi_id_7 = it.roi_id OR
                            rm.roi_id_8 = it.roi_id
                            )
                        WHERE
                            r.ron_estado = 1 AND r.ron_estado_logico = 1 AND
                            it.roi_estado = 1 AND it.roi_estado_logico = 1 AND
                            rm.rama_estado = 1 AND rm.rama_estado_logico = 1
                        GROUP BY r.pes_id, rm.rama_id
                    ) AS tmp ON tmp.rama_id = ram.rama_id
                    LEFT JOIN (
                        SELECT
                            MIN(r.ron_id) as ron_id,
                            r.per_id as per_id
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                        WHERE
                            r.ron_estado = 1 AND r.ron_estado_logico = 1
                        GROUP BY per_id
                    ) AS mi ON mi.ron_id = r.ron_id
                    LEFT JOIN " . $con_academico->dbname . ".registro_pago_matricula AS reg on reg.rpm_id = ram.rpm_id AND reg.per_id = per.per_id
                        AND r.ron_id = reg.ron_id AND reg.rpm_estado = 1 AND reg.rpm_estado_logico = 1
                    " . /*LEFT JOIN " . $con_academico->dbname . ".enrolamiento_agreement AS enr on enr.ron_id = r.ron_id
	                        AND reg.rpm_estado = 1 AND reg.rpm_estado_logico = 1 AND enr.rpm_id = ram.rpm_id AND enr.eagr_estado = 1 AND enr.eagr_estado_logico = 1
						*/" 
	                    LEFT JOIN (
                        SELECT
                            ro.ron_id as ron_id,
                            ro.rpm_id as rpm_id,
                            SUM(ri.roi_costo) as Refund
                        FROM
                            " . $con_academico->dbname . ".cancelacion_registro_online_item AS it
                            INNER JOIN " . $con_academico->dbname . ".cancelacion_registro_online AS ro ON it.cron_id = ro.cron_id
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS ri ON ri.roi_id = it.roi_id
                        WHERE
                            ro.cron_estado_cancelacion = 2 AND
                            it.croi_estado = 1 AND it.croi_estado_logico = 1 AND
                            ro.cron_estado = 1 AND ro.cron_estado_logico = 1 AND
                            ri.roi_estado = 1 AND ri.roi_estado_logico = 1
                        GROUP BY ro.ron_id, ro.rpm_id
                    ) AS rf ON rf.ron_id = r.ron_id AND rf.rpm_id = reg.rpm_id
                WHERE
                    $str_search
                    $condition
                    roc.rpm_id = ram.rpm_id AND
                    pe.pes_estado = 1 AND pe.pes_estado_logico = 1 AND
                    p.pla_estado = 1 AND p.pla_estado_logico = 1 AND
                    per.per_estado = 1 AND per.per_estado_logico = 1 AND
                    est.est_estado = 1 AND est.est_estado_logico = 1 AND
                    r.ron_estado = 1 AND r.ron_estado_logico = 1 AND
                    roc.roc_estado = 1 AND roc.roc_estado_logico = 1 AND
                    ram.rama_estado =1 and ram.rama_estado_logico = 1 AND
                    tmp.Cant IS NOT NULL AND
                    tmp.Costo IS NOT NULL AND
                    tmp.costo_adm IS NOT NULL AND
                    ram.rpm_id IS NOT NULL AND
                    reg.rpm_tipo_pago = 3

                UNION

                SELECT distinct
                        CONCAT(ifnull(TRIM(per.per_pri_nombre),''),' ',ifnull(TRIM(per.per_seg_nombre),''),' ',ifnull(TRIM(per.per_pri_apellido),''),' ',ifnull(TRIM(per.per_seg_apellido),''),'') as Estudiante,
                        per.per_cedula						as 'Cedula',
                        p.pla_periodo_academico             as 'Periodo Academico',
                        date_format(reg.rpm_fecha_transaccion, '%Y-%m-%d')            as 'Fecha transacción',
                        mo.mod_nombre                       as 'Modalidad',
                        tmp.Cant                            as 'Materias',
                        reg.rpm_total                       as 'Costo',
                        ifnull(rf.Refund, '0.00')           as 'Reembolso',
                        ifnull(reg.rpm_estado_generado,0)   as 'Estado'
                FROM " . $con_academico->dbname . ".planificacion_estudiante                AS pe
                    INNER JOIN " . $con_academico->dbname . ".planificacion                 AS p on p.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online               AS r on r.pes_id = pe.pes_id
                    INNER JOIN " . $con->dbname . ".persona                                 AS per on per.per_id = pe.per_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante                    AS est on est.per_id = per.per_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante_carrera_programa   AS ec ON est.est_id = ec.est_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad_estudio_unidad      AS me ON ec.meun_id = me.meun_id
                    INNER JOIN " . $con_academico->dbname . ".estudio_academico             AS ea ON ea.eaca_id = me.eaca_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica              AS ua ON me.uaca_id = ua.uaca_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad                     AS mo ON mo.mod_id = me.mod_id
                    LEFT JOIN " . $con_academico->dbname . ".registro_adicional_materias    AS ram on ram.ron_id = r.ron_id
                    INNER JOIN db_facturacion.detalle_pagos_factura                         AS dpfa on ram.pfes_id = dpfa.pfes_id
                    LEFT JOIN (
                        SELECT
                            r.pes_id as pes_id,
                            rm.rama_id as rama_id,
                            COUNT(*) as Cant,
                            SUM(it.roi_costo) as Costo,
                            r.ron_valor_gastos_adm as costo_adm,
                            SUM(it.roi_creditos) as Creditos
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS it ON it.ron_id = r.ron_id
                            LEFT JOIN " . $con_academico->dbname . ".registro_adicional_materias AS rm ON rm.ron_id = r.ron_id
                            AND (
                            rm.roi_id_1 = it.roi_id OR
                            rm.roi_id_2 = it.roi_id OR
                            rm.roi_id_3 = it.roi_id OR
                            rm.roi_id_4 = it.roi_id OR
                            rm.roi_id_5 = it.roi_id OR
                            rm.roi_id_6 = it.roi_id OR
                            rm.roi_id_7 = it.roi_id OR
                            rm.roi_id_8 = it.roi_id
                            )
                        WHERE
                            r.ron_estado = 1 AND r.ron_estado_logico = 1 AND
                            it.roi_estado = 1 AND it.roi_estado_logico = 1 AND
                            rm.rama_estado = 1 AND rm.rama_estado_logico = 1
                        GROUP BY r.pes_id, rm.rama_id
                    ) AS tmp ON tmp.rama_id = ram.rama_id
                    LEFT JOIN (
                        SELECT
                            MIN(r.ron_id) as ron_id,
                            r.per_id as per_id
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                        WHERE
                            r.ron_estado = 1 AND r.ron_estado_logico = 1
                        GROUP BY per_id
                    ) AS mi ON mi.ron_id = r.ron_id
                    LEFT JOIN " . $con_academico->dbname . ".registro_pago_matricula AS reg on reg.rpm_id = ram.rpm_id AND reg.per_id = per.per_id
                        AND r.ron_id = reg.ron_id AND reg.rpm_estado = 1 AND reg.rpm_estado_logico = 1
                    " . /*LEFT JOIN " . $con_academico->dbname . ".enrolamiento_agreement AS enr on enr.ron_id = r.ron_id
	                        AND reg.rpm_estado = 1 AND reg.rpm_estado_logico = 1 AND enr.rpm_id = ram.rpm_id AND enr.eagr_estado = 1 AND enr.eagr_estado_logico = 1
						*/" 
	                    LEFT JOIN (
                        SELECT
                            ro.ron_id as ron_id,
                            ro.rpm_id as rpm_id,
                            SUM(ri.roi_costo) as Refund
                        FROM
                            " . $con_academico->dbname . ".cancelacion_registro_online_item AS it
                            INNER JOIN " . $con_academico->dbname . ".cancelacion_registro_online AS ro ON it.cron_id = ro.cron_id
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS ri ON ri.roi_id = it.roi_id
                        WHERE
                            ro.cron_estado_cancelacion = 2 AND
                            it.croi_estado = 1 AND it.croi_estado_logico = 1 AND
                            ro.cron_estado = 1 AND ro.cron_estado_logico = 1 AND
                            ri.roi_estado = 1 AND ri.roi_estado_logico = 1
                        GROUP BY ro.ron_id, ro.rpm_id
                    ) AS rf ON rf.ron_id = r.ron_id AND rf.rpm_id = reg.rpm_id
                WHERE
                    $str_search
                    $condition
                    reg.rpm_id = ram.rpm_id AND
                    -- dpfa.dpfa_estado_pago = 2 and 
                    dpfa.dpfa_estado_financiero = 'C' AND
                    pe.pes_estado = 1 AND pe.pes_estado_logico = 1 AND
                    p.pla_estado = 1 AND p.pla_estado_logico = 1 AND
                    per.per_estado = 1 AND per.per_estado_logico = 1 AND
                    est.est_estado = 1 AND est.est_estado_logico = 1 AND
                    r.ron_estado = 1 AND r.ron_estado_logico = 1 AND
                    reg.rpm_estado = 1 AND reg.rpm_estado_logico = 1 AND
                    ram.rama_estado =1 and ram.rama_estado_logico = 1 AND
                    tmp.Cant IS NOT NULL AND
                    tmp.Costo IS NOT NULL AND
                    tmp.costo_adm IS NOT NULL AND
                    ram.rpm_id IS NOT NULL AND
                    dpfa.dpfa_estado = 1 and dpfa.dpfa_estado_logico = 1 AND
                    reg.rpm_tipo_pago = 2

                order by 'Fecha transacción' desc";

		$comando = $con_academico->createCommand($sql);

		if (isset($search) && $search != "") {
			$comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
		}

		if (isset($mod_id) && $mod_id != "" && $mod_id != 0) {
			$comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
		}

		/*if (isset($estado) && $estado != "" && $estado != -1) {
			$comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
		}*/

		if (isset($periodo) && $periodo != "" && $periodo != 0) {
			$comando->bindParam(":periodo", $periodo, \PDO::PARAM_STR);
		}

		//if ($isEstud) {
		if ($grupo_id == 12) {
			$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
		}

		if ($f_ini != "" && $f_fin != "") {
			$comando->bindParam(":fec_ini", $f_ini, \PDO::PARAM_STR);
			$comando->bindParam(":fec_fin", $f_fin, \PDO::PARAM_STR);
		}

		$res = $comando->queryAll();
		\app\models\Utilities::putMessageLogFile($comando->getRawSql());

		if ($dataProvider) {
			$dataProvider = new ArrayDataProvider([
				'key' => 'Id',
				'allModels' => $res,
				'pagination' => [
					'pageSize' => Yii::$app->params["pageSize"],
				],
				'sort' => [
					'attributes' => ['Estudiante', 'Cedula', "Carrera", "Modalidad", "Periodo", "Estado", "Fecha"],
				],
			]);

			return $dataProvider;
		}
		return $res;
	}
}
