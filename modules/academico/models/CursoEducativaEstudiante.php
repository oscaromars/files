<?php

namespace app\modules\academico\models;
use yii\data\ArrayDataProvider;
use app\modules\academico\models\PeriodoAcademico;
use Yii;

/**
 * This is the model class for table "curso_educativa_estudiante".
 *
 * @property int $ceest_id
 * @property int $cedu_id
 * @property int $est_id
 * @property string $ceest_estado_bloqueo
 * @property int $ceest_usuario_ingreso
 * @property int $ceest_usuario_modifica
 * @property string $ceest_estado
 * @property string $ceest_fecha_creacion
 * @property string $ceest_fecha_modificacion
 * @property string $ceest_estado_logico
 *
 * @property Estudiante $est
 * @property CursoEducativa $cedu
 */
class CursoEducativaEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso_educativa_estudiante';
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
            [['cedu_id', 'est_id', 'ceest_estado_bloqueo', 'ceest_usuario_ingreso', 'ceest_estado', 'ceest_estado_logico'], 'required'],
            [['cedu_id', 'est_id', 'ceest_usuario_ingreso', 'ceest_usuario_modifica'], 'integer'],
            [['ceest_fecha_creacion', 'ceest_fecha_modificacion'], 'safe'],
            [['ceest_estado_bloqueo', 'ceest_estado', 'ceest_estado_logico'], 'string', 'max' => 1],
            [['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudiante::className(), 'targetAttribute' => ['est_id' => 'est_id']],
            [['cedu_id'], 'exist', 'skipOnError' => true, 'targetClass' => CursoEducativa::className(), 'targetAttribute' => ['cedu_id' => 'cedu_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ceest_id' => 'Ceest ID',
            'cedu_id' => 'Cedu ID',
            'est_id' => 'Est ID',
            'ceest_estado_bloqueo' => 'Ceest Estado Bloqueo',
            'ceest_usuario_ingreso' => 'Ceest Usuario Ingreso',
            'ceest_usuario_modifica' => 'Ceest Usuario Modifica',
            'ceest_estado' => 'Ceest Estado',
            'ceest_fecha_creacion' => 'Ceest Fecha Creacion',
            'ceest_fecha_modificacion' => 'Ceest Fecha Modificacion',
            'ceest_estado_logico' => 'Ceest Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEst()
    {
        return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCedu()
    {
        return $this->hasOne(CursoEducativa::className(), ['cedu_id' => 'cedu_id']);
    }
    /**
     * Function Obtiene información de distributivo todos los estudiantes.
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDistributivoasigest($arrFiltro = array(), $reporte) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            /* $str_search .= "(p.per_pri_nombre like :search OR ";
            $str_search .= "p.per_seg_nombre like :search OR ";
            $str_search .= "p.per_pri_apellido like :search OR ";
            $str_search .= "p.per_seg_apellido like :search OR ";
            $str_search .= "p.per_cedula like :search) AND "; */

            if (!empty($arrFiltro['profesor'])) {
                $str_search .= "(pe.per_pri_nombre like :profesor OR ";
                $str_search .= "pe.per_seg_nombre like :profesor OR ";
                $str_search .= "pe.per_pri_apellido like :profesor OR ";
                $str_search .= "pe.per_seg_apellido like :profesor) AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "a.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "a.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "a.paca_id = :periodo AND ";
            }
            /*if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $str_search .= "a.asi_id = :asignatura AND ";
            }*/             
            if ($arrFiltro['curso'] != "" && $arrFiltro['curso'] > 0) {
                $str_search .= "cur.cedu_id = :curso AND ";
            }
   
        }else{
          $mod_paca        = new PeriodoAcademico(); 
          $paca_actual_id  = $mod_paca->getPeriodoAcademicoActual();
          $str_search      = "a.paca_id = ".$paca_actual_id['id']." AND ";
        }


        

        $sql = "SELECT  distinct h.est_id, 
                        d.uaca_nombre as unidad, 
                        e.mod_nombre as modalidad,
                        p.per_cedula as identificacion, 
                        concat(p.per_pri_nombre, ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as estudiante,
                        concat(saca_nombre, '-', baca_nombre,'-',baca_anio) as periodo,
                        z.asi_nombre as asignatura
                FROM " . $con->dbname . ".distributivo_academico a inner join " . $con->dbname . ".profesor b
                    on b.pro_id = a.pro_id 
                    inner join " . $con1->dbname . ".persona c on c.per_id = b.per_id
                    inner join " . $con1->dbname . ".persona pe on pe.per_id = b.per_id
                    inner join " . $con->dbname . ".unidad_academica d on d.uaca_id = a.uaca_id
                    inner join " . $con->dbname . ".modalidad e on e.mod_id = a.mod_id
                    inner join " . $con->dbname . ".periodo_academico f on f.paca_id = a.paca_id
                    inner join " . $con->dbname . ".distributivo_academico_estudiante g on g.daca_id = a.daca_id
                    inner join " . $con->dbname . ".estudiante h on h.est_id = g.est_id
                    inner join " . $con1->dbname . ".persona p on p.per_id = h.per_id
                    inner join " . $con->dbname . ".semestre_academico s on s.saca_id = f.saca_id
                    inner join " . $con->dbname . ".bloque_academico t on t.baca_id = f.baca_id
                    inner join " . $con->dbname . ".asignatura z on a.asi_id = z.asi_id
                    inner join " . $con->dbname . ".curso_educativa cur on cur.paca_id = a.paca_id
                    WHERE $str_search 
                    a.daca_estado = :estado
                    and a.daca_estado_logico = :estado
                    and g.daes_estado = :estado
                    and g.daes_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            /*$search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);*/

            if (!empty($arrFiltro['profesor'])) {
                $search_profe = "%" . $arrFiltro["profesor"] . "%";
                $comando->bindParam(":profesor", $search_profe, \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $search_mod = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $search_mod, \PDO::PARAM_INT);
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $search_per = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $search_per, \PDO::PARAM_INT);
            }
            /*if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $search_asi = $arrFiltro["asignatura"];
                $comando->bindParam(":asignatura", $search_asi, \PDO::PARAM_INT);
            } */
            if ($arrFiltro['curso'] != "" && $arrFiltro['curso'] > 0) {
                $search_cur = $arrFiltro["curso"];
                $comando->bindParam(":curso", $search_cur, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();

        $resultData2 = array();

        foreach ($resultData as $key => $value) {
            $band = 1;

            if(empty($resultData2))
              $resultData2[] = $value;

            foreach ($resultData2 as $key2 => $value2) {

                if ($resultData2[$key2]['est_id'] == $value['est_id']) {
                    if($resultData2[$key2]['asignatura'] == $value['asignatura']){
                        $band = 0;
                    }
                }
            }

            if($band == 1)
                $resultData2[] = $value;
        }

        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData2,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);
        if ($reporte == 1) {
            return $dataProvider;
        } else {
            return $resultData2;
        }
    }
}
