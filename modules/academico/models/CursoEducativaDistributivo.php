<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use yii\data\ArrayDataProvider;
use app\modules\academico\models\CursoEducativa;
use Yii;

/**
 * This is the model class for table "curso_educativa_distributivo".
 *
 * @property int $cedi_id
 * @property int $cedu_id
 * @property int $daca_id
 * @property int $cedi_usuario_ingreso
 * @property int $cedi_usuario_modifica
 * @property string $cedi_estado
 * @property string $cedi_fecha_creacion
 * @property string $cedi_fecha_modificacion
 * @property string $cedi_estado_logico
 *
 * @property CursoEducativa $cedu
 * @property DistributivoAcademico $daca
 */
class CursoEducativaDistributivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso_educativa_distributivo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cedu_id', 'daca_id', 'cedi_usuario_ingreso', 'cedi_estado', 'cedi_estado_logico'], 'required'],
            [['cedu_id', 'daca_id', 'cedi_usuario_ingreso', 'cedi_usuario_modifica'], 'integer'],
            [['cedi_fecha_creacion', 'cedi_fecha_modificacion'], 'safe'],
            [['cedi_estado', 'cedi_estado_logico'], 'string', 'max' => 1],
            [['cedu_id'], 'exist', 'skipOnError' => true, 'targetClass' => CursoEducativa::className(), 'targetAttribute' => ['cedu_id' => 'cedu_id']],
            [['daca_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistributivoAcademico::className(), 'targetAttribute' => ['daca_id' => 'daca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cedi_id' => 'Cedi ID',
            'cedu_id' => 'Cedu ID',
            'daca_id' => 'Daca ID',
            'cedi_usuario_ingreso' => 'Cedi Usuario Ingreso',
            'cedi_usuario_modifica' => 'Cedi Usuario Modifica',
            'cedi_estado' => 'Cedi Estado',
            'cedi_fecha_creacion' => 'Cedi Fecha Creacion',
            'cedi_fecha_modificacion' => 'Cedi Fecha Modificacion',
            'cedi_estado_logico' => 'Cedi Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCedu()
    {
        return $this->hasOne(CursoEducativa::className(), ['cedu_id' => 'cedu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDaca()
    {
        return $this->hasOne(DistributivoAcademico::className(), ['daca_id' => 'daca_id']);
    }

    public function getListadoDistributivoedu($search = NULL, $modalidad = NULL, $asignatura = NULL, $jornada = NULL, $unidadAcademico = NULL, $periodoAcademico = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $search_cond = "%" . $search . "%";
        $estado = "1";
        $str_search = "";
        $str_unidad = "";
        $str_periodo = "";
        $str_modalidad = "";
        $str_jornada = "";

        if (isset($search) && $search != "") {
            $str_search = "(pe.per_pri_nombre like :search OR ";
            $str_search .= "pe.per_pri_apellido like :search OR ";
            $str_search .= "pe.per_cedula like :search) AND ";
        }
        if (isset($modalidad) && $modalidad > 0) {
            $str_modalidad = "m.mod_id = :modalidad AND ";
        }
        if (isset($asignatura) && $asignatura > 0) {
            $str_asignatura = "a.asi_id = :asignatura AND ";
        }
        if (isset($unidadAcademico) && $unidadAcademico > 0) {
            $str_unidad = "ua.uaca_id = :unidad AND ";
        }
        if (isset($periodoAcademico) && $periodoAcademico > 0) {
            $str_periodo = "pa.paca_id = :periodo AND ";
        }
        if (isset($jornada) && $jornada > 0) {
            $str_jornada = "dh.daho_jornada = :jornada AND ";
        }

        $sql = "SELECT 
                    da.daca_id AS Id, 
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS Nombres,
                    pe.per_cedula AS Cedula,
                    ua.uaca_nombre AS UnidadAcademica,
                    m.mod_nombre AS Modalidad,
                    ifnull(CONCAT(blq.baca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),blq.baca_anio) AS Periodo,
                    a.asi_nombre AS Asignatura,
                    CASE
                        WHEN dh.daho_jornada = 1 THEN '(M) Matutino'
                        WHEN dh.daho_jornada = 2 THEN '(N) Nocturno'
                        WHEN dh.daho_jornada = 3 THEN '(S) Semipresencial'
                        WHEN dh.daho_jornada = 4 THEN '(D) Distancia'
                        ELSE ''
                    END AS Jornada
                FROM 
                    " . $con_academico->dbname . ".distributivo_academico AS da 
                    LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario AS dh ON da.daho_id = dh.daho_id
                    INNER JOIN " . $con_academico->dbname . ".profesor AS p ON da.pro_id = p.pro_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad AS m ON da.mod_id = m.mod_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON da.uaca_id = ua.uaca_id
                    INNER JOIN " . $con_academico->dbname . ".asignatura AS a ON da.asi_id = a.asi_id
                    INNER JOIN " . $con_academico->dbname . ".periodo_academico AS pa ON da.paca_id = pa.paca_id
                    INNER JOIN " . $con_db->dbname . ".persona AS pe ON p.per_id = pe.per_id
                    LEFT JOIN " . $con_academico->dbname . ".semestre_academico sem  ON sem.saca_id = pa.saca_id 
                    LEFT JOIN " . $con_academico->dbname . ".bloque_academico blq ON blq.baca_id = pa.baca_id
                WHERE 
                    $str_search 
                    $str_modalidad 
                    $str_asignatura
                    $str_unidad
                    $str_periodo
                    $str_jornada
                    pa.paca_activo = 'A' AND
                    pa.paca_estado = :estado AND
                    da.daca_estado_logico = :estado AND 
                    da.daca_estado = :estado AND
                    p.pro_estado_logico = :estado AND 
                    p.pro_estado = :estado AND
                    m.mod_estado_logico = :estado AND 
                    m.mod_estado = :estado AND
                    ua.uaca_estado_logico = :estado AND 
                    ua.uaca_estado = :estado AND
                    pa.paca_estado_logico = :estado";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($search) && $search != "") {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        if (isset($modalidad) && $modalidad != "") {
            $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        }
        if (isset($asignatura) && $asignatura != "") {
            $comando->bindParam(":asignatura", $asignatura, \PDO::PARAM_INT);
        }
        if (isset($unidadAcademico) && $unidadAcademico != "") {
            $comando->bindParam(":unidad", $unidadAcademico, \PDO::PARAM_INT);
        }
        if (isset($periodoAcademico) && $periodoAcademico != "") {
            $comando->bindParam(":periodo", $periodoAcademico, \PDO::PARAM_INT);
        }
        if (isset($jornada) && $jornada > 0) {
            $comando->bindParam(":jornada", $jornada, \PDO::PARAM_INT);            
        }

        $res = $comando->queryAll();

        $mod_educativa = new CursoEducativa();
        $arr_curso = $mod_educativa->consultarCursostodos();  
        $arr_curso = array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_curso);
        
        //print_r($arr_curso);
        foreach ($res as $key => $value) {
            $value['cursos'] = $arr_curso;
            $res[$key] =  $value;
        /*if ($onlyData)
            return $res;*/
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Nombres', "Cedula", "UnidadAcademica", "Modalidad", "Periodo"],
            ],
        ]);

       // return $dataProvider;
       if ($onlyData) {
        return $res;
    } else {
        return $dataProvider;
    }
    }

 }
}