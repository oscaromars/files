<?php

namespace app\modules\academico\models;

use yii\base\Exception;
use Yii;
use yii\data\ArrayDataProvider;
use app\models\Utilities;

/**
 * This is the model class for table "materia_paralelo_periodo".
 *
 * @property int $mpp_id
 * @property int $asi_id
 * @property int $mod_id
 * @property int $paca_id
 * @property int $daho_id
 * @property int $mpp_num_paralelo
 * @property int $mpp_usuario_ingreso
 * @property int $mpp_usuario_modifica
 * @property int $mpp_estado
 * @property string $mpp_fecha_creacion
 * @property string $mpp_fecha_modificacion
 * @property string $mpp_estado_logico
 *
 * @property DistributivoAcademico[] $distributivoAcademicos
 */
class MateriaParaleloPeriodo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'materia_paralelo_periodo';
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
            [['asi_id', 'mod_id', 'paca_id', 'daho_id', 'mpp_num_paralelo', 'mpp_usuario_ingreso', 'mpp_usuario_modifica', 'mpp_estado'], 'integer'],
            [['mpp_fecha_creacion', 'mpp_fecha_modificacion'], 'safe'],
            [['mpp_estado_logico'], 'string', 'max' => 1],
        ];
    }


public static function getNumparalelo(){
    return [
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
        ];
}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsig()
    {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMod()
    {
        return $this->hasOne(Modalidad::className(), ['mod_id' => 'mod_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'mpp_id' => 'Id',
            'paca_id' => 'Periodo',
            'asi_id' => 'Asignatura',
            'mod_id'=>'Modalidad',
            'mpp_num_paralelo'=>'Número de Paralelos',
            'mpp_usuario_ingreso' => '',
            'mpp_usuario_modifica' => '',
            'mpp_estado' => 'Estado',
            'mpp_fecha_creacion' => '',
            'mpp_fecha_modificacion' => '',
            'mpp_estado_logico' => '',

        ];
    }
    public function getParalelosAsignatura($paca_id,$mod_id,$asi_id){
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "select mpp.mpp_id id, mpp_num_paralelo name  from "
                         . $con->dbname . ".materia_paralelo_periodo  as mpp
                         left join " . $con->dbname . ".distributivo_academico as dc on mpp.mpp_id= dc.mpp_id and mpp.asi_id =dc.asi_id and mpp.mod_id=dc.mod_id and mpp.paca_id=dc.paca_id

                        where  dc.mpp_id is null and  mpp.asi_id =:asi_id
                        and mpp.mod_id=:mod_id
                        and mpp.paca_id=:paca_id
                        and mpp_estado=:estado";
        \app\models\Utilities::putMessageLogFile($sql);
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

     public function getParalelosAlumnos($paca_id,$mod_id,$asi_id){
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "select
                concat (mpp.mpp_num_paralelo, ' ', mpp.asi_id,'(',mpp.daho_id,')') as course,
                mpp.mpp_id id, mpp.mpp_num_paralelo,mpp.daho_id, mpp.paca_id,
                mpp.mod_id, mpp.asi_id
                  from "
                         . $con->dbname . ".materia_paralelo_periodo  as mpp
                         inner join db_academico.planificacion_estudiante b
                on mpp.mpp_id in (b.pes_mat_b1_h1_mpp,b.pes_mat_b1_h2_mpp,b.pes_mat_b1_h3_mpp,
                b.pes_mat_b1_h4_mpp,b.pes_mat_b1_h5_mpp,b.pes_mat_b1_h6_mpp,
                b.pes_mat_b2_h1_mpp,b.pes_mat_b2_h2_mpp,b.pes_mat_b2_h3_mpp,
                b.pes_mat_b2_h4_mpp,b.pes_mat_b2_h5_mpp,b.pes_mat_b2_h6_mpp)
                         left join " . $con->dbname . ".distributivo_academico as dc on mpp.mpp_id= dc.mpp_id and mpp.asi_id =dc.asi_id and mpp.mod_id=dc.mod_id and mpp.paca_id=dc.paca_id
                        where  dc.mpp_id is null and  mpp.asi_id =:asi_id
                        and mpp.mod_id=:mod_id
                        and mpp.paca_id=:paca_id
                        and mpp_estado=:estado";
        \app\models\Utilities::putMessageLogFile($sql);
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consultaParalelosHorario
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer
     * @return
     */
    public function consultaParalelosHorario($asi_id, $paca_id,$mod_id){
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT
                    mpp.mpp_id,
                    mpp.asi_id,
                    mpp.mod_id,
                    mpp.paca_id,
                    mpp.daho_id,
                    IFNULL(CONCAT('Paralelo',' ', mpp.mpp_num_paralelo),' ') AS mpp_num_paralelo,
                    IFNULL((SELECT daho.daho_descripcion
                            FROM ". $con->dbname . ".distributivo_academico_horario daho
                            WHERE daho.daho_id = mpp.daho_id),'No Asignado') as daho_descripcion
                    FROM ". $con->dbname . ".materia_paralelo_periodo mpp
                    /* INNER JOIN ". $con->dbname . ".distributivo_academico_horario daho
                    ON daho.daho_id = mpp.daho_id */
                    WHERE mpp.asi_id = :asi_id AND
                        mpp.paca_id = :paca_id AND
                        mpp.mod_id = :mod_id AND
                        mpp.mpp_estado = :estado AND
                        mpp.mpp_estado_logico = :estado;";
        //\app\models\Utilities::putMessageLogFile($sql);
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
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
     * Function consultaParalelosHorarioxmpp_id
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer
     * @return
     */
    public function consultaParalelosHorarioxmpp_id($mpp_id){
        $con = \Yii::$app->db_academico;
        //$estado = 1;

        $sql = "SELECT
                    mpp.mpp_id,
                    mpp.asi_id,
                    mpp.mod_id,
                    mpp.paca_id,
                    mpp.daho_id,
                    mpp.mpp_num_paralelo,
                    moda.mod_descripcion,
                    asi.asi_nombre
                    FROM ". $con->dbname . ".materia_paralelo_periodo mpp
                    INNER JOIN ". $con->dbname . ".modalidad moda ON moda.mod_id = mpp.mod_id
                    INNER JOIN ". $con->dbname . ".asignatura asi ON asi.asi_id = mpp.asi_id
                    WHERE mpp.mpp_id = :mpp_id";

        //\app\models\Utilities::putMessageLogFile($sql);
        $comando = $con->createCommand($sql);
        //$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":mpp_id", $mpp_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
            return $resultData;

    }

    /**
     * Function modifica materia paralelo perido por mpp_id.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function modificarMateriaparalelo($mpp_id, $daho_id, $mpp_usuario_modifica, $mpp_fecha_modificacion) {

        $con = \Yii::$app->db_academico;
        \app\models\Utilities::putMessageLogFile('xxx '.$mpp_id);
        \app\models\Utilities::putMessageLogFile('ccc '.$daho_id);
        \app\models\Utilities::putMessageLogFile('sss '.$mpp_usuario_modifica);
        \app\models\Utilities::putMessageLogFile('www '. $mpp_fecha_modificacion);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $sql = "UPDATE " . $con->dbname . ".materia_paralelo_periodo
                      SET daho_id = :daho_id,
                          mpp_usuario_modifica = :mpp_usuario_modifica,
                          mpp_fecha_modificacion = :mpp_fecha_modificacion
                      WHERE
                          mpp_id = :mpp_id ";
            \app\models\Utilities::putMessageLogFile('sql '. $sql);
            $comando = $con->createCommand($sql);
            $comando->bindParam(":mpp_id", $mpp_id, \PDO::PARAM_INT);
            $comando->bindParam(":daho_id", $daho_id, \PDO::PARAM_INT);
            $comando->bindParam(":mpp_usuario_modifica", $mpp_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":mpp_fecha_modificacion", $mpp_fecha_modificacion, \PDO::PARAM_STR);
            \app\models\Utilities::putMessageLogFile($sql);
            $response = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function conmsullta asiganturas para asignar a paralelo.
     * @author Julio Lopez <analistadesarrollo03@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarMateriaparaleloperiodo($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] != "0") {
                $str_search .= " mpp.paca_id = :periodo AND";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] != "0") {
                $str_search .= " a.uaca_id = :unidad AND";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] != "0") {
                $str_search .= " mpp.mod_id = :modalidad AND";
            }    
             if ($arrFiltro['asignaturas'] != "" && $arrFiltro['asignaturas'] != "0") {
                $str_search .= " mpp.asi_id = :asignaturas AND";
            }           
        }

        $sql = "SELECT mpp.asi_id, a.asi_nombre, mpp.mod_id, a.uaca_id, moda.mod_nombre, paca_id,max(mpp_num_paralelo) as mpp_num_paralelo
                from db_academico.materia_paralelo_periodo as mpp 
                INNER JOIN db_academico.asignatura as a on a.asi_id=mpp.asi_id
                INNER JOIN db_academico.modalidad as moda on mpp.mod_id = moda.mod_id
                where  $str_search
                1 = 1 AND
                mpp.mpp_estado = 1 and mpp.mpp_estado_logico=1
                group by a.asi_nombre,mpp.asi_id, mpp.mod_id, moda.mod_nombre, mpp.paca_id,a.uaca_id";

        $comando = $con->createCommand($sql);
          $sql = $sql . "order by a.asi_nombre ASC";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] != "0") {
                $comando->bindParam(":periodo", $arrFiltro["periodo"], \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] != "0") {
               $comando->bindParam(":unidad", $arrFiltro["unidad"], \PDO::PARAM_STR);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] != "0") {
                $comando->bindParam(":modalidad", $arrFiltro["modalidad"], \PDO::PARAM_STR);
            }
             if ($arrFiltro['asignaturas'] != "" && $arrFiltro['asignaturas'] != "0") {
                $comando->bindParam(":asignaturas", $arrFiltro["asignaturas"], \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'attributes' => ['asi_id', 'paca_id', 'mpp_num_paralelo'],
            ],
        ]);

        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }
}