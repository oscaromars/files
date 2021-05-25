<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "enfoque".
 *
 * @property int $enf_id
 * @property string $enf_nombre
 * @property string $enf_descripcion
 * @property int $enf_usuario_ingreso
 * @property int|null $enf_usuario_modifica
 * @property string $enf_estado
 * @property string $enf_fecha_creacion
 * @property string|null $enf_fecha_modificacion
 * @property string $enf_estado_logico
 *
 * @property ObjetivoEstrategico[] $objetivoEstrategicos
 */
class Enfoque extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'enfoque';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gpr');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['enf_nombre', 'enf_descripcion', 'enf_usuario_ingreso', 'enf_estado', 'enf_estado_logico'], 'required'],
            [['enf_usuario_ingreso', 'enf_usuario_modifica'], 'integer'],
            [['enf_fecha_creacion', 'enf_fecha_modificacion'], 'safe'],
            [['enf_nombre'], 'string', 'max' => 300],
            [['enf_descripcion'], 'string', 'max' => 500],
            [['enf_estado', 'enf_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'enf_id' => 'Enf ID',
            'enf_nombre' => 'Enf Nombre',
            'enf_descripcion' => 'Enf Descripcion',
            'enf_usuario_ingreso' => 'Enf Usuario Ingreso',
            'enf_usuario_modifica' => 'Enf Usuario Modifica',
            'enf_estado' => 'Enf Estado',
            'enf_fecha_creacion' => 'Enf Fecha Creacion',
            'enf_fecha_modificacion' => 'Enf Fecha Modificacion',
            'enf_estado_logico' => 'Enf Estado Logico',
        ];
    }

    /**
     * Gets query for [[ObjetivoEstrategicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetivoEstrategicos()
    {
        return $this->hasMany(ObjetivoEstrategico::className(), ['enf_id' => 'enf_id']);
    }

    public function getAllEnfoqueGrid($search = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gpr;
        if(isset($search)){
            $str_search  = "(enf_nombre like :search OR ";
            $str_search .= "enf_descripcion like :search) AND ";
        }
        $sql = "SELECT 
                    enf_id as id,
                    enf_nombre as Nombre,
                    enf_descripcion as Descripcion,
                    enf_estado as Estado
                FROM 
                    ".$con->dbname.".enfoque
                WHERE 
                    $str_search
                    enf_estado_logico=1 
                ORDER BY enf_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'Estado', 'Descripcion'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
}
