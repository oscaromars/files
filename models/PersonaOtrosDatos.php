<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "persona_otros_datos".
 *
 * @property int $poda_id
 * @property int $per_id
 * @property int $nins_id
 * @property int $bseg_id
 * @property string|null $poda_contacto_red_social
 * @property string $poda_estado
 * @property int $poda_usuario_creacion
 * @property string $poda_fecha_creacion
 * @property string|null $poda_fecha_modificacion
 * @property int|null $poda_usuario_modificacion
 * @property string $poda_estado_logico
 *
 * @property Persona $per
 */
class PersonaOtrosDatos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'persona_otros_datos';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_asgard');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['per_id', 'nins_id', 'bseg_id', 'poda_estado', 'poda_usuario_creacion', 'poda_estado_logico'], 'required'],
            [['per_id', 'nins_id', 'bseg_id', 'poda_usuario_creacion', 'poda_usuario_modificacion'], 'integer'],
            [['poda_fecha_creacion', 'poda_fecha_modificacion'], 'safe'],
            [['poda_contacto_red_social', 'poda_estado', 'poda_estado_logico'], 'string', 'max' => 1],
            [['per_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persona::className(), 'targetAttribute' => ['per_id' => 'per_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'poda_id' => 'Poda ID',
            'per_id' => 'Per ID',
            'nins_id' => 'Nins ID',
            'bseg_id' => 'Bseg ID',
            'poda_contacto_red_social' => 'Poda Contacto Red Social',
            'poda_estado' => 'Poda Estado',
            'poda_usuario_creacion' => 'Poda Usuario Creacion',
            'poda_fecha_creacion' => 'Poda Fecha Creacion',
            'poda_fecha_modificacion' => 'Poda Fecha Modificacion',
            'poda_usuario_modificacion' => 'Poda Usuario Modificacion',
            'poda_estado_logico' => 'Poda Estado Logico',
        ];
    }

    /**
     * Gets query for [[Per]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPer()
    {
        return $this->hasOne(Persona::className(), ['per_id' => 'per_id']);
    }

    /**
     * Function insertarPersonaOtrosDatos
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function insertar($data) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "INSERT INTO " . $con->dbname . ".persona_otros_datos
            (per_id, nins_id, bseg_id, poda_contacto_red_social, poda_estado, poda_usuario_creacion, poda_estado_logico) VALUES
            (:per_id,:nins_id,:bseg_id,:poda_contacto_red_social,:poda_estado, :poda_usuario_creacion, :poda_estado) ";

        $command = $con->createCommand($sql);
        $command->bindParam(":per_id", $data[0]['per_id'], \PDO::PARAM_INT);
        $command->bindParam(":nins_id", $data[0]['nins_id'], \PDO::PARAM_INT);
        $command->bindParam(":bseg_id", $data[0]['bseg_id'], \PDO::PARAM_INT);
        $command->bindParam(":poda_contacto_red_social", $data[0]['poda_contacto_red_social'], \PDO::PARAM_STR);
        $comando->bindParam(":poda_usuario_creacion", $data[0]['usuario'], \PDO::PARAM_INT);
        $command->bindParam(":poda_estado", $estado, \PDO::PARAM_STR); 
        
        $command->execute();
        return $con->getLastInsertID();
    }

    /**
     * Function modificar
     * @author  Grace Viteri
     * @property
     * @return
     */
    public function modificar($data) {
        $con = \Yii::$app->db_asgard;
        $fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;

        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".persona_otros_datos
                    SET
                    nins_id = :nins_id,
                    bseg_id = :bseg_id,
                    poda_contacto_red_social = :poda_contacto_red_social,
                    poda_fecha_modificacion = :poda_fecha_modificacion,
                    poda_usuario_modificacion = :poda_usuario_modificacion                   
                    WHERE
                    per_id = :per_id AND
                    poda_estado = :estado AND
                    poda_estado_logico = :estado");

        $comando->bindParam(":per_id", $data[0]['per_id'], \PDO::PARAM_INT);
        $comando->bindParam(":nins_id", $data[0]['nins_id'], \PDO::PARAM_INT);
        $comando->bindParam(":bseg_id", $data[0]['bseg_id'], \PDO::PARAM_INT);
        $comando->bindParam(":poda_contacto_red_social", $data[0]['poda_contacto_red_social'], \PDO::PARAM_STR);
        $comando->bindParam(":poda_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
        $comando->bindParam(":poda_usuario_modificacion", $data[0]['usuario'], \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $response = $comando->execute();
            
        return $response;        
    }
}
