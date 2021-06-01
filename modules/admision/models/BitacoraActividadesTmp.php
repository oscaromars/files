<?php

namespace app\modules\admision\models;
use yii\base\Exception;
use app\modules\admision\models\BitacoraActividadesTmp;
use app\modules\admision\models\PersonaGestion;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use Yii;

/**
 * This is the model class for table "bitacora_actividades_tmp".
 *
 * @property int $bact_id
 * @property int $opo_id
 * @property int $usu_id
 * @property int $padm_id
 * @property int $eopo_id
 * @property int $oact_id
 * @property string $bact_fecha_registro
 * @property string $bact_descripcion
 * @property string $bact_fecha_proxima_atencion
 */
class BitacoraActividadesTmp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bitacora_actividades_tmp';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_crm');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['opo_id', 'usu_id', 'padm_id', 'eopo_id', 'oact_id'], 'integer'],
            [['bact_fecha_registro', 'bact_fecha_proxima_atencion'], 'safe'],
            [['bact_descripcion'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bact_id' => 'Bact ID',
            'opo_id' => 'Opo ID',
            'usu_id' => 'Usu ID',
            'padm_id' => 'Padm ID',
            'eopo_id' => 'Eopo ID',
            'oact_id' => 'Oact ID',
            'bact_fecha_registro' => 'Bact Fecha Registro',
            'bact_descripcion' => 'Bact Descripcion',
            'bact_fecha_proxima_atencion' => 'Bact Fecha Proxima Atencion',
        ];
    }
    
    public function uploadFile($emp_id, $usu_id, $padm_id, $file) {
        $filaError = 0;
        $chk_ext = explode(".", $file);
        $con = \Yii::$app->db_crm;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        if (strtolower(end($chk_ext)) == "xls" || strtolower(end($chk_ext)) == "xlsx") {            
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
                $this->deletetablaTemp($con, $usu_id);                
                $fila = 1;                    
                foreach ($dataArr as $val) {                                        
                    $fila++;        
                    $modelPerGestion = new PersonaGestion();
                    $modelUnidad = new UnidadAcademica();
                    $modelModalidad = new Modalidad();
                    $modelEstAcademico = new EstudioAcademico();                                           
                    $model = new BitacoraActividadesTmp();                    
                    if (empty($val[6])) {
                        $correo = "X";
                    } else {
                        $correo = $val[6];
                    }
                    $respIdPerGes = $modelPerGestion::existePersonaGestion($correo, $val[5]);                       
                    $respUnidad = $modelUnidad::consultarIdsUnid_Academica($val[1]);                    
                    $respModalidad = $modelModalidad::consultarIdsModalidad($val[2]);                    
                    $respEstAcade = $modelEstAcademico::consultarIdsEstudioAca($val[3]);      
                    \app\models\Utilities::putMessageLogFile('empresa: '.$emp_id);
                    \app\models\Utilities::putMessageLogFile('IdPers: '.$respIdPerGes);
                    \app\models\Utilities::putMessageLogFile('Est acad: '.$respEstAcade);
                    \app\models\Utilities::putMessageLogFile('Unidad: '.$respUnidad);
                    \app\models\Utilities::putMessageLogFile('Modali: '.$respModalidad);
                    $respOport = $model->consultarOportunidad($emp_id, $respIdPerGes, $respEstAcade, $respUnidad, $respModalidad);                    
                    if (!($respOport)) {                           
                        $bandera= '0';
                        $mensaje = "No se encontró ninguna oportunidad asociada.";                                                     
                    }
                    $respEstadoOpo = $model->consultarEstadoXoportunidad($val[7]);
                    if (!($respEstadoOpo)) {                           
                        $bandera= '0';
                        $mensaje = "No se encontró estado de oportunidad.";                           
                    }
                    $respObservaOpo = $model->consultarObservacionXoportunidad($val[8]);
                    if (!($respObservaOpo)) {                          
                        $bandera= '0';
                        $mensaje = "No se encontró código de observación.";                           
                    }
                    if ($val[7] == 5) { //Estado oportunidad perdida
                        $respOpoPerdida = $model->consultarOporPerdida($val[12]);
                        if (!($respOpoPerdida)) {                                 
                            $bandera= '0';
                            $mensaje = "No se encontró código de opotunidad perdida.";                               
                        }
                    }
                    if ($bandera == '0') {
                        $arroout["status"] = FALSE;
                        $arroout["error"] = null;
                        $arroout["message"] = " Error en la Fila => N°$fila Nombres => $val[4]";
                        $arroout["data"] = null;
                        throw new Exception('Error en la Fila => N°'.$fila. ' Nombres => '. $val[4]);
                    }                    
                    $model->opo_id = $respOport["opo_id"];
                    $model->usu_id = $usu_id;
                    $model->padm_id = $padm_id;
                    $model->eopo_id = $val[7];
                    $model->oact_id = $val[8];
                    $model->bact_fecha_registro = $val[9];                    
                    if ($val[7] == 1) { //Estado en curso
                        $model->bact_fecha_proxima_atencion =$val[10];
                    }
                    if ($val[7] == 5) { //Estado oportunidad perdida
                        $model->oper_id =$val[12];                        
                    }
                    $model->bact_descripcion = $val[11];                                         
                    $model->save();                    
                    if (empty($model->bact_id)) {                             
                        $arroout["status"] = FALSE;
                        $arroout["error"] = null;
                        $arroout["message"] = " Error en la Fila => N°$fila Nombres => $val[4]";
                        $arroout["data"] = null;
                        throw new Exception('Error, al grabar actividad.');
                    }
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
                    \app\models\Utilities::putMessageLogFile('rollback');
                    $arroout["status"] = FALSE;
                    $arroout["error"] = null;
                    $arroout["message"] = " Error en la Fila => N°$fila Nombres => $val[4]. $mensaje";
                    $arroout["data"] = null;
                    
                return $arroout;
            }
        }
    }

    public function deletetablaTemp($con, $usu_id) {
        $sql = "DELETE FROM " . $con->dbname . ".bitacora_actividades_tmp where usu_id = :usu_id";
        $command = $con->createCommand($sql);        
        $command->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
        $command->execute();
    }
    
    public function consultarOportunidad($emp_id, $pges_id, $eaca_id, $uaca_id, $mod_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT opo_id FROM " . $con->dbname . ".oportunidad 
                WHERE emp_id = :emp_id
                    and pges_id = :pges_id
                    and eaca_id = :eaca_id
                    and uaca_id = :uaca_id
                    and mod_id = :mod_id
                    and opo_estado = :estado
                    and opo_estado_logico = :estado ";     
                       
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    public function consultarEstadoXoportunidad($eopo_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT 'S' existe FROM " . $con->dbname . ".estado_oportunidad 
                WHERE eopo_id = :eopo_id
                      AND eopo_estado = :estado
                      AND eopo_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":eopo_id", $eopo_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    public function consultarObservacionXoportunidad($oact_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT 'S' existe FROM " . $con->dbname . ".observacion_actividades 
                WHERE oact_id = :oact_id
                      AND oact_estado = :estado
                      AND oact_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":oact_id", $oact_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    public function consultarIdXPadm($per_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT padm_id FROM " . $con->dbname . ".personal_admision 
                WHERE per_id = :per_id
                      AND padm_estado = :estado
                      AND padm_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;                
    }
    
    public function consultarBitacoraTemp($usu_id) {
        $con = \Yii::$app->db_crm;        
        $sql = "SELECT * FROM " . $con->dbname . ".bitacora_actividades_tmp where usu_id = :usu_id";        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
        return $comando->queryAll();
    }
        
    public function consultarOporPerdida($opoper_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT 'S' existe FROM " . $con->dbname . ".oportunidad_perdida 
                WHERE oper_id = :oper_id
                      AND oper_estado = :estado
                      AND oper_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":oper_id", $opoper_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }    
}

