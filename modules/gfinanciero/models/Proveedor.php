<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "MG0032".
 *
 * @property string $COD_PRO
 * @property string|null $CED_RUC
 * @property string|null $NOM_PRO
 * @property string|null $FEC_PRO
 * @property string|null $COD_PAI
 * @property string|null $COD_CIU
 * @property string|null $DIR_PRO
 * @property string|null $TEL_N01
 * @property string|null $TEL_N02
 * @property string|null $NUM_FAX
 * @property string|null $CORRE_E
 * @property string|null $TIP_PRO
 * @property string|null $C_TRA_E
 * @property float|null $LIM_CRE
 * @property float|null $LIM_DIA
 * @property float|null $POR_DES
 * @property string|null $CTA_BAN
 * @property string|null $COD_CTA
 * @property string|null $COD_VEN
 * @property string|null $NOM_CTO
 * @property string|null $TEL_CTO
 * @property string|null $COD_LIN
 * @property string|null $ACT_PRO
 * @property string|null $NOM_GER
 * @property string|null $NOM_SEC
 * @property string|null $OBSER01
 * @property string|null $N_U_PAG
 * @property string|null $N_S_PRO
 * @property string|null $N_A_PRO
 * @property string|null $FEC_CAD
 * @property string|null $COD_I_P
 * @property string|null $TIP_SER
 * @property string|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string|null $EST_PRO
 * @property string|null $CTA_IVA
 * @property float|null $P_R_IVA
 * @property string|null $IMG_PRO
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property CB0020[] $cB0020s
 * @property CP0002[] $cP0002s
 * @property CP0010[] $cP0010s
 * @property CP0011[] $cP0011s
 * @property IG0050[] $iG0050s
 * @property IG0051[] $iG0051s
 * @property IG0054[] $iG0054s
 * @property IG0055[] $iG0055s
 * @property IG0056[] $iG0056s
 */
