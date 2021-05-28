<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "profesor_evaluacion".
 *
 * @property int $peva_id
 * @property int $pro_id
 * @property string $peva_periodo
 * @property string $peva_institucion
 * @property string $peva_evaluacion
 * @property int $peva_usuario_ingreso
 * @property int $peva_usuario_modifica
 * @property string $peva_estado
 * @property string $peva_fecha_creacion
 * @property string $peva_fecha_modificacion
 * @property string $peva_estado_logico
 *
 * @property Profesor $pro
 */
class ProfesorEvaluacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesor_evaluacion';
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
            [['pro_id', 'peva_periodo', 'peva_institucion', 'peva_evaluacion', 'peva_usuario_ingreso', 'peva_estado', 'peva_estado_logico'], 'required'],
            [['pro_id', 'peva_usuario_ingreso', 'peva_usuario_modifica'], 'integer'],
            [['peva_fecha_creacion', 'peva_fecha_modificacion'], 'safe'],
            [['peva_periodo', 'peva_institucion', 'peva_evaluacion'], 'string', 'max' => 100],
            [['peva_estado', 'peva_estado_logico'], 'string', 'max' => 1],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'peva_id' => 'Peva ID',
            'pro_id' => 'Pro ID',
            'peva_periodo' => 'Peva Periodo',
            'peva_institucion' => 'Peva Institucion',
            'peva_evaluacion' => 'Peva Evaluacion',
            'peva_usuario_ingreso' => 'Peva Usuario Ingreso',
            'peva_usuario_modifica' => 'Peva Usuario Modifica',
            'peva_estado' => 'Peva Estado',
            'peva_fecha_creacion' => 'Peva Fecha Creacion',
            'peva_fecha_modificacion' => 'Peva Fecha Modificacion',
            'peva_estado_logico' => 'Peva Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }


    function getAllEvaluacionGrid($pro_id, $onlyData=false){
        $con_academico = \Yii::$app->db_academico;
        $sql = "SELECT 
                    p.peva_id as Ids,
                    pro.pro_id,
                    pro.per_id,
                    p.peva_periodo as Periodo,
                    p.peva_institucion as Institucion,
                    p.peva_evaluacion as Evaluacion
                FROM " . $con_academico->dbname . ".profesor AS pro
                inner JOIN " . $con_academico->dbname . ".profesor_evaluacion as p on pro.pro_id = p.pro_id
                WHERE pro.pro_estado_logico = 1 and pro.pro_estado = 1 and p.peva_estado_logico = 1 
                and p.peva_estado = 1 and pro.pro_id =:proId";
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
                'attributes' => ['Periodo', 'Institucion',"Evaluacion"],
            ],
        ]);

        return $dataProvider;
    }

    function getDataToStorage($pro_id){
        $data = $this->getAllEvaluacionGrid($pro_id, true);

        $arrData = array();
        $arrLabel = array();
        $btnactions = array();

        foreach($data as $key => $value){
            $arrData[] = [ 
                $value['Ids'], 
                $value['Periodo'], 
                $value['Institucion'], 
                $value['Evaluacion'], 
            ];
        }
        foreach($data as $key => $value){
            $arrLabel[] = [ 
                $value['Ids'], 
                $value['Periodo'], 
                $value['Institucion'], 
                $value['Evaluacion'], 
            ];
        }
        foreach($data as $key => $value){
            $btnactions[] = [[
                "id" => "deleteN".$value['Ids'],
                "class" => "",
                "href" => "",
                "onclick" => "javascript:removeItemEvaluacion(this)",
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
            $sql = "UPDATE " . $con_academico->dbname . ".profesor_evaluacion 
                SET peva_estado_logico=0, peva_estado=0 
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
