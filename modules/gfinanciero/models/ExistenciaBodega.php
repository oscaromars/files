<?php

namespace app\modules\gfinanciero\models;

use app\models\Utilities;
use Yii;

use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;
use Exception;

financiero::registerTranslations();

/**
 * This is the model class for table "IG0022".
 *
 * @property string $COD_BOD
 * @property string $COD_ART
 * @property float|null $I_I_UNI
 * @property float|null $I_I_COS
 * @property float|null $T_UI_AC
 * @property float|null $T_IC_AC
 * @property float|null $T_UE_AC
 * @property float|null $T_EC_AC
 * @property float|null $T_UR_AC
 * @property float|null $T_RC_AC
 * @property string|null $UBI_FIS
 * @property string|null $S_I_FIS
 * @property string|null $F_I_FIS
 * @property float|null $EXI_COM
 * @property float|null $EXI_TOT
 * @property float|null $P_COSTO
 * @property float|null $EXI_M01
 * @property float|null $EXI_M02
 * @property float|null $EXI_M03
 * @property float|null $EXI_M04
 * @property float|null $EXI_M05
 * @property float|null $EXI_M06
 * @property float|null $EXI_M07
 * @property float|null $EXI_M08
 * @property float|null $EXI_M09
 * @property float|null $EXI_M10
 * @property float|null $EXI_M11
 * @property float|null $EXI_M12
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string|null $USU_DES
 * @property float|null $P_LISTA
 * @property string|null $DET_COM
 * @property float|null $EXI_TEM
 * @property string|null $EST_ACT
 * @property float|null $EXI_ANT
 * @property float|null $EXI_HIS
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property IG0020 $cODART
 * @property IG0021 $cODBOD
 */
