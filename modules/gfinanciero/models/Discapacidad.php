<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "discapacidad".
 *
 * @property int $dis_id
 * @property string|null $dis_nombre
 * @property float|null $dis_porcentaje
 * @property int|null $dis_usuario_ingreso
 * @property int|null $dis_usuario_modifica
 * @property string|null $dis_estado
 * @property string|null $dis_fecha_creacion
 * @property string|null $dis_fecha_modificacion
 * @property string|null $dis_estado_logico
 */
class Discapacidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'discapacidad';
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
            [['dis_porcentaje'], 'number'],
            [['dis_usuario_ingreso', 'dis_usuario_modifica'], 'integer'],
            [['dis_fecha_creacion', 'dis_fecha_modificacion'], 'safe'],
            [['dis_nombre'], 'string', 'max' => 200],
            [['dis_estado', 'dis_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dis_id' => financiero::t('discapacidad', 'Disability Code'),
            'dis_nombre' => financiero::t('discapacidad', 'Disability Name'),
            'dis_porcentaje' => financiero::t('discapacidad', 'Percentage'),
            'dis_usuario_ingreso' => financiero::t('gfinanciero', 'User Creates'),
            'dis_usuario_modifica' => financiero::t('gfinanciero', 'User Modifies'),
            'dis_estado' => financiero::t('gfinanciero', 'Status'),
            'dis_fecha_creacion' => financiero::t('gfinanciero', 'Creation Date'),
            'dis_fecha_modificacion' => financiero::t('gfinanciero', 'Modification Date'),
            'dis_estado_logico' => financiero::t('gfinanciero', 'Logic Status'),
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
            $str_search .= "(dis_nombre like :search) AND ";
        }
              
        $cols  = "dis_id as Id, ";
        $cols .= "dis_nombre as Nombre, ";
        //$cols .= "dis_porcentaje as Porcentaje ";
        $cols .= "dis_fecha_creacion as Fcreacion ";
        
        if($export) $cols = "dis_nombre as Nombre, dis_fecha_creacion as Fcreacion ";
        $sql = "SELECT
                    $cols
                FROM
                    ".$con->dbname.".discapacidad
                WHERE
                    $str_search
                    dis_estado_logico = 1 AND dis_estado = 1
                ORDER BY dis_id;";
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

    /**
     * Get Last Id Item Record
     *
     * @return void
     */
    public static function getLastIdItemRecord(){
        $row = self::find()->select(['dis_id'])->where(['dis_estado_logico' => '1', 'dis_estado' => '1'])->orderBy(['dis_id' => SORT_DESC])->one();
        return $row['dis_id'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['dis_id'])->where(['dis_estado_logico' => '1', 'dis_estado' => '1'])->orderBy(['dis_id' => SORT_DESC])->one();
        $newId = 1 + $row['dis_id'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
}