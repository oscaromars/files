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
            [['COD_PAI', 'COD_EST', 'COD_CIU'], 'string', 'max' => 10],
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
            'COD_PTO' => financiero::t('establecimiento', 'Code'),
            'NOM_PTO' => financiero::t('establecimiento', 'Name'),
            'COD_PAI' => financiero::t('establecimiento', 'Country'),
            'COD_EST' => financiero::t('localidad', 'State'),
            'COD_CIU' => financiero::t('localidad', 'Canton'),
            'DIR_PTO' => financiero::t('localidad', 'Address'),
            'TEL_N01' => financiero::t('establecimiento', 'Telephone 1'),
            'TEL_N02' => financiero::t('establecimiento', 'Telephone 2'),
            'NUM_FAX' => financiero::t('establecimiento', 'Telephone Fax'),
            'CORRE_E' => 'Cod E',
            'CORRE_CT' => financiero::t('establecimiento', 'Mail'),
            'COD_RES' => 'Cod Res',
            'REG_ASO' => 'Reg Aso',
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO' => financiero::t('gfinanciero', 'Computer'),
            'FEC_PTO' => financiero::t('gfinanciero', 'Date'),
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
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
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Item Name
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
    public function getAllItemsGrid($search, $dataProvider = false, $export = false) {
        $search_cond = "%" . $search . "%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;
        $cols = "";
        //// Code Begin
        if (isset($search)) {
            $str_search .= "(E.NOM_PTO like :search) AND ";
        }
        $cols = "E.COD_PTO as CodPto, ";
        $cols .= "E.NOM_PTO as NomPto, ";
        $cols .= "P.NOM_OCG as NomPai, ";
        $cols .= "ES.NOM_OCG as NomEst, ";
        $cols .= "P.NOM_OCG as NomCiu, ";
        $cols .= "E.DIR_PTO as DirPto, ";
        $cols .= "E.TEL_N01 as TelN01, ";
        $cols .= "E.TEL_N02 as TelN02, ";
        $cols .= "E.NUM_FAX as NumFax, ";
        $cols .= "E.CORRE_E as Correo, ";
        $cols .= "E.CORRE_CT as CorreoCt, ";
        $cols .= "E.COD_RES as CodRes, ";
        $cols .= "E.REG_ASO as RegAso ";

        if ($export) {
            $cols = "E.COD_PTO as CodPto, ";
            $cols .= "E.NOM_PTO as NomPto, ";
            $cols .= "P.NOM_OCG as NomPai, ";
            $cols .= "ES.NOM_OCG as NomEst, ";
            $cols .= "P.NOM_OCG as NomCiu, ";
            $cols .= "E.DIR_PTO as DirPto ";
        }
        $sql = "SELECT
                    $cols
                FROM
                    " . $con->dbname . ".MG0015 E
                INNER JOIN   " . $con->dbname . ".MG0014 P on P.COD_OCG=E.COD_PAI AND P.EST_LOG=1 AND P.EST_DEL=1
                INNER JOIN   " . $con->dbname . ".MG0014 ES on ES.COD_OCG=E.COD_EST AND ES.EST_LOG=1 AND ES.EST_DEL=1
                INNER JOIN   " . $con->dbname . ".MG0014 C on C.COD_OCG=E.COD_CIU AND C.EST_LOG=1 AND C.EST_DEL=1
                
                WHERE
                    $str_search
                    E.EST_LOG = 1 AND E.EST_DEL = 1
                ORDER BY E.COD_PTO;";
        //// Code End

        $comando = $con->createCommand($sql);
        if (isset($search)) {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }


        $result = $comando->queryAll();
        if ($dataProvider) {
            $dataProvider = new ArrayDataProvider([
                'key' => 'CodPunto',
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
