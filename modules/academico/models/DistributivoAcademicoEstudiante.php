<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "distributivo_academico_estudiante".
 *
 * @property int $daes_id
 * @property int $daca_id
 * @property int $est_id
 * @property string $daes_fecha_registro
 * @property int $daes_usuario_ingreso
 * @property int $daes_usuario_modifica
 * @property string $daes_estado
 * @property string $daes_fecha_creacion
 * @property string $daes_fecha_modificacion
 * @property string $daes_estado_logico
 *
 * @property DistributivoAcademico $daca
 * @property Estudiante $est
 */
class DistributivoAcademicoEstudiante extends \yii\db\ActiveRecord {

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'distributivo_academico_estudiante';
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
			[['daca_id', 'est_id', 'daes_estado', 'daes_estado_logico'], 'required'],
			[['daca_id', 'est_id', 'daes_usuario_ingreso', 'daes_usuario_modifica'], 'integer'],
			[['daes_fecha_creacion', 'daes_fecha_modificacion', 'daes_fecha_registro'], 'safe'],
			[['daes_estado', 'daes_estado_logico'], 'string', 'max' => 1],
			[['daca_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistributivoAcademico::className(), 'targetAttribute' => ['daca_id' => 'daca_id']],
			[['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudiante::className(), 'targetAttribute' => ['est_id' => 'est_id']],
		];
	}

	/**
     * {@inheritdoc}
     //JLC: 28 ABRIL 2022
     */
    public function attributeLabels()
    {
        return [
            'daes_id' => 'Daes ID',
            'daca_id' => 'Daca ID',
            'est_id' => 'Est ID',
            'daes_fecha_registro' => 'Daes Fecha Registro',
            'daes_usuario_ingreso' => 'Daes Usuario Ingreso',
            'daes_usuario_modifica' => 'Daes Usuario Modifica',
            'daes_estado' => 'Daes Estado',
            'daes_fecha_creacion' => 'Daes Fecha Creacion',
            'daes_fecha_modificacion' => 'Daes Fecha Modificacion',
            'daes_estado_logico' => 'Daes Estado Logico',
        ];
    }//JLC: 28 ABRIL 2022

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEst() {
		return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDaca() {
		return $this->hasOne(DistributivoAcademico::className(), ['daca_id' => 'daca_id']);
	}

	public function getListadoDistributivoEstudiante($daca_id, $search = null, $onlyData = false) {
		$con_academico = \Yii::$app->db_academico;
		$con_db = \Yii::$app->db;
		$search_cond = "%" . $search . "%";
		$estado = "1";
		$str_search = "";

		if (isset($search) && $search != "") {
			$str_search = "(pe.per_pri_nombre like :search OR ";
			$str_search .= "pe.per_pri_apellido like :search OR ";
			$str_search .= "pe.per_cedula like :search) AND ";
		}

		$sql = "SELECT
                    de.daes_id AS Id,
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS Nombres,
                    pe.per_cedula AS Cedula,
                    pe.per_correo AS Correo,
                    ifnull(pe.per_celular, '') AS Telefono,
                    e.est_matricula AS Matricula,
                    ea.eaca_nombre AS Carrera
                FROM
                    " . $con_academico->dbname . ".distributivo_academico AS da
                    INNER JOIN " . $con_academico->dbname . ".distributivo_academico_estudiante AS de ON da.daca_id = de.daca_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante AS e ON e.est_id = de.est_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante_carrera_programa AS ec ON ec.est_id = e.est_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad_estudio_unidad AS mu ON mu.meun_id = ec.meun_id
                    INNER JOIN " . $con_academico->dbname . ".estudio_academico AS ea ON ea.eaca_id = mu.eaca_id
                    INNER JOIN " . $con_db->dbname . ".persona AS pe ON e.per_id = pe.per_id
                WHERE
                    $str_search
                    da.daca_id =:daca_id AND
                    da.daca_estado = :estado AND
                    da.daca_estado_logico = :estado AND
                    de.daes_estado = :estado AND
                    de.daes_estado_logico = :estado AND
                    e.est_estado = :estado AND
                    e.est_estado_logico = :estado AND
                    ec.ecpr_estado = :estado AND
                    ec.ecpr_estado_logico = :estado AND
                    mu.meun_estado = :estado AND
                    mu.meun_estado_logico = :estado AND
                    ea.eaca_estado = :estado AND
                    ea.eaca_estado_logico = :estado AND
                    pe.per_estado = :estado AND
                    pe.per_estado_logico = :estado ";

		$comando = $con_academico->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);
		if (isset($search) && $search != "") {
			$comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
		}

		$res = $comando->queryAll();
		if ($onlyData) {
			return $res;
		}

		$dataProvider = new ArrayDataProvider([
			'key' => 'Id',
			'allModels' => $res,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => ['Nombres', "Cedula", "Cedula", "Correo", "Carrera"],
			],
		]);

		return $dataProvider;
	}

	public function getEstudiantesXUnidadAcademica($uaca_id, $search) {
		$con_academico = \Yii::$app->db_academico;
		$con_db = \Yii::$app->db;
		$search_cond = "%" . $search . "%";
		$estado = "1";
		$str_search = "";

		if (isset($search) && $search != "") {
			$str_search = "(pe.per_pri_nombre like :search OR ";
			$str_search .= "pe.per_pri_apellido like :search OR ";
			$str_search .= "pe.per_cedula like :search) AND ";
		}

		$sql = "SELECT
                    e.est_id AS id,
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido, ' - ', pe.per_cedula) AS value,
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido, ' - ', pe.per_cedula) AS label
                FROM
                    " . $con_academico->dbname . ".estudiante AS e
                    INNER JOIN " . $con_academico->dbname . ".estudiante_carrera_programa AS ec ON ec.est_id = e.est_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad_estudio_unidad AS mu ON mu.meun_id = ec.meun_id
                    INNER JOIN " . $con_academico->dbname . ".estudio_academico AS ea ON ea.eaca_id = mu.eaca_id
                    -- INNER JOIN " . $con_academico->dbname . ".promocion_programa AS pp ON pp.eaca_id = ea.eaca_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON ua.uaca_id = mu.uaca_id
                    INNER JOIN " . $con_db->dbname . ".persona AS pe ON e.per_id = pe.per_id
                WHERE
                    $str_search
                    ua.uaca_id =:uaca_id AND
                    e.est_estado = :estado AND
                    e.est_estado_logico = :estado AND
                    ec.ecpr_estado = :estado AND
                    ec.ecpr_estado_logico = :estado AND
                    mu.meun_estado = :estado AND
                    mu.meun_estado_logico = :estado AND
                    ea.eaca_estado = :estado AND
                    ea.eaca_estado_logico = :estado AND
                    -- pp.ppro_estado = :estado AND
                    -- pp.ppro_estado_logico = :estado AND
                    ua.uaca_estado = :estado AND
                    ua.uaca_estado_logico = :estado AND
                    pe.per_estado = :estado AND
                    pe.per_estado_logico = :estado ";

