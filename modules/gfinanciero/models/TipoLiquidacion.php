<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "tipo_liquidacion".
 *
 * @property int $tliq_id
 * @property string|null $tliq_nombre
 * @property float|null $tliq_porcentaje
 * @property int|null $tliq_usuario_ingreso
 * @property int|null $tliq_usuario_modifica
 * @property string|null $tliq_estado
 * @property string|null $tliq_fecha_creacion
 * @property string|null $tliq_fecha_modificacion
 * @property string|null $tliq_estado_logico
 *
 * @property Liquidaciones[] $liquidaciones
 */
class TipoLiquidacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_liquidacion';
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
            [['tliq_porcentaje'], 'number'],
            [['tliq_usuario_ingreso', 'tliq_usuario_modifica'], 'integer'],
            [['tliq_fecha_creacion', 'tliq_fecha_modificacion'], 'safe'],
            [['tliq_nombre'], 'string', 'max' => 200],
            [['tliq_estado', 'tliq_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tliq_id' => 'Tliq ID',
            'tliq_nombre' => financiero::t('tipoliquidacion', 'Liquidation Name'),
            'tliq_porcentaje' => financiero::t('tipoliquidacion', 'Percentage'),
            'tliq_usuario_ingreso' => 'Tliq Usuario Ingreso',
            'tliq_usuario_modifica' => 'Tliq Usuario Modifica',
            'tliq_estado' => 'Tliq Estado',
            'tliq_fecha_creacion' => 'Tliq Fecha Creacion',
            'tliq_fecha_modificacion' => 'Tliq Fecha Modificacion',
            'tliq_estado_logico' => 'Tliq Estado Logico',
        ];
    }

    /**
     * Gets query for [[Liquidaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiquidaciones()
    {
        return $this->hasMany(Liquidaciones::className(), ['tliq_id' => 'tliq_id']);
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
            $str_search .= "(tliq_nombre like :search) AND ";
        }
        $cols = "tliq_id as Id, tliq_nombre as Nombre, tliq_porcentaje as Porcentaje, tliq_fecha_creacion as Fcreacion";
        if($export) $cols = "tliq_nombre as Nombre, tliq_fecha_creacion as Fcreacion";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".tipo_liquidacion
                WHERE 
                    $str_search
                    tliq_estado = 1 AND tliq_estado_logico = 1
                ORDER BY tliq_id;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
        foreach($result as $key => $value){
            $result[$key]['Fcreacion'] = date(Yii::$app->params['dateByDefault'], strtotime($value['Fcreacion']));
        }
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
