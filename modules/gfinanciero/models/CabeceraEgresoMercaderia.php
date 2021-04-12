<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "IG0027".
 *
 * @property string $COD_BOD
 * @property string $NUM_EGR
 * @property string|null $NUM_B_T
 * @property string|null $FEC_EGR
 * @property string|null $COD_CLI
 * @property string|null $NOM_CLI
 * @property float|null $T_I_EGR
 * @property float|null $T_P_EGR
 * @property float|null $TOT_COS
 * @property string|null $LIN_N01
 * @property string|null $LIN_N02
 * @property string|null $LIN_N03
 * @property string|null $LIN_N04
 * @property string|null $LIN_N05
 * @property string|null $IND_UPD
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $TIP_EGR
 * @property string|null $TIP_B_T
 * @property string|null $COD_B_T
 * @property string|null $GUI_REM
 * @property string|null $NUM_ORT
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property DetalleEgresoMercaderia[] $iG0028s
 */
class CabeceraEgresoMercaderia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'IG0027';
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
            [['COD_BOD', 'NUM_EGR', 'TIP_EGR'], 'required'],
            [['FEC_EGR', 'FEC_SIS'], 'safe'],
            [['T_I_EGR', 'T_P_EGR', 'TOT_COS'], 'number'],
            [['COD_BOD', 'TIP_EGR', 'TIP_B_T', 'COD_B_T'], 'string', 'max' => 2],
            [['NUM_EGR', 'NUM_B_T', 'COD_CLI', 'HOR_SIS', 'GUI_REM', 'NUM_ORT'], 'string', 'max' => 10],
            [['NOM_CLI'], 'string', 'max' => 70],
            [['LIN_N01', 'LIN_N02', 'LIN_N03', 'LIN_N04', 'LIN_N05'], 'string', 'max' => 150],
            [['IND_UPD', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['COD_BOD', 'NUM_EGR', 'TIP_EGR'], 'unique', 'targetAttribute' => ['COD_BOD', 'NUM_EGR', 'TIP_EGR']],
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
            'NUM_B_T' => 'Num B T',
            'FEC_EGR' => 'Fec Egr',
            'COD_CLI' => 'Cod Cli',
            'NOM_CLI' => 'Nom Cli',
            'T_I_EGR' => 'T I Egr',
            'T_P_EGR' => 'T P Egr',
            'TOT_COS' => 'Tot Cos',
            'LIN_N01' => 'Lin N01',
            'LIN_N02' => 'Lin N02',
            'LIN_N03' => 'Lin N03',
            'LIN_N04' => 'Lin N04',
            'LIN_N05' => 'Lin N05',
            'IND_UPD' => 'Ind Upd',
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'TIP_EGR' => 'Tip Egr',
            'TIP_B_T' => 'Tip B T',
            'COD_B_T' => 'Cod B T',
            'GUI_REM' => 'Gui Rem',
            'NUM_ORT' => 'Num Ort',
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[IG0028s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleEgresoMercaderias()
    {
        return $this->hasMany(DetalleEgresoMercaderia::className(), ['COD_BOD' => 'COD_BOD', 'NUM_EGR' => 'NUM_EGR']);
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
            $str_search .= "((EG.NOM_CLI like :search) OR ";
            $str_search .= "(EG.NUM_EGR like :search)) AND ";
        }
        if(isset($tipo) && $tipo !== "0"){
            if(strtoupper($tipo) == "IN")
                $str_search .= " EG.TIP_B_T = :tipo AND "; // Transferencia
        }
        $cols = "EG.COD_BOD as CodBodegaOrigen, BG.NOM_BOD as BodegaOrigen, EG.NUM_EGR as NumEgreso, EG.TIP_EGR as Tipo, EG.FEC_EGR as Fecha,
        EG.COD_CLI as CodCliente, EG.NOM_CLI as NomCliente, EG.T_I_EGR as CantEgresado, EG.TOT_COS as TotalEgresado,
        IFNULL(EG.COD_B_T, '-') as CodBodegaDst, IFNULL(BD.NOM_BOD, '-') as BodegaDst, IFNULL(EG.NUM_B_T, '-') as NumTransfer, EG.IND_UPD as Estado";
        if($export) $cols = "EG.COD_BOD as CodBodegaOrigen, BG.NOM_BOD as BodegaOrigen, EG.TIP_EGR as Tipo, EG.NUM_EGR as NumEgreso, EG.FEC_EGR as Fecha,
        EG.COD_CLI as CodCliente, EG.NOM_CLI as NomCliente, EG.TOT_COS as TotalEgresado,
        IFNULL(EG.COD_B_T, '-') as CodBodegaDst, IFNULL(BD.NOM_BOD, '-') as BodegaDst, IFNULL(EG.NUM_B_T, '-') as NumTransfer";
        $sql = "SELECT
                    $cols
                FROM
                    ".$con->dbname.".IG0027 AS EG
                    INNER JOIN ".$con->dbname.".IG0021 AS BG ON BG.COD_BOD = EG.COD_BOD
                    LEFT JOIN ".$con->dbname.".IG0021 AS BD ON BD.COD_BOD = EG.COD_B_T AND BD.EST_LOG = 1 AND BD.EST_DEL = 1 
                WHERE
                    $str_search
                    EG.EST_LOG = 1 AND EG.EST_DEL = 1 AND 
                    BG.EST_LOG = 1 AND BG.EST_DEL = 1 
                ORDER BY EG.FEC_EGR DESC, EG.NUM_EGR DESC;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($tipo) && $tipo !== "0"){
            if(strtoupper($tipo) == "IN")
                $comando->bindParam(":tipo",$tipo, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
        $currency = Yii::$app->params['currency'];
        foreach($result as $key => $value){
            $result[$key]['TotalEgresado'] = $currency . number_format($value['TotalEgresado'], 2, '.', ',');
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
            'EG' => financiero::t('egresomercaderia', 'Merchandise Egress'),
            'IN' => financiero::t('egresomercaderia', 'Cellar Transfer'),
        ];
    }
}
