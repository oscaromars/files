<?php

namespace app\modules\academico\models;
use yii\data\ArrayDataProvider;
use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "distributivo_horario_paralelo".
 *
 * @property int $dhpa_id
 * @property int $daho_id
 * @property string|null $dhpa_grupo
 * @property string $dhpa_paralelo
 * @property int $dhpa_usuario_ingreso
 * @property int|null $dhpa_usuario_modifica
 * @property string $dhpa_estado
 * @property string $dhpa_fecha_creacion
 * @property string|null $dhpa_fecha_modificacion
 * @property string $dhpa_estado_logico
 *
 * @property DistributivoAcademicoHorario $daho
 */
class DistributivoHorarioParalelo extends \yii\db\ActiveRecord 
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'distributivo_horario_paralelo';
    }

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
            [[ 'dhpa_paralelo'], 'required'],
            [['daho_id', 'dhpa_usuario_ingreso', 'dhpa_usuario_modifica'], 'integer'],
            [['dhpa_fecha_creacion', 'dhpa_fecha_modificacion'], 'safe'],
            [['dhpa_grupo'], 'string', 'max' => 2],
            [['dhpa_estado', 'dhpa_estado_logico'], 'string', 'max' => 1],
            [['daho_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistributivoAcademicoHorario::className(), 'targetAttribute' => ['daho_id' => 'daho_id']],
         ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dhpa_id' => '',
            'daho_id' => 'Horario',
            'dhpa_grupo' => 'Grupo: ',
            'dhpa_paralelo' => 'Nombre del Paralelo',
            'dhpa_usuario_ingreso' => '',
            'dhpa_usuario_modifica' => '',
            'dhpa_estado' => '',
            'dhpa_fecha_creacion' => '',
            'dhpa_fecha_modificacion' => '',
            'dhpa_estado_logico' => '',
        ];
    }

    /**
     * Gets query for [[Daho]].
     *
     * @return \yii\db\ActiveQuery
     */
    
        
     public function getDaho() {
        return $this->hasOne(DistributivoAcademicoHorario::className(), ['daho_id' => 'daho_id']);
    }
  
    

    
    function getAllDistributivoHorarioParaleloGrid($search = NULL, $perfil){
          $con_academico = \Yii::$app->db_academico;
           $str_search = "";
           $search_cond = "%" . $search . "%";
          if (isset($search)) {
            $str_search = " and (p.dhpa_paralelo like :search )";
            
        }
        $estado = 1; 
         $sql = "SELECT dhpa_id as dhpa_id,dhpa_paralelo as Nombre, daho_descripcion  as Horario, p.daho_id, dhpa_grupo, uaca_id,p.dhpa_estado  from "
                   .$con_academico->dbname.".distributivo_horario_paralelo p inner join "
                   .$con_academico->dbname.".distributivo_academico_horario h on h.daho_id=p.daho_id ".
                                           " where p.dhpa_estado= :estado ".$str_search;
         
         $comando = $con_academico->createCommand($sql);
          $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
          if(isset($search)){
              
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
            
        }
           $res = $comando->queryAll();
           $dataProvider = new ArrayDataProvider([
            'key' => 'dhpa_id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['dhpa_id', 'Nombre','Horario','daho_id','dhpa_grupo','uaca_id','dhpa_estado'],
            ],
        ]);

        return $dataProvider;

    }
}
