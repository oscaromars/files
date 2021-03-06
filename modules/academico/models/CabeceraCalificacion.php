<?php

namespace app\modules\academico\models;
use app\models\Utilities;
use Yii;
use yii\base\Exception;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "cabecera_calificacion".
 *
 * @property int $ccal_id
 * @property int $paca_id
 * @property int $est_id
 * @property int $pro_id
 * @property int $asi_id
 * @property int $ecun_id
 * @property double $ccal_calificacion
 * @property string $ccal_estado
 * @property string $ccal_fecha_creacion
 * @property string $ccal_fecha_modificacion
 * @property string $ccal_estado_logico
 *
 * @property PeriodoAcademico $paca
 * @property Estudiante $est
 * @property Profesor $pro
 * @property Asignatura $asi
 * @property EsquemaCalificacionUnidad $ecun
 * @property DetalleCalificacion[] $detalleCalificacions
 */
class CabeceraCalificacion extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'cabecera_calificacion';
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
			[['paca_id', 'est_id', 'pro_id', 'asi_id', 'ecun_id', 'ccal_estado', 'ccal_estado_logico'], 'required'],
			[['paca_id', 'est_id', 'pro_id', 'asi_id', 'ecun_id'], 'integer'],
			[['ccal_calificacion'], 'number'],
			[['ccal_fecha_creacion', 'ccal_fecha_modificacion'], 'safe'],
			[['ccal_estado', 'ccal_estado_logico'], 'string', 'max' => 1],
			[['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
			[['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudiante::className(), 'targetAttribute' => ['est_id' => 'est_id']],
			[['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
			[['asi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asi_id' => 'asi_id']],
			[['ecun_id'], 'exist', 'skipOnError' => true, 'targetClass' => EsquemaCalificacionUnidad::className(), 'targetAttribute' => ['ecun_id' => 'ecun_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'ccal_id' => 'Ccal ID',
			'paca_id' => 'Paca ID',
			'est_id' => 'Est ID',
			'pro_id' => 'Pro ID',
			'asi_id' => 'Asi ID',
			'ecun_id' => 'Ecun ID',
			'ccal_calificacion' => 'Ccal Calificacion',
			'ccal_estado' => 'Ccal Estado',
			'ccal_fecha_creacion' => 'Ccal Fecha Creacion',
			'ccal_fecha_modificacion' => 'Ccal Fecha Modificacion',
			'ccal_estado_logico' => 'Ccal Estado Logico',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPaca() {
		return $this->hasOne(PeriodoAcademico::className(), ['paca_id' => 'paca_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEst() {
		return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPro() {
		return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAsi() {
		return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEcun() {
		return $this->hasOne(EsquemaCalificacionUnidad::className(), ['ecun_id' => 'ecun_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDetalleCalificacions() {
		return $this->hasMany(DetalleCalificacion::className(), ['ccal_id' => 'ccal_id']);
	}

	/**
	 * Function Consultar componentes por unidad academicas
	 * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
	 * @param
	 * @return  $resultData (Retornar los datos).
	 */
	public function getComponenteUnidad($uaca_id) {
		$con_academico = \Yii::$app->db_academico;
		$estado = "1";

		$sql = "SELECT group_concat(comp.com_id, ', ') as id,
                       group_concat('Sum(', com_nombre, '), ') as columna,
                       group_concat( com_nombre, ', ') as nombre
                FROM " . $con_academico->dbname . ".componente_unidad coun
                INNER JOIN " . $con_academico->dbname . ".componente comp ON comp.com_id = coun.com_id
                WHERE coun.uaca_id = :uaca_id
                      AND coun.cuni_estado = :estado
                      AND coun.cuni_estado_logico = :estado";

		$comando = $con_academico->createCommand($sql);
		$comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
		$comando->bindParam("estado", $estado, \PDO::PARAM_STR);

		$res = $comando->queryAll();
		return $res;
	}

	/**
	 * Function Consultar los componentes por unidad academicas
	 * y devuelve un arreglo
	 * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
	 * @modify Luis Cajamarca <analita04>
	 * @param
	 * @return  $resultData (Retornar los datos).
	 */
	public function getComponenteUnidadarr($uaca_id, $mod_id, $parcial) {
		$con_academico = Yii::$app->db_academico;
		$estado = "1";

		$str_parcial = "";
		/* Correcci??n de codigo, la consulta determinaba sin parcial, por lo que, no determinaba de manera correcta para otras modalidades.
			            // Si se est?? buscando Grado y a la vez Online, para evitar problemas, se busca el ecal_id 1 que es el 1er Parcial, y este tiene los componentes iguales que el 2do Parcial
			            if ($uaca_id == 1 && $mod_id == 1) {
			                $str_parcial = "AND coun.ecal_id = 1 ";
			            }
		*/

		$sql = "SELECT coun.cuni_id as id,
                       comp.com_nombre as nombre
                       ,coun.cuni_calificacion as notamax
                  FROM " . $con_academico->dbname . ".componente_unidad coun
            INNER JOIN " . $con_academico->dbname . ".componente comp
                    ON comp.com_id = coun.com_id
                 WHERE coun.uaca_id = :uaca_id
                   AND coun.mod_id = :mod_id
                   AND coun.ecal_id= :parcial
                   AND coun.cuni_estado = :estado
                   AND coun.cuni_estado_logico = :estado";

		$comando = $con_academico->createCommand($sql);
		$comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
		$comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
		$comando->bindParam(":parcial", $parcial, \PDO::PARAM_INT);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

		$res = $comando->queryAll();
		return $res;
	}

	/*
		    public function modificarCalificacion($value){
		        $con_academico = \Yii::$app->db_academico;
		        $estado = "1";

		        $sql = "SELECT coun.cuni_id as id,
		                       com_nombre as nombre
		                FROM " . $con_academico->dbname . ".componente_unidad coun
		                INNER JOIN " . $con_academico->dbname . ".componente comp ON comp.com_id = coun.com_id
		                WHERE coun.uaca_id = :uaca_id
		                      AND coun.cuni_estado = :estado
		                      AND coun.cuni_estado_logico = :estado";

		        $comando = $con_academico->createCommand($sql);
		        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
		        $comando->bindParam("estado", $estado, \PDO::PARAM_STR);

		        $res = $comando->queryAll();
		        return $res;
		    }
	*/

	/**
	 * Function Crear Vista de componentes
	 * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
	 * @param
	 * @return  $resultData (Crear Vista).
	 */
	public function ViewComponentexunidad() {
		$con_academico = \Yii::$app->db_academico;
		//$estado = "1";

		$sql = "create
                view componente_columna as (
                    select comp.com_id as id, coun.uaca_id as uaca_id,
                     case when comp.com_id  = 1 then 0   end as 'As??ncrona',
                     case when comp.com_id  = 2 then 0   end as 'S??ncrona',
                     case when comp.com_id  = 3 then 0   end as 'Aut??noma',
                     case when comp.com_id  = 4 then 0   end as 'Evaluaci??n',
                     case when comp.com_id  = 5 then 0   end as 'Examen',
                     case when comp.com_id  = 6 then 0   end as 'Trabajo_Final'
                     from " . $con_academico->dbname . ".componente_unidad coun
                     INNER JOIN " . $con_academico->dbname . ".componente comp ON comp.com_id = coun.com_id
              )";

		$comando = $con_academico->createCommand($sql);
		//$comando->bindParam("estado", $estado, \PDO::PARAM_STR);

		$res = $comando->queryAll();
		return $res;
	}

	/**
	 * Function Obtner los compnentes segun unidad
	 * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function getComponente($id, $columna, $nombre, $uaca_id) {
		$con_academico = \Yii::$app->db_academico;
		//$estado = "1";
		$ids = explode(",", $id);
		$columnas = explode(",", $columna);
		$alias = explode(",", $nombre);
		/*\app\models\Utilities::putMessageLogFile('iii---: ' . $id);
			        \app\models\Utilities::putMessageLogFile('ccc---: ' . $columna);
		*/

		for ($i = 0; $i < count($alias); $i++) {
			//\app\models\Utilities::putMessageLogFile('alias---: ' . $alias[$i]);
			if ($alias[$i] != ' ') {
				$columndata .= $columnas[$i] . ' as ' . $alias[$i] . ', ';
				$idsdata .= $ids[$i] . ' , ';
			}
		}
		$colmn = substr($columndata, 0, -2);
		$ides = substr($idsdata, 0, -2);
		//\app\models\Utilities::putMessageLogFile('colmn---: ' . $colmn);
		$sql = "SELECT
                    $colmn
                FROM " . $con_academico->dbname . ".componente_columna
                WHERE uaca_id = :uaca_id AND id IN (
                    $ides )";

		$comando = $con_academico->createCommand($sql);
		$comando->bindParam("uaca_id", $uaca_id, \PDO::PARAM_INT);

		$res = $comando->queryAll();
		return $res;
		/*$dataProvider = new ArrayDataProvider([
			            'key' => 'Id',
			            'allModels' => $res,
			            'pagination' => [
			                'pageSize' => Yii::$app->params["pageSize"],
			            ],
			            'sort' => [

			            ],
			        ]);

		*/
	}

	/**
	 * Retorna el supletorio de un estudiante dado los IDs del estudiante, el per??odo, el profesor, y la asignatura
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function getSupletorioPorIDs($est_id, $asi_id, $pro_id, $paca_id) {
		$con = Yii::$app->db_academico;

		$sql = "SELECT * FROM db_academico.cabecera_calificacion as ccal
                WHERE ccal.est_id = $est_id AND ccal.asi_id = $asi_id AND ccal.pro_id = $pro_id AND ccal.paca_id = $paca_id
                AND ccal.ccal_estado = 1 AND ccal.ccal_estado_logico = 1
                AND ccal.ecun_id = 3 OR ccal.ecun_id = 6 OR ccal.ecun_id = 9";

		$comando = $con->createCommand($sql);
		$resultData = $comando->queryOne();

		return $resultData;
	}

	/**
	 * Retorna el rango de fechas del cierre del periodo dependiendo del grupo de usuarios
	 * @author  Luis Cajamarca <analista04>
	 * @param
	 * @return
	 */
	public function getPeriodoCalificaciones($grupos, $paca_id) {
		$con = Yii::$app->db_academico;

		if ($grupos == 1) {
			$fecha_cierre = "case when IFNULL(paca.paca_fecha_cierre_fin,0) = 0 then paca.paca_fecha_fin else paca.paca_fecha_cierre_fin end as fin";
			$str_search = " now() >= paca.paca_fecha_inicio and now()<= case when IFNULL(paca.paca_fecha_cierre_fin,0) = 0 then paca.paca_fecha_fin else paca.paca_fecha_cierre_fin end AND ";
		} elseif ($grupos >= 6 and $grupo <= 8) {
			$fecha_cierre = "paca.paca_fecha_fin as fin";
			$str_search = " now() >= paca.paca_fecha_inicio and now()<= paca.paca_fecha_fin AND ";
		} else {
			$fecha_cierre = "paca.paca_fecha_fin as fin";
			$str_search = " now() >= paca.paca_fecha_inicio and now()<= paca.paca_fecha_fin AND ";
		}

		$sql = "SELECT paca.paca_fecha_inicio as inicio,  $fecha_cierre
                FROM db_academico.periodo_academico as paca
                WHERE  $str_search
                paca.paca_id =  $paca_id";

		$comando = $con->createCommand($sql);
		$resultData = $comando->queryOne();

		return $resultData;
	}

	/**
	 * Insertar en la tabla cabecera_calificacion
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function insertarCabeceraCalificacion($paca_id, $est_id, $pro_id, $asi_id, $ecun_id, $ccal_calificacion) {
		$con = Yii::$app->db_academico;
		$transaccion = $con->getTransaction(); // se obtiene la transacci??n actual
		if ($transaccion !== null) {
			$transaccion = null; // si existe la transacci??n entonces no se crea una
		} else {
			$transaccion = $con->beginTransaction(); // si no existe la transacci??n entonces se crea una
		}

		try
		{
			$sql = "INSERT INTO " . $con->dbname . ".cabecera_calificacion (paca_id, est_id, pro_id, asi_id, ecun_id, ccal_calificacion, ccal_fecha_creacion, ccal_estado, ccal_estado_logico) VALUES($paca_id, $est_id, $pro_id, $asi_id, $ecun_id, $ccal_calificacion, now(), 1, 1)";

			$comando = $con->createCommand($sql);

			\app\models\Utilities::putMessageLogFile($comando->getRawSql());

			$result = $comando->execute();
			$idtable = $con->getLastInsertID($con->dbname . '.cabecera_calificacion');

			if ($transaccion !== null) {
				$transaccion->commit();
			}

			return $idtable;
		} catch (Exception $ex) {
			if ($transaccion !== null) {
				$transaccion->rollback();
			}
			return 0;
		}
	} //function insertarCabeceraCalificacion

	/**
	 * Insertar en la tabla detalle_calificacion
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function insertarDetalleCalificacion($ccal_id, $cuni_id, $dcal_calificacion) {
		$con = Yii::$app->db_academico;
		$transaccion = $con->beginTransaction();

		try
		{
			$sql = "INSERT INTO " . $con->dbname . ".detalle_calificacion (ccal_id, cuni_id, dcal_calificacion, dcal_usuario_creacion, dcal_fecha_creacion, dcal_estado, dcal_estado_logico) VALUES($ccal_id, $cuni_id, $dcal_calificacion, 1, now(), 1, 1)";

			$comando = $con->createCommand($sql);
			$result = $comando->execute();
			$idtable = $con->getLastInsertID($con->dbname . '.detalle_calificacion');

			if ($transaccion !== null) {
				$transaccion->commit();
			}

			return $idtable;
		} catch (Exception $ex) {
			if ($transaccion !== null) {
				$transaccion->rollback();
			}
			return 0;
		}
	}

	/**
	 * Actualizar registro en la tabla cabecera_calificacion
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function actualizarCabeceraCalificacion($ccal_id, $cal_calif) {
		$con = Yii::$app->db_academico;
		$transaccion = $con->beginTransaction();

		try {
			$sql = "UPDATE db_academico.cabecera_calificacion SET ccal_calificacion = $cal_calif WHERE ccal_id = $ccal_id";

			$comando = $con->createCommand($sql);
			$result = $comando->execute();

			if ($transaccion !== null) {
				$transaccion->commit();
			}

			return 1;
		} catch (Exception $ex) {
			if ($transaccion !== null) {
				$transaccion->rollback();
			}
			return 0;
		}
	}

	/**
	 * Actualizar registro en la tabla detalle_calificacion
	 * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function actualizarDetalleCalificacionporcomponente($ccal_id, $key, $value) {
		$con = \Yii::$app->db_academico;
		$estado = '1';
		//$usu_id = @Yii::$app->session->get("PB_iduser");
		//$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);

		//$com_nombre = array_key_first($value);
		$sql = "UPDATE db_academico.detalle_calificacion
                   SET dcal_calificacion = $value
                 WHERE dcal_id = (
                       SELECT valor FROM(
                              SELECT dc1.dcal_id as valor
                                from db_academico.detalle_calificacion dc1,
                                     db_academico.componente_unidad cu1,
                                     db_academico.componente com1
                               where dc1.ccal_id = $ccal_id
                                 and dc1.cuni_id = cu1.cuni_id
                                 and cu1.com_id = com1.com_id
                                 and com1.com_nombre = '$key'
                               ) AS alias_tabla1
                );";

		$command = $con->createCommand($sql);
		//$command->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);
		//$command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
		$idtabla = $command->execute();

		\app\models\Utilities::putMessageLogFile('actualizarDetalleCalificacionporcomponente: ' . $command->getRawSql());
		return $idtabla;
	} //function actualizarDetalleCalificacionporid

	/**
	 * Actualizar registro en la tabla detalle_calificacion
	 * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function crearCabeceraCalificacionporcomponente($paca_id, $est_id, $pro_id, $asi_id, $ecal_id, $uaca_id) {
		$con = \Yii::$app->db_academico;
		$transaccion = $con->beginTransaction();
		//$estado = '1';
		//$usu_id = @Yii::$app->session->get("PB_iduser");

		//$com_nombre = array_key_first($value);
		$sql = "INSERT INTO " . $con->dbname . ".cabecera_calificacion
                            (
                              `paca_id`,
                              `est_id`,
                              `pro_id`,
                              `asi_id`,
                              `ecun_id`,
                              `ccal_calificacion`,
                              `ccal_estado`,
                              `ccal_fecha_creacion`,
                              `ccal_estado_logico`
                            )
                     VALUES(
                              $paca_id,
                              $est_id,
                              $pro_id,
                              $asi_id,
                              (select ecun_id FROM esquema_calificacion_unidad WHERE ecal_id = $ecal_id and uaca_id = $uaca_id),
                              0,
                              1,
                              now(),
                              1
                )";

		$command = $con->createCommand($sql);
		//$command->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);
		//$command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
		$idtabla = $command->execute();

		$idtable = $con->getLastInsertID($con->dbname . '.cabecera_calificacion');

		$transaccion->commit();

		\app\models\Utilities::putMessageLogFile('Crear Cabecera: ' . $command->getRawSql());
		return $idtable;
	} //function actualizarDetalleCalificacionporid

	/**
	 * Actualizar registro en la tabla detalle_calificacion
	 * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function crearDetalleCalificacionporcomponente($ccal_id, $key, $value, $uaca_id, $mod_id, $ecal_id) {
		$con = \Yii::$app->db_academico;
		$estado = '1';
		$usu_id = @Yii::$app->session->get("PB_iduser");

		if ($uaca_id == 1 && $mod_id == 1) {
			$strCond = "and cuni.ecal_id   = $ecal_id";
		} else {
			$strCond = "";
		}

		//$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
		if ($value == '') {

			//$com_nombre = array_key_first($value);
			$sql = "INSERT INTO `db_academico`.`detalle_calificacion`
                                (
                                   `ccal_id`,
                                   `cuni_id`,
                                   `dcal_usuario_creacion`,
                                   `dcal_estado`,
                                   `dcal_fecha_creacion`,
                                   `dcal_estado_logico`
                                )
                         VALUES (
                                    $ccal_id,
                                    (SELECT cuni.cuni_id
                                       from db_academico.componente_unidad cuni,
                                            db_academico.componente com
                                      where cuni.com_id    = com.com_id
                                        and com.com_nombre = '$key'
                                        and cuni.uaca_id   = $uaca_id
                                        and cuni.mod_id    = $mod_id
                                        $strCond ),
                                    $usu_id,
                                    1,
                                    now(),
                                    1
                                )
            ";
		} else {
			//$com_nombre = array_key_first($value);
			$sql = "INSERT INTO `db_academico`.`detalle_calificacion`
                                (
                                   `ccal_id`,
                                   `cuni_id`,
                                   `dcal_calificacion`,
                                   `dcal_usuario_creacion`,
                                   `dcal_estado`,
                                   `dcal_fecha_creacion`,
                                   `dcal_estado_logico`
                                )
                         VALUES (
                                    $ccal_id,
                                    (SELECT cuni.cuni_id
                                       from db_academico.componente_unidad cuni,
                                            db_academico.componente com
                                      where cuni.com_id = com.com_id
                                        and com.com_nombre = '$key'
                                        and cuni.uaca_id = $uaca_id
                                        and cuni.mod_id    = $mod_id
                                       $strCond ),
                                    $value,
                                    $usu_id,
                                    1,
                                    now(),
                                    1
                                )
            ";
		}
		$command = $con->createCommand($sql);
		//$command->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);
		//$command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
		$idtabla = $command->execute();

		\app\models\Utilities::putMessageLogFile('Crear Detalle: ' . $command->getRawSql());
		return $idtabla;
	} //function actualizarDetalleCalificacionporid

	/**
	 * Actualizar registro en la tabla detalle_calificacion
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function actualizarDetalleCalificacion($ccal_id, $cuni_id, $dcal_calificacion) {
		$con = Yii::$app->db_academico;
		$transaccion = $con->beginTransaction();

		try {
			$sql = "UPDATE db_academico.detalle_calificacion SET dcal_calificacion = $dcal_calificacion WHERE ccal_id = $ccal_id AND cuni_id = $cuni_id";

			$comando = $con->createCommand($sql);
			$result = $comando->execute();

			if ($transaccion !== null) {
				$transaccion->commit();
			}

			return 1;
		} catch (Exception $ex) {
			if ($transaccion !== null) {
				$transaccion->rollback();
			}
			return 0;
		}
	}

	/**
	 * Actualizar registro en la tabla detalle_calificacion
	 * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function actualizarDetalleCalificacion2($ccal_id, $dcal_calificacion) {
		$con = Yii::$app->db_academico;
		$transaccion = $con->beginTransaction();

		try {
			$sql = "UPDATE db_academico.cabecera_calificacion
                       SET ccal_calificacion = $dcal_calificacion
                     WHERE ccal_id = $ccal_id";

			$comando = $con->createCommand($sql);
			$result = $comando->execute();

			if ($transaccion !== null) {
				$transaccion->commit();
			}

			return 1;
		} catch (Exception $ex) {
			if ($transaccion !== null) {
				$transaccion->rollback();
			}
			return 0;
		}
	} //function actualizarDetalleCalificacion2

	/**
	 * @author  Didimo Zamora <analistadesarrollo03@uteg.edu.ec>
	 * @param
	 * @return
	 *  Consulta dal calificaciones de los estudiantes pot Docente y Priodo academico y  asignatura
	 */
	public function consultaCalificacionRegistroDocente($paca_id, $asi_id, $pro_id) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;
		$sql = "SELECT
                CASE
                    WHEN  resultado.uaca_id = 3  THEN
                        resultado.Parcial_I
                        ELSE ( IFNULL(resultado.Parcial_I,0) + IFNULL(resultado.Parcial_II,0))/2
                    END AS promedio_final,
                resultado.* FROM (
                        SELECT
                        ccal_id,
                        est.est_id,
                        est.est_matricula,
                        concat(per.per_pri_nombre,' ',per.per_pri_apellido) as Nombres_completos,
                        clfc.asi_id,
                        clfc.pro_id,
                        per.per_pri_apellido,
                        clfc.ccal_calificacion,
                        ecun.uaca_id,
                        (SELECT clfc3.ccal_calificacion FROM " . $con->dbname . ".cabecera_calificacion clfc3
                                INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun_sup ON ecun_sup.ecun_id = clfc3.ecun_id
                                INNER JOIN " . $con->dbname . ".esquema_calificacion esquema_calificacion1 ON esquema_calificacion1.ecal_id = ecun_sup.ecal_id
                                    WHERE
                                            esquema_calificacion1.ecal_id = 3 AND
                                            clfc3.est_id = clfc.est_id AND
                                            clfc3.pro_id = clfc.pro_id AND
                                            clfc3.asi_id = clfc.asi_id ) as 'supletorio',
                        (SELECT clfc1.ccal_calificacion FROM " . $con->dbname . ".cabecera_calificacion clfc1
                                INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun_par ON ecun_par.ecun_id = clfc1.ecun_id
                                INNER JOIN " . $con->dbname . ".esquema_calificacion esquema_calificacion1 ON esquema_calificacion1.ecal_id = ecun_par.ecal_id
                                    WHERE   clfc1.est_id = est.est_id AND
                                            clfc1.pro_id = clfc.pro_id AND
                                            clfc1.asi_id = clfc.asi_id AND
                                            esquema_calificacion1.ecal_id = 1) as 'Parcial_I',
                        (SELECT clfc2.ccal_calificacion FROM " . $con->dbname . ".cabecera_calificacion clfc2
                                INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun_par ON ecun_par.ecun_id = clfc2.ecun_id
                                INNER JOIN " . $con->dbname . ".esquema_calificacion esquema_calificacion1 ON esquema_calificacion1.ecal_id = ecun_par.ecal_id
                                    WHERE
                                            esquema_calificacion1.ecal_id = 2 AND
                                            clfc2.est_id = est.est_id AND
                                            clfc2.pro_id = clfc.pro_id AND
                                            clfc2.asi_id = clfc.asi_id ) as 'Parcial_II',
                        asignatura.asi_nombre

                    FROM  " . $con->dbname . ".cabecera_calificacion clfc
                        INNER JOIN " . $con->dbname . ".estudiante est ON clfc.est_id = est.est_id
                        INNER JOIN " . $con1->dbname . ".persona per ON per.per_id = est.per_id
                        INNER JOIN " . $con->dbname . ".asignatura  asignatura ON asignatura.asi_id = clfc.asi_id
                        INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                    WHERE   clfc.paca_id = :paca_id
                        AND clfc.asi_id = :asi_id
                        AND clfc.pro_id = :pro_id
                        AND clfc.ccal_estado = :estado
                        AND clfc.ccal_estado_logico = :estado
                        AND est.est_estado = :estado
                        AND est.est_estado_logico = :estado
                        AND per.per_estado = :estado
                        AND per.per_estado_logico = :estado
                        AND ecun.ecun_estado = :estado
                        AND ecun.ecun_estado_logico = :estado
                        GROUP BY  est.est_id
                         ) AS resultado";
		$comando = $con->createCommand($sql);
		$comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
		$comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
		$comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_INT);

		$resultData = $comando->queryAll();
		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $resultData,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => [
				],
			],
		]);
		return $dataProvider;
	}

	/**
	 * @author  Didimo Zamora <analistadesarrollo03@uteg.edu.ec>
	 * @param
	 * @return
	 *  Consulta dal calificaciones de los estudiantes pot Docente y Priodo academico y  asignatura
	 */
	public function consultaCalificacionRegistroDocenteSearch($uaca_id, $paca_id, $asi_id, $pro_id, $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;

		if ($paca_id != "" && $paca_id > 0) {
			$str_search .= " clfc.paca_id  = :paca_id AND ";
		}

		if ($asi_id != "" && $asi_id > 0) {
			$str_search .= " clfc.asi_id = :asi_id AND ";
		}

		if ($pro_id != "" && $pro_id > 0) {
			$str_search .= " clfc.pro_id =  :pro_id AND ";
		}

		if ($uaca_id != "" && $uaca_id > 0) {
			$str_search .= " ecun.uaca_id =  :uaca_id AND ";
		}

		$sql = "
            SELECT DISTINCT
                CASE
                    WHEN resultado.uaca_id = 3  THEN
                        resultado.Parcial_I
                    ELSE
                        ROUND((IFNULL(resultado.Parcial_I,0) + IFNULL(resultado.Parcial_II,0))/2,2)
                END AS promedio_final,
                resultado.* FROM (
                        SELECT DISTINCT
                        (   SELECT DISTINCT ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS value
                            FROM " . $con->dbname . ".semestre_academico AS saca
                            INNER JOIN " . $con->dbname . ".periodo_academico AS paca ON saca.saca_id = paca.saca_id
                            INNER JOIN " . $con->dbname . ".bloque_academico AS baca ON baca.baca_id = paca.baca_id
                            WHERE
                            paca_id = :paca_id AND
                            paca.paca_activo = 'A' AND
                            paca.paca_estado = 1 AND
                            paca.paca_estado_logico = 1 AND
                            saca.saca_estado = 1 AND
                            saca.saca_estado_logico = 1 AND
                            baca.baca_estado = 1 AND
                            baca.baca_estado_logico = 1
                        )  as paca_nombre,
                        ccal_id,
                        est.est_id,
                        est.est_matricula,
                        concat(per.per_pri_nombre,' ',per.per_pri_apellido) as Nombres_completos,
                        clfc.asi_id,
                        clfc.pro_id,
                        per.per_pri_apellido,
                        clfc.ccal_calificacion,
                        ecun.uaca_id,
                        IFNULL(
                        (   SELECT DISTINCT clfc3.ccal_calificacion FROM " . $con->dbname . ".cabecera_calificacion clfc3
                            INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun_par3 ON ecun_par3.ecun_id = clfc3.ecun_id
                            INNER JOIN " . $con->dbname . ".esquema_calificacion esquema_calificacion3 ON esquema_calificacion3.ecal_id = ecun_par3.ecal_id
                            WHERE
                            clfc3.est_id = clfc.est_id AND
                            clfc3.pro_id = clfc.pro_id AND
                            clfc3.asi_id = clfc.asi_id AND

                            clfc3.ccal_estado = :estado         AND
                            clfc3.ccal_estado_logico = :estado  AND
                            ecun_par3.ecun_estado = :estado          AND
                            ecun_par3.ecun_estado_logico = :estado   AND
                            esquema_calificacion3.ecal_estado = :estado  AND
                            esquema_calificacion3.ecal_estado_logico = :estado  AND

                            esquema_calificacion3.ecal_id = 3 AND

                            ecun_par3.uaca_id = :uaca_id ),'NN'
                        ) as 'supletorio',
                        (   SELECT DISTINCT clfc1.ccal_calificacion FROM " . $con->dbname . ".cabecera_calificacion clfc1
                            INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun_par ON ecun_par.ecun_id = clfc1.ecun_id
                            INNER JOIN " . $con->dbname . ".esquema_calificacion esquema_calificacion1 ON esquema_calificacion1.ecal_id = ecun_par.ecal_id
                            WHERE
                            clfc1.est_id = est.est_id AND
                            clfc1.pro_id = clfc.pro_id AND
                            clfc1.asi_id = clfc.asi_id AND
                            clfc1.ccal_estado = :estado AND
                            clfc1.ccal_estado_logico = :estado AND
                            ecun_par.ecun_estado = :estado AND
                            ecun_par.ecun_estado_logico = :estado AND
                            esquema_calificacion1.ecal_estado = :estado AND
                            esquema_calificacion1.ecal_estado_logico = :estado AND
                            esquema_calificacion1.ecal_id = 1 AND
                            ecun_par.uaca_id = :uaca_id
                        ) as 'Parcial_I',
                        (   SELECT DISTINCT clfc2.ccal_calificacion FROM " . $con->dbname . ".cabecera_calificacion clfc2
                            INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun_par2 ON ecun_par2.ecun_id = clfc2.ecun_id
                            INNER JOIN " . $con->dbname . ".esquema_calificacion esquema_calificacion2 ON esquema_calificacion2.ecal_id = ecun_par2.ecal_id
                            WHERE
                            clfc2.est_id = est.est_id AND
                            clfc2.pro_id = clfc.pro_id AND
                            clfc2.asi_id = clfc.asi_id  AND

                            clfc2.ccal_estado = :estado         AND
                            clfc2.ccal_estado_logico = :estado  AND
                            ecun_par2.ecun_estado = :estado          AND
                            ecun_par2.ecun_estado_logico = :estado   AND
                            esquema_calificacion2.ecal_estado = :estado  AND
                            esquema_calificacion2.ecal_estado_logico = :estado AND
                            esquema_calificacion2.ecal_id = 2 AND
                            ecun_par2.uaca_id = :uaca_id
                        ) as 'Parcial_II',
                        (SELECT DISTINCT casi.casi_porc_total
                         FROM db_academico.cabecera_asistencia casi
                            INNER JOIN db_academico.asistencia_esquema_unidad aeun_id_asistencia ON aeun_id_asistencia.aeun_id = casi.aeun_id
                            INNER JOIN db_academico.esquema_calificacion_unidad esquema_calificacion_unidad ON esquema_calificacion_unidad.ecun_id = aeun_id_asistencia.aeun_id
                            INNER JOIN db_academico.esquema_calificacion esquema_calificacion_asistencia ON  esquema_calificacion_asistencia.ecal_id = esquema_calificacion_unidad.ecal_id
                            WHERE   casi.paca_id = clfc.paca_id and
                                    casi.est_id = clfc.est_id  and
                                    casi.pro_id = clfc.pro_id and
                                     casi.asi_id = clfc.asi_id AND
                                     esquema_calificacion_unidad.uaca_id = :uaca_id AND
                                     esquema_calificacion_asistencia.ecal_id = 1 and
                                     casi.casi_estado = 1 and
                                     casi.casi_estado_logico = 1 AND
                                     aeun_id_asistencia.aeun_estado = 1 AND
                                     aeun_id_asistencia.aeun_estado_logico = 1 and
                                     esquema_calificacion_unidad.ecun_estado = 1 and
                                     esquema_calificacion_unidad.ecun_estado_logico = 1 and
                                     esquema_calificacion_asistencia.ecal_estado = 1 and
                                     esquema_calificacion_asistencia.ecal_estado_logico = 1
                        ) as asistencia_parcial_I,
                        (SELECT DISTINCT casi.casi_porc_total
                         FROM db_academico.cabecera_asistencia casi
                            INNER JOIN db_academico.asistencia_esquema_unidad aeun_id_asistencia ON aeun_id_asistencia.aeun_id = casi.aeun_id
                            INNER JOIN db_academico.esquema_calificacion_unidad esquema_calificacion_unidad ON esquema_calificacion_unidad.ecun_id = aeun_id_asistencia.aeun_id
                            INNER JOIN db_academico.esquema_calificacion esquema_calificacion_asistencia ON  esquema_calificacion_asistencia.ecal_id = esquema_calificacion_unidad.ecal_id
                            WHERE   casi.paca_id = clfc.paca_id and
                                    casi.est_id = clfc.est_id  and
                                    casi.pro_id = clfc.pro_id and
                                     casi.asi_id = clfc.asi_id AND
                                     esquema_calificacion_unidad.uaca_id = :uaca_id AND
                                     esquema_calificacion_asistencia.ecal_id = 2 and
                                     casi.casi_estado = 1 and
                                     casi.casi_estado_logico = 1 AND
                                     aeun_id_asistencia.aeun_estado = 1 AND
                                     aeun_id_asistencia.aeun_estado_logico = 1 and
                                     esquema_calificacion_unidad.ecun_estado = 1 and
                                     esquema_calificacion_unidad.ecun_estado_logico = 1 and
                                     esquema_calificacion_asistencia.ecal_estado = 1 and
                                     esquema_calificacion_asistencia.ecal_estado_logico = 1
                        ) as asistencia_parcial_II,
                        asignatura.asi_nombre
                    FROM  " . $con->dbname . ".estudiante est
                        LEFT JOIN " . $con->dbname . ".cabecera_calificacion clfc ON clfc.est_id = est.est_id
                        INNER JOIN " . $con1->dbname . ".persona per ON per.per_id = est.per_id
                        INNER JOIN " . $con->dbname . ".asignatura  asignatura ON asignatura.asi_id = clfc.asi_id
                        INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                        INNER JOIN " . $con->dbname . ".distributivo_academico daca on daca.pro_id = clfc.pro_id
                        INNER JOIN " . $con->dbname . ".distributivo_academico_estudiante daca_est ON daca_est.daca_id = daca.daca_id

                    WHERE
                        $str_search
                        daca_est.est_id = clfc.est_id
                        AND daca.paca_id =  clfc.paca_id
                        AND daca.asi_id =   clfc.asi_id
                        AND daca.pro_id =   clfc.pro_id

                        AND clfc.ccal_estado = :estado
                        AND clfc.ccal_estado_logico = :estado
                        AND est.est_estado = :estado
                        AND est.est_estado_logico = :estado
                        AND per.per_estado = :estado
                        AND per.per_estado_logico = :estado
                        AND ecun.ecun_estado = :estado
                        AND ecun.ecun_estado_logico = :estado

                        AND asignatura.asi_estado = 1
                        AND asignatura.asi_estado_logico = 1
                        AND daca.daca_estado  = 1
                        AND daca.daca_estado_logico = 1
                        AND daca_est.daes_estado = 1
                        AND daca_est.daes_estado_logico = 1

                ) AS resultado
                ORDER BY Nombres_completos";

		//\app\models\Utilities::putMessageLogFile('consultaCalificacionRegistroDocenteSearch Sql: '.$sql);

		$comando = $con->createCommand($sql);

		if ($paca_id != "" && $paca_id > 0) {
			//$periodo = $arrFiltro["periodo"];
			$comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
		}

		if ($asi_id != "" && $asi_id > 0) {
			//$materia = $arrFiltro["materia"];
			$comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
		}

		if ($pro_id != "" && $pro_id > 0) {
			//$profesor = $arrFiltro["profesor"];
			$comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
		}
		if ($uaca_id != "" && $uaca_id > 0) {
			//$profesor = $arrFiltro["profesor"];
			$comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
		}

		$comando->bindParam(":estado", $estado, \PDO::PARAM_INT);

		// \app\models\Utilities::putMessageLogFile('consultaCalificacionRegistroDocenteSearch: '.$comando->getRawSql());

		$resultData = $comando->queryAll();

		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $resultData,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => [
				],
			],
		]);
		if ($onlyData) {
			return $resultData;
		} else {
			return $dataProvider;
		}

	}

	/**
	 * Function Obtiene informacion de estudiante segun profesor, unidad, asug etc.
	 * Modificaci??n, se implemento rango de fechas para cada periodo academico para los diferentes grupos
	 * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
	 * @modify Luis Cajamarca <analista04>
	 * @param
	 * @return  $resultData (Retornar los datos).
	 */
	public function getRegistroCalificaciones($arrFiltro) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$str_search = "";
		$estado = "1";

		// \app\models\Utilities::putMessageLogFile($arrFiltro);

		$arr_componentes = $this->getComponenteUnidadarr($arrFiltro['unidad'], $arrFiltro['modalidad'], $arrFiltro['parcial']);

		if (isset($arrFiltro) && count($arrFiltro) > 0) {

			if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
				$str_search .= " AND data.paca_id = :paca_id  ";
			}
			if ($arrFiltro['materia'] != "" && $arrFiltro['materia'] > 0) {
				$str_search .= " AND data.asi_id = :asi_id  ";
			}
			if ($arrFiltro['profesor'] != "" && $arrFiltro['profesor'] > 0) {
				$str_search .= " AND data.pro_id = :pro_id  ";
			}
			if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
				$str_search .= " AND data.uaca_id = :uaca_id  ";
			}
			if ($arrFiltro['parcial'] != "" && $arrFiltro['parcial'] > 0) {
				$str_search .= " AND data.nparcial = (select ecal_nombre FROM esquema_calificacion WHERE ecal_id = :ecal_id)  ";
			}
			if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
				$str_search .= " AND data.mod_id = :mod_id  ";
			}
			if ($arrFiltro['grupo'] != "" && $arrFiltro['grupo'] > 0) {
				/*if ($arrFiltro['grupo'] == 1) {
					                    $str_search .= " AND now()>=data.paca_fecha_inicio and now()<=data.paca_fecha_cierre_fin ";
				*/if ($arrFiltro['grupo'] >= 6 and $arrFiltro['grupo'] <= 8) {
					$str_search .= " AND now()>=data.paca_fecha_inicio and now()<=data.paca_fecha_fin ";
				}
			}

		}

		$sql = "SELECT (@row_number:=@row_number + 1) AS row_num,  data.*
                  FROM (
                 SELECT distinct daes.est_id
                        ,ifnull(est.est_matricula,'') as matricula
                        ,concat(ifnull(trim(per.per_pri_apellido),''),' ',ifnull(trim(per.per_seg_apellido),''),' ',ifnull(trim(per.per_pri_nombre),'')) as nombre
                        ,CASE WHEN ecun.ecal_id = 1 THEN 'Parcial I'
                              WHEN ecun.ecal_id = 2 THEN 'Parcial II'
                              WHEN ecun.ecal_id = 3 THEN 'Supletorio'
                              WHEN ecun.ecal_id = 4 THEN 'Mejoramiento'
                        END as nparcial
                        ,ecun.ecal_id as ecal_id
                        ,(  SELECT ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS paca_nombre
                              FROM db_academico.semestre_academico AS saca
                        INNER JOIN db_academico.periodo_academico AS paca
                                ON saca.saca_id = paca.saca_id
                               AND paca.paca_estado = 1
                               AND paca.paca_estado_logico = 1
                        INNER JOIN db_academico.bloque_academico AS baca
                                ON baca.baca_id = paca.baca_id
                               AND baca.baca_estado = 1
                               AND baca.baca_estado_logico = 1
                             WHERE paca.paca_activo = 'A'
                               AND saca.saca_estado = 1
                               AND saca.saca_estado_logico = 1
                               AND paca.paca_id = daca.paca_id) as periodo
                        ,asi.asi_id
                        ,asi.asi_descripcion as materia
                        ,coalesce(clfc.ccal_id,0) as ccal_id
                        ,case when daca.uaca_id= 1 then CONCAT('P - ',ifnull(mpp.mpp_num_paralelo,''))
                             when daca.uaca_id= 2 then ifnull(pp.ppro_grupo,'')
                        end as paralelo
        ";

		foreach ($arr_componentes as $key => $value) {
			$sql .= "   ,(SELECT dc1.dcal_calificacion
                            FROM " . $con->dbname . ".detalle_calificacion dc1,
                                 " . $con->dbname . ".componente_unidad cu1,
                                 " . $con->dbname . ".componente com1
                           WHERE dc1.ccal_id = clfc.ccal_id
                             AND dc1.cuni_id = cu1.cuni_id
                             AND cu1.com_id  = com1.com_id
                             AND com1.com_nombre = '" . $value['nombre'] . "') as '" . $value['nombre'] . "' ";
		} //foreach

		$sql .= ",(SELECT sum(dc1.dcal_calificacion)
                            FROM " . $con->dbname . ".detalle_calificacion dc1,
                                 " . $con->dbname . ".componente_unidad cu1,
                                 " . $con->dbname . ".componente com1
                           WHERE dc1.ccal_id = clfc.ccal_id
                             AND dc1.cuni_id = cu1.cuni_id
                             AND cu1.com_id  = com1.com_id ) as 'total'
                        ,daca.paca_id as paca_id
                        ,daca.pro_id  as pro_id
                        ,daca.mod_id  as mod_id
                        ,meun.uaca_id as uaca_id
                        ,paca.paca_activo
                        ,paca.paca_fecha_inicio
                        ,paca.paca_fecha_fin
                        ,paca.paca_fecha_cierre_fin
                   FROM " . $con->dbname . ".distributivo_academico daca
             INNER JOIN " . $con->dbname . ".distributivo_academico_estudiante daes
                     ON daes.daca_id = daca.daca_id
                    AND daes.daes_estado = 1 AND daes.daes_estado_logico = 1
                    AND daca.daca_estado = 1 AND daca.daca_estado_logico = 1
             INNER JOIN " . $con->dbname . ".periodo_academico paca
                     ON daca.paca_id = paca.paca_id
                    AND paca.paca_estado = 1 AND paca.paca_estado_logico = 1
              LEFT JOIN " . $con->dbname . ".materia_paralelo_periodo mpp
                     ON mpp.mpp_id = daca.mpp_id
                    AND mpp.mpp_estado = 1 AND mpp.mpp_estado_logico = 1
              LEFT JOIN " . $con->dbname . ".paralelo_promocion_programa pppr
                     on pppr.pppr_id = daca.pppr_id
                    AND pppr.pppr_estado = 1 AND pppr.pppr_estado_logico = 1
              LEFT JOIN " . $con->dbname . ".promocion_programa pp
                     ON pp.ppro_id = pppr.ppro_id
                    AND pp.ppro_estado = 1 AND pp.ppro_estado_logico = 1
              LEFT JOIN " . $con->dbname . ".estudiante est
                     ON est.est_id   = daes.est_id
                    AND est.est_estado = 1 AND est.est_estado_logico = 1
             INNER JOIN " . $con1->dbname . ".persona per
                     ON per.per_id   = est.per_id
                    AND per.per_estado = 1 AND per.per_estado_logico = 1
              LEFT JOIN " . $con->dbname . ".estudiante_carrera_programa ecpr
                     ON ecpr.est_id = est.est_id
                    AND ecpr.ecpr_estado = 1 AND ecpr.ecpr_estado_logico = 1
             INNER JOIN " . $con->dbname . ".modalidad_estudio_unidad meun
                     ON meun.meun_id = ecpr.meun_id
                    AND meun.meun_estado = 1 AND meun.meun_estado_logico = 1
             INNER JOIN " . $con->dbname . ".asignatura AS asi
                     ON asi.asi_id = daca.asi_id
                    AND asi.uaca_id = meun.uaca_id
                    AND asi.asi_estado = 1 AND asi.asi_estado_logico = 1
              LEFT JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun
                     ON ecun.uaca_id = meun.uaca_id
                    AND ecun.ecun_estado = 1 AND ecun.ecun_estado_logico = 1
              LEFT JOIN " . $con->dbname . ".cabecera_calificacion clfc
                     ON clfc.est_id  = est.est_id
                    AND clfc.pro_id  = daca.pro_id
                    AND clfc.paca_id = daca.paca_id
                    AND clfc.asi_id  = asi.asi_id
                    AND clfc.ecun_id = ecun.ecun_id
                  WHERE ecun.ecal_id = 1 or ecun.ecal_id = 2 or ecun.ecal_id = 3 or ecun.ecal_id = 4

            ) as data
            ,(SELECT @row_number:=0) AS t
            WHERE 1=1
                  $str_search
            Order by data.nombre asc
            ";

		$comando = $con->createCommand($sql);

		//$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

		if (isset($arrFiltro) && count($arrFiltro) > 0) {

			if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
				$periodo = $arrFiltro["periodo"];
				$comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
			}
			if ($arrFiltro['materia'] != "" && $arrFiltro['materia'] > 0) {
				$materia = $arrFiltro["materia"];
				$comando->bindParam(":asi_id", $materia, \PDO::PARAM_INT);
			}
			if ($arrFiltro['profesor'] != "" && $arrFiltro['profesor'] > 0) {
				$profesor = $arrFiltro["profesor"];
				$comando->bindParam(":pro_id", $profesor, \PDO::PARAM_INT);
			}
			if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
				$unidad = $arrFiltro["unidad"];
				$comando->bindParam(":uaca_id", $unidad, \PDO::PARAM_INT);
			}
			if ($arrFiltro['parcial'] != "" && $arrFiltro['parcial'] > 0) {
				$parcial = $arrFiltro["parcial"];
				$comando->bindParam(":ecal_id", $parcial, \PDO::PARAM_INT);
			}
			if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
				$modalidad = $arrFiltro["modalidad"];
				$comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
			}

		}

		$res = $comando->queryAll();

		\app\models\Utilities::putMessageLogFile($comando->getRawSql());

		return $res;
	} //function getRegistroCalificaciones

	/**
	 * @author  Didimo Zamora <analistadesarrollo03@uteg.edu.ec>
	 * @param
	 * @return
	 *  Consulta dal calificaciones de los estudiantes pot Docente y Priodo academico y  asignatura
	 */
	public function consultaCalificacionRegistroDocenteAllSearch($uaca_id, $paca_id, $asi_id, $pro_id, $mod_id, $est = null, $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;

		if ($paca_id != "" && $paca_id > 0) {
			$str_search .= " daca.paca_id  = :paca_id AND ";
		}

		if ($asi_id != "" && $asi_id > 0) {
			//$str_search .= " clfc.asi_id = :asi_id AND ";
			$str_search .= " daca.asi_id = :asi_id AND ";
		}

		if ($pro_id != "" && $pro_id > 0) {
			$str_search .= " daca.pro_id =  :pro_id AND ";
		}

		if ($uaca_id != "" && $uaca_id > 0) {
			//$str_search .= " ecun.uaca_id =  :uaca_id AND ";
			$str_search .= " (ecun.uaca_id = :uaca_id OR ecun.uaca_id IS NULL) AND";
		}

		if ($mod_id != "" && $mod_id > 0) {
			$str_search .= " daca.mod_id  = :mod_id AND ";
		}

		if ($est != "" && $est > 0) {
			$str_search .= " estudiante.est_id  = $est AND ";
		}

		$sql = "SELECT DISTINCT
                        estudiante.est_id,
                        estudiante.est_matricula,
                        estudiante.Nombres_completos,
                        estudiante.paca_nombre,
                        estudiante.pro_id,
                        estudiante.asi_id,
                        estudiante.asi_nombre,
                        IFNULL(A.PARCIAL_I,'0') parcial_1,
                        IFNULL(B.PARCIAL_II,'0') parcial_2,
                        IFNULL(C.SUPLETORIO,'0') supletorio,
                        IFNULL(F.MEJORAMIENTO,'0') mejoramiento,
                        CASE
                        WHEN estudiante.uaca_id = 3 THEN
                             IFNULL(A.PARCIAL_I,'0')
                        ELSE
                            case
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=14.50 then
                                case when IFNULL(F.MEJORAMIENTO,0) > 0 then
                                    case
                                        when IFNULL(A.PARCIAL_I,0) >= IFNULL(B.PARCIAL_II,0) then (IFNULL(A.PARCIAL_I,0) + IFNULL(F.MEJORAMIENTO,0))/2
                                        when IFNULL(A.PARCIAL_I,0) <= IFNULL(B.PARCIAL_II,0) then (IFNULL(B.PARCIAL_II,0) + IFNULL(F.MEJORAMIENTO,0))/2
                                    end
                                else (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2
                                end
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 > 0 and (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 < 14.50 then
                                case
                                when IFNULL(C.SUPLETORIO,0) > 0 then
                                    (((IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2)+IFNULL(C.SUPLETORIO,0))/2
                                else
                                    (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2
                                end
                            end
                        END AS promedio_final,
                        case
                            when ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) >= 75 then

                                case
                                    when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=14.50 then
                                        case when IFNULL(F.MEJORAMIENTO,0) > 0 then
                                            case
                                                when IFNULL(A.PARCIAL_I,0) >= IFNULL(B.PARCIAL_II,0) then
                                                case
                                                    when (IFNULL(A.PARCIAL_I,0) + IFNULL(F.MEJORAMIENTO,0))/2 >= 14.50 then 'Aprobado'
                                                end
                                                when IFNULL(A.PARCIAL_I,0) <= IFNULL(B.PARCIAL_II,0) then
                                                case
                                                    when (IFNULL(B.PARCIAL_II,0) + IFNULL(F.MEJORAMIENTO,0))/2 >= 14.50 then 'Aprobado'
                                                end
                                            end
                                        else 'Aprobado'
                                        end
                                    when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 > 0 and (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 < 14.50 then
                                        case
                                            when IFNULL(C.SUPLETORIO,0) > 0 then

                                                case
                                                    when (((IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2)+IFNULL(C.SUPLETORIO,0))/2 >= 14.50 then 'Aprobado'
                                                    when (((IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2)+IFNULL(C.SUPLETORIO,0))/2 > 0 and (((IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2)+IFNULL(C.SUPLETORIO,0))/2 < 14.50 then 'Reprobado'
                                                end

                                        else
                                            case when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 < 14.50 then 'Reprobado' end
                                        end
                                    when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 = 0 then 'Pendiente'
                                end
                            when ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) > 0 and ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) < 75 then
                                case
                                when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=14.50 then 'Reprobado'
                                when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >0 and (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 < 14.50 then 'Reprobado'
                                end
                            else 'Pendiente'

                        end as estado_academico,

                        IFNULL(D.ASISTENCIA_PARCIAL_I,'0') asistencia_parcial_1,
                        IFNULL(E.ASISTENCIA_PARCIAL_II,'0') asistencia_parcial_2,
                        ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) as asistencia_final,
                        case
                            when ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) >= 75 then 'Aprobado'
                            when ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) >= 1 and ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) < 75 then 'Reprobado'
                            when ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) = 0 then'Pendiente'
                        end as estado_asist
                 FROM
                    (
                        SELECT DISTINCT
                               daca_est.est_id,
                               estudiante.est_matricula,
                               concat(ifnull(trim(persona.per_pri_apellido),''),' ',ifnull(trim(persona.per_seg_apellido),''),' ',ifnull(trim(persona.per_pri_nombre),'')) as Nombres_completos,
                               ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS paca_nombre,
                               paca.paca_id,
                               daca.pro_id,
                               asignatura.asi_id,
                               meun.uaca_id as uaca_id,
                               asignatura.asi_descripcion as asi_nombre
                            FROM db_academico.estudiante estudiante
                            LEFT JOIN db_academico.cabecera_calificacion clfc ON estudiante.est_id =  clfc.est_id
                            LEFT JOIN db_academico.esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                            INNER JOIN db_asgard.persona persona ON persona.per_id = estudiante.per_id
                            INNER JOIN db_academico.distributivo_academico_estudiante daca_est ON daca_est.est_id = estudiante.est_id
                            INNER JOIN db_academico.distributivo_academico daca ON daca.daca_id = daca_est.daca_id
                            LEFT JOIN db_academico.asignatura asignatura ON asignatura.asi_id = daca.asi_id
                            INNER JOIN db_academico.estudiante_carrera_programa AS ecpr ON ecpr.est_id = estudiante.est_id
                            INNER JOIN db_academico.modalidad_estudio_unidad AS meun ON meun.meun_id = ecpr.meun_id
                            INNER JOIN db_academico.periodo_academico AS paca ON paca.paca_id = daca.paca_id
                            INNER JOIN db_academico.semestre_academico AS saca ON saca.saca_id = paca.saca_id
                            INNER JOIN db_academico.bloque_academico AS baca ON baca.baca_id = paca.baca_id
                        WHERE
                            $str_search
                            /*meun.uaca_id = asignatura.uaca_id
                            AND */paca.paca_activo = 'A'
                            AND persona.per_estado= 1
                            AND persona.per_estado_logico = 1
                            AND daca.daca_estado = 1
                            AND estudiante.est_estado = 1
                            AND estudiante.est_estado_logico = 1
                            AND persona.per_estado = 1
                            AND persona.per_estado_logico = 1
                            AND daca.daca_estado_logico = 1
                            AND daca_est.daes_estado = 1
                            AND daca_est.daes_estado_logico = 1
                            AND asignatura.asi_estado = 1
                            AND asignatura.asi_estado_logico = 1
                            AND ecpr.ecpr_estado = 1
                            AND ecpr.ecpr_estado_logico = 1
                            AND meun.meun_estado= 1
                            AND meun.meun_estado_logico = 1
                            AND paca.paca_estado = 1
                            AND paca.paca_estado_logico = 1
                        ) estudiante
                            LEFT JOIN
                              (
                                SELECT clfc.ccal_id, clfc.paca_id, clfc.est_id, clfc.asi_id,ecun.uaca_id,clfc.pro_id,clfc.ccal_calificacion AS PARCIAL_I
                                FROM db_academico.cabecera_calificacion clfc
                                INNER JOIN db_academico.esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                                INNER JOIN db_academico.esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                                WHERE ecal.ecal_id = 1 AND clfc.ccal_estado = 1 AND clfc.ccal_estado_logico = 1
                              ) A  on  estudiante.est_id = A.est_id
                                               AND estudiante.paca_id = A.paca_id
                                               AND estudiante.pro_id  = A.pro_id
                                               AND estudiante.asi_id  = A.asi_id
                                               AND estudiante.uaca_id = A.uaca_id
                            LEFT JOIN
                                  (
                                    SELECT clfc.ccal_id, clfc.paca_id, clfc.est_id, clfc.asi_id,ecun.uaca_id,clfc.pro_id,ecal.ecal_descripcion  ,clfc.ccal_calificacion AS PARCIAL_II
                                    FROM db_academico.cabecera_calificacion clfc
                                    INNER JOIN db_academico.esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                                    INNER JOIN db_academico.esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                                    WHERE ecal.ecal_id = 2 AND clfc.ccal_estado = 1 AND clfc.ccal_estado_logico = 1
                                  ) B  ON estudiante.est_id = B.est_id
                                                AND estudiante.paca_id = B.paca_id
                                                AND estudiante.pro_id  = B.pro_id
                                                AND estudiante.asi_id  = B.asi_id
                                                AND estudiante.uaca_id = B.uaca_id
                            LEFT JOIN
                                  (
                                    SELECT clfc.ccal_id, clfc.paca_id, clfc.est_id, clfc.asi_id,ecun.uaca_id,clfc.pro_id,ecal.ecal_descripcion  ,clfc.ccal_calificacion AS SUPLETORIO
                                    FROM db_academico.cabecera_calificacion clfc
                                    INNER JOIN db_academico.esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                                    INNER JOIN db_academico.esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                                    WHERE ecal.ecal_id = 3 AND clfc.ccal_estado = 1 AND clfc.ccal_estado_logico = 1
                                  ) C ON estudiante.est_id = C.est_id
                                                AND estudiante.paca_id = C.paca_id
                                                AND estudiante.pro_id  = C.pro_id
                                                AND estudiante.asi_id  = C.asi_id
                                                AND estudiante.uaca_id = C.uaca_id
                            LEFT JOIN
                                  (
                                    SELECT  casi.casi_id, casi.paca_id, casi.est_id,casi.asi_id,esquema_calificacion_unidad.uaca_id,casi.pro_id, casi.casi_porc_total*100 as ASISTENCIA_PARCIAL_I
                                        FROM db_academico.cabecera_asistencia casi
                                        INNER JOIN db_academico.asistencia_esquema_unidad aeun_id_asistencia ON aeun_id_asistencia.aeun_id = casi.aeun_id
                                        INNER JOIN db_academico.esquema_calificacion_unidad esquema_calificacion_unidad ON esquema_calificacion_unidad.ecun_id = aeun_id_asistencia.ecun_id
                                        INNER JOIN db_academico.esquema_calificacion esquema_calificacion_asistencia ON esquema_calificacion_asistencia.ecal_id = esquema_calificacion_unidad.ecal_id
                                  WHERE esquema_calificacion_asistencia.ecal_id = 1
                                  AND casi.casi_estado = 1 AND casi.casi_estado_logico = 1
                                  ) D ON  estudiante.est_id = D.est_id  AND estudiante.paca_id = D.paca_id
                                                AND estudiante.pro_id  = D.pro_id
                                                AND estudiante.asi_id  = D.asi_id
                                                AND estudiante.uaca_id = D.uaca_id
                            LEFT JOIN
                                  (
                                    SELECT  casi.casi_id, casi.paca_id, casi.est_id,casi.asi_id,esquema_calificacion_unidad.uaca_id,casi.pro_id, casi.casi_porc_total*100 as ASISTENCIA_PARCIAL_II
                                        FROM db_academico.cabecera_asistencia casi
                                        INNER JOIN db_academico.asistencia_esquema_unidad aeun_id_asistencia ON aeun_id_asistencia.aeun_id = casi.aeun_id
                                        INNER JOIN db_academico.esquema_calificacion_unidad esquema_calificacion_unidad ON esquema_calificacion_unidad.ecun_id = aeun_id_asistencia.ecun_id
                                        INNER JOIN db_academico.esquema_calificacion esquema_calificacion_asistencia ON esquema_calificacion_asistencia.ecal_id = esquema_calificacion_unidad.ecal_id
                                  WHERE esquema_calificacion_asistencia.ecal_id = 2
                                  AND casi.casi_estado = 1 AND casi.casi_estado_logico = 1
                                  ) E ON  estudiante.est_id = E.est_id  AND estudiante.paca_id = E.paca_id
                                                AND estudiante.pro_id  = E.pro_id
                                                AND estudiante.asi_id  = E.asi_id
                                                AND estudiante.uaca_id = E.uaca_id
                            LEFT JOIN
                              (
                                SELECT clfc.ccal_id, clfc.paca_id, clfc.est_id, clfc.asi_id,ecun.uaca_id,clfc.pro_id,clfc.ccal_calificacion AS MEJORAMIENTO FROM
                                        db_academico.cabecera_calificacion clfc
                                 INNER JOIN db_academico.esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                                 INNER JOIN db_academico.esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                                 WHERE   ecal.ecal_id = 4 AND clfc.ccal_estado = 1 AND clfc.ccal_estado_logico = 1
                               ) F  on  estudiante.est_id = F.est_id
                                               AND estudiante.paca_id = F.paca_id
                                               AND estudiante.pro_id  = F.pro_id
                                               AND estudiante.asi_id  = F.asi_id
                                               AND estudiante.uaca_id = F.uaca_id
                            Order By Nombres_completos asc
                                                ";

		$comando = $con->createCommand($sql);

		if ($paca_id != "" && $paca_id > 0) {
			//$periodo = $arrFiltro["periodo"];
			$comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
		}

		if ($asi_id != "" && $asi_id > 0) {
			//$materia = $arrFiltro["materia"];
			$comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
		}

		if ($pro_id != "" && $pro_id > 0) {
			//$profesor = $arrFiltro["profesor"];
			$comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
		}
		if ($uaca_id != "" && $uaca_id > 0) {
			//$profesor = $arrFiltro["profesor"];
			$comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
		}
		if ($mod_id != "" && $mod_id > 0) {
			//$profesor = $arrFiltro["profesor"];
			$comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
		}

		$resultData = $comando->queryAll();

		\app\models\Utilities::putMessageLogFile('consultaCalificacionRegistroDocenteAllSearch: ' . $comando->getRawSql());

		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $resultData,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => [
				],
			],
		]);
		if ($onlyData) {
			return $resultData;
		} else {
			return $dataProvider;
		}

	}

	/**
	 * @author  Julio Lopez <analistadesarrollo03@uteg.edu.ec>
	 * @param
	 * @return
	 *  Consulta dal calificaciones de los estudiantes segun unidad, modalidad, programa, y periodo.
	 */
	public function consultaCalificacionRegistroDocenteAllStudentSearch($search, $per_id, $onlyData = false) {
		$con = Yii::$app->db_academico;
		$con1 = Yii::$app->db_asgard;

		if (!empty($search) && count($search) > 0) {
			if ($search['periodo'] != "" && $search['periodo'] != "0") {
				\app\models\Utilities::putMessageLogFile('1548      $search[periodo]:  ' . $search['periodo']);
				//$str_search .= " paca.paca_id = :periodo AND ";
				$str_search .= " paca.paca_id = " . $search['periodo'] . " AND ";
			}
			if ($search['unidad'] != "" && $search['unidad'] != "0") {
				\app\models\Utilities::putMessageLogFile('1548      $search[unidad]:  ' . $search['unidad']);
				//$str_search .= " ( ecun.uaca_id = :unidad OR ecun.uaca_id IS NULL ) AND ";
				$str_search .= " meun.uaca_id = " . $search['unidad'] . " AND ";
			}
			if ($search['carrera'] != "" && $search['carrera'] != "0") {
				// \app\models\Utilities::putMessageLogFile('1519      $search[carrera]:  '.$search['carrera']);
				//$str_search .= " meun.eaca_id = :carrera AND ";
				$str_search .= " meun.eaca_id = " . $search['carrera'] . " AND ";
			}
			if ($search['modalidad'] != "" && $search['modalidad'] != "0") {
				// \app\models\Utilities::putMessageLogFile('1519      $search[carrera]:  '.$search['carrera']);
				$str_search .= " meun.mod_id = " . $search['modalidad'] . " AND ";
			}
		}

		if ($per_id != "" && $per_id > 0) {
			//$str_perfil_user .= " persona.per_id = :per_id AND";
			$str_perfil_user .= " persona.per_id = " . $per_id . " AND "; //GALO
		}

		$sql = "SELECT DISTINCT
                estudiante.est_id,
                estudiante.est_matricula,
                estudiante.nombre,
                estudiante.paca_nombre as periodo,
                estudiante.paca_id,
                estudiante.pro_id,
                estudiante.profesor,
                estudiante.asi_id,
                estudiante.asi_nombre as materia,
                IFNULL(A.PARCIAL_I,'0') parcial_1,
                IFNULL(B.PARCIAL_II,'0') parcial_2,
                IFNULL(C.SUPLETORIO,'0') supletorio,
                CASE
                        WHEN estudiante.uaca_id = 3 THEN
                             IFNULL(A.PARCIAL_I,'0')
                        ELSE
                            case
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=14.50 then (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 > 0 and (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 < 14.50 then
                                case
                                when IFNULL(C.SUPLETORIO,0) > 0 then
                                    (((IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2)+IFNULL(C.SUPLETORIO,0))/2
                                    /*case
                                        when IFNULL(A.PARCIAL_I,0) >= IFNULL(B.PARCIAL_II,0) then (IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2
                                        when IFNULL(A.PARCIAL_I,0) <= IFNULL(B.PARCIAL_II,0) then (IFNULL(B.PARCIAL_II,0) + IFNULL(C.SUPLETORIO,0))/2
                                    end*/
                                else (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2
                                end
                            end
                        END AS promedio_final,
                        case
                        when ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) >= 75 then

                            case
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=14.50 then 'Aprobado'
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=1 and (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 <= 14.49 then
                                case
                                when IFNULL(C.SUPLETORIO,0) > 0 then

                                    case
                                        when (((IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2)+IFNULL(C.SUPLETORIO,0))/2 >= 14.50 then 'Aprobado'
                                        when (((IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2)+IFNULL(C.SUPLETORIO,0))/2 > 0 and (((IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2)+IFNULL(C.SUPLETORIO,0))/2 < 14.50 then 'Reprobado'
                                    end

                                else
                                    case when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 < 14.50 then 'Reprobado' end
                                end
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 = 0 then 'Pendiente'
                            end
                        when ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) > 0 and ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) < 75 then
                            case
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=14.50 then 'Reprobado'
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=1 and (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 <= 14.49 then 'Reprobado'
                            end

                        end as estado_academico,
                            /*case
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=14.50 then 'Aprobado'
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=1 and (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 <= 14.49 then
                                case
                                when IFNULL(C.SUPLETORIO,0) > 0 then
                                    case
                                        when IFNULL(A.PARCIAL_I,0) >= IFNULL(B.PARCIAL_II,0) then
                                        case
                                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2 >= 14.50 then 'Aprobado'
                                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2 >= 1 and (IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2 < 14.49 then 'Reprobado'
                                        end
                                        when IFNULL(A.PARCIAL_I,0) <= IFNULL(B.PARCIAL_II,0) then
                                        case
                                            when (IFNULL(B.PARCIAL_II,0) + IFNULL(C.SUPLETORIO,0))/2 >= 14.50 then 'Aprobado'
                                            when (IFNULL(B.PARCIAL_II,0) + IFNULL(C.SUPLETORIO,0))/2 >= 1 and (IFNULL(B.PARCIAL_II,0) + IFNULL(C.SUPLETORIO,0))/2 < 14.49 then 'Reprobado'
                                        end
                                    end
                                else
                                    case when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 < 14.50 then 'Reprobado' end
                                end
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 = 0 then 'Pendiente'
                            end as estado_academico,*/

                IFNULL(D.ASISTENCIA_PARCIAL_I,'0') asistencia_parcial_1,
                IFNULL(E.ASISTENCIA_PARCIAL_II,'0') asistencia_parcial_2,
                ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) as asistencia_final,
                case
                            when ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) >= 75 then 'Aprobado'
                            when ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) >= 1 and ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) < 75 then 'Reprobado'
                            when ((coalesce(D.ASISTENCIA_PARCIAL_I, 0) + coalesce(E.ASISTENCIA_PARCIAL_II, 0)) / 2) = 0 then'Pendiente'
                        end as estado_asist
                FROM
                    (
                    SELECT DISTINCT
                        estudiante.est_id,
                        estudiante.est_matricula,
                        estudiante.est_estado, -- CHANGED
                        concat(persona.per_pri_nombre,' ',persona.per_pri_apellido) as nombre,
                        paca.paca_id,
                        ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS paca_nombre,
                        daca.pro_id,
                        concat(profesor.per_pri_nombre,' ',profesor.per_pri_apellido) as profesor,
                        asi.asi_id,
                        meun.uaca_id as uaca_id,
                        asi.asi_descripcion as asi_nombre
                    FROM " . $con->dbname . ".estudiante AS estudiante
                    INNER JOIN " . $con1->dbname . ".persona AS persona ON persona.per_id = estudiante.per_id
                    INNER JOIN " . $con->dbname . ".distributivo_academico_estudiante AS daca_est ON daca_est.est_id = estudiante.est_id
                    INNER JOIN " . $con->dbname . ".distributivo_academico AS daca on daca_est.daca_id = daca.daca_id
                    INNER JOIN " . $con->dbname . ".asignatura AS asi ON asi.asi_id = daca.asi_id
                    INNER JOIN " . $con->dbname . ".profesor AS pro ON pro.pro_id = daca.pro_id
                    LEFT JOIN " . $con1->dbname . ".persona AS profesor ON profesor.per_id = pro.per_id
                    INNER JOIN " . $con->dbname . ".estudiante_carrera_programa AS ecpr ON ecpr.est_id = estudiante.est_id
                    INNER JOIN " . $con->dbname . ".modalidad_estudio_unidad AS meun ON meun.meun_id = ecpr.meun_id
                    INNER JOIN " . $con->dbname . ".periodo_academico AS paca ON daca.paca_id = paca.paca_id
                    INNER JOIN " . $con->dbname . ".semestre_academico AS saca ON saca.saca_id = paca.saca_id
                    INNER JOIN " . $con->dbname . ".bloque_academico AS baca ON baca.baca_id = paca.baca_id
                    WHERE
                    $str_search
                    $str_perfil_user
                     paca.paca_activo = 'A'
                    -- AND estudiante.est_activo = 1
                    AND estudiante.est_estado = 1 AND estudiante.est_estado_logico = 1
                    AND persona.per_estado = 1 AND persona.per_estado_logico = 1
                    AND daca.daca_estado = 1 AND daca.daca_estado_logico = 1
                    AND pro.pro_estado = 1 AND pro.pro_estado_logico = 1
                    AND daca_est.daes_estado = 1 AND daca_est.daes_estado_logico = 1
                    AND asi.asi_estado = 1 AND asi.asi_estado_logico = 1
                    AND ecpr.ecpr_estado = 1 AND ecpr.ecpr_estado_logico = 1
                    AND meun.meun_estado= 1 AND meun.meun_estado_logico = 1
                    AND paca.paca_estado = 1 AND paca.paca_estado_logico = 1
                    AND saca.saca_estado = 1 AND saca.saca_estado_logico = 1
                    AND baca.baca_estado = 1 AND baca.baca_estado_logico = 1
                    ) estudiante
                LEFT JOIN
                (
                    SELECT DISTINCT clfc.ccal_id, clfc.paca_id, clfc.est_id, clfc.asi_id,ecun.uaca_id,clfc.pro_id,clfc.ccal_calificacion AS PARCIAL_I
                    FROM " . $con->dbname . ".cabecera_calificacion clfc
                    INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                    INNER JOIN " . $con->dbname . ".esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                    WHERE   ecal.ecal_id = 1
                    AND clfc.ccal_estado = 1 AND clfc.ccal_estado_logico = 1
                ) A  on  estudiante.est_id = A.est_id
                AND estudiante.paca_id = A.paca_id
                AND estudiante.pro_id  = A.pro_id
                AND estudiante.asi_id = A.asi_id
                AND estudiante.uaca_id = A.uaca_id
                LEFT JOIN
                (
                    SELECT DISTINCT clfc.ccal_id, clfc.paca_id, clfc.est_id, clfc.asi_id,ecun.uaca_id,clfc.pro_id,ecal.ecal_descripcion  ,clfc.ccal_calificacion AS PARCIAL_II
                    FROM " . $con->dbname . ".cabecera_calificacion clfc
                    INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                    INNER JOIN " . $con->dbname . ".esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                    WHERE ecal.ecal_id = 2
                    AND clfc.ccal_estado = 1 AND clfc.ccal_estado_logico = 1
                ) B  ON estudiante.est_id = B.est_id
                AND estudiante.paca_id = B.paca_id
                AND estudiante.pro_id  = B.pro_id
                AND estudiante.asi_id = B.asi_id
                AND estudiante.uaca_id = B.uaca_id
                LEFT JOIN
                (
                    SELECT DISTINCT clfc.ccal_id, clfc.paca_id, clfc.est_id, clfc.asi_id,ecun.uaca_id,clfc.pro_id,ecal.ecal_descripcion  ,clfc.ccal_calificacion AS SUPLETORIO
                    FROM " . $con->dbname . ".cabecera_calificacion clfc
                    INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                    INNER JOIN " . $con->dbname . ".esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                    WHERE ecal.ecal_id = 3
                    AND clfc.ccal_estado = 1 AND clfc.ccal_estado_logico = 1
                ) C ON estudiante.est_id = C.est_id
                AND estudiante.paca_id = C.paca_id
                AND estudiante.pro_id  = C.pro_id
                AND estudiante.asi_id = C.asi_id
                    AND estudiante.uaca_id = C.uaca_id
                LEFT JOIN
                (
                    SELECT DISTINCT casi.casi_id, casi.paca_id, casi.est_id,casi.asi_id,esquema_calificacion_unidad.uaca_id,casi.pro_id, casi.casi_porc_total*100 as ASISTENCIA_PARCIAL_I
                    FROM " . $con->dbname . ".cabecera_asistencia casi
                    INNER JOIN " . $con->dbname . ".asistencia_esquema_unidad aeun_id_asistencia ON aeun_id_asistencia.aeun_id = casi.aeun_id
                    INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad esquema_calificacion_unidad ON esquema_calificacion_unidad.ecun_id = aeun_id_asistencia.ecun_id
                    INNER JOIN " . $con->dbname . ".esquema_calificacion esquema_calificacion_asistencia ON esquema_calificacion_asistencia.ecal_id = esquema_calificacion_unidad.ecal_id
                    WHERE esquema_calificacion_asistencia.ecal_id = 1
                    AND casi.casi_estado = 1 AND casi.casi_estado_logico = 1
                ) D ON  estudiante.est_id = D.est_id  AND estudiante.paca_id = D.paca_id
                AND estudiante.pro_id  = D.pro_id
                AND estudiante.asi_id = D.asi_id
                AND estudiante.uaca_id = D.uaca_id
                LEFT JOIN
                (
                    SELECT DISTINCT casi.casi_id, casi.paca_id, casi.est_id,casi.asi_id,esquema_calificacion_unidad.uaca_id,casi.pro_id, casi.casi_porc_total*100 as ASISTENCIA_PARCIAL_II
                    FROM " . $con->dbname . ".cabecera_asistencia casi
                    INNER JOIN " . $con->dbname . ".asistencia_esquema_unidad aeun_id_asistencia ON aeun_id_asistencia.aeun_id = casi.aeun_id
                    INNER JOIN " . $con->dbname . ".esquema_calificacion_unidad esquema_calificacion_unidad ON esquema_calificacion_unidad.ecun_id = aeun_id_asistencia.ecun_id
                    INNER JOIN " . $con->dbname . ".esquema_calificacion esquema_calificacion_asistencia ON esquema_calificacion_asistencia.ecal_id = esquema_calificacion_unidad.ecal_id
                    WHERE esquema_calificacion_asistencia.ecal_id = 2
                    AND casi.casi_estado = 1 AND casi.casi_estado_logico = 1
                ) E ON  estudiante.est_id = E.est_id  AND estudiante.paca_id = E.paca_id
                AND estudiante.pro_id  = E.pro_id
                AND estudiante.asi_id = E.asi_id
                AND estudiante.uaca_id = E.uaca_id";

		$comando = $con->createCommand($sql);
		\app\models\Utilities::putMessageLogFile('consultaCalificacionRegistroDocenteAllStudentSearch Sql: ' . $sql);

		$resultData = $comando->queryAll();
		// \app\models\Utilities::putMessageLogFile('consultaCalificacionRegistroDocenteAllStudentSearch: '.$comando->getRawSql());

		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $resultData,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => [
				],
			],
		]);
		if ($onlyData) {
			return $resultData;
		} else {
			return $dataProvider;
		}

	}

	/**
	 * Function consulta el nombre de unidad academica
	 * @author  Julio Lopez <analistadesarrollo03@uteg.edu.ec>;
	 * @property
	 * @return
	 */
	public function consultarUnidadAcademicasEstudiante($empresa, $est_id) {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		if ($empresa > 0) {
			$condicion = 'emp_id = :empresa AND ';
		}

		$sql = "SELECT distinct una.uaca_id as id, una.uaca_nombre as name
                        FROM " . $con->dbname . ".modalidad_estudio_unidad meu
                             inner JOIN " . $con->dbname . ".unidad_academica una on una.uaca_id = meu.uaca_id
                    where $condicion
                        una.uaca_id = (SELECT meun.uaca_id FROM " . $con->dbname . ".estudiante_carrera_programa  AS ecpr
                                        INNER JOIN " . $con->dbname . ".modalidad_estudio_unidad AS meun ON meun.meun_id = ecpr.meun_id
                                        WHERE ecpr.est_id = :est_id
                                          AND ecpr.ecpr_estado = 1 AND ecpr.ecpr_estado_logico = 1
                                          AND meun.meun_estado = 1 AND meun.meun_estado_logico = 1) AND
                        meu.meun_estado = :estado AND
                        meu.meun_estado_logico = :estado AND
                        una.uaca_estado = :estado AND
                        una.uaca_estado_logico = :estado
                    ORDER BY id asc ;";
		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":empresa", $empresa, \PDO::PARAM_INT);
		$comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
		$resultData = $comando->queryAll();
		\app\models\Utilities::putMessageLogFile('consultarUnidadAcademicasEstudiante: ' . $comando->getRawSql());
		return $resultData;
	}

	/**
	 * Function obtener carreras del estudiante segun unidad academica y modalidad
	 * @author  Julio Lopez <analistadesarrollo02@uteg.edu.ec>;
	 * @property
	 * @return
	 */
	public function consultarCarreraModalidadEstudiante($est_id, $unidad, $modalidad) {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		/*$sql = "
			                SELECT
			                        eac.eaca_id as id,
			                        eac.eaca_nombre as name
			                    FROM
			                        " . $con->dbname . ".modalidad_estudio_unidad as mcn
			                        INNER JOIN " . $con->dbname . ".estudio_academico as eac on eac.eaca_id = mcn.eaca_id
			                    WHERE
			                        mcn.uaca_id =:unidad AND
			                        mcn.mod_id =:modalidad AND
			                        eac.eaca_estado_logico=:estado AND
			                        eac.eaca_estado=:estado AND
			                        mcn.meun_estado_logico = :estado AND
			                        mcn.meun_estado = :estado
		*/

		$sql = "SELECT
                    eac.eaca_id as id,
                    eac.eaca_nombre as name
                FROM " . $con->dbname . ".estudiante as e
                INNER JOIN " . $con->dbname . ".estudiante_carrera_programa as ec on ec.est_id = e.est_id
                INNER JOIN " . $con->dbname . ".modalidad_estudio_unidad as mcn on mcn.meun_id = ec.meun_id
                INNER JOIN " . $con->dbname . ".estudio_academico as eac on eac.eaca_id = mcn.eaca_id
                WHERE e.est_id  = :est_id AND
                    mcn.uaca_id =:unidad AND
                    mcn.mod_id =:modalidad AND
                    e.est_estado = :estado and e.est_estado_logico = :estado AND
                    ec.ecpr_estado =:estado and ec.ecpr_estado_logico = :estado AND
                    eac.eaca_estado_logico = :estado AND
                    eac.eaca_estado = :estado AND
                    mcn.meun_estado_logico = :estado AND
                    mcn.meun_estado = :estado
                    ORDER BY name asc";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
		$comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
		$comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
		$resultData = $comando->queryAll();
		return $resultData;
	}

	public function activateCron($cron_id, $fecha) {

		$con = \Yii::$app->db_academico;
		$getsearch =
			"
SELECT
croe_id, croe_mod_id, croe_paca_id, croe_uaca_id,croe_parcial, croe_fecha_ejecucion, croe_exec
FROM db_academico.cron_estudiantes_educativa
WHERE croe_id = :cronid
";

		$comando = $con->createCommand($getsearch);
		$comando->bindParam(":cronid", $cron_id, \PDO::PARAM_INT);
		$datasearcher = $comando->queryOne();

/*
$datasearcher['croe_paca_id'];
$datasearcher['croe_uaca_id'];
$datasearcher['croe_mod_id'];
 */
		$con = \Yii::$app->db_academico;
		$updater = "
UPDATE db_academico.cron_estudiantes_educativa
SET croe_fecha_ejecucion = :fecha,
croe_exec = '1'
WHERE croe_id = :cron_id";

		$comando = $con->createCommand($updater);
		$comando->bindParam(":fecha", $fecha, \PDO::PARAM_STR);
		$comando->bindParam(":cron_id", $cron_id, \PDO::PARAM_INT);
		$result = $comando->execute();

		return $datasearcher;

	}

	function getallmods($paca_id, $uaca_id, $mod_id, $parcial_id) {
		$con = \Yii::$app->db_academico;

		if ($uaca_id < 2) {
			$newuaca_id = 1;
			FOR ($loopmod = 1; $loopmod < 5; $loopmod++) {

				$sqlmod = " SELECT
croe_id, croe_mod_id, croe_paca_id, croe_uaca_id,croe_parcial, croe_fecha_ejecucion, croe_exec
 FROM db_academico.cron_estudiantes_educativa
 Where  croe_paca_id = $paca_id
 AND croe_uaca_id = $newuaca_id
 AND croe_mod_id = $loopmod
 AND croe_parcial = $parcial_id
";

				$comando = $con->createCommand($sqlmod);
				$resultallData = $comando->queryOne();

				if ($resultallData['croe_mod_id'] == Null) {

					$sql =
					"INSERT INTO " . $con->dbname . ".cron_estudiantes_educativa (croe_mod_id, croe_paca_id, croe_uaca_id,croe_parcial, croe_exec, croe_usuario_ingreso, croe_estado, croe_fecha_creacion, croe_estado_logico)
  VALUES($loopmod, $paca_id, $newuaca_id,$parcial_id, '2', '1','1',now(),'1')";

					$comando = $con->createCommand($sql);
					$result = $comando->execute();

				}

			}}

		$con = \Yii::$app->db_academico;
		$str_search = "";
		if ($paca_id != "" && $paca_id > 0) {
			$str_search .= " AND croe_paca_id  = :croe_paca_id  ";
		}
		if ($uaca_id != "" && $uaca_id > 0) {
			$str_search .= " AND croe_uaca_id  = :croe_uaca_id ";
		}
		if ($mod_id != "" && $mod_id > 0) {
			$str_search .= " AND croe_mod_id  = :croe_mod_id ";
		}
		if ($parcial_id != "" && $parcial_id > 0) {
			$str_search .= " AND croe_parcial  = :croe_parcial_id ";
		}

		$sqlmod = " SELECT
croe.croe_id, croe.croe_mod_id, croe.croe_paca_id, croe.croe_uaca_id,croe.croe_parcial, croe.croe_fecha_ejecucion,
croe.croe_exec,ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS paca_nombre
 FROM db_academico.cron_estudiantes_educativa as croe
 INNER JOIN db_academico.periodo_academico as paca ON paca.paca_id = croe.croe_paca_id
 INNER JOIN db_academico.semestre_academico as saca ON saca.saca_id = paca.saca_id
 INNER JOIN db_academico.bloque_academico as baca ON baca.baca_id = paca.baca_id
 Where  1 = 1
 $str_search
"
		;
		$comando = $con->createCommand($sqlmod);

		if ($paca_id != "" && $paca_id > 0) {

			$comando->bindParam(":croe_paca_id", $paca_id, \PDO::PARAM_INT);
		}
		if ($uaca_id != "" && $uaca_id > 0) {

			$comando->bindParam(":croe_uaca_id", $uaca_id, \PDO::PARAM_INT);
		}
		if ($mod_id != "" && $mod_id > 0) {

			$comando->bindParam(":croe_mod_id", $mod_id, \PDO::PARAM_INT);
		}

		if ($parcial_id != "" && $parcial_id > 0) {

			$comando->bindParam(":croe_parcial_id", $parcial_id, \PDO::PARAM_INT);
		}

		$resultData = $comando->queryAll();

		return $resultData;
	}

