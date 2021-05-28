<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "TIP_CON".
 *
 * @property string $COD_CON
 * @property string|null $NOM_CON
 * @property string|null $FEC_CON
 * @property float|null $POR_R_F
 * @property float|null $POR_R_I
 * @property float|null $GRA_IVA
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 * @property string|null $FEC_MOD
 */
class TipoContribuyente extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'TIP_CON';
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
            [['COD_CON'], 'required'],
            [['FEC_CON', 'FEC_SIS', 'FEC_MOD'], 'safe'],
            [['POR_R_F', 'POR_R_I', 'GRA_IVA', 'REG_ASO'], 'number'],
            [['COD_CON'], 'string', 'max' => 2],
            [['NOM_CON'], 'string', 'max' => 30],
            [['HOR_SIS'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_CON'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'COD_CON' => 'Cod Con',
            'NOM_CON' => 'Nom Con',
            'FEC_CON' => 'Fec Con',
            'POR_R_F' => 'Por R F',
            'POR_R_I' => 'Por R I',
            'GRA_IVA' => 'Gra Iva',
            'REG_ASO' => 'Reg Aso',
            'FEC_SIS' => 'Fec Sis',
            'HOR_SIS' => 'Hor Sis',
            'FEC_MOD' => 'Fec Mod',
            'USUARIO' => 'Usuario',
            'EQUIPO' => 'Equipo',
            'EST_LOG' => 'Est Log',
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
            $str_search .= "(NOM_CON like :search) AND ";
        }
        $cols = "COD_CON as Id, ";
        $cols .= "NOM_CON as Nombre, ";
        $cols .= "FEC_CON as Fecha, ";
        $cols .= "POR_R_F as PorRF, ";
        $cols .= "POR_R_I as PorRI ";

        if ($export) {
            $cols = "COD_CON as Id, ";
            $cols .= "NOM_CON as Nombre, ";
            $cols .= "FEC_CON as Fecha, ";
            $cols .= "POR_R_F as PorRF, ";
            $cols .= "POR_R_I as PorRI ";
        }
        $sql = "SELECT 
                    $cols
                FROM 
                    " . $con->dbname . ".TIP_CON
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1                    
                ORDER BY COD_CON;";
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
