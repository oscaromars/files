<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;
/**
 * This is the model class for table "tipo_contrato".
 *
 * @property int $tipc_id
 * @property string|null $tipc_nombre
 * @property int|null $tipc_usuario_ingreso
 * @property int|null $tipc_usuario_modifica
 * @property string|null $tipc_estado
 * @property string|null $tipc_fecha_creacion
 * @property string|null $tipc_fecha_modificacion
 * @property string|null $tipc_estado_logico
 */
class TipoContrato extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_contrato';
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
            [['tipc_usuario_ingreso', 'tipc_usuario_modifica'], 'integer'],
            [['tipc_fecha_creacion', 'tipc_fecha_modificacion'], 'safe'],
            [['tipc_nombre'], 'string', 'max' => 200],
            [['tipc_estado', 'tipc_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tipc_id' => financiero::t('tipocontrato', 'Contract Code'),
            'tipc_nombre' => financiero::t('tipocontrato', 'Contract Name'),
            'tipc_usuario_ingreso' => financiero::t('gfinanciero', 'User Creates'),
            'tipc_usuario_modifica' => financiero::t('gfinanciero', 'User Modifies'),
            'tipc_estado' => financiero::t('gfinanciero', 'Status'),
            'tipc_fecha_creacion' => financiero::t('gfinanciero', 'Creation Date'),
            'tipc_fecha_modificacion' => financiero::t('gfinanciero', 'Modification Date'),
            'tipc_estado_logico' => financiero::t('gfinanciero', 'Logic Status'),
        ];
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
            $str_search .= "(tipc_nombre like :search) AND ";
        }
              
        $cols  = "tipc_id as Id, ";
        $cols .= "tipc_nombre as Nombre ";
        
        
        if($export) $cols = "tipc_nombre as Nombre ";
        $sql = "SELECT
                    $cols
                FROM
                    ".$con->dbname.".tipo_contrato
                WHERE
                    $str_search
                    tipc_estado_logico = 1 AND tipc_estado = 1
                ORDER BY tipc_id;";
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

    /**
     * Get Last Id Item Record
     *
     * @return void
     */
    public static function getLastIdItemRecord(){
        $row = self::find()->select(['tipc_id'])->where(['tipc_estado_logico' => '1', 'tipc_estado' => '1'])->orderBy(['tipc_id' => SORT_DESC])->one();
        return $row['tipc_id'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['tipc_id'])->where(['tipc_estado_logico' => '1', 'tipc_estado' => '1'])->orderBy(['tipc_id' => SORT_DESC])->one();
        $newId = 1 + $row['tipc_id'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
}

