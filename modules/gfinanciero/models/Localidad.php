<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "MG0014".
 *
 * @property string $C_I_OCG
 * @property string $COD_OCG
 * @property string|null $NOM_OCG
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 */
class Localidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'MG0014';
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
            [['C_I_OCG', 'COD_OCG'], 'required'],
            [['REG_ASO'], 'number'],
            [['FEC_SIS'], 'safe'],
            [['C_I_OCG'], 'string', 'max' => 2],
            [['COD_OCG'], 'string', 'max' => 3],
            [['NOM_OCG'], 'string', 'max' => 200],
            [['HOR_SIS'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['C_I_OCG', 'COD_OCG'], 'unique', 'targetAttribute' => ['C_I_OCG', 'COD_OCG']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'C_I_OCG' => financiero::t('localidad', 'Location Type'),
            'COD_OCG' => financiero::t('localidad', 'Location Code'),
            'NOM_OCG' => financiero::t('localidad', 'Location Name'),
            'REG_ASO' => financiero::t('gfinanciero', 'Associated Register'),
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Item Name
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
    public function getAllItemsGrid($search, $tipo = NULL, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(NOM_OCG like :search) AND ";
        }
        if(isset($tipo) && $tipo != "0"){
            $str_search .= "C_I_OCG = :tipo AND ";
        }
        $cols = "C_I_OCG as Id, COD_OCG as CodLocalidad, NOM_OCG as Nombre, FEC_SIS as Fecha, EST_LOG as Estado";
        if($export) $cols = "C_I_OCG as Id, NOM_OCG as Nombre";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".MG0014
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY C_I_OCG;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($tipo) && $tipo != "0"){
            $comando->bindParam(":tipo",$tipo, \PDO::PARAM_STR);
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
                    'attributes' => ['Nombre', 'Estado'],
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
        $row = self::find()->select(['C_I_OCG'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['C_I_OCG' => SORT_DESC])->one();
        return $row['C_I_OCG'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['C_I_OCG'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['C_I_OCG' => SORT_DESC])->one();
        $newId = 1 + $row['C_I_OCG'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
}
