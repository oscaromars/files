<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "matriculacion_programa_inscrito".
 *
 * @property int $mpin_id
 * @property int $ppro_id
 * @property int $adm_id
 * @property int $est_id
 * @property string $mpin_fecha_matriculacion
 * @property string $mpin_ficha
 * @property string $mpin_fecha_registro_ficha
 * @property int $mpin_usuario_ingresa
 * @property string $mpin_estado
 * @property string $mpin_fecha_creacion
 * @property int $mpin_usuario_modifica
 * @property string $mpin_fecha_modificacion
 * @property string $mpin_estado_logico
 *
 * @property PromocionPrograma $ppro
 * @property Estudiante $est
 */
class MatriculacionProgramaInscrito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'matriculacion_programa_inscrito';
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
            [['ppro_id', 'adm_id', 'mpin_estado', 'mpin_estado_logico'], 'required'],
            [['ppro_id', 'adm_id', 'est_id', 'mpin_usuario_ingresa', 'mpin_usuario_modifica'], 'integer'],
            [['mpin_fecha_matriculacion', 'mpin_fecha_registro_ficha', 'mpin_fecha_creacion', 'mpin_fecha_modificacion'], 'safe'],
            [['mpin_ficha', 'mpin_estado', 'mpin_estado_logico'], 'string', 'max' => 1],
            [['ppro_id'], 'exist', 'skipOnError' => true, 'targetClass' => PromocionPrograma::className(), 'targetAttribute' => ['ppro_id' => 'ppro_id']],
            [['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudiante::className(), 'targetAttribute' => ['est_id' => 'est_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mpin_id' => 'Mpin ID',
            'ppro_id' => 'Ppro ID',
            'adm_id' => 'Adm ID',
            'est_id' => 'Est ID',
            'mpin_fecha_matriculacion' => 'Mpin Fecha Matriculacion',
            'mpin_ficha' => 'Mpin Ficha',
            'mpin_fecha_registro_ficha' => 'Mpin Fecha Registro Ficha',
            'mpin_usuario_ingresa' => 'Mpin Usuario Ingresa',
            'mpin_estado' => 'Mpin Estado',
            'mpin_fecha_creacion' => 'Mpin Fecha Creacion',
            'mpin_usuario_modifica' => 'Mpin Usuario Modifica',
            'mpin_fecha_modificacion' => 'Mpin Fecha Modificacion',
            'mpin_estado_logico' => 'Mpin Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPpro()
    {
        return $this->hasOne(PromocionPrograma::className(), ['ppro_id' => 'ppro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEst()
    {
        return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
    }
    
    /**
     * Function guardar matriculacion inscrito
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar el código de matriculacion inscrito).
     */
    public function insertarMatriculainscrito($pppr_id, $adm_id, $est_id, $mpin_fecha_registro_ficha, $mpin_usuario_ingresa, $mpin_fecha_creacion) {

        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "mpin_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", mpin_estado";
        $bsol_sql .= ", 1";
        if (isset($pppr_id)) {
            $param_sql .= ", pppr_id";
            $bsol_sql .= ", :pppr_id";
        }

        if (isset($adm_id)) {
            $param_sql .= ", adm_id";
            $bsol_sql .= ", :adm_id";
        }

        if (isset($est_id)) {
            $param_sql .= ", est_id";
            $bsol_sql .= ", :est_id";
        }

        if (isset($mpin_fecha_registro_ficha)) {
            $param_sql .= ", mpin_fecha_registro_ficha";
            $bsol_sql .= ", :mpin_fecha_registro_ficha";
        }

        if (isset($mpin_usuario_ingresa)) {
            $param_sql .= ", mpin_usuario_ingresa";
            $bsol_sql .= ", :mpin_usuario_ingresa";
        }

        if (isset($mpin_fecha_creacion)) {
            $param_sql .= ", mpin_fecha_creacion";
            $bsol_sql .= ", :mpin_fecha_creacion";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".matriculacion_programa_inscrito ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($pppr_id)) {
                $comando->bindParam(':pppr_id', $pppr_id, \PDO::PARAM_INT);
            }

            if (isset($adm_id)) {
                $comando->bindParam(':adm_id', $adm_id, \PDO::PARAM_INT);
            }

            if (isset($est_id)) {
                $comando->bindParam(':est_id', $est_id, \PDO::PARAM_INT);
            }

            if (isset($mpin_fecha_registro_ficha)) {
                $comando->bindParam(':mpin_fecha_registro_ficha', $mpin_fecha_registro_ficha, \PDO::PARAM_STR);
            }

            if (isset($mpin_usuario_ingresa)) {
                $comando->bindParam(':mpin_usuario_ingresa', $mpin_usuario_ingresa, \PDO::PARAM_INT);
            }

            if (isset($mpin_fecha_creacion)) {
                $comando->bindParam(':mpin_fecha_creacion', $mpin_fecha_creacion, \PDO::PARAM_STR);
            }
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.matriculacion_programa_inscrito');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
    
    /**
     * Function modifica numeros de paralelo en promocion.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;     *          
     * @param
     * @return
     */
    public function modificarMatriculainscrito($pppr_id, $adm_id, $mpin_usuario_modifica) {
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
                    ("UPDATE " . $con->dbname . ".matriculacion_programa_inscrito		       
                      SET pppr_id = :pppr_id,                       
                          mpin_fecha_modificacion = :mpin_fecha_modificacion,
                          mpin_usuario_modifica = :mpin_usuario_modifica
                      WHERE adm_id = :adm_id AND                        
                            mpin_estado = :estado AND
                            mpin_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":mpin_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":mpin_usuario_modifica", $mpin_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":pppr_id", $pppr_id, \PDO::PARAM_INT);
            $comando->bindParam(":adm_id", $adm_id, \PDO::PARAM_INT);
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
