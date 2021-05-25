<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "tipo_configuracion".
 *
 * @property int $tcon_id
 * @property string $tcon_nombre
 * @property string $tcon_descripcion
 * @property int $tcon_usuario_ingreso
 * @property int|null $tcon_usuario_modifica
 * @property string $tcon_estado
 * @property string $tcon_fecha_creacion
 * @property string|null $tcon_fecha_modificacion
 * @property string $tcon_estado_logico
 */
class TipoConfiguracion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_configuracion';
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
            [['tcon_nombre', 'tcon_descripcion', 'tcon_usuario_ingreso', 'tcon_estado', 'tcon_estado_logico'], 'required'],
            [['tcon_usuario_ingreso', 'tcon_usuario_modifica'], 'integer'],
            [['tcon_fecha_creacion', 'tcon_fecha_modificacion'], 'safe'],
            [['tcon_nombre'], 'string', 'max' => 300],
            [['tcon_descripcion'], 'string', 'max' => 500],
            [['tcon_estado', 'tcon_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tcon_id' => 'Tcon ID',
            'tcon_nombre' => 'Tcon Nombre',
            'tcon_descripcion' => 'Tcon Descripcion',
            'tcon_usuario_ingreso' => 'Tcon Usuario Ingreso',
            'tcon_usuario_modifica' => 'Tcon Usuario Modifica',
            'tcon_estado' => 'Tcon Estado',
            'tcon_fecha_creacion' => 'Tcon Fecha Creacion',
            'tcon_fecha_modificacion' => 'Tcon Fecha Modificacion',
            'tcon_estado_logico' => 'Tcon Estado Logico',
        ];
    }

    function getAllTipConfGrid($search = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gpr;
        if(isset($search)){
            $str_search  = "(tcon_nombre like :search OR ";
            $str_search .= "tcon_descripcion like :search) AND ";
        }
        $sql = "SELECT 
                    tcon_id as id,
                    tcon_nombre as Nombre,
                    tcon_descripcion as Descripcion,
                    tcon_estado as Estado
                FROM 
                    ".$con->dbname.".tipo_configuracion
                WHERE 
                    $str_search
                    tcon_estado_logico=1 
                ORDER BY tcon_id;";
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
