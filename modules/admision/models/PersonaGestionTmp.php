<?php

namespace app\modules\admision\models;

use Yii;
use yii\base\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use app\model\Utilities;
use app\modules\academico\models\EstudioAcademico;
use app\modules\admision\models\PersonaGestionTmp;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;

/**
 * This is the model class for table "persona_gestion_tmp".
 *
 * @property int $pgest_id
 * @property string $pgest_nombre
 * @property string $pgest_carr_nombre
 * @property string $pgest_contacto
 * @property string $pgest_horario
 * @property string $pgest_modalidad
 * @property string $pgest_numero
 * @property string $pgest_correo
 */
class PersonaGestionTmp extends \app\modules\admision\components\CActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'persona_gestion_tmp';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_crm');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pgest_nombre'], 'string', 'max' => 250],
            [['pgest_carr_nombre', 'pgest_contacto', 'pgest_modalidad', 'pgest_unidad_academica', 'pgest_correo'], 'string', 'max' => 100],
            [['pgest_horario'], 'string', 'max' => 50],
            [['pgest_numero'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pgest_id' => 'Pgest ID',
            'pgest_nombre' => 'Pgest Nombre',
            'pgest_carr_nombre' => 'Pgest Carr Nombre',
            'pgest_contacto' => 'Pgest Contacto',
            'pgest_horario' => 'Pgest Horario',
            'pgest_modalidad' => 'Pgest Modalidad',
            'pgest_unidad_academica' => 'Unidad Academica',
            'pgest_numero' => 'Pgest Numero',
            'pgest_correo' => 'Pgest Correo',
        ];
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function findByCondition($condition) {
        return parent::findByCondition($condition);
    }

    public function uploadFile($emp_id, $file) {
        $filaError = 0;
        $chk_ext = explode(".", $file);
        $con = \Yii::$app->db_crm;
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
                $this->deletetablaTemp($con);
                //PersonaGestionTmp::deletetablaTemp($con);
                while (($data = fgetcsv($handle, ",")) !== FALSE) {
                    $filaError++;
                    if ($cont != 0) {
                        $model = new PersonaGestionTmp(); //isset
                        $model->pgest_carr_nombre = ($emp_id == "1") ? EstudioAcademico::consultarIdsEstudioAca($data[0]) : EstudioAcademico::consultarIdsModEstudio($emp_id, $data[0]); //"$data[0]";
                        $model->pgest_contacto = PersonaGestionTmp::consultarIdsConocimientoCanal($data[1]); //"$data[1]";
                        $model->pgest_horario = "$data[2]";
                        $model->pgest_unidad_academica = UnidadAcademica::consultarIdsUnid_Academica($data[3]); //"$data[3]";
                        $model->pgest_modalidad = Modalidad::consultarIdsModalidad($data[4]); //"$data[4]";
                        $model->pgest_nombre = "$data[5]";
                        $model->pgest_numero = "$data[6]";
                        $model->pgest_correo = "$data[7]";
                        $model->pgest_comentario = "$data[8]";

                        if (!$model->save()) {
                            $arroout["status"] = FALSE;
                            $arroout["error"] = null;
                            $arroout["message"] = " Error en la Fila => N°$filaError Nombre => $data[5]";
                            $arroout["data"] = null;
                            throw new Exception('Error, Item no almacenado');
                        }
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
                $this->deletetablaTemp($con);
                //PersonaGestionTmp::deletetablaTemp($con);                
                $filaError = 1;
                foreach ($dataArr as $val) {
                    $filaError++;
                    $model = new PersonaGestionTmp();
                    $model->pgest_carr_nombre = ($emp_id == "1") ? EstudioAcademico::consultarIdsEstudioAca($val[1]) : EstudioAcademico::consultarIdsModEstudio($emp_id, $val[1]);
                    $model->pgest_contacto = PersonaGestionTmp::consultarIdsConocimientoCanal($val[2]); //"$val[2]";
                    $model->pgest_horario = "$val[3]";
                    $model->pgest_unidad_academica = UnidadAcademica::consultarIdsUnid_Academica($val[4]);
                    $model->pgest_modalidad = Modalidad::consultarIdsModalidad($val[5]);
                    $model->pgest_nombre = "$val[6]";
                    $model->pgest_numero = "$val[7]";
                    $model->pgest_correo = "$val[8]";
                    $model->pgest_comentario = "$val[9]";                    
                    if (!$model->save()) {
                        $arroout["status"] = FALSE;
                        $arroout["error"] = null;
                        $arroout["message"] = " Error en la Fila => N°$filaError Nombre => $val[6]";
                        $arroout["data"] = null;
                        throw new Exception('Error, Item no almacenado');
                    }
                }
                if ($trans !== null)
                    $trans->commit();                    
                    
                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;
                //return true;
                return $arroout;
            } catch (Exception $ex) {
                if ($trans !== null)
                    $trans->rollback();
                    \app\models\Utilities::putMessageLogFile('Error en la Fila => N° ' .$filaError .' Nombre =>'. $val[6]); 
                //return false;
                return $arroout;                
            }
        }
    }

    public function deletetablaTemp($con) {
        $sql = "DELETE FROM " . $con->dbname . ".persona_gestion_tmp";
        $command = $con->createCommand($sql);
        $command->execute();
    }

    /**
     * Function consultarIdsCarrera
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id      
     * @return  
     */
    public static function consultarIdsConocimientoCanal($TextAlias) {
        $con = \Yii::$app->db_crm;
        $sql = "SELECT ccan_id Ids 
                    FROM " . $con->dbname . ".conocimiento_canal  
                WHERE ccan_estado=1 AND ccan_nombre=:ccan_nombre ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":ccan_nombre", $TextAlias, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    }

}
