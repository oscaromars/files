<?php

namespace app\modules\gfinanciero\models;

use Yii;

use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;
use Exception;

financiero::registerTranslations();


/**
 * This is the model class for table "IG0021".
 *
 * @property string $COD_BOD
 * @property string|null $NOM_BOD
 * @property string|null $DIR_BOD
 * @property string|null $COD_PAI
 * @property string|null $COD_CIU
 * @property string|null $TEL_N01
 * @property string|null $TEL_N02
 * @property string|null $NUM_FAX
 * @property string|null $CORRE_E
 * @property string|null $COD_RES
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string|null $NUM_ING
 * @property string|null $NUM_EGR
 * @property string|null $COD_PTO
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property ExistenciaBodega[] $iG0022s
 * @property ExistenciaBodega[] $cODARTs
 */
class Bodega extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'IG0021';
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
            [['COD_BOD'], 'required'],
            [['REG_ASO'], 'number'],
            [['FEC_SIS'], 'safe'],
            [['COD_BOD', 'COD_PAI', 'COD_CIU'], 'string', 'max' => 2],
            [['NOM_BOD', 'CORRE_E'], 'string', 'max' => 30],
            [['DIR_BOD'], 'string', 'max' => 40],
            [['TEL_N01', 'TEL_N02'], 'string', 'max' => 9],
            [['NUM_FAX'], 'string', 'max' => 20],
            [['COD_RES', 'HOR_SIS', 'NUM_ING', 'NUM_EGR'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['COD_PTO'], 'string', 'max' => 3],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_BOD'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_BOD' => financiero::t('bodega', 'Code'),
            'NUM_ING' => financiero::t('bodega', 'Income Number'),
            'NUM_EGR' => financiero::t('bodega', 'Egress Number'),
            'COD_PTO' => financiero::t('bodega', 'Emission Point'),
            'NOM_BOD' => financiero::t('bodega', 'Cellar'),
            'DIR_BOD' => financiero::t('bodega', 'Address'),
            'COD_PAI' => financiero::t('bodega', 'Country'),
            'COD_CIU' => financiero::t('bodega', 'City'),
            'TEL_N01' => financiero::t('bodega', 'Phone'),
            'TEL_N02' => financiero::t('bodega', 'Phone'),
            'NUM_FAX' => 'Num Fax',
            'CORRE_E' => financiero::t('bodega', 'Mail'),
            'COD_RES' => financiero::t('bodega', 'Responsable'),
            'REG_ASO' => 'Reg Aso',
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),            
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[IG0022s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0022s()
    {
        return $this->hasMany(ExistenciaBodega::className(), ['COD_BOD' => 'COD_BOD']);
    }

    /**
     * Gets query for [[CODARTs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODARTs()
    {
        return $this->hasMany(ExistenciaBodega::className(), ['COD_ART' => 'COD_ART'])->viaTable('IG0022', ['COD_BOD' => 'COD_BOD']);
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
            $str_search .= "(NOM_BOD like :search) AND ";
        }
        $cols  = "COD_BOD as Id, ";
        $cols .= "NOM_BOD as Nombre, ";
        $cols .= "DIR_BOD as Direccion, ";
        $cols .= "COD_PTO as Punto ";
       
        if($export) $cols = "COD_BOD,NOM_BOD,DIR_BOD,TEL_N01,CORRE_E,NUM_ING,NUM_EGR ";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".IG0021  
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY COD_BOD;";
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
                    'attributes' => ['Nombre','Direccion','Punto'],
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
    public static function getLastId(){
        $row = Bodega::find()->select(['COD_BOD'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['cod_bod' => SORT_DESC])->one();
        return ($row<>"")?intval($row['COD_BOD'])+1:1;
    }
    
     /**
     * Return columns to dataset of create a query to widget Search.
     * 
     * @param  bool $getDirection   Get Direction in query response
     * @return mixed Return a Record Array
     */
    public static function getDataColumnsQueryWidget($getDirection = false){
        $arr_data = [];
        $arr_data['con'] = Yii::$app->db_gfinanciero;
        $arr_data['table'] = "IG0021";
        $arr_data['cols'] = [
            'COD_BOD', 
            'NOM_BOD',
        ];
        $arr_data['aliasCols'] = [
            financiero::t('bodega', 'Code'), 
            financiero::t('bodega', 'Item'),
        ];
        $arr_data['colVisible'] = [
            financiero::t('bodega', 'Code'), 
            financiero::t('bodega', 'Item'),
        ];
        $arr_data['where'] = "EST_LOG = 1 and EST_DEL = 1";
        $arr_data['order'] = "COD_BOD ASC";
        $arr_data['limitPages'] = Yii::$app->params['pageSize'];

        if($getDirection){
            $arr_data['cols'][] = "DIR_BOD";
            $arr_data['aliasCols'][] = financiero::t('bodega', 'Address');
        }
        return $arr_data;
    }
    
    /**
     * Return columns to dataset of create a query to widget Search.
     *
     * @return mixed Return a Record Array
     */
    public static function getBodegas() {
        $con = \Yii::$app->db_gfinanciero;
        $sql = "SELECT COD_BOD Ids,NOM_BOD Nombre 
                    FROM " . $con->dbname . ".IG0021
                WHERE EST_LOG=1 AND EST_DEL=1 ";
        $comando = $con->createCommand($sql);
        //$comando->bindParam(":esp_id", $Ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }
    
    /**
     * Return columns to dataset of create a query to widget Search.
     *
     * @return mixed Return a Record Array
     */
    public static function existeItemBodega($CodBod,$CodArt){
        $con = \Yii::$app->db_gfinanciero;        
        $sql = "SELECT COD_ART,F_I_FIS FROM " . $con->dbname . ".IG0022 
                   WHERE COD_ART=:CodArt AND COD_BOD=:CodBod ;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":CodBod", $CodBod, \PDO::PARAM_STR);
        $comando->bindParam(":CodArt", $CodArt, \PDO::PARAM_STR);
        $rawData=$comando->queryAll();
        if(count($rawData) > 0)
            return $rawData; 
        return 0;//Retonra 0 si no existe
    }

    /** 
     * Function to return secuence of Cellar
     * 
     * @param   string  $code   Code Cellar
     * @param   string  $type   Type Transaction. Default EG = Egress. May Be IN = Ingress
     * @param   bool    $executeSecuence    True update or insert secuence and return, False only return next secuence.
     * @return string Return a Next Secuence
     */
    
    public static function newSecuence($code, $type = 'EG', $executeSecuence = false) {
        $numero = 0;
        $strPad = 10;
        try{
            $con = \Yii::$app->db_gfinanciero; 
            $fieldSec = "NUM_EGR";
            if($type == "IN")   $fieldSec = "NUM_ING";

            $sql = "
                SELECT 
                    IFNULL(CAST($fieldSec AS UNSIGNED), 0) secuencia 
                FROM 
                    " . $con->dbname . ".IG0021 
                WHERE 
                    COD_BOD = :code AND
                    EST_LOG = 1 AND EST_DEL = 1";
            if($executeSecuence)   $sql .= " FOR UPDATE ";                 
            
            $comando = $con->createCommand($sql);
            $comando->bindParam(":code", $code, \PDO::PARAM_STR);
            $rawData = $comando->queryScalar();

            // This not apply 
            /*if ($rawData == false) { // No existe secuencia
                $numero = str_pad(0, $strPad, "0", STR_PAD_LEFT);
                if(!$executeSecuence)   return $numero;
                $sql = " 
                    INSERT INTO " . $con->dbname . ".IG0021 "
                . "() "
                . "VALUES() ";
                $comando = $con->createCommand($sql);
                $rawData = $comando->execute();   
                return $numero;      
            }*/

            if ($rawData !== false){ // Existe Secuencia
                $numero = str_pad( (int) $rawData + 1, $strPad, "0", STR_PAD_LEFT);
                if(!$executeSecuence)   return $numero;
                $sql = "
                    UPDATE 
                        " . $con->dbname . ".IG0021 
                    SET 
                        $fieldSec = :secuencia 
                    WHERE 
                        COD_BOD = :code AND
                        EST_LOG = 1 AND EST_DEL = 1 ";
                
                $comando = $con->createCommand($sql);
                $comando->bindParam(":code", $code, \PDO::PARAM_STR);
                $comando->bindParam(":secuencia", $numero, \PDO::PARAM_STR);
                $rawData = $comando->execute();  
                return $numero;       
            }
        }catch(Exception $e){
            return 0;
        }
    }    
}
