<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estudiante_instruccion".
 *
 * @property int $eins_id
 * @property int $per_id
 * @property string $eins_titulo3ernivel
 * @property string $eins_institucion3ernivel
 * @property string $eins_añogrado3ernivel
 * @property string $eins_titulo4tonivel
 * @property string $eins_institucion4tonivel
 * @property string $eins_añogrado4tonivel
 * @property string $eins_estado
 * @property string $eins_fecha_creacion
 * @property string $eins_fecha_modificacion
 * @property string $eins_estado_logico
 */
class EstudianteInstruccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estudiante_instruccion';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_inscripcion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['per_id'], 'integer'],
            [['eins_estado', 'eins_estado_logico'], 'required'],
            [['eins_fecha_creacion', 'eins_fecha_modificacion'], 'safe'],
            [['eins_titulo3ernivel', 'eins_titulo4tonivel'], 'string', 'max' => 200],
            [['eins_institucion3ernivel', 'eins_institucion4tonivel'], 'string', 'max' => 150],
            [['eins_añogrado3ernivel', 'eins_añogrado4tonivel'], 'string', 'max' => 50],
            [['eins_estado', 'eins_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'eins_id' => 'Eins ID',
            'per_id' => 'Per ID',
            'eins_titulo3ernivel' => 'Eins Titulo3ernivel',
            'eins_institucion3ernivel' => 'Eins Institucion3ernivel',
            'eins_añogrado3ernivel' => 'Eins Añogrado3ernivel',
            'eins_titulo4tonivel' => 'Eins Titulo4tonivel',
            'eins_institucion4tonivel' => 'Eins Institucion4tonivel',
            'eins_añogrado4tonivel' => 'Eins Añogrado4tonivel',
            'eins_estado' => 'Eins Estado',
            'eins_fecha_creacion' => 'Eins Fecha Creacion',
            'eins_fecha_modificacion' => 'Eins Fecha Modificacion',
            'eins_estado_logico' => 'Eins Estado Logico',
        ];
    }

    /**
     * Function consultaEstudianteinstruccion
     * @author  Lisbeth Gonzalez <analista.desarrollo@uteg.edu.ec>
     * @property integer $userid       
     * @return  
     */
   

    public function consultarEstInstruccion($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;

        $sql = "
                SELECT   
                         count(*) as existe_instruccion
                FROM " . $con->dbname . ".estudiante_instruccion 
                WHERE per_id = :per_id AND 
                     eins_estado = :estado AND
                     eins_estado_logico = :estado";
                     
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function insertarEstudianteInstruccion($per_id, $titulo_ter, $universidad_tercer, $grado_tercer, $titulo_cuarto, $universidad_cuarto, $grado_cuarto) {
        $con = \Yii::$app->db_inscripcion;

        $sql = "INSERT INTO " . $con->dbname . ".estudiante_instruccion
            (per_id, eins_titulo3ernivel, eins_institucion3ernivel, eins_añogrado3ernivel, eins_titulo4tonivel, eins_institucion4tonivel, eins_añogrado4tonivel, eins_estado, eins_fecha_modificacion, eins_estado_logico) VALUES
            ($per_id, '$titulo_ter', '$universidad_tercer', '$grado_tercer', '$titulo_cuarto', '$universidad_cuarto', '$grado_cuarto', 1, CURRENT_TIMESTAMP(), 1)";

        
        $command = $con->createCommand($sql);
        $command->execute();
        return $con->getLastInsertID($con->dbname . '.estudiante_instruccion');
        
    }

    public function modificarEstudianteinstruccion($per_id, $titulo_ter, $universidad_tercer, $grado_tercer, $titulo_cuarto, $universidad_cuarto, $grado_cuarto) {
        $con = \Yii::$app->db_inscripcion;
        $eins_fecha_modificacion = date("Y-m-d H:i:s");
        $estado='1';
        
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".estudiante_instruccion             
                      SET 
                        eins_id =:eins_id,    
                        eins_titulo3ernivel =:eins_titulo3ernivel,
                        eins_institucion3ernivel =:eins_institucion3ernivel,
                        eins_añogrado3ernivel =:eins_añogrado3ernivel,
                        eins_titulo4tonivel =:eins_titulo4tonivel,
                        eins_institucion4tonivel =:eins_institucion4tonivel,
                        eins_añogrado4tonivel =:eins_añogrado4tonivel,                        
                        eins_fecha_modificacion =:eins_fecha_modificacion
                      WHERE 
                        per_id =:per_id AND
                        eins_estado =:estado AND
                        eins_estado_logico =:estado");
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":eins_titulo3ernivel", $titulo_ter, \PDO::PARAM_STR);
            $comando->bindParam(":eins_institucion3ernivel", $universidad_tercer, \PDO::PARAM_STR);
            $comando->bindParam(":eins_añogrado3ernivel", $grado_tercer, \PDO::PARAM_STR);
            $comando->bindParam(":eins_titulo4tonivel", $titulo_cuarto, \PDO::PARAM_STR);
            $comando->bindParam(":eins_institucion4tonivel", $universidad_cuarto, \PDO::PARAM_STR);
            $comando->bindParam(":eins_añogrado4tonivel", $grado_cuarto, \PDO::PARAM_STR);
            $comando->bindParam(":eins_fecha_modificacion", $eins_fecha_modificacion, \PDO::PARAM_STR);
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
