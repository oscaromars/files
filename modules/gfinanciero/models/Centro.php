<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "COSCENTRO".
 *
 * @property string $COD_CEN
 * @property string|null $NOM_CEN
 * @property string $FEC_CRE
 * @property string|null $FEC_MOD
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property COSSUBCEN[] $cOSSUBCENs
 */
class Centro extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'COSCENTRO';
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
            [['COD_CEN'], 'required'],
            [['FEC_CRE', 'FEC_MOD'], 'safe'],
            [['COD_CEN'], 'string', 'max' => 20],
            [['NOM_CEN'], 'string', 'max' => 100],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_CEN'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_CEN' => financiero::t('centro', 'Code'),
            'NOM_CEN' => financiero::t('centro', 'Center Name'),
            'FEC_CRE' => financiero::t('gfinanciero', 'Creation Date'),
            'FEC_MOD' => financiero::t('gfinanciero', 'Modification Date'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[COSSUBCENs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCOSSUBCENs()
    {
        return $this->hasMany(COSSUBCEN::className(), ['COD_CEN' => 'COD_CEN']);
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
            $str_search .= "(NOM_CEN like :search) AND ";
        }
        $cols = "COD_CEN as Id, NOM_CEN as Nombre";
        if($export) $cols = "COD_CEN as Id, NOM_CEN as Nombre";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".COSCENTRO
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY COD_CEN;";
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
        $row = self::find()->select(['COD_CEN'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_CEN' => SORT_DESC])->one();
        return $row['COD_CEN'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['COD_CEN'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_CEN' => SORT_DESC])->one();
        $newId = 1 + $row['COD_CEN'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
}
