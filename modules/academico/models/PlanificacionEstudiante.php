<?php

namespace app\modules\academico\models;

use app\models\Persona;
use app\models\Utilities;
use Yii;
use yii\base\Exception;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "planificacion_estudiante".
 *
 * @property int $pes_id
 * @property int $pla_id
 * @property int $per_id
 * @property string|null $pes_jornada
 * @property string|null $pes_cod_carrera
 * @property string|null $pes_carrera
 * @property string|null $pes_dni
 * @property string|null $pes_nombres
 * @property string|null $pes_egresado
 * @property string|null $pes_tutoria_nombre
 * @property string|null $pes_tutoria_cod
 * @property string|null $pes_cod_malla
 * @property string|null $pes_mat_b1_h1_nombre
 * @property string|null $pes_mat_b1_h1_cod
 * @property string|null $pes_mod_b1_h1
 * @property string|null $pes_jor_b1_h1
 * @property string|null $pes_mat_b1_h2_nombre
 * @property string|null $pes_mat_b1_h2_cod
 * @property string|null $pes_mod_b1_h2
 * @property string|null $pes_jor_b1_h2
 * @property string|null $pes_mat_b1_h3_nombre
 * @property string|null $pes_mat_b1_h3_cod
 * @property string|null $pes_mod_b1_h3
 * @property string|null $pes_jor_b1_h3
 * @property string|null $pes_mat_b1_h4_nombre
 * @property string|null $pes_mat_b1_h4_cod
 * @property string|null $pes_mod_b1_h4
 * @property string|null $pes_jor_b1_h4
 * @property string|null $pes_mat_b1_h5_nombre
 * @property string|null $pes_mat_b1_h5_cod
 * @property string|null $pes_mod_b1_h5
 * @property string|null $pes_jor_b1_h5
 * @property string|null $pes_mat_b1_h6_nombre
 * @property string|null $pes_mat_b1_h6_cod
 * @property string|null $pes_mod_b1_h6
 * @property string|null $pes_jor_b1_h6
 * @property string|null $pes_mat_b2_h1_nombre
 * @property string|null $pes_mat_b2_h1_cod
 * @property string|null $pes_mod_b2_h1
 * @property string|null $pes_jor_b2_h1
 * @property string|null $pes_mat_b2_h2_nombre
 * @property string|null $pes_mat_b2_h2_cod
 * @property string|null $pes_mod_b2_h2
 * @property string|null $pes_jor_b2_h2
 * @property string|null $pes_mat_b2_h3_nombre
 * @property string|null $pes_mat_b2_h3_cod
 * @property string|null $pes_mod_b2_h3
 * @property string|null $pes_jor_b2_h3
 * @property string|null $pes_mat_b2_h4_nombre
 * @property string|null $pes_mat_b2_h4_cod
 * @property string|null $pes_mod_b2_h4
 * @property string|null $pes_jor_b2_h4
 * @property string|null $pes_mat_b2_h5_nombre
 * @property string|null $pes_mat_b2_h5_cod
 * @property string|null $pes_mod_b2_h5
 * @property string|null $pes_jor_b2_h5
 * @property string|null $pes_mat_b2_h6_nombre
 * @property string|null $pes_mat_b2_h6_cod
 * @property string|null $pes_mod_b2_h6
 * @property string|null $pes_jor_b2_h6
 * @property string $pes_estado
 * @property string $pes_fecha_creacion
 * @property int|null $pes_usuario_modifica
 * @property string|null $pes_fecha_modificacion
 * @property string $pes_estado_logico
 *
 * @property Planificacion $pla
 */
