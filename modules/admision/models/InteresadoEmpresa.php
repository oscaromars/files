<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "interesado_empresa".
 *
 * @property int $iemp_id
 * @property int $int_id
 * @property int $emp_id
 * @property string $iemp_estado
 * @property int $iemp_usuario_ingreso
 * @property int $iemp_usuario_modifica
 * @property string $iemp_fecha_creacion
 * @property string $iemp_fecha_modificacion
 * @property string $iemp_estado_logico
 *
 * @property Interesado $int
 */
class InteresadoEmpresa extends \app\modules\admision\components\CActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interesado_empresa';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_captacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['int_id', 'emp_id', 'iemp_usuario_ingreso', 'iemp_estado_logico'], 'required'],
            [['int_id', 'emp_id', 'iemp_usuario_ingreso', 'iemp_usuario_modifica'], 'integer'],
            [['iemp_fecha_creacion', 'iemp_fecha_modificacion'], 'safe'],
            [['iemp_estado', 'iemp_estado_logico'], 'string', 'max' => 1],
            [['int_id'], 'exist', 'skipOnError' => true, 'targetClass' => Interesado::className(), 'targetAttribute' => ['int_id' => 'int_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iemp_id' => 'Iemp ID',
            'int_id' => 'Int ID',
            'emp_id' => 'Emp ID',
            'iemp_estado' => 'Iemp Estado',
            'iemp_usuario_ingreso' => 'Iemp Usuario Ingreso',
            'iemp_usuario_modifica' => 'Iemp Usuario Modifica',
            'iemp_fecha_creacion' => 'Iemp Fecha Creacion',
            'iemp_fecha_modificacion' => 'Iemp Fecha Modificacion',
            'iemp_estado_logico' => 'Iemp Estado Logico',
        ];
    }
    public function consultaInteresadoEmpresaById($inte_id,$emp_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "
                    SELECT
                            ifnull(iemp_id,0) as iemp_id
                    FROM 
                            db_captacion.interesado_empresa
                    WHERE 
                            int_id = $inte_id
                            and emp_id= $emp_id
                            and iemp_estado = $estado
                            and iemp_estado_logico=$estado
                ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if (empty($resultData['iemp_id']))
            return 0;
        else {
            return $resultData['iemp_id'];
        }
    }
    public function crearInteresadoEmpresa($int_id,$emp_id, $user_id) {
        $con = \Yii::$app->db_captacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "iemp_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", iemp_estado";
        $bsol_sql .= ", 1";

        
        if (isset($int_id)) {
            $param_sql .= ", int_id";
            $bsol_sql .= ", $int_id";
        }
        if (isset($emp_id)) {
            $param_sql .= ", emp_id";
            $bsol_sql .= ", $emp_id";
        }

        if (isset($user_id)) {
            $param_sql .= ", iemp_usuario_ingreso";
            $bsol_sql .= ", $user_id";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".interesado_empresa ($param_sql) VALUES($bsol_sql);";
//            if (isset($int_id))
//                $comando->bindParam(':int_id', $int_id, \PDO::PARAM_INT);
//            if (isset($emp_id))
//                $comando->bindParam(':emp_id', $emp_id, \PDO::PARAM_INT);
//
//            if (isset($user_id))
//                $comando->bindParam(':iemp_usuario_ingreso', $user_id, \PDO::PARAM_INT);

            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.' . 'interesado_empresa');
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInt()
    {
        return $this->hasOne(Interesado::className(), ['int_id' => 'int_id']);
    }
}
