<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "estado_contacto".
 *
 * @property int $econ_id
 * @property string $econ_nombre
 * @property string $econ_descripcion
 * @property string $econ_estado
 * @property string $econ_fecha_creacion
 * @property string $econ_fecha_modificacion
 * @property string $econ_estado_logico
 *
 * @property PersonaGestion[] $personaGestions
 */
class EstadoContacto extends \app\modules\admision\components\CActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'estado_contacto';
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
            [['econ_nombre', 'econ_descripcion', 'econ_estado', 'econ_estado_logico'], 'required'],
            [['econ_fecha_creacion', 'econ_fecha_modificacion'], 'safe'],
            [['econ_nombre'], 'string', 'max' => 300],
            [['econ_descripcion'], 'string', 'max' => 500],
            [['econ_estado', 'econ_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'econ_id' => 'Econ ID',
            'econ_nombre' => 'Econ Nombre',
            'econ_descripcion' => 'Econ Descripcion',
            'econ_estado' => 'Econ Estado',
            'econ_fecha_creacion' => 'Econ Fecha Creacion',
            'econ_fecha_modificacion' => 'Econ Fecha Modificacion',
            'econ_estado_logico' => 'Econ Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaGestions() {
        return $this->hasMany(PersonaGestion::className(), ['econ_id' => 'econ_id']);
    }

    /**
     * Function consulta los subestados de los clientes.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarEstadoContacto() {
        $con = \Yii::$app->db_crm;
        $estado = 1;

        $sql = "SELECT 
                   ec.econ_id as id,
                   ec.econ_nombre as name
                FROM 
                   " . $con->dbname . ".estado_contacto  ec
                WHERE 
                      -- ec.econ_id = :estado_contacto AND
                      ec.econ_estado = :estado AND
                      ec.econ_estado_logico = :estado
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
    public function consultarIdsEstadoContacto($TextAlias) {
        $con = \Yii::$app->db_crm;        
        $sql = "SELECT econ_id Ids 
                    FROM " . $con->dbname . ".estado_contacto  
                WHERE econ_estado_logico=1 AND econ_nombre=:econ_nombre ";                
        $comando = $con->createCommand($sql);
        $comando->bindParam(":econ_nombre", $TextAlias, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData=$comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    }

}
