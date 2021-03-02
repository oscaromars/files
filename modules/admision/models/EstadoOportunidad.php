<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "estado_oportunidad".
 *
 * @property int $eopo_id
 * @property string $eopo_nombre
 * @property string $eopo_descripcion
 * @property string $eopo_tipo
 * @property string $eopo_estado
 * @property string $eopo_fecha_creacion
 * @property string $eopo_fecha_modificacion
 * @property string $eopo_estado_logico
 *
 * @property Oportunidad[] $oportunidads
 */
class EstadoOportunidad extends \app\modules\admision\components\CActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'estado_oportunidad';
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
            [['eopo_nombre', 'eopo_descripcion', 'eopo_estado', 'eopo_estado_logico'], 'required'],
            [['eopo_fecha_creacion', 'eopo_fecha_modificacion'], 'safe'],
            [['eopo_nombre'], 'string', 'max' => 300],
            [['eopo_descripcion'], 'string', 'max' => 500],
            [['eopo_tipo', 'eopo_estado', 'eopo_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'eopo_id' => 'Eopo ID',
            'eopo_nombre' => 'Eopo Nombre',
            'eopo_descripcion' => 'Eopo Descripcion',
            'eopo_tipo' => 'Eopo Tipo',
            'eopo_estado' => 'Eopo Estado',
            'eopo_fecha_creacion' => 'Eopo Fecha Creacion',
            'eopo_fecha_modificacion' => 'Eopo Fecha Modificacion',
            'eopo_estado_logico' => 'Eopo Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOportunidads() {
        return $this->hasMany(Oportunidad::className(), ['eopo_id' => 'eopo_id']);
    }

    /**
     * Function consulta los subestados de las oportunidades de venta.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarEstadOportunidad() {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $filtro = '';
        $sql = "
                SELECT 
                   eopo.eopo_id as id,
                   eopo.eopo_nombre as name
                FROM 
                   " . $con->dbname . ".estado_oportunidad  eopo
                WHERE eopo.eopo_estado = :estado AND
                      eopo.eopo_estado_logico = :estado
                ORDER BY name asc  ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarCodigoArea
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id      
     * @return  
     */
    public function consultarIdsEstadOportunidad($TextAlias) {
        $con = \Yii::$app->db_crm;        
        $sql = "SELECT eopo_id Ids 
                    FROM " . $con->dbname . ".estado_oportunidad  
                WHERE eopo_estado_logico=1 AND eopo_nombre=:eopo_nombre ";                
        $comando = $con->createCommand($sql);
        $comando->bindParam(":eopo_nombre", $TextAlias, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData=$comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    }

}
