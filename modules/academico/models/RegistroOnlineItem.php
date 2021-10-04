<?php

namespace app\modules\academico\models;

use app\modules\academico\models\RegistroOnline;
use Yii;

/**
 * This is the model class for table "registro_online_item".
 *
 * @property integer $roi_id
 * @property integer $ron_id
 * @property string $roi_materia_cod
 * @property string $roi_materia_nombre
 * @property string $roi_creditos
 * @property string $roi_costo
 * @property string $roi_bloque
 * @property string $roi_hora
 * @property string $roi_paralelo
 * @property string $roi_estado
 * @property string $roi_fecha_creacion
 * @property string $roi_usuario_ingreso
 * @property string $roi_usuario_modifica
 * @property string $roi_fecha_modifcacion
 * @property string $roi_estado_logico
 *
 */

class RegistroOnlineItem extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'registro_online_item';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_academico');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ron_id', 'roi_estado', 'roi_estado_logico'], 'required'],
            [['roi_fecha_creacion', 'roi_fecha_modificacion'], 'safe'],
            [['roi_estado_logico', 'roi_estado'], 'string', 'max' => 1],
            [['ron_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroOnline::className(), 'targetAttribute' => ['ron_id' => 'ron_id']],
            /* [['pes_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionEstudiante::className(), 'targetAttribute' => ['pes_id' => 'pes_id']], */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'roi_id' => 'Roi ID',
            'ron_id' => 'Ron ID',
            'roi_materia_cod' => 'Roi Materia Codigo',
            'roi_materia_nombre' => 'Roi Materia Nombre',
            'roi_creditos' => 'Roi Creditos',
            'roi_costo' => 'Roi Costo',
            'roi_bloque' => 'Roi Bloque',
            'roi_hora' => 'Roi Hora',
            'roi_paralelo' => 'Roi Paralelo',
            'roi_estado' => 'Roi Estado',
            'roi_fecha_creacion' => 'Roi Fecha Creacion',
            'roi_usuario_ingreso' => 'Roi Usuario Ingreso',
            'roi_usuario_modifica' => 'Roi Usuario Modifica',
            'roi_fecha_modifcacion' => 'Roi Fecha Modificacion',
            'roi_estado_logico' => 'Roi Estado Logico',
        ];
    }

    public static function getTotalCreditsByRegister($per_id, $rama_id) {
        $con_academico = \Yii::$app->db_academico;

        $sql = "SELECT
                    sum(r.roi_creditos) as Credito,
                    sum(r.roi_costo) as Costo
                FROM
                    " . $con_academico->dbname . ".registro_online_item AS r
                    inner join " . $con_academico->dbname . ".registro_online ro on ro.ron_id = r.ron_id
                    INNER JOIN " . $con_academico->dbname . ".registro_adicional_materias AS rm ON rm.ron_id = r.ron_id
                    AND (
                        rm.roi_id_1 = r.roi_id OR
                        rm.roi_id_2 = r.roi_id OR
                        rm.roi_id_3 = r.roi_id OR
                        rm.roi_id_4 = r.roi_id OR
                        rm.roi_id_5 = r.roi_id OR
                        rm.roi_id_6 = r.roi_id
                    )
                WHERE
                    r.roi_estado = 1 and r.roi_estado_logico = 1 and
                    rm.rama_estado = 1 and rm.rama_estado_logico = 1 and
                    ro.per_id = :per_id and
                    r.ron_id = ro.ron_id  ";
        //rm.rama_id = :rama_id ";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $ron_id, \PDO::PARAM_INT);
        //$comando->bindParam(":rama_id",$rama_id, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        return $res;
    }

    public function getCostobyRon($ron_id, $cod) {
        $con = \Yii::$app->db_academico;

        $sql = "
                 select b.ron_id,a.roi_id,a.roi_costo ,a.roi_materia_cod from db_academico_mbtu.registro_online_item a
                inner join db_academico_mbtu.registro_online b
                where b.ron_id =209
                and a.ron_id =209
                and a.roi_estado=1
                and a.roi_estado_logico=1
                and b.ron_estado=1
                and b.ron_estado_logico=1
                ";

        $sql = "
                 select roi_materia_cod, roi_costo,roi_id
                 from db_academico_mbtu.registro_online_item
                 where
                 roi_materia_cod= :cod AND
                 ron_id =:ron_id AND
                 roi_estado = 1 AND
                 roi_estado_logico = 1";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":cod", $cod, \PDO::PARAM_STR);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();

        return $resultData;

    }

    /**
     *  @Author Didimo  (analistadesarrollo03)
     *  @dscripcion:   Obtiene sii existe una materia que tenga pago  con credito directo
     * @since 06-05-2021
     */
    public static function verificarPagoMateriaByRegistroOnLine($ron_id, $cod) {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                 COUNT(b.ron_id) as exist_rpm
               from
                    db_academico_mbtu.registro_online_item a
                INNER JOIN db_academico_mbtu.registro_online b on  b.ron_id = a.ron_id
                INNER JOIN db_academico_mbtu.registro_pago_matricula rpm ON rpm.ron_id = b.ron_id
                INNER JOIN db_academico_mbtu.registro_adicional_materias  rama ON rama.ron_id = b.ron_id

                where b.ron_id = :ron_id
                AND rpm.rpm_tipo_pago = 3
                and a.roi_estado=1
                and a.roi_estado_logico=1
                and b.ron_estado=1
                and b.ron_estado_logico=1
                AND rpm.rpm_estado = 1
                AND rpm.rpm_estado_logico = 1
                AND a.roi_materia_cod = :cod
                AND rama.rpm_id = rpm.rpm_id
                AND rama.rama_estado = 1
                AND rama.rama_estado_logico = 1
                AND (rama.roi_id_1 = a.roi_id OR
                     rama.roi_id_2 = a.roi_id OR
                     rama.roi_id_3 = a.roi_id OR
                     rama.roi_id_4 = a.roi_id or
                     rama.roi_id_5 = a.roi_id or
                     rama.roi_id_6 = a.roi_id)";
        $comando = $con_academico->createCommand($sql);
        \app\models\Utilities::putMessageLogFile('mensaje: ' . $comando->getRawSql());
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":cod", $cod, \PDO::PARAM_STR);
        $res = $comando->queryOne();
        return $res;
    }
    /**
     * Funcion para insertar las data a la tabla registro online item
     * @author Luis Cajamarca <analistadesarrollo04>
     * @param 
     * @return 
     */
    public function insertRegistroOnlineItem(
        $ron_id,
        $roi_materia_cod,
        $roi_materia_nombre,
        $roi_creditos,
        $roi_costo,
        $roi_bloque,
        $roi_hora,
        $roi_paralelo,
        $usuario

    ) {

        $con = Yii::$app->db_academico;

        $date = date(Yii::$app->params['dateTimeByDefault']);
        $anio = strval(date("Y"));

        $sql = "INSERT INTO " . $con->dbname . ".registro_online_item
                (ron_id,
                roi_materia_cod,
                roi_materia_nombre,
                roi_creditos,
                roi_costo,
                roi_bloque,
                roi_hora,
                roi_paralelo,
                roi_estado,
                roi_fecha_creacion,
                roi_usuario_ingreso,
                roi_usuario_modifica,
                roi_fecha_modificacion,
                roi_estado_logico
                )
                VALUES (
                    $ron_id,
                    '$roi_materia_cod',
                    '$roi_materia_nombre',
                    '$roi_creditos',
                    $roi_costo,
                    '$roi_bloque',
                    '$roi_hora',
                    '$roi_paralelo',
                    1,
                    '$date',
                    $usuario,
                    Null,
                    Null,
                    1
                )";

        $command = $con->createCommand($sql);
        \app\models\Utilities::putMessageLogFile($command->getRawSql());
        $command->execute();

        return $con->getLastInsertID($con->dbname . '.registro_online_item');
    }

    public function getIdPlanificacionEstudiante($ron_id) {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "
            SELECT pes_id, pla_id
            FROM " . $con_academico->dbname . ".planificacion_estudiante as pes
            WHERE pes.per_id=:per_id
            -- AND pes.pla_id=:pla_id
            AND pes.pes_estado=:estado
            AND pes.pes_estado_logico=:estado
            ORDER BY pla_id desc;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        \app\models\Utilities::putMessageLogFile('getIdPlanificacionEstudiante: ' . $comando->getRawSql());
        return $resultData;
    }
}