class Proveedor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'MG0032';
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
            [['COD_PRO'], 'required'],
            [['FEC_PRO', 'FEC_SIS'], 'safe'],
            [['LIM_CRE', 'LIM_DIA', 'POR_DES', 'P_R_IVA'], 'number'],
            [['IMG_PRO'], 'string'],
            [['COD_PRO', 'COD_VEN', 'TEL_CTO', 'N_U_PAG', 'N_A_PRO', 'FEC_CAD', 'HOR_SIS'], 'string', 'max' => 10],
            [['CED_RUC', 'EQUIPO'], 'string', 'max' => 15],
            [['NOM_PRO'], 'string', 'max' => 70],
            [['COD_PAI', 'COD_CIU', 'TIP_PRO', 'C_TRA_E', 'COD_I_P'], 'string', 'max' => 2],
            [['DIR_PRO', 'CORRE_E'], 'string', 'max' => 100],
            [['TEL_N01', 'TEL_N02', 'NUM_FAX'], 'string', 'max' => 50],
            [['CTA_BAN', 'REG_ASO'], 'string', 'max' => 20],
            [['COD_CTA', 'CTA_IVA'], 'string', 'max' => 12],
            [['NOM_CTO'], 'string', 'max' => 40],
            [['COD_LIN', 'TIP_SER'], 'string', 'max' => 3],
            [['ACT_PRO'], 'string', 'max' => 80],
            [['NOM_GER', 'NOM_SEC'], 'string', 'max' => 25],
            [['OBSER01'], 'string', 'max' => 500],
            [['N_S_PRO'], 'string', 'max' => 7],
            [['USUARIO'], 'string', 'max' => 250],
            [['EST_PRO', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_PRO'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_PRO' => 'Cod Pro',
            'CED_RUC' => 'Ced Ruc',
            'NOM_PRO' => 'Nom Pro',
            'FEC_PRO' => 'Fec Pro',
            'COD_PAI' => 'Cod Pai',
            'COD_CIU' => 'Cod Ciu',
            'DIR_PRO' => 'Dir Pro',
            'TEL_N01' => 'Tel N01',
            'TEL_N02' => 'Tel N02',
            'NUM_FAX' => 'Num Fax',
            'CORRE_E' => 'Corre E',
            'TIP_PRO' => 'Tip Pro',
            'C_TRA_E' => 'C Tra E',
            'LIM_CRE' => 'Lim Cre',
            'LIM_DIA' => 'Lim Dia',
            'POR_DES' => 'Por Des',
            'CTA_BAN' => 'Cta Ban',
            'COD_CTA' => 'Cod Cta',
            'COD_VEN' => 'Cod Ven',
            'NOM_CTO' => 'Nom Cto',
            'TEL_CTO' => 'Tel Cto',
            'COD_LIN' => 'Cod Lin',
            'ACT_PRO' => 'Act Pro',
            'NOM_GER' => 'Nom Ger',
            'NOM_SEC' => 'Nom Sec',
            'OBSER01' => 'Obser01',
            'N_U_PAG' => 'N U Pag',
            'N_S_PRO' => 'N S Pro',
            'N_A_PRO' => 'N A Pro',
            'FEC_CAD' => 'Fec Cad',
            'COD_I_P' => 'Cod I P',
            'TIP_SER' => 'Tip Ser',
            'REG_ASO' => financiero::t('gfinanciero', 'Associated Register'),
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'EST_PRO' => 'Est Pro',
            'CTA_IVA' => 'Cta Iva',
            'P_R_IVA' => 'P R Iva',
            'IMG_PRO' => 'Img Pro',
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[CB0020s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCB0020s()
    {
        return $this->hasMany(CB0020::className(), ['COD_PRO' => 'COD_PRO']);
    }

    /**
     * Gets query for [[CP0002s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCP0002s()
    {
        return $this->hasMany(CP0002::className(), ['COD_PRO' => 'COD_PRO']);
    }

    /**
     * Gets query for [[CP0010s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCP0010s()
    {
        return $this->hasMany(CP0010::className(), ['COD_PRO' => 'COD_PRO']);
    }

    /**
     * Gets query for [[CP0011s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCP0011s()
    {
        return $this->hasMany(CP0011::className(), ['COD_PRO' => 'COD_PRO']);
    }

    /**
     * Gets query for [[IG0050s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0050s()
    {
        return $this->hasMany(IG0050::className(), ['COD_PRO' => 'COD_PRO']);
    }

    /**
     * Gets query for [[IG0051s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0051s()
    {
        return $this->hasMany(IG0051::className(), ['COD_PRO' => 'COD_PRO']);
    }

    /**
     * Gets query for [[IG0054s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0054s()
    {
        return $this->hasMany(IG0054::className(), ['COD_PRO' => 'COD_PRO']);
    }

    /**
     * Gets query for [[IG0055s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0055s()
    {
        return $this->hasMany(IG0055::className(), ['COD_PRO' => 'COD_PRO']);
    }

    /**
     * Gets query for [[IG0056s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0056s()
    {
        return $this->hasMany(IG0056::className(), ['COD_PRO' => 'COD_PRO']);
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
            $str_search .= "(NOM_PRO like :search) AND ";
        }
        $cols = "COD_PRO as Id, NOM_PRO as Nombre";
        if($export) $cols = "COD_PRO as Id, NOM_PRO as Nombre";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".MG0032
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY NOM_PRO;";
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
     * Return columns to dataset of create a query to widget Search.
     *
     * @return mixed Return a Record Array
     */
    public static function getDataColumnsQueryWidget(){
        $arr_data = [];
        $arr_data['con'] = Yii::$app->db_gfinanciero;
        $arr_data['table'] = "MG0032";
        $arr_data['cols'] = [
            'COD_PRO', 
            'NOM_PRO',
        ];
        $arr_data['aliasCols'] = [
            financiero::t('proveedor', 'Code'), 
            financiero::t('proveedor', 'Provider'),
        ];
        $arr_data['colVisible'] = [
            financiero::t('proveedor', 'Code'), 
            financiero::t('proveedor', 'Provider'),
        ];
        $arr_data['where'] = "EST_LOG = 1 and EST_DEL = 1";
        $arr_data['order'] = "NOM_PRO ASC";
        $arr_data['limitPages'] = Yii::$app->params['pageSize'];
        return $arr_data;
    }

}
