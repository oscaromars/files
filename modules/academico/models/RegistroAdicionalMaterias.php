<?php

namespace app\modules\academico\models;

use Exception;
use Yii;

/**
 * This is the model class for table "registro_adicional_materias".
 *
 * @property int $rama_id
 * @property int $ron_id
 * @property int $per_id
 * @property int $pla_id
 * @property int $paca_id
 * @property int $rpm_id
 * @property int $roi_id_1
 * @property int $roi_id_2
 * @property int $roi_id_3
 * @property int $roi_id_4
 * @property int $roi_id_5
 * @property int $roi_id_6
 * @property string $rama_estado
 * @property string $rama_fecha_creacion
 * @property string $rama_fecha_modificacion
 * @property string $rama_estado_logico
 *
 * @property RegistroOnline $ron
 */
class RegistroAdicionalMaterias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_adicional_materias';
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
            [['ron_id', 'per_id', 'pla_id', 'paca_id', 'rama_estado', 'rama_estado_logico'], 'required'],
            [['ron_id', 'per_id', 'pla_id', 'paca_id', 'rpm_id', 'roi_id_1', 'roi_id_2', 'roi_id_3', 'roi_id_4', 'roi_id_5', 'roi_id_6'], 'integer'],
            [['rama_fecha_creacion', 'rama_fecha_modificacion'], 'safe'],
            [['rama_estado', 'rama_estado_logico'], 'string', 'max' => 1],
            [['ron_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroOnline::className(), 'targetAttribute' => ['ron_id' => 'ron_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rama_id' => 'Rama ID',
            'ron_id' => 'Ron ID',
            'per_id' => 'Per ID',
            'pla_id' => 'Pla ID',
            'paca_id' => 'Paca ID',
            'rpm_id' => 'Rpm ID',
            'roi_id_1' => 'Roi Id 1',
            'roi_id_2' => 'Roi Id 2',
            'roi_id_3' => 'Roi Id 3',
            'roi_id_4' => 'Roi Id 4',
            'roi_id_5' => 'Roi Id 5',
            'roi_id_6' => 'Roi Id 6',
            'rama_estado' => 'Rama Estado',
            'rama_fecha_creacion' => 'Rama Fecha Creacion',
            'rama_fecha_modificacion' => 'Rama Fecha Modificacion',
            'rama_estado_logico' => 'Rama Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRon()
    {
        return $this->hasOne(RegistroOnline::className(), ['ron_id' => 'ron_id']);
    }

    public function insertRegistroAdicionalMaterias(
        $ron_id,
        $per_id,
        $pla_id,
        $paca_id,
        $roi_id_1,
        $roi_id_2,
        $roi_id_3,
        $roi_id_4,
        $roi_id_5,
        $roi_id_6
        
    ){
        $con = Yii::$app->db_academico;
        $transaction=$con->beginTransaction();
        $date = date(Yii::$app->params['dateTimeByDefault']);

        try{
            if(empty($roi_id_1)){ $roi_id_1 = "NULL"; }
            if(empty($roi_id_2)){ $roi_id_2 = "NULL"; }
            if(empty($roi_id_3)){ $roi_id_3 = "NULL"; }
            if(empty($roi_id_4)){ $roi_id_4 = "NULL"; }
            if(empty($roi_id_5)){ $roi_id_5 = "NULL"; }
            if(empty($roi_id_6)){ $roi_id_6 = "NULL"; }            

            $sql = "INSERT INTO " . $con->dbname . ".registro_adicional_materias
                    (ron_id,
                    per_id,
                    pla_id,
                    paca_id,
                    rpm_id,
                    roi_id_1,
                    roi_id_2,
                    roi_id_3,
                    roi_id_4,
                    roi_id_5,
                    roi_id_6,
                    rama_estado,
                    rama_fecha_creacion,
                    rama_fecha_modificacion,
                    rama_estado_logico
                    )
                    VALUES (
                        $ron_id,
                        $per_id,
                        $pla_id,
                        $paca_id,
                        Null,
                        $roi_id_1,
                        $roi_id_2,
                        $roi_id_3,
                        $roi_id_4,
                        $roi_id_5,
                        $roi_id_6,
                        1, 
                        '$date', 
                        '$date',
                        1
                    )";

            $command = $con->createCommand($sql);
            $command->execute();
           
           \app\models\Utilities::putMessageLogFile('insertRegistroAdicionalMaterias: ' . $command->getRawSql());

            if ($transaction !== null){
                $transaction->commit();
            }

            return true;

        } catch (Exception $ex) {
            if ($transaction !== null)
                $transaction->rollback();
            return FALSE;
        }

    }


    public function insertarActualizacionRegistroAdicional($rama_id, $ron_id,$roi_id_nuevo,$i) {
        /*\app\models\Utilities::putMessageLogFile('ron_id: ' . $ron_id);
        \app\models\Utilities::putMessageLogFile('roi_id_nuevo: ' . $roi_id_nuevo);
        \app\models\Utilities::putMessageLogFile('i: ' . $i);*/

        $con = \Yii::$app->db_academico;
        $rama_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
            ("UPDATE " . $con->dbname . ".registro_adicional_materias               
              SET roi_id_".($i+1)." = :roi_id_nuevo,
                rama_fecha_modificacion = :rama_fecha_modificacion
              WHERE 
                rama_id = :rama_id
                AND ron_id = :ron_id
                AND rama_estado = :estado 
                AND rama_estado_logico = :estado");

            if (isset($rama_id)) {
                $comando->bindParam(':rama_id', $rama_id, \PDO::PARAM_INT);
            }
            if (isset($roi_id_nuevo)) {
                $comando->bindParam(':roi_id_nuevo', $roi_id_nuevo, \PDO::PARAM_INT);
            }
            if (isset($ron_id)) {
                $comando->bindParam(':ron_id', $ron_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($rama_fecha_modificacion)))) {
                $comando->bindParam(':rama_fecha_modificacion', $rama_fecha_modificacion, \PDO::PARAM_STR);
            }
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

            $result = $comando->execute();

            if ($trans !== null)
                $trans->commit();
                                            
            \app\models\Utilities::putMessageLogFile('insertarActualizacionGastos: ' . $comando->getRawSql());

            return TRUE;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
}
