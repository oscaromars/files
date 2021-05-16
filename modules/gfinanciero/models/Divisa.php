<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "MG0021".
 *
 * @property string $COD_DIV
 * @property string|null $NOM_DIV
 * @property float|null $V_COTIZ
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 */
class Divisa extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'MG0021';
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
            [['COD_DIV'], 'required'],
            [['V_COTIZ', 'REG_ASO'], 'number'],
            [['FEC_SIS'], 'safe'],
            [['COD_DIV'], 'string', 'max' => 2],
            [['NOM_DIV'], 'string', 'max' => 30],
            [['HOR_SIS'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_DIV'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'COD_DIV' => financiero::t('divisa', 'Code'),
            'NOM_DIV' => financiero::t('divisa', 'Name'),
            'V_COTIZ' => financiero::t('divisa', 'Quotation'),
            'REG_ASO' => financiero::t('gfinanciero', 'Associated Register'),
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO' => financiero::t('gfinanciero', 'Computer'),
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
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

        //// Code Begin
        if (isset($search)) {
            $str_search .= "(NOM_DIV like :search) AND ";
        }
        $cols = "COD_DIV as Id, ";
        $cols .= "NOM_DIV as Nombre, ";
        $cols .= "V_COTIZ as Cotizacion ";

        if ($export) {
            $cols = "NOM_DIV as Nombre, ";
            $cols .= "V_COTIZ as Cotizacion ";
        }

        $sql = "SELECT 
                    $cols
                FROM 
                    " . $con->dbname . ".MG0021
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY COD_DIV;";
        //// Code End

        $comando = $con->createCommand($sql);
        if (isset($search)) {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
        if ($dataProvider) {
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
    public static function getDataColumnsQueryWidget() {
        $arr_data = [];
        $arr_data['con'] = Yii::$app->db_gfinanciero;
        $arr_data['table'] = "MG0021";
        $arr_data['cols'] = [
            'COD_DIV',
            'NOM_DIV',
        ];
        $arr_data['aliasCols'] = [
            financiero::t('divisa', 'Code'),
            financiero::t('divisa', 'Currency'),
        ];
        $arr_data['colVisible'] = [
            financiero::t('divisa', 'Code'),
            financiero::t('divisa', 'Currency'),
        ];
        $arr_data['where'] = "EST_LOG = 1 and EST_DEL = 1";
        $arr_data['order'] = "NOM_DIV ASC";
        $arr_data['limitPages'] = Yii::$app->params['pageSize'];
        return $arr_data;
    }

    /**
     * Get Last Id Item Record
     *
     * @return void
     */
    public static function getLastIdItemRecord() {
        $row = self::find()->select(['COD_DIV'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_DIV' => SORT_DESC])->one();
        return $row['COD_DIV'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord() {
        $row = self::find()->select(['COD_DIV'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_DIV' => SORT_DESC])->one();
        $newId = 1 + $row['COD_DIV'];
        $newId = str_pad($newId, 2, "0", STR_PAD_LEFT);
        return $newId;
    }

}
