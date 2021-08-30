<?php

namespace app\modules\investigacion\models;

use Yii;
use app\models\Utilities;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use yii\base\Exception;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "linea_investigacion".
 *
 * @property int $linv_id
 * @property string linv_nombre_investigacion
 * @property string $linv_descripcion
 * @property int $linv_usuario_ingreso
 * @property int $linv_usuario_modifica
 * @property int $linv_estado
 * @property string $linv_fecha_creacion
 * @property string $linv_fecha_modificacion
 * @property int $linv_estado_logico
 *
 * @property Macroproyecto[] $macroproyectos
 * @property RegistroProyecto[] $registroProyectos
 */
class LineaInvestigacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'linea_investigacion';
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
            [['linv_descripcion', 'linv_nombre_investigacion', 'linv_usuario_ingreso', 'linv_estado', 'linv_estado_logico'], 'required'],
            [['linv_usuario_ingreso', 'linv_usuario_modifica', 'linv_estado', 'linv_estado_logico'], 'integer'],
            [['linv_fecha_creacion', 'linv_fecha_modificacion'], 'safe'],
            [['linv_descripcion'], 'string', 'max' => 350],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'linv_id' => 'Linv ID',
            'linv_descripcion' => 'Linv Descripcion',
            'linv_usuario_ingreso' => 'Linv Usuario Ingreso',
            'linv_usuario_modifica' => 'Linv Usuario Modifica',
            'linv_estado' => 'Linv Estado',
            'linv_fecha_creacion' => 'Linv Fecha Creacion',
            'linv_fecha_modificacion' => 'Linv Fecha Modificacion',
            'linv_estado_logico' => 'Linv Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMacroproyectos()
    {
        return $this->hasMany(Macroproyecto::className(), ['linv_id' => 'linv_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroProyectos()
    {
        return $this->hasMany(RegistroProyecto::className(), ['linv_id' => 'linv_id']);
    }

    public function consultarLineaInvestigacion() {
        $con = \Yii::$app->db_investigacion;
        $estado=1;
        $sql = "SELECT
                    linv_id as id, 
                    linv_nombre_investigacion as linea
                from " . $con->dbname . ".linea_investigacion 
                
                WHERE 
                linv_estado= :estado and
                linv_estado_logico= :estado";

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
                'attributes' => ["id","linea"],
            ],
        ]);
        
        return $dataProvider;
    }

    public function insertLineaInv($nombre_investigacion) {
        $con = \Yii::$app->db_investigacion;
        $transaction=$con->beginTransaction(); 
        $date = date(Yii::$app->params["dateTimeByDefault"]);
        // se obtiene la transacción actual
                
        try {
            \app\models\Utilities::putMessageLogFile('entro insert...: ');
            $sql = "INSERT INTO " . $con->dbname . ".linea_investigacion 
                (linv_nombre_investigacion, 
                linv_descripcion, 
                linv_usuario_ingreso, 
                linv_usuario_modifica, 
                linv_estado, 
                linv_fecha_creacion, 
                linv_fecha_modificacion, 
                linv_estado_logico
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

    public function updateNombreLineaInv($id, $nombre_investigacion) {
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
            $sql = "UPDATE " . $con->dbname . ".linea_investigacion 
                SET linv_nombre_investigacion = :nombre_investigacion,
                linv_descripcion = :nombre_investigacion,
                linv_fecha_modificacion=:fecha
                WHERE linv_id = :id
                AND linv_estado = :estado
                AND linv_estado_logico = :estado";
              
              
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
