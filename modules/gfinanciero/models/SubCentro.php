<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "COSSUBCEN".
 *
 * @property string $COD_SCEN
 * @property string $COD_CEN
 * @property string|null $NOM_SCEN
 * @property string $FEC_CRE
 * @property string|null $FEC_MOD
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property COSCENTRO $cODCEN
 * @property SubcentroEmpleado[] $subcentroEmpleados
 */
class SubCentro extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'COSSUBCEN';
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
            [['COD_SCEN', 'COD_CEN'], 'required'],
            [['FEC_CRE', 'FEC_MOD'], 'safe'],
            [['COD_SCEN', 'EQUIPO'], 'string', 'max' => 15],
            [['COD_CEN'], 'string', 'max' => 10],
            [['NOM_SCEN'], 'string', 'max' => 100],
            [['USUARIO'], 'string', 'max' => 250],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_SCEN'], 'unique'],
            [['COD_CEN'], 'exist', 'skipOnError' => true, 'targetClass' => Centro::className(), 'targetAttribute' => ['COD_CEN' => 'COD_CEN']],
        ];
        
        
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
            
        return [
            'COD_SCEN' => financiero::t('centro', 'Sub Center Code'),
            'COD_CEN' => financiero::t('centro', 'Center Code'),
            'NOM_SCEN' => financiero::t('centro', 'Sub Center Name'),
            'FEC_CRE' => financiero::t('gfinanciero', 'Creation Date'),
            'FEC_MOD' => financiero::t('gfinanciero', 'Modification Date'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[CODCEN]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODCEN()
    {
        return $this->hasOne(COSCENTRO::className(), ['COD_CEN' => 'COD_CEN']);
    }

    /**
     * Gets query for [[SubcentroEmpleados]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubcentroEmpleados()
    {
        return $this->hasMany(SubcentroEmpleado::className(), ['cod_scen' => 'COD_SCEN']);
    }
    
    /**
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Item Name
     * @param  string $centro   Search Center Name
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
    public function getAllItemsGrid($search, $centro = NULL, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(CS.NOM_SCEN like :search) AND ";
        }
        if(isset($centro) && $centro !== "0"){
            $str_search .= " C.COD_CEN = :centro AND ";
        }
        $cols = "CS.COD_SCEN as Id, CS.NOM_SCEN as Nombre, C.NOM_CEN as NombreCentro";
        if($export) $cols = "CS.COD_SCEN as Id, CS.NOM_SCEN as Nombre, C.NOM_CEN as NombreCentro";
        $sql = "SELECT
                    $cols
                FROM
                    ".$con->dbname.".COSSUBCEN AS CS
                    INNER JOIN ".$con->dbname.".COSCENTRO AS C ON CS.COD_CEN = C.COD_CEN
                WHERE
                    $str_search
                    CS.EST_LOG = 1 AND CS.EST_DEL = 1 AND 
                    C.EST_LOG = 1 AND C.EST_DEL = 1
                ORDER BY CS.COD_SCEN;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($centro) && $centro !== "0"){
            $comando->bindParam(":centro",$centro, \PDO::PARAM_STR);
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
        $row = self::find()->select(['COD_SCEN'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_SCEN' => SORT_DESC])->one();
        return $row['COD_SCEN'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['COD_SCEN'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_SCEN' => SORT_DESC])->one();
        $newId = 1 + $row['COD_SCEN'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }   
    
    
}
