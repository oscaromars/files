<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "persona_contacto".
 *
 * @property integer $pcon_id
 * @property integer $per_id
 * @property string $pcon_nombre
 * @property integer $tpar_id
 * @property string $pcon_telefono
 * @property string $pcon_celular
 * @property string $pcon_estado
 * @property string $pcon_fecha_creacion
 * @property string $pcon_fecha_modificacion
 * @property string $pcon_estado_logico
 *
 * @property Persona $per
 * @property TipoParentesco $tpar
 */
class PersonaContacto extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'persona_contacto';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['per_id', 'tpar_id'], 'integer'],
            [['pcon_estado', 'pcon_estado_logico'], 'required'],
            [['pcon_fecha_creacion', 'pcon_fecha_modificacion'], 'safe'],
            [['pcon_nombre'], 'string', 'max' => 250],
            [['pcon_telefono', 'pcon_celular'], 'string', 'max' => 50],
            [['pcon_estado', 'pcon_estado_logico'], 'string', 'max' => 1],
            [['per_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persona::className(), 'targetAttribute' => ['per_id' => 'per_id']],
            [['tpar_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoParentesco::className(), 'targetAttribute' => ['tpar_id' => 'tpar_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'pcon_id' => 'Pcon ID',
            'per_id' => 'Per ID',
            'pcon_nombre' => 'Pcon Nombre',
            'tpar_id' => 'Tpar ID',
            'pcon_telefono' => 'Pcon Telefono',
            'pcon_celular' => 'Pcon Celular',
            'pcon_estado' => 'Pcon Estado',
            'pcon_fecha_creacion' => 'Pcon Fecha Creacion',
            'pcon_fecha_modificacion' => 'Pcon Fecha Modificacion',
            'pcon_estado_logico' => 'Pcon Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPer() {
        return $this->hasOne(Persona::className(), ['per_id' => 'per_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTpar() {
        return $this->hasOne(TipoParentesco::className(), ['tpar_id' => 'tpar_id']);
    }

    /**
     * Function consultaPersonaContacto
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $userid       
     * @return  
     */
    public function consultaPersonaContacto($per_id) {
        $con = \Yii::$app->db_asgard;
        $estado = '1';
        $sql = "SELECT 
                    pcon_id as contacto_id,
                    per_id as persona_id,
                    pcon_nombre as nombre,
                    tpar_id as parentesco,
                    pcon_telefono as telefono,
                    pcon_direccion as direccion,
                    pcon_celular as celular 
                    
                FROM 
                    " . $con->dbname . ". persona_contacto        
                WHERE 
                    per_id = :perid AND
                    pcon_estado = :estado AND 
                    pcon_estado_logico = :estado
                LIMIT 1";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":perid", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function crearPersonaContacto
     * @author  Diana López
     * @property      
     * @return  
     */
    public function crearPersonaContacto($per_id, $tpar_id, $pcon_nombre, $pcon_telefono, $pcon_celular, $pcon_direccion) {
        $con = \Yii::$app->db_asgard;
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "pcon_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", pcon_estado";
        $bsol_sql .= ", 1";

        if (isset($per_id)) {
            $param_sql .= ", per_id";
            $bsol_sql .= ", :per_id";
        }

        if (isset($tpar_id)) {
            $param_sql .= ", tpar_id";
            $bsol_sql .= ", :tpar_id";
        }

        if (isset($pcon_nombre)) {
            $param_sql .= ", pcon_nombre";
            $bsol_sql .= ", :pcon_nombre";
        }

        if (isset($pcon_telefono)) {
            $param_sql .= ", pcon_telefono";
            $bsol_sql .= ", :pcon_telefono";
        }
        if (isset($pcon_celular)) {
            $param_sql .= ", pcon_celular";
            $bsol_sql .= ", :pcon_celular";
        }

        if (isset($pcon_direccion)) {
            $param_sql .= ", pcon_direccion";
            $bsol_sql .= ", :pcon_direccion";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".persona_contacto ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($per_id))
                $comando->bindParam(':per_id', $per_id, \PDO::PARAM_INT);

            if (isset($tpar_id))
                $comando->bindParam(':tpar_id', $tpar_id, \PDO::PARAM_INT);

            if (isset($pcon_nombre))
                $comando->bindParam(':pcon_nombre', $pcon_nombre, \PDO::PARAM_STR);

            if (isset($pcon_telefono))
                $comando->bindParam(':pcon_telefono', $pcon_telefono, \PDO::PARAM_STR);

            if (isset($pcon_celular))
                $comando->bindParam(':pcon_celular', $pcon_celular, \PDO::PARAM_STR);

            if (isset($pcon_direccion))
                $comando->bindParam(':pcon_direccion', $pcon_direccion, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.persona_contacto');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function modificarPersonacontacto
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function modificarPersonacontacto($per_id, $tpar_id, $pcon_nombre, $pcon_telefono, $pcon_celular, $pcon_direccion) {
        $con = \Yii::$app->db_asgard;
        $pcon_fecha_modificacion = date("Y-m-d H:i:s");
        $estado='1';
        
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".persona_contacto 		       
                      SET 
                        tpar_id = :tpar_id,    
                        pcon_nombre = :pcon_nombre,
                        pcon_telefono = :pcon_telefono,
                        pcon_celular = :pcon_celular,
                        pcon_direccion = :pcon_direccion,                        
                        pcon_fecha_modificacion = :pcon_fecha_modificacion
                      WHERE 
                        per_id = :per_id AND
                        pcon_estado = :estado AND
                        pcon_estado_logico = :estado");
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":tpar_id", $tpar_id, \PDO::PARAM_INT);
            $comando->bindParam(":pcon_nombre", $pcon_nombre, \PDO::PARAM_STR);
            $comando->bindParam(":pcon_telefono", $pcon_telefono, \PDO::PARAM_STR);
            $comando->bindParam(":pcon_celular", $pcon_celular, \PDO::PARAM_STR);
            $comando->bindParam(":pcon_direccion", $pcon_direccion, \PDO::PARAM_STR);
            $comando->bindParam(":pcon_fecha_modificacion", $pcon_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
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
