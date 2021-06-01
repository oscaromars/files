<?php

namespace app\modules\gfinanciero\models;

use Yii;

/**
 * This is the model class for table "CB0001".
 *
 * @property int $IDS_BAN
 * @property string|null $NOM_BAN
 * @property string|null $NOMEN_B
 * @property string|null $COD_BAN
 * @property string $FEC_CRE
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 */
class Banco extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CB0001';
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
            [['FEC_CRE'], 'safe'],
            [['NOM_BAN'], 'string', 'max' => 50],
            [['NOMEN_B'], 'string', 'max' => 3],
            [['COD_BAN'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IDS_BAN' => 'Ids Ban',
            'NOM_BAN' => 'Nom Ban',
            'NOMEN_B' => 'Nomen B',
            'COD_BAN' => 'Cod Ban',
            'FEC_CRE' => 'Fec Cre',
            'USUARIO' => 'Usuario',
            'EQUIPO' => 'Equipo',
            'EST_LOG' => 'Est Log',
            'EST_DEL' => 'Est Del',
        ];
    }
}
