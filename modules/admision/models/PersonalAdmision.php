<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "personal_admision".
 *
 * @property int $padm_id
 * @property int $per_id
 * @property int $grol_id
 * @property string $padm_codigo
 * @property string $padm_tipo_asignacion
 * @property string $padm_estado
 * @property string $padm_fecha_creacion
 * @property string $padm_fecha_modificacion
 * @property string $padm_estado_logico
 *
 * @property BitacoraActividades[] $bitacoraActividades
 * @property Oportunidad[] $oportunidads
 * @property PersonalNivelModalidad[] $personalNivelModalidads
 */
class PersonalAdmision extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'personal_admision';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_crm');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['per_id', 'grol_id', 'padm_tipo_asignacion', 'padm_estado', 'padm_estado_logico'], 'required'],
            [['per_id', 'grol_id'], 'integer'],
            [['padm_fecha_creacion', 'padm_fecha_modificacion'], 'safe'],
            [['padm_codigo'], 'string', 'max' => 10],
            [['padm_tipo_asignacion', 'padm_estado', 'padm_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'padm_id' => 'Padm ID',
            'per_id' => 'Per ID',
            'grol_id' => 'Grol ID',
            'padm_codigo' => 'Padm Codigo',
            'padm_tipo_asignacion' => 'Padm Tipo Asignacion',
            'padm_estado' => 'Padm Estado',
            'padm_fecha_creacion' => 'Padm Fecha Creacion',
            'padm_fecha_modificacion' => 'Padm Fecha Modificacion',
            'padm_estado_logico' => 'Padm Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBitacoraActividades() {
        return $this->hasMany(BitacoraActividades::className(), ['padm_id' => 'padm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOportunidads() {
        return $this->hasMany(Oportunidad::className(), ['padm_id' => 'padm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonalNivelModalidads() {
        return $this->hasMany(PersonalNivelModalidad::className(), ['padm_id' => 'padm_id']);
    }

    /**
     * Function consulta agente. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarAgentereasigna($padm_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT 
                padm_id as id, 
                padm_codigo as name                   
                FROM 
                   " . $con->dbname . ".personal_admision ";
        $sql .= "  WHERE  
                   padm_id <> :padm_id AND
                   padm_estado = :estado AND
                   padm_estado_logico = :estado
                ORDEr BY name";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":padm_id", $padm_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consulta agente. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function actualizarAgenteOport($opo_id, $padm_id, $opo_usuario_modif) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".oportunidad		       
                      SET padm_id = :padm_id,                           
                          opo_usuario_modif = :opo_usuario_modif
                      WHERE opo_id = :opo_id AND                        
                            opo_estado = :estado AND
                            opo_estado_logico = :estado");
            
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":opo_id", $opo_id, \PDO::PARAM_INT);
            $comando->bindParam(":padm_id", $padm_id, \PDO::PARAM_INT);
            $comando->bindParam(":opo_usuario_modif", $opo_usuario_modif, \PDO::PARAM_INT);           
            $comando->bindParam(":opo_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            
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
     * Function consulta agente para el filtro de contacto . 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarAgenteconta() {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT u.usu_id as id,
                -- per.per_id as id, 
                concat(per.per_pri_nombre, ' ', ifnull(per.per_pri_apellido,' '))  as name                   
                FROM 
                   " . $con->dbname . ".personal_admision pad
                INNER JOIN " . $con1->dbname . ".persona per on per.per_id = pad.per_id
                INNER JOIN " . $con1->dbname . ".usuario u on u.per_id = per.per_id";
        $sql .= "  WHERE                   
                   padm_estado = :estado AND
                   padm_estado_logico = :estado AND
                   per.per_estado = :estado AND
                   per.per_estado_logico = :estado AND
                   u.usu_estado = :estado AND
                   u.usu_estado_logico = :estado
                ORDEr BY name";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;
    }

}
