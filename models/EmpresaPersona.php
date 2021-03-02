<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "empresa_persona".
 *
 * @property int $eper_id
 * @property int $emp_id
 * @property int $per_id
 * @property string $eper_estado
 * @property string $eper_fecha_creacion
 * @property string $eper_fecha_modificacion
 * @property string $eper_estado_logico
 *
 * @property Empresa $emp
 * @property UsuaGrolEper[] $usuaGrolEpers
 */
class EmpresaPersona extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empresa_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'per_id', 'eper_estado', 'eper_estado_logico'], 'required'],
            [['emp_id', 'per_id'], 'integer'],
            [['eper_fecha_creacion', 'eper_fecha_modificacion'], 'safe'],
            [['eper_estado', 'eper_estado_logico'], 'string', 'max' => 1],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empresa::className(), 'targetAttribute' => ['emp_id' => 'emp_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'eper_id' => 'Eper ID',
            'emp_id' => 'Emp ID',
            'per_id' => 'Per ID',
            'eper_estado' => 'Eper Estado',
            'eper_fecha_creacion' => 'Eper Fecha Creacion',
            'eper_fecha_modificacion' => 'Eper Fecha Modificacion',
            'eper_estado_logico' => 'Eper Estado Logico',
        ];
    }
    
    /**
     * Function modificaPersona
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    public function insertarEmpresaPersona($con,$parameters,$keys,$name_table) {
        $trans = $con->getTransaction(); 
        $param_sql .= "" . $keys[0];
        $bdet_sql .= "'" . $parameters[0]."'";
        for ($i = 1; $i < count($parameters); $i++) {
            if (isset($parameters[$i])) {
                $param_sql .= ", " . $keys[$i];
                $bdet_sql .= ", '" . $parameters[$i]."'";
            }
        }
        try {
            $sql = "INSERT INTO " . $con->dbname.'.'.$name_table . " ($param_sql) VALUES($bdet_sql);";                        
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $idtable=$con->getLastInsertID($con->dbname . '.' . $name_table);
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null){
                $trans->rollback();            
            }
            return 0;
        }
    }
    /**
     * Function consultarIdPersona 
     * @author  Kleber Loayza
     * @property      
     * @return  
     */
    public function consultarIdEmpresaPersona($per_id=null,$emp_id=null) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "
                SELECT ifnull(eper_id,0) as eper_id
                FROM empresa_persona as eper           
                WHERE 
                per_id=$per_id and emp_id=$emp_id
                and eper.eper_estado = $estado
                and eper.eper_estado_logico=$estado
              ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();        
        if(empty($resultData['eper_id']))
            return 0;
        else {
            return $resultData['eper_id'];    
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmp()
    {
        return $this->hasOne(Empresa::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuaGrolEpers()
    {
        return $this->hasMany(UsuaGrolEper::className(), ['eper_id' => 'eper_id']);
    }
    
    /**
     * Function listadoUsuariosP
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @param      
     * @return  
     */
    
    public function insertarDataEmpresaPersona($con,$data) {
        //eper_id
        $sql = "INSERT INTO " . $con->dbname . ".empresa_persona
            (emp_id,per_id,eper_fecha_creacion,eper_estado,eper_estado_logico)VALUES
            (:emp_id,:per_id,CURRENT_TIMESTAMP(),1,1) ";
        $command = $con->createCommand($sql);
        $command->bindParam(":emp_id",$data[0]['emp_id'], \PDO::PARAM_INT);
        $command->bindParam(":per_id",$data[0]['per_id'], \PDO::PARAM_INT);    
        $command->execute();
        return $con->getLastInsertID();
    }
    
}
