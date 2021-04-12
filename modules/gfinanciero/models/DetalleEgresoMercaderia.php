<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "IG0028".
 *
 * @property string $COD_BOD
 * @property string $NUM_EGR
 * @property string|null $FEC_EGR
 * @property string|null $COD_CLI
 * @property string $COD_ART
 * @property float|null $CAN_PED
 * @property float|null $CAN_DEV
 * @property float|null $CAN_FAC
 * @property float|null $P_COSTO
 * @property float|null $P_LISTA
 * @property string|null $IND_EST
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $TIP_EGR
 * @property string|null $COD_B_T
 * @property string|null $TIP_B_T
 * @property string|null $NUM_B_T
 * @property string|null $NUM_ORT
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property CabeceraEgresoMercaderia $cODBOD
 */
class DetalleEgresoMercaderia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'IG0028';
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
            [['COD_BOD', 'NUM_EGR', 'COD_ART', 'TIP_EGR'], 'required'],
            [['FEC_EGR', 'FEC_SIS'], 'safe'],
            [['CAN_PED', 'CAN_DEV', 'CAN_FAC', 'P_COSTO', 'P_LISTA'], 'number'],
            [['COD_BOD', 'TIP_EGR', 'COD_B_T', 'TIP_B_T'], 'string', 'max' => 2],
            [['NUM_EGR', 'COD_CLI', 'HOR_SIS', 'NUM_B_T', 'NUM_ORT'], 'string', 'max' => 10],
            [['COD_ART'], 'string', 'max' => 20],
            [['IND_EST', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['COD_BOD', 'NUM_EGR', 'COD_ART', 'TIP_EGR'], 'unique', 'targetAttribute' => ['COD_BOD', 'NUM_EGR', 'COD_ART', 'TIP_EGR']],
            [['COD_BOD', 'NUM_EGR'], 'exist', 'skipOnError' => true, 'targetClass' => CabeceraEgresoMercaderia::className(), 'targetAttribute' => ['COD_BOD' => 'COD_BOD', 'NUM_EGR' => 'NUM_EGR']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_BOD' => 'Cod Bod',
            'NUM_EGR' => 'Num Egr',
            'FEC_EGR' => 'Fec Egr',
            'COD_CLI' => 'Cod Cli',
            'COD_ART' => 'Cod Art',
            'CAN_PED' => 'Can Ped',
            'CAN_DEV' => 'Can Dev',
            'CAN_FAC' => 'Can Fac',
            'P_COSTO' => 'P Costo',
            'P_LISTA' => 'P Lista',
            'IND_EST' => 'Ind Est',
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'TIP_EGR' => 'Tip Egr',
            'COD_B_T' => 'Cod B T',
            'TIP_B_T' => 'Tip B T',
            'NUM_B_T' => 'Num B T',
            'NUM_ORT' => 'Num Ort',
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
        return $this->hasOne(CabeceraEgresoMercaderia::className(), ['COD_BOD' => 'COD_BOD', 'NUM_EGR' => 'NUM_EGR']);
    }

    /**
     * Get all items Cellar by Egress Transaction.
     *
     * @param  string $codBod   Cellar Code
     * @param  string $numSec   Secuence of Cellar
     * @param  string $type     Type Egress or Ingress. Default "EG". Other Values "IN"
     * @param  string $status   Type Status. Default "L". Other Values "A"
     * @param  string $typeTrans By defualt is NULL, but if is a transfer value must be "IN"
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array
     */
    public static function getAllArticlesByEgress($codBod, $numSec, $type = "EG", $status = "L", $typeTrans = NULL, $dataProvider = false){
        $con = Yii::$app->db_gfinanciero;
        $search_cond = "";
        if(isset($typeTrans)){
            $search_cond .= " TIP_B_T = :typeTrans AND ";
        }
        $sql = "SELECT
                    DE.COD_BOD AS CodBodOrg,
                    DE.NUM_EGR AS SecBodOrg,
                    DE.FEC_EGR AS FechaEgreso,
                    DE.COD_CLI AS CodCli,
                    DE.COD_ART AS CodArt,
                    A.DES_COM AS NomArt,
                    DE.CAN_PED AS CantItems,
                    DE.P_COSTO AS PReferencia,
                    DE.P_LISTA AS PProveedor,
                    (DE.CAN_PED * DE.P_COSTO) AS TCosto,
                    DE.IND_EST AS Estado,
                    DE.TIP_EGR AS Tipo,
                    DE.COD_B_T AS CodBodDst,
                    DE.TIP_B_T AS TipTrans,
                    DE.NUM_B_T AS SecBodDst
                FROM
                    ".$con->dbname.".IG0028 AS DE 
                    INNER JOIN ".$con->dbname.".IG0020 AS A ON DE.COD_ART = A.COD_ART
                WHERE
                    $search_cond
                    DE.TIP_EGR = :type AND 
                    DE.IND_EST = :status AND
                    DE.NUM_EGR = :numSec AND 
                    DE.COD_BOD = :codBod AND 
                    DE.EST_LOG = 1 AND DE.EST_DEL = 1 
                ORDER BY DE.FEC_EGR DESC;";

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
    public static function getItemsPrintByEgress($codBod, $numSec, $type = "EG"){
        $con = Yii::$app->db_gfinanciero;
        $search_cond = "";
        $sql = "SELECT
                    DE.COD_ART AS CodArt,
                    A.DES_COM AS NomArt,
                    DE.CAN_PED AS CantItems
                FROM
                    ".$con->dbname.".IG0028 AS DE 
                    INNER JOIN ".$con->dbname.".IG0020 AS A ON DE.COD_ART = A.COD_ART
                WHERE
                    $search_cond
                    DE.TIP_EGR = :type AND 
                    DE.NUM_EGR = :numSec AND 
                    DE.COD_BOD = :codBod AND 
                    DE.EST_LOG = 1 AND DE.EST_DEL = 1 
                ORDER BY DE.COD_ART DESC;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":type",$type, \PDO::PARAM_STR);
        $comando->bindParam(":numSec",$numSec, \PDO::PARAM_STR);
        $comando->bindParam(":codBod",$codBod, \PDO::PARAM_STR);
        $result = $comando->queryAll();
        return $result;
    }
}
