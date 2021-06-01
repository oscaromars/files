<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "MG0033".
 *
 * @property string $COD_EMP
 * @property string|null $CED_RUC
 * @property string|null $NOM_EMP
 * @property string|null $FEC_EMP
 * @property string|null $FEC_NAC
 * @property string|null $COD_VEN
 * @property string|null $USU_ACC
 * @property int|null $IDS_DEP
 * @property string|null $COD_PAI
 * @property string|null $COD_CIU
 * @property string|null $DIR_EMP
 * @property string|null $TEL_N01
 * @property string|null $TEL_N02
 * @property string|null $EXT_EMP
 * @property string|null $NUM_FAX
 * @property string|null $CORRE_E
 * @property string|null $TIP_EMP
 * @property string|null $COD_CTA
 * @property string|null $OBSER01
 * @property float|null $CUO_SEM
 * @property float|null $CUO_MES
 * @property float|null $CUO_SHP
 * @property float|null $CUO_MHP
 * @property float|null $CUO_COB
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string|null $IMG_EMP
 * @property string|null $EST_PER
 * @property string|null $EST_CVAR
 * @property string|null $SUP_VTA
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property IG0040[] $iG0040s
 * @property IG0041[] $iG0041s
 */
class GEmpleado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'MG0033';
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
            [['COD_EMP'], 'required'],
            [['FEC_EMP', 'FEC_NAC', 'FEC_SIS'], 'safe'],
            [['IDS_DEP'], 'integer'],
            [['CUO_SEM', 'CUO_MES', 'CUO_SHP', 'CUO_MHP', 'CUO_COB', 'REG_ASO'], 'number'],
            [['IMG_EMP'], 'string'],
            [['COD_EMP', 'COD_VEN', 'USU_ACC', 'TEL_N01', 'TEL_N02', 'EXT_EMP', 'NUM_FAX', 'HOR_SIS', 'SUP_VTA'], 'string', 'max' => 10],
            [['CED_RUC', 'EQUIPO'], 'string', 'max' => 15],
            [['NOM_EMP'], 'string', 'max' => 70],
            [['COD_PAI', 'COD_CIU'], 'string', 'max' => 10],
            [['TIP_EMP'], 'string', 'max' => 2],
            [['DIR_EMP'], 'string', 'max' => 60],
            [['CORRE_E'], 'string', 'max' => 30],
            [['COD_CTA'], 'string', 'max' => 12],
            [['OBSER01'], 'string', 'max' => 120],
            [['USUARIO'], 'string', 'max' => 250],
            [['EST_PER', 'EST_CVAR', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_EMP'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_EMP' => 'Cod Emp',
            'CED_RUC' => 'Ced Ruc',
            'NOM_EMP' => 'Nom Emp',
            'FEC_EMP' => 'Fec Emp',
            'FEC_NAC' => 'Fec Nac',
            'COD_VEN' => 'Cod Ven',
            'USU_ACC' => 'Usu Acc',
            'IDS_DEP' => 'Ids Dep',
            'COD_PAI' => 'Cod Pai',
            'COD_CIU' => 'Cod Ciu',
            'DIR_EMP' => 'Dir Emp',
            'TEL_N01' => 'Tel N01',
            'TEL_N02' => 'Tel N02',
            'EXT_EMP' => 'Ext Emp',
            'NUM_FAX' => 'Num Fax',
            'CORRE_E' => 'Corre E',
            'TIP_EMP' => 'Tip Emp',
            'COD_CTA' => 'Cod Cta',
            'OBSER01' => 'Obser01',
            'CUO_SEM' => 'Cuo Sem',
            'CUO_MES' => 'Cuo Mes',
            'CUO_SHP' => 'Cuo Shp',
            'CUO_MHP' => 'Cuo Mhp',
            'CUO_COB' => 'Cuo Cob',
            'REG_ASO' => 'Reg Aso',
            'FEC_SIS' => 'Fec Sis',
            'HOR_SIS' => 'Hor Sis',
            'USUARIO' => 'Usuario',
            'EQUIPO' => 'Equipo',
            'IMG_EMP' => 'Img Emp',
            'EST_PER' => 'Est Per',
            'EST_CVAR' => 'Est Cvar',
            'SUP_VTA' => 'Sup Vta',
            'EST_LOG' => 'Est Log',
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[IG0040s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0040s()
    {
        return $this->hasMany(IG0040::className(), ['ATIENDE' => 'COD_EMP']);
    }

    /**
     * Gets query for [[IG0041s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIG0041s()
    {
        return $this->hasMany(IG0041::className(), ['COD_EMP' => 'COD_EMP']);
    }

    /**
     * Return columns to dataset of create a query to widget Search.
     *
     * @return mixed Return a Record Array
     */
    public static function getDataColumnsQueryWidget(){
        $arr_data = [];
        $arr_data['con'] = Yii::$app->db_gfinanciero;
        $arr_data['table'] = "MG0033";
        $arr_data['cols'] = [
            'COD_EMP', 
            'NOM_EMP',
        ];
        $arr_data['aliasCols'] = [
            financiero::t('empleado', 'Code'), 
            financiero::t('empleado', 'Employee'),
        ];
        $arr_data['colVisible'] = [
            financiero::t('articulo', 'Code'), 
            financiero::t('articulo', 'Employee'),
        ];
        $arr_data['where'] = "EST_LOG = 1 AND EST_DEL = 1";
        $arr_data['order'] = "COD_EMP ASC";
        $arr_data['limitPages'] = Yii::$app->params['pageSize'];
        return $arr_data;
    }
}
