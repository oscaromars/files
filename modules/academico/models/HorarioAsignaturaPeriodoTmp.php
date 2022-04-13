<?php

namespace app\modules\academico\models;
use app\modules\academico\models\HorarioAsignaturaPeriodoTmp;
use yii\base\Exception;

use Yii;

/**
 * This is the model class for table "horario_asignatura_periodo_tmp".
 *
 * @property int $hapt_id
 * @property int $asi_id
 * @property int $paca_id
 * @property int $pro_id
 * @property int $uaca_id
 * @property int $mod_id
 * @property int $dia_id
 * @property string $hapt_fecha_clase
 * @property string $hapt_hora_entrada
 * @property string $hapt_hora_salida
 * @property int $usu_id
 *
 * @property Asignatura $asi
 * @property PeriodoAcademico $paca
 * @property Profesor $pro
 * @property UnidadAcademica $uaca
 * @property Modalidad $mod
 */
class HorarioAsignaturaPeriodoTmp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'horario_asignatura_periodo_tmp';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asi_id', 'paca_id', 'pro_id', 'uaca_id', 'mod_id', 'dia_id', 'hapt_hora_entrada', 'hapt_hora_salida', 'usu_id'], 'required'],
            [['asi_id', 'paca_id', 'pro_id', 'uaca_id', 'mod_id', 'dia_id', 'usu_id'], 'integer'],
            [['hapt_fecha_clase'], 'safe'],
            [['hapt_hora_entrada', 'hapt_hora_salida'], 'string', 'max' => 10],
            [['asi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asi_id' => 'asi_id']],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
            [['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidad::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hapt_id' => 'Hapt ID',
            'asi_id' => 'Asi ID',
            'paca_id' => 'Paca ID',
            'pro_id' => 'Pro ID',
            'uaca_id' => 'Uaca ID',
            'mod_id' => 'Mod ID',
            'dia_id' => 'Dia ID',
            'hapt_fecha_clase' => 'Hapt Fecha Clase',
            'hapt_hora_entrada' => 'Hapt Hora Entrada',
            'hapt_hora_salida' => 'Hapt Hora Salida',
            'usu_id' => 'Usu ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsi()
    {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaca()
    {
        return $this->hasOne(PeriodoAcademico::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaca()
    {
        return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMod()
    {
        return $this->hasOne(Modalidad::className(), ['mod_id' => 'mod_id']);
    }
    
    public function uploadFile($periodo_id, $usu_id, $file) {
        $fila = 0;
        $chk_ext = explode(".", $file);
        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }        
        if (strtolower(end($chk_ext)) == "xls" || strtolower(end($chk_ext)) == "xlsx") {      
            \app\models\Utilities::putMessageLogFile('extension de excel');
            //Create new PHPExcel object
            $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $dataArr = array();              
            try {                
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
                \app\models\Utilities::putMessageLogFile('antes de borrar temporal');
                $this->deletetablaTemp($con, $usu_id); 
                $fecha = date(Yii::$app->params["dateTimeByDefault"]);
                $fila = 1; 
                $bandera = '1';                
                foreach ($dataArr as $val) {                      
                    $fila++;                                                
                    $model = new HorarioAsignaturaPeriodoTmp();
                    //Validación de materia.  
                     \app\models\Utilities::putMessageLogFile('Materia Excel:'.$val[1]);
                    $respMateria = $model->consultarMateriaXnombre($val[1], $val[5]); 
                    $mensaje = "";
                    if (!($respMateria)) {                           
                        $bandera= '0';
                        $mensaje = "No se encontró materia con ese nombre o se encuentra inactiva.";                                                     
                    }                    
                    //Validación de unidad académica.
                    $respUnidad = $model->consultarExisteUnidad($val[5]);
                    if (!($respUnidad)) {                           
                        $bandera= '0';
                        $mensaje = "No se encontró unidad académica o se encuentra inactiva.";                                                     
                    }                    
                    //Validación modalidad.                    
                    $respMod = $model->consultarExisteModalidad($val[6]);
                    if (!($respMod)) {                           
                        $bandera= '0';
                        $mensaje = "No se encontró modalidad o se encuentra inactiva.";                                                     
                    }                    
                    //Validación profesor.    
                    \app\models\Utilities::putMessageLogFile('Profesor Excel:'.$val[3]);
                    $respProfesor = $model->consultarExisteProfesor($val[3]);                    
                    if (!($respProfesor)) {                           
                        $bandera= '0';
                        $mensaje = "No se encontró profesor o se encuentra inactivo.";                                                     
                    }                          
                    //Validación de días.
                    if (($val[7]>7) or ($val[7]<1)) {
                        $bandera= '0';
                        $mensaje = "Código de día incorrecto."; 
                    }                    
                    if ($bandera == '0') {
                        $arroout["status"] = FALSE;
                        $arroout["error"] = null;
                        $arroout["message"] = " Error en la Fila => N°$fila Materia => $val[1]";
                        $arroout["data"] = null;
                        throw new Exception('Error en la Fila => N°'.$fila. ' Materia => '. $val[1]);
                    }                                                                
                    //if ($val[6] == 4 or $val[6] == 1 or $val[6] == 3) { //modalidad a distancia                        
                    if (!empty($val[8])) {
                        $fecha_hora_clase = $val[8];
                    }  else {
                        $fecha_hora_clase = null;
                    }
                    if (!empty($respMateria["asi_id"])) {
                        $respuesta = $model->insertarHorarioTmp($respMateria["asi_id"], $periodo_id, $respProfesor["pro_id"], $val[5], $val[6], $val[7], $fecha_hora_clase, $val[9], $val[10], $usu_id);                                                                                    
                        if (!($respuesta)) {                                        
                            $arroout["status"] = FALSE;
                            $arroout["error"] = null;
                            $arroout["message"] = " Error en la Fila => N°$fila Materia => $val[1]";
                            $arroout["data"] = null;
                            \app\models\Utilities::putMessageLogFile('error fila '.$fila);
                            throw new Exception('Error, al grabar horario.');
                        }
                    }    
                    \app\models\Utilities::putMessageLogFile('Fila => '. $fila. 'Materia =>' . $val[1]);
                } 
                
                if ($trans !== null)                    
                    $trans->commit();  
                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;                                     
                return $arroout;
            } catch (Exception $ex) {
                if ($trans !== null)                    
                    $trans->rollback();                        
                    $arroout["status"] = FALSE;
                    $arroout["error"] = null;
                    $arroout["message"] = " Error en la Fila => N°$fila Materia => $val[1]. $mensaje";
                    $arroout["data"] = null;                    
                return $arroout;
            }
        }
    }

    public function deletetablaTemp($con, $usu_id) {
        $sql = "DELETE FROM " . $con->dbname . ".horario_asignatura_periodo_tmp where usu_id = :usu_id";
        $command = $con->createCommand($sql);        
        $command->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
        $command->execute();
    }
    
    
    public function consultarMateriaXnombre($nombre, $unidad) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT asi_id 
                FROM " . $con->dbname . ".asignatura a
                WHERE asi_nombre = :nombre
                and uaca_id = :unidad
                and asi_estado = :estado
                and asi_estado_logico = :estado";      
        \app\models\Utilities::putMessageLogFile('sql:'.$sql);
        \app\models\Utilities::putMessageLogFile('nombre:'.$nombre);
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":nombre", $nombre, \PDO::PARAM_STR);        
        $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);        
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    public function consultarExisteUnidad($uaca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 'S' existe
                from " . $con->dbname . ".unidad_academica a
                where uaca_id = :uaca_id
                and uaca_estado = :estado
                and uaca_estado_logico = :estado";                    
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    public function consultarExisteModalidad($mod_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "select 'S' existe
                from " . $con->dbname . ".modalidad a
                where mod_id = :mod_id
                and mod_estado = :estado
                and mod_estado_logico = :estado";               
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryOne();
        return $resultData;
    }       
    
    public function consultarExisteProfesor($cedula) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT pr.pro_id
                FROM " . $con1->dbname . ".persona p inner join " . $con->dbname . ".profesor pr on pr.per_id = p.per_id
                WHERE p.per_cedula = :cedula
                and p.per_estado = :estado
                and p.per_estado_logico = :estado
                and pr.pro_estado = :estado
                and pr.pro_estado_logico = :estado";               
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);  
        \app\models\Utilities::putMessageLogFile('cedula:'.$cedula);     
        \app\models\Utilities::putMessageLogFile('Validacion Profesor:'.$sql);   
        $resultData = $comando->queryOne();
        return $resultData;
    }        
    
    public function consultarHorarioTemp($usu_id) {
        $con = \Yii::$app->db_academico;        
        $sql = "SELECT * FROM " . $con->dbname . ".horario_asignatura_periodo_tmp where usu_id = :usu_id";            
        $comando = $con->createCommand($sql);
        $comando->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
        return $comando->queryAll();
    }
    
     /**
     * Function insertarHorarioTmp graba horario según período.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;     
     * @param
     * @return
     */
    public function insertarHorarioTmp($asi_id, $paca_id, $pro_id, $uaca_id, $mod_id, $dia_id, $hapt_fecha_clase, $hapt_hora_entrada, $hapt_hora_salida, $usu_id) {
        $con = \Yii::$app->db_academico;         
        if (isset($asi_id)) {
            $param_sql .= "asi_id";
            $bdet_sql .= ":asi_id";
        }
        if (isset($paca_id)) {
            $param_sql .= ", paca_id";
            $bdet_sql .= ", :paca_id";
        }
        if (isset($pro_id)) {
            $param_sql .= ", pro_id";
            $bdet_sql .= ", :pro_id";
        }
        if (isset($uaca_id)) {
            $param_sql .= ", uaca_id";
            $bdet_sql .= ", :uaca_id";
        }
        if (isset($mod_id)) {
            $param_sql .= ", mod_id";
            $bdet_sql .= ", :mod_id";
        }
        if (isset($dia_id)) {
            $param_sql .= ", dia_id";
            $bdet_sql .= ", :dia_id";
        }        
        if (isset($hapt_fecha_clase)) {
            $param_sql .= ", hapt_fecha_clase";
            $bdet_sql .= ", :hapt_fecha_clase";
        }
        if (isset($hapt_hora_entrada)) {
            $param_sql .= ", hapt_hora_entrada";
            $bdet_sql .= ", :hapt_hora_entrada";
        }
        if (isset($hapt_hora_salida)) {
            $param_sql .= ", hapt_hora_salida";
            $bdet_sql .= ", :hapt_hora_salida";
        }  
        if (isset($usu_id)) {
            $param_sql .= ", usu_id";
            $bdet_sql .= ", :usu_id";
        } 
        try {
            $sql = "INSERT INTO " . $con->dbname . ".horario_asignatura_periodo_tmp ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($asi_id)) {
                $comando->bindParam(':asi_id', $asi_id, \PDO::PARAM_INT);
            }
            if (isset($paca_id)) {
                $comando->bindParam(':paca_id', $paca_id, \PDO::PARAM_INT);
            }
            if (isset($pro_id)) {
                $comando->bindParam(':pro_id', $pro_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($uaca_id)))) {
                $comando->bindParam(':uaca_id', $uaca_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($mod_id)))) {
                $comando->bindParam(':mod_id', $mod_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($dia_id)))) {
                $comando->bindParam(':dia_id', $dia_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($hapt_fecha_clase)))) {
                $comando->bindParam(':hapt_fecha_clase', $hapt_fecha_clase, \PDO::PARAM_STR);
            }
            if (!empty((isset($hapt_hora_entrada)))) {
                $comando->bindParam(':hapt_hora_entrada', $hapt_hora_entrada, \PDO::PARAM_STR);
            }
            if (!empty((isset($hapt_hora_salida)))) {
                $comando->bindParam(':hapt_hora_salida', $hapt_hora_salida, \PDO::PARAM_STR);
            }  
            if (isset($usu_id)) {
                $comando->bindParam(':usu_id', $usu_id, \PDO::PARAM_INT);
            }            
            $result = $comando->execute();            
            return $con->getLastInsertID($con->dbname . '.horario_asignatura_periodo_tmp');            
        } catch (Exception $ex) {         
            return FALSE;
        }
    }
}