class PlanificacionEstudiante extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'planificacion_estudiante';
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
			[['pla_id', 'per_id', 'pes_estado', 'pes_estado_logico'], 'required'],
			[['pla_id', 'per_id', 'pes_usuario_modifica'], 'integer'],
			[['pes_fecha_creacion', 'pes_fecha_modificacion'], 'safe'],
			[['pes_jornada'], 'string', 'max' => 3],
			[['pes_cod_carrera', 'pes_tutoria_cod', 'pes_mat_b1_h1_cod', 'pes_jor_b1_h1', 'pes_mat_b1_h2_cod', 'pes_jor_b1_h2', 'pes_mat_b1_h3_cod', 'pes_jor_b1_h3', 'pes_mat_b1_h4_cod', 'pes_jor_b1_h4', 'pes_mat_b1_h5_cod', 'pes_jor_b1_h5', 'pes_mat_b1_h6_cod', 'pes_jor_b1_h6', 'pes_mat_b2_h1_cod', 'pes_mat_b2_h2_cod', 'pes_jor_b2_h2', 'pes_mat_b2_h3_cod', 'pes_jor_b2_h3', 'pes_mat_b2_h4_cod', 'pes_jor_b2_h4', 'pes_mat_b2_h5_cod', 'pes_jor_b2_h5', 'pes_mat_b2_h6_cod', 'pes_jor_b2_h6'], 'string', 'max' => 20],
			[['pes_carrera', 'pes_tutoria_nombre', 'pes_mat_b1_h1_nombre', 'pes_mat_b1_h2_nombre', 'pes_mat_b1_h3_nombre', 'pes_mat_b1_h4_nombre', 'pes_mat_b1_h5_nombre', 'pes_mat_b1_h6_nombre', 'pes_mat_b2_h1_nombre', 'pes_mat_b2_h2_nombre', 'pes_mat_b2_h3_nombre', 'pes_mat_b2_h4_nombre', 'pes_mat_b2_h5_nombre', 'pes_mat_b2_h6_nombre'], 'string', 'max' => 100],
			[['pes_dni'], 'string', 'max' => 15],
			[['pes_nombres'], 'string', 'max' => 200],
			[['pes_egresado', 'pes_estado', 'pes_estado_logico'], 'string', 'max' => 1],
			[['pes_cod_malla'], 'string', 'max' => 50],
			[['pes_mod_b1_h1', 'pes_mod_b1_h2', 'pes_mod_b1_h3', 'pes_mod_b1_h4', 'pes_mod_b1_h5', 'pes_mod_b1_h6', 'pes_mod_b2_h1', 'pes_mod_b2_h2', 'pes_mod_b2_h3', 'pes_mod_b2_h4', 'pes_mod_b2_h5', 'pes_mod_b2_h6'], 'string', 'max' => 2],
			[['pes_jor_b2_h1'], 'string', 'max' => 30],
			[['pla_id'], 'exist', 'skipOnError' => true, 'targetClass' => Planificacion::className(), 'targetAttribute' => ['pla_id' => 'pla_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'pes_id' => 'Pes ID',
			'pla_id' => 'Pla ID',
			'per_id' => 'Per ID',
			'pes_jornada' => 'Pes Jornada',
			'pes_cod_carrera' => 'Pes Cod Carrera',
			'pes_carrera' => 'Pes Carrera',
			'pes_dni' => 'Pes Dni',
			'pes_nombres' => 'Pes Nombres',
			'pes_egresado' => 'Pes Egresado',
			'pes_tutoria_nombre' => 'Pes Tutoria Nombre',
			'pes_tutoria_cod' => 'Pes Tutoria Cod',
			'pes_cod_malla' => 'Pes Cod Malla',
			'pes_mat_b1_h1_nombre' => 'Pes Mat B1 H1 Nombre',
			'pes_mat_b1_h1_cod' => 'Pes Mat B1 H1 Cod',
			'pes_mod_b1_h1' => 'Pes Mod B1 H1',
			'pes_jor_b1_h1' => 'Pes Jor B1 H1',
			'pes_mat_b1_h2_nombre' => 'Pes Mat B1 H2 Nombre',
			'pes_mat_b1_h2_cod' => 'Pes Mat B1 H2 Cod',
			'pes_mod_b1_h2' => 'Pes Mod B1 H2',
			'pes_jor_b1_h2' => 'Pes Jor B1 H2',
			'pes_mat_b1_h3_nombre' => 'Pes Mat B1 H3 Nombre',
			'pes_mat_b1_h3_cod' => 'Pes Mat B1 H3 Cod',
			'pes_mod_b1_h3' => 'Pes Mod B1 H3',
			'pes_jor_b1_h3' => 'Pes Jor B1 H3',
			'pes_mat_b1_h4_nombre' => 'Pes Mat B1 H4 Nombre',
			'pes_mat_b1_h4_cod' => 'Pes Mat B1 H4 Cod',
			'pes_mod_b1_h4' => 'Pes Mod B1 H4',
			'pes_jor_b1_h4' => 'Pes Jor B1 H4',
			'pes_mat_b1_h5_nombre' => 'Pes Mat B1 H5 Nombre',
			'pes_mat_b1_h5_cod' => 'Pes Mat B1 H5 Cod',
			'pes_mod_b1_h5' => 'Pes Mod B1 H5',
			'pes_jor_b1_h5' => 'Pes Jor B1 H5',
			'pes_mat_b1_h6_nombre' => 'Pes Mat B1 H6 Nombre',
			'pes_mat_b1_h6_cod' => 'Pes Mat B1 H6 Cod',
			'pes_mod_b1_h6' => 'Pes Mod B1 H6',
			'pes_jor_b1_h6' => 'Pes Jor B1 H6',
			'pes_mat_b2_h1_nombre' => 'Pes Mat B2 H1 Nombre',
			'pes_mat_b2_h1_cod' => 'Pes Mat B2 H1 Cod',
			'pes_mod_b2_h1' => 'Pes Mod B2 H1',
			'pes_jor_b2_h1' => 'Pes Jor B2 H1',
			'pes_mat_b2_h2_nombre' => 'Pes Mat B2 H2 Nombre',
			'pes_mat_b2_h2_cod' => 'Pes Mat B2 H2 Cod',
			'pes_mod_b2_h2' => 'Pes Mod B2 H2',
			'pes_jor_b2_h2' => 'Pes Jor B2 H2',
			'pes_mat_b2_h3_nombre' => 'Pes Mat B2 H3 Nombre',
			'pes_mat_b2_h3_cod' => 'Pes Mat B2 H3 Cod',
			'pes_mod_b2_h3' => 'Pes Mod B2 H3',
			'pes_jor_b2_h3' => 'Pes Jor B2 H3',
			'pes_mat_b2_h4_nombre' => 'Pes Mat B2 H4 Nombre',
			'pes_mat_b2_h4_cod' => 'Pes Mat B2 H4 Cod',
			'pes_mod_b2_h4' => 'Pes Mod B2 H4',
			'pes_jor_b2_h4' => 'Pes Jor B2 H4',
			'pes_mat_b2_h5_nombre' => 'Pes Mat B2 H5 Nombre',
			'pes_mat_b2_h5_cod' => 'Pes Mat B2 H5 Cod',
			'pes_mod_b2_h5' => 'Pes Mod B2 H5',
			'pes_jor_b2_h5' => 'Pes Jor B2 H5',
			'pes_mat_b2_h6_nombre' => 'Pes Mat B2 H6 Nombre',
			'pes_mat_b2_h6_cod' => 'Pes Mat B2 H6 Cod',
			'pes_mod_b2_h6' => 'Pes Mod B2 H6',
			'pes_jor_b2_h6' => 'Pes Jor B2 H6',
			'pes_estado' => 'Pes Estado',
			'pes_fecha_creacion' => 'Pes Fecha Creacion',
			'pes_usuario_modifica' => 'Pes Usuario Modifica',
			'pes_fecha_modificacion' => 'Pes Fecha Modificacion',
			'pes_estado_logico' => 'Pes Estado Logico',
		];
	}

	/**
	 * Gets query for [[Pla]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getPla() {
		return $this->hasOne(Planificacion::className(), ['pla_id' => 'pla_id']);
	}
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRegistroOnlines() {
		return $this->hasMany(RegistroOnline::className(), ['pes_id' => 'pes_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAllPlanificacionesGrid($search = NULL, $dataProvider = false) {
		$iduser = Yii::$app->session->get('PB_iduser', FALSE);
		$search_cond = "%" . $search . "%";
		$str_search = "";
		if (isset($search)) {
			$str_search = "(do.doc_uni_departamento like :search OR ";
			$str_search .= "do.doc_proceso like :search) AND ";
		}
		$sql = "SELECT
                    do.doc_id as id,
                    do.doc_uni_departamento as Departamento,
                    do.doc_proceso as Proceso,
                    do.doc_tipo_informacion as TipoInfo,
                    do.doc_observaciones as Observaciones,
                    do.doc_estado_documento as Estado,
                FROM
                    documento as do
                    inner join clase as cla on cla.cla_id = do.cla_id
                    inner join serie as ser on ser.ser_id = do.ser_id
                    inner join subserie as sub on sub.sub_id = do.sub_id
                WHERE
                    $str_search
                    do.doc_estado_logico=1 and
                    cla.cla_estado_logico=1 and
                    ser.ser_estado_logico=1 and
                    sub.sub_estado_logico=1
                    ORDER BY do.doc_id;";
		$comando = Yii::$app->db_documental->createCommand($sql);
		if (isset($search)) {
			$comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
		}
		$res = $comando->queryAll();
		foreach ($res as $key => $valor) {
			foreach ($valor as $key2 => $valor2) {
				if (strcasecmp($key2, "TipoInfo") == 0) {
					$res[$key][$key2] = $this->getValueTipoInformacionByKey($valor2);
				}

				if (strcasecmp($key2, "Estado") == 0) {
					$res[$key][$key2] = $this->getValueEstadoDocumentoByKey($valor2);
				}
			}
		}

		if ($dataProvider) {
			$dataProvider = new ArrayDataProvider([
				'key' => 'doc_id',
				'allModels' => $res,
				'pagination' => [
					'pageSize' => Yii::$app->params["pageSize"],
				],
				'sort' => [
					'attributes' => ['Departamento', 'Proceso', 'Codigo', 'TipoInfo', 'Observaciones', 'Estado'],
				],
			]);
			return $dataProvider;
		}
		return $res;
	}

	/**
	 * Function obtener datos para programacion de materias siga
	 * @author   Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>
	 * @property
	 * @return
	 */

	public function generateDatatotrasfer($pla_id) {
		$con = \Yii::$app->db_academico;
		switch ($pla_id) {
		case 35:
			$mod_id = 2;
			break;
		case 36:
			$mod_id = 4;
			break;
		case 37:
			$mod_id = 3;
			break;
		case 38:
			$mod_id = 1;
			break;
		}

		$procc = "
select mpp_id, mpp.mpp_num_paralelo,made.asi_id, made.maca_id, pes.pla_id, mpp.mod_id, 1 as uaca_id, mpp.paca_id
from db_academico.planificacion_estudiante pes
inner join db_academico.malla_academica_detalle made on made.made_codigo_asignatura = pes.pes_mat_b1_h1_cod
or made.made_codigo_asignatura = pes.pes_mat_b1_h2_cod or made.made_codigo_asignatura = pes.pes_mat_b1_h3_cod
or made.made_codigo_asignatura = pes.pes_mat_b1_h4_cod or made.made_codigo_asignatura = pes.pes_mat_b1_h5_cod
or made.made_codigo_asignatura = pes.pes_mat_b1_h6_cod or made.made_codigo_asignatura = pes.pes_mat_b2_h1_cod
or made.made_codigo_asignatura = pes.pes_mat_b2_h2_cod or made.made_codigo_asignatura = pes.pes_mat_b2_h3_cod
or made.made_codigo_asignatura = pes.pes_mat_b2_h4_cod or made.made_codigo_asignatura = pes.pes_mat_b2_h5_cod
or made.made_codigo_asignatura = pes.pes_mat_b2_h6_cod
inner join db_academico.materia_paralelo_periodo mpp on mpp.asi_id = made.asi_id and mpp.paca_id in (28,29) and mpp.mod_id = :mod_id
 where pla_id = :pla_id;
 ";

		$comando = $con->createCommand($procc);
		$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
		$comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
		$pars = $comando->queryAll();

		if (count($pars) > 0) {
			for ($i = 0; $i < count($pars); $i++) {

				$pla_api = $pars[$i]["pla_id"];
				$asi_api = $pars[$i]["asi_id"];
				$mod_api = $pars[$i]["mod_id"];
				$maca_api = $pars[$i]["maca_id"];
				$uaca_api = $pars[$i]["uaca_id"];
				$mpp_api = $pars[$i]["mpp_id"];
				$mpp_num = $pars[$i]["mpp_num_paralelo"];
				if ($pars[$i]["paca_id"] == 28) {
					$bloq_api = 1;
				}
				if ($pars[$i]["paca_id"] == 29) {
					$bloq_api = 2;
				}

				$searchparsiiga = "
                    SELECT  pasi_id,pasi_cantidad  from db_academico.paralelos_siiga
                    WHERE pla_id = :pla_id
                    AND asi_id = :asi_id
                    AND mod_id = :mod_id
                    AND maca_id = :maca_id
                    AND uaca_id = :uaca_id
                    AND bloq_id = :bloq_id
                    AND mpp_id = :mpp_id
                    ";

				$comando = $con->createCommand($searchparsiiga);
				$comando->bindParam(":pla_id", $pla_api, \PDO::PARAM_INT);
				$comando->bindParam(":asi_id", $asi_api, \PDO::PARAM_INT);
				$comando->bindParam(":mod_id", $mod_api, \PDO::PARAM_INT);
				$comando->bindParam(":maca_id", $maca_api, \PDO::PARAM_INT);
				$comando->bindParam(":uaca_id", $uaca_api, \PDO::PARAM_INT);
				$comando->bindParam(":bloq_id", $bloq_api, \PDO::PARAM_INT);
				$comando->bindParam(":mpp_id", $mpp_api, \PDO::PARAM_INT);
				$existparsiiga = $comando->queryOne();

				if ($existparsiiga["pasi_id"] == Null) {

					$createpartemp =
						"
                INSERT INTO db_academico.paralelos_siiga
    (pla_id,asi_id,mod_id,maca_id,uaca_id,bloq_id,mpp_id,siiga_paralelo,pasi_cantidad,pasi_usuario_ingreso,pasi_estado,pasi_fecha_creacion,pasi_estado_logico)

                VALUES
    ('" . $pla_api . "','" . $asi_api . "','" . $mod_api . "','" . $maca_api . "','" . $uaca_api . "','" . $bloq_api . "','" . $mpp_api . "','" . $mpp_num . "','1','1', '1',
 '2021-08-30 17:10:53', '1')
                ";

					$comando = $con->createCommand($createpartemp);
					$fillpars = $comando->execute();

				} Else {

					$cantpasi = $existparsiiga["pasi_cantidad"] + 1;
					$pasi_id = $existparsiiga["pasi_id"];
					if ($cantpasi > 50) {$cantpasi = 50;}
					$updatepasicant = "
                UPDATE db_academico.paralelos_siiga SET pasi_cantidad = $cantpasi
                WHERE pasi_id = $pasi_id";
					$comando = $con->createCommand($updatepasicant);
					$result = $comando->execute();

				}

			}}

		$getallbymod = "
        SELECT DISTINCT  a.pasi_id,a.pla_id, a.asi_id,a.mod_id, a.maca_id, a.mpp_id, a.uaca_id,a.bloq_id, a.pasi_cantidad,f.smad_cod_legal, f.sasi_id, a.siiga_paralelo
        FROM db_academico.paralelos_siiga a
        INNER JOIN db_academico.planificacion b ON a.pla_id = b.pla_id
        INNER JOIN db_academico.asignatura c ON a.asi_id = c.asi_id
        INNER JOIN db_academico.malla_academica d ON a.maca_id = d.maca_id
        INNER JOIN db_academico.modalidad e ON a.mod_id = e.mod_id
        INNER JOIN db_academico.siga_malla_academica_detalle f
        ON f.asi_id  =  a.asi_id AND f.maca_id = a.maca_id
        INNER JOIN db_academico.unidad_academica g ON g.uaca_id = a.uaca_id
        INNER JOIN db_academico.semestre_academico h ON h.saca_id = b.saca_id
        INNER JOIN db_academico.periodo_academico i ON i.saca_id = h.saca_id
        INNER JOIN db_academico.bloque_academico j ON j.baca_id = i.baca_id
        WHERE
        a.pla_id = :pla_id AND
         a.pasi_estado = 1 AND
         a.pasi_estado_logico = 1
        ";

		$comando = $con->createCommand($getallbymod);
		$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
		$programacion_siga = $comando->queryAll();

		return $programacion_siga;

	}

	public function generateDatatotrasferauto($pla_id) {
		$con = \Yii::$app->db_academico;
		$getallbymod = "
        SELECT
        concat(j.baca_anio,' ',j.baca_descripcion,' ',
        SUBSTR(e.mod_nombre,1,3),' ',j.baca_nombre) as periodo_nombre,
        concat('SEMESTRE ', j.baca_descripcion,' ',j.baca_anio,' ',
        e.mod_nombre,' Bloque ',j.baca_nombre) as periodo_descripcion,
        i.paca_fecha_inicio as periodo_inicio,i.paca_fecha_fin as periodo_fin,
        j.baca_anio as periodo_anio,a.siiga_paralelo as paralelo,
        a.pasi_cantidad as alumnos,f.sasi_id as materia_siga,
        0 as modalidad_siga,0 as docente_siga, b.pla_id as periodo_lectivo_siga
        FROM db_academico.paralelos_siiga a
        INNER JOIN db_academico.planificacion b on a.pla_id = b.pla_id
        INNER JOIN db_academico.asignatura c on a.asi_id = c.asi_id
        INNER JOIN db_academico.malla_academica d on a.maca_id = d.maca_id
        INNER JOIN db_academico.modalidad e on a.mod_id = e.mod_id
        INNER JOIN db_academico.siga_malla_academica_detalle f
        on f.asi_id  =  a.asi_id and f.mod_id = a.mod_id
        INNER JOIN db_academico.unidad_academica g on g.uaca_id = a.uaca_id
        INNER JOIN db_academico.semestre_academico h on h.saca_id = b.saca_id
        INNER JOIN db_academico.periodo_academico i on i.saca_id = h.saca_id
        INNER JOIN db_academico.bloque_academico j on j.baca_id = i.baca_id
        WHERE a.pla_id = :pla_id
        ";

		$getallbymod = "
        SELECT DISTINCT  a.pasi_id,a.pla_id, a.asi_id,a.mod_id, a.maca_id, a.mpp_id, a.uaca_id,a.bloq_id, a.pasi_cantidad,f.smad_cod_legal, f.sasi_id, a.siiga_paralelo
        FROM db_academico.paralelos_siiga a
        INNER JOIN db_academico.planificacion b ON a.pla_id = b.pla_id
        INNER JOIN db_academico.asignatura c ON a.asi_id = c.asi_id
        INNER JOIN db_academico.malla_academica d ON a.maca_id = d.maca_id
        INNER JOIN db_academico.modalidad e ON a.mod_id = e.mod_id
        INNER JOIN db_academico.siga_malla_academica_detalle f
        ON f.asi_id  =  a.asi_id AND f.maca_id = a.maca_id
        INNER JOIN db_academico.unidad_academica g ON g.uaca_id = a.uaca_id
        INNER JOIN db_academico.semestre_academico h ON h.saca_id = b.saca_id
        INNER JOIN db_academico.periodo_academico i ON i.saca_id = h.saca_id
        INNER JOIN db_academico.bloque_academico j ON j.baca_id = i.baca_id
        WHERE
        a.pla_id = :pla_id AND
         a.pasi_estado = 1 AND
         a.pasi_estado_logico = 1
        ";

		$comando = $con->createCommand($getallbymod);
		$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
		$programacion_siga = $comando->queryAll();

		return $programacion_siga;

	}

	public function processFile($fname, $pla_id) {
		$file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "planificacion/" . $fname;
		$fila = 0;
		$chk_ext = explode(".", $file);
		$con = \Yii::$app->db_academico;
		$trans = $con->getTransaction(); // se obtiene la transacción actual
		/* print("pasa cargar"); */
		if ($trans !== null) {
			$trans = null; // si existe la transacción entonces no se crea una
		} else {
			$trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
		}
		/* print_r($chk_ext); */
		if (strtolower(end($chk_ext)) == "xls" || strtolower(end($chk_ext)) == "xlsx") {
			//Creacion de PHPExcel object
			try {
				$objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
				$dataArr = array();
				$validacion = false;
				$row_global = 0;

				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle = $worksheet->getTitle();
					$highestRow = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'
					$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
					/* print('######');
						                      print($worksheetTitle);
						                      print(";");
						                      print($highestRow);
						                      print(";");
						                      print($highestColumn);
						                      print(";");
						                      print($highestColumnIndex);
					*/
					//lectura del Archivo XLS filas y Columnas
					for ($row = 2; $row <= $highestRow; ++$row) {
						$row_global = $row_global + 1;
						for ($col = 1; $col <= $highestColumnIndex; ++$col) {
							$cell = $worksheet->getCellByColumnAndRow($col, $row);
							$dataArr[$row_global][$col] = $cell->getValue();
						}
					}
					/* unset($dataArr[1], $dataArr[2]);                     */
				}

				/*  return $dataArr; */
				/* print($row_global . "\n");
					                  print(sizeof($dataArr) . "\n");
				*/

				$fila = 0;
				foreach ($dataArr as $val) {
					/* print("#");
						                      print(gettype($val[1]));
					*/
					if (!is_null($val[4]) || $val[4]) {
						$val[4] = strval($val[4]);
						$per_id_estudiante = Persona::ObtenerPersonabyCedulaPasaporteRuc($val[4], $val[4], $val[4]);
						$fila++;
						/* print(strlen($val[5]));
                          print($ser_cod_str); */
						/* print(gettype($ser_cod_str)); */
						/* print("AntesGuardar"); */

						$save_documento = $this->saveDocumentoDB($val, $pla_id, $per_id_estudiante);
						if (!$save_documento) {
							/* print("IFErrorSave"); */
							$arroout["status"] = FALSE;
							$arroout["error"] = null;
							$arroout["message"] = "Error al guardar el registro de la Fila => N°$fila Cedula => $val[4]. No se encontró al estudiante.";
							$arroout["data"] = null;
							$arroout["validate"] = $val;
							\app\models\Utilities::putMessageLogFile('error fila ' . $fila);
							return $arroout;
						}
					}
				}
				/* print("AfterFOR"); */
				if ($trans !== null) {
					$trans->commit();
				}

				// print_r($dataArr);
				$arroout["status"] = TRUE;
				$arroout["error"] = null;
				$arroout["message"] = null;
				$arroout["data"] = null;
				$arroout["validate"] = $fila;
				return $arroout;
			} catch (\Exception $ex) {
				if ($trans !== null) {
					$trans->rollback();
				}

				$arroout["status"] = FALSE;
				$arroout["error"] = null;
				$arroout["message"] = null;
				$arroout["data"] = null;
				return $arroout;
			}
		}
	}

	public function cerrarPlanificacionaut($pla_id) {
		$con = \Yii::$app->db_academico;
		$sql = "UPDATE db_academico.planificacion SET pla_path = '0'
                WHERE pla_id = $pla_id";
		$comando = $con->createCommand($sql);
		$result = $comando->execute();

		return $result;

	}

	public function saveDocumentoDB($val, $pla_id, $per_id_estudiante) {

		$model_planificacion_estudiante = new PlanificacionEstudiante();
		$model_planificacion_estudiante->pla_id = $pla_id;

		$model_planificacion_estudiante->per_id = $per_id_estudiante;
		//$model_planificacion_estudiante->pes_jornada = $val[1];
		$model_planificacion_estudiante->pes_cod_malla = $val[1];
		/*  $model_planificacion_estudiante->pes_cod_carrera = $val[2]; */
		//bloque 1
		$model_planificacion_estudiante->pes_carrera = $val[3];
		$model_planificacion_estudiante->pes_dni = strval($val[4]);
		$model_planificacion_estudiante->pes_nombres = $val[5];
		\app\models\Utilities::putMessageLogFile('dni: ' . $val[4]);
		\app\models\Utilities::putMessageLogFile('val[6]: ' . $val[6]);
		if (!empty($val[6])) {
			$materia_id1 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[6]);
			$model_planificacion_estudiante->pes_mat_b1_h1_cod = $materia_id1['made_codigo_asignatura'];
			\app\models\Utilities::putMessageLogFile('val[1]: ' . $val[1]);
			\app\models\Utilities::putMessageLogFile('made_codigo_asignatura: ' . $materia_id1['made_codigo_asignatura']);
		}
		//$model_planificacion_estudiante->pes_mat_b1_h1_cod = $val[6];
		$model_planificacion_estudiante->pes_mod_b1_h1 = $val[7];
		$model_planificacion_estudiante->pes_jor_b1_h1 = $val[8];
		if (!empty($val[9])) {
			$materia_id2 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[9]);
			$model_planificacion_estudiante->pes_mat_b1_h2_cod = $materia_id2['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b1_h2_cod = $val[9];
		$model_planificacion_estudiante->pes_mod_b1_h2 = $val[10];
		$model_planificacion_estudiante->pes_jor_b1_h2 = $val[11];
		if (!empty($val[12])) {
			$materia_id3 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[12]);
			$model_planificacion_estudiante->pes_mat_b1_h3_cod = $materia_id3['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b1_h3_cod = $val[12];
		$model_planificacion_estudiante->pes_mod_b1_h3 = $val[13];
		$model_planificacion_estudiante->pes_jor_b1_h3 = $val[14];
		if (!empty($val[15])) {
			$materia_id4 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[15]);
			$model_planificacion_estudiante->pes_mat_b1_h4_cod = $materia_id4['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b1_h4_cod = $val[15];
		$model_planificacion_estudiante->pes_mod_b1_h4 = $val[16];
		$model_planificacion_estudiante->pes_jor_b1_h4 = $val[17];
		if (!empty($val[18])) {
			$materia_id5 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[18]);
			$model_planificacion_estudiante->pes_mat_b1_h5_cod = $materia_id5['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b1_h5_cod = $val[18];
		$model_planificacion_estudiante->pes_mod_b1_h5 = $val[19];
		$model_planificacion_estudiante->pes_jor_b1_h5 = $val[20];
		if (!empty($val[21])) {
			$materia_id6 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[21]);
			$model_planificacion_estudiante->pes_mat_b1_h6_cod = $materia_id6['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b1_h6_cod = $val[21];
		$model_planificacion_estudiante->pes_mod_b1_h6 = $val[22];
		$model_planificacion_estudiante->pes_jor_b1_h6 = $val[23];
		//bloque 2
		if (!empty($val[24])) {
			$materia_id7 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[24]);
			$model_planificacion_estudiante->pes_mat_b2_h1_cod = $materia_id7['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b2_h1_cod = $val[24];
		$model_planificacion_estudiante->pes_mod_b2_h1 = $val[25];
		$model_planificacion_estudiante->pes_jor_b2_h1 = $val[26];
		if (!empty($val[27])) {
			$materia_id8 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[27]);
			$model_planificacion_estudiante->pes_mat_b2_h2_cod = $materia_id8['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b2_h2_cod = $val[27];
		$model_planificacion_estudiante->pes_mod_b2_h2 = $val[28];
		$model_planificacion_estudiante->pes_jor_b2_h2 = $val[29];
		if (!empty($val[30])) {
			$materia_id9 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[30]);
			$model_planificacion_estudiante->pes_mat_b2_h3_cod = $materia_id9['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b2_h3_cod = $val[30];
		$model_planificacion_estudiante->pes_mod_b2_h3 = $val[31];
		$model_planificacion_estudiante->pes_jor_b2_h3 = $val[32];
		if (!empty($val[33])) {
			$materia_id10 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[33]);
			$model_planificacion_estudiante->pes_mat_b2_h4_cod = $materia_id10['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b2_h4_cod = $val[33];
		$model_planificacion_estudiante->pes_mod_b2_h4 = $val[34];
		$model_planificacion_estudiante->pes_jor_b2_h4 = $val[35];
		if (!empty($val[36])) {
			$materia_id11 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[36]);
			$model_planificacion_estudiante->pes_mat_b2_h5_cod = $materia_id11['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b2_h5_cod = $val[36];
		$model_planificacion_estudiante->pes_mod_b2_h5 = $val[37];
		$model_planificacion_estudiante->pes_jor_b2_h5 = $val[38];
		if (!empty($val[39])) {
			$materia_id12 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[39]);
			$model_planificacion_estudiante->pes_mat_b2_h6_cod = $materia_id12['made_codigo_asignatura'];
		}
		//$model_planificacion_estudiante->pes_mat_b2_h6_cod = $val[39];
		$model_planificacion_estudiante->pes_mod_b2_h6 = $val[40];
		$model_planificacion_estudiante->pes_jor_b2_h6 = $val[41];
		$model_planificacion_estudiante->pes_estado = "1";
		$model_planificacion_estudiante->pes_estado_logico = "1";
		/* if($val[4] == "0925029605") {
			          var_dump($model_planificacion_estudiante);
			          }
		*/
		/* print($model_planificacion_estudiante); */

		return $model_planificacion_estudiante->save();
	}

	public static function getCarreras() {
		$con_academico = \Yii::$app->db_academico;
		$sql = "SELECT @row_number:=@row_number+1 as pes_id, pes_carrera " .
		"FROM " . Yii::$app->db_academico->dbname . ".planificacion_estudiante, (SELECT @row_number:=0) AS t " .
			"WHERE pes_estado=1 AND pes_estado_logico=1 " .
			"GROUP BY pes_carrera";

		$comando = $con_academico->createCommand($sql);
		$resultData = $comando->queryAll();

		return $resultData;
	}

	/**
	 * Function consulta los periodos academcicos en tablas de planificacion.
	 * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
	 * @param
	 * @return
	 */
	public function consultarPeriodoplanifica() {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		$condition = "";

		$sql = "SELECT distinct pla_periodo_academico as id,
                pla_periodo_academico as name
                  FROM " . $con->dbname . ".planificacion
                  WHERE pla_estado = :estado AND
                  pla_estado_logico = :estado;";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$resultData = $comando->queryAll();
		return $resultData;
	}

	/**
	 * Function consultarEstudianteplanifica
	 * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
	 * @property
	 * @return
	 */
	public function consultarEstudianteplanifica($arrFiltro = array(), $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;
		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			$str_search .= "(pers.per_pri_nombre like :estudiante OR ";
			$str_search .= "pers.per_seg_nombre like :estudiante OR ";
			$str_search .= "pers.per_pri_apellido like :estudiante OR ";
			$str_search .= "pers.per_seg_nombre like :estudiante OR ";
			$str_search .= "pers.per_cedula like :estudiante)  AND ";

			if ($arrFiltro['modalidad'] > 0) {
				$str_search .= " plan.mod_id = :modalidad AND ";
			}

			if ($arrFiltro['carrera'] != 'Todas') {
				$str_search .= " plae.pes_carrera like :carrera AND ";
			}

			if ($arrFiltro['periodo'] != '0') {
				$str_search .= " plan.pla_periodo_academico = :periodo AND ";
			}
		}
		if ($onlyData == false) {
			$idplanifica = 'plae.pla_id, ';
			$idper = 'plae.per_id, ';
		}
		$sql = "SELECT
                    $idplanifica
                    $idper
                    pers.per_cedula,
                    plae.pes_nombres,
                    plae.pes_carrera,
                    plan.pla_periodo_academico
                FROM " . $con->dbname . ".planificacion_estudiante plae
                INNER JOIN " . $con->dbname . ".planificacion plan ON plan.pla_id = plae.pla_id
                INNER JOIN " . $con1->dbname . ".persona pers ON pers.per_id = plae.per_id
                WHERE
                    $str_search
                    plae.pes_estado = :estado AND
                    plae.pes_estado_logico = :estado AND
                    plan.pla_estado = :estado AND
                    plan.pla_estado_logico = :estado AND
                    pers.per_estado = :estado AND
                    pers.per_estado_logico = :estado";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			$search_cond = "%" . $arrFiltro["estudiante"] . "%";
			$comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);

			if ($arrFiltro['modalidad'] > 0) {
				$modalidad = $arrFiltro["modalidad"];
				$comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
			}

			if ($arrFiltro['carrera'] != 'Todas') {
				$search_carrera = "%" . $arrFiltro["carrera"] . "%";
				$comando->bindParam(":carrera", $search_carrera, \PDO::PARAM_STR);
			}

			if ($arrFiltro['periodo'] != '0') {
				$periodo = $arrFiltro["periodo"];
				$comando->bindParam(":periodo", $periodo, \PDO::PARAM_STR);
			}
		}

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

	public function consultarEstudianteplanificaPdf($arrFiltro = array(), $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;
		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			$str_search .= "(pers.per_pri_nombre like :estudiante OR ";
			$str_search .= "pers.per_seg_nombre like :estudiante OR ";
			$str_search .= "pers.per_pri_apellido like :estudiante OR ";
			$str_search .= "pers.per_seg_nombre like :estudiante OR ";
			$str_search .= "pers.per_cedula like :estudiante)  AND ";

			if ($arrFiltro['modalidad'] > 0) {
				$str_search .= " plan.mod_id = :modalidad AND ";
			}

			if ($arrFiltro['carrera'] != 'Todas') {
				$str_search .= " plae.pes_carrera like :carrera AND ";
			}

			if ($arrFiltro['periodo'] != '0') {
				$str_search .= " plan.pla_periodo_academico = :periodo AND ";
			}
		}
		if ($onlyData == false) {
			$idplanifica = 'plae.pla_id, ';
			$idper = 'plae.per_id, ';
		}
		$sql = "SELECT
                    $idplanifica
                    $idper
                    pers.per_cedula,
                    plae.pes_nombres,
                    plae.pes_carrera,
                    plan.pla_periodo_academico,
                    mad.made_codigo_asignatura,/**/
                    asig.asi_nombre
                    /*asig2.asi_nombre *//*ComentDBE*/
                FROM " . $con->dbname . ".planificacion_estudiante plae
                INNER JOIN " . $con->dbname . ".planificacion plan ON plan.pla_id = plae.pla_id
                INNER JOIN " . $con1->dbname . ".persona pers ON pers.per_id = plae.per_id
                INNER JOIN " . $con->dbname . ".malla_academica_detalle mad ON
                    mad.made_codigo_asignatura in (plae.pes_mat_b1_h1_cod ,plae.pes_mat_b1_h2_cod
                        ,plae.pes_mat_b1_h3_cod
                        ,plae.pes_mat_b1_h4_cod
                        ,plae.pes_mat_b1_h5_cod
                        ,plae.pes_mat_b1_h6_cod
                        ,plae.pes_mat_b2_h1_cod
                        ,plae.pes_mat_b2_h2_cod
                        ,plae.pes_mat_b2_h3_cod
                        ,plae.pes_mat_b2_h4_cod
                        ,plae.pes_mat_b2_h5_cod
                        ,plae.pes_mat_b2_h6_cod)
                INNER JOIN " . $con->dbname . ".asignatura asig ON
                    asig.asi_id = mad.asi_id

                WHERE
                    $str_search
                    plae.pes_estado = :estado AND
                    plae.pes_estado_logico = :estado AND
                    plan.pla_estado = :estado AND
                    plan.pla_estado_logico = :estado AND
                    pers.per_estado = :estado AND
                    pers.per_estado_logico = :estado";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			$search_cond = "%" . $arrFiltro["estudiante"] . "%";
			$comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);

			if ($arrFiltro['modalidad'] > 0) {
				$modalidad = $arrFiltro["modalidad"];
				$comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
			}

			if ($arrFiltro['carrera'] != 'Todas') {
				$search_carrera = "%" . $arrFiltro["carrera"] . "%";
				$comando->bindParam(":carrera", $search_carrera, \PDO::PARAM_STR);
			}

			if ($arrFiltro['periodo'] != '0') {
				$periodo = $arrFiltro["periodo"];
				$comando->bindParam(":periodo", $periodo, \PDO::PARAM_STR);
			}
		}

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
	 * Function elimina planificacion de estudiante.
	 * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
	 * @param
	 * @return
	 */
	public function eliminarPlanificacionest($pla_id, $per_id, $pes_usuario_modifica, $pes_estado, $pes_fecha_modificacion) {

		$con = \Yii::$app->db_academico;

		if ($trans !== null) {
			$trans = null; // si existe la transacción entonces no se crea una
		} else {
			$trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
		}
		try {
			$comando = $con->createCommand
				("UPDATE " . $con->dbname . ".planificacion_estudiante
                      SET pes_estado = :pes_estado,
                          pes_usuario_modifica = :pes_usuario_modifica,
                          pes_fecha_modificacion = :pes_fecha_modificacion
                      WHERE
                        pla_id = :pla_id AND per_id = :per_id");
			$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
			$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
			$comando->bindParam(":pes_usuario_modifica", $pes_usuario_modifica, \PDO::PARAM_INT);
			$comando->bindParam(":pes_fecha_modificacion", $pes_fecha_modificacion, \PDO::PARAM_STR);
			$comando->bindParam(":pes_estado", $pes_estado, \PDO::PARAM_STR);
			$response = $comando->execute();
			if ($trans !== null) {
				$trans->commit();
			}

			return $response;
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();
			}

			return FALSE;
		}
	}

	/**
	 * Function consultarDetalleplanifica
	 * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
	 * @param
	 * @return  $resultData (información de los paralelos por período.)
	 */
	public function consultarDetalleplanifica($pla_id, $per_id, $onlyData = false) {
		$con = \Yii::$app->db_academico;
		// Bloque 1
		for ($i = 1; $i < 7; $i++) {
			$sql .= "SELECT pes_id as Ids, pes_jor_b1_h" . $i . " as jor_materia, pes_mat_b1_h" . $i . "_cod as cod_asignatura, asig.asi_nombre as asignatura, CASE pes_jornada
                            WHEN 'M' THEN 'Matutino'
                            WHEN 'N' THEN 'Nocturno'
                            WHEN 'S' THEN 'Semipresencial'
                            WHEN 'D' THEN 'Distancia'
		    END AS pes_jornada, 'Bloque 1', moda.mod_nombre as modalidad, 'Hora " . $i . "'
                    FROM " . $con->dbname . ".planificacion_estudiante ples
                    INNER JOIN " . $con->dbname . ".modalidad moda ON  moda.mod_id = ples.pes_mod_b1_h" . $i . "
                    INNER JOIN " . $con->dbname . ".malla_academica_detalle mad ON  mad.made_codigo_asignatura = pes_mat_b1_h" . $i . "_cod
                    INNER JOIN " . $con->dbname . ".asignatura asig ON  asig.asi_id = mad.asi_id
                    where pla_id = " . $pla_id . " and per_id = " . $per_id . "
                    UNION ";
		}
		// Bloque 2
		for ($j = 1; $j < 7; $j++) {
			$sql .= "SELECT pes_id as Ids, pes_jor_b2_h" . $j . " as jor_materia, pes_mat_b2_h" . $j . "_cod as cod_asignatura, asig.asi_nombre as asignatura, CASE pes_jornada
                            WHEN 'M' THEN 'Matutino'
                            WHEN 'N' THEN 'Nocturno'
                            WHEN 'S' THEN 'Semipresencial'
                            WHEN 'D' THEN 'Distancia'
		    END AS pes_jornada, 'Bloque 2', moda.mod_nombre as modalidad, 'Hora " . $j . "'
                    FROM " . $con->dbname . ".planificacion_estudiante ples
                    INNER JOIN " . $con->dbname . ".modalidad moda ON  moda.mod_id = ples.pes_mod_b2_h" . $j . "
                    INNER JOIN " . $con->dbname . ".malla_academica_detalle mad ON  mad.made_codigo_asignatura = pes_mat_b2_h" . $j . "_cod
                    INNER JOIN " . $con->dbname . ".asignatura asig ON  asig.asi_id = mad.asi_id
                    where pla_id = " . $pla_id . " and per_id = " . $per_id . " ";
			if ($j < 6) {
				$sql .= "UNION ";
			}
		}

		$comando = $con->createCommand($sql);
		$resultData = $comando->queryall();
		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $resultData,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => [],
			],
		]);
		if ($onlyData) {
			return $resultData;
		} else {
			return $dataProvider;
		}
	}

	/**
	 * Function Consultar datos de cabecera para palnificacion.
	 * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
	 * @property
	 * @return
	 */
	public function consultarCabeceraplanifica($pla_id, $per_id) {
		$con = \Yii::$app->db_academico;

		$sql = "SELECT plan.mod_id,
                       plan.pla_periodo_academico,
                       plae.pes_carrera
                FROM " . $con->dbname . ".planificacion plan
                INNER JOIN " . $con->dbname . ".planificacion_estudiante plae ON plae.pla_id = plan.pla_id
                WHERE plan.pla_id = :pla_id and plae.per_id = :per_id";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
		$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
		$resultData = $comando->queryOne();
		return $resultData;
	}

	/**
	 * Function elimina materia de la planifiacion.
	 * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
	 * @param
	 * @return
	 */
	public function eliminarPlanmatest($pla_id, $per_id, $bloque, $hora, $pes_usuario_modifica, $pes_estado, $pes_fecha_modificacion) {

		$con = \Yii::$app->db_academico;

		if ($trans !== null) {
			$trans = null; // si existe la transacción entonces no se crea una
		} else {
			$trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
		}
		if (!empty($bloque) && !empty($hora)) {
			$modificar = "pes_mat_b" . $bloque . "_h" . $hora . "_cod = null,
                      pes_mat_b" . $bloque . "_h" . $hora . "_mpp = null,
                      pes_jor_b" . $bloque . "_h" . $hora . " = null,
                      pes_mod_b" . $bloque . "_h" . $hora . " = null,
                      pes_mat_b" . $bloque . "_h" . $hora . "_nombre = null,";
		}
		$este = "UPDATE db_academico.planificacion_estudiante
                      SET
                          $modificar
                          pes_usuario_modifica = $pes_usuario_modifica,
                          pes_fecha_modificacion = $pes_fecha_modificacion
                      WHERE
                        pes_estado= 1 AND pla_id = $pla_id AND per_id = $per_id";
		try {
			$comando = $con->createCommand
				("UPDATE " . $con->dbname . ".planificacion_estudiante
                      SET
                          $modificar
                          pes_usuario_modifica = :pes_usuario_modifica,
                          pes_fecha_modificacion = :pes_fecha_modificacion
                      WHERE
                        pes_estado= :pes_estado AND pla_id = :pla_id AND per_id = :per_id");
			$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
			$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
			$comando->bindParam(":pes_usuario_modifica", $pes_usuario_modifica, \PDO::PARAM_INT);
			$comando->bindParam(":pes_fecha_modificacion", $pes_fecha_modificacion, \PDO::PARAM_STR);
			$comando->bindParam(":pes_estado", $pes_estado, \PDO::PARAM_STR);
			$response = $comando->execute();
			if ($trans !== null) {
				$trans->commit();
			}

			return $response;
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();
			}

			return FALSE;
		}
	}

	/**
	 * Function insertarDataPlanificacionestudiante
	 * Guiarse de insertarPersona
	 * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
	 * @property integer $userid
	 * @return
	 */
	/* INSERTAR DATOS */
	public function insertarDataPlanificacionestudiante($pla_id, $per_id, $pes_jornada, $pes_carrera, $pes_dni, $pes_nombres, $pes_cod_malla, $insertar, $valores) {
		$arroout = array();
		$con = \Yii::$app->db_academico;
		$trans = $con->beginTransaction();
		try {
			$data = isset($data['DATA']) ? $data['DATAS'] : array();
			$this->insertarPlanificacionestudiante($con, $pla_id, $per_id, $pes_jornada, $pes_carrera, $pes_dni, $pes_nombres, $pes_cod_malla, $insertar, $valores);
			$trans->commit();
			$con->close();
			//RETORNA DATOS
			$arroout["status"] = true;

			return $arroout;
		} catch (\Exception $e) {
			$trans->rollBack();
			$con->close();
			$arroout["status"] = false;
			return $arroout;
		}
	}

	/**
	 * Function insertarPlanificacionestudiante
	 * Guiarse de insertarPersona
	 * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
	 * @property integer $userid
	 * @return
	 */
	private function insertarPlanificacionestudiante($con, $pla_id, $per_id, $pes_jornada, $pes_carrera, $pes_dni, $pes_nombres, $pes_cod_malla, $insertar, $valores) {
		$estado = 1;
		$sql = "INSERT INTO " . $con->dbname . ".planificacion_estudiante
                    (pla_id,
                     per_id,
                     pes_jornada,
                     pes_carrera,
                     pes_dni,
                     pes_nombres,
                     pes_cod_malla, " .
			$insertar . "
                     pes_estado,
                     pes_estado_logico)VALUES
                    (" . $pla_id . "," . $per_id . ",'" . $pes_jornada . "','" . $pes_carrera . "','" . $pes_dni . "','"
			. $pes_nombres . "'," . $pes_cod_malla . ", " . $valores . " '" . $estado . "','" . $estado . "')";
		$command = $con->createCommand($sql);
		$command->execute();
	}

	/**
	 * Function Consultar modalidad y periodo en planificacion.
	 * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
	 * @property
	 * @return
	 */
	public function consultarDatoscabplanifica($mod_id, $pla_periodo_academico) {
		$con = \Yii::$app->db_academico;
		$estado = 1;

		$sql = "SELECT pla_id
                FROM " . $con->dbname . ".planificacion plan
                WHERE plan.mod_id = :mod_id AND
                      plan.pla_periodo_academico = :pla_periodo_academico AND
                      plan.pla_estado = :estado AND
                      plan.pla_estado_logico = :estado";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
		$comando->bindParam(":pla_periodo_academico", $pla_periodo_academico, \PDO::PARAM_STR);
		$resultData = $comando->queryOne();
		return $resultData;
	}

	/**
	 * Function Consultar id de carrera.
	 * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
	 * @property
	 * @return
	 */
	public function consultardataplan($pla_id, $per_id, $materia) {
		$con = \Yii::$app->db_academico;
		$estado = 1;

		$sql = "SELECT plan.pes_carrera,
                       esta.eaca_id,
                       plan.pes_jornada,
                       plan.pes_nombres,
                       ifnull((SELECT maca.maca_nombre FROM " . $con->dbname . ".malla_academica_detalle made
		                       INNER JOIN " . $con->dbname . ".malla_academica maca ON maca.maca_id = made.maca_id
                               WHERE made_codigo_asignatura = :materia), ' ') as malla
                FROM " . $con->dbname . ".planificacion_estudiante plan
                INNER JOIN " . $con->dbname . ".estudio_academico esta ON esta.eaca_nombre = plan.pes_carrera
                WHERE plan.pla_id = :pla_id AND
                      plan.per_id = :per_id AND
                      plan.pes_estado = :estado AND
                      plan.pes_estado_logico = :estado";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
		$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
		$comando->bindParam(":materia", $materia, \PDO::PARAM_STR);
		$resultData = $comando->queryOne();
		return $resultData;
	}

	/**
	 * Function busca los etudiantes para autocompletar en palnificacion.
	 * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
	 * @param
	 * @return
	 */
	public function busquedaEstudianteplanificacion() {
		$con = \Yii::$app->db_academico;
		$estado = 1;

		$sql = "SELECT est.per_id as id, concat(/*est.per_id, ' - ',*/ pers.per_cedula, ' - ',
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

	public function busquedaEstudianteplanificacionaut($per_id) {
		$con = \Yii::$app->db_academico;
		$estado = 1;

		$sql = "SELECT est.per_id as id, concat(pers.per_cedula, ' - ',
                    ifnull(pers.per_pri_nombre, ' ') ,' ',
                    ifnull(pers.per_pri_apellido,' ')) as name
                    FROM db_academico.estudiante est
                    JOIN db_asgard.persona pers ON pers.per_id = est.per_id
                WHERE pers.per_estado = :estado AND
                      pers.per_estado_logico = :estado AND
                      est.est_estado = :estado AND
                      est.est_estado_logico = :estado AND
                      est.per_id = $per_id limit 0,1;";
		if ($per_id == null) {
			$resultData = [];
		} else {
			$comando = $con->createCommand($sql);
			$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
			$resultData = $comando->queryAll();
		}

		return $resultData;
	}

	/**
	 * Function Consultar modalidad y periodo en planificacion.
	 * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
	 * @property
	 * @return
	 */
	public function consultarAlumnoplan($pla_id, $per_id) {
		$con = \Yii::$app->db_academico;
		$estado = 1;

		$sql = "SELECT count(*) as planexiste
                FROM " . $con->dbname . ".planificacion_estudiante
                WHERE pla_id = :pla_id AND
                      per_id = :per_id AND
                      pes_estado = :estado AND
                      pes_estado_logico = :estado";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
		$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
		$resultData = $comando->queryOne();
		return $resultData;
	}

	/**
	 * Function modificarDataPlanificacionestudiante
	 * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
	 * @property integer $userid
	 * @return
	 */
	/* MODIFICAR DATOS */
	public function modificarDataPlanificacionestudiante($pla_id, $per_id, $pes_usuario_modifica, $modificar) {
		$arroout = array();
		$con = \Yii::$app->db_academico;
		$trans = $con->beginTransaction();
		$fecha = date(Yii::$app->params["dateTimeByDefault"]);
		try {
			$data = isset($data['DATA']) ? $data['DATAS'] : array();
			$this->modificarPlanificacionestudiante($con, $pla_id, $per_id, $pes_usuario_modifica, $fecha, $modificar);
			$trans->commit();
			$this->agregarMateriasPlanificacionEstudiante($pla_id, $per_id);
			$con->close();
			//RETORNA DATOS
			$arroout["status"] = true;

			return $arroout;
		} catch (\Exception $e) {
			$trans->rollBack();
			$con->close();
			$arroout["status"] = false;
			return $arroout;
		}
	}

	/**
	 * Function Modificar planificacion x estudiante.
	 * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
	 * @param
	 * @return
	 */
	public function modificarPlanificacionestudiante($con, $pla_id, $per_id, $pes_usuario_modifica, $fecha, $modificar) {
		$pes_usuario_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
		$estado = 1;
		$trans = $con->getTransaction(); // se obtiene la transacción actual
		if ($trans !== null) {
			$trans = null; // si existe la transacción entonces no se crea una
		} else {
			$trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
		}
		try {
			$comando = $con->createCommand
				("UPDATE " . $con->dbname . ".planificacion_estudiante
                      SET pes_usuario_modifica = " . $pes_usuario_modifica . ", " .
				$modificar . "
                      pes_fecha_modificacion = '" . $fecha . "'
                      WHERE
                        pla_id = " . $pla_id . " AND
                        per_id = " . $per_id . " AND
                        pes_estado = :estado AND
                        pes_estado_logico = :estado");

			$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
			$result = $comando->execute();
			if ($trans !== null) {
				$trans->commit();
			}

			return $con->getLastInsertID($con->dbname . '.planificacion_estudiante');
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();
			}

			return FALSE;
		}
	}

	public function agregarMateriasPlanificacionEstudiante($pla_id, $per_id) {
		$con = \Yii::$app->db_academico;
		$trans = $con->beginTransaction();
		$estado = 1;
		//$trans = $con->getTransaction(); // se obtiene la transacción actual
		try {
			for ($i = 1, $i <= 6; $i++;) {
				for ($j = 1; $j <= 2; $j++) {
					$campo = "pes_mat_b" . $j . "_h" . $i . "_nombre ";
					$registro = "pes_mat_b" . $j . "_h" . $i . "_cod ";
					$comando = $con->createCommand
						("UPDATE " . $con->dbname . ".planificacion_estudiante
                      SET " . $campo . " =
                            (SELECT asi.asi_nombre from " . $con->dbname . ".planificacion_estudiante pla
                            inner join " . $con->dbname . ".malla_academica_detalle mad on mad.made_codigo_asignatura = pla." . $registro . "
                            inner join " . $con->dbname . ".asignatura asi on mad.asi_id = asi.asi_id
                            WHERE
                            pla.pla_id = " . $pla_id . " AND
                            pla.per_id = " . $per_id . " AND
                            pla.pes_estado = :estado AND
                            pla.pes_estado_logico = :estado)
                      WHERE
                        pla_id = " . $pla_id . " AND
                        per_id = " . $per_id . " AND
                        pes_estado = :estado AND
                        pes_estado_logico = :estado");
					$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
					$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
					$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
					$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
					$result = $comando->execute();
					//if ($trans !== null){
					$trans->commit(); //}
				}
			}

			if ($trans !== null) {
				$trans->commit();
			}

			$con->close();
			return TRUE; //$con->getLastInsertID($con->dbname . '.planificacion_estudiante');
		} catch (Exception $ex) {
			if ($trans !== null) {
				$trans->rollback();
			}

			$con->close();
			return FALSE;
		}
	}

	public function confirmarPlanificacionExistente($per_id, $periodo) {
		$con = \Yii::$app->db_academico;
		$con2 = \Yii::$app->db_asgard;
		$fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
		$estado = 1;

		try {
			if ($per_id == null) {
				$resultData = [];
				\app\models\Utilities::putMessageLogFile('No Enviado');
			} else {
				$sql = ("SELECT * from " . $con->dbname . ".planificacion_estudiante pe
                        inner join " . $con->dbname . ".planificacion pla on pla.saca_id = $periodo and pla.pla_id = pe.pla_id
                        where pe.per_id = $per_id;");
				$comando = $con->createCommand($sql);
				$resultData = $comando->queryOne();
				\app\models\Utilities::putMessageLogFile('Encontrado');
			}

			if ($resultData == null) {
				$sql3 =
					("INSERT INTO " . $con->dbname . ".planificacion_estudiante(pes_cod_malla,pes_cod_carrera,per_id,pes_dni,pes_jornada,pes_nombres,pla_id,pes_estado,pes_estado_logico,pes_fecha_creacion)
                    select distinct(ma.maca_codigo),ma.maca_nombre,e.per_id, pe.per_cedula, ecpr_jornada as jornada,
                                        concat(pe.per_pri_nombre, ' ', pe.per_seg_nombre,' ', pe.per_pri_apellido, ' ',pe.per_seg_apellido) as nombres, pla.pla_id as pla_id, $estado,$estado,
                                        '$fecha_modificacion'
                                        from " . $con->dbname . ".estudiante_carrera_programa ecp
                                        inner join " . $con->dbname . ".modalidad_estudio_unidad meu on ecp.meun_id = meu.meun_id
                                        inner join " . $con->dbname . ".estudio_academico es on es.eaca_id = meu.eaca_id
                                        inner join " . $con->dbname . ".estudiante e on e.est_id = ecp.est_id and  ecp.est_id = e.est_id
                                        inner join " . $con->dbname . ".malla_academico_estudiante maes on maes.per_id = e.per_id
                                        inner join " . $con->dbname . ".malla_academica ma on ma.maca_id = maes.maca_id
                                        inner join " . $con2->dbname . ".persona pe on pe.per_id = e.per_id
                                        inner join " . $con->dbname . ".planificacion pla on pla.saca_id = $periodo and meu.mod_id = pla.mod_id
                                        where e.per_id = $per_id;");
				$comando3 = $con->createCommand($sql3);
				$result3 = $comando3->execute();
				$resultData = $resultData + $result3;
				\app\models\Utilities::putMessageLogFile('Insertado');
				return $resultData;

			} else {
				\app\models\Utilities::putMessageLogFile('No Insertado');
				return $resultData;
			}

		} catch (Exception $ex) {
			\app\models\Utilities::putMessageLogFile($ex->getMessage());
			return $ex->getMessage();
		}
	}

	/**
	 * Function Consultar codigo asigantura para archivo de planificacion estudiante.
	 * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
	 * @property
	 * @return
	 */
	public function consultarCodigoAsignatura($maca_codigo, $asi_id) {
		$con = \Yii::$app->db_academico;
		$estado = 1;

		$sql = "SELECT made_codigo_asignatura
                FROM " . $con->dbname . ".malla_academica_detalle macad
                    INNER JOIN " . $con->dbname . ".malla_academica maca
                    ON maca.maca_id = macad.maca_id
                    AND maca_codigo = maca_codigo
                WHERE macad.asi_id = asi_id AND
                      maca.maca_estado = :estado AND
                      maca.maca_estado_logico = :estado AND
                      macad.made_estado = :estado AND
                      macad.made_estado_logico = :estado";
		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":maca_codigo", $maca_codigo, \PDO::PARAM_STR);
		$comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
		$resultData = $comando->queryOne();
		return $resultData;
	}

	public function consultarResumenplanificaPdf($arrFiltro = array(), $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;
		$str_search = '';
		if (isset($arrFiltro) && count($arrFiltro) > 0) {

			if ($arrFiltro['modalidad'] != 0) {
				if ($arrFiltro['modalidad'] == 1) {
					$str_search .= " and meu.mod_id = 1";
				}
				if ($arrFiltro['modalidad'] == 2) {
					$str_search .= " and meu.mod_id = 2";
				}
				if ($arrFiltro['modalidad'] == 3) {
					$str_search .= " and meu.mod_id = 3";
				}
				if ($arrFiltro['modalidad'] == 4) {
					$str_search .= " and meu.mod_id = 4";
				}
			}

			if ($arrFiltro['periodo'] != 0) {
				$periodo = $arrFiltro['periodo'];
				$str_search2 .= "and pla.saca_id = $periodo";
			}
		}
		if ($onlyData == false) {
			$idplanifica = 'plae.pla_id, ';
			$idper = 'plae.per_id, ';
		}
		$sql = "SELECT
        CASE meu.mod_id
        when 1 then 'ONLINE'
        when 2 then 'PRESENCIAL'
        WHEN 3 then 'SEMIPRESENCIAL'
        WHEN 4 then 'DISTANCIA'
        end as 'Modalidad',
        (select concat(saca.saca_nombre,'-',saca.saca_anio)
        from " . $con->dbname . ".semestre_academico saca,
        " . $con->dbname . ".planificacion p
        where p.pla_id = pe.pla_id
        and saca.saca_id = p.saca_id) as 'Periodo',
        a.asi_id as 'Codigo de Materia',
        a.asi_nombre as Materia,
        (select count(*) from  " . $con->dbname . ".planificacion pla,
        " . $con->dbname . ".planificacion_estudiante pes,
        " . $con->dbname . ".semestre_academico se
        where pla.pla_id = pes.pla_id
        and pla.per_id = pes.per_id
        and se.saca_id = pla.saca_id
        $str_search2
        and mad.made_codigo_asignatura in (pes.pes_mat_b1_h1_cod,
                                pes.pes_mat_b1_h2_cod,
                                pes.pes_mat_b1_h3_cod,
                                pes.pes_mat_b1_h4_cod,
                                pes.pes_mat_b1_h5_cod,
                                pes.pes_mat_b1_h6_cod,
                                pes.pes_mat_b2_h1_cod,
                                pes.pes_mat_b2_h2_cod,
                                pes.pes_mat_b2_h3_cod,
                                pes.pes_mat_b2_h4_cod,
                                pes.pes_mat_b2_h5_cod,
                                pes.pes_mat_b2_h6_cod)) as Cantidad
        from " . $con->dbname . ".planificacion_estudiante pe,
        " . $con->dbname . ".malla_academica_detalle mad,
        " . $con->dbname . ".asignatura a,
        " . $con->dbname . ".modalidad_estudio_unidad meu,
        " . $con->dbname . ".malla_unidad_modalidad mum
        where mad.made_codigo_asignatura in
        (pe.pes_mat_b1_h1_cod,
        pe.pes_mat_b1_h2_cod,
        pe.pes_mat_b1_h3_cod,
        pe.pes_mat_b1_h4_cod,
        pe.pes_mat_b1_h5_cod,
        pe.pes_mat_b1_h6_cod,
        pe.pes_mat_b2_h1_cod,
        pe.pes_mat_b2_h2_cod,
        pe.pes_mat_b2_h3_cod,
        pe.pes_mat_b2_h4_cod,
        pe.pes_mat_b2_h5_cod,
        pe.pes_mat_b2_h6_cod)
        and mad.asi_id = a.asi_id
        and mad.maca_id = mum.maca_id

        and (select count(*) from  " . $con->dbname . ".planificacion pla,
        " . $con->dbname . ".planificacion_estudiante pes,
        " . $con->dbname . ".semestre_academico se
        where pla.pla_id = pes.pla_id
        and pla.per_id = pes.per_id
        and se.saca_id = pla.saca_id
        $str_search2
        and mad.made_codigo_asignatura in (pes.pes_mat_b1_h1_cod,
                                pes.pes_mat_b1_h2_cod,
                                pes.pes_mat_b1_h3_cod,
                                pes.pes_mat_b1_h4_cod,
                                pes.pes_mat_b1_h5_cod,
                                pes.pes_mat_b1_h6_cod,
                                pes.pes_mat_b2_h1_cod,
                                pes.pes_mat_b2_h2_cod,
                                pes.pes_mat_b2_h3_cod,
                                pes.pes_mat_b2_h4_cod,
                                pes.pes_mat_b2_h5_cod,
                                pes.pes_mat_b2_h6_cod))> 0
        $str_search
        and meu.meun_id = (select meun_id from db_academico.malla_unidad_modalidad where maca_id = mum.maca_id limit 0,1)
        group by a.asi_id,a.asi_nombre
        order by cantidad;";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			$search_cond = "%" . $arrFiltro["estudiante"] . "%";

			if ($arrFiltro['modalidad'] > 0) {
				$modalidad = $arrFiltro["modalidad"];
				$comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
			}

			if ($arrFiltro['periodo'] != '0') {
				$periodo = $arrFiltro["periodo"];
				$comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
			}
		}

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

	public function consultarEstudiantePeriodo($arrFiltro = array(), $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;
		// view: 0 = all, 1 = excel, 2 = pdf
		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			\app\models\Utilities::putMessageLogFile('----------------------------------------Con Filtros');
			$pla_id = $arrFiltro['periodo'] ? $arrFiltro['periodo'] : 0;
			$mod_id = $arrFiltro['modalidad'] ? $arrFiltro['modalidad'] : 0;
			$bloque = $arrFiltro['bloque'] ? $arrFiltro['bloque'] : 0;
			$view = $arrFiltro['view'] ? $arrFiltro['view'] : 0;
			//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('pla_id y mod_id '.$arrFiltro['periodo'] .'-'.$arrFiltro['modalidad']);
			$str_search = '';
			$str_campos_filtro = '';
			$str_search1 = null;

			if ($bloque == 0) {
				$filtro = '(pe.pes_mat_b1_h1_cod,
                pe.pes_mat_b1_h2_cod,
                pe.pes_mat_b1_h3_cod,
                pe.pes_mat_b1_h4_cod,
                pe.pes_mat_b1_h5_cod,
                pe.pes_mat_b1_h6_cod,
                pe.pes_mat_b2_h1_cod,
                pe.pes_mat_b2_h2_cod,
                pe.pes_mat_b2_h3_cod,
                pe.pes_mat_b2_h4_cod,
                pe.pes_mat_b2_h5_cod,
                pe.pes_mat_b2_h6_cod)';
			}
			if ($bloque == 1) {
				$filtro = '(pe.pes_mat_b1_h1_cod,
                pe.pes_mat_b1_h2_cod,
                pe.pes_mat_b1_h3_cod,
                pe.pes_mat_b1_h4_cod,
                pe.pes_mat_b1_h5_cod,
                pe.pes_mat_b1_h6_cod)';
			}
			if ($bloque == 2) {
				$filtro = '(pe.pes_mat_b2_h1_cod,
                pe.pes_mat_b2_h2_cod,
                pe.pes_mat_b2_h3_cod,
                pe.pes_mat_b2_h4_cod,
                pe.pes_mat_b2_h5_cod,
                pe.pes_mat_b2_h6_cod)';
			}

			$consulta = "x.*, count(x.per_id) as total";
			if ($view == 1) {
				$consulta = "x.id, x.Modalidad, x.Periodo, x.bloque, x.Materia,count(x.per_id) as total";
				// print_r($consulta);die();
			}
			if ($view == 2) {
				$consulta = "x.id, x.Modalidad, x.Periodo, x.bloque, x.Materia,count(x.per_id) as total";
				// print_r($consulta);die();
			}

			if (!strcmp($str_search, '')) {
				if ($arrFiltro['periodo'] != 0 || $arrFiltro['modalidad'] != 0) {
					$where .= " WHERE ";
				}

				if ($arrFiltro['periodo'] != 0) {
					$str_search1 = " pla.saca_id = $pla_id ";
					//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('-------------------'. $str_search1 .'---------------------');
				}
				if ($arrFiltro['modalidad'] != 0) {
					//$str_search2 .= " pla.mod_id = $mod_id and meu.mod_id = $mod_id";
					$str_search2 .= " pla.mod_id = $mod_id";
					//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('-------------------'. $str_search2 .'---------------------');
				}
				if (isset($where)) {
					$str_search1 = (!is_null($str_search1) && !is_null($str_search2) ? $str_search1 . ' AND' : $str_search1);
					$str_search .= $where . $str_search1 . $str_search2;
				}
				\app\models\Utilities::putMessageLogFile('else-------------------' . $str_search . '---------------------');
				//print_r($str_search); die();
			}
			//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('str_search '.$str_search);

		}

		$sql2 = "SELECT $consulta from
                ( SELECT (pe.per_id),a.asi_id as id,  pla.saca_id,pla.mod_id,
                                case pla.mod_id
                                when 1 then 'ONLINE'
                                when 2 then 'PRESENCIAL'
                                WHEN 3 then 'SEMIPRESENCIAL'
                                WHEN 4 then 'DISTANCIA'
                                end as Modalidad,
                                concat('Paralelo ',mpp.mpp_num_paralelo) as Paralelo,
                                mad.made_codigo_asignatura,
                                (select concat(saca.saca_nombre,'-',saca.saca_anio)
                                    from db_academico.semestre_academico saca,
                                    db_academico.planificacion p
                                    where p.pla_id = pe.pla_id
                                    and saca.saca_id = p.saca_id) as 'Periodo',
                                case
                                when mad.made_codigo_asignatura  in (pe.pes_mat_b1_h1_cod,pe.pes_mat_b1_h2_cod,pe.pes_mat_b1_h3_cod,
                                                                    pe.pes_mat_b1_h4_cod,pe.pes_mat_b1_h5_cod,pe.pes_mat_b1_h6_cod)
                                then 'Bloque 1'
                                when mad.made_codigo_asignatura  in (pe.pes_mat_b2_h1_cod,pe.pes_mat_b2_h2_cod,pe.pes_mat_b2_h3_cod,
                                                                        pe.pes_mat_b2_h4_cod,pe.pes_mat_b2_h5_cod,pe.pes_mat_b2_h6_cod)
                                then 'Bloque 2' end as bloque,
                        a.asi_nombre as Materia, ifnull(mpp.mpp_id,'0') as mpp_id,
                        case daho.daho_id
                        when '0' then ''
                        when NULL then ''
                        else ifnull(daho.daho_descripcion ,'')
                        end as horario,
                        mpp.daho_id as daho_id,
                        count(pe.per_id) as Cantidad

                    from  " . $con->dbname . ".planificacion_estudiante pe
                    inner join  " . $con->dbname . ".malla_academica_detalle mad on mad.made_codigo_asignatura in $filtro
                    inner join  " . $con->dbname . ".asignatura a on mad.asi_id = a.asi_id
                    inner join  " . $con->dbname . ".malla_unidad_modalidad mum on mad.maca_id = mum.maca_id
                    inner join  " . $con->dbname . ".modalidad_estudio_unidad meu on meu.meun_id /*=*/ IN (select s.meun_id from db_academico.malla_unidad_modalidad s where s.maca_id = mum.maca_id /*limit 0,1*/)
                    inner join  " . $con->dbname . ".planificacion pla on pla.pla_id = pe.pla_id
                    inner join  " . $con->dbname . ".semestre_academico se on se.saca_id = pla.saca_id
                    inner join  " . $con->dbname . ".materia_paralelo_periodo mpp on mpp.mpp_id in (pe.pes_mat_b1_h1_mpp,pe.pes_mat_b1_h2_mpp,pe.pes_mat_b1_h3_mpp,
																							pe.pes_mat_b1_h4_mpp,pe.pes_mat_b1_h5_mpp,pe.pes_mat_b1_h6_mpp,
																							pe.pes_mat_b2_h1_mpp,pe.pes_mat_b2_h2_mpp,pe.pes_mat_b2_h3_mpp,
																							pe.pes_mat_b2_h4_mpp,pe.pes_mat_b2_h5_mpp,pe.pes_mat_b2_h6_mpp)
																		and mpp.asi_id = a.asi_id
                    inner join  " . $con->dbname . ".distributivo_academico_horario daho on daho.daho_id = mpp.daho_id or ifnull(mpp.daho_id,0) = 0
                    $str_search
                    group by pe.per_id,mpp.mpp_id
                    order by a.asi_id) as x group by x.mpp_id order by x.Materia,x.Paralelo,x.mod_id asc;";
		\app\models\Utilities::putMessageLogFile('consultarModalidad: ' . $sql2);
		$comando = $con->createCommand($sql2);

		/*if (isset($arrFiltro) && count($arrFiltro) > 0) {

			            if ($arrFiltro['modalidad'] > 0) {
			                $modalidad = $arrFiltro["modalidad"];
			                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
			            }

			            if ($arrFiltro['periodo'] != '0') {
			                $periodo = $arrFiltro["periodo"];
			                $comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
			            }
		*/

		$resultData = $comando->queryAll();
		\app\models\Utilities::putMessageLogFile('consultarModalidad: ' . $comando->getRawSql());
		//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('consultarModalidad: '.$comando->getRawSql());
		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $resultData,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => [],
			],
		]);
		if ($onlyData) {
			\app\models\Utilities::putMessageLogFile('----------------------------------------true' . $onlyData);
			return $resultData;
		} else {
			\app\models\Utilities::putMessageLogFile('----------------------------------------false' . $onlyData);
			return $dataProvider;
		}
	}

	public function consultarPeriodoAcadplanifica() {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		$condition = "";

		$sql = "SELECT saca_id as id,
                concat(saca_nombre, ' ',saca_anio) as 'name'
                  FROM " . $con->dbname . ".semestre_academico
                  WHERE saca_estado = 1 AND
                  saca_estado_logico = 1
                  order by saca_anio;";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$resultData = $comando->queryAll();
		return $resultData;
	}

	public function consultarProcesonull() {
		$sql = "select Asignatura,Jornada,Bloque,Modalidad,Hora from dual";
		$comando = $con->createCommand($sql);
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

	public function consultaPeriodoAcadVigente() {
		$con = \Yii::$app->db_academico;
		$estado = 'A';
		$sql = "select
                a.paca_id as 'id',
                concat(b.saca_nombre , ' ' , b.saca_anio) as 'name'
                from  " . $con->dbname . ".periodo_academico a
                inner join " . $con->dbname . ".semestre_academico b on a.saca_id = b.saca_id
                where a.paca_activo = 'A'
                order by a.paca_id desc;";
		$comando = $con->createCommand($sql);
		//$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$resultData = $comando->queryAll();
		return $resultData;
	}

	public function consultaPeriodoAcadVigenteFechas() {
		$con = \Yii::$app->db_academico;
		$estado = 'A';
		$sql = "select
                a.paca_id as 'id',
                concat(b.saca_nombre , ' ' , b.saca_anio) as 'name',
                paca_fecha_inicio as fecha_inicio,
                paca_fecha_fin as fecha_fin
                from  " . $con->dbname . ".periodo_academico a
                inner join " . $con->dbname . ".semestre_academico b on a.saca_id = b.saca_id
                where a.paca_activo = 'A'
                order by a.paca_id desc;";
		$comando = $con->createCommand($sql);
		//$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$resultData = $comando->queryAll();
		return $resultData;
	}

	public function consultaPlanificacionEstVigente($per_id, $saca_id) {
		$con = \Yii::$app->db_academico;
		$estado = '1';
		$sql = "SELECT pl.pla_id as 'id', pl.pla_id as 'name'
                from " . $con->dbname . ".planificacion_estudiante pl
                where pl.per_id = $per_id
                and pl.pes_estado_logico = 1
                order by pl.pla_id desc
                limit 0,1;";

		$sql2 = "SELECT distinct ples.pla_id from " . $con->dbname . ".periodo_academico pa
                inner join " . $con->dbname . ".planificacion pla on pla.saca_id = pa.saca_id
                inner join " . $con->dbname . ".planificacion_estudiante ples on ples.pla_id = pla.pla_id
                and ples.per_id = $per_id
                and pa.saca_id = $paca_id
                and ples.pes_estado = 1
                and ples.pes_estado_logico = 1";
		if ($per_id == null) {
			$resultData = [];
		} else {
			$comando = $con->createCommand($sql);
			//$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
			$resultData = $comando->queryAll();

		}
		return $resultData;
	}

	public function consultaMallaEstudiante($per_id) {
		$con = \Yii::$app->db_academico;

		$sql = "SELECT b.maca_id as 'id',
                       b.maca_nombre as 'name'
                       from " . $con->dbname . ".planificacion_estudiante a
                       inner join " . $con->dbname . ".malla_academica b on a.pes_cod_malla = b.maca_codigo
                       where a.per_id = $per_id;";

		if ($per_id == null) {
			$resultData = [];
		} else {
			$comando = $con->createCommand($sql);
			//$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
			$resultData = $comando->queryAll();

		}
		return $resultData;
	}

	public function consultaracarreraxmallaaut($per_id) {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		$sql = "SELECT m.maca_id as 'id', pe.per_id as 'name'
                        from " . $con->dbname . ".planificacion_estudiante pe
                        inner join " . $con->dbname . ".malla_academica m on m.maca_codigo = pe.pes_cod_malla
                        and pe.per_id = $per_id";
		$comando = $con->createCommand($sql);
		if ($per_id == null) {
			$resultData = [];
		} else {

			$resultData = $comando->queryAll();
		}
		return $resultData;
	}

	public function consultarProcesoPlanificacionAut($arrFiltro = array(), $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;
		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			$estudiante = $arrFiltro['estudiante'];
			$estudiante = $_GET['estudiante'];
			//$str_search2 .= "and pla.saca_id = $periodo";
			/*$str_search .= "(h.per_nombre like '%$estudiante%' OR ";
	            $str_search .= "h.per_nombre_segundo like '%$estudiante%' OR ";
	            $str_search .= "h.per_apellido like '%$estudiante%' OR ";
	            $str_search .= "h.per_apellido_segundo like '%$estudiante%' OR ";

*/

			$str_search .= "a.per_id = $estudiante AND ";
			/* if ($arrFiltro['modalidad'] != 0) {
				                if($arrFiltro['modalidad'] == 1) {
				                    $str_search .= " and meu.mod_id = 1";
				                }
				                if($arrFiltro['modalidad'] == 2) {
				                    $str_search .= " and meu.mod_id = 2";
				                }
				                if($arrFiltro['modalidad'] == 3) {
				                    $str_search .= " and meu.mod_id = 3";
				                }
				                if($arrFiltro['modalidad'] == 4) {
				                    $str_search .= " and meu.mod_id = 4";
				                }
				            }
			*/
			if ($arrFiltro['periodo'] != 0) {
				$periodo = $arrFiltro['periodo'];
				$str_search2 .= "and pla.saca_id = $periodo";
			}

			if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
				$str_search .= "me.uaca_id = :unidad AND ";
			}
			if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
				$str_search .= "mo.mod_id = :modalidad AND ";
			}
			if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
				$str_search .= "me.eaca_id = :carrera AND ";
			}

			//if($arrFiltro['modalidad'] )
		}
		if ($onlyData == false) {
			$idplanifica = 'plae.pla_id, ';
			$idper = 'plae.per_id, ';
		}

		$sql = "select
                    h.cedula as 'Identificacion',
                    concat(h.per_nombre,' ', h.per_apellido) as 'Estudiante',
                    b.asi_nombre as 'Asignatura',
                    a.per_id,
                    d.made_semestre,
                    c.pmac_nota,
                    e.enac_asig_estado
                from " . $con->dbname . ".malla_academico_estudiante a
                inner join " . $con->dbname . ".malla_academica_detalle md on md.made_id=a.made_id
                inner join " . $con->dbname . ".malla_unidad_modalidad mu on mu.maca_id=a.maca_id
                inner join " . $con->dbname . ".modalidad_estudio_unidad me on me.meun_id=mu.meun_id
                inner join " . $con->dbname . ".asignatura b on b.asi_id=a.asi_id
                inner join " . $con->dbname . ".promedio_malla_academico c on c.maes_id=a.maes_id
                inner join " . $con->dbname . ".malla_academica_detalle d on a.made_id=d.made_id
                inner join " . $con->dbname . ".estado_nota_academico e on e.enac_id=c.enac_id
                inner join " . $con->dbname . ".historico_siga h on h.per_id=a.per_id
                inner join " . $con->dbname . ".modalidad mo on mo.mod_id=h.modalidad
                where $str_search
                c.enac_id >=2
                and a.per_id in (SELECT per_id FROM " . $con->dbname . ".estudiante)";

		$comando = $con->createCommand($sql);

		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			if ($arrFiltro['estudiante'] != "") {
				$search_cond = "%" . $arrFiltro["estudiante"] . "%";
				$comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);
			}

			$unidad = $arrFiltro["unidad"];
			if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
				$comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
			}
			$modalidad = $arrFiltro["modalidad"];
			if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
				$comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
			}
			$carrera = $arrFiltro["carrera"];
			if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
				$comando->bindParam(":carrera", $carrera, \PDO::PARAM_INT);
			}
			$periodo = $arrFiltro["periodo"];
			if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
				$comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
			}
			$resultData = $comando->queryAll();

		} else {
			$resultData = [];
		}

		$rows_in = $resultData;
		if (count($rows_in) > 0) {
			//$per_id_init = array_keys($rows_in)[0];
			//echo $per_id_init;
			$aux = array();
			$array = array();
			$per_id_init = $rows_in[0]["Identificacion"];
			for ($i = 0; $i < count($rows_in); $i++) {
				for ($j = 0; $j < 6; $j++) {
					if ($rows_in[$i]["Identificacion"] == $per_id_init) {
						$aux = array("Identificacion" => isset($rows_in[$i]["Identificacion"]) ? $rows_in[$i]["Identificacion"] : "0000",
							"Estudiante" => isset($rows_in[$i]["Estudiante"]) ? $rows_in[$i]["Estudiante"] : "0000",
							"Asignatura" => isset($rows_in[$i]["Asignatura"]) ? $rows_in[$i]["Asignatura"] : "0000",
						);
						array_push($array, $aux);
						$aux = array();
						$i++;
					} else {
						break;
					}
				}
				while ($per_id_init == $rows_in[$i]["Identificacion"]) {
					$i++;
				}
				$per_id_init = $rows_in[$i]["Identificacion"];
			}
			//$rows_in[$i]["Identificacion"] = '0101';
		}

		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $array,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => ["Asignatura",
				],
			],
		]);
		if ($onlyData) {
			return $resultData;
		} else {
			return $dataProvider;
		}
	}

	public function consultarProcesoPlanificacionEstudiante($estudiante, $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;

		//$estudiante = $arrFiltro['estudiante'];
		//$str_search2 .= "and pla.saca_id = $periodo";
		$str_search .= "(h.per_nombre like '%$estudiante%' OR ";
		$str_search .= "h.per_nombre_segundo like '%$estudiante%' OR ";
		$str_search .= "h.per_apellido like '%$estudiante%' OR ";
		$str_search .= "h.per_apellido_segundo like '%$estudiante%' OR ";
		$str_search .= "h.cedula like '%$estudiante%')  AND ";

		if ($onlyData == false) {
			$idplanifica = 'plae.pla_id, ';
			$idper = 'plae.per_id, ';
		}

		$sql = "select
                    h.cedula as 'Identificacion',
                    concat(h.per_nombre,' ', h.per_apellido) as 'Estudiante',
                    a.per_id,
                    d.made_semestre,
                    b.asi_nombre as 'Materia',
                    c.pmac_nota,
                    e.enac_asig_estado,
                    (select pes.pes_carrera from db_academico.planificacion_estudiante pes where pes.per_id = a.per_id) as 'Carrera'
                from " . $con->dbname . ".malla_academico_estudiante a
                inner join " . $con->dbname . ".asignatura b on b.asi_id=a.asi_id
                inner join " . $con->dbname . ".promedio_malla_academico c on c.maes_id=a.maes_id
                inner join " . $con->dbname . ".malla_academica_detalle d on a.made_id=d.made_id
                inner join " . $con->dbname . ".estado_nota_academico e on e.enac_id=c.enac_id
                inner join " . $con->dbname . ".historico_siga h on h.per_id=a.per_id
                where $str_search
                c.enac_id >=2
                and a.per_id in (SELECT per_id FROM " . $con->dbname . ".estudiante)";
		$sql2 = "SELECT

            c.id_dummy,
            c.materia as Materia,
            count(c.materia) as Cantidad,
            c.id_modalidad
        FROM " . $con->dbname . ".dummy_pruebasiga c
        where   $str_search
                c.nota=0
                group by c.periodo,c.materia, c.carrera, c.modalidad, c.bloque_academico";

		$comando = $con->createCommand($sql);

		if (isset($arrFiltro) && count($arrFiltro) > 0) {

			if ($arrFiltro['modalidad'] > 0) {
				$modalidad = $arrFiltro["modalidad"];
				$comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
			}

			if ($arrFiltro['periodo'] != '0') {
				$periodo = $arrFiltro["periodo"];
				$comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
			}
		}

		$resultData = $comando->queryAll();
		$rows_in = $resultData;
		if (count($rows_in) > 0) {
			//$per_id_init = array_keys($rows_in)[0];
			//echo $per_id_init;
			$aux = array();
			$array = array();
			$per_id_init = $rows_in[0]["Identificacion"];
			for ($i = 0; $i < count($rows_in); $i++) {
				for ($j = 0; $j < 6; $j++) {
					if ($rows_in[$i]["Identificacion"] == $per_id_init) {
						$aux = array("Identificacion" => isset($rows_in[$i]["Identificacion"]) ? $rows_in[$i]["Identificacion"] : "0000",
							"Estudiante" => isset($rows_in[$i]["Estudiante"]) ? $rows_in[$i]["Estudiante"] : "0000",
							"Materia" => isset($rows_in[$i]["Materia"]) ? $rows_in[$i]["Materia"] : "0000",
						);
						array_push($array, $aux);
						$aux = array();
						$i++;
					} else {
						break;
					}
				}
				while ($per_id_init == $rows_in[$i]["Identificacion"]) {
					$i++;
				}
				$per_id_init = $rows_in[$i]["Identificacion"];
			}
			//$rows_in[$i]["Identificacion"] = '0101';
		}
		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $array,
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

	public function consultarEstudianteplanificax($arrFiltro = array(), $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;
		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			$str_search .= "(pers.per_pri_nombre like :estudiante OR ";
			$str_search .= "pers.per_seg_nombre like :estudiante OR ";
			$str_search .= "pers.per_pri_apellido like :estudiante OR ";
			$str_search .= "pers.per_seg_nombre like :estudiante OR ";
			$str_search .= "pers.per_cedula like :estudiante)  AND ";

			if ($arrFiltro['modalidad'] > 0) {
				$str_search .= " plan.mod_id = :modalidad AND ";
			}

			if ($arrFiltro['carrera'] != 'Todas') {
				$str_search .= " plae.pes_carrera like :carrera AND ";
			}

			if ($arrFiltro['periodo'] != '0') {
				$str_search .= " plan.pla_periodo_academico = :periodo AND ";
			}
		}
		if ($onlyData == false) {
			$idplanifica = 'plae.pla_id, ';
			$idper = 'plae.per_id, ';
		}
		$sql = "SELECT
                    $idplanifica
                    $idper
                    pers.per_cedula,
                    plae.pes_nombres,
                    plae.pes_carrera,
                    plan.pla_periodo_academico
                FROM " . $con->dbname . ".planificacion_estudiante plae
                INNER JOIN " . $con->dbname . ".planificacion plan ON plan.pla_id = plae.pla_id
                INNER JOIN " . $con1->dbname . ".persona pers ON pers.per_id = plae.per_id
                WHERE
                    $str_search
                    plae.pes_estado = :estado AND
                    plae.pes_estado_logico = :estado AND
                    plan.pla_estado = :estado AND
                    plan.pla_estado_logico = :estado AND
                    pers.per_estado = :estado AND
                    pers.per_estado_logico = :estado";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			$search_cond = "%" . $arrFiltro["estudiante"] . "%";
			$comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);

			if ($arrFiltro['modalidad'] > 0) {
				$modalidad = $arrFiltro["modalidad"];
				$comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
			}

			if ($arrFiltro['carrera'] != 'Todas') {
				$search_carrera = "%" . $arrFiltro["carrera"] . "%";
				$comando->bindParam(":carrera", $search_carrera, \PDO::PARAM_STR);
			}

			if ($arrFiltro['periodo'] != '0') {
				$periodo = $arrFiltro["periodo"];
				$comando->bindParam(":periodo", $periodo, \PDO::PARAM_STR);
			}
		}

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

	public function consultarEstudianteplanificapesold($pla_id, $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;
		// if (isset($arrFiltro) && count($arrFiltro) > 0) {
		// $str_search .= "(pers.per_pri_nombre like :estudiante OR ";
		// $str_search .= "pers.per_seg_nombre like :estudiante OR ";
		// $str_search .= "pers.per_pri_apellido like :estudiante OR ";
		// $str_search .= "pers.per_seg_nombre like :estudiante OR ";
		// $str_search .= "pers.per_cedula like :estudiante)  AND ";

		// if ($arrFiltro['modalidad'] > 0) {
		//    $str_search .= " plan.mod_id = :modalidad AND ";
		//}

		// if ($arrFiltro['carrera'] != 'Todas') {
		//    $str_search .= " plae.pes_carrera like :carrera AND ";
		//}

		if ($pla_id != 0) {
			$str_search .= " plae.pla_id = :pla_id  AND ";
		}
		// }
		// if ($onlyData == false) {
		//    $idplanifica = 'plae.pla_id, ';
		//    $idper = 'plae.per_id, ';
		//}
		$sql = "SELECT
                    pers.per_cedula,
                    plae.pes_nombres,
                    plae.pes_carrera,
                    plan.pla_periodo_academico,
                     ifnull((SELECT concat (plae.pes_mat_b1_h1_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h1_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b1_h1_nombre) as mat1,
                    (SELECT daho.daho_descripcion
                    FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h1_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho1,
                        ifnull((SELECT concat (plae.pes_mat_b1_h2_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h2_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b1_h2_nombre) as mat2,
                    (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h2_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho2,
                       ifnull((SELECT concat (plae.pes_mat_b1_h3_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h3_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b1_h3_nombre) as mat3,
                     (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h3_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho3,
                        ifnull((SELECT concat (plae.pes_mat_b1_h4_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h4_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b1_h4_nombre) as mat4,
                    (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h4_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho4,
                       ifnull((SELECT concat (plae.pes_mat_b1_h5_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h5_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b1_h5_nombre) as mat5,
                    (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h5_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho5,
                     ifnull((SELECT concat (plae.pes_mat_b1_h6_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h6_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b1_h6_nombre) as mat6,
                     (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b1_h6_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho6,
                     ifnull((SELECT concat (plae.pes_mat_b2_h1_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h1_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b2_h1_nombre) as mat7,
                    (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h1_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho7,
                      ifnull((SELECT concat (plae.pes_mat_b2_h2_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h2_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b2_h2_nombre) as mat8,
                     (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h2_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho8,
                      ifnull((SELECT concat (plae.pes_mat_b2_h3_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h3_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b2_h3_nombre) as mat9,
                     (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h3_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho9,
                      ifnull((SELECT concat (plae.pes_mat_b2_h4_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h4_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b2_h4_nombre) as mat10,
                     (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h4_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho10,
                      ifnull((SELECT concat (plae.pes_mat_b2_h5_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h5_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b2_h5_nombre) as mat11,
                     (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h5_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho11,
                      ifnull((SELECT concat (plae.pes_mat_b2_h6_nombre,' P-',mpp.mpp_num_paralelo)
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h6_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ),plae.pes_mat_b2_h6_nombre) as mat12,
                     (SELECT daho.daho_descripcion
FROM db_academico.distributivo_academico_horario as daho
INNER JOIN db_academico.materia_paralelo_periodo as mpp on mpp.daho_id = daho.daho_id
INNER JOIN db_academico.planificacion_estudiante as pes on mpp.mpp_id = pes.pes_mat_b2_h6_mpp
WHERE mpp.daho_id > 0  AND pes.per_id = plae.per_id
AND pes.pla_id = :pla_id ) as daho12
                FROM " . $con->dbname . ".planificacion_estudiante plae
                LEFT JOIN " . $con->dbname . ".planificacion plan ON plan.pla_id = plae.pla_id
                INNER JOIN " . $con1->dbname . ".persona pers ON pers.per_id = plae.per_id
                WHERE
                     $str_search
                    plae.pes_estado = :estado AND
                    plae.pes_estado_logico = :estado
                    -- AND
                    -- plan.pla_estado = :estado AND
                    -- plan.pla_estado_logico = :estado
                    -- AND
                    -- pers.per_estado = :estado AND
                    -- pers.per_estado_logico = :estado
                    order by plae.pes_semestre, plae.pes_carrera ASC
                    ";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		// if (isset($arrFiltro) && count($arrFiltro) > 0) {
		//  $search_cond = "%" . $arrFiltro["estudiante"] . "%";
		// $comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);

		// if ($arrFiltro['modalidad'] > 0) {
		//     $modalidad = $arrFiltro["modalidad"];
		//      $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
		//  }

		// if ($arrFiltro['carrera'] != 'Todas') {
		//     $search_carrera = "%" . $arrFiltro["carrera"] . "%";
		//     $comando->bindParam(":carrera", $search_carrera, \PDO::PARAM_STR);
		// }

		if ($pla_id != 0) {
			//  $periodo = $arrFiltro["pla_id "];
			$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
		}
		// }

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

	public function consultarDetalleplanificaaut($arrFiltro = array(), $onlyData = true) {
		try {
			$con = \Yii::$app->db_academico;
			//if (isset($arrFiltro) && count($arrFiltro) > 0) {
			if ($arrFiltro['estudiante']) {
				$per_id = $arrFiltro['estudiante'];
				$per_id = $_GET['estudiante'];
				$pla_id = $arrFiltro['planificacion'];
				$saca_id = $arrFiltro['saca_id'];
				$periodo = $arrFiltro['periodoAca'];
			}
			if ($arrFiltro['modalidad']) {
				$mod_id = $arrFiltro['modalidad'];
				$strMod = 'and pl.mod_id = ' . $mod_id;
			}

			/* Busqueda por periodo */
			$paca_id = PeriodoAcademico::find()->select("paca_id")->where(["saca_id" => $saca_id, "paca_estado" => 1, "paca_estado_logico" => 1])->asArray()->all();
			$paca_1 = $paca_id[0]['paca_id'];
			$paca_2 = $paca_id[1]['paca_id'];
			\app\models\Utilities::putMessageLogFile('paca 1...: ' . $paca_1);
			\app\models\Utilities::putMessageLogFile('paca 1...: ' . $paca_2);
			/* Fin de busqueda*/
			$sql .= 'select distinct x.Ids,x.daho_id,x.saca_id,x.paralelo,x.jor_materia,x.cod_asignatura, x.asignatura,x.pes_jornada,x.bloque,x.modalidad,x.hora,x.horario from (';
			// Bloque 1
			for ($i = 1; $i < 7; $i++) {
				$sql .= "SELECT pes_id as Ids, mpp.daho_id, pla.saca_id,
                                concat('Paralelo ',mpp.mpp_num_paralelo) as paralelo,
                                pes_jor_b1_h" . $i . " as jor_materia, pes_mat_b1_h" . $i . "_cod as cod_asignatura, asig.asi_nombre as asignatura,
                                CASE ples.pes_jor_b1_h" . $i . "
                                    WHEN 'M' THEN 'Matutino'
                                    WHEN 'N' THEN 'Nocturno'
                                    WHEN 'S' THEN 'Semipresencial'
                                    WHEN 'D' THEN 'Distancia'
                                    WHEN '1' THEN 'Matutino'
                                    WHEN '2' THEN 'Nocturno'
                                    WHEN '3' THEN 'Semipresencial'
                                    WHEN '4' THEN 'Distancia'
                                    else ples.pes_jor_b1_h" . $i . "
                                END AS pes_jornada,
                                'Bloque 1' as bloque, moda.mod_nombre as modalidad, 'Hora " . $i . "' as hora,
                                mpp.mpp_id,
                                case mpp.daho_id
                                    when daho.daho_id then daho.daho_descripcion
                                    when ifnull(mpp.daho_id,0)=0 then ''
                                end as horario
                        FROM " . $con->dbname . ".planificacion_estudiante ples
                        INNER JOIN " . $con->dbname . ".planificacion pla on pla.pla_id = ples.pla_id
                        INNER JOIN " . $con->dbname . ".modalidad moda ON  moda.mod_id = ples.pes_mod_b1_h" . $i . "
                        INNER JOIN " . $con->dbname . ".malla_academica_detalle mad ON  mad.made_codigo_asignatura = pes_mat_b1_h" . $i . "_cod
                        INNER JOIN " . $con->dbname . ".asignatura asig ON  asig.asi_id = mad.asi_id
                        INNER JOIN " . $con->dbname . ".materia_paralelo_periodo mpp on mpp.mpp_id = ples.pes_mat_b1_h" . $i . "_mpp and mpp.mod_id =moda.mod_id and mpp.paca_id= $paca_1
                        LEFT JOIN " . $con->dbname . ".distributivo_academico_horario daho on daho.daho_id = mpp.daho_id or ifnull(mpp.daho_id,0) = 0
                        where ples.per_id =  $per_id and ples.pla_id = (SELECT distinct ples.pla_id from " . $con->dbname . ".periodo_academico pa
                                                                inner join " . $con->dbname . ".planificacion pl on pl.saca_id = pa.saca_id
                                                                inner join " . $con->dbname . ".planificacion_estudiante ples on ples.pla_id = pl.pla_id
                                                                and ples.per_id = $per_id
                                                                and pa.saca_id = $saca_id
                                                                and ples.pes_estado = 1
                                                                and ples.pes_estado_logico = 1
                                                                $strMod )
                        UNION ";
			}
			// Bloque 2
			for ($j = 1; $j < 7; $j++) {
				$sql .= "SELECT pes_id as Ids, mpp.daho_id,pla.saca_id,
                                concat('Paralelo ',mpp.mpp_num_paralelo) as paralelo,
                                pes_jor_b2_h" . $j . " as jor_materia, pes_mat_b2_h" . $j . "_cod as cod_asignatura, asig.asi_nombre as asignatura,
                                CASE ples.pes_jor_b2_h" . $j . "
                                    WHEN 'M' THEN 'Matutino'
                                    WHEN 'N' THEN 'Nocturno'
                                    WHEN 'S' THEN 'Semipresencial'
                                    WHEN 'D' THEN 'Distancia'
                                    WHEN '1' THEN 'Matutino'
                                    WHEN '2' THEN 'Nocturno'
                                    WHEN '3' THEN 'Semipresencial'
                                    WHEN '4' THEN 'Distancia'
                                    else ples.pes_jor_b2_h" . $j . "
                                END AS pes_jornada,
                                'Bloque 2' as bloque, moda.mod_nombre as modalidad, 'Hora " . $j . "' as hora,
                                mpp.mpp_id,
                                case mpp.daho_id
                                when daho.daho_id then daho.daho_descripcion
                                when ifnull(mpp.daho_id,0)=0 then ''
                                end as horario
                        FROM " . $con->dbname . ".planificacion_estudiante ples
                        INNER JOIN " . $con->dbname . ".planificacion pla on pla.pla_id = ples.pla_id
                        INNER JOIN " . $con->dbname . ".modalidad moda ON  moda.mod_id = ples.pes_mod_b2_h" . $j . "
                        INNER JOIN " . $con->dbname . ".malla_academica_detalle mad ON  mad.made_codigo_asignatura = pes_mat_b2_h" . $j . "_cod
                        INNER JOIN " . $con->dbname . ".asignatura asig ON  asig.asi_id = mad.asi_id
                        INNER JOIN " . $con->dbname . ".materia_paralelo_periodo mpp on mpp.mpp_id = ples.pes_mat_b2_h" . $j . "_mpp and mpp.mod_id =moda.mod_id and mpp.paca_id= $paca_2
                        LEFT JOIN " . $con->dbname . ".distributivo_academico_horario daho on daho.daho_id = mpp.daho_id or ifnull(mpp.daho_id,0) = 0
                        where ples.per_id =  $per_id and ples.pla_id = (SELECT distinct ples.pla_id from " . $con->dbname . ".periodo_academico pa
                                                                inner join " . $con->dbname . ".planificacion pl on pl.saca_id = pa.saca_id
                                                                inner join " . $con->dbname . ".planificacion_estudiante ples on ples.pla_id = pl.pla_id
                                                                and ples.per_id = $per_id
                                                                and pa.saca_id = $saca_id
                                                                and ples.pes_estado = 1
                                                                and ples.pes_estado_logico = 1
                                                                $strMod )";
				if ($j < 6) {
					$sql .= "UNION ";
				}
			}
			if ($per_id == null || $pla_id == null) {
				$resultData = [];
			} else {
				$sql .= ') as x group by x.cod_asignatura ;   ';
				$comando = $con->createCommand($sql);
				$resultData = $comando->queryall();
				//\app\models\Utilities::putMessageLogFile('query 1...: '.$sql);
				\app\models\Utilities::putMessageLogFile('consultarDetalleplanificaaut: ' . $comando->getRawSql());

				/*if ($arrFiltro['pla_id'] > 0) {
					                    $modalidad = $arrFiltro["pla_id"];
					                    $comando->bindParam(":pla_id", $modalidad, \PDO::PARAM_INT);
					                }
				*/
			}

			$dataProvider = new ArrayDataProvider([
				'key' => 'id',
				'allModels' => $resultData,
				'pagination' => [
					'pageSize' => Yii::$app->params["pageSize"],
				],
				'sort' => [
					'attributes' => [],
				],
			]);
			if ($onlyData) {
				return $resultData;
			} else {
				return $dataProvider;
			}
		} catch (Exception $e) {
			$resultData = [];
			$dataProvider = new ArrayDataProvider([
				'key' => 'id',
				'allModels' => $resultData,
				'pagination' => [
					'pageSize' => Yii::$app->params["pageSize"],
				],
				'sort' => [
					'attributes' => [],
				],
			]);
			if ($onlyData) {
				return $resultData;
			} else {
				return $dataProvider;
			}
		}
	}

	public function getPla_id($per_id) {
		$con = \Yii::$app->db_academico;

		$sql = "SELECT
                pla_id as id,
                pes_nombres as nombres
                from " . $con->dbname . ".planificacion_estudiante
                where per_id = :per_id and pes_estado_logico = 1 order by pla_id desc limit 0,1;";

		if ($per_id == NULL) {
			$resultData = [];
		} else {
			$comando = $con->createCommand($sql);
			$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
			$resultData = $comando->queryAll();
		}
		return $resultData;
	}
	public function getCedula($per_id) {
		$con = \Yii::$app->db_asgard;

		$sql = "SELECT
                per_id as id,
                per_cedula as cedula
                from " . $con->dbname . ".persona
                where per_id = :per_id and per_estado_logico = 1 limit 0,1;";

		if ($per_id == NULL) {
			$resultData = [];
		} else {
			$comando = $con->createCommand($sql);
			$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
			$resultData = $comando->queryOne();
		}
		return $resultData;
	}

	public function getPlanificacionxPeriodo($paca_id, $per_id) {
		$con = \Yii::$app->db_academico;

		$sql = "SELECT pla.pla_id
                from db_academico.periodo_academico per
                inner join " . $con->dbname . ".planificacion pla on pla.paca_id = per.paca_id
                inner join " . $con->dbname . ".planificacion_estudiante pes on pes.pla_id = pla.pla_id
                where per.paca_id = :paca_id and pes.per_id = :per_id
                limit 0,1;";

		if ($paca_id == NULL) {
			$resultData = [];
		} else {
			$comando = $con->createCommand($sql);
			$comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
			$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
			$resultData = $comando->queryOne();
		}
		return $resultData;
	}

	public function consultarEstxMatPlan($arrFiltro = array(), $onlyData = false) {
		$con = \Yii::$app->db_academico;
		$con1 = \Yii::$app->db_asgard;
		$estado = 1;
		if (isset($arrFiltro) && count($arrFiltro) > 0) {
			\app\models\Utilities::putMessageLogFile('----------------------------------------Con Filtros');
			$pla_id = $arrFiltro['periodo'] ? $arrFiltro['periodo'] : 0;
			$mod_id = $arrFiltro['modalidad'] ? $arrFiltro['modalidad'] : 0;
			$bloque = $arrFiltro['bloque'] ? $arrFiltro['bloque'] : 0;
			$asi_id = $arrFiltro['asi_id'] ? $arrFiltro['asi_id'] : 0;
			//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('pla_id y mod_id '.$arrFiltro['periodo'] .'-'.$arrFiltro['modalidad']);
			$str_search = '';
			$str_search1 = null;
			$strMod = '';

			if (!strcmp($str_search, '')) {
				if ($arrFiltro['periodo'] != 0 || $arrFiltro['modalidad'] != 0) {
					$where .= " WHERE ";
				}

				if ($arrFiltro['periodo'] != 0) {
					$str_search1 = " pla.saca_id = $pla_id ";
					//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('-------------------'. $str_search1 .'---------------------');
				}
				if ($arrFiltro['modalidad'] != 0) {
					$str_search2 .= " pla.mod_id = $mod_id and meu.mod_id = $mod_id";
					//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('-------------------'. $str_search2 .'---------------------');
				}
				if (isset($where)) {
					$str_search1 = (!is_null($str_search1) && !is_null($str_search2) ? $str_search1 . ' AND' : $str_search1);
					$str_search .= $where . $str_search1 . $str_search2;
				}
				\app\models\Utilities::putMessageLogFile('else-------------------' . $str_search . '---------------------');
				//print_r($str_search); die();
			}
			//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('str_search '.$str_search);

			if ($bloque == 0) {
				$filtro = '(pe.pes_mat_b1_h1_cod,
                    pe.pes_mat_b1_h2_cod,
                    pe.pes_mat_b1_h3_cod,
                    pe.pes_mat_b1_h4_cod,
                    pe.pes_mat_b1_h5_cod,
                    pe.pes_mat_b1_h6_cod,
                    pe.pes_mat_b2_h1_cod,
                    pe.pes_mat_b2_h2_cod,
                    pe.pes_mat_b2_h3_cod,
                    pe.pes_mat_b2_h4_cod,
                    pe.pes_mat_b2_h5_cod,
                    pe.pes_mat_b2_h6_cod)';
				$filtro2 = '(pe.pes_mat_b1_h1_mpp,
                    pe.pes_mat_b1_h2_mpp,
                    pe.pes_mat_b1_h3_mpp,
                    pe.pes_mat_b1_h4_mpp,
                    pe.pes_mat_b1_h5_mpp,
                    pe.pes_mat_b1_h6_mpp,
                    pe.pes_mat_b2_h1_mpp,
                    pe.pes_mat_b2_h2_mpp,
                    pe.pes_mat_b2_h3_mpp,
                    pe.pes_mat_b2_h4_mpp,
                    pe.pes_mat_b2_h5_mpp,
                    pe.pes_mat_b2_h6_mpp)';
			}
			if ($bloque == 1) {
				$filtro = '(pe.pes_mat_b1_h1_cod,
                    pe.pes_mat_b1_h2_cod,
                    pe.pes_mat_b1_h3_cod,
                    pe.pes_mat_b1_h4_cod,
                    pe.pes_mat_b1_h5_cod,
                    pe.pes_mat_b1_h6_cod)';
				$filtro2 = '(pe.pes_mat_b1_h1_mpp,
                    pe.pes_mat_b1_h2_mpp,
                    pe.pes_mat_b1_h3_mpp,
                    pe.pes_mat_b1_h4_mpp,
                    pe.pes_mat_b1_h5_mpp,
                    pe.pes_mat_b1_h6_mpp)';
			}
			if ($bloque == 2) {
				$filtro = '(pe.pes_mat_b2_h1_cod,
                    pe.pes_mat_b2_h2_cod,
                    pe.pes_mat_b2_h3_cod,
                    pe.pes_mat_b2_h4_cod,
                    pe.pes_mat_b2_h5_cod,
                    pe.pes_mat_b2_h6_cod)';
				$filtro2 = '(pe.pes_mat_b2_h1_mpp,
                    pe.pes_mat_b2_h2_mpp,
                    pe.pes_mat_b2_h3_mpp,
                    pe.pes_mat_b2_h4_mpp,
                    pe.pes_mat_b2_h5_mpp,
                    pe.pes_mat_b2_h6_mpp)';
			}
			if ($arrFiltro['mpp_id']) {
				$mpp_id = $arrFiltro['mpp_id'];
				$strMod = 'and ' . $mpp_id . ' in ' . $filtro2;
			}

		}

		$sql2 = "SELECT distinct
                        a.asi_id as id,mad.maca_id, pla.saca_id,pla.mod_id,
                        pe.per_id,
                        pe.pes_nombres,
                        case pla.mod_id
                        when 1 then 'ONLINE'
                        when 2 then 'PRESENCIAL'
                        WHEN 3 then 'SEMIPRESENCIAL'
                        WHEN 4 then 'DISTANCIA'
                        end as Modalidad,
                        concat('Paralelo ',mpp.mpp_num_paralelo) as Paralelo,
                        mad.made_codigo_asignatura,
                        case
                        when mad.made_codigo_asignatura  in (pe.pes_mat_b1_h1_cod,
                                                                                                            pe.pes_mat_b1_h2_cod,
                                                                                                            pe.pes_mat_b1_h3_cod,
                                                                                                            pe.pes_mat_b1_h4_cod,
                                                                                                            pe.pes_mat_b1_h5_cod,
                                                                                                            pe.pes_mat_b1_h6_cod)
                        then 'Bloque 1'
                        when mad.made_codigo_asignatura  in (pe.pes_mat_b2_h1_cod,
                                                                                                            pe.pes_mat_b2_h2_cod,
                                                                                                            pe.pes_mat_b2_h3_cod,
                                                                                                            pe.pes_mat_b2_h4_cod,
                                                                                                            pe.pes_mat_b2_h5_cod,
                                                                                                            pe.pes_mat_b2_h6_cod)
                                                                                                            then 'Bloque 2'
                                                                                                            end as bloque,
                        pla.mod_id ,
                        (select concat(saca.saca_nombre,'-',saca.saca_anio)
                            from " . $con->dbname . ".semestre_academico saca,
                            " . $con->dbname . ".planificacion p
                            where p.pla_id = pe.pla_id
                            and saca.saca_id = p.saca_id) as 'Periodo',
                        a.asi_nombre as Materia ,pla.saca_id
                        from  " . $con->dbname . ".planificacion_estudiante pe
                        inner join  " . $con->dbname . ".asignatura a
                        inner join  " . $con->dbname . ".malla_academica_detalle mad on mad.made_codigo_asignatura in $filtro
                        inner join  " . $con->dbname . ".planificacion pla on pla.pla_id = pe.pla_id
                        inner join  " . $con->dbname . ".semestre_academico se on se.saca_id = pla.saca_id
                        inner join  " . $con->dbname . ".materia_paralelo_periodo mpp on mpp.mpp_id in $filtro2
                        and mpp.asi_id = a.asi_id
                        WHERE  pla.saca_id = $pla_id and pla.mod_id = $mod_id
					    and a.asi_id = $asi_id $strMod
                        group by pe.per_id
                        order by pe.pes_nombres;";
		\app\models\Utilities::putMessageLogFile('consultarModalidad: ' . $sql2);
		$comando = $con->createCommand($sql2);

		/*if (isset($arrFiltro) && count($arrFiltro) > 0) {

			                if ($arrFiltro['modalidad'] > 0) {
			                    $modalidad = $arrFiltro["modalidad"];
			                    $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
			                }

			                if ($arrFiltro['periodo'] != '0') {
			                    $periodo = $arrFiltro["periodo"];
			                    $comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
			                }
		*/

		$resultData = $comando->queryAll();
		\app\models\Utilities::putMessageLogFile('consultarModalidad: ' . $comando->getRawSql());
		//\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('consultarModalidad: '.$comando->getRawSql());
		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $resultData,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => [],
			],
		]);
		if ($onlyData) {
			\app\models\Utilities::putMessageLogFile('----------------------------------------true' . $onlyData);
			return $resultData;
		} else {
			\app\models\Utilities::putMessageLogFile('----------------------------------------false' . $onlyData);
			return $dataProvider;
		}
	}
}
