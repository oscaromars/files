<?php

namespace app\modules\academico\models;

use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "paralelo_promocion_programa".
 *
 * @property int $pppr_id
 * @property int $ppro_id
 * @property int $pppr_cupo
 * @property int $pppr_cupo_actual
 * @property int $pppr_usuario_ingresa
 * @property string $pppr_estado
 * @property string $pppr_fecha_creacion
 * @property int $pppr_usuario_modifica
 * @property string $pppr_fecha_modificacion
 * @property string $pppr_estado_logico
 *
 * @property PromocionPrograma $ppro
 */
class ParaleloPromocionPrograma extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'paralelo_promocion_programa';
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
            [['ppro_id', 'pppr_cupo', 'pppr_estado', 'pppr_estado_logico'], 'required'],
            [['ppro_id', 'pppr_cupo', 'pppr_cupo_actual', 'pppr_usuario_ingresa', 'pppr_usuario_modifica'], 'integer'],
            [['pppr_fecha_creacion', 'pppr_fecha_modificacion'], 'safe'],
            [['pppr_estado', 'pppr_estado_logico'], 'string', 'max' => 1],
            [['ppro_id'], 'exist', 'skipOnError' => true, 'targetClass' => PromocionPrograma::className(), 'targetAttribute' => ['ppro_id' => 'ppro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pppr_id' => 'Pppr ID',
            'ppro_id' => 'Ppro ID',
            'pppr_cupo' => 'Pppr Cupo',
            'pppr_cupo_actual' => 'Pppr Cupo Actual',
            'pppr_usuario_ingresa' => 'Pppr Usuario Ingresa',
            'pppr_estado' => 'Pppr Estado',
            'pppr_fecha_creacion' => 'Pppr Fecha Creacion',
            'pppr_usuario_modifica' => 'Pppr Usuario Modifica',
            'pppr_fecha_modificacion' => 'Pppr Fecha Modificacion',
            'pppr_estado_logico' => 'Pppr Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPpro() {
        return $this->hasOne(PromocionPrograma::className(), ['ppro_id' => 'ppro_id']);
    }

    /**
     * Function consulta los paralelos por programa. 
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarParalelosxPrograma($promo_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT pppr_id id, pppr_descripcion name                 
                FROM 
                " . $con->dbname . ".paralelo_promocion_programa  ppp 
                WHERE ppro_id = :promo_id AND
                   pppr_estado = :estado AND
                   pppr_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":promo_id", $promo_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function getPromocion
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (información del aspirante)
     */
    public static function getParalelos($ppro_id, $onlyData = false) {
        $con = \Yii::$app->db;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;

        $sql = " SELECT        
                    ppp.pppr_id as pppr_id,
                    ppp.ppro_id as ppro_id,                    
                    ppp.pppr_cupo as pppr_cupo,
                    ppp.pppr_cupo_actual as pppr_cupo_actual,
                    ppp.pppr_fecha_creacion as pppr_fecha_creacion
                    
                FROM " . $con1->dbname . ".paralelo_promocion_programa ppp                   
                WHERE 
                ppp.ppro_id = :ppro_id AND
                ppp.pppr_estado = :estado AND
                ppp.pppr_estado_logico = :estado 
                ORDER BY ppp.pppr_fecha_creacion DESC ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ppro_id", $ppro_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'ppr.ppro_codigo',
                    ' ppr.ppro_anio',
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
     * Function eliminar el paralelo de posgrados, cambia el estado a 0
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property 
     * @return  
     */
    public function deleteParelelo($pppr_id, $pppr_usuario_modifica) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $estado_cambio = 0;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("  
                      UPDATE " . $con->dbname . ".paralelo_promocion_programa                      
                      SET pppr_estado = :estado_cambio, 
                          pppr_estado_logico = :estado_cambio,
                          pppr_usuario_modifica = :pppr_usuario_modifica,
                          pppr_fecha_modificacion = :fecha_modificacion
                      WHERE pppr_id = :pppr_id ");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":estado_cambio", $estado_cambio, \PDO::PARAM_STR);
            $comando->bindParam(":pppr_id", $pppr_id, \PDO::PARAM_INT);
            $comando->bindParam(":pppr_usuario_modifica", $pppr_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
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
     * Function Consultar datos de paralelo promocion segun id de paralelo y id de promocion.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function getParalelosxids($pppr_id, $ppro_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT 	
                        pppr.pppr_cupo as pppr_cupo,
                        pppr.pppr_cupo_actual as pppr_cupo_actual,
                        pppr.pppr_fecha_creacion as pppr_fecha_creacion
                        
                FROM " . $con->dbname . ".paralelo_promocion_programa pppr                    
                WHERE   pppr.pppr_id = :pppr_id
                        AND pppr.ppro_id = :ppro_id
                        AND pppr.pppr_estado = :estado
                        AND pppr.pppr_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pppr_id", $pppr_id, \PDO::PARAM_INT);
        $comando->bindParam(":ppro_id", $ppro_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function actualizar cupo de paralelos x promocion
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer 
     * @return  
     */
    public function actualizarParalelo($con, $ppro_id, $pppr_id, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $params_sql = "";
        for ($i = 0; $i < (count($parameters) - 1); $i++) {
            if (isset($parameters[$i])) {
                $params_sql .= $keys[$i] . " = '" . $parameters[$i] . "',";
            }
        }
        $params_sql .= $keys[count($parameters) - 1] . " = '" . $parameters[count($parameters) - 1] . "'";
        try {
            $sql = "UPDATE " . $con->dbname . '.' . $name_table .
                    " SET $params_sql" .
                    " WHERE pppr_id=$pppr_id AND ppro_id= $ppro_id";
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            if ($trans !== null) {
                return true;
            } else {
                $transaction->commit();
                return true;
            }
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function ObtenerCupodisponible
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Cupo disponible paralelo)
     */
    public function ObtenerCupodisponible($pppr_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT pppr_cupo_actual cupo 
                FROM " . $con->dbname . ".paralelo_promocion_programa 
                WHERE pppr_id = :pppr_id                   
                    and pppr_estado = :estado
                    and pppr_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pppr_id", $pppr_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }   

    /**
     * Function actualiza cupo actual del paralelo.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;     *          
     * @param
     * @return
     */
    public function actualizarCupoparalelo($pppr_id, $ppro_id, $pppr_usuario_modifica) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".paralelo_promocion_programa		       
                      SET pppr_cupo_actual = pppr_cupo_actual - 1,                       
                          pppr_fecha_modificacion = :pppr_fecha_modificacion,
                          pppr_usuario_modifica = :pppr_usuario_modifica
                      WHERE pppr_id = :pppr_id AND
                            ppro_id = :ppro_id AND
                            pppr_estado = :estado AND
                            pppr_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":pppr_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":pppr_usuario_modifica", $pppr_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":pppr_id", $pppr_id, \PDO::PARAM_INT);
            $comando->bindParam(":ppro_id", $ppro_id, \PDO::PARAM_INT);
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
     * Function Consultar datos de paralelo promocion segun id de paralelo y id de promocion.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarMatriculacionxadmid($per_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT
                    mpi.pppr_id as paralelo,
                    ppp.ppro_id as promocion,
                    ppp.pppr_cupo_actual as disponible,
                    est.est_matricula as matricula
                FROM " . $con->dbname . ".matriculacion_programa_inscrito mpi
                INNER JOIN " . $con->dbname . ".paralelo_promocion_programa ppp ON ppp.pppr_id =  mpi.pppr_id
                INNER JOIN " . $con->dbname . ".estudiante est ON est.est_id =  mpi.est_id
                WHERE est.per_id = :per_id AND
                      mpi.mpin_estado = :estado AND
                      mpi.mpin_estado_logico =:estado AND
                      ppp.pppr_estado =:estado AND
                      ppp.pppr_estado_logico =:estado AND
                      est.est_estado =:estado AND
                      est.est_estado_logico =:estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        // $comando->bindParam(":adm_id", $adm_id, \PDO::PARAM_INT);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    /**
     * Function actualiza cupo actual del paralelo.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;     *          
     * @param
     * @return
     */
    public function modificarCupoparalelo($pppr_id, $ppro_id, $pppr_usuario_modifica) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".paralelo_promocion_programa		       
                      SET pppr_cupo_actual = pppr_cupo_actual + 1,                       
                          pppr_fecha_modificacion = :pppr_fecha_modificacion,
                          pppr_usuario_modifica = :pppr_usuario_modifica
                      WHERE pppr_id = :pppr_id AND
                            ppro_id = :ppro_id AND
                            pppr_estado = :estado AND
                            pppr_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":pppr_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":pppr_usuario_modifica", $pppr_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":pppr_id", $pppr_id, \PDO::PARAM_INT);
            $comando->bindParam(":ppro_id", $ppro_id, \PDO::PARAM_INT);
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

}
