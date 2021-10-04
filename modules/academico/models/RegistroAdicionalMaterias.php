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
 * @property int $roi_id_7
 * @property int $roi_id_8
 * @property string $rama_estado
 * @property string $rama_fecha_creacion
 * @property string $rama_fecha_modificacion
 * @property string $rama_estado_logico
 * @property string $pfes_id
 * @property int $rama_usuario_ingreso
 * @property int $rama_usuario_modifica
 *
 * @property RegistroOnline $ron
 */
class RegistroAdicionalMaterias extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'registro_adicional_materias';
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
    public function attributeLabels() {
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
            'roi_id_7' => 'Roi Id 7',
            'roi_id_8' => 'Roi Id 8',
            'rama_estado' => 'Rama Estado',
            'rama_fecha_creacion' => 'Rama Fecha Creacion',
            'rama_fecha_modificacion' => 'Rama Fecha Modificacion',
            'rama_estado_logico' => 'Rama Estado Logico',
            'pfes_id' => 'Pfes ID',
            'rama_usuario_ingreso' => 'Rama Usuario Ingreso',
            'rama_usuario_modifica' => 'Rama Usuario Modifica',
        ];
    }

    /**
     * Funcion para insertar las data a la tabla registro adicional materias
     * @author Luis Cajamarca <analistadesarrollo04>
     * @param
     * @return
     */
    public function getRon() {
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
        $roi_id_6,
        $roi_id_7,
        $roi_id_8,
        $usuario

    ) {
        $con = Yii::$app->db_academico;
        $transaction = $con->beginTransaction();
        $date = date(Yii::$app->params['dateTimeByDefault']);

        try {
            if (empty($roi_id_1)) {$roi_id_1 = "NULL";}
            if (empty($roi_id_2)) {$roi_id_2 = "NULL";}
            if (empty($roi_id_3)) {$roi_id_3 = "NULL";}
            if (empty($roi_id_4)) {$roi_id_4 = "NULL";}
            if (empty($roi_id_5)) {$roi_id_5 = "NULL";}
            if (empty($roi_id_6)) {$roi_id_6 = "NULL";}
            if (empty($roi_id_7)) {$roi_id_7 = "NULL";}
            if (empty($roi_id_8)) {$roi_id_8 = "NULL";}

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
                    roi_id_7,
                    roi_id_8,
                    rama_estado,
                    rama_fecha_creacion,
                    rama_fecha_modificacion,
                    rama_estado_logico,
                    pfes_id,
                    rama_usuario_ingreso,
                    rama_usuario_modifica
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
                        $roi_id_7,
                        $roi_id_8,
                        1,
                        '$date',
                        Null,
                        1,
                        Null,
                        $usuario,
                        Null
                    )";

            $command = $con->createCommand($sql);
            $command->execute();

            \app\models\Utilities::putMessageLogFile('insertRegistroAdicionalMaterias: ' . $command->getRawSql());

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
     * Funcion update las data a la tabla registro adicional materias
     * @author Luis Cajamarca <analistadesarrollo04>
     * @param
     * @return
     */

    public function insertarActualizacionRegistroAdicional($rama_id, $ron_id, $roi_id_nuevo, $i) {
        /*\app\models\Utilities::putMessageLogFile('ron_id: ' . $ron_id);
                    \app\models\Utilities::putMessageLogFile('roi_id_nuevo: ' . $roi_id_nuevo);
        */

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
              SET roi_id_" . ($i + 1) . " = :roi_id_nuevo,
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

            if ($trans !== null) {
                $trans->commit();
            }

            \app\models\Utilities::putMessageLogFile('insertarActualizacionGastos: ' . $comando->getRawSql());

            return TRUE;
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }

            return FALSE;
        }
    }
    public function materiasMatriculadas($ron_id) {
        $con = \Yii::$app->db_academico;
        try{
            $sql = "SELECT replace(concat(ifnull(roi_id_1,'-'),' ',
                                            ifnull(roi_id_2,'-'),' ',
                                            ifnull(roi_id_3,'-'),' ',
                                            ifnull(roi_id_4,'-'),' ',
                                            ifnull(roi_id_5,'-'),' ',
                                            ifnull(roi_id_6,'-')),'-','')  as materias
                                from " . $con->dbname . ".registro_adicional_materias 
                                where ron_id = $ron_id limit 0,1;";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        return $resultData;
        } catch (Exception $ex) {
            \app\models\Utilities::putMessageLogFile('materiasMatriculadas: ' . $ex->getMessage());
            return FALSE;
        }
    }
}
