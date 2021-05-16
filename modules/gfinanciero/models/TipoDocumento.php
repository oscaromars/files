<?php

namespace app\modules\gfinanciero\models;

use app\models\Utilities;
use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "NOT_FAC".
 *
 * @property string $COD_PTO
 * @property string $COD_CAJ
 * @property string $TIP_NOF
 * @property string $NUM_NOF
 * @property string|null $NOM_NOF
 * @property string|null $EDOC_TIP_DOC
 * @property string|null $EDOC_EST
 * @property float|null $POR_IVA
 * @property string|null $FEC_NOF
 * @property string|null $HOR_NOF
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string|null $TIP_DOC
 * @property float|null $C_ITEMS
 * @property string|null $CTA_IVA
 * @property string|null $NUM_INI
 * @property string|null $NUM_FIN
 * @property string|null $SEC_AUT
 * @property string|null $NUM_SER
 * @property string|null $NUM_AUT
 * @property string|null $FEC_CAD
 * @property string|null $INC_IVA
 * @property string|null $DOC_AUT
 * @property string|null $FEC_AUT
 * @property string $EST_LOG
 * @property string $EST_DEL
 */
class TipoDocumento extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'NOT_FAC';
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
            [['COD_PTO', 'COD_CAJ', 'TIP_NOF'], 'required'],
            [['POR_IVA', 'C_ITEMS'], 'number'],
            [['FEC_NOF', 'FEC_SIS'], 'safe'],
            [['COD_PTO', 'COD_CAJ'], 'string', 'max' => 3],
            [['TIP_NOF', 'EDOC_TIP_DOC', 'TIP_DOC'], 'string', 'max' => 2],
            [['NUM_NOF', 'HOR_SIS', 'NUM_INI', 'NUM_FIN', 'NUM_SER', 'NUM_AUT', 'FEC_AUT'], 'string', 'max' => 10],
            [['NOM_NOF'], 'string', 'max' => 30],
            [['EDOC_EST', 'SEC_AUT', 'INC_IVA', 'DOC_AUT', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['HOR_NOF'], 'string', 'max' => 8],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['CTA_IVA'], 'string', 'max' => 9],
            [['FEC_CAD'], 'string', 'max' => 5],
            [['COD_PTO', 'COD_CAJ', 'TIP_NOF'], 'unique', 'targetAttribute' => ['COD_PTO', 'COD_CAJ', 'TIP_NOF']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'COD_PTO' => financiero::t('tipodocumento', 'Point Code'),
            'COD_CAJ' => financiero::t('tipodocumento', 'Box Code'),
            'TIP_NOF' => financiero::t('tipodocumento', 'Code'),
            'NUM_NOF' => financiero::t('tipodocumento', 'Document Number'),
            'NOM_NOF' => financiero::t('tipodocumento', 'Document Name'),
            'EDOC_TIP_DOC' => financiero::t('tipodocumento', 'Document Type'),
            'EDOC_EST' => financiero::t('tipodocumento', 'Document Status'),
            'POR_IVA' => financiero::t('tipodocumento', 'Iva'),
            'FEC_NOF' => financiero::t('tipodocumento', 'Document Date'),
            'HOR_NOF' => financiero::t('gfinanciero', 'System Hour'),
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO' => financiero::t('gfinanciero', 'Computer'),
            'TIP_DOC' => financiero::t('tipodocumento', 'Document Type'),
            'C_ITEMS' => financiero::t('tipodocumento', 'Quantity Items'),
            'CTA_IVA' => financiero::t('tipodocumento', 'Document Date'),
            'NUM_INI' => 'Num Ini',
            'NUM_FIN' => 'Num Fin',
            'SEC_AUT' => financiero::t('tipodocumento', 'Sequence'),
            'NUM_SER' => financiero::t('tipodocumento', 'Serial Number'),
            'NUM_AUT' => financiero::t('tipodocumento', 'Authorization Number'),
            'FEC_CAD' => financiero::t('tipodocumento', 'Expiration Date'),
            'INC_IVA' => 'Inc Iva',
            'DOC_AUT' => 'Doc Aut',
            'FEC_AUT' => 'Fec Aut',
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
    public function getAllItemsGrid($search, $type_est = NULL, $type_emi = NULL, $dataProvider = false, $export = false){
        $search_cond = "%" . $search . "%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;
        $cols = "";
        //// Code Begin
        if (isset($search)) {
            $str_search .= "(N.NOM_NOF like :search) AND ";
        }
        if(isset($type_est) && $type_est !== "0"){
            $str_search .= " N.COD_PTO = :typeA AND ";
        }
        if(isset($type_emi) && $type_emi !== "0"){
            $str_search .= " N.COD_CAJ = :typeB AND ";
        }
                   
        
        $cols .= "ES.COD_PTO as IdPunto, ";
        $cols .= "EM.COD_CAJ as IdCaja, ";
        $cols .= "CONCAT(ES.COD_PTO,' - ',ES.NOM_PTO) as CodPunto, ";
        $cols .= "CONCAT(EM.COD_CAJ,' - ',EM.NOM_CAJ) as CodCaja, ";
        $cols .= "N.TIP_NOF as TipNof, ";
        $cols .= "N.NUM_NOF as NumNof, ";
        $cols .= "N.NOM_NOF as NomNof, ";
        $cols .= "N.EDOC_TIP_DOC as EdocTipDoc, ";
        $cols .= "N.EDOC_EST as EdocEst, ";
        $cols .= "N.POR_IVA as PorIva, ";
        $cols .= "N.FEC_NOF as FecNof, ";
        $cols .= "N.HOR_NOF as HorNof, ";
        $cols .= "N.FEC_SIS as FecSis, ";
        $cols .= "N.HOR_SIS as HorSis, ";
        $cols .= "N.USUARIO as Usuario, ";
        $cols .= "N.EQUIPO as Equipo, ";
        $cols .= "N.TIP_DOC as TipDoc, ";
        $cols .= "N.C_ITEMS as CItems, ";
        $cols .= "CONCAT(N.CTA_IVA,' - ',C.NOM_CTA) as CtaIva, ";
        $cols .= "N.NUM_INI as NumIni, ";
        $cols .= "N.NUM_FIN as NumFin, ";
        $cols .= "N.SEC_AUT as SecAut, ";
        $cols .= "N.NUM_SER as NumSer, ";
        $cols .= "N.NUM_AUT as NumAut, ";
        $cols .= "N.FEC_CAD as FecCad, ";
        $cols .= "N.INC_IVA as IncIva, ";
        $cols .= "N.DOC_AUT as DocAut, ";
        $cols .= "N.FEC_AUT as FecAut ";

        if ($export) {
            $cols  = "N.TIP_NOF as TipNof, ";
            $cols .= "CONCAT(ES.COD_PTO,' - ',ES.NOM_PTO) as CodPunto, ";
            $cols .= "CONCAT(EM.COD_CAJ,' - ',EM.NOM_CAJ) as CodCaja, ";           
            $cols .= "N.NUM_NOF as NumNof, ";
            $cols .= "N.NOM_NOF as NomNof, ";            
            $cols .= "N.POR_IVA as PorIva, ";            
            $cols .= "CONCAT(N.CTA_IVA,' - ',C.NOM_CTA) as CtaIva ";
      
        }
        $sql = "SELECT
                    $cols
                FROM
                    " . $con->dbname . ".NOT_FAC N
                INNER JOIN   " . $con->dbname . ".MG0015 ES on ES.COD_PTO=N.COD_PTO AND ES.EST_LOG=1 AND ES.EST_DEL=1
                INNER JOIN   " . $con->dbname . ".MG0016 EM on EM.COD_CAJ=N.COD_CAJ AND EM.EST_LOG=1 AND EM.EST_DEL=1 
                INNER JOIN   " . $con->dbname . ".CATALOGO C on C.COD_CTA=N.CTA_IVA AND C.EST_LOG=1 AND C.EST_DEL=1
                    
                WHERE
                    $str_search
                    N.EST_LOG = 1 
                ORDER BY N.COD_PTO;";
        //// Code End

        $comando = $con->createCommand($sql);
        if (isset($search)) {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        if(isset($type_est) && $type_est !== "0"){
            $comando->bindParam(":typeA",$type_est, \PDO::PARAM_STR);
        }
        if(isset($type_emi) && $type_emi !== "0"){
            $comando->bindParam(":typeB",$type_emi, \PDO::PARAM_STR);
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
     * Get Last Id Item Record
     *
     * @return void
     */
    public static function getLastIdItemRecord() {
        $row = self::find()->select(['tipc_id'])->where(['tipc_estado_logico' => '1', 'tipc_estado' => '1'])->orderBy(['tipc_id' => SORT_DESC])->one();
        return $row['tipc_id'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord() {
        $row = self::find()->select(['tipc_id'])->where(['tipc_estado_logico' => '1', 'tipc_estado' => '1'])->orderBy(['tipc_id' => SORT_DESC])->one();
        $newId = 1 + $row['tipc_id'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }

    /**
     * Get all types of Sequence.
     *
     * @return mixed Return an Array of types Transactions
     */
    public static function getTypesSequence() {
        $arr_tipo = [
            '0' => financiero::t('tipodocumento', 'Manual'),
            '1' => financiero::t('tipodocumento', 'Automatic'),
        ];
        return $arr_tipo;
    }

    /**
     * Get all types of Sequence.
     *
     * @return mixed Return an Array of types Transactions
     */
    public static function getListTypeDoc() {
        $arr_tipo = [
            'V' => financiero::t('tipodocumento', 'Sales'),
            'C' => financiero::t('tipodocumento', 'Purchases'),
            'O' => financiero::t('tipodocumento', 'Others'),
        ];
        return $arr_tipo;
    }


    /**
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Item Name
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
    public function getAllItemsGridFechas($search, $type_est = NULL, $type_emi = NULL, $dataProvider = false, $extenderfecha = false){
        $search_cond = "%" . $search . "%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;
        $cols = "";
        //// Code Begin
        if (isset($search)) {
            $str_search .= "(N.NOM_NOF like :search) AND ";
        }
        if(isset($type_est) && $type_est !== "0"){
            $str_search .= " N.COD_PTO = :type_est AND ";
        }
        if(isset($type_emi) && $type_emi !== "0"){
            $str_search .= " N.COD_CAJ = :type_emi AND ";
        }
                   
        
        $cols .= "ES.COD_PTO as IdPunto, ";
        $cols .= "CONCAT(EM.COD_CAJ,'-',N.TIP_NOF) as IdCaja, ";
        $cols .= "CONCAT(ES.COD_PTO,' - ',ES.NOM_PTO) as CodPunto, ";
        $cols .= "CONCAT(EM.COD_CAJ,' - ',EM.NOM_CAJ) as CodCaja, ";
        $cols .= "N.TIP_NOF as TipNof, ";
        $cols .= "N.NUM_NOF as NumNof, ";
        $cols .= "N.NOM_NOF as NomNof, ";           
        $cols .= "N.FEC_NOF as FecNof, ";        
        $cols .= "N.DOC_AUT as DocAut, ";
        $cols .= "N.FEC_AUT as FecAut ";

        if ($extenderfecha) {
            $str_search .= " N.DOC_AUT = 1 AND ";//Muestra las Opciones para Extender Fechas
        }

     
        $sql = "SELECT
                    $cols
                FROM
                    " . $con->dbname . ".NOT_FAC N
                INNER JOIN   " . $con->dbname . ".MG0015 ES on ES.COD_PTO=N.COD_PTO AND ES.EST_LOG=1 AND ES.EST_DEL=1
                INNER JOIN   " . $con->dbname . ".MG0016 EM on EM.COD_CAJ=N.COD_CAJ AND EM.EST_LOG=1 AND EM.EST_DEL=1              
                    
                WHERE
                    $str_search
                    N.EST_LOG = 1 
                ORDER BY N.COD_PTO;";
        //// Code End

        $comando = $con->createCommand($sql);
        if (isset($search)) {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        if(isset($type_est) && $type_est !== "0"){
            $comando->bindParam(":type_est",$type_est, \PDO::PARAM_STR);
        }
        if(isset($type_emi) && $type_emi !== "0"){
            $comando->bindParam(":type_emi",$type_emi, \PDO::PARAM_STR);
        }
        
        $result = $comando->queryAll();
        if ($dataProvider) {
            $dataProvider = new ArrayDataProvider([
                'key' => 'IdCaja',
                'allModels' => $result,
                'pagination' => [
                    'pageSize' => 100,//Yii::$app->params["pageSize"],
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
