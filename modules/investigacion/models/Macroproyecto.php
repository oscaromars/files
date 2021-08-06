<?php

namespace app\modules\investigacion\models;

use Yii;
use app\models\Utilities;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use yii\base\Exception;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "macroproyecto".
 *
 * @property int $mpro_id
 * @property int $linv_id
 * @property string $mpro_descripcion
 * @property int $mpro_usuario_ingreso
 * @property int $mpro_usuario_modifica
 * @property int $mpro_estado
 * @property string $mpro_fecha_creacion
 * @property string $mpro_fecha_modificacion
 * @property int $mpro_estado_logico
 *
 * @property LineaInvestigacion $linv
 */
class Macroproyecto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'macroproyecto';
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
            [['linv_id', 'mpro_descripcion', 'mpro_usuario_ingreso', 'mpro_estado', 'mpro_estado_logico'], 'required'],
            [['linv_id', 'mpro_usuario_ingreso', 'mpro_usuario_modifica', 'mpro_estado', 'mpro_estado_logico'], 'integer'],
            [['mpro_fecha_creacion', 'mpro_fecha_modificacion'], 'safe'],
            [['mpro_descripcion'], 'string', 'max' => 350],
            [['linv_id'], 'exist', 'skipOnError' => true, 'targetClass' => LineaInvestigacion::className(), 'targetAttribute' => ['linv_id' => 'linv_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mpro_id' => 'Mpro ID',
            'linv_id' => 'Linv ID',
            'mpro_descripcion' => 'Mpro Descripcion',
            'mpro_usuario_ingreso' => 'Mpro Usuario Ingreso',
            'mpro_usuario_modifica' => 'Mpro Usuario Modifica',
            'mpro_estado' => 'Mpro Estado',
            'mpro_fecha_creacion' => 'Mpro Fecha Creacion',
            'mpro_fecha_modificacion' => 'Mpro Fecha Modificacion',
            'mpro_estado_logico' => 'Mpro Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinv()
    {
        return $this->hasOne(LineaInvestigacion::className(), ['linv_id' => 'linv_id']);
    }

    public function consultarMacroproyecto($investigacion = NULL, $onlyData = false) {
        $con = \Yii::$app->db_investigacion;
        $estado=1;
        if (isset($investigacion) && $investigacion > 0) {
                $str_investigacion = "l.linv_id = :investigacion AND";
        }
        $sql = "SELECT
                    m.mpro_id as id, 
                    l.linv_nombre_investigacion as linea,
                    m.mpro_descripcion as macro
                from " . $con->dbname . ".macroproyecto m
                inner join " . $con->dbname . ".linea_investigacion l on l.linv_id = m.linv_id
                
                WHERE 
                $str_investigacion
                m.mpro_estado = :estado and
                l.linv_estado = :estado and
                m.mpro_estado_logico = :estado and
                l.linv_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($investigacion) && $investigacion != "") {
            $comando->bindParam(":investigacion", $investigacion, \PDO::PARAM_INT);
        }
        
        $resultData = $comando->queryAll();
          
        if ($onlyData){ return $resultData; }                    
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["id","linea","macro"],
            ],
        ]);
        
        return $dataProvider;
    }

    public function insertMacro($linv_id,$descripcion) {
        $con = \Yii::$app->db_investigacion;
        $transaction=$con->beginTransaction(); 
        $date = date(Yii::$app->params["dateTimeByDefault"]);
        // se obtiene la transacción actual
                
        try {
            \app\models\Utilities::putMessageLogFile('entro insert...: ');
            $sql = "INSERT INTO " . $con->dbname . ".macroproyecto 
                (linv_id, 
                mpro_descripcion, 
                mpro_usuario_ingreso, 
                mpro_usuario_modifica, 
                mpro_estado, 
                mpro_fecha_creacion, 
                mpro_fecha_modificacion, 
                mpro_estado_logico
                ) VALUES(
                    '$linv_id', 
                    '$descripcion',
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
    public function updateNombreMacroproyecto($id, $nombre_investigacion) {
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
            $sql = "UPDATE " . $con->dbname . ".macroproyecto 
                SET mpro_descripcion = :nombre_investigacion,
                mpro_fecha_modificacion=:fecha
                WHERE mpro_id = :id
                AND mpro_estado = :estado
                AND mpro_estado_logico = :estado";
              
              
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