/**
 * Actualizar el promedio de la tabla de promedio de malla academico
 * @author  Luis Cajamarca <analista04>
 * @param
 * @return
 */
	public function updatepromedio($maes_id, $paca_id) {
		$con = Yii::$app->db_academico;
		$usu_id = @Yii::$app->session->get("PB_iduser");
		$date = date(Yii::$app->params['dateTimeByDefault']);
		$transaccion = $con->getTransaction(); // se obtiene la transacci??n actual
		if ($trans !== null) {
			$trans = null; // si existe la transacci??n entonces no se crea una
		} else {
			$trans = $con->beginTransaction(); // si no existe la transacci??n entonces se crea una
		}
		try {
			$sql = "UPDATE db_academico.promedio_malla_academico pm, (
                        select distinct
                            pmac.maes_id,
                            pmac.paca_id,

                            case
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=14.50 then (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 <=14.49 then
                                case
                                when IFNULL(C.SUPLETORIO,0) > 0 then
                                    case
                                        when IFNULL(A.PARCIAL_I,0) >= IFNULL(B.PARCIAL_II,0) then (IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2
                                        when IFNULL(A.PARCIAL_I,0) <= IFNULL(B.PARCIAL_II,0) then (IFNULL(B.PARCIAL_II,0) + IFNULL(C.SUPLETORIO,0))/2
                                    end
                                else
                                    (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2
                                end
                            end as promedio,
                            case
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=14.50 then '1'
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 >=1 and (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 <= 14.49 then
                                case
                                when IFNULL(C.SUPLETORIO,0) > 0 then
                                    case
                                        when IFNULL(A.PARCIAL_I,0) >= IFNULL(B.PARCIAL_II,0) then
                                        case
                                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2 >= 14.50 then '1'
                                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2 >= 1 and (IFNULL(A.PARCIAL_I,0) + IFNULL(C.SUPLETORIO,0))/2 < 14.49 then '2'
                                        end
                                        when IFNULL(A.PARCIAL_I,0) <= IFNULL(B.PARCIAL_II,0) then
                                        case
                                            when (IFNULL(B.PARCIAL_II,0) + IFNULL(C.SUPLETORIO,0))/2 >= 14.50 then '1'
                                            when (IFNULL(B.PARCIAL_II,0) + IFNULL(C.SUPLETORIO,0))/2 >= 1 and (IFNULL(B.PARCIAL_II,0) + IFNULL(C.SUPLETORIO,0))/2 < 14.49 then '2'
                                        end
                                    end
                                else
                                    case when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 < 14.50 then '2' end
                                end
                            when (IFNULL(A.PARCIAL_I,0) + IFNULL(B.PARCIAL_II,0))/2 = 0 then '3'
                            end as estado
                        from db_academico.promedio_malla_academico pmac
                        inner join db_academico.malla_academico_estudiante maes on maes.maes_id=pmac.maes_id
                        inner join db_academico.estudiante est on est.per_id=maes.per_id
                        inner join db_academico.cabecera_calificacion ccal on est.est_id=ccal.est_id
                        left join
                            (SELECT clfc.ccal_id, clfc.paca_id, clfc.est_id, clfc.asi_id,ecun.uaca_id,clfc.pro_id,clfc.ccal_calificacion AS PARCIAL_I
                                FROM db_academico.cabecera_calificacion clfc
                             INNER JOIN db_academico.esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                             INNER JOIN db_academico.esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                             WHERE   ecal.ecal_id = 1 AND clfc.ccal_estado = 1 AND clfc.ccal_estado_logico = 1
                            ) A on est.est_id=A.est_id and pmac.paca_id=A.paca_id and maes.asi_id=A.asi_id
                        left join
                            (SELECT clfc.ccal_id, clfc.paca_id, clfc.est_id, clfc.asi_id,ecun.uaca_id,clfc.pro_id,clfc.ccal_calificacion AS PARCIAL_II
                                FROM db_academico.cabecera_calificacion clfc
                             INNER JOIN db_academico.esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                             INNER JOIN db_academico.esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                             WHERE   ecal.ecal_id = 2 AND clfc.ccal_estado = 1 AND clfc.ccal_estado_logico = 1
                            ) B on est.est_id=B.est_id and pmac.paca_id=B.paca_id and maes.asi_id=B.asi_id
                        LEFT JOIN
                            (SELECT DISTINCT clfc.ccal_id, clfc.paca_id, clfc.est_id, clfc.asi_id,ecun.uaca_id,clfc.pro_id,ecal.ecal_descripcion  ,clfc.ccal_calificacion AS SUPLETORIO
                            FROM db_academico.cabecera_calificacion clfc
                            INNER JOIN db_academico.esquema_calificacion_unidad ecun ON ecun.ecun_id = clfc.ecun_id
                            INNER JOIN db_academico.esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                            WHERE ecal.ecal_id = 3 AND clfc.ccal_estado = 1 AND clfc.ccal_estado_logico = 1
                            ) C on est.est_id=C.est_id and pmac.paca_id=C.paca_id and maes.asi_id=C.asi_id
                        where pmac.maes_id =$maes_id and pmac.paca_id= $paca_id
                    ) pmac
                set pm.pmac_nota = pmac.promedio,
                    pm.enac_id = pmac.estado,
                    pm.pmac_fecha_modificacion = '$date',
                    pm.pmac_usuario_ingreso = $usu_id
                where pm.maes_id=pmac.maes_id";

			$comando = $con->createCommand($sql);
			$result = $comando->execute();
			//\app\models\Utilities::putMessageLogFile('updatepromedio: ' . $comando->getRawSql());

			if ($transaccion !== null) {
				$transaccion->commit();
			}

			return TRUE;
		} catch (Exception $ex) {
			if ($transaccion !== null) {
				$transaccion->rollback();
			}
			return FALSE;
		}
	} //function updatepromedio

	/**
	 * Function insertar Rbno_id por medio del insert select del ccal
	 * @author  Luis Cajamarca  <analistadesarrollo04@uteg.edu.ec>
	 * @property integer $daes_id
	 * @return
	 */
	public function insertarRBNO($ccal_id, $key, $value, $valida) {
		$con = \Yii::$app->db_academico;
		$transaction = $con->beginTransaction();
		$estado = "1";
		$date = date(Yii::$app->params['dateTimeByDefault']);
		$usu_id = @Yii::$app->session->get("PB_iduser");
		try {
			if ($valida == 1) {
				$sql = "INSERT INTO db_academico.registro_bitacora_nota
                    (dcal_id, rbno_nota_anterior, rbno_nota_actual, rbno_usuario_creacion, rbno_estado, rbno_fecha_creacion, rbno_estado_logico)
                    (SELECT dc1.dcal_id, 0, ifnull(dc1.dcal_calificacion,0), $usu_id, 1, '$date', 1
                    from db_academico.detalle_calificacion dc1
                    inner join db_academico.componente_unidad cu1 on dc1.cuni_id = cu1.cuni_id
                    inner join db_academico.componente com1 on cu1.com_id = com1.com_id
                    where dc1.ccal_id = $ccal_id
                    and com1.com_nombre = '$key')";
			} else {
				$sql = "INSERT INTO db_academico.registro_bitacora_nota
                    (dcal_id, rbno_nota_anterior, rbno_nota_actual, rbno_usuario_creacion, rbno_estado, rbno_fecha_creacion, rbno_estado_logico)
                    (SELECT dc1.dcal_id, ifnull(dc1.dcal_calificacion,0), $value, $usu_id, 1, '$date', 1
                    from db_academico.detalle_calificacion dc1
                    inner join db_academico.componente_unidad cu1 on dc1.cuni_id = cu1.cuni_id
                    inner join db_academico.componente com1 on cu1.com_id = com1.com_id
                    where dc1.ccal_id = $ccal_id
                    and com1.com_nombre = '$key')";
			}

			$comando = $con->createCommand($sql);
			$comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
			$comando->execute();

			\app\models\Utilities::putMessageLogFile('insertarRBNO: ' . $comando->getRawSql());

			if ($transaction !== null) {
				$transaction->commit();
			}

			return true;

		} catch (Exception $ex) {
			if ($transaction !== null) {
				$transaction->rollback();
			}

			return FALSE;
		}

	}

	function getPagopend($cedusuedu) {

		$ceduladni['cedula'] = $cedusuedu;
		$url = "https://acade.uteg.edu.ec/planificaciondesa/grades.php";
		$content = json_encode($ceduladni);
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
			array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$json_response = curl_exec($curl); //--
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($status != 200) {
			die(" status $status content $content ");
		}
		$html = curl_multi_getcontent($curl);
		$response = json_decode($json_response, true); //--
		print_r(" status $status content $content $response $html  ");
		curl_close($curl);

		//  %saldo%
		$allresponse = explode('":"', $html);
		if (isset($allresponse[1])) {
			$saldos = explode('"', $allresponse[1]);
			print_r('SALDO ==> ' . $saldos[0]);

		} else {

			print_r('SALDO ==> 0.00');

		}

		if ($saldos == 0) {
			return True;
		} else {
			return False;
		}

	}

	public function busquedaEstudiantes() {
		$con = \Yii::$app->db_academico;
		$estado = 1;

		$sql = "SELECT est.est_id as id, concat(/*est.per_id, ' - ',*/ pers.per_cedula, ' - ',
                    ifnull(pers.per_pri_nombre, ' ') ,' ',
                    ifnull(pers.per_pri_apellido,' ')) as name
                    FROM db_academico.estudiante est
                    JOIN db_asgard.persona pers ON pers.per_id = est.per_id
                WHERE pers.per_estado = :estado AND
                      pers.per_estado_logico = :estado AND
                      est.est_estado = :estado AND
                      est.est_estado_logico = :estado;";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$resultData = $comando->queryAll();
		return $resultData;
	}

	/**
	 * @author  Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>
	 * @param
	 * @return
	 *  Consulta Aulas Educativa
	 */
	public function consultarAulas($paca_id = Null, $uaca_id = Null, $mod_id = Null, $cedu_id = Null, $ecal_id = Null) {
		//public function consultarAulas($paca_id=28,$uaca_id=1,$mod_id=1,$cedu_id=3616) {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		if ($ecal_id == Null) {$ecal_id = -1;}

		$str_search = "";
		if ($paca_id != "" && $paca_id > 0) {
			$str_search .= " AND daca.paca_id = :paca_id  ";
		} else { $str_search .= " AND daca.paca_id = 0  ";}
		if ($uaca_id != "" && $uaca_id > 0) {
			$str_search .= " AND daca.uaca_id = :uaca_id ";
		}
		if ($mod_id != "" && $mod_id > 0) {
			$str_search .= " AND daca.mod_id = :mod_id ";
		}
		if ($cedu_id != "" && $cedu_id > 0) {
			$str_search .= " AND ceduct.cedu_asi_id = :cedu_id ";
		}

		$sql = "
		SELECT distinct  cedist.daca_id,cuni.ecal_id, ceduct.cedu_asi_id as id ,LEFT(ceduct.cedu_asi_nombre, 80) as name,cedist.daca_id,uaca.uaca_nombre, daca.paca_id, moda.mod_nombre, daca.mpp_id, daca.uaca_id,daca.mod_id,daca.pro_id, daca.asi_id,
		concat (person.per_pri_nombre, ' ',person.per_pri_apellido, ' Msc.') as docente, daca.asi_id,
		ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS paca_nombre
		FROM db_academico.curso_educativa_distributivo cedist
		INNER JOIN db_academico.curso_educativa as ceduct on cedist.cedu_id = ceduct.cedu_id
		INNER JOIN db_academico.distributivo_academico as daca on cedist.daca_id = daca.daca_id
		INNER JOIN db_academico.distributivo_academico_estudiante as daes on daes.daca_id = daca.daca_id
		INNER JOIN db_academico.usuario_educativa as usuedu on usuedu.est_id = daes.est_id
		INNER JOIN db_academico.estudiante as estu on  estu.est_id = daes.est_id
		INNER JOIN db_academico.unidad_academica as uaca on  uaca.uaca_id = daca.uaca_id
		INNER JOIN db_academico.modalidad as moda on  moda.mod_id = daca.uaca_id
		INNER JOIN db_academico.profesor as profe on  profe.pro_id = daca.pro_id
		INNER JOIN db_asgard.persona as person on  person.per_id = profe.per_id
		INNER JOIN db_academico.componente_unidad as cuni on ecal_id = :ecal
		INNER JOIN db_academico.malla_academico_estudiante macaes
		ON macaes.per_id = usuedu.per_id AND macaes.asi_id = daca.asi_id
		INNER JOIN db_academico.periodo_academico AS paca ON paca.paca_id = daca.paca_id  --
		INNER JOIN db_academico.semestre_academico AS saca ON saca.saca_id = paca.saca_id --
		INNER JOIN db_academico.bloque_academico AS baca ON baca.baca_id = paca.baca_id --
		WHERE  TRUE $str_search
		AND ceduct.cedu_estado = :estado AND ceduct.cedu_estado_logico = :estado
		AND cedist.cedi_estado = :estado AND cedist.cedi_estado_logico = :estado
		AND daca.daca_estado = :estado AND daca.daca_estado_logico = :estado
		AND daes.daes_estado = :estado AND daes.daes_estado_logico = :estado
		AND usuedu.uedu_estado = :estado AND usuedu.uedu_estado_logico = :estado
		AND estu.est_estado = :estado AND estu.est_estado_logico = :estado
		AND uaca.uaca_estado = :estado AND uaca.uaca_estado_logico = :estado
		AND moda.mod_estado = :estado AND moda.mod_estado_logico = :estado
		AND paca.paca_estado = :estado AND paca.paca_estado_logico = :estado
		AND saca.saca_estado = :estado AND saca.saca_estado_logico = :estado
		AND baca.baca_estado = :estado AND baca.baca_estado_logico = :estado
		AND profe.pro_estado = :estado AND profe.pro_estado_logico = :estado
		AND person.per_estado = :estado AND person.per_estado_logico = :estado
		AND macaes.maes_estado = :estado AND macaes.maes_estado_logico = :estado
		ORDER BY name ASC
	     ";

		$comando = $con->createCommand($sql);
		if ($paca_id != "" && $paca_id > 0) {
			$comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
		}
		if ($uaca_id != "" && $uaca_id > 0) {
			$comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
		}
		if ($mod_id != "" && $mod_id > 0) {
			$comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
		}
		if ($cedu_id != "" && $cedu_id > 0) {
			$comando->bindParam(":cedu_id", $cedu_id, \PDO::PARAM_INT);
		}
		$comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
		$comando->bindParam(":ecal", $ecal_id, \PDO::PARAM_INT);
		$resultAulas = $comando->queryAll();
		return $resultAulas;
	}

/**
 * @author  Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>
 * @param
 * @return
 *  Consulta Usuarios por distributivo-aula Educativa
 */
	public function consultarUsuarios($uedu_aula, $parcial) {
		$con = \Yii::$app->db_academico;
		$estado = 1;

		$deduc = "
			SELECT cabec.ccal_id, cedist.daca_id, ceduct.cedu_asi_id,
			daca.uaca_id, daca.paca_id, daca.mod_id, daca.mpp_id,
			daca.pro_id, daca.asi_id, daes.est_id,
			usuedu.uedu_usuario, usuedu.per_id, person.per_cedula
			FROM db_academico.curso_educativa_distributivo cedist
			INNER JOIN db_academico.curso_educativa as ceduct on cedist.cedu_id = ceduct.cedu_id
			INNER JOIN db_academico.distributivo_academico as daca on cedist.daca_id = daca.daca_id
			INNER JOIN db_academico.distributivo_academico_estudiante as daes on daes.daca_id = daca.daca_id
			INNER JOIN db_academico.usuario_educativa as usuedu on usuedu.est_id = daes.est_id
			INNER JOIN db_academico.estudiante as estu on  estu.est_id = daes.est_id
			INNER JOIN db_asgard.persona as person on  estu.per_id = person.per_id
			LEFT JOIN db_academico.malla_academico_estudiante macaes
			ON macaes.per_id = usuedu.per_id AND macaes.asi_id = daca.asi_id
			LEFT JOIN db_academico.cabecera_calificacion as cabec on  cabec.est_id = daes.est_id
			AND cabec.asi_id = daca.asi_id
			LEFT JOIN db_academico.temp_estudiantes_noprocesados as tempo on  tempo.est_id = daes.est_id
			AND tempo.asi_id = daca.asi_id
			WHERE TRUE
			AND ceduct.cedu_asi_id = :uedu_aula
			AND ceduct.cedu_estado = :estado AND ceduct.cedu_estado_logico = :estado
			AND daca.daca_estado = :estado AND daca.daca_estado_logico = :estado
			AND daes.daes_estado = :estado AND daes.daes_estado_logico = :estado
			AND usuedu.uedu_estado = :estado AND usuedu.uedu_estado_logico = :estado
			AND estu.est_estado = :estado AND estu.est_estado_logico = :estado
			AND person.per_estado = :estado AND person.per_estado_logico = :estado
			";

		$comando = $con->createCommand($deduc);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
		$comando->bindParam(":uedu_aula", $uedu_aula, \PDO::PARAM_INT);
		$resultUsers = $comando->queryAll();
		return $resultUsers;

	}

	/**
	 * @author  Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>
	 * @param
	 * @return
	 *  Adaptacion dinamica para obtener calificaciones
	 */
	public function getallcode($i, $j, $k) {

		$b1 = '$arraydata';
		$b2 = '[$grades]';
		$b3 = "['id_categoria'] = ";
		$b4 = '$arraycat';

		if ($i == -1) {$v1 = Null;} else {
			$v1 = '[';
			$v1 .= $i;
			$v1 .= ']';}

		$alld = $b1 . '1' . $b2 . "['id_categoria'] = " . $b4 . $v1 . "['id_categoria']" . ';';
		$alld .= $b1 . '1' . $b2 . "['nombre'] = " . $b4 . $v1 . "['nombre']" . ';';
		$alld .= $b1 . '1' . $b2 . "['descripcion'] = " . $b4 . $v1 . "['descripcion']" . ';';
		$alld .= $b1 . '1' . $b2 . "['estado'] = " . $b4 . $v1 . "['estado']" . ';';
		$alld .= $b1 . '1' . $b2 . "['id_modulo'] = " . $b4 . $v1 . "['id_modulo']" . ';';
		$alld .= $b1 . '1' . $b2 . "['id_grupo'] = " . $b4 . $v1 . "['id_grupo']" . ';';

		if ($j == -1) {$v2 = Null;} else {
			$v2 = '[';
			$v2 .= $j;
			$v2 .= ']';}

		$alld .= $b1 . '2' . $b2 . "['id_calificacion'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['id_calificacion']" . ';';
		$alld .= $b1 . '2' . $b2 . "['nombre'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['nombre']" . ';';
		//$b1.'2'.$b2."['descripcion'] = ".$b4.$v1."['calificaciones']".$v2."['descripcion']".';';
		$alld .= $b1 . '2' . $b2 . "['id_docente'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['id_docente']" . ';';
		$alld .= $b1 . '2' . $b2 . "['fecha'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['fecha']" . ';';
		$alld .= $b1 . '2' . $b2 . "['rango_usuarios'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['rango_usuarios']" . ';';
		$alld .= $b1 . '2' . $b2 . "['tipo_calificacion'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['tipo_calificacion']" . ';';
		//$b1.'2'.$b2."['id_evaluacion'] = ".$b4.$v1."['calificaciones']".$v2."['id_evaluacion']".';';

		if ($k == -1) {$v3 = Null;} else {
			$v3 = '[';
			$v3 .= $k;
			$v3 .= ']';}

		$alld .= $b1 . '3' . $b2 . "['id_nota'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['notas']" . $v3 . "['id_nota']" . ';';
		$alld .= $b1 . '3' . $b2 . "['id_usuario'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['notas']" . $v3 . "['id_usuario']" . ';';
		$alld .= $b1 . '3' . $b2 . "['fecha'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['notas']" . $v3 . "['fecha']" . ';';
		$alld .= $b1 . '3' . $b2 . "['nota'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['notas']" . $v3 . "['nota']" . ';';
		$alld .= $b1 . '3' . $b2 . "['observaciones'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['notas']" . $v3 . "['observaciones']" . ';';
		$alld .= $b1 . '3' . $b2 . "['detalles'] = " . $b4 . $v1 . "['calificaciones']" . $v2 . "['notas']" . $v3 . "['detalles']" . ';';

		return $alld;

	}

	/**
	 * @author  Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>
	 * @param
	 * @return
	 *  Obtener escalas de calificaciones
	 */
	public function getescalas($uaca_id, $mod_id, $ecal_id) {
		$con = Yii::$app->db_academico;
		$sql = "
	SELECT cuni.cuni_id, cuni.com_id,comp.com_nombre, cuni.cuni_calificacion
	FROM db_academico.componente_unidad as cuni
	inner join db_academico.componente as comp
	on comp.com_id = cuni.com_id
	where uaca_id = $uaca_id AND mod_id = $mod_id AND ecal_id = $ecal_id
	AND cuni.cuni_estado = 1 AND cuni.cuni_estado_logico = 1
	AND comp.com_estado = 1 AND comp.com_estado_logico = 1
	";
		$comando = $con->createCommand($sql);
		$escalas = $comando->queryAll();
		return $escalas;

	}

	/**
	 * @author  Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>
	 * @param
	 * @return
	 *  funciones auxiliares para gestion de Cabeceras de calificaciones
	 */
	public function getcabeceras($est_id, $asi_id, $paca_id, $parciales) {
		$con = Yii::$app->db_academico;
		$sql = "
	SELECT ccal_id,ccal_calificacion FROM db_academico.cabecera_calificacion
	where
	est_id= $est_id AND
	asi_id= $asi_id AND
	paca_id= $paca_id AND
	ecun_id = $parciales
	AND ccal_estado = 1 AND ccal_estado_logico = 1
	";
		$comando = $con->createCommand($sql);
		$cabeceras = $comando->queryAll();
		return $cabeceras;
	}
	public function putcabeceras($est_id, $asi_id, $paca_id, $parciales, $pro_id) {
		$con = Yii::$app->db_academico;
		$sql = "
INSERT INTO db_academico.cabecera_calificacion
(paca_id, est_id, pro_id, asi_id, ecun_id,
ccal_estado, ccal_estado_logico)
VALUES ( $paca_id, $est_id, $pro_id, $asi_id, $parciales, '1', '1');
";
		$comando = $con->createCommand($sql);
		$cabeceras = $comando->execute();
		return $cabeceras;
	}
	public function updatecabeceras($ccal_id) {
		$con = Yii::$app->db_academico;
		$sql = "
		UPDATE db_academico.cabecera_calificacion
		SET ccal_calificacion = (select round(sum(dcal_calificacion),2)
		from db_academico.detalle_calificacion
		where ccal_id = $ccal_id
		AND dcal_estado = 1 AND dcal_estado_logico = 1
		),
		 ccal_fecha_modificacion = now()
		 WHERE ccal_id = $ccal_id;
		";
		$comando = $con->createCommand($sql);
		$cabeceras = $comando->execute();
		return $cabeceras;

	}

	/**
	 * @author  Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>
	 * @param
	 * @return
	 *  funciones auxiliares para gestion de Detalles de calificaciones
	 */
	function getdetalles($ccal_id, $cuni_id) {
		$con = Yii::$app->db_academico;
		$sql = "
SELECT dcal_id, ccal_id,cuni_id,dcal_calificacion,
dcal_usuario_creacion,dcal_fecha_modificacion
FROM db_academico.detalle_calificacion
WHERE ccal_id = $ccal_id AND cuni_id = $cuni_id
AND dcal_estado = 1 AND dcal_estado_logico = 1
;
";
		$comando = $con->createCommand($sql);
		$detalles = $comando->queryOne();
		return $detalles;
	}
	function putdetalles($ccal_id, $cuni_id, $dcalificacion) {
		$con = Yii::$app->db_academico;
		$sql = "
INSERT INTO db_academico.detalle_calificacion
(ccal_id,cuni_id,dcal_calificacion,dcal_usuario_creacion,dcal_estado,dcal_estado_logico)
VALUES ($ccal_id,$cuni_id,$dcalificacion, '1', '1', '1')
";
		$comando = $con->createCommand($sql);
		$detalles = $comando->execute();
		return $detalles;
	}
	function putbitacora($ccal_id, $cuni_id, $dcalificacion) {
		GLOBAL $dsn, $dbuser, $dbpass, $dbname;
		$con = new \PDO($dsn, $dbuser, $dbpass);
		$sql = "
INSERT INTO db_academico.registro_bitacora_nota
(dcal_id, rbno_nota_anterior, rbno_nota_actual, rbno_usuario_creacion, rbno_estado,
rbno_estado_logico) VALUES ($dcal_id, '0',$dcalificacion, '1', '1', '1');
";
		$comando = $con->createCommand($sql);
		$bitacora = $comando->execute();
		return $bitacora;
	}
	function updatedetalles($dcal_id, $dcalificacion) {
		$con = Yii::$app->db_academico;
		$sql = "
UPDATE db_academico.detalle_calificacion
 SET dcal_calificacion = $dcalificacion,
 dcal_fecha_modificacion = now()
 WHERE dcal_id = $dcal_id;
";
		$comando = $con->createCommand($sql);
		$detalles = $comando->execute();
		return $detalles;

	}

	/**
	 * @author  Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>
	 * @param
	 * @return
	 *  funciones auxiliares para obtencion de calificaciones Educativa
	 */
	function getparamcategoria($elemento) {
		$datacategorias = array();
		$elementos = explode(" ", $elemento);
		for ($iter = 0; $iter < 21; $iter++) {

			if (isset($elementos[$iter])) {

				if (isset($elementos[$iter + 1])) {
					$nexter = $elementos[$iter + 1];
				} else {
					$nexter = 0;

				}
				if (strtoupper(substr($elementos[$iter], 0, 1)) == 'S') {

					if (intval(substr($elementos[$iter], 1, 1)) > 0) {
						$datacategorias['semana'] = substr($elementos[$iter], 1, 2);
					} elseif (intval($nexter) > 0 AND strtoupper(substr($elementos[$iter], 2, 1)) == 'M') {
						$datacategorias['semana'] = $nexter;
					}

				} elseif (strtoupper(substr($elementos[$iter], 0, 1)) == 'P') {

					if (intval(substr($elementos[$iter], 1, 1)) > 0) {
						$datacategorias['parcial'] = substr($elementos[$iter], 1, 2);
					} elseif (intval($nexter) > 0) {
						$datacategorias['parcial'] = $nexter;
					}

				} elseif (strtoupper(substr($elementos[$iter], 0, 1)) == 'U') {
					if (intval(substr($elementos[$iter], 1, 1)) > 0) {
						$datacategorias['unidad'] = substr($elementos[$iter], 1, 2);
					} elseif (intval($nexter) > 0) {
						$datacategorias['unidad'] = $nexter;
					}

				}

			}

		}

		return $datacategorias;
	}

	function getnota($elemento) {
		$notas = explode("/", $elemento);
		$withouter = str_replace(chr(44), chr(46), $notas[0]);
		$grade = floatval($withouter);
		return $grade;
	}

		function getitemparcial($elemento) {
		$dataparcial = array();
		$elementos = explode(" ", $elemento);
		for ($iter = 0; $iter < count($elementos); $iter++) {
			if (strtoupper(substr($elementos[$iter], 0, 4)) == 'PRIM') {
				$dataparcial['parcial'] = 1;

			} elseif (strtoupper(substr($elementos[$iter], 0, 4)) == 'SEGU') {
				$dataparcial['parcial'] = 2;

			}  elseif (strtoupper(substr($elementos[$iter], 0, 4)) == 'SUPL') {
				$dataparcial['parcial'] = 3;

			}  

		} if (!isset($dataparcial['parcial'])) { $dataparcial['parcial'] = 4; }

		return $dataparcial;
	}


	function getparamitem($elemento) {
		$dataitems = array();
		$elementos = explode(" ", $elemento);
		for ($iter = 0; $iter < count($elementos); $iter++) {
			if (strtoupper(substr($elementos[$iter], 0, 3)) == 'TAL') {
				if (intval($elementos[$iter + 1]) > 0) {
					$dataitems['taller'] = $elementos[$iter + 1];
				} elseif (intval($elementos[$iter + 2]) > 0) {
					$dataitems['taller'] = $elementos[$iter + 2];
				}

			} elseif (strtoupper(substr($elementos[$iter], 0, 3)) == 'EXA') {

				if  (isset($dataitems['evaluacion']) != 1) { 
				$dataitems['examen'] = 1; $dataitems['psexamen'] = 1;
			}

			} elseif (strtoupper(substr($elementos[$iter], 0, 3)) == 'MEJ') {
				$dataitems['mejoramiento'] = 1;

			} elseif (strtoupper(substr($elementos[$iter], 0, 3)) == 'SUP') {
				$dataitems['supletorio'] = 1;

			} elseif (strtoupper(substr($elementos[$iter], 0, 3)) == 'FOR') {
				$dataitems['foro'] = 1;

			} elseif (strtoupper(substr($elementos[$iter], 0, 3)) == 'VIV') {
				$dataitems['sincrona'] = 1;

			} elseif (strtoupper(substr($elementos[$iter], 0, 5)) == 'CUEST') {
				$dataitems['evaluacion'] = 1;

			}   elseif (strtoupper(substr($elementos[$iter], 0, 4)) == 'ASIS') {
				$dataitems['asistencia'] = 1;

			}   elseif (strtoupper(substr($elementos[$iter], 0, 3)) == 'ACT') {
				$dataitems['psactividades'] = 1;

			}   elseif (strtoupper(substr($elementos[$iter], 0, 4)) == 'LECC') {
				$dataitems['psleccion'] = 1;

			}   elseif (strtoupper(substr($elementos[$iter], 0, 3)) == 'EVA') {
				$dataitems['psevaluacion'] = 1;

			}   elseif (strtoupper(substr($elementos[$iter], 3, 3)) == 'NCR' AND strtoupper(substr($elementos[$iter], 0, 1)) == 'A') {
				$dataitems['psasincrona'] = 1;

			}   elseif (strtoupper(substr($elementos[$iter], 4, 3)) == 'NCR' AND strtoupper(substr($elementos[$iter], 0, 1)) == 'A') {
				$dataitems['psasincrona'] = 1;

			}   elseif (strtoupper(substr($elementos[$iter], 3, 3)) == 'NCR' AND strtoupper(substr($elementos[$iter], 0, 1)) == 'S') {
				$dataitems['psssincrona'] = 1;

			}   elseif (strtoupper(substr($elementos[$iter], 2, 3)) == 'NCR' AND strtoupper(substr($elementos[$iter], 0, 1)) == 'S') {
				$dataitems['psssincrona'] = 1;

			}  


		}

		return $dataitems;
	}

}