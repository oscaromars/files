<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "profesor_referencia".
 *
 * @property int $pref_id
 * @property int $pro_id
 * @property string $pref_contacto
 * @property string $pref_relacion_cargo
 * @property string $pref_organizacion
 * @property string $pref_numero
 * @property int $pref_usuario_ingreso
 * @property int $pref_usuario_modifica
 * @property string $pref_estado
 * @property string $pref_fecha_creacion
 * @property string $pref_fecha_modificacion
 * @property string $pref_estado_logico
 *
 * @property Profesor $pro
 */
class ProfesorReferencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesor_referencia';
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
            [['pro_id', 'pref_contacto', 'pref_relacion_cargo', 'pref_organizacion', 'pref_numero', 'pref_usuario_ingreso', 'pref_estado', 'pref_estado_logico'], 'required'],
            [['pro_id', 'pref_usuario_ingreso', 'pref_usuario_modifica'], 'integer'],
            [['pref_fecha_creacion', 'pref_fecha_modificacion'], 'safe'],
            [['pref_contacto', 'pref_relacion_cargo', 'pref_organizacion', 'pref_numero'], 'string', 'max' => 100],
            [['pref_estado', 'pref_estado_logico'], 'string', 'max' => 1],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pref_id' => 'Pref ID',
            'pro_id' => 'Pro ID',
            'pref_contacto' => 'Pref Contacto',
            'pref_relacion_cargo' => 'Pref Relacion Cargo',
            'pref_organizacion' => 'Pref Organizacion',
            'pref_numero' => 'Pref Numero',
            'pref_usuario_ingreso' => 'Pref Usuario Ingreso',
            'pref_usuario_modifica' => 'Pref Usuario Modifica',
            'pref_estado' => 'Pref Estado',
            'pref_fecha_creacion' => 'Pref Fecha Creacion',
            'pref_fecha_modificacion' => 'Pref Fecha Modificacion',
            'pref_estado_logico' => 'Pref Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }

    function getAllReferenciaGrid($pro_id, $onlyData=false){
        $con_academico = \Yii::$app->db_academico;
        $sql = "SELECT 
                    p.pref_id as Ids,
                    pro.pro_id,
                    pro.per_id,
                    p.pref_contacto as Nombre,
                    p.pref_relacion_cargo as Cargo,
                    p.pref_organizacion as Organizacion, 
                    p.pref_numero as Numero
                FROM " . $con_academico->dbname . ".profesor AS pro
                inner JOIN " . $con_academico->dbname . ".profesor_referencia as p on pro.pro_id = p.pro_id
                WHERE pro.pro_estado_logico = 1 and pro.pro_estado = 1 and p.pref_estado_logico = 1 
                and p.pref_estado = 1 and pro.pro_id =:proId";
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
                'attributes' => ['Nombre', 'Cargo',"Organizacion","Numero"],
            ],
        ]);

        return $dataProvider;
    }

    function getDataToStorage($pro_id){
        $data = $this->getAllReferenciaGrid($pro_id, true);

        $arrData = array();
        $arrLabel = array();
        $btnactions = array();

        foreach($data as $key => $value){
            $arrData[] = [ 
                $value['Ids'], 
                $value['Nombre'], 
                $value['Cargo'], 
                $value['Organizacion'], 
                $value['Numero'],
            ];
        }
        foreach($data as $key => $value){
            $arrLabel[] = [ 
                $value['Ids'], 
                $value['Nombre'], 
                $value['Cargo'], 
                $value['Organizacion'], 
                $value['Numero'],
            ];
        }
        foreach($data as $key => $value){
            $btnactions[] = [[
                "id" => "deleteN".$value['Ids'],
                "class" => "",
                "href" => "",
                "onclick" => "javascript:removeItemReferencia(this)",
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
            $sql = "UPDATE " . $con_academico->dbname . ".profesor_referencia 
                SET pref_estado_logico=0, pref_estado=0 
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
