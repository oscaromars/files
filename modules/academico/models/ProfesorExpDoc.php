<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "profesor_exp_doc".
 *
 * @property int $pedo_id
 * @property int $pro_id
 * @property string $pedo_fecha_inicio
 * @property string $pedo_fecha_fin
 * @property int $ins_id
 * @property string $pedo_denominacion
 * @property string $pedo_asignaturas
 * @property int $pedo_usuario_ingreso
 * @property int $pedo_usuario_modifica
 * @property string $pedo_estado
 * @property string $pedo_fecha_creacion
 * @property string $pedo_fecha_modificacion
 * @property string $pedo_estado_logico
 *
 * @property Profesor $pro
 */
class ProfesorExpDoc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesor_exp_doc';
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
            [['pro_id', 'ins_id', 'pedo_denominacion', 'pedo_asignaturas', 'pedo_usuario_ingreso', 'pedo_estado', 'pedo_estado_logico'], 'required'],
            [['pro_id', 'pedo_usuario_ingreso', 'pedo_usuario_modifica'], 'integer'],
            [['pedo_fecha_inicio', 'pedo_fecha_fin', 'pedo_fecha_creacion', 'pedo_fecha_modificacion'], 'safe'],
            [['pedo_asignaturas'], 'string', 'max' => 5000],
            [['pedo_denominacion'], 'string', 'max' => 100],
            [['pedo_estado', 'pedo_estado_logico'], 'string', 'max' => 1],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pedo_id' => 'Pedo ID',
            'pro_id' => 'Pro ID',
            'pedo_fecha_inicio' => 'Pedo Fecha Inicio',
            'pedo_fecha_fin' => 'Pedo Fecha Fin',
            'ins_id' => 'Pedo Institucion',
            'pedo_denominacion' => 'Pedo Denominacion',
            'pedo_asignaturas' => 'Pedo Asignaturas',
            'pedo_usuario_ingreso' => 'Pedo Usuario Ingreso',
            'pedo_usuario_modifica' => 'Pedo Usuario Modifica',
            'pedo_estado' => 'Pedo Estado',
            'pedo_fecha_creacion' => 'Pedo Fecha Creacion',
            'pedo_fecha_modificacion' => 'Pedo Fecha Modificacion',
            'pedo_estado_logico' => 'Pedo Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }

    public function getInstituciones($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search = "(ins_nombre like :search AND ";
        }
        $sql = "SELECT
                    ins_id AS id,
                    ins_nombre AS nombre
                FROM
                    institucion
                WHERE
                    $str_search
                    ins_estado_logico = 1
                    AND ins_estado_logico = 1
                ORDER BY ins_id;";
        $comando = Yii::$app->db_general->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'ins_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['nombre'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    function getAllExperienciaGrid($pro_id, $onlyData=false){
        $con_academico = \Yii::$app->db_academico;
        $con_general = \Yii::$app->db_general;
        $sql = "SELECT 
                    p.pedo_id as Ids,
                    pro.pro_id,
                    pro.per_id,
                    n.ins_id  as ins_id,
                    p.pedo_fecha_inicio as Desde,
                    p.pedo_fecha_fin as Hasta,
                    p.pedo_denominacion as Denominacion, 
                    p.pedo_asignaturas as Materias, 
                    n.ins_nombre as Institucion
                FROM " . $con_academico->dbname . ".profesor AS pro
                inner JOIN " . $con_academico->dbname . ".profesor_exp_doc as p on pro.pro_id = p.pro_id
                inner JOIN " . $con_general->dbname . ".institucion as n on n.ins_id = p.ins_id
                WHERE pro.pro_estado_logico = 1 and pro.pro_estado = 1 and p.pedo_estado_logico = 1 
                and p.pedo_estado = 1 and pro.pro_id =:proId";
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
                'attributes' => ['Desde', 'Hasta',"Denominacion","Materias","Institucion"],
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
                $value['ins_id'], 
                date('Y-m-d', strtotime($value['Desde'])), 
                (isset($value['Hasta']) && $value['Hasta'] != "")?date('Y-m-d', strtotime($value['Hasta'])):"", 
                $value['Denominacion'], 
                $value['Materias'], 
            ];
        }
        foreach($data as $key => $value){
            $arrLabel[] = [ 
                $value['Ids'], 
                $value['Institucion'], 
                date('Y-m-d', strtotime($value['Desde'])), 
                (isset($value['Hasta']) && $value['Hasta'] != "")?date('Y-m-d', strtotime($value['Hasta'])):"", 
                $value['Denominacion'], 
                $value['Materias'], 
            ];
        }
        foreach($data as $key => $value){
            $btnactions[] = [[ 
                "id" => "deleteN".$value['Ids'],
                "class" => "",
                "href" => "",
                "onclick" => "javascript:removeItemDocencia(this)",
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
            $sql = "UPDATE " . $con_academico->dbname . ".profesor_exp_doc 
                SET pedo_estado_logico=0, pedo_estado=0 
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
