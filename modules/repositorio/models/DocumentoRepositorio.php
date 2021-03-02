<?php

namespace app\modules\repositorio\models;
use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "documento_repositorio".
 *
 * @property int $dre_id
 * @property int $est_id
 * @property int $dre_tipo
 * @property string $dre_codificacion
 * @property string $dre_ruta
 * @property string $dre_imagen
 * @property int $dre_usu_modifica
 * @property string $dre_estado
 * @property string $dre_fecha_archivo
 * @property string $dre_fecha_creacion
 * @property string $dre_fecha_modificacion
 * @property string $dre_estado_logico
 *
 * @property Estandar $est
 */
class DocumentoRepositorio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documento_repositorio';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_repositorio');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['est_id', 'dre_codificacion', 'dre_ruta', 'dre_imagen', 'dre_estado', 'dre_estado_logico'], 'required'],
            [['est_id', 'dre_tipo', 'dre_usu_modifica'], 'integer'],
            [['dre_fecha_archivo', 'dre_fecha_creacion', 'dre_fecha_modificacion'], 'safe'],
            [['dre_codificacion', 'dre_imagen'], 'string', 'max' => 100],
            [['dre_ruta'], 'string', 'max' => 200],
            [['dre_estado', 'dre_estado_logico'], 'string', 'max' => 1],
            [['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estandar::className(), 'targetAttribute' => ['est_id' => 'est_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dre_id' => 'Dre ID',
            'est_id' => 'Est ID',
            'dre_tipo' => 'Dre Tipo',
            'dre_codificacion' => 'Dre Codificacion',
            'dre_ruta' => 'Dre Ruta',
            'dre_imagen' => 'Dre Imagen',
            'dre_usu_modifica' => 'Dre Usu Modifica',
            'dre_estado' => 'Dre Estado',
            'dre_fecha_archivo' => 'Dre Fecha Archivo',
            'dre_fecha_creacion' => 'Dre Fecha Creacion',
            'dre_fecha_modificacion' => 'Dre Fecha Modificacion',
            'dre_estado_logico' => 'Dre Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEst()
    {
        return $this->hasOne(Estandar::className(), ['est_id' => 'est_id']);
    }
    
    
     /**
     * Function consultarDocumentos consultar documentos
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param 
     * @return
     */
    public function consultarDocumentos($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_repositorio;        
        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if (($arrFiltro['est_id'] != "") && ($arrFiltro['est_id'] > 0)) {
                $str_search = "and dr.est_id = :est_id ";
            }
            if ($arrFiltro['search'] != "") {
                $str_search .= "and dr.dre_imagen like :archivo ";                
            }
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "and dr.dre_fecha_archivo >= :fec_ini and ";
                $str_search .= "dr.dre_fecha_archivo <= :fec_fin ";
            }            
            if (($arrFiltro['mod_id'] != "") && ($arrFiltro['mod_id'] > 0)){
                $str_search .= "and f.mod_id = :mod_id ";
            }
            if (($arrFiltro['cat_id'] != "") && ($arrFiltro['cat_id'] > 0)){
                $str_search .= "and e.fun_id = :fun_id ";
            }
            if (($arrFiltro['comp_id'] != "") && ($arrFiltro['comp_id'] > 0)){
                $str_search .= "and e.com_id = :comp_id ";
            }
        }
        $sql = "SELECT dre_imagen, case when dre_tipo='1' then 'Privado' else 'Público' end tipo,  
                        dre_descripcion, dre_fecha_archivo, dre_fecha_creacion ";
        if ($onlyData==false) {
            $sql .= ", dre_ruta, dre_id ";
        } 
        $sql .= "FROM " . $con->dbname . ".documento_repositorio dr inner join " . $con->dbname . ".estandar e on e.est_id = dr.est_id
                    left join " . $con->dbname . ".componente c on c.com_id = e.com_id
                    inner join " . $con->dbname . ".funcion f on f.fun_id = e.fun_id
                WHERE dre_estado = :estado
                      and dre_estado_logico = :estado ";              
        if (!empty($str_search)) {
            $sql .= " $str_search  
                    ORDER BY dre_fecha_creacion desc ";
        } else {
            $sql .= " ORDER BY dre_fecha_creacion desc";
        }
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $est_id = $arrFiltro["est_id"];
            $archivo = "%" . $arrFiltro["search"] . "%";   
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
            $mod_id = $arrFiltro["mod_id"];
            $fun_id = $arrFiltro["cat_id"];
            $comp_id = $arrFiltro["comp_id"];
            if (($arrFiltro['est_id'] != "") or ($arrFiltro['est_id'] > 0)) {
                $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
            }
            if ($arrFiltro['search'] != "") {
                $comando->bindParam(":archivo", $archivo, \PDO::PARAM_STR);
            } 
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if (($arrFiltro['mod_id'] != "") && ($arrFiltro['mod_id'] > 0)){
                $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
            }
            if (($arrFiltro['cat_id'] != "") && ($arrFiltro['cat_id'] > 0)){
                $comando->bindParam(":fun_id", $fun_id, \PDO::PARAM_INT);
            }
            if (($arrFiltro['comp_id'] != "") && ($arrFiltro['comp_id'] > 0)){
                $comando->bindParam(":comp_id", $comp_id, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }
    
    /* INSERTAR DATOS */
    public function insertarDataDocumentos($data) {
        $arroout = array();
        $con = \Yii::$app->db_repositorio;        
        $trans = $con->beginTransaction();
        try {
            //$per_id = @Yii::$app->session->get("PB_perid");    
            $data = isset($data['DATA']) ? $data['DATA'] : array();
            $this->insertarDocumentos($con,json_decode($data));
            $trans->commit();
            $con->close();
            //RETORNA DATOS 
            //$arroout["ids"]= $ftem_id;
            $arroout["status"]= true;
            //$arroout["secuencial"]= $doc_numero;
            
                       
            return $arroout;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();
            //throw $e;
            $arroout["status"]= false;
            return $arroout;
        }
    }
    
    private function insertarDocumentos($con,$dts) {
        //dre_id
        $usu_id = @Yii::$app->session->get("PB_iduser");
        for ($i = 0; $i < sizeof($dts); $i++) {
            $sql = "INSERT INTO " . $con->dbname . ".documento_repositorio
                    (est_id,dre_tipo,dre_codificacion,dre_ruta,dre_imagen,dre_descripcion,
                        dre_usu_ingresa,dre_estado,dre_fecha_archivo,dre_estado_logico)VALUES
                    (:est_id,:dre_tipo,:dre_codificacion,:dre_ruta,:dre_imagen,:dre_descripcion,
                        :dre_usu_ingresa,:dre_estado,:dre_fecha_archivo,:dre_estado_logico)";
            $command = $con->createCommand($sql);
            $command->bindParam(":est_id", $dts[$i]->est_id, \PDO::PARAM_INT);
            $command->bindParam(":dre_tipo", $dts[$i]->dre_tipo, \PDO::PARAM_INT);
            $command->bindParam(":dre_codificacion", $dts[$i]->dre_codificacion, \PDO::PARAM_STR);
            $command->bindParam(":dre_ruta", $dts[$i]->dre_ruta, \PDO::PARAM_STR);
            $command->bindParam(":dre_imagen", $dts[$i]->dre_imagen, \PDO::PARAM_STR);
            $command->bindParam(":dre_descripcion", ucwords(strtolower($dts[$i]->dre_descripcion)), \PDO::PARAM_STR);
            $command->bindParam(":dre_usu_ingresa", $usu_id, \PDO::PARAM_INT);
            $command->bindParam(":dre_estado", $dts[$i]->dre_estado, \PDO::PARAM_INT);
            $command->bindParam(":dre_fecha_archivo", $dts[$i]->dre_fecha_archivo, \PDO::PARAM_STR);            
            $command->bindParam(":dre_estado_logico", $dts[$i]->dre_estado_logico, \PDO::PARAM_INT);
            //$command->bindParam(":per_nombre", $data[0]['per_nombre'], \PDO::PARAM_STR);
            $command->execute();
        }
        
    }
        
    public function consultarXdocumentoid($ids){        
        $con = \Yii::$app->db_repositorio;        
        $estado = 1;
        $sql = "SELECT 
                    dre_id, dre_ruta, dre_imagen                       
                FROM " . $con->dbname . ".documento_repositorio dr                                        
                WHERE dre_id = :dre_id AND  
                    dre_estado_logico=:estado AND 
                    dre_estado=:estado";        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $comando->bindParam(":dre_id", $ids, \PDO::PARAM_INT);        
        $resultData = $comando->queryOne();
        return $resultData;          
    }
    
   
    
    public function modificarXdocumentoid($ids, $usu_id){        
        $con = \Yii::$app->db_repositorio;        
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $sql = "UPDATE " . $con->dbname . ".documento_repositorio
                SET dre_estado = '0',
                    dre_estado_logico = '0',
                    dre_fecha_modificacion = :fecha,
                    dre_usu_modifica = :usu_id
                WHERE dre_id = :dre_id AND              
                    dre_estado_logico=:estado AND 
                    dre_estado=:estado";        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);    
        $comando->bindParam(":dre_id", $ids, \PDO::PARAM_INT);
        $comando->bindParam(":fecha", $fecha_modificacion, \PDO::PARAM_STR);
        $comando->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
        $response = $comando->execute();
        return $response;       
    }
    
    
    
}
