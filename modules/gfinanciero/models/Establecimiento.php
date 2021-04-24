<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "MG0015".
 *
 * @property string $COD_PTO
 * @property string|null $NOM_PTO
 * @property string|null $COD_PAI
 * @property string|null $COD_EST
 * @property string|null $COD_CIU
 * @property string|null $DIR_PTO
 * @property string|null $TEL_N01
 * @property string|null $TEL_N02
 * @property string|null $NUM_FAX
 * @property string|null $CORRE_E
 * @property string|null $CORRE_CT
 * @property string|null $COD_RES
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string|null $FEC_PTO
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property MG0016[] $mG0016s
 */
class Establecimiento extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'MG0015';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_gfinanciero');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['COD_PTO'], 'required'],
            [['REG_ASO'], 'number'],
            [['FEC_SIS', 'FEC_PTO'], 'safe'],
            [['COD_PTO'], 'string', 'max' => 3],
            [['NOM_PTO', 'DIR_PTO', 'CORRE_E'], 'string', 'max' => 30],
            [['COD_PAI', 'COD_EST', 'COD_CIU'], 'string', 'max' => 2],
            [['TEL_N01', 'TEL_N02', 'NUM_FAX', 'COD_RES', 'HOR_SIS'], 'string', 'max' => 10],
            [['CORRE_CT'], 'string', 'max' => 60],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_PTO'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'COD_PTO' => 'Cod Pto',
            'NOM_PTO' => 'Nom Pto',
            'COD_PAI' => 'Cod Pai',
            'COD_EST' => 'Cod Est',
            'COD_CIU' => 'Cod Ciu',
            'DIR_PTO' => 'Dir Pto',
            'TEL_N01' => 'Tel N01',
            'TEL_N02' => 'Tel N02',
            'NUM_FAX' => 'Num Fax',
            'CORRE_E' => 'Corre E',
            'CORRE_CT' => 'Corre Ct',
            'COD_RES' => 'Cod Res',
            'REG_ASO' => 'Reg Aso',
            'FEC_SIS' => 'Fec Sis',
            'HOR_SIS' => 'Hor Sis',
            'USUARIO' => 'Usuario',
            'EQUIPO' => 'Equipo',
            'FEC_PTO' => 'Fec Pto',
            'EST_LOG' => 'Est Log',
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[MG0016s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMG0016s() {
        return $this->hasMany(MG0016::className(), ['COD_PTO' => 'COD_PTO']);
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getEstablecimiento() {
        $con = \Yii::$app->db_gfinanciero;
        $sql = "SELECT COD_PTO Ids,NOM_PTO Nombre 
                    FROM " . $con->dbname . ".MG0015
                WHERE EST_LOG=1 AND EST_DEL=1 ";
        $comando = $con->createCommand($sql);
        //$comando->bindParam(":esp_id", $Ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getTypeEstablecimiento() {
        $con = \Yii::$app->db_gfinanciero;
        $sql = "SELECT 
                    COD_PTO as id,
                    CONCAT(COD_PTO,' - ',NOM_PTO) as name
                FROM " . $con->dbname . ".MG0015
                WHERE EST_LOG=1 AND EST_DEL=1 ";
        $comando = $con->createCommand($sql);

        return $comando->queryAll();
    }

}
