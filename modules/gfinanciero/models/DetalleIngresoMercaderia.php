<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();
/**
 * This is the model class for table "IG0026".
 *
 * @property string $COD_BOD
 * @property string $NUM_ING
 * @property string|null $FEC_ING
 * @property string $COD_PRO
 * @property string $COD_ART
 * @property float|null $CAN_ANT
 * @property float|null $CAN_PED
 * @property float|null $CAN_DEV
 * @property float|null $C_ANTER
 * @property float|null $P_COSTO
 * @property float|null $P_LISTA
 * @property float|null $T_COSTO
 * @property string|null $IND_EST
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $TIP_ING
 * @property string|null $TIP_B_T
 * @property string|null $NUM_B_T
 * @property string|null $COD_B_T
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property CabeceraIngresoMercaderia $cODBOD
 */
class DetalleIngresoMercaderia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'IG0026';
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
            [['COD_BOD', 'NUM_ING', 'COD_PRO', 'COD_ART', 'TIP_ING'], 'required'],
            [['FEC_ING', 'FEC_SIS'], 'safe'],
            [['CAN_ANT', 'CAN_PED', 'CAN_DEV', 'C_ANTER', 'P_COSTO', 'P_LISTA', 'T_COSTO'], 'number'],
            [['COD_BOD', 'TIP_ING', 'TIP_B_T', 'COD_B_T'], 'string', 'max' => 2],
            [['NUM_ING', 'COD_PRO', 'HOR_SIS', 'NUM_B_T'], 'string', 'max' => 10],
            [['COD_ART'], 'string', 'max' => 20],
            [['IND_EST', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['COD_BOD', 'NUM_ING', 'COD_PRO', 'COD_ART', 'TIP_ING'], 'unique', 'targetAttribute' => ['COD_BOD', 'NUM_ING', 'COD_PRO', 'COD_ART', 'TIP_ING']],
            [['COD_BOD', 'NUM_ING'], 'exist', 'skipOnError' => true, 'targetClass' => CabeceraIngresoMercaderia::className(), 'targetAttribute' => ['COD_BOD' => 'COD_BOD', 'NUM_ING' => 'NUM_ING']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_BOD' => 'Cod Bod',
            'NUM_ING' => 'Num Ing',
            'FEC_ING' => 'Fec Ing',
            'COD_PRO' => 'Cod Pro',
            'COD_ART' => 'Cod Art',
            'CAN_ANT' => 'Can Ant',
            'CAN_PED' => 'Can Ped',
            'CAN_DEV' => 'Can Dev',
            'C_ANTER' => 'C Anter',
            'P_COSTO' => 'P Costo',
            'P_LISTA' => 'P Lista',
            'T_COSTO' => 'T Costo',
            'IND_EST' => 'Ind Est',
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'TIP_ING' => 'Tip Ing',
            'TIP_B_T' => 'Tip B T',
            'NUM_B_T' => 'Num B T',
            'COD_B_T' => 'Cod B T',
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[CODBOD]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODBOD()
    {
        return $this->hasOne(CabeceraIngresoMercaderia::className(), ['COD_BOD' => 'COD_BOD', 'NUM_ING' => 'NUM_ING']);
    }
    
     /**
     * Get all items Cellar by Entry Transaction.
     *
     * @param  string $codBod   Cellar Code
     * @param  string $numSec   Secuence of Cellar
     * @param  string $type     Type Egress or Ingress. Default "EG". Other Values "IN"
     * @param  string $status   Type Status. Default "L". Other Values "A"
     * @param  string $typeTrans By defualt is NULL, but if is a transfer value must be "IN"
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array
     */
    public static function getAllArticlesByEntry($codBod, $numSec, $type = "IN", $status = "L", $typeTrans = NULL, $dataProvider = false){
        $con = Yii::$app->db_gfinanciero;
        $search_cond = "";
        if(isset($typeTrans)){
            $search_cond .= " TIP_B_T = :typeTrans AND ";
        }
        $sql = "SELECT
                    DI.COD_BOD AS CodBodOrg,
                    DI.NUM_ING AS SecBodOrg,
                    DI.FEC_ING AS FechaIngreso,
                    DI.COD_PRO AS CodPro,
                    DI.COD_ART AS CodArt,
                    A.DES_COM AS NomArt,
                    DI.CAN_PED AS CantItems,
                    DI.P_COSTO AS PReferencia,
                    DI.P_LISTA AS PProveedor,
                    (DI.CAN_PED * DI.P_COSTO) AS TCosto,
                    DI.IND_EST AS Estado,
                    DI.TIP_ING AS Tipo
                   
                FROM
                    ".$con->dbname.".IG0026 AS DI 
                    INNER JOIN ".$con->dbname.".IG0020 AS A ON DI.COD_ART = A.COD_ART
                WHERE
                    $search_cond
                    DI.TIP_ING = :type AND 
                    DI.IND_EST = :status AND
                    DI.NUM_ING = :numSec AND 
                    DI.COD_BOD = :codBod AND 
                    DI.EST_LOG = 1 AND DI.EST_DEL = 1 
                ORDER BY DI.FEC_ING DESC;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":type",$type, \PDO::PARAM_STR);
        $comando->bindParam(":status",$status, \PDO::PARAM_STR);
        $comando->bindParam(":numSec",$numSec, \PDO::PARAM_STR);
        $comando->bindParam(":codBod",$codBod, \PDO::PARAM_STR);
        if(isset($typeTrans)){
            $comando->bindParam(":typeTrans",$typeTrans, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
        $currency = Yii::$app->params['currency'];
        foreach($result as $key => $value){
            $result[$key]['PReferencia'] = $currency . number_format($value['PReferencia'], Yii::$app->params['numDecimals'], '.', ',');
            $result[$key]['PProveedor'] = $currency . number_format($value['PProveedor'], Yii::$app->params['numDecimals'], '.', ',');
            $result[$key]['TCosto'] = $currency . number_format($value['TCosto'], Yii::$app->params['numDecimals'], '.', ',');
        }
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $result,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['CodBodOrg'],
                ],
            ]);
            return $dataProvider;
        }
        return $result;
    }
    
    /**
     * Get all items Cellar by Egress Transaction to print.
     *
     * @param  string $codBod   Cellar Code
     * @param  string $numSec   Secuence of Cellar
     * @param  string $type     Type Egress or Ingress. Default "EG". Other Values "IN"
     * @return mixed Return a Record Array
     */
    public static function getItemsPrintByEntry($codBod, $numSec, $type = "IN"){
        $con = Yii::$app->db_gfinanciero;
        $search_cond = "";
        $sql = "SELECT
                    DI.COD_ART AS CodArt,
                    A.DES_COM AS NomArt,
                    DI.CAN_PED AS CantItems
                FROM
                    ".$con->dbname.".IG0026 AS DI 
                    INNER JOIN ".$con->dbname.".IG0020 AS A ON DI.COD_ART = A.COD_ART
                WHERE
                    $search_cond
                    DI.TIP_ING = :type AND 
                    DI.NUM_ING = :numSec AND 
                    DI.COD_BOD = :codBod AND 
                    DI.EST_LOG = 1 AND DI.EST_DEL = 1 
                ORDER BY DI.COD_ART DESC;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":type",$type, \PDO::PARAM_STR);
        $comando->bindParam(":numSec",$numSec, \PDO::PARAM_STR);
        $comando->bindParam(":codBod",$codBod, \PDO::PARAM_STR);
        $result = $comando->queryAll();
        return $result;
    }

    
}
