<?php

namespace app\modules\investigacion\models;

use Yii;
use app\models\Utilities;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use yii\base\Exception;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "proyectos".
 *
 * @property int $proy_id
 * @property string $proy_nombre
 * @property string $proy_descripcion
 * @property int $proy_usuario_ingreso
 * @property int $proy_usuario_modifica
 * @property int $proy_estado
 * @property string $proy_fecha_creacion
 * @property string $proy_fecha_modificacion
 * @property int $proy_estado_logico
 *
 * @property RegistroProyecto[] $registroProyectos
 */
class Proyectos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proyectos';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_investigacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['proy_nombre', 'proy_descripcion', 'proy_usuario_ingreso', 'proy_estado', 'proy_estado_logico'], 'required'],
            [['proy_usuario_ingreso', 'proy_usuario_modifica', 'proy_estado', 'proy_estado_logico'], 'integer'],
            [['proy_fecha_creacion', 'proy_fecha_modificacion'], 'safe'],
            [['proy_nombre'], 'string', 'max' => 50],
            [['proy_descripcion'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'proy_id' => 'Proy ID',
            'proy_nombre' => 'Proy Nombre',
            'proy_descripcion' => 'Proy Descripcion',
            'proy_usuario_ingreso' => 'Proy Usuario Ingreso',
            'proy_usuario_modifica' => 'Proy Usuario Modifica',
            'proy_estado' => 'Proy Estado',
            'proy_fecha_creacion' => 'Proy Fecha Creacion',
            'proy_fecha_modificacion' => 'Proy Fecha Modificacion',
            'proy_estado_logico' => 'Proy Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroProyectos()
    {
        return $this->hasMany(RegistroProyecto::className(), ['proy_id' => 'proy_id']);
    }

    public function consultarProyectos() {
        $con = \Yii::$app->db_investigacion;
        $estado=1;
        $sql = "SELECT
                    proy_id as id, 
                    proy_nombre as proyecto
                from " . $con->dbname . ".proyectos 
                
                WHERE 
                proy_estado= :estado and
                proy_estado_logico= :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
                      
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["id","proyecto"],
            ],
        ]);
        
        return $dataProvider;
    }

    public function insertProyecto($nombre_investigacion) {
        $con = \Yii::$app->db_investigacion;
        $transaction=$con->beginTransaction(); 
        $date = date(Yii::$app->params["dateTimeByDefault"]);
        // se obtiene la transacción actual
                
        try {
            \app\models\Utilities::putMessageLogFile('entro insert...: ');
            $sql = "INSERT INTO " . $con->dbname . ".proyectos 
                (proy_nombre, 
                proy_descripcion, 
                proy_usuario_ingreso, 
                proy_usuario_modifica, 
                proy_estado, 
                proy_fecha_creacion, 
                proy_fecha_modificacion, 
                proy_estado_logico
                ) VALUES(
                    '$nombre_investigacion', 
                    '$nombre_investigacion',
                    1,
                    Null,
                    1,
                    '$date',
                    Null,
                    1
                )";
            $comando = $con->createCommand($sql);
            $comando->execute();
            \app\models\Utilities::putMessageLogFile('insertRegConf: ' . $comando->getRawSql());

            if ($transaction !== null)
                $transaction->commit();
            return TRUE;
        } catch (Exception $ex) {
            if ($transaction !== null)
                $transaction->rollback();
            return FALSE;
        }
    } 

    public function updateNombreProy($id, $nombre_investigacion) {
        $con = \Yii::$app->db_investigacion;
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            \app\models\Utilities::putMessageLogFile('entro sdesdf...: ');
            $sql = "UPDATE " . $con->dbname . ".proyectos 
                SET proy_nombre = :nombre_investigacion,
                proy_descripcion = :nombre_investigacion,
                proy_fecha_modificacion=:fecha
                WHERE proy_id = :id
                AND proy_estado = :estado
                AND proy_estado_logico = :estado";
              
              
            // Hacer al query un comando
            $comando = $con->createCommand($sql);            
            if (isset($id)) {
                $comando->bindParam(':id', $id, \PDO::PARAM_INT);
            }
            if (isset($nombre_investigacion)) {
                $comando->bindParam(':nombre_investigacion', $nombre_investigacion, \PDO::PARAM_INT);
            }
            if (!empty((isset($fecha)))) {
                $comando->bindParam(':fecha', $fecha, \PDO::PARAM_STR);
            }
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return TRUE;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }  

}