class ExistenciaBodega extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'IG0022';
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
            [['COD_BOD', 'COD_ART'], 'required'],
            [['I_I_UNI', 'I_I_COS', 'T_UI_AC', 'T_IC_AC', 'T_UE_AC', 'T_EC_AC', 'T_UR_AC', 'T_RC_AC', 'EXI_COM', 'EXI_TOT', 'P_COSTO', 'EXI_M01', 'EXI_M02', 'EXI_M03', 'EXI_M04', 'EXI_M05', 'EXI_M06', 'EXI_M07', 'EXI_M08', 'EXI_M09', 'EXI_M10', 'EXI_M11', 'EXI_M12', 'REG_ASO', 'P_LISTA', 'EXI_TEM', 'EXI_ANT', 'EXI_HIS'], 'number'],
            [['F_I_FIS', 'FEC_SIS'], 'safe'],
            [['COD_BOD'], 'string', 'max' => 2],
            [['COD_ART', 'UBI_FIS'], 'string', 'max' => 20],
            [['S_I_FIS', 'EST_ACT', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['HOR_SIS'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['USU_DES'], 'string', 'max' => 30],
            [['DET_COM'], 'string', 'max' => 500],
            [['COD_BOD', 'COD_ART'], 'unique', 'targetAttribute' => ['COD_BOD', 'COD_ART']],
            [['COD_ART'], 'exist', 'skipOnError' => true, 'targetClass' => Articulo::className(), 'targetAttribute' => ['COD_ART' => 'COD_ART']],
            [['COD_BOD'], 'exist', 'skipOnError' => true, 'targetClass' => Bodega::className(), 'targetAttribute' => ['COD_BOD' => 'COD_BOD']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_BOD' => financiero::t('bodega', 'Code'),
            'COD_ART' => financiero::t('bodega', 'Item Code'),
            'I_I_UNI' => financiero::t('bodega', 'Initial inventory'),
            'I_I_COS' => financiero::t('bodega', 'Initial cost'),
            'T_UI_AC' => 'T Ui Ac',
            'T_IC_AC' => 'T Ic Ac',
            'T_UE_AC' => 'T Ue Ac',
            'T_EC_AC' => 'T Ec Ac',
            'T_UR_AC' => 'T Ur Ac',
            'T_RC_AC' => 'T Rc Ac',
            'UBI_FIS' => financiero::t('bodega', 'Physical location'),
            'S_I_FIS' => 'S I Fis',//saldo inventrio fisico
            'F_I_FIS' => financiero::t('bodega', 'Physical Inventory Date'),
            'EXI_COM' => financiero::t('bodega', 'Compromised Existence'),
            'EXI_TOT' => financiero::t('bodega', 'Total Existence'),
            'P_COSTO' => financiero::t('bodega', 'Cost price'),
            'EXI_M01' => financiero::t('bodega', 'January'),
            'EXI_M02' => financiero::t('bodega', 'February'),
            'EXI_M03' => financiero::t('bodega', 'March'),
            'EXI_M04' => financiero::t('bodega', 'April'),
            'EXI_M05' => financiero::t('bodega', 'May'),
            'EXI_M06' => financiero::t('bodega', 'June'),
            'EXI_M07' => financiero::t('bodega', 'July'),
            'EXI_M08' => financiero::t('bodega', 'August'),
            'EXI_M09' => financiero::t('bodega', 'September'),
            'EXI_M10' => financiero::t('bodega', 'October'),
            'EXI_M11' => financiero::t('bodega', 'November'),
            'EXI_M12' => financiero::t('bodega', 'December'),
            'USU_DES' => 'Usu Des',
            'P_LISTA' => financiero::t('bodega', 'Price'),
            'DET_COM' => financiero::t('bodega', 'Compromised detail'),
            'EXI_TEM' => financiero::t('bodega', 'Temporary Existence'),
            'EST_ACT' => financiero::t('bodega', 'Current Existence'),
            'EXI_ANT' => financiero::t('bodega', 'Previous Existence'),
            'EXI_HIS' => financiero::t('bodega', 'Historical Existence'),
            'REG_ASO' => 'Reg Aso',
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),            
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[CODART]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODART()
    {
        return $this->hasOne(IG0020::className(), ['COD_ART' => 'COD_ART']);
    }

    /**
     * Gets query for [[CODBOD]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCODBOD()
    {
        return $this->hasOne(IG0021::className(), ['COD_BOD' => 'COD_BOD']);
    }
    
    
    /**
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Item Name
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
    
     public function getAllItemsGrid($search, $codBod = NULL, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(B.DES_COM like :search) AND ";
        }
        if(isset($codBod) && $codBod !== "0"){
            $str_search .= " A.COD_BOD = :codBod AND ";
        }
        
        //SELECT A.COD_BOD AS Id,A.COD_ART AS Cod_art,B.DES_COM AS Nombre,A.EXI_TOT AS ESTOCK,A.P_LISTA AS Precio
	//FROM db_gfinanciero.IG0022 A
//        INNER JOIN db_gfinanciero.IG0020 B ON A.COD_ART=B.COD_ART
    //WHERE A.EST_LOG=1 AND A.EST_DEL=1;
        
        $cols = "A.COD_BOD AS Id,A.COD_ART AS Cod_art,B.DES_COM AS Nombre,IF(B.TIP_PRO='I',A.EXI_TOT,0) AS Existencia,A.P_LISTA AS Precio ";
        if($export) $cols = "A.COD_BOD,A.COD_ART,B.DES_COM ,IF(B.TIP_PRO='I',A.EXI_TOT,0) AS Existencia,A.P_LISTA";
        $sql = "SELECT
                    $cols
                FROM
                    ".$con->dbname.".IG0022 AS A
                    INNER JOIN ".$con->dbname.".IG0020 AS B ON A.COD_ART=B.COD_ART
                WHERE
                    $str_search
                    A.EST_LOG=1 AND A.EST_DEL=1
                ORDER BY A.COD_ART;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($codBod) && $codBod !== "0"){
            $comando->bindParam(":codBod",$codBod, \PDO::PARAM_STR);
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
                    'attributes' => ['Nombre'],
                ],
            ]);
            return $dataProvider;
        }
        return $result;
    }
    

    /**
     * Get all items of Model by params to filter data.
     * @param  string $search   Search Item Name
     * @return mixed Return a Record Array or DataProvider
     */    
    public function getExistenciaData($cod_bod,$cod_art) {
        $con = Yii::$app->db_gfinanciero;        
        $sql = "SELECT  A.COD_BOD,C.NOM_BOD,A.COD_ART,B.DES_COM,A.I_I_UNI,A.I_I_COS,A.UBI_FIS,A.S_I_FIS,A.F_I_FIS,
                    A.EXI_COM,A.EXI_TOT,A.P_COSTO,A.P_LISTA,A.EXI_M01,A.EXI_M02,A.EXI_M03,A.EXI_M04,A.EXI_M05,
                    A.EXI_M06,A.EXI_M07,A.EXI_M08,A.EXI_M09,A.EXI_M10,A.EXI_M11,A.EXI_M12,A.USU_DES,A.DET_COM,
                    A.EXI_TEM,A.EST_ACT,A.EXI_ANT,A.EXI_HIS
                 FROM " . $con->dbname . ".IG0022 A
                       INNER JOIN " . $con->dbname . ".IG0020 B ON A.COD_ART=B.COD_ART
                       INNER JOIN " . $con->dbname . ".IG0021 C ON A.COD_BOD=C.COD_BOD
                   WHERE A.EST_LOG=1 AND A.EST_DEL=1 AND A.COD_BOD=:codbod AND A.COD_ART=:codart ;";

        //echo $sql;           
        $comando = $con->createCommand($sql);
        $comando->bindParam(":codbod", $cod_bod, \PDO::PARAM_STR);
        $comando->bindParam(":codart", $cod_art, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    
    /**
     * Get all items of Model by params to filter data.
     * @author Byron Villacreses <developer@uteg.edu.ec>
     * @param  string $search   Search Item Name
     * @return mixed Return a Record Array or DataProvider
     */  
    public static function getMostrarItems($CodArt) { 
        $con = Yii::$app->db_gfinanciero;
        $sql = "SELECT COD_ART "
                . " FROM " . $con->dbname . ".IG0020 "
                . " WHERE EST_LOG=1 AND EST_DEL=1 "; 
        $sql .= ($CodArt <> "") ? " AND COD_ART=:Codigo  " : " ";
        $sql .= " ORDER BY COD_ART ASC ";
        //$sql .= " LIMIT 10 ";
        $comando = $con->createCommand($sql);
        if($CodArt <>""){
            $comando->bindParam(":Codigo", $CodArt, \PDO::PARAM_STR);
        }
        //$comando->bindParam(":fecha_cita", date("Y-m-d", strtotime($Fecha)) , \PDO::PARAM_STR);
        return $comando->queryAll();
        
    }
    
    
    /**
     * Get all items of Model by params to filter data.
     * @author Byron Villacreses <developer@uteg.edu.ec>
     * @param  string $search   Search Item Name
     * @return mixed Return a Record Array or DataProvider
     */  
    public static function getContarExistncias($CodBod,$CodArt,$fec_ini,$fec_fin) { 
        $rawData = array();
        $con = Yii::$app->db_gfinanciero;        
        $sql = "(SELECT IFNULL(SUM(CAN_PED),0) AS CAN_ING, 0 AS CAN_EGR 
                    FROM " . $con->dbname . ".IG0026 WHERE COD_ART=:Codigo AND COD_BOD=:Bodega AND IND_EST <> 'A' 
				AND FEC_ING BETWEEN :fec_ini AND :fec_fin ) 
                UNION ALL 
                (SELECT 0 AS CAN_ING, IFNULL(SUM(CAN_PED),0) AS CAN_EGR 
                    FROM " . $con->dbname . ".IG0028 WHERE COD_ART=:Codigo AND COD_BOD=:Bodega AND IND_EST <> 'A' 
                        AND FEC_EGR BETWEEN :fec_ini AND :fec_fin ) ";
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":Codigo", $CodArt, \PDO::PARAM_STR);
        $comando->bindParam(":Bodega", $CodBod, \PDO::PARAM_STR);        
        $comando->bindParam(":fec_ini", date("Y-m-d", strtotime($fec_ini)) , \PDO::PARAM_STR);
        $comando->bindParam(":fec_fin", date("Y-m-d", strtotime($fec_fin)) , \PDO::PARAM_STR);
        $rawData=$comando->queryAll();
        $result['CAN_ING']=$rawData[0]['CAN_ING'];
        $result['CAN_EGR']=$rawData[1]['CAN_EGR'];
        return $result;
    }
    
     /**
     * Get all items of Model by params to filter data.
     * @author Byron Villacreses <developer@uteg.edu.ec>
     * @param  string $search   Search Item Name
     * @return mixed Return a Record Array or DataProvider
     */ 
    public static function actExisteciaBodega($con,$CodBod,$CodArt,$ExiMes,$Mes,$EstAct) {
        //Datos Adicionales 
        //$Mes=date("m", strtotime($Mes)); //Retorna 01-02 antepone 0      
        $sql = "UPDATE " . $con->dbname . ".IG0022 "
                . " SET EXI_M$Mes=IFNULL(I_I_UNI,0) + :EXI_MES ";
        $sql .= ($EstAct <> "0") ? " , EXI_TOT=IFNULL(I_I_UNI,0) + :EXI_MES  " : " ";
        $sql .= " WHERE COD_BOD=:COD_BOD AND COD_ART=:COD_ART "; 
        $command = $con->createCommand($sql);
        $command->bindParam(":COD_BOD", $CodBod, \PDO::PARAM_STR); //Id Comparacion
        $command->bindParam(":COD_ART", $CodArt, \PDO::PARAM_STR);
        $command->bindParam(":EXI_MES", floatval($ExiMes), \PDO::PARAM_STR);
        $command->execute();
    }
    
     /**
     * Get all items of Model by params to filter data.
     * @author Byron Villacreses <developer@uteg.edu.ec>
     * @param  string $search   Search Item Name
     * @return mixed Return a Record Array or DataProvider
     */ 
    public static function existeIMesBodega($CodArt,$Mes){
        $con = \Yii::$app->db_gfinanciero;            
        $sql = "SELECT SUM(IFNULL(EXI_M$Mes,0)) AS EXI_MES "
                . " FROM " . $con->dbname . ".IG0022 
                   WHERE COD_ART=:CodArt ;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":CodArt", $CodArt, \PDO::PARAM_STR);
        $rawData=$comando->queryScalar();
        if ($rawData === false)
            return 0; //Si no Existe suma devuelve 0 
        return $rawData;
    }
    
     /**
     * Get all items of Model by params to filter data.
     * @author Byron Villacreses <developer@uteg.edu.ec>
     * @param  string $search   Search Item Name
     * @return mixed Return a Record Array or DataProvider
     */ 
    public static function actExistMesMaestra($con,$CodArt,$ExiMes,$Mes,$EstAct) {
        //Datos Adicionales  
        $sql = "UPDATE " . $con->dbname . ".IG0020 "
                . " SET EXI_M$Mes=:EXI_MES,P_C_M$Mes=P_PROME ";
        $sql .= ($EstAct <> "0") ? " , EXI_TOT= :EXI_MES  " : " ";
        $sql .= " WHERE COD_ART=:COD_ART "; 
        $command = $con->createCommand($sql);
        $command->bindParam(":COD_ART", $CodArt, \PDO::PARAM_STR);
        $command->bindParam(":EXI_MES", floatval($ExiMes), \PDO::PARAM_STR);
        $command->execute();
    }
    
    /**
     * Get all items of Model by params to filter data.
     * @author Byron Villacreses <developer@uteg.edu.ec>
     * @param  string $search   Search Item Name
     * @return mixed Return a Record Array or DataProvider
     */  
    public static function getExistenciaCosto($CodBod,$CodArt,$CodLin,$CodTip,$CodMar,$TipPro) { 
        $con = Yii::$app->db_gfinanciero;
        $sql = "SELECT A.COD_BOD,A.COD_ART,B.DES_COM,B.COD_LIN,B.COD_TIP,B.COD_MAR,
                       B.TIP_PRO,B.P_PROME,B.P_COSTO,IF(B.TIP_PRO='I',A.EXI_TOT,0) EXI_TOT
                    FROM " . $con->dbname . ".IG0022 A
                        INNER JOIN " . $con->dbname . ".IG0020 B ON A.COD_ART=B.COD_ART
                WHERE A.EST_LOG=1 AND A.EST_DEL=1 AND A.COD_BOD=:CodBod ";
        $sql .= ($CodArt <> "") ? " AND B.COD_ART=:Codigo  " : " ";
        $sql .= ($CodLin <> "" AND $CodLin<>"0") ? " AND B.COD_LIN=:CodLin  " : " ";
        $sql .= ($CodTip <> "" AND $CodTip<>"0") ? " AND B.COD_TIP=:CodTip  " : " ";
        $sql .= ($CodMar <> "" AND $CodMar<>"0") ? " AND B.COD_MAR=:CodMar  " : " ";
        $sql .= ($TipPro <> "" AND $TipPro<>"0") ? " AND B.TIP_PRO=:TipPro  " : " ";        
        $sql .= " ORDER BY B.COD_ART ASC ";
        //$sql .= " LIMIT 10 ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":CodBod", $CodBod, \PDO::PARAM_STR);
        if($CodArt <>"" ){$comando->bindParam(":Codigo", $CodArt, \PDO::PARAM_STR);}
        if($CodLin <>"" AND $CodLin<>"0"){$comando->bindParam(":CodLin", $CodLin, \PDO::PARAM_STR);}
        if($CodTip <>"" AND $CodTip<>"0"){$comando->bindParam(":CodTip", $CodTip, \PDO::PARAM_STR);}
        if($CodMar <>"" AND $CodMar<>"0"){$comando->bindParam(":CodMar", $CodMar, \PDO::PARAM_STR);}
        if($TipPro <>"" AND $TipPro<>"0"){$comando->bindParam(":TipPro", $TipPro, \PDO::PARAM_STR);}
        return $comando->queryAll();
        
    }

     /** 
     * Funcion to update item Reserve to Cellar
     * 
     * @param   string  $codeArt    Code Item/Article
     * @param   string  $codeBod    Code Cellar
     * @param   string  $type       Type Transaction. Default 1 -> Reserve. May Be 0 -> Unreserve
     * @param   int     $cant       Number of item to reserve o unreserve.
     * @return bool     True if OK else NOOK.
     */
    public static function reservaItemBodega($codeArt, $codeBod, $cant, $type = '1') {
        try{
            $con = \Yii::$app->db_gfinanciero; 
            $fieldSec = "-";
            if($type == "1")   $fieldSec = "+";

            $sql = "
                UPDATE " . $con->dbname . ".IG0022 
                    SET EXI_COM = IF((EXI_COM $fieldSec :cant) < 0, 0, (EXI_COM $fieldSec :cant))
                WHERE 
                    COD_ART=:codeArt AND COD_BOD=:codeBod AND 
                    EST_LOG = 1 AND EST_DEL = 1";
            $comando = $con->createCommand($sql);
            $comando->bindParam(":codeArt", $codeArt, \PDO::PARAM_STR);
            $comando->bindParam(":codeBod", $codeBod, \PDO::PARAM_STR);
            $comando->bindParam(":cant", $cant, \PDO::PARAM_INT);
            $rawData = $comando->execute();
            if(!isset($rawData) || $rawData < 0){
                return false;
            }
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    
     /** 
     * Funcion to update item Reserve to Cellar
     * 
     * @param   string  $codeBod    Code Cellar
     * @param   string  $codeArt    Code Item/Article
     * @return int     Number of items in Cellars by Code.
     */
    public static function existeItemExistenciaBodega($codBod, $codArt) {
        $con = \Yii::$app->db_gfinanciero;      
        $sql = "SELECT 
                    COUNT(*) as cant 
                FROM " . $con->dbname . ".IG0022 
                WHERE 
                    COD_ART = :codArt AND COD_BOD = :codBod AND
                    EST_LOG=1 AND EST_DEL=1 "; 
        $comando = $con->createCommand($sql);
        $comando->bindParam(":codArt", $codArt, \PDO::PARAM_STR);
        $comando->bindParam(":codBod", $codBod, \PDO::PARAM_STR);
        return $comando->queryOne();
    }

    /** 
     * Funcion to remove reserve item to Cellar
     * 
     * @param   string  $codeBod    Code Cellar
     * @param   mixed  $arrCant    Array of Items. Format must be: $data = [['code' => 00231, 'cant' => '3'], ['code' => 02455, 'cant' => '5']]
     * @return mixed     Array Value for response.
     */
    public static function removeAllArrayReserveItems($codBod, $arrCant){
        $con = \Yii::$app->db_gfinanciero;
        $response = ["error" => false, "msg" => financiero::t('egresomercaderia', "Items have been unreserved.")];
        try{
            $transaction = $con->beginTransaction();
            for($i = 0; $i < count($arrCant); $i++){
                $codeArt = $arrCant[$i]['code'];
                $cant = $arrCant[$i]['cant'];
                $result = self::reservaItemBodega($codeArt, $codBod, $cant, "0");
                if(!$result){
                    throw new \Exception(financiero::t('egresomercaderia', "Error for code '{code}' to unreserve {cant} Item(s).",
                    ['code' => "<b>".$codeArt."</b>", 'cant' => "<b>".$cant."</b>", ]
                    ));
                }
            }
            if ($transaction !== null)  $transaction->commit();
        }catch(Exception $e){
            $response = ["error" => true, "msg" => $e->getMessage()];
            if ($transaction !== null)  $transaction->rollback();
            return $response;
        }
        return $response;
    }

    /** 
     * Funcion to Edit Stock Cellar
     * 
     * @param   string  $codeBod    Code Cellar
     * @param   string  $codArt    Code Item
     * @param   string  $pReferencia    Reference Price. P_COSTO
     * @param   string  $pProveedor    Provider Price. P_LISTA
     * @param   string  $cantPed    Amount items 
     * @param   string  $type    If "EG" is a Egress Transaction or "IN" es Ingress Transaction
     * @return bool     True => OK and False => NOOK.
     */
    public static function modificarStockBodega($codBod, $codArt, $pReferencia, $pProveedor, $cantPed, $type = "EG"){
        try{
            $con = \Yii::$app->db_gfinanciero; 
            $setField = "";
            $costoT = $pReferencia * $cantPed;
            if($type == "IN"){
                $setField .= "T_UI_AC = T_UI_AC + :cantPed , ";
                $setField .= "T_IC_AC = T_IC_AC + :costoT , ";
                $setField .= "P_COSTO = :pReferencia , ";
                $setField .= "P_LISTA = :pProveedor , ";
                $setField .= "EXI_TOT = EXI_TOT + :cantPed ";
            }else{
                $setField .= "T_UE_AC = T_UE_AC + :cantPed , ";
                $setField .= "T_EC_AC = T_EC_AC + :costoT , ";
                $setField .= "EXI_TOT = EXI_TOT - :cantPed ";
            }

            $sql = "
                UPDATE " . $con->dbname . ".IG0022 
                    SET $setField 
                WHERE 
                    COD_ART=:codArt AND COD_BOD=:codBod AND 
                    EST_LOG = 1 AND EST_DEL = 1";
            $comando = $con->createCommand($sql);
            $comando->bindParam(":codArt", $codArt, \PDO::PARAM_STR);
            $comando->bindParam(":codBod", $codBod, \PDO::PARAM_STR);
            $comando->bindParam(":cantPed", $cantPed, \PDO::PARAM_STR);
            $comando->bindParam(":costoT", $costoT, \PDO::PARAM_STR);
            if($type == "IN"){
                $comando->bindParam(":pReferencia", $pReferencia, \PDO::PARAM_STR);
                $comando->bindParam(":pProveedor", $pProveedor, \PDO::PARAM_STR);
            }
            $rawData = $comando->execute();
            if(!isset($rawData) || $rawData < 0){
                return false;
            }
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    /** 
     * Funcion to Reverse Stock Cellar
     * 
     * @param   string  $codeBod    Code Cellar
     * @param   string  $codArt    Code Item
     * @param   string  $pReferencia    Reference Price. P_COSTO
     * @param   string  $pProveedor    Provider Price. P_LISTA
     * @param   string  $cantPed    Amount items 
     * @param   string  $type    If "EG" is a Egress Transaction or "IN" es Ingress Transaction
     * @return bool     True => OK and False => NOOK.
     */
    public static function reversarStockBodega($codBod, $codArt, $pReferencia, $pProveedor, $cantPed, $type = "EG"){
        try{
            $con = \Yii::$app->db_gfinanciero; 
            $setField = "";
            $costoT = $pReferencia * $cantPed;
            if($type == "IN"){
                //$setField .= "T_UI_AC = T_UI_AC - :cantPed , ";
                //$setField .= "T_IC_AC = T_IC_AC - :costoT , ";
                //$setField .= "P_COSTO = :pReferencia , ";
                //$setField .= "P_LISTA = :pProveedor , ";
                $setField = "EXI_TOT = EXI_TOT - :cantPed ";
            }else{
                //$setField .= "T_UE_AC = T_UE_AC - :cantPed , ";
                //$setField .= "T_EC_AC = T_EC_AC - :costoT , ";
                $setField = "EXI_TOT = EXI_TOT + :cantPed ";
            }

            $sql = "
                UPDATE " . $con->dbname . ".IG0022 
                    SET $setField 
                WHERE 
                    COD_ART=:codArt AND COD_BOD=:codBod AND 
                    EST_LOG = 1 AND EST_DEL = 1";
            $comando = $con->createCommand($sql);
            $comando->bindParam(":codArt", $codArt, \PDO::PARAM_STR);
            $comando->bindParam(":codBod", $codBod, \PDO::PARAM_STR);
            $comando->bindParam(":cantPed", $cantPed, \PDO::PARAM_STR);
            //$comando->bindParam(":costoT", $costoT, \PDO::PARAM_STR);
            if($type == "IN"){
                //$comando->bindParam(":pReferencia", $pReferencia, \PDO::PARAM_STR);
                //$comando->bindParam(":pProveedor", $pProveedor, \PDO::PARAM_STR);
            }
            $rawData = $comando->execute();
            if(!isset($rawData) || $rawData < 0){
                return false;
            }
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    
    
    /**
     * Get all items of Model by params to filter data.
     * @param  string $search   Search Item Name
     * @return mixed Return a Record Array or DataProvider
     */  
    public static function getRepExistenciaBodega($CodBod,$CodArt,$CodLin,$CodTip,$CodMar,$TipPro) { 
        $con = Yii::$app->db_gfinanciero;
        //Retorna las bodegas para la consulta
        $cols  = "";
        if($CodBod=="0"){
             $objBod=Bodega::getBodegas();
             for ($i = 0; $i < sizeof($objBod); $i++)  {
                 $CodBod = isset($objBod[$i]['Ids']) ? trim($objBod[$i]['Ids']) : "";
                 $cols  .= ",(SELECT IF(A.TIP_PRO='I',A.EXI_TOT,0) EXI_TOT FROM " . $con->dbname . ".IG0022 "
                    . "     WHERE COD_BOD='$CodBod' AND COD_ART=A.COD_ART) EXI_B$CodBod ";
                 
             }
        }else{
            $cols  .= ",(SELECT IF(A.TIP_PRO='I',A.EXI_TOT,0) EXI_TOT FROM " . $con->dbname . ".IG0022 "
                    . "     WHERE COD_BOD='$CodBod' AND COD_ART=A.COD_ART) EXI_B$CodBod ";
        }
       
        
        $sql = "SELECT A.COD_ART,A.DES_COM,A.COD_LIN,B.NOM_LIN,A.COD_TIP,C.NOM_TIP,A.COD_MAR,D.NOM_MAR
                        $cols
            FROM " . $con->dbname . ".IG0020 A
                    INNER JOIN " . $con->dbname . ".IG0001 B ON B.COD_LIN=A.COD_LIN
                    INNER JOIN " . $con->dbname . ".IG0002 C ON C.COD_TIP=A.COD_TIP
                    INNER JOIN " . $con->dbname . ".IG0003 D ON D.COD_MAR=A.COD_MAR
        WHERE A.EST_LOG=1 AND A.EST_DEL=1 ";
        
       
        $sql .= ($CodArt <> "") ? " AND A.COD_ART=:Codigo  " : " ";
        $sql .= ($CodLin <> "" AND $CodLin<>"0") ? " AND A.COD_LIN=:CodLin  " : " ";
        $sql .= ($CodTip <> "" AND $CodTip<>"0") ? " AND A.COD_TIP=:CodTip  " : " ";
        $sql .= ($CodMar <> "" AND $CodMar<>"0") ? " AND A.COD_MAR=:CodMar  " : " ";
        $sql .= ($TipPro <> "" AND $TipPro<>"0") ? " AND A.TIP_PRO=:TipPro  " : " ";        
        $sql .= " ORDER BY A.COD_ART ASC ";
        //$sql .= " LIMIT 10 ";
        
        $comando = $con->createCommand($sql);
        //Utilities::putMessageLogFile($sql);
        //$comando->bindParam(":CodBod", $CodBod, \PDO::PARAM_STR);
        if($CodArt <>"" ){$comando->bindParam(":Codigo", $CodArt, \PDO::PARAM_STR);}
        if($CodLin <>"" AND $CodLin<>"0"){$comando->bindParam(":CodLin", $CodLin, \PDO::PARAM_STR);}
        if($CodTip <>"" AND $CodTip<>"0"){$comando->bindParam(":CodTip", $CodTip, \PDO::PARAM_STR);}
        if($CodMar <>"" AND $CodMar<>"0"){$comando->bindParam(":CodMar", $CodMar, \PDO::PARAM_STR);}
        if($TipPro <>"" AND $TipPro<>"0"){$comando->bindParam(":TipPro", $TipPro, \PDO::PARAM_STR);}
        return $comando->queryAll();
        
    }

    /** 
     * Funcion to Reverse Stock Cellar
     * 
     * @param   string  $codeBod    Code Cellar
     * @param   string|NULL  $numExistencia  Value to identify if query have a EXI_TOT = 0 or > 0 or All.
     * @param   bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
    public static function getListaArticulosFisicosBodega($codBod, $numExistencia = NULL, $dataProvider=true){
        $con = Yii::$app->db_gfinanciero;
        $search = '';
        if(isset($numExistencia) && $numExistencia === "1"){
            $search = "E.EXI_TOT = 0 AND ";
        }elseif(isset($numExistencia) && $numExistencia === "2"){
            $search = "E.EXI_TOT > 0 AND ";
        }
        $sql = "SELECT 
                    A.COD_ART AS Code,
                    A.DES_COM AS Name,
                    IFNULL(E.EXI_TEM, '0.00') AS EFisica,
                    IFNULL(E.EXI_TOT, '0.00') AS ETotal,
                    IFNULL(E.EST_ACT, '') AS Estado
                FROM " . $con->dbname . ".IG0022 E
                    INNER JOIN " . $con->dbname . ".IG0020 A ON A.COD_ART = E.COD_ART
                WHERE
                    $search
                    E.COD_BOD=:codBod AND 
                    E.EST_LOG=1 AND E.EST_DEL=1 AND 
                    A.EST_LOG=1 AND A.EST_DEL=1  
                ORDER BY A.COD_ART ASC ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":codBod", $codBod, \PDO::PARAM_STR);
        $result = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Code',
                'allModels' => $result,
                'pagination' => [
                    'pageSize' => count($result) + 1,
                ],
                'sort' => [
                    'attributes' => ['Code'],
                ],
            ]);
            return $dataProvider;
        }
        return $result;
    }

    /** 
     * Funcion to Reverse Stock Cellar
     * 
     * @param   string  $codeBod    Code Cellar
     * @param   string|NULL  $numExistencia  Value to identify if query have a EXI_TOT = 0 or > 0 or All.
     * @return bool     True => OK and False => NOOK.
     */
    public static function setTemporalStockWithCurrentStock($codBod, $numExistencia = NULL){
        try{
            $con = \Yii::$app->db_gfinanciero; 
            $search = '';
            if(isset($numExistencia) && $numExistencia === "1"){
                $search = "EXI_TOT = 0 AND ";
            }elseif(isset($numExistencia) && $numExistencia === "2"){
                $search = "EXI_TOT > 0 AND ";
            }
            $sql = "
                UPDATE " . $con->dbname . ".IG0022 
                    SET  EXI_TEM = EXI_TOT, EST_ACT = 'M' 
                WHERE 
                    $search
                    COD_BOD = :codBod AND 
                    EST_LOG = 1 AND EST_DEL = 1";
            $comando = $con->createCommand($sql);
            $comando->bindParam(":codBod", $codBod, \PDO::PARAM_STR);
            $rawData = $comando->execute();
            if(!isset($rawData) || $rawData < 0){
                return false;
            }
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    /** 
     * Funcion to Reverse Stock Cellar
     * 
     * @param   string  $codeBod    Code Cellar
     * @param   string|NULL  $numExistencia  Value to identify if query have a EXI_TOT = 0 or > 0 or All.
     * @return mixed Return a Record Array or DataProvider
     */
    public static function getCantItemsComprometidosXBodega($codBod, $numExistencia = NULL){
        $con = Yii::$app->db_gfinanciero;
        $search = '';
        if(isset($numExistencia) && $numExistencia === "1"){
            $search = "E.EXI_TOT = 0 AND ";
        }elseif(isset($numExistencia) && $numExistencia === "2"){
            $search = "E.EXI_TOT > 0 AND ";
        }
        $sql = "SELECT 
                    COUNT(*) AS Cant
                FROM " . $con->dbname . ".IG0022 E
                WHERE
                    $search
                    E.EXI_COM > 0 AND
                    E.COD_BOD = :codBod AND 
                    E.EST_LOG = 1 AND E.EST_DEL = 1 ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":codBod", $codBod, \PDO::PARAM_STR);
        $result = $comando->queryOne();
        return $result['Cant'];
    }

    /** 
     * Funcion to Get Total Stock By article in all Cellars
     * 
     * @param   string      $articulo       Code Article or Item
     * @param   string|NULL  $numExistencia  Value to identify if query have a EXI_TOT = 0 or > 0 or All if NULL.
     * @param   mixed|NULL  $excluirBod    Array Cellars to exclude. Defualt NULL. Example: ['CODOD1', 'COD_BOD2']
     * @return mixed Return a Record Array
     */
    public static function getExistenciasTotalesEnBodegas($articulo = NULL, $numExistencia = NULL, $excluirBod = NULL ){
        $con = Yii::$app->db_gfinanciero;
        $search = '';
        $exclude = '';
        $params = array();
        if(isset($numExistencia) && $numExistencia === "1"){
            $search .= "E.EXI_TOT = 0 AND ";
        }elseif(isset($numExistencia) && $numExistencia === "2"){
            $search .= "E.EXI_TOT > 0 AND ";
        }
        if(isset($excluirBod) && count($excluirBod) > 0){
            for($i=0; $i < count($excluirBod); $i++){
                $exclude .= " E.COD_BOD <> :p$i AND ";
            }
        }
        if(isset($articulo)){
            $search .= "E.COD_ART = :articulo AND  ";
        }
        $sql = "SELECT 
                    E.COD_ART AS Code,
                    SUM(E.EXI_TOT) AS Total
                FROM " . $con->dbname . ".IG0022 E
                WHERE
                    $search
                    $exclude
                    E.EST_LOG = 1 AND E.EST_DEL = 1 
                GROUP BY E.COD_ART";
        $comando = $con->createCommand($sql);
        if(isset($excluirBod) && count($excluirBod) > 0){
            for($i=0; $i < count($excluirBod); $i++){
                $params[$i] = $excluirBod[$i];
                $comando->bindParam(":p$i", $params[$i], \PDO::PARAM_STR);
            }
        }
        if(isset($articulo)){
            $comando->bindParam(":articulo", $articulo, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
        return $result;
    }

    /** 
     * Funcion to Get Total Stock By article in all Cellars
     * 
     * @return mixed Return a Record Array
     */
    public static function getInventarioValorizadoXBodega(){
        $con = Yii::$app->db_gfinanciero;
        $cols = '';
        $from = '';
        $colsCTotal = '(';
        $colsTTotal = '(';
        $groupby = '';
        $arr_models = Bodega::find()->select(['COD_BOD'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_BOD' => SORT_ASC])->asArray()->all();
        for($i=0; $i<count($arr_models); $i++){
            $cols .= " IFNULL(B$i.EXI_TOT, 0.00) AS ExiB$i, ";
            $from .= " LEFT JOIN ( SELECT COD_BOD, COD_ART, EXI_TOT FROM ". $con->dbname . ".IG0022 WHERE COD_BOD = '".$arr_models[$i]['COD_BOD']."' AND EST_LOG = 1 AND EST_DEL = 1 ) B$i ON B$i.COD_ART = E.COD_ART ";
            if($i == 0) $colsCTotal .= " IFNULL(B$i.EXI_TOT, 0.00) ";
            else    $colsCTotal .= " + IFNULL(B$i.EXI_TOT, 0.00) ";
            $groupby .= ", IFNULL(B$i.EXI_TOT, 0.00) ";
        }
        $groupby .= ',' . $colsCTotal . "), (" . $colsCTotal . ") * IFNULL(A.P_PROME, 0.0000)) ";
        $colsTTotal .= $colsCTotal . ") * IFNULL(A.P_PROME, 0.0000)) AS TTotal ";
        $colsCTotal .= ") AS TCant, ";
        
        $sql = "SELECT 
                    E.COD_ART AS Code,
                    A.DES_COM AS Description,
                    IFNULL(A.P_PROME, 0.0000) AS PPromedio,
                    $cols
                    $colsCTotal
                    $colsTTotal
                FROM " . $con->dbname . ".IG0022 E
                    INNER JOIN " . $con->dbname . ".IG0020 A ON A.COD_ART = E.COD_ART
                    $from
                WHERE
                    E.EST_LOG = 1 AND E.EST_DEL = 1 
                GROUP BY E.COD_ART, A.DES_COM, A.P_PROME $groupby
                ORDER BY E.COD_ART ASC";
        $comando = $con->createCommand($sql);
        $result = $comando->queryAll();
        $currency = Yii::$app->params['currency'];
        for($i=0; $i < count($result); $i++){
            $result[$i]['PPromedio'] = $currency . number_format($result[$i]['PPromedio'], 2, '.', ',');
            $result[$i]['TTotal'] = $currency . number_format($result[$i]['TTotal'], 2, '.', ',');
        }
        return $result;
    }

    /** 
     * Funcion to Get Total Stock By article By Cellar
     * 
     * @param   string  $codeBod    Code Cellar
     * @param   string|NULL  $numExistencia  Value to identify if query have a EXI_TOT = 0 or > 0 or All.
     * @return mixed Return a Record Array
     */
    public static function getArticuloTomaFisicaXBodega($codBod, $numExistencia = NULL){
        $con = Yii::$app->db_gfinanciero;
        $search = '';
        if(isset($numExistencia) && $numExistencia === "1"){
            $search = "E.EXI_TOT = 0 AND ";
        }elseif(isset($numExistencia) && $numExistencia === "2"){
            $search = "E.EXI_TOT > 0 AND ";
        }
        $sql = "SELECT 
                    E.COD_ART AS Code,
                    A.DES_COM AS Description,
                    IFNULL(E.EXI_TOT, 0.00) AS Stock,
                    IFNULL(E.EXI_TEM, 0.00) AS Conteo,
                    (IFNULL(E.EXI_TOT, 0.00) - IFNULL(E.EXI_TEM, 0.00)) AS DCant,
                    IFNULL(A.P_PROME, 0.0000) AS PPromedio,
                    (IFNULL(E.EXI_TOT, 0.00) * IFNULL(A.P_PROME, 0.0000)) AS CostoStock,
                    (IFNULL(E.EXI_TEM, 0.00) * IFNULL(A.P_PROME, 0.0000)) AS CostoConteo,
                    ((IFNULL(E.EXI_TOT, 0.00) * IFNULL(A.P_PROME, 0.0000)) - (IFNULL(E.EXI_TEM, 0.00) * IFNULL(A.P_PROME, 0.0000))) AS CostoDiff,
                    IFNULL(E.EST_ACT,'') AS Estado
                FROM " . $con->dbname . ".IG0022 E
                    INNER JOIN " . $con->dbname . ".IG0020 A ON A.COD_ART = E.COD_ART
                WHERE
                    $search
                    E.COD_BOD = :codBod AND 
                    A.EST_LOG = 1 AND A.EST_DEL = 1 AND 
                    E.EST_LOG = 1 AND E.EST_DEL = 1 ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":codBod", $codBod, \PDO::PARAM_STR);
        $result = $comando->queryAll();
        $currency = Yii::$app->params['currency'];
        for($i=0; $i < count($result); $i++){
            $result[$i]['PPromedio'] = $currency . number_format($result[$i]['PPromedio'], 2, '.', ',');
            $result[$i]['CostoDiff'] = $currency . number_format($result[$i]['CostoDiff'], 2, '.', ',');
            $result[$i]['CostoStock'] = $currency . number_format($result[$i]['CostoStock'], 2, '.', ',');
            $result[$i]['CostoConteo'] = $currency . number_format($result[$i]['CostoConteo'], 2, '.', ',');
            $result[$i]['Stock'] = number_format($result[$i]['Stock'], 2, '.', ',');
            $result[$i]['Conteo'] = number_format($result[$i]['Conteo'], 2, '.', ',');
            $result[$i]['DCant'] = number_format($result[$i]['DCant'], 2, '.', ',');
        }
        return $result;
    }

    /** 
     * Funcion to Get Total Stock By Cellar
     * 
     * @param   string  $codeBod    Code Cellar
     * @param   string|NULL  $numExistencia  Value to identify if query have a EXI_TOT = 0 or > 0 or All.
     * @return mixed Return a Record Array
     */
    public static function getArticulosExistenciaXBodega($codBod, $numExistencia = NULL){
        $con = Yii::$app->db_gfinanciero;
        $search = '';
        if(isset($numExistencia) && $numExistencia === "1"){
            $search = "E.EXI_TOT = 0 AND ";
        }elseif(isset($numExistencia) && $numExistencia === "2"){
            $search = "E.EXI_TOT > 0 AND ";
        }
        $sql = "SELECT 
                    E.COD_ART AS Code,
                    A.DES_COM AS Description,
                    IFNULL(E.EXI_TOT, 0.00) AS Stock,
                    '' AS Observacion,
                    IFNULL(E.EST_ACT,'') AS Estado
                FROM " . $con->dbname . ".IG0022 E
                    INNER JOIN " . $con->dbname . ".IG0020 A ON A.COD_ART = E.COD_ART
                WHERE
                    $search
                    E.COD_BOD = :codBod AND 
                    A.EST_LOG = 1 AND A.EST_DEL = 1 AND 
                    E.EST_LOG = 1 AND E.EST_DEL = 1 ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":codBod", $codBod, \PDO::PARAM_STR);
        $result = $comando->queryAll();
        for($i=0; $i < count($result); $i++){
            $result[$i]['Stock'] = number_format($result[$i]['Stock'], 2, '.', ',');
        }
        return $result;
    }

}
