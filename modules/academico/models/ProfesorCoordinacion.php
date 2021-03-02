<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;
/**
 * This is the model class for table "profesor_coordinacion".
 *
 * @property int $pcoo_id
 * @property int $pro_id
 * @property string $pcoo_alumno
 * @property string $pcoo_programa
 * @property string $pcoo_academico
 * @property int $ins_id
 * @property string $pcoo_anio
 * @property int $pcoo_usuario_ingreso
 * @property int $pcoo_usuario_modifica
 * @property string $pcoo_estado
 * @property string $pcoo_fecha_creacion
 * @property string $pcoo_fecha_modificacion
 * @property string $pcoo_estado_logico
 *
 * @property Profesor $pro
 */
class ProfesorCoordinacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesor_coordinacion';
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
            [['pro_id', 'pcoo_alumno', 'pcoo_programa', 'pcoo_academico', 'pcoo_anio', 'pcoo_usuario_ingreso', 'pcoo_estado', 'pcoo_estado_logico'], 'required'],
            [['pro_id', 'ins_id', 'pcoo_usuario_ingreso', 'pcoo_usuario_modifica'], 'integer'],
            [['pcoo_fecha_creacion', 'pcoo_fecha_modificacion'], 'safe'],
            [['pcoo_alumno'], 'string', 'max' => 100],
            [['pcoo_academico', 'pcoo_programa'], 'string', 'max' => 1000],            
            [['pcoo_anio'], 'string', 'max' => 4],
            [['pcoo_estado', 'pcoo_estado_logico'], 'string', 'max' => 1],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pcoo_id' => 'Pcoo ID',
            'pro_id' => 'Pro ID',
            'pcoo_alumno' => 'Pcoo Alumno',
            'pcoo_programa' => 'Pcoo Programa',
            'pcoo_academico' => 'Pcoo Academico',
            'ins_id' => 'Ins ID',
            'pcoo_anio' => 'Pcoo Anio',
            'pcoo_usuario_ingreso' => 'Pcoo Usuario Ingreso',
            'pcoo_usuario_modifica' => 'Pcoo Usuario Modifica',
            'pcoo_estado' => 'Pcoo Estado',
            'pcoo_fecha_creacion' => 'Pcoo Fecha Creacion',
            'pcoo_fecha_modificacion' => 'Pcoo Fecha Modificacion',
            'pcoo_estado_logico' => 'Pcoo Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }
    
    function getAllCoordinacionGrid($pro_id, $onlyData=false){
        $con_academico = \Yii::$app->db_academico;
        $con_general = \Yii::$app->db_general;
        $sql = "SELECT 
                    p.pcoo_id as Ids,
                    pro.pro_id,
                    pro.per_id,                    
                    n.ins_id as ins_id,
                    p.pcoo_alumno as Estudiante,
                    p.pcoo_programa as Programa,
                    p.pcoo_academico as Academico, 
                    n.ins_nombre as Institucion, 
                    p.pcoo_anio as Anio
                FROM " . $con_academico->dbname . ".profesor AS pro
                inner JOIN " . $con_academico->dbname . ".profesor_coordinacion as p on pro.pro_id = p.pro_id
                inner JOIN " . $con_general->dbname . ".institucion as n on n.ins_id = p.ins_id
                WHERE pro.pro_estado_logico = 1 and pro.pro_estado = 1 and p.pcoo_estado_logico = 1 
                and p.pcoo_estado = 1 and pro.pro_id =:proId";
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
                'attributes' => ['Estudiante', 'Programa',"Academico","Anio","Institucion"],
            ],
        ]);

        return $dataProvider;
    }

    function getDataToStorage($pro_id){
        $data = $this->getAllCoordinacionGrid($pro_id, true);

        $arrData = array();
        $arrLabel = array();
        $btnactions = array();

        foreach($data as $key => $value){
            $arrData[] = [ 
                $value['Ids'], 
                $value['Estudiante'], 
                $value['Programa'], 
                $value['Academico'], 
                $value['ins_id'], 
                $value['Anio'], 
            ];
        }
        foreach($data as $key => $value){
            $arrLabel[] = [ 
                $value['Ids'], 
                $value['Estudiante'], 
                $value['Programa'], 
                $value['Academico'], 
                $value['Institucion'], 
                $value['Anio'], 
            ];
        }
        foreach($data as $key => $value){
            $btnactions[] = [[
                "id" => "deleteN".$value['Ids'],
                "class" => "",
                "href" => "",
                "onclick" => "javascript:removeItemCoordinacion(this)",
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
            $sql = "UPDATE " . $con_academico->dbname . ".profesor_coordinacion 
                SET pcoo_estado_logico=0, pcoo_estado=0 
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
