<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();
/**
 * This is the model class for table "IG0025".
 *
 * @property string $COD_BOD
 * @property string $NUM_ING
 * @property string|null $FEC_ING
 * @property string|null $COD_PRO
 * @property string|null $NOM_PRO
 * @property float|null $T_I_ING
 * @property float|null $T_P_ING
 * @property float|null $TOT_COS
 * @property string|null $LIN_N01
 * @property string|null $IND_UPD
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $TIP_ING
 * @property string|null $TIP_B_T
 * @property string|null $NUM_B_T
 * @property string|null $COD_B_T
 * @property string|null $LIN_N02
 * @property string|null $LIN_N03
 * @property string|null $LIN_N04
 * @property string|null $LIN_N05
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property DetalleIngresoMercaderia[] $DetalleIngresoMercaderia
 */
class CabeceraIngresoMercaderia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'IG0025';
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
            [['COD_BOD', 'NUM_ING', 'TIP_ING'], 'required'],
            [['FEC_ING', 'FEC_SIS'], 'safe'],
            [['T_I_ING', 'T_P_ING', 'TOT_COS'], 'number'],
            [['COD_BOD', 'TIP_ING', 'TIP_B_T', 'COD_B_T'], 'string', 'max' => 2],
            [['NUM_ING', 'COD_PRO', 'HOR_SIS', 'NUM_B_T'], 'string', 'max' => 10],
            [['NOM_PRO'], 'string', 'max' => 70],
            [['LIN_N01'], 'string', 'max' => 120],
            [['IND_UPD', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['LIN_N02', 'LIN_N03', 'LIN_N04', 'LIN_N05'], 'string', 'max' => 150],
            [['COD_BOD', 'NUM_ING', 'TIP_ING'], 'unique', 'targetAttribute' => ['COD_BOD', 'NUM_ING', 'TIP_ING']],
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
            'NOM_PRO' => 'Nom Pro',
            'T_I_ING' => 'T I Ing',
            'T_P_ING' => 'T P Ing',
            'TOT_COS' => 'Tot Cos',
            'LIN_N01' => 'Lin N01',
            'IND_UPD' => 'Ind Upd',
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'TIP_ING' => 'Tip Ing',
            'TIP_B_T' => 'Tip B T',
            'NUM_B_T' => 'Num B T',
            'COD_B_T' => 'Cod B T',
            'LIN_N02' => 'Lin N02',
            'LIN_N03' => 'Lin N03',
            'LIN_N04' => 'Lin N04',
            'LIN_N05' => 'Lin N05',
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[DetalleIngresoMercaderias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleIngresoMercaderias()
    {
        return $this->hasMany(DetalleIngresoMercaderia::className(), ['COD_BOD' => 'COD_BOD', 'NUM_ING' => 'NUM_ING']);
    }
    
    
    /**
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Client Name
     * @param  string $tipo   Search Type Egress
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @param  bool $export   Param to export data Report
     * @return mixed Return a Record Array or DataProvider
     */
    public function getAllItemsGrid($search, $tipo = NULL, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "((IG.NOM_PRO like :search) OR ";
            $str_search .= "(IG.NUM_ING like :search)) AND ";
        }
       
        $cols = "IG.COD_BOD as CodBodegaOrigen, BG.NOM_BOD as BodegaOrigen, IG.NUM_ING as NumIngreso, IG.TIP_ING as Tipo, IG.FEC_ING as Fecha,
                    IG.COD_PRO as CodProveedor, IG.NOM_PRO as NomProveedor, IG.T_I_ING as CantIngresadas, IG.TOT_COS as TotalIngresado,IG.IND_UPD as Estado ";
        
        if($export) $cols = "IG.COD_BOD as CodBodegaOrigen, BG.NOM_BOD as BodegaOrigen, IG.NUM_ING as NumIngreso, IG.TIP_ING as Tipo, IG.FEC_ING as Fecha,
                    IG.COD_PRO as CodProveedor, IG.NOM_PRO as NomProveedor, IG.T_I_ING as CantIngresadas, IG.TOT_COS as TotalIngresado,IG.IND_UPD as Estado ";
        
        $sql = "SELECT
                    $cols
                FROM
                    ".$con->dbname.".IG0025 AS IG
                    INNER JOIN ".$con->dbname.".IG0021 AS BG ON BG.COD_BOD = IG.COD_BOD                    
                WHERE
                    $str_search
                    IG.EST_LOG = 1 AND IG.EST_DEL = 1 
                ORDER BY IG.FEC_ING DESC, IG.NUM_ING DESC;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        
        $result = $comando->queryAll();
        $currency = Yii::$app->params['currency'];
        foreach($result as $key => $value){
            $result[$key]['TotalIngresado'] = $currency . number_format($value['TotalIngresado'], 2, '.', ',');
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
    
    public static function getEgressTypes(){
        return [
            'IN' => financiero::t('ingresomercaderia', 'Merchandise Entry'),
        ];
    }

    
}
