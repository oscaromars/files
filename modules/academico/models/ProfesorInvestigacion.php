<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "profesor_investigacion".
 *
 * @property int $pinv_id
 * @property int $pro_id
 * @property string $pinv_proyecto
 * @property string $pinv_ambito
 * @property string $pinv_responsabilidad
 * @property string $pinv_entidad
 * @property string $pinv_anio
 * @property string $pinv_duracion
 * @property int $pinv_usuario_ingreso
 * @property int $pinv_usuario_modifica
 * @property string $pinv_estado
 * @property string $pinv_fecha_creacion
 * @property string $pinv_fecha_modificacion
 * @property string $pinv_estado_logico
 *
 * @property Profesor $pro
 */
class ProfesorInvestigacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesor_investigacion';
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
            [['pro_id', 'pinv_proyecto', 'pinv_ambito', 'pinv_responsabilidad', 'pinv_entidad', 'pinv_anio', 'pinv_duracion', 'pinv_usuario_ingreso', 'pinv_estado', 'pinv_estado_logico'], 'required'],
            [['pro_id', 'pinv_usuario_ingreso', 'pinv_usuario_modifica'], 'integer'],
            [['pinv_fecha_creacion', 'pinv_fecha_modificacion'], 'safe'],
            [['pinv_proyecto', 'pinv_responsabilidad', 'pinv_entidad'], 'string', 'max' => 200],
            [['pinv_ambito'], 'string', 'max' => 100],
            [['pinv_anio'], 'string', 'max' => 4],
            [['pinv_duracion'], 'string', 'max' => 20],
            [['pinv_estado', 'pinv_estado_logico'], 'string', 'max' => 1],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pinv_id' => 'Pinv ID',
            'pro_id' => 'Pro ID',
            'pinv_proyecto' => 'Pinv Proyecto',
            'pinv_ambito' => 'Pinv Ambito',
            'pinv_responsabilidad' => 'Pinv Responsabilidad',
            'pinv_entidad' => 'Pinv Entidad',
            'pinv_anio' => 'Pinv Anio',
            'pinv_duracion' => 'Pinv Duracion',
            'pinv_usuario_ingreso' => 'Pinv Usuario Ingreso',
            'pinv_usuario_modifica' => 'Pinv Usuario Modifica',
            'pinv_estado' => 'Pinv Estado',
            'pinv_fecha_creacion' => 'Pinv Fecha Creacion',
            'pinv_fecha_modificacion' => 'Pinv Fecha Modificacion',
            'pinv_estado_logico' => 'Pinv Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }

    function getAllInvestigacionGrid($pro_id, $onlyData=false){
        $con_academico = \Yii::$app->db_academico;
        $sql = "SELECT 
                    p.pinv_id as Ids,
                    pro.pro_id,
                    pro.per_id,
                    p.pinv_proyecto as Denominancion,
                    p.pinv_ambito as Ambito,
                    p.pinv_responsabilidad as Resposabilidad, 
                    p.pinv_entidad as Entidad, 
                    p.pinv_anio as Anio,
                    p.pinv_duracion as Duracion
                FROM " . $con_academico->dbname . ".profesor AS pro
                inner JOIN " . $con_academico->dbname . ".profesor_investigacion as p on pro.pro_id = p.pro_id
                WHERE pro.pro_estado_logico = 1 and pro.pro_estado = 1 and p.pinv_estado_logico = 1 
                and p.pinv_estado = 1 and pro.pro_id =:proId";
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
                'attributes' => ['Denominancion', 'Ambito',"Resposabilidad","Denominacion","Entidad","Duracion"],
            ],
        ]);

        return $dataProvider;
    }

    function getDataToStorage($pro_id){
        $data = $this->getAllInvestigacionGrid($pro_id, true);

        $arrData = array();
        $arrLabel = array();
        $btnactions = array();

        foreach($data as $key => $value){
            $arrData[] = [ 
                $value['Ids'], 
                $value['Denominancion'], 
                $value['Ambito'], 
                $value['Resposabilidad'], 
                $value['Entidad'], 
                $value['Anio'], 
                $value['Duracion'], 
            ];
        }
        foreach($data as $key => $value){
            $arrLabel[] = [ 
                $value['Ids'], 
                $value['Denominancion'], 
                $value['Ambito'], 
                $value['Resposabilidad'], 
                $value['Entidad'], 
                $value['Anio'], 
                $value['Duracion'], 
            ];
        }
        foreach($data as $key => $value){
            $btnactions[] = [[ 
                "id" => "deleteN".$value['Ids'],
                "class" => "",
                "href" => "",
                "onclick" => "javascript:removeItemInvestigacion(this)",
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
            $sql = "UPDATE " . $con_academico->dbname . ".profesor_investigacion 
                SET pinv_estado_logico=0, pinv_estado=0 
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
