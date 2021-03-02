<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contacto_general".
 *
 * @property integer $cgen_id
 * @property integer $tcge_id
 * @property integer $ext_id
 * @property string $ext_base
 * @property string $cgen_nombre
 * @property string $cgen_apellido
 * @property integer $tpar_id
 * @property string $cgen_direccion
 * @property string $cgen_telefono
 * @property string $cgen_celular
 * @property string $cgen_estado
 * @property string $cgen_fecha_creacion
 * @property string $cgen_fecha_modificacion
 * @property string $cgen_estado_logico
 *
 * @property TipoContactoGeneral $tcge
 */
class ContactoGeneral extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'contacto_general';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_general');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['tcge_id', 'ext_id', 'tpar_id'], 'integer'],
                [['ext_id', 'cgen_estado', 'cgen_estado_logico'], 'required'],
                [['cgen_fecha_creacion', 'cgen_fecha_modificacion'], 'safe'],
                [['ext_base', 'cgen_nombre', 'cgen_apellido', 'cgen_telefono', 'cgen_celular'], 'string', 'max' => 50],
                [['cgen_direccion'], 'string', 'max' => 500],
                [['cgen_estado', 'cgen_estado_logico'], 'string', 'max' => 1],
                [['tcge_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoContactoGeneral::className(), 'targetAttribute' => ['tcge_id' => 'tcge_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'cgen_id' => 'Cgen ID',
            'tcge_id' => 'Tcge ID',
            'ext_id' => 'Ext ID',
            'ext_base' => 'Ext Base',
            'cgen_nombre' => 'Cgen Nombre',
            'cgen_apellido' => 'Cgen Apellido',
            'tpar_id' => 'Tpar ID',
            'cgen_direccion' => 'Cgen Direccion',
            'cgen_telefono' => 'Cgen Telefono',
            'cgen_celular' => 'Cgen Celular',
            'cgen_estado' => 'Cgen Estado',
            'cgen_fecha_creacion' => 'Cgen Fecha Creacion',
            'cgen_fecha_modificacion' => 'Cgen Fecha Modificacion',
            'cgen_estado_logico' => 'Cgen Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTcge() {
        return $this->hasOne(TipoContactoGeneral::className(), ['tcge_id' => 'tcge_id']);
    }

    /**
     * Function consultaContactoGeneral
     * @author  Omar Romero <analistadesarrollo03@uteg.edu.ec>
     * @property integer $per_id       
     * @return  
     */
    public function consultaContactoGeneral($per_id) {

        $con = \Yii::$app->db_general;
        $estado = '1';
        $sql = "SELECT 
                    cgen_id as contacto_id,
                    tcge_id as tipocontacto_id,                    
                    cgen_nombre as nombre,
                    cgen_apellido as apellido,
                    tpar_id as parentesco,
                    cgen_direccion as direccion,
                    cgen_telefono as telefono,
                    cgen_celular as celular                      
                FROM 
                    " . $con->dbname . ". contacto_general        
                WHERE 
                    per_id = :per_id AND
                    cgen_estado = :estado AND 
                    cgen_estado_logico = :estado
                LIMIT 1";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryOne();

        return $resultData;
    }

    /**
     * Function consultaContactoGeneral
     * @author  Omar Romero <analistadesarrollo03@uteg.edu.ec>
     * @property integer $per_id       
     * @return  
     */
    public function crearContactoGeneral($per_id, $cgen_nombre, $cgen_apellido, $tpar_id, $cgen_direccion, $cgen_telefono, $cgen_celular) {
        $con = \Yii::$app->db_general;
        $tcge_id = 1; // Telefono de Contacto de Emergencia 

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "cgen_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", cgen_estado";
        $bsol_sql .= ", 1";

        $param_sql .= ", tcge_id";
        $bsol_sql .= ", :tcge_id";

        if (isset($per_id)) {
            $param_sql .= ", per_id";
            $bsol_sql .= ", :per_id";
        }
        if (isset($tpar_id)) {
            $param_sql .= ", tpar_id";
            $bsol_sql .= ", :tpar_id";
        }
        if (isset($cgen_nombre)) {
            $param_sql .= ", cgen_nombre";
            $bsol_sql .= ", :cgen_nombre";
        }
        if (isset($cgen_apellido)) {
            $param_sql .= ", cgen_apellido";
            $bsol_sql .= ", :cgen_apellido";
        }
        if (isset($cgen_direccion)) {
            $param_sql .= ", cgen_direccion";
            $bsol_sql .= ", :cgen_direccion";
        }
        if (isset($cgen_telefono)) {
            $param_sql .= ", cgen_telefono";
            $bsol_sql .= ", :cgen_telefono";
        }
        if (isset($cgen_celular)) {
            $param_sql .= ", cgen_celular";
            $bsol_sql .= ", :cgen_celular";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".contacto_general ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($tcge_id))
                $comando->bindParam(':tcge_id', $tcge_id, \PDO::PARAM_INT);

            if (isset($per_id))
                $comando->bindParam(':per_id', $per_id, \PDO::PARAM_INT);

            if (isset($tpar_id))
                $comando->bindParam(':tpar_id', $tpar_id, \PDO::PARAM_INT);

            if (isset($cgen_nombre))
                $comando->bindParam(':cgen_nombre', $cgen_nombre, \PDO::PARAM_STR);

            if (isset($cgen_apellido))
                $comando->bindParam(':cgen_apellido', $cgen_apellido, \PDO::PARAM_STR);

            if (isset($cgen_direccion))
                $comando->bindParam(':cgen_direccion', $cgen_direccion, \PDO::PARAM_STR);

            if (isset($cgen_telefono))
                $comando->bindParam(':cgen_telefono', $cgen_telefono, \PDO::PARAM_STR);

            if (isset($cgen_celular))
                $comando->bindParam(':cgen_celular', $cgen_celular, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.contacto_general');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    public function modificaContactoGeneral($cgen_id, $per_id, $cgen_nombre, $cgen_apellido, $tpar_id, $cgen_direccion, $cgen_telefono, $cgen_celular) {
        $con = \Yii::$app->db_general;
        $estado = 1;
        $cgen_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".contacto_general		       
                      SET 
                        cgen_nombre  = :cgen_nombre,
                        cgen_apellido  = :cgen_apellido,
                        tpar_id  = :tpar_id,
                        cgen_direccion  = :cgen_direccion,  
                        cgen_telefono  = :cgen_telefono,
                        cgen_celular  = :cgen_celular,                          
                        cgen_fecha_modificacion = :cgen_fecha_modificacion
                      WHERE 
                        per_id = :per_id AND
                        cgen_id = :cgen_id AND
                        cgen_estado = :estado AND
                        cgen_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":cgen_fecha_modificacion", $cgen_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":cgen_id", $cgen_id, \PDO::PARAM_INT);
            $comando->bindParam(":cgen_nombre", $cgen_nombre, \PDO::PARAM_STR);
            $comando->bindParam(":cgen_apellido", $cgen_apellido, \PDO::PARAM_STR);
            $comando->bindParam(":tpar_id", $tpar_id, \PDO::PARAM_INT);
            $comando->bindParam(":cgen_direccion", $cgen_direccion, \PDO::PARAM_STR);
            $comando->bindParam(":cgen_telefono", $cgen_telefono, \PDO::PARAM_STR);
            $comando->bindParam(":cgen_celular", $cgen_celular, \PDO::PARAM_STR);

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
     * Function to get array 
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   string  $username    
     * @return  mixed   $res        New array 
     */
    public static function getAgenteInscrito() {
        $con = \Yii::$app->db_general;
        $estado = 1;
        
        $sql = "SELECT aima_id as id,
                       aima_nombre as value 
                From " . $con->dbname . ".agente_inscrito_maestria 
                WHERE aima_estado_logico = :estado AND 
                aima_estado = :estado 
                ORDER BY id asc";
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        return $comando->queryAll();
    }

}
