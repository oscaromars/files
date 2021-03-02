<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "umbral".
 *
 * @property int $umb_id
 * @property string $umb_nombre
 * @property string $umb_descripcion
 * @property string $umb_color
 * @property string $umb_per_inicio
 * @property string $umb_per_fin
 * @property int $umb_usuario_ingreso
 * @property int|null $umb_usuario_modifica
 * @property string $umb_estado
 * @property string $umb_fecha_creacion
 * @property string|null $umb_fecha_modificacion
 * @property string $umb_estado_logico
 */
class Umbral extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'umbral';
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
            [['umb_nombre', 'umb_descripcion', 'umb_color', 'umb_per_inicio', 'umb_per_fin', 'umb_usuario_ingreso', 'umb_estado', 'umb_estado_logico'], 'required'],
            [['umb_usuario_ingreso', 'umb_usuario_modifica'], 'integer'],
            [['umb_fecha_creacion', 'umb_fecha_modificacion'], 'safe'],
            [['umb_nombre'], 'string', 'max' => 300],
            [['umb_descripcion'], 'string', 'max' => 500],
            [['umb_color', 'umb_per_inicio', 'umb_per_fin'], 'string', 'max' => 10],
            [['umb_estado', 'umb_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'umb_id' => 'Umb ID',
            'umb_nombre' => 'Umb Nombre',
            'umb_descripcion' => 'Umb Descripcion',
            'umb_color' => 'Umb Color',
            'umb_per_inicio' => 'Umb Per Inicio',
            'umb_per_fin' => 'Umb Per Fin',
            'umb_usuario_ingreso' => 'Umb Usuario Ingreso',
            'umb_usuario_modifica' => 'Umb Usuario Modifica',
            'umb_estado' => 'Umb Estado',
            'umb_fecha_creacion' => 'Umb Fecha Creacion',
            'umb_fecha_modificacion' => 'Umb Fecha Modificacion',
            'umb_estado_logico' => 'Umb Estado Logico',
        ];
    }

    function getAllUmbralesGrid($search = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gpr;
        if(isset($search)){
            $str_search  = "(umb_nombre like :search OR ";
            $str_search .= "umb_descripcion like :search) AND ";
        }
        $sql = "SELECT 
                    umb_id as id,
                    umb_nombre as Nombre,
                    umb_descripcion as Descripcion,
                    umb_color as Color,
                    umb_per_inicio as Inicio,
                    umb_per_fin as Fin,
                    umb_estado as Estado
                FROM 
                    ".$con->dbname.".umbral
                WHERE 
                    $str_search
                    umb_estado_logico=1 
                ORDER BY umb_id;";
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

    public static function getUmbralByParameter($parameter){
        $con = Yii::$app->db_gpr;
        $sql = "SELECT 
                    umb_nombre as Nombre,
                    umb_color as Color,
                    umb_per_inicio as Inicio,
                    umb_per_fin as Fin
                FROM 
                    ".$con->dbname.".umbral
                WHERE 
                    umb_per_inicio <= :parameter AND umb_per_fin >= :parameter 
                    AND umb_estado_logico=1 AND umb_estado=1
                ORDER BY umb_id;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":parameter",$parameter, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        return $res;
    }
}
