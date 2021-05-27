<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "tipo_distributivo".
 *
 * @property int $tdis_id
 * @property string $tdis_nombre
 * @property string $tdis_estado
 * @property string $tdis_fecha_creacion
 * @property string $tdis_fecha_modificacion
 * @property string $tdis_estado_logico
 *
 * @property DistributivoCargaHoraria[] $distributivoCargaHorarias
 */
class TipoDistributivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_distributivo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tdis_estado', 'tdis_estado_logico'], 'required'],
            [['tdis_fecha_creacion', 'tdis_fecha_modificacion'], 'safe'],
            [['tdis_nombre'], 'string', 'max' => 250],
            [['tdis_estado', 'tdis_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tdis_id' => 'Tdis ID',
            'tdis_nombre' => 'Tdis Nombre',
            'tdis_estado' => 'Tdis Estado',
            'tdis_fecha_creacion' => 'Tdis Fecha Creacion',
            'tdis_fecha_modificacion' => 'Tdis Fecha Modificacion',
            'tdis_estado_logico' => 'Tdis Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivoCargaHorarias()
    {
        return $this->hasMany(DistributivoCargaHoraria::className(), ['tdis_id' => 'tdis_id']);
    }
    
    /**
     * Function consulta el nombre de Tipo Distributivo
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarTipoDistributivo($dedic=null) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        
        $sql = "SELECT tdis_id id, tdis_nombre name 
                FROM " . $con->dbname . ".tipo_distributivo 
                WHERE tdis_estado = :estado 
                    and tdis_estado_logico = :estado                
                ";  
        if($dedic==1){
            $sql = $sql."and tdis_id in (1,2,3,4,6,7) "; 
        }
        if($dedic==2){
           $sql = $sql."and tdis_id in (1,7) "; 
        }
        if($dedic==3){
           $sql = $sql."and tdis_id in (1,7) "; 
        }
        if($dedic==null){
          // $sql = $sql."and tdis_id in (0) "; 
        }
          \app\models\Utilities::putMessageLogFile('consultarTipoDistributivo: '.$sql);
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}