<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;


/**
 * This is the model class for table "profesor_exp_prof".
 *
 * @property int $pepr_id
 * @property int $pro_id
 * @property string $pepr_fecha_inicio
 * @property string $pepr_fecha_fin
 * @property string $pepr_organizacion
 * @property string $pepr_denominacion
 * @property string $pepr_funciones
 * @property int $pepr_usuario_ingreso
 * @property int $pepr_usuario_modifica
 * @property string $pepr_estado
 * @property string $pepr_fecha_creacion
 * @property string $pepr_fecha_modificacion
 * @property string $pepr_estado_logico
 *
 * @property Profesor $pro
 */
class ProfesorExpProf extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesor_exp_prof';
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
            [['pro_id', 'pepr_organizacion', 'pepr_denominacion', 'pepr_usuario_ingreso', 'pepr_estado', 'pepr_estado_logico'], 'required'],
            [['pro_id', 'pepr_usuario_ingreso', 'pepr_usuario_modifica'], 'integer'],
            [['pepr_fecha_inicio', 'pepr_fecha_fin', 'pepr_fecha_creacion', 'pepr_fecha_modificacion'], 'safe'],
            [['pepr_organizacion'], 'string', 'max' => 200],
            [['pepr_denominacion'], 'string', 'max' => 100],
            [['pepr_funciones'], 'string', 'max' => 5000],
            [['pepr_estado', 'pepr_estado_logico'], 'string', 'max' => 1],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pepr_id' => 'Pepr ID',
            'pro_id' => 'Pro ID',
            'pepr_fecha_inicio' => 'Pepr Fecha Inicio',
            'pepr_fecha_fin' => 'Pepr Fecha Fin',
            'pepr_organizacion' => 'Pepr Organizacion',
            'pepr_denominacion' => 'Pepr Denominacion',
            'pepr_funciones' => 'Pepr Funciones',
            'pepr_usuario_ingreso' => 'Pepr Usuario Ingreso',
            'pepr_usuario_modifica' => 'Pepr Usuario Modifica',
            'pepr_estado' => 'Pepr Estado',
            'pepr_fecha_creacion' => 'Pepr Fecha Creacion',
            'pepr_fecha_modificacion' => 'Pepr Fecha Modificacion',
            'pepr_estado_logico' => 'Pepr Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }
    
    function getAllExperienciaGrid($pro_id, $onlyData=false){
        $con_academico = \Yii::$app->db_academico;
        $sql = "SELECT 
                    p.pepr_id as Ids,
                    pro.pro_id,
                    pro.per_id,
                    p.pepr_fecha_inicio as Desde,
                    p.pepr_fecha_fin as Hasta,
                    p.pepr_organizacion as Institucion, 
                    p.pepr_denominacion as Denominacion, 
                    p.pepr_funciones as Funciones
                FROM " . $con_academico->dbname . ".profesor AS pro
                inner JOIN " . $con_academico->dbname . ".profesor_exp_prof as p on pro.pro_id = p.pro_id
                WHERE pro.pro_estado_logico = 1 and pro.pro_estado = 1 and p.pepr_estado_logico = 1 
                and p.pepr_estado = 1 and pro.pro_id =:proId";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(':proId', $pro_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        if($onlyData)   return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Ids',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Desde', 'Hasta',"Denominacion","Funciones","Institucion"],
            ],
        ]);

        return $dataProvider;
    }

    function getDataToStorage($pro_id){
        $data = $this->getAllExperienciaGrid($pro_id, true);

        $arrData = array();
        $arrLabel = array();
        $btnactions = array();

        foreach($data as $key => $value){
            $arrData[] = [ 
                $value['Ids'], 
                $value['Institucion'], 
                date('Y-m-d', strtotime($value['Desde'])), 
                (isset($value['Hasta']) && $value['Hasta'] != "")?date('Y-m-d', strtotime($value['Hasta'])):"", 
                $value['Denominacion'], 
                $value['Funciones'], 
            ];
        }
        foreach($data as $key => $value){
            $arrLabel[] = [ 
                $value['Ids'], 
                $value['Institucion'], 
                date('Y-m-d', strtotime($value['Desde'])), 
                (isset($value['Hasta']) && $value['Hasta'] != "")?date('Y-m-d', strtotime($value['Hasta'])):"", 
                $value['Denominacion'], 
                $value['Funciones'], 
            ];
        }
        foreach($data as $key => $value){
            $btnactions[] = [[ 
                "id" => "deleteN".$value['Ids'],
                "class" => "",
                "href" => "",
                "onclick" => "javascript:removeItemExperiencia(this)",
                "tipo_accion" => "delete",
                "title" => Yii::t("accion","Delete")
            ]];
        }
        return [$arrData, $arrLabel, $btnactions];
    }

    public static function deleteAllInfo($pro_id){
        $con_academico = \Yii::$app->db_academico;
        $trans = $con_academico->beginTransaction();
        try{
            $sql = "UPDATE " . $con_academico->dbname . ".profesor_exp_prof 
                SET pepr_estado_logico=0, pepr_estado=0 
                WHERE pro_id=:proId";

            $comando = $con_academico->createCommand($sql);
            $comando->bindParam(':proId', $pro_id, \PDO::PARAM_INT);
            $res = $comando->execute();
            if($res){
                $trans->commit();
                return true;
            }else
                throw new \Exception('Error');
        }catch(\Exception $e){
            $trans->rollBack();
            return false;
        }
    }
}
