<?php

namespace app\modules\gfinanciero\models;

use Yii;

/**
 * This is the model class for table "IG0020".
 *
 * @property string $COD_ART
 * @property string|null $F_A_INV
 * @property string|null $DES_NAT
 * @property string|null $DES_COM
 * @property string|null $NUM_REF
 * @property string|null $COD_LIN
 * @property string|null $COD_TIP
 * @property string|null $COD_MAR
 * @property string|null $COD_GRU
 * @property string|null $AUX_N01
 * @property string|null $AUX_N02
 * @property string|null $AUX_N03
 * @property string|null $AUX_N04
 * @property string|null $UBI_FIS
 * @property string|null $COD_PAI
 * @property string|null $COD_DIV
 * @property string|null $COD_P_A
 * @property string|null $COD_PRO
 * @property float|null $I_M_IVA
 * @property float|null $EXI_MAX
 * @property float|null $EXI_MIN
 * @property float|null $EXI_COM
 * @property float|null $EXI_TOT
 * @property float|null $EXI_BOD
 * @property float|null $DIF_FIS
 * @property float|null $DIF_BOD
 * @property float|null $I_I_UNI
 * @property float|null $I_I_COS
 * @property float|null $I_F_UNI
 * @property float|null $I_B_UNI
 * @property float|null $I_F_COS
 * @property float|null $P_LISTA
 * @property float|null $P_PROME
 * @property float|null $P_COSTO
 * @property string|null $F_LIS_N
 * @property string|null $F_COS_N
 * @property float|null $P_L_ANT
 * @property float|null $P_C_ANT
 * @property float|null $P_P_ANT
 * @property string|null $F_LIS_V
 * @property string|null $F_COS_V
 * @property float|null $P_VENTA Solo Oficomput
 * @property float|null $PAUX_01
 * @property float|null $PAUX_02
 * @property float|null $PAUX_03
 * @property string|null $F_VEN_N
 * @property float|null $P_V_ANT
 * @property float|null $RAUX_01
 * @property float|null $RAUX_02
 * @property float|null $RAUX_03
 * @property string|null $F_VEN_V
 * @property float|null $T_UI_AC
 * @property float|null $DEM_ACT
 * @property float|null $T_UE_AC
 * @property float|null $T_UR_AC
 * @property float|null $T_URCAC
 * @property float|null $T_RC_AC
 * @property string|null $NUM_PED
 * @property float|null $T_REPOS
 * @property float|null $EXI_M01
 * @property float|null $P_C_M01
 * @property float|null $EXI_M02
 * @property float|null $P_C_M02
 * @property float|null $EXI_M03
 * @property float|null $P_C_M03
 * @property float|null $EXI_M04
 * @property float|null $P_C_M04
 * @property float|null $EXI_M05
 * @property float|null $P_C_M05
 * @property float|null $EXI_M06
 * @property float|null $P_C_M06
 * @property float|null $EXI_M07
 * @property float|null $P_C_M07
 * @property float|null $EXI_M08
 * @property float|null $P_C_M08
 * @property float|null $EXI_M09
 * @property float|null $P_C_M09
 * @property float|null $EXI_M10
 * @property float|null $P_C_M10
 * @property float|null $EXI_M11
 * @property float|null $P_C_M11
 * @property float|null $EXI_M12
 * @property float|null $P_C_M12
 * @property float|null $POR_DES
 * @property float|null $CANT_01
 * @property float|null $CANT_02
 * @property float|null $CANT_03
 * @property float|null $CANT_04
 * @property string|null $COD_MED
 * @property float|null $FAC_CON
 * @property float|null $FAC_BUL
 * @property float|null $POR_N01
 * @property float|null $POR_N02
 * @property float|null $POR_N03
 * @property float|null $POR_N04
 * @property string|null $LIS_DIS
 * @property int|null $LIS_CAN
 * @property float|null $P_VDANT
 * @property float|null $P_VDIS1
 * @property float|null $P_VDIS2
 * @property float|null $PV_PROMO
 * @property float|null $P_VDREAL
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string|null $F_C_ART
 * @property string|null $F_E_ART
 * @property float|null $I_M_DES
 * @property string|null $IMG_ART
 * @property float|null $P_IMPOR
 * @property string|null $CAR_ART
 * @property float|null $COS_M_P
 * @property float|null $COS_M_O
 * @property float|null $COS_IND
 * @property string|null $CTI_M_P
 * @property string|null $CTI_M_O
 * @property string|null $CTI_IND
 * @property string|null $CTC_M_P
 * @property string|null $CTC_M_O
 * @property string|null $CTC_IND
 * @property string|null $ART_WEB
 * @property float|null $PRO_VTA Promedio de Venta
 * @property string|null $PRO_IMP
 * @property bool|null $ESTADO_RES
 * @property string|null $COD_VEN_RES
 * @property string|null $FEC_LIM_RES
 * @property string|null $LIS_PROM
 * @property int|null $IDS_CAT
 * @property int|null $NUM_CRE
 * @property int|null $HOR_SEM
 * @property string|null $TIP_PRO
 * @property string|null $EST_LOG
 * @property string|null $COD_SGRU
 * @property string|null $NOM_GRU
 * @property string|null $NOM_SGRU
 *
 * @property IG0001 $cODLIN
 * @property IG0003 $cODMAR
 * @property IG0002 $cODTIP
 * @property IG0022[] $iG0022s
 * @property IG0021[] $cODBODs
 * @property IG0041[] $iG0041s
 * @property IG0040[] $nUMCOTs
 * @property IG0053[] $iG0053s
 * @property IG0052[] $cODPTOs
 * @property IG0056[] $iG0056s
 * @property IG0055[] $cODPTOs0
 * @property IG0061[] $iG0061s
 * @property IG0060[] $cODPTOs1
 * @property VD010101[] $vD010101s
 * @property VC010101[] $cODPTOs2
 */
class ItemArticulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'IG0020';
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
            [['COD_ART'], 'required'],
            [['F_A_INV', 'F_LIS_N', 'F_COS_N', 'F_LIS_V', 'F_COS_V', 'F_VEN_N', 'F_VEN_V', 'FEC_SIS', 'F_C_ART', 'F_E_ART', 'FEC_LIM_RES'], 'safe'],
            [['I_M_IVA', 'EXI_MAX', 'EXI_MIN', 'EXI_COM', 'EXI_TOT', 'EXI_BOD', 'DIF_FIS', 'DIF_BOD', 'I_I_UNI', 'I_I_COS', 'I_F_UNI', 'I_B_UNI', 'I_F_COS', 'P_LISTA', 'P_PROME', 'P_COSTO', 'P_L_ANT', 'P_C_ANT', 'P_P_ANT', 'P_VENTA', 'PAUX_01', 'PAUX_02', 'PAUX_03', 'P_V_ANT', 'RAUX_01', 'RAUX_02', 'RAUX_03', 'T_UI_AC', 'DEM_ACT', 'T_UE_AC', 'T_UR_AC', 'T_URCAC', 'T_RC_AC', 'T_REPOS', 'EXI_M01', 'P_C_M01', 'EXI_M02', 'P_C_M02', 'EXI_M03', 'P_C_M03', 'EXI_M04', 'P_C_M04', 'EXI_M05', 'P_C_M05', 'EXI_M06', 'P_C_M06', 'EXI_M07', 'P_C_M07', 'EXI_M08', 'P_C_M08', 'EXI_M09', 'P_C_M09', 'EXI_M10', 'P_C_M10', 'EXI_M11', 'P_C_M11', 'EXI_M12', 'P_C_M12', 'POR_DES', 'CANT_01', 'CANT_02', 'CANT_03', 'CANT_04', 'FAC_CON', 'FAC_BUL', 'POR_N01', 'POR_N02', 'POR_N03', 'POR_N04', 'P_VDANT', 'P_VDIS1', 'P_VDIS2', 'PV_PROMO', 'P_VDREAL', 'I_M_DES', 'P_IMPOR', 'COS_M_P', 'COS_M_O', 'COS_IND', 'PRO_VTA'], 'number'],
            [['LIS_CAN', 'IDS_CAT', 'NUM_CRE', 'HOR_SEM'], 'integer'],
            [['IMG_ART', 'LIS_PROM'], 'string'],
            [['ESTADO_RES'], 'boolean'],
            [['COD_ART', 'UBI_FIS'], 'string', 'max' => 20],
            [['DES_NAT', 'DES_COM'], 'string', 'max' => 60],
            [['NUM_REF', 'AUX_N01', 'AUX_N02', 'AUX_N03', 'COD_PRO', 'NUM_PED', 'HOR_SIS', 'CTI_M_P', 'CTI_M_O', 'CTI_IND', 'CTC_M_P', 'CTC_M_O', 'CTC_IND', 'COD_VEN_RES'], 'string', 'max' => 10],
            [['COD_LIN', 'COD_TIP', 'COD_MAR', 'COD_GRU', 'COD_SGRU'], 'string', 'max' => 3],
            [['AUX_N04'], 'string', 'max' => 9],
            [['COD_PAI', 'COD_DIV', 'COD_MED'], 'string', 'max' => 2],
            [['COD_P_A'], 'string', 'max' => 17],
            [['LIS_DIS', 'ART_WEB', 'PRO_IMP', 'TIP_PRO', 'EST_LOG'], 'string', 'max' => 1],
            [['USUARIO', 'EQUIPO'], 'string', 'max' => 15],
            [['CAR_ART'], 'string', 'max' => 400],
            [['NOM_GRU', 'NOM_SGRU'], 'string', 'max' => 100],
            [['COD_ART'], 'unique'],
            [['COD_LIN'], 'exist', 'skipOnError' => true, 'targetClass' => IG0001::className(), 'targetAttribute' => ['COD_LIN' => 'COD_LIN']],
            [['COD_MAR'], 'exist', 'skipOnError' => true, 'targetClass' => IG0003::className(), 'targetAttribute' => ['COD_MAR' => 'COD_MAR']],
            [['COD_TIP'], 'exist', 'skipOnError' => true, 'targetClass' => IG0002::className(), 'targetAttribute' => ['COD_TIP' => 'COD_TIP']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_ART' => 'Cod Art',
            'F_A_INV' => 'F A Inv',
            'DES_NAT' => 'Des Nat',
            'DES_COM' => 'Des Com',
            'NUM_REF' => 'Num Ref',
            'COD_LIN' => 'Cod Lin',
            'COD_TIP' => 'Cod Tip',
            'COD_MAR' => 'Cod Mar',
            'COD_GRU' => 'Cod Gru',
            'AUX_N01' => 'Aux N01',
            'AUX_N02' => 'Aux N02',
            'AUX_N03' => 'Aux N03',
            'AUX_N04' => 'Aux N04',
            'UBI_FIS' => 'Ubi Fis',
            'COD_PAI' => 'Cod Pai',
            'COD_DIV' => 'Cod Div',
            'COD_P_A' => 'Cod P A',
            'COD_PRO' => 'Cod Pro',
            'I_M_IVA' => 'I M Iva',
            'EXI_MAX' => 'Exi Max',
            'EXI_MIN' => 'Exi Min',
            'EXI_COM' => 'Exi Com',
            'EXI_TOT' => 'Exi Tot',
            'EXI_BOD' => 'Exi Bod',
            'DIF_FIS' => 'Dif Fis',
            'DIF_BOD' => 'Dif Bod',
            'I_I_UNI' => 'I I Uni',
            'I_I_COS' => 'I I Cos',
            'I_F_UNI' => 'I F Uni',
            'I_B_UNI' => 'I B Uni',
            'I_F_COS' => 'I F Cos',
            'P_LISTA' => 'P Lista',
            'P_PROME' => 'P Prome',
            'P_COSTO' => 'P Costo',
            'F_LIS_N' => 'F Lis N',
            'F_COS_N' => 'F Cos N',
            'P_L_ANT' => 'P L Ant',
            'P_C_ANT' => 'P C Ant',
            'P_P_ANT' => 'P P Ant',
            'F_LIS_V' => 'F Lis V',
            'F_COS_V' => 'F Cos V',
            'P_VENTA' => 'P Venta',
            'PAUX_01' => 'Paux 01',
            'PAUX_02' => 'Paux 02',
            'PAUX_03' => 'Paux 03',
            'F_VEN_N' => 'F Ven N',
            'P_V_ANT' => 'P V Ant',
            'RAUX_01' => 'Raux 01',
            'RAUX_02' => 'Raux 02',
            'RAUX_03' => 'Raux 03',
            'F_VEN_V' => 'F Ven V',
            'T_UI_AC' => 'T Ui Ac',
            'DEM_ACT' => 'Dem Act',
            'T_UE_AC' => 'T Ue Ac',
            'T_UR_AC' => 'T Ur Ac',
            'T_URCAC' => 'T Urcac',
            'T_RC_AC' => 'T Rc Ac',
            'NUM_PED' => 'Num Ped',
            'T_REPOS' => 'T Repos',
            'EXI_M01' => 'Exi M01',
            'P_C_M01' => 'P C M01',
            'EXI_M02' => 'Exi M02',
            'P_C_M02' => 'P C M02',
            'EXI_M03' => 'Exi M03',
            'P_C_M03' => 'P C M03',
            'EXI_M04' => 'Exi M04',
            'P_C_M04' => 'P C M04',
            'EXI_M05' => 'Exi M05',
            'P_C_M05' => 'P C M05',
            'EXI_M06' => 'Exi M06',
            'P_C_M06' => 'P C M06',
            'EXI_M07' => 'Exi M07',
            'P_C_M07' => 'P C M07',
            'EXI_M08' => 'Exi M08',
            'P_C_M08' => 'P C M08',
            'EXI_M09' => 'Exi M09',
            'P_C_M09' => 'P C M09',
            'EXI_M10' => 'Exi M10',
            'P_C_M10' => 'P C M10',
            'EXI_M11' => 'Exi M11',
            'P_C_M11' => 'P C M11',
            'EXI_M12' => 'Exi M12',
            'P_C_M12' => 'P C M12',
            'POR_DES' => 'Por Des',
            'CANT_01' => 'Cant 01',
            'CANT_02' => 'Cant 02',
            'CANT_03' => 'Cant 03',
            'CANT_04' => 'Cant 04',
            'COD_MED' => 'Cod Med',
            'FAC_CON' => 'Fac Con',
            'FAC_BUL' => 'Fac Bul',
            'POR_N01' => 'Por N01',
            'POR_N02' => 'Por N02',
            'POR_N03' => 'Por N03',
            'POR_N04' => 'Por N04',
            'LIS_DIS' => 'Lis Dis',
            'LIS_CAN' => 'Lis Can',
            'P_VDANT' => 'P Vdant',
            'P_VDIS1' => 'P Vdis1',
            'P_VDIS2' => 'P Vdis2',
            'PV_PROMO' => 'Pv Promo',
            'P_VDREAL' => 'P Vdreal',
            'FEC_SIS' => 'Fec Sis',
            'HOR_SIS' => 'Hor Sis',
            'USUARIO' => 'Usuario',
            'EQUIPO' => 'Equipo',
            'F_C_ART' => 'F C Art',
            'F_E_ART' => 'F E Art',
            'I_M_DES' => 'I M Des',
            'IMG_ART' => 'Img Art',
            'P_IMPOR' => 'P Impor',
            'CAR_ART' => 'Car Art',
            'COS_M_P' => 'Cos M P',
            'COS_M_O' => 'Cos M O',
            'COS_IND' => 'Cos Ind',
            'CTI_M_P' => 'Cti M P',
            'CTI_M_O' => 'Cti M O',
            'CTI_IND' => 'Cti Ind',
            'CTC_M_P' => 'Ctc M P',
            'CTC_M_O' => 'Ctc M O',
            'CTC_IND' => 'Ctc Ind',
            'ART_WEB' => 'Art Web',
            'PRO_VTA' => 'Pro Vta',
            'PRO_IMP' => 'Pro Imp',
            'ESTADO_RES' => 'Estado Res',
            'COD_VEN_RES' => 'Cod Ven Res',
            'FEC_LIM_RES' => 'Fec Lim Res',
            'LIS_PROM' => 'Lis Prom',
            'IDS_CAT' => 'Ids Cat',
            'NUM_CRE' => 'Num Cre',
            'HOR_SEM' => 'Hor Sem',
            'TIP_PRO' => 'Tip Pro',
            'EST_LOG' => 'Est Log',
            'COD_SGRU' => 'Cod Sgru',
            'NOM_GRU' => 'Nom Gru',
            'NOM_SGRU' => 'Nom Sgru',
        ];
    }

    /**
     * Gets query for [[CODLIN]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODLIN()
    {
        return $this->hasOne(IG0001::className(), ['COD_LIN' => 'COD_LIN']);
    }

    /**
     * Gets query for [[CODMAR]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODMAR()
    {
        return $this->hasOne(IG0003::className(), ['COD_MAR' => 'COD_MAR']);
    }

    /**
     * Gets query for [[CODTIP]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODTIP()
    {
        return $this->hasOne(IG0002::className(), ['COD_TIP' => 'COD_TIP']);
    }

    /**
     * Gets query for [[IG0022s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0022s()
    {
        return $this->hasMany(IG0022::className(), ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[CODBODs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODBODs()
    {
        return $this->hasMany(IG0021::className(), ['COD_BOD' => 'COD_BOD'])->viaTable('IG0022', ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[IG0041s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0041s()
    {
        return $this->hasMany(IG0041::className(), ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[NUMCOTs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNUMCOTs()
    {
        return $this->hasMany(IG0040::className(), ['NUM_COT' => 'NUM_COT'])->viaTable('IG0041', ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[IG0053s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0053s()
    {
        return $this->hasMany(IG0053::className(), ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[CODPTOs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODPTOs()
    {
        return $this->hasMany(IG0052::className(), ['COD_PTO' => 'COD_PTO', 'TIP_PED' => 'TIP_PED', 'NUM_PED' => 'NUM_PED'])->viaTable('IG0053', ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[IG0056s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0056s()
    {
        return $this->hasMany(IG0056::className(), ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[CODPTOs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODPTOs0()
    {
        return $this->hasMany(IG0055::className(), ['COD_PTO' => 'COD_PTO', 'TIP_DEV' => 'TIP_DEV', 'NUM_DEV' => 'NUM_DEV'])->viaTable('IG0056', ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[IG0061s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0061s()
    {
        return $this->hasMany(IG0061::className(), ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[CODPTOs1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODPTOs1()
    {
        return $this->hasMany(IG0060::className(), ['COD_PTO' => 'COD_PTO', 'TIP_DEV' => 'TIP_DEV', 'NUM_DEV' => 'NUM_DEV'])->viaTable('IG0061', ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[VD010101s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVD010101s()
    {
        return $this->hasMany(VD010101::className(), ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[CODPTOs2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODPTOs2()
    {
        return $this->hasMany(VC010101::className(), ['COD_PTO' => 'COD_PTO', 'COD_CAJ' => 'COD_CAJ', 'TIP_NOF' => 'TIP_NOF', 'NUM_NOF' => 'NUM_NOF'])->viaTable('VD010101', ['COD_ART' => 'COD_ART']);
    }
}
