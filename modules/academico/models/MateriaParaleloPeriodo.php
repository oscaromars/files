<?php
namespace app\modules\academico\models;

use yii\base\Exception;
use Yii;
use yii\data\ArrayDataProvider;
use app\models\Utilities;
 
class MateriaParaleloPeriodo extends \app\modules\admision\components\CActiveRecord {
     
     /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'materia_paralelo_periodo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
           
           [['asi_id', 'mod_id', 'paca_id','mpp_num_paralelo' ], 'integer'],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
            [['asi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asi_id' => 'asi_id']],
            [['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidad::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
            ];
    }

    
public static function getNumparalelo(){
    return [
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
        ];
}
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsig()
    {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMod()
    {
        return $this->hasOne(Modalidad::className(), ['mod_id' => 'mod_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'mpp_id' => 'Id',
            'paca_id' => 'Periodo',
            'asi_id' => 'Asignatura',
            'mod_id'=>'Modalidad',
            'mpp_num_paralelo'=>'Número de Paralelos',
            'mpp_usuario_ingreso' => '',
            'mpp_usuario_modifica' => '',
            'mpp_estado' => 'Estado',
            'mpp_fecha_creacion' => '',
            'mpp_fecha_modificacion' => '',
            'mpp_estado_logico' => '',
            
        ];
    }
    public function getParalelosAsignatura($paca_id,$mod_id,$asi_id){
        $con = \Yii::$app->db_academico;
        $estado = 1;  

        $sql = "select mpp.mpp_id id, mpp_num_paralelo name  from "
                         . $con->dbname . ".materia_paralelo_periodo  as mpp
                         left join " . $con->dbname . ".distributivo_academico as dc on mpp.mpp_id= dc.mpp_id and mpp.asi_id =dc.asi_id and mpp.mod_id=dc.mod_id and mpp.paca_id=dc.paca_id
                       
                        where  dc.mpp_id is null and  mpp.asi_id =:asi_id
                        and mpp.mod_id=:mod_id
                        and mpp.paca_id=:paca_id
                        and mpp_estado=:estado";
 \app\models\Utilities::putMessageLogFile($sql); 
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}