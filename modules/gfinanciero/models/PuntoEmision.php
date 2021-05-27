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
class PuntoEmision extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'MG0016';
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
            [['COD_PTO'], 'exist', 'skipOnError' => true, 'targetClass' => Establecimiento::className(), 'targetAttribute' => ['COD_PTO' => 'COD_PTO']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'COD_PTO' => 'Cod Pto',
            'COD_CAJ' => 'Cod Caj',
            'NOM_CAJ' => 'Nom Caj',
            'UBI_CAJ' => 'Ubi Caj',
            'COD_RES' => 'Cod Res',
            'AUT_CAJ' => 'Aut Caj',
            'REG_ASO' => 'Reg Aso',
            'COB_CAJ' => 'Cob Caj',
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO' => financiero::t('gfinanciero', 'Computer'),
            'CAJ_FEC' => 'Caj Fec',
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[CODPTO]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODPTO() {
        return $this->hasOne(MG0015::className(), ['COD_PTO' => 'COD_PTO']);
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
            $str_search .= "(PE.NOM_CAJ like :search) AND ";
        }

        $cols .= "PE.COD_PTO as IdPunto, ";
        $cols .= "PE.COD_CAJ as IdCaja, ";
        $cols .= "CONCAT(ES.COD_PTO,' - ',ES.NOM_PTO) as CodPunto, ";
        $cols .= "PE.NOM_CAJ as NomCaj, ";
        $cols .= "PE.UBI_CAJ as UbiCaj, ";
        $cols .= "PE.COD_RES as CodRes, ";
        $cols .= "PE.AUT_CAJ as AutCaj, ";
        $cols .= "PE.REG_ASO as RegAso, ";
        $cols .= "PE.COB_CAJ as CobCaj, ";
        $cols .= "PE.CAJ_FEC as CajFec ";

        if ($export) {
            $cols = "PE.COD_CAJ as IdCaja, ";
            $cols .= "CONCAT(ES.COD_PTO,' - ',ES.NOM_PTO) as CodPunto, ";
            $cols .= "PE.NOM_CAJ as NomCaj, ";
            $cols .= "PE.UBI_CAJ as UbiCaj, ";
            $cols .= "PE.CAJ_FEC as CajFec ";
        }
        $sql = "SELECT
                    $cols
                FROM
                    " . $con->dbname . ".MG0016 PE
                INNER JOIN   " . $con->dbname . ".MG0015 ES on ES.COD_PTO=PE.COD_PTO AND ES.EST_LOG=1 AND ES.EST_DEL=1
                                    
                WHERE
                    $str_search
                    PE.EST_LOG = 1 AND PE.EST_DEL = 1
                ORDER BY PE.COD_PTO;";
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

}
