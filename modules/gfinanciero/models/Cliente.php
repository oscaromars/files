<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "MG0031".
 *
 * @property string $COD_CLI
 * @property string|null $CED_RUC
 * @property string|null $COD_SCEN
 * @property string|null $FAC_CLI
 * @property string|null $NOM_CLI
 * @property string|null $FEC_CLI
 * @property string|null $COD_PAI
 * @property string|null $COD_CIU
 * @property string|null $DIR_CLI
 * @property string|null $TEL_N01
 * @property string|null $TEL_N02
 * @property string|null $NUM_FAX
 * @property string|null $CORRE_E
 * @property string|null $TIP_CLI
 * @property string|null $TIP_EST
 * @property string|null $COD_CPE
 * @property string|null $C_TRA_E
 * @property float|null $LIM_CRE
 * @property float|null $MAX_CRE
 * @property float|null $LIM_DIA
 * @property int|null $MAX_DIA
 * @property string|null $COD_FOR
 * @property float|null $POR_DES
 * @property string|null $CTA_BAN
 * @property string|null $COD_CTA
 * @property string|null $COD_VEN
 * @property string|null $NOM_CTO
 * @property string|null $TEL_CTO
 * @property string|null $ACT_CLI
 * @property string|null $NOM_GER
 * @property string|null $NOM_SEC
 * @property string|null $OBSER01
 * @property string|null $OBSER02
 * @property string|null $OBSER03
 * @property string|null $N_U_PAG
 * @property string|null $N_S_PRO
 * @property string|null $N_A_PRO
 * @property string|null $FEC_CAD
 * @property string|null $COD_I_C
 * @property int|null $NUM_MAT
 * @property string|null $COD_CAT
 * @property int|null $IDS_CAR
 * @property int|null $IDS_MOD
 * @property int|null $IDS_REP
 * @property string|null $EMAIL_U
 * @property string|null $EST_CLI
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string|null $IMG_CLI
 * @property string|null $CTA_IVA
 * @property float|null $P_R_IVA
 * @property string|null $NUM_CRE
 * @property string|null $TIP_PRE
 * @property string|null $LUG_DES
 * @property string|null $NOM_ACC
 * @property float|null $DIA_GRA
 * @property string|null $CLI_DIS
 * @property string|null $EST_FAC
 * @property int|null $REG_ASO
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property CB0010[] $cB0010s
 * @property CC0002[] $cC0002s
 * @property CC0010[] $cC0010s
 * @property CC0011[] $cC0011s
 * @property IG0040[] $iG0040s
 * @property IG0041[] $iG0041s
 * @property IG0060[] $iG0060s
 * @property IG0061[] $iG0061s
 * @property VC010101[] $vC010101s
 * @property VD010101[] $vD010101s
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'MG0031';
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
            [['COD_CLI'], 'required'],
            [['FEC_CLI', 'FEC_SIS'], 'safe'],
            [['LIM_CRE', 'MAX_CRE', 'LIM_DIA', 'POR_DES', 'P_R_IVA', 'DIA_GRA'], 'number'],
            [['MAX_DIA', 'NUM_MAT', 'IDS_CAR', 'IDS_MOD', 'IDS_REP', 'REG_ASO'], 'integer'],
            [['OBSER03', 'IMG_CLI'], 'string'],
            [['COD_CLI', 'COD_VEN', 'TEL_CTO', 'N_U_PAG', 'N_A_PRO', 'HOR_SIS', 'NUM_CRE'], 'string', 'max' => 10],
            [['CED_RUC', 'COD_SCEN', 'FAC_CLI', 'EQUIPO'], 'string', 'max' => 15],
            [['NOM_CLI', 'DIR_CLI', 'CORRE_E', 'NOM_GER', 'NOM_ACC'], 'string', 'max' => 100],
            [['COD_PAI', 'COD_CIU', 'TIP_CLI', 'TIP_EST', 'COD_FOR', 'COD_I_C'], 'string', 'max' => 2],
            [['TEL_N01'], 'string', 'max' => 80],
            [['TEL_N02', 'NUM_FAX'], 'string', 'max' => 50],
            [['COD_CPE', 'C_TRA_E'], 'string', 'max' => 3],
            [['CTA_BAN'], 'string', 'max' => 20],
            [['COD_CTA', 'CTA_IVA'], 'string', 'max' => 12],
            [['NOM_CTO', 'ACT_CLI'], 'string', 'max' => 40],
            [['NOM_SEC'], 'string', 'max' => 25],
            [['OBSER01', 'OBSER02'], 'string', 'max' => 900],
            [['N_S_PRO', 'FEC_CAD'], 'string', 'max' => 7],
            [['COD_CAT', 'EST_CLI', 'TIP_PRE', 'CLI_DIS', 'EST_FAC', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['EMAIL_U'], 'string', 'max' => 60],
            [['USUARIO'], 'string', 'max' => 250],
            [['LUG_DES'], 'string', 'max' => 30],
            [['COD_CLI'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_CLI' => 'Cod Cli',
            'CED_RUC' => 'Ced Ruc',
            'COD_SCEN' => 'Cod Scen',
            'FAC_CLI' => 'Fac Cli',
            'NOM_CLI' => 'Nom Cli',
            'FEC_CLI' => 'Fec Cli',
            'COD_PAI' => 'Cod Pai',
            'COD_CIU' => 'Cod Ciu',
            'DIR_CLI' => 'Dir Cli',
            'TEL_N01' => 'Tel N01',
            'TEL_N02' => 'Tel N02',
            'NUM_FAX' => 'Num Fax',
            'CORRE_E' => 'Corre E',
            'TIP_CLI' => 'Tip Cli',
            'TIP_EST' => 'Tip Est',
            'COD_CPE' => 'Cod Cpe',
            'C_TRA_E' => 'C Tra E',
            'LIM_CRE' => 'Lim Cre',
            'MAX_CRE' => 'Max Cre',
            'LIM_DIA' => 'Lim Dia',
            'MAX_DIA' => 'Max Dia',
            'COD_FOR' => 'Cod For',
            'POR_DES' => 'Por Des',
            'CTA_BAN' => 'Cta Ban',
            'COD_CTA' => 'Cod Cta',
            'COD_VEN' => 'Cod Ven',
            'NOM_CTO' => 'Nom Cto',
            'TEL_CTO' => 'Tel Cto',
            'ACT_CLI' => 'Act Cli',
            'NOM_GER' => 'Nom Ger',
            'NOM_SEC' => 'Nom Sec',
            'OBSER01' => 'Obser01',
            'OBSER02' => 'Obser02',
            'OBSER03' => 'Obser03',
            'N_U_PAG' => 'N U Pag',
            'N_S_PRO' => 'N S Pro',
            'N_A_PRO' => 'N A Pro',
            'FEC_CAD' => 'Fec Cad',
            'COD_I_C' => 'Cod I C',
            'NUM_MAT' => 'Num Mat',
            'COD_CAT' => 'Cod Cat',
            'IDS_CAR' => 'Ids Car',
            'IDS_MOD' => 'Ids Mod',
            'IDS_REP' => 'Ids Rep',
            'EMAIL_U' => 'Email U',
            'EST_CLI' => 'Est Cli',
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'IMG_CLI' => 'Img Cli',
            'CTA_IVA' => 'Cta Iva',
            'P_R_IVA' => 'P R Iva',
            'NUM_CRE' => 'Num Cre',
            'TIP_PRE' => 'Tip Pre',
            'LUG_DES' => 'Lug Des',
            'NOM_ACC' => 'Nom Acc',
            'DIA_GRA' => 'Dia Gra',
            'CLI_DIS' => 'Cli Dis',
            'EST_FAC' => 'Est Fac',
            'REG_ASO' => financiero::t('gfinanciero', 'Associated Register'),
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[CB0010s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCB0010s()
    {
        return $this->hasMany(CB0010::className(), ['COD_CLI' => 'COD_CLI']);
    }

    /**
     * Gets query for [[CC0002s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCC0002s()
    {
        return $this->hasMany(CC0002::className(), ['COD_CLI' => 'COD_CLI']);
    }

    /**
     * Gets query for [[CC0010s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCC0010s()
    {
        return $this->hasMany(CC0010::className(), ['COD_CLI' => 'COD_CLI']);
    }

    /**
     * Gets query for [[CC0011s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCC0011s()
    {
        return $this->hasMany(CC0011::className(), ['COD_CLI' => 'COD_CLI']);
    }

    /**
     * Gets query for [[IG0040s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0040s()
    {
        return $this->hasMany(IG0040::className(), ['COD_CLI' => 'COD_CLI']);
    }

    /**
     * Gets query for [[IG0041s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0041s()
    {
        return $this->hasMany(IG0041::className(), ['COD_CLI' => 'COD_CLI']);
    }

    /**
     * Gets query for [[IG0060s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0060s()
    {
        return $this->hasMany(IG0060::className(), ['COD_CLI' => 'COD_CLI']);
    }

    /**
     * Gets query for [[IG0061s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0061s()
    {
        return $this->hasMany(IG0061::className(), ['COD_CLI' => 'COD_CLI']);
    }

    /**
     * Gets query for [[VC010101s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVC010101s()
    {
        return $this->hasMany(VC010101::className(), ['COD_CLI' => 'COD_CLI']);
    }

    /**
     * Gets query for [[VD010101s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVD010101s()
    {
        return $this->hasMany(VD010101::className(), ['COD_CLI' => 'COD_CLI']);
    }

    /**
     * Return columns to dataset of create a query to widget Search.
     * 
     * @return mixed Return a Record Array
     */
    public static function getDataColumnsQueryWidget(){
        $arr_data = [];
        $arr_data['con'] = Yii::$app->db_gfinanciero;
        $arr_data['table'] = "MG0031";
        $arr_data['cols'] = [
            'COD_CLI', 
            'NOM_CLI',
            'CED_RUC',
        ];
        $arr_data['aliasCols'] = [
            financiero::t('cliente', 'Code'), 
            financiero::t('cliente', 'Client'),
            financiero::t('cliente', 'DNI'),
        ];
        $arr_data['colVisible'] = [
            financiero::t('cliente', 'Code'), 
            financiero::t('cliente', 'Client'),
        ];
        $arr_data['where'] = "EST_LOG = 1 and EST_DEL = 1";
        $arr_data['order'] = "NOM_CLI ASC";
        $arr_data['limitPages'] = Yii::$app->params['pageSize'];

        return $arr_data;
    }

}
