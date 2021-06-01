<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "TIPOS".
 *
 * @property string $COD_TIP
 * @property string $NOM_TIP
 * @property string|null $CONTADOR
 * @property string $FEC_SIS
 * @property string $HOR_SIS
 * @property string $USUARIO
 * @property string $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 * @property string|null $FEC_MOD
 */
class TipoComprobante extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'TIPOS';
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
            [['COD_TIP', 'NOM_TIP', 'FEC_SIS', 'HOR_SIS', 'USUARIO', 'EQUIPO', 'EST_LOG', 'EST_DEL'], 'required'],
            [['FEC_SIS', 'FEC_MOD'], 'safe'],
            [['COD_TIP'], 'string', 'max' => 2],
            [['NOM_TIP'], 'string', 'max' => 30],
            [['CONTADOR', 'HOR_SIS'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_TIP'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'COD_TIP' => financiero::t('tipocomprobante', 'Code'),
            'NOM_TIP' => financiero::t('tipocomprobante', 'Name'),
            'CONTADOR' => financiero::t('tipocomprobante', 'Accountant'),
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'FEC_MOD' => financiero::t('gfinanciero', 'Modification Date'),
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
        $cols = "";
        //// Code Begin
        if (isset($search)) {
            $str_search .= "(NOM_TIP like :search) AND ";
        }
        $cols = "COD_TIP as Id, ";
        $cols .= "NOM_TIP as Nombre, ";
        $cols .= "CONTADOR as Contador ";

        if ($export) {
            $cols = "COD_TIP as Id, ";
            $cols .= "NOM_TIP as Nombre, ";
            $cols .= "CONTADOR as Contador ";
        }
        $sql = "SELECT 
                    $cols
                FROM 
                    " . $con->dbname . ".TIPOS
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1                    
                ORDER BY COD_TIP;";
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

}
