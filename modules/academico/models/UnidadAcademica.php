<?php

namespace app\modules\academico\models;

use yii\data\ArrayDataProvider;
use Yii;


/**
 * Este es el modelo de clase para la tabla "unidad_academica".
 *
 * @property integer $uaca_id
 * @property string $uaca_nombre
 * @property string $uaca_descripcion
 * @property string $uaca_estado
 * @property string $uaca_fecha_creacion
 * @property string $uaca_fecha_modificacion
 * @property string $uaca_estado_logico
 */
class UnidadAcademica extends \app\modules\academico\components\CActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {        
        return 'unidad_academica';        
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_academico');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uaca_nombre', 'uaca_descripcion', 'uaca_estado', 'uaca_estado_logico'], 'required'],
            [['uaca_fecha_creacion', 'uaca_fecha_modificacion'], 'safe'],
            [['uaca_nombre'], 'string', 'max' => 300],
            [['uaca_descripcion'], 'string', 'max' => 500],
            [['uaca_estado', 'uaca_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'uaca_id' => 'Uaca ID',
            'uaca_nombre' => 'Uaca Nombre',
            'uaca_descripcion' => 'Uaca Descripcion',
            'uaca_estado' => 'Uaca Estado',
            'uaca_fecha_creacion' => 'Uaca Fecha Creacion',
            'uaca_fecha_modificacion' => 'Uaca Fecha Modificacion',
            'uaca_estado_logico' => 'Uaca Estado Logico',
        ];
    }

    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarCodigoArea
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public static function consultarIdsUnid_Academica($TextAlias) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT uaca_id Ids
                    FROM " . $con->dbname . ".unidad_academica  
                WHERE uaca_estado_logico=1 AND uaca_nombre=:uaca_nombre ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":uaca_nombre", $TextAlias, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    }


    /**
     * Function consulta el nombre de unidad academica
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarUnidadAcademicas() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
                    SELECT
                        unia.uaca_nombre as name,
                        unia.uaca_id as id
                    FROM
                        " . $con->dbname . ".unidad_academica as unia            
                    WHERE
                        unia.uaca_estado_logico=:estado AND 
                        unia.uaca_estado=:estado
                    ORDER BY 2 asc
               ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consulta el nombre de unidad academica
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarUnidadAcademicasEmpresa($empresa) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        if ($empresa > 0) {
           $condicion = 'emp_id = :empresa AND '; 
        }

        $sql = "
                    SELECT distinct una.uaca_id as id, una.uaca_nombre as name
                        FROM db_academico.modalidad_estudio_unidad meu
                             inner JOIN db_academico.unidad_academica una on una.uaca_id = meu.uaca_id               
                    where $condicion
                        meu.meun_estado = :estado AND
                        meu.meun_estado_logico = :estado AND
                        una.uaca_estado = :estado AND
                        una.uaca_estado_logico = :estado
                    ORDER BY id asc ;
               ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":empresa", $empresa, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consulta el nombre de unidad academica
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarNombreunidad($unidad) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    unia.uaca_nombre as nombre_unidad
                FROM
                    " . $con->dbname . ".unidad_academica as unia
                WHERE
                    unia.uaca_id=:unidad AND
                    unia.uaca_estado_logico=:estado AND
                    unia.uaca_estado=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    
     /**
     * Function consulta el nombre de unidad academica 
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarUnidadAcademicasxUteg() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
                    SELECT 
                        unia.uaca_nombre as name,
                        unia.uaca_id as id
                    FROM 
                        " . $con->dbname . ".unidad_academica as unia            
                    WHERE   
                        unia.uaca_estado_logico=:estado AND 
                        unia.uaca_estado=:estado AND
                        unia.uaca_id in (1,2)
                    ORDER BY 2 asc
               ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Retorna las Unidades Académicas en las que se ha registrado el estudiante, usando el est_id
     * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarUnidadAcademicaDelEstudiante($est_id){
        $con = Yii::$app->db_academico;
            
        $sql = "SELECT distinct uaca.uaca_id as id, uaca.uaca_nombre as name
                FROM " . $con->dbname . ".unidad_academica AS uaca
                INNER JOIN " . $con->dbname . ".modalidad_estudio_unidad AS meun ON uaca.uaca_id = meun.uaca_id
                INNER JOIN " . $con->dbname . ".estudiante_carrera_programa AS ecpr ON ecpr.meun_id = meun.meun_id
                WHERE ecpr.est_id = :est_id
                AND ecpr.ecpr_estado = 1 AND ecpr.ecpr_estado_logico = 1
                AND meun.meun_estado = 1 AND meun.meun_estado_logico = 1
                AND uaca.uaca_estado = 1 AND uaca.uaca_estado_logico = 1
                ORDER BY id ASC";

        $comando = $con->createCommand($sql);

        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);   
             
        $resultData = $comando->queryAll();
        // \app\models\Utilities::putMessageLogFile('consultarUnidadAcademicasEstudiante: '.$comando->getRawSql());
        return $resultData;
    }

}