		$comando = $con_academico->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
		if (isset($search) && $search != "") {
			$comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
		}

		$res = $comando->queryAll();
		return $res;
	}
	/**
	 * Function consultarHorarioEstudiante
	 * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
	 * @property integer $userid
	 * @return
	 */

	public function consultarHorarioEstudiante($est_id) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db;
		$estado = "1";
		$sql = "SELECT
                    dae.daca_id,
                    dae.est_id,
                    dac.asi_id,
                    dac.pro_id,
                    CASE dac.daca_jornada
                        WHEN 1 THEN 'Matutino'
                        WHEN 2 THEN 'Nocturno'
                        WHEN 3 THEN 'Semipresencial'
                        WHEN 4 THEN 'Distancia'
                    END AS daca_jornada,
                    dac.daca_horario,
                    ifnull((SELECT ifnull(daho_descripcion,' ')
                    FROM " . $con->dbname . ".distributivo_academico_horario dah
                    WHERE dah.daho_id = dac.daho_id),'') as  daho_descripcion,
                    asig.asi_nombre as materia,
                    concat(pers.per_pri_nombre, ' ', pers.per_pri_apellido) as profesor
                FROM  " . $con->dbname . ".distributivo_academico_estudiante dae
                INNER JOIN " . $con->dbname . ".distributivo_academico dac ON dac.daca_id = dae.daca_id
                INNER JOIN " . $con->dbname . ".periodo_academico pea ON pea.paca_id = dac.paca_id
                INNER JOIN " . $con->dbname . ".asignatura asig ON asig.asi_id = dac.asi_id
                INNER JOIN " . $con->dbname . ".profesor prof ON prof.pro_id = dac.pro_id
                INNER JOIN " . $con1->dbname . ".persona pers ON pers.per_id = prof.per_id
                WHERE  dae.est_id = :est_id AND
                pea.paca_activo = 'A' AND
                dae.daes_estado = :estado AND
                dae.daes_estado_logico = :estado";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);

		$res = $comando->queryAll();
		//return $res;
		$dataProvider = new ArrayDataProvider([
			'key' => 'Id',
			'allModels' => $res,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				//'attributes' => ['Nombres', "Cedula", "Cedula", "Correo", "Carrera"],
			],
		]);

		return $dataProvider;
	}

	/**
	 * Function insertar Daes_id por medio del insert select del daca_id
	 * @author  Luis Cajamarca  <analistadesarrollo04@uteg.edu.ec>
	 * @property integer $daes_id
	 * @return
	 */

	public function insertarDaesEstudiante($paca_id) {
		$con = \Yii::$app->db_academico;
		$transaction = $con->beginTransaction();
		$estado = "1";
		$date = date(Yii::$app->params['dateTimeByDefault']);

		try {
			$sql = "INSERT INTO db_academico.distributivo_academico_estudiante
                (daca_id, est_id, daes_fecha_registro, daes_estado, daes_usuario_ingreso,daes_fecha_creacion, daes_fecha_modificacion, daes_estado_logico)
                (SELECT distinct daca.daca_id, est.est_id,'$date', 1, 1,'$date', Null, 1
                FROM
                    (SELECT  pera.paca_id as id, ifnull(CONCAT(blq.baca_nombre,'-',sem.saca_nombre,' ',sem.saca_anio),'') as nombre, blq.baca_nombre as bloque
                        FROM db_academico.periodo_academico pera
                        inner join db_academico.semestre_academico sem  ON sem.saca_id = pera.saca_id
                        inner join db_academico.bloque_academico blq ON blq.baca_id = pera.baca_id
                        WHERE pera.paca_activo = 'A' AND now() >= pera.paca_fecha_inicio AND now() <= pera.paca_fecha_fin AND
                        pera.paca_estado = 1 AND pera.paca_estado_logico = 1) as periodo,
                db_academico.estudiante est
                inner join db_academico.estudiante_carrera_programa ecp ON est.est_id=ecp.est_id
                inner join db_academico.modalidad_estudio_unidad meun ON meun.meun_id=ecp.meun_id
                inner join db_academico.registro_online ron ON ron.per_id=est.per_id
                inner join db_academico.registro_online_item roi ON roi.ron_id=ron.ron_id
                inner join db_academico.malla_academica_detalle made ON made.made_codigo_asignatura=roi.roi_materia_cod
                inner join db_academico.asignatura asi ON asi.asi_id=made.asi_id
                inner join db_academico.materia_paralelo_periodo mpp ON mpp.asi_id=asi.asi_id AND mpp.mod_id=meun.mod_id AND mpp.mpp_num_paralelo=roi.roi_paralelo
                inner join db_academico.distributivo_academico daca ON daca.asi_id=asi.asi_id AND daca.mod_id=meun.mod_id AND daca.mpp_id=mpp.mpp_id
                left join db_academico.distributivo_academico_estudiante daes on daca.daca_id = daes.daca_id
                where roi.roi_bloque = periodo.bloque and daes.daes_id IS NULL and periodo.id = :paca_id
                AND est.est_estado = 1   AND est.est_estado_logico = 1
                AND meun.meun_estado = 1 AND meun.meun_estado_logico = 1
                AND ron.ron_estado = 1   AND ron.ron_estado_logico = 1
                AND roi.roi_estado = 1   AND roi.roi_estado_logico = 1
                AND mpp.mpp_estado = 1   AND mpp.mpp_estado_logico = 1
                AND daca.daca_estado = 1 AND daca.daca_estado_logico = 1
            )";

			$comando = $con->createCommand($sql);
			$comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
			$comando->execute();

			\app\models\Utilities::putMessageLogFile('insertarDaesEstudiante: ' . $comando->getRawSql());

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

	/**
	 * Function consultar Daes_id por medio del select del daca_id
	 * @author  Luis Cajamarca  <analistadesarrollo04@uteg.edu.ec>
	 * @property integer $daes_id
	 * @return
	 */
	public function consultarDaesEstudiante($paca_id) {
		$con = \Yii::$app->db_academico;
		$estado = "1";

		$sql = "SELECT distinct daca.daca_id
                FROM
                    (SELECT  pera.paca_id as id,
                            ifnull(CONCAT(blq.baca_nombre,'-',sem.saca_nombre,' ',sem.saca_anio),'') as nombre,
                            blq.baca_nombre as bloque
                    FROM db_academico.periodo_academico pera
                    inner join db_academico.semestre_academico sem  ON sem.saca_id = pera.saca_id
                    inner join db_academico.bloque_academico blq ON blq.baca_id = pera.baca_id
                    WHERE pera.paca_activo = 'A' AND
                    now() >= pera.paca_fecha_inicio and now() <= pera.paca_fecha_fin and
                    pera.paca_estado = 1 AND pera.paca_estado_logico = 1) as periodo,
                db_academico.estudiante est
                inner join db_academico.estudiante_carrera_programa ecp
                   ON est.est_id=ecp.est_id
                  AND est.est_estado=1 AND est.est_estado_logico=1
                inner join db_academico.modalidad_estudio_unidad meun
                   ON meun.meun_id=ecp.meun_id
                  AND meun.meun_estado=1 AND meun.meun_estado_logico=1
                inner join db_academico.registro_online ron
                   ON ron.per_id=est.per_id
                  AND ron.ron_estado=1 AND ron.ron_estado_logico=1
                inner join db_academico.registro_online_item roi
                   ON roi.ron_id=ron.ron_id
                  AND roi.roi_estado=1 AND roi.roi_estado_logico=1
                inner join db_academico.malla_academica_detalle made
                   ON made.made_codigo_asignatura=roi.roi_materia_cod
                inner join db_academico.asignatura asi
                   ON asi.asi_id=made.asi_id
                inner join db_academico.materia_paralelo_periodo mpp
                   ON mpp.asi_id=asi.asi_id
                  AND mpp.mod_id=meun.mod_id
                  AND mpp.mpp_num_paralelo=roi.roi_paralelo
                  AND mpp.mpp_estado=1 AND mpp.mpp_estado_logico=1
                inner join db_academico.distributivo_academico daca
                   ON daca.asi_id=asi.asi_id
                  AND daca.mod_id=meun.mod_id
                  AND daca.mpp_id=mpp.mpp_id
                  AND daca.daca_estado=1 AND daca.daca_estado_logico=1
                left join db_academico.distributivo_academico_estudiante daes on daca.daca_id=daes.daca_id
                where roi.roi_bloque=periodo.bloque and daes.daes_id IS NULL and periodo.id=:paca_id";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
		\app\models\Utilities::putMessageLogFile($comando->getRawSql());
		$res = $comando->queryOne();
		return $res;

	}

}
