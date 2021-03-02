<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "tipo_proyecto".
 *
 * @property int $tpro_id
 * @property string $tpro_nombre
 * @property string $tpro_descripcion
 * @property int $tpro_usuario_ingreso
 * @property int|null $tpro_usuario_modifica
 * @property string $tpro_estado
 * @property string $tpro_fecha_creacion
 * @property string|null $tpro_fecha_modificacion
 * @property string $tpro_estado_logico
 */
class TipoProyecto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_proyecto';
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
            [['tpro_nombre', 'tpro_descripcion', 'tpro_usuario_ingreso', 'tpro_estado', 'tpro_estado_logico'], 'required'],
            [['tpro_usuario_ingreso', 'tpro_usuario_modifica'], 'integer'],
            [['tpro_fecha_creacion', 'tpro_fecha_modificacion'], 'safe'],
            [['tpro_nombre'], 'string', 'max' => 300],
            [['tpro_descripcion'], 'string', 'max' => 500],
            [['tpro_estado', 'tpro_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tpro_id' => 'Tpro ID',
            'tpro_nombre' => 'Tpro Nombre',
            'tpro_descripcion' => 'Tpro Descripcion',
            'tpro_usuario_ingreso' => 'Tpro Usuario Ingreso',
            'tpro_usuario_modifica' => 'Tpro Usuario Modifica',
            'tpro_estado' => 'Tpro Estado',
            'tpro_fecha_creacion' => 'Tpro Fecha Creacion',
            'tpro_fecha_modificacion' => 'Tpro Fecha Modificacion',
            'tpro_estado_logico' => 'Tpro Estado Logico',
        ];
    }

    function getAllTipProGrid($search = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gpr;
        if(isset($search)){
            $str_search  = "(tpro_nombre like :search OR ";
            $str_search .= "tpro_descripcion like :search) AND ";
        }
        $sql = "SELECT 
                    tpro_id as id,
                    tpro_nombre as Nombre,
                    tpro_descripcion as Descripcion,
                    tpro_estado as Estado
                FROM 
                    ".$con->dbname.".tipo_proyecto
                WHERE 
                    $str_search
                    tpro_estado_logico=1 
                ORDER BY tpro_id;";
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
