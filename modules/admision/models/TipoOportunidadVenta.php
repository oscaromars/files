<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "tipo_oportunidad_venta".
 *
 * @property integer $tove_id
 * @property integer $uaca_id
 * @property string $tove_nombre
 * @property string $tove_descripcion
 * @property string $tove_estado
 * @property string $tove_fecha_creacion
 * @property string $tove_fecha_modificacion
 * @property string $tove_estado_logico
 *
 * @property GestionCrm[] $gestionCrms
 */
class TipoOportunidadVenta extends \app\modules\admision\components\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_oportunidad_venta';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_crm');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uaca_id', 'tove_nombre', 'tove_descripcion', 'tove_estado', 'tove_estado_logico'], 'required'],
            [['uaca_id'], 'integer'],
            [['tove_fecha_creacion', 'tove_fecha_modificacion'], 'safe'],
            [['tove_nombre'], 'string', 'max' => 300],
            [['tove_descripcion'], 'string', 'max' => 500],
            [['tove_estado', 'tove_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tove_id' => 'Tove ID',
            'uaca_id' => 'Uaca ID',
            'tove_nombre' => 'Tove Nombre',
            'tove_descripcion' => 'Tove Descripcion',
            'tove_estado' => 'Tove Estado',
            'tove_fecha_creacion' => 'Tove Fecha Creacion',
            'tove_fecha_modificacion' => 'Tove Fecha Modificacion',
            'tove_estado_logico' => 'Tove Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGestionCrms()
    {
        return $this->hasMany(GestionCrm::className(), ['tove_id' => 'tove_id']);
    }
    
     /**
     * Function consulta los tipos de oportunidad por unidad académica.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarOporxUnidad($uaca_id) {
        $con = \Yii::$app->db_crm;        
        $estado = 1;
        
        $sql = "SELECT 
                   tov.tove_id as id,
                   tov.tove_nombre as name
                FROM 
                   " . $con->dbname . ".tipo_oportunidad_venta  tov
                WHERE tov.uaca_id = :uaca_id AND
                      tov.tove_estado = :estado AND
                      tov.tove_estado_logico = :estado
                ORDER BY name asc  ";
     
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);       
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);       
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarCodigoArea
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id      
     * @return  
     */
    public function consultarIdsOporxUnidad($TextAlias) {
        $con = \Yii::$app->db_crm;        
        $sql = "SELECT tove_id Ids 
                    FROM " . $con->dbname . ".tipo_oportunidad_venta  
                WHERE tove_estado_logico=1 AND tove_nombre=:tove_nombre ";                
        $comando = $con->createCommand($sql);
        $comando->bindParam(":tove_nombre", $TextAlias, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData=$comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    }
    
}
