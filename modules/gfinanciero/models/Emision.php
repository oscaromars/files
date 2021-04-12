<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "MG0016".
 *
 * @property string $COD_PTO
 * @property string $COD_CAJ
 * @property string|null $NOM_CAJ
 * @property string|null $UBI_CAJ
 * @property string|null $COD_RES
 * @property string|null $AUT_CAJ
 * @property float|null $REG_ASO
 * @property string|null $COB_CAJ
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string|null $CAJ_FEC
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property MG0015 $cODPTO
 */
class Emision extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'MG0016';
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
            [['COD_PTO', 'COD_CAJ'], 'required'],
            [['REG_ASO'], 'number'],
            [['FEC_SIS', 'CAJ_FEC'], 'safe'],
            [['COD_PTO', 'COD_CAJ'], 'string', 'max' => 3],
            [['NOM_CAJ', 'UBI_CAJ'], 'string', 'max' => 30],
            [['COD_RES', 'HOR_SIS'], 'string', 'max' => 10],
            [['AUT_CAJ', 'COB_CAJ', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['COD_PTO', 'COD_CAJ'], 'unique', 'targetAttribute' => ['COD_PTO', 'COD_CAJ']],
            [['COD_PTO'], 'exist', 'skipOnError' => true, 'targetClass' => MG0015::className(), 'targetAttribute' => ['COD_PTO' => 'COD_PTO']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_PTO' => 'Cod Pto',
            'COD_CAJ' => 'Cod Caj',
            'NOM_CAJ' => 'Nom Caj',
            'UBI_CAJ' => 'Ubi Caj',
            'COD_RES' => 'Cod Res',
            'AUT_CAJ' => 'Aut Caj',
            'REG_ASO' => 'Reg Aso',
            'COB_CAJ' => 'Cob Caj',
            'FEC_SIS' => 'Fec Sis',
            'HOR_SIS' => 'Hor Sis',
            'USUARIO' => 'Usuario',
            'EQUIPO' => 'Equipo',
            'CAJ_FEC' => 'Caj Fec',
            'EST_LOG' => 'Est Log',
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[CODPTO]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODPTO()
    {
        return $this->hasOne(MG0015::className(), ['COD_PTO' => 'COD_PTO']);
    }
}
