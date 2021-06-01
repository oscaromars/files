<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "tipo_rol".
 *
 * @property int $trol_id
 * @property string|null $trol_nombre
 * @property int|null $trol_numero_horas
 * @property float|null $trol_porcentaje
 * @property int|null $trol_usuario_ingreso
 * @property int|null $trol_usuario_modifica
 * @property string|null $trol_estado
 * @property string|null $trol_fecha_creacion
 * @property string|null $trol_fecha_modificacion
 * @property string|null $trol_estado_logico
 *
 * @property RolPagos[] $rolPagos
 * @property RolPagosTemp[] $rolPagosTemps
 */
class TipoRol extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_rol';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gfinanciero');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trol_numero_horas', 'trol_usuario_ingreso', 'trol_usuario_modifica'], 'integer'],
            [['trol_porcentaje'], 'number'],
            [['trol_fecha_creacion', 'trol_fecha_modificacion'], 'safe'],
            [['trol_nombre'], 'string', 'max' => 60],
            [['trol_estado', 'trol_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'trol_id' => 'Trol ID',
            'trol_nombre' => financiero::t("tiporol", "Role Name"),
            'trol_numero_horas' => financiero::t("tiporol", "Hours"),
            'trol_porcentaje' => financiero::t("tiporol", "Percentage"),
            'trol_usuario_ingreso' => 'Trol Usuario Ingreso',
            'trol_usuario_modifica' => 'Trol Usuario Modifica',
            'trol_estado' => 'Trol Estado',
            'trol_fecha_creacion' => 'Trol Fecha Creacion',
            'trol_fecha_modificacion' => 'Trol Fecha Modificacion',
            'trol_estado_logico' => 'Trol Estado Logico',
        ];
    }

    /**
     * Gets query for [[RolPagos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRolPagos()
    {
        return $this->hasMany(RolPagos::className(), ['trol_id' => 'trol_id']);
    }

    /**
     * Gets query for [[RolPagosTemps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRolPagosTemps()
    {
        return $this->hasMany(RolPagosTemp::className(), ['trol_id' => 'trol_id']);
    }

    /**
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Item Name
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
    public function getAllItemsGrid($search, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(trol_nombre like :search) AND ";
        }
        $cols = "trol_id as Id, trol_nombre as Nombre, trol_numero_horas as Horas, trol_porcentaje as Porcentaje";
        if($export) $cols = "trol_nombre as Nombre, trol_numero_horas as Horas, trol_porcentaje as Porcentaje";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".tipo_rol
                WHERE 
                    $str_search
                    trol_estado = 1 AND trol_estado_logico = 1
                ORDER BY trol_id;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $result,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre'],
                ],
            ]);
            return $dataProvider;
        }
        return $result;
    }
}
