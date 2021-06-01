<?php

namespace app\modules\marcacionhistorico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "registro_marcacion_historial".
 *
 * @property int $rmhi_id
 * @property int $haph_id
 * @property string $rmhi_fecha_hora_entrada
 * @property string $rmhi_fecha_hora_salida
 * @property string $rmhi_estado
 * @property string $rmhi_fecha_creacion
 * @property string $rmhi_fecha_modificacion
 * @property string $rmhi_estado_logico
 *
 * @property HorarioAsignaturaPeriodoHistorial $haph
 */
class RegistroMarcacionHistorial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_marcacion_historial';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_marcacion_historico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['haph_id', 'rmhi_estado', 'rmhi_estado_logico'], 'required'],
            [['haph_id'], 'integer'],
            [['rmhi_fecha_hora_entrada', 'rmhi_fecha_hora_salida', 'rmhi_fecha_creacion', 'rmhi_fecha_modificacion'], 'safe'],
            [['rmhi_estado', 'rmhi_estado_logico'], 'string', 'max' => 1],
            [['haph_id'], 'exist', 'skipOnError' => true, 'targetClass' => HorarioAsignaturaPeriodoHistorial::className(), 'targetAttribute' => ['haph_id' => 'haph_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rmhi_id' => 'Rmhi ID',
            'haph_id' => 'Haph ID',
            'rmhi_fecha_hora_entrada' => 'Rmhi Fecha Hora Entrada',
            'rmhi_fecha_hora_salida' => 'Rmhi Fecha Hora Salida',
            'rmhi_estado' => 'Rmhi Estado',
            'rmhi_fecha_creacion' => 'Rmhi Fecha Creacion',
            'rmhi_fecha_modificacion' => 'Rmhi Fecha Modificacion',
            'rmhi_estado_logico' => 'Rmhi Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHaph()
    {
        return $this->hasOne(HorarioAsignaturaPeriodoHistorial::className(), ['haph_id' => 'haph_id']);
    }
    /**
     * Function consultarMarcacionHistorica
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>    
     * @property  
     * @return  
     */
    public function consultarMarcacionHistorica($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_marcacion_historico;
        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :profesor OR ";
            $str_search .= "per.per_seg_nombre like :profesor OR ";
            $str_search .= "per.per_pri_apellido like :profesor OR ";
            $str_search .= "per.per_seg_nombre like :profesor )  AND ";
            $str_search .= "asig.asi_nombre like :materia  AND ";

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "rmh.rmhi_fecha_creacion >= :fec_ini AND ";
                $str_search .= "rmh.rmhi_fecha_creacion <= :fec_fin AND ";
            }
            /*if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= " hap.paca_id = :periodo AND ";
            }*/
        }
        if ($onlyData == false) {
             $periodoacademico = ', rmh.haph_id as periodo ';           
        }
        $sql = "
               SELECT rmh.rmhi_id,
                    -- rmh.haph_id,
                    CONCAT(ifnull(per.per_pri_nombre,' '), ' ', ifnull(per.per_pri_apellido,' ')) as nombres,
                    asig.asi_nombre as materia,  
                    DATE_FORMAT(rmh.rmhi_fecha_hora_entrada, '%Y-%m-%d') as fecha,
                        DATE_FORMAT(rmh.rmhi_fecha_hora_entrada, '%H:%i:%s') as hora_inicio,
                    DATE_FORMAT(rmh.rmhi_fecha_hora_salida, '%H:%i:%s') as hora_salida
                    $periodoacademico                                  
                    FROM " . $con2->dbname . ".registro_marcacion_historial rmh
                    INNER JOIN " . $con2->dbname . ".horario_asignatura_periodo_historial hap on hap.haph_id = rmh.haph_id
                    INNER JOIN " . $con->dbname . ".profesor profe on profe.pro_id = hap.pro_id
                    INNER JOIN " . $con1->dbname . ".persona per on per.per_id = profe.per_id 
                    INNER JOIN " . $con->dbname . ".asignatura asig on asig.asi_id = hap.asi_id                  
                    WHERE $str_search                    
                    rmh.rmhi_estado = :estado AND
                    rmh.rmhi_estado_logico = :estado AND
                    hap.haph_estado = :estado AND
                    hap.haph_estado_logico = :estado AND
                    profe.pro_estado = :estado AND
                    profe.pro_estado_logico = :estado AND
                    per.per_estado = :estado AND
                    per.per_estado_logico = :estado AND
                    asig.asi_estado = :estado AND
                    asig.asi_estado_logico = :estado
                    GROUP BY nombres,materia,fecha, rmh.rmhi_id
                    ORDER BY fecha DESC
               ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["profesor"] . "%";
            $comando->bindParam(":profesor", $search_cond, \PDO::PARAM_STR);
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $materia = "%" . $arrFiltro["materia"] . "%";
            $comando->bindParam(":materia", $materia, \PDO::PARAM_STR);

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            /*if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
            }*/
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
    
    
    
    
    public function uploadFileHorario($fname, $file) {
        $filaError = 0;
        $contError = 0;
        $rawData = ''; //array();
        $mensError = '';
        $ExisIds=0;
        $file=Yii::$app->basePath .$file.$fname;
        $chk_ext = explode(".", $fname);
        $con = \Yii::$app->db_marcacion_historico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }        
        if (strtolower(end($chk_ext)) == "csv") {
            //si es correcto, entonces damos permisos de lectura para subir                  
            try {
                $handle = fopen($file, "r");
                $cont = 0;
                while (($data = fgetcsv($handle, ",")) !== FALSE) {
                    
                    $haph_id=0;
                    $filaError++;
                    $ExisIds=$this->ExisteHorario($data);
                    if($ExisIds==0){//NO existe 
                        $haph_id = $this->InsertarHistorialHorario($con, $data);
                        if (!$haph_id) {
                            $contError++;
                            $mensError .= 'Profesor: '.$data[4].' - Linea: '.$filaError.' - NO INGRESADO <br/>'; 
                        }
                    }else{
                        $contError++;
                        $mensError .= 'Profesor: '.$data[4].' - Linea: '.$filaError.' - YA EXISTE <br/>';
                    }
                }
                fclose($handle);
                if ($trans !== null)
                    $trans->commit();

                if ($contError > 0) {
                    $rawData = '<br/> Resumen de información No ingresada: <br/>';
                    $rawData .= $mensError;
                    $rawData .= 'TOTAL: ' . $contError . ' <br/>';
                }
                    
                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = $rawData;
                //return true;
                return $arroout;
            } catch (Exception $ex) {
                fclose($handle);
                if ($trans !== null)
                    $trans->rollback();
                //return false;
                return $arroout;
            }
        }else if (strtolower(end($chk_ext)) == "xls" || strtolower(end($chk_ext)) == "xlsx") {
            //Create new PHPExcel object            
            $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $dataArr = array();    
            try {
                //$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10 
                    $highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                    //lectura del Archivo XLS filas y Columnas
                    for ($row = 1; $row <= $highestRow; ++$row) {
                        for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            $val = $cell->getValue();
                            $dataArr[$row][$col] = $val;
                        }
                    }
                    unset($dataArr[1]); // Se elimina la cabecera de titulos del file
                }           
                $filaError = 1;
                
                foreach ($dataArr as $val) {
                    $haph_id=0;
                    $filaError++;
                    $ExisIds=$this->ExisteHorario($val);
                    if($ExisIds==0){//NO existe 
                        $haph_id = $this->InsertarHistorialHorario($con, $val);
                        if (!$haph_id) {
                            $contError++;
                            $mensError .= 'Profesor: '.$val[4].' - Linea: '.$filaError.' - NO INGRESADO <br/>'; 
                        }
                    }else{
                        $contError++;
                        $mensError .= 'Profesor: '.$val[4].' - Linea: '.$filaError.' - YA EXISTE <br/>';
                    }
                }
                if ($trans !== null)
                    $trans->commit();
                
                if ($contError > 0) {
                    $rawData = '<br/> Resumen de información No ingresada: <br/>';
                    $rawData .= $mensError;
                    $rawData .= 'TOTAL: ' . $contError . ' <br/>';
                }
                    
                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = $rawData;
                //return true;
                return $arroout;
            } catch (Exception $ex) {
                fclose($handle);
                if ($trans !== null)
                    $trans->rollback();
                    //\app\models\Utilities::putMessageLogFile('Error en la Fila => N° ' .$filaError .' Nombre =>'. $val[6]); 
                //return false;
                $arroout["status"] = FALSE;
                $arroout["error"] = $ex->getCode();
                $arroout["message"] = $ex->getMessage();
                $arroout["data"] = $rawData;
                return $arroout;              
            }
        }
    }
    
    private function InsertarHistorialHorario($con, $dataInfo) {       
        $idsData=0;
        $IdsPro=$this->consultarIdDocente($dataInfo[3]);
        $IdsPro=($IdsPro!=0)?$IdsPro: 0;        
        $asi_id=$this->consultarIdAsignatura($dataInfo[1]); 
        
        if ($IdsPro==0 || $asi_id==0){//Verifica que existan los IDS
            return false;
        }
        
        $sql = "INSERT INTO " . $con->dbname . ".horario_asignatura_periodo_historial
            (asi_id,pahi_id,pro_id,uaca_id,mod_id,dia_id,haph_fecha_clase,haph_hora_entrada,
             haph_hora_salida,haph_estado,haph_fecha_creacion,haph_estado_logico)VALUES
            (:asi_id,:pahi_id,:pro_id,:uaca_id,:mod_id,:dia_id,:haph_fecha_clase,:haph_hora_entrada,
             :haph_hora_salida,1, CURRENT_TIMESTAMP(),1);";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
        $comando->bindParam(":pahi_id", intval($dataInfo[2]), \PDO::PARAM_INT);
        $comando->bindParam(":pro_id", $IdsPro, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", intval($dataInfo[5]), \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", intval($dataInfo[6]), \PDO::PARAM_INT);
        $comando->bindParam(":dia_id", intval($dataInfo[7]), \PDO::PARAM_INT);
        $comando->bindParam(":haph_fecha_clase", $dataInfo[8], \PDO::PARAM_STR);
        $comando->bindParam(":haph_hora_entrada", $dataInfo[9], \PDO::PARAM_STR);
        $comando->bindParam(":haph_hora_salida", $dataInfo[10], \PDO::PARAM_STR);
        $comando->execute();
        return $con->getLastInsertID();
    }
    
    private function InsertarMarcacion($con, $dataInfo,$haph_id) {       
        //rmhi_id
        $fhora_entrada=$dataInfo[6];
        $fhora_salida=$dataInfo[7];
        $sql = "INSERT INTO " . $con->dbname . ".registro_marcacion_historial
              (haph_id,rmhi_fecha_hora_entrada,rmhi_fecha_hora_salida,rmhi_estado,rmhi_fecha_creacion,rmhi_estado_logico)VALUES
              (:haph_id,:rmhi_fecha_hora_entrada,:rmhi_fecha_hora_salida,1,CURRENT_TIMESTAMP(),1);";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":haph_id", $haph_id, \PDO::PARAM_INT);
        $comando->bindParam(":rmhi_fecha_hora_entrada", $fhora_entrada, \PDO::PARAM_STR);
        $comando->bindParam(":rmhi_fecha_hora_salida", $fhora_salida, \PDO::PARAM_STR);
        $comando->execute();
        return $con->getLastInsertID();
    }
    
    private function consultarIdDocente($Cedula) {
        $con = \Yii::$app->db_academico;  
        $con1 = \Yii::$app->db_asgard;        
        $sql = "SELECT pro_id Ids FROM " . $con->dbname . ".profesor
                    WHERE  pro_estado=1 AND pro_estado_logico=1
                    AND per_id=(SELECT per_id FROM " . $con1->dbname . ".persona "
                                 . " WHERE per_cedula=:per_cedula);";
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_cedula", $Cedula, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData=$comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    }  
    
    private function consultarIdAsignatura($nombre) {
        $con = \Yii::$app->db_academico; 
        $sql = "SELECT asi_id FROM " . $con->dbname . ".asignatura "
                . "WHERE asi_estado=1 AND asi_estado_logico=1 AND asi_nombre=:asi_nombre; ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":asi_nombre", $nombre, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData=$comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    } 
    
    public function uploadFileMarcacion($fname, $file) {
        $filaError = 0;
        $contError = 0;
        $rawData = ''; //array();
        $mensError = '';
        $file=Yii::$app->basePath .$file.$fname;
        $chk_ext = explode(".", $fname);
        $con = \Yii::$app->db_marcacion_historico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }        
        if (strtolower(end($chk_ext)) == "csv") {
            //si es correcto, entonces damos permisos de lectura para subir                  
            try {
                $handle = fopen($file, "r");
                $cont = 0;
                while (($data = fgetcsv($handle, ",")) !== FALSE) {
                    
                    $filaError++;                   
                    $haph_id=$this->consultarIdHorario($data);                    
                    if ($haph_id > 0) {
                        if($this->ExisteMarcacion($haph_id)>0){//YA existe marcacion
                            $contError++;
                            $mensError .= 'Asignatura: '.$data[1].' - Linea: '.$filaError.' - MARCACIÓN YA INGRESADA <br/>'; 
                        }else{
                            //NO EXISTE MARCACION Y INSERTA
                            $rmhi_id = $this->InsertarMarcacion($con, $data,$haph_id);
                            if (!$rmhi_id) {
                                $contError++;
                                $mensError .= 'Asignatura: '.$data[1].' - Linea: '.$filaError.' - MARCACIÓN NO INGRESADA <br/>'; 
                            }
                            
                        }
                        
                    }else{                    
                        $contError++;
                        $mensError .= 'Profesor: '.$data[1].' - Linea: '.$filaError.' - NO EXISTE HORARIO <br/>';
                        //throw new Exception('Error, Item no almacenado');
                    }

                    $cont++;
                }
                fclose($handle);
                if ($trans !== null)
                    $trans->commit();

                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;
                //return true;
                return $arroout;
            } catch (Exception $ex) {
                fclose($handle);
                if ($trans !== null)
                    $trans->rollback();
                //return false;
                return $arroout;
            }
        }else if (strtolower(end($chk_ext)) == "xls" || strtolower(end($chk_ext)) == "xlsx") {
            //Create new PHPExcel object            
            $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $dataArr = array();    
            try {
                //$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10 
                    $highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                    //lectura del Archivo XLS filas y Columnas
                    for ($row = 1; $row <= $highestRow; ++$row) {
                        for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            $val = $cell->getValue();
                            $dataArr[$row][$col] = $val;
                        }
                    }
                    unset($dataArr[1]); // Se elimina la cabecera de titulos del file
                }
               
                $filaError = 1;
                foreach ($dataArr as $val) {
                    $filaError++;                   
                    $haph_id=$this->consultarIdHorario($val);                    
                    if ($haph_id > 0) {
                        if($this->ExisteMarcacion($haph_id)>0){//YA existe marcacion
                            $contError++;
                            $mensError .= 'Asignatura: '.$val[1].' - Linea: '.$filaError.' - MARCACIÓN YA INGRESADA <br/>'; 
                        }else{
                            //NO EXISTE MARCACION Y INSERTA
                            $rmhi_id = $this->InsertarMarcacion($con, $val,$haph_id);
                            if (!$rmhi_id) {
                                $contError++;
                                $mensError .= 'Asignatura: '.$val[1].' - Linea: '.$filaError.' - MARCACIÓN NO INGRESADA <br/>'; 
                            }
                            
                        }
                        
                    }else{                    
                        $contError++;
                        $mensError .= 'Profesor: '.$val[1].' - Linea: '.$filaError.' - NO EXISTE HORARIO <br/>';
                        //throw new Exception('Error, Item no almacenado');
                    }
                }
                if ($trans !== null)
                    $trans->commit(); 
                
                if ($contError > 0) {
                    $rawData = '<br/> Resumen de información No ingresada: <br/>';
                    $rawData .= $mensError;
                    $rawData .= 'TOTAL: ' . $contError . ' <br/>';
                }
                    
                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = $rawData;

                //return true;
                return $arroout;
            } catch (Exception $ex) {
                if ($trans !== null)
                    $trans->rollback();
                //\app\models\Utilities::putMessageLogFile('Error en la Fila => N° ' .$filaError .' Nombre =>'. $val[6]); 
                //return false;
                $arroout["status"] = FALSE;
                $arroout["error"] = $ex->getCode();
                $arroout["message"] = $ex->getMessage();
                $arroout["data"] = $rawData;    
                return $arroout;                
            }
        }
    }
    
    
    private function consultarIdHorario($dataInfo) {
        $con = \Yii::$app->db_marcacion_historico;       
        $sql = "SELECT haph_id 	FROM " . $con->dbname . ".horario_asignatura_periodo_historial
                        WHERE asi_id=:asi_id AND pro_id=:pro_id AND uaca_id=:uaca_id 
                        AND mod_id=:mod_id AND dia_id=:dia_id ;";
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":asi_id", $dataInfo[1], \PDO::PARAM_STR);
        $comando->bindParam(":pro_id", $dataInfo[2], \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $dataInfo[3], \PDO::PARAM_STR);
        $comando->bindParam(":mod_id", $dataInfo[4], \PDO::PARAM_STR);
        $comando->bindParam(":dia_id", $dataInfo[5], \PDO::PARAM_STR);
        
        //return $comando->queryAll();
        $rawData=$comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    }  
    
    private function ExisteHorario($dataInfo) {
        $IdsPro=$this->consultarIdDocente($dataInfo[3]);
        $IdsPro=($IdsPro!=0)?$IdsPro: 0;        
        $asi_id=$this->consultarIdAsignatura($dataInfo[1]); 

        $con = \Yii::$app->db_marcacion_historico;       
        $sql = "SELECT haph_id 	FROM " . $con->dbname . ".horario_asignatura_periodo_historial
                        WHERE asi_id=:asi_id AND pro_id=:pro_id AND uaca_id=:uaca_id 
                        AND mod_id=:mod_id AND dia_id=:dia_id ;";
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_STR);
        $comando->bindParam(":pro_id", $IdsPro, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $dataInfo[5], \PDO::PARAM_STR);
        $comando->bindParam(":mod_id", $dataInfo[6], \PDO::PARAM_STR);
        $comando->bindParam(":dia_id", $dataInfo[7], \PDO::PARAM_STR);
        
        //return $comando->queryAll();
        $rawData=$comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    } 
    
    private function ExisteMarcacion($Ids) {
        $con = \Yii::$app->db_marcacion_historico;  
        $sql = "SELECT haph_id FROM " . $con->dbname . ".registro_marcacion_historial
                    WHERE rmhi_estado=1 AND rmhi_estado_logico=1 AND haph_id=:haph_id;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":haph_id", $Ids, \PDO::PARAM_INT);
        //return $comando->queryAll();
        $rawData=$comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    } 
   

}
