<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "TIPO_ITEM".
 *
 * @property int $tite_id
 * @property string|null $tite_nombre
 * @property int|null $tite_usuario_ingreso
 * @property int|null $tite_usuario_modifica
 * @property string|null $tite_estado
 * @property string|null $tite_equipo
 * @property string|null $tite_fecha_creacion
 * @property string|null $tite_fecha_modificacion
 * @property string|null $tite_estado_logico
 */
class TipoItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'TIPO_ITEM';
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
            [['TITE_USUARIO_INGRESO', 'TITE_USUARIO_MODIFICA'], 'integer'],
            [['TITE_FECHA_CREACION', 'TITE_FECHA_MODIFICACION'], 'safe'],
            [['TITE_NOMBRE'], 'string', 'max' => 200],
            [['TITE_ESTADO', 'TITE_ESTADO_LOGICO'], 'string', 'max' => 1],
            [['TITE_EQUIPO'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
          'TITE_ID' => financiero::t('tipoitem', 'Type Item Code'),
          'TITE_NOMBRE' => financiero::t('tipoitem', 'Type Item Name'),
          'TITE_USUARIO_INGRESO' => financiero::t('gfinanciero', 'User Creates'),
          'TITE_USUARIO_MODIFICA' => financiero::t('gfinanciero', 'User Modifies'),
          'TITE_ESTADO' => financiero::t('gfinanciero', 'Status'),
          'TITE_EQUIPO' => financiero::t('gfinanciero', 'Computer'),
          'TITE_FECHA_CREACION' => financiero::t('gfinanciero', 'Creation Date'),
          'TITE_FECHA_MODIFICACION' => financiero::t('gfinanciero', 'Modification Date'),
          'TITE_ESTADO_LOGICO' => financiero::t('gfinanciero', 'Logic Status'),

        ];
    }

    /**
     * Gets query for [[COSSUBCENs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCOSSUBCENs()
    {
        return $this->hasMany(COSSUBCEN::className(), ['TITE_ID' => 'TITE_ID']);
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
            $str_search .= "(TITE_NOMBRE like :search) AND ";
        }
        $cols = "TITE_ID as Id, TITE_NOMBRE as Nombre";
        if($export) $cols = "TITE_ID as Id, TITE_NOMBRE as Nombre";
        $sql = "SELECT
                    $cols
                FROM
                    ".$con->dbname.".TIPO_ITEM
                WHERE
                    $str_search
                    TITE_ESTADO_LOGICO = 1 AND TITE_ESTADO = 1
                ORDER BY TITE_ID;";
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
        $row = self::find()->select(['TITE_ID'])->where(['TITE_ESTADO_LOGICO' => '1', 'TITE_ESTADO' => '1'])->orderBy(['TITE_ID' => SORT_DESC])->one();
        return $row['TITE_ID'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['TITE_ID'])->where(['TITE_ESTADO_LOGICO' => '1', 'TITE_ESTADO' => '1'])->orderBy(['TITE_ID' => SORT_DESC])->one();
        $newId = 1 + $row['TITE_ID'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
}
