<?php

namespace app\modules\gfinanciero\models;

use Yii;

use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "IG0003".
 *
 * @property string $COD_MAR
 * @property string|null $NOM_MAR
 * @property string|null $FEC_MAR
 * @property float|null $REG_ASO
 * @property string|null $REG_SEL
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 */
class MarcaArticulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'IG0003';
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
            [['COD_MAR'], 'required'],
            [['FEC_MAR', 'FEC_SIS'], 'safe'],
            [['REG_ASO'], 'number'],
            [['COD_MAR'], 'string', 'max' => 3],
            [['NOM_MAR'], 'string', 'max' => 30],
            [['REG_SEL', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['HOR_SIS'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['COD_MAR'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_MAR' => 'Cod Mar',
            'NOM_MAR' => 'Nom Mar',
            'FEC_MAR' => 'Fec Mar',
            'REG_ASO' => 'Reg Aso',
            'REG_SEL' => 'Reg Sel',
            'FEC_SIS' => 'Fec Sis',
            'HOR_SIS' => 'Hor Sis',
            'USUARIO' => 'Usuario',
            'EQUIPO' => 'Equipo',
            'EST_LOG' => 'Est Log',
            'EST_DEL' => 'Est Del',
        ];
    }
    
    public function getAllItemsGrid($search, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(NOM_MAR like :search) AND ";
        }
        $cols = "COD_MAR as Id, NOM_MAR as Nombre, FEC_MAR as Fecha";
        if($export) $cols .= ", EST_LOG as Estado";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".IG0003
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY COD_MAR;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $result,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'Estado'],
                ],
            ]);
            return $dataProvider;
        }
        return $result;
    }
    
    /**
     * Get Last Id Item Record
     * 
     * @return void
     */
    public static function getLastIdItemArticulo(){
        $row = TipoArticulo::find()->select(['COD_MAR'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_MAR' => SORT_DESC])->one();
        return $row['COD_MAR'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemArticulo(){
        $row = TipoArticulo::find()->select(['COD_MAR'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_MAR' => SORT_DESC])->one();
        $newId = 1 + $row['COD_MAR'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }

}
