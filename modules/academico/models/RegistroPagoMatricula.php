<?php

namespace app\modules\academico\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "registro_pago_matricula".
 *
 * @property int $rpm_id
 * @property int $per_id
 * @property int $pla_id
 * @property string $rpm_archivo
 * @property string $rpm_estado_aprobacion
 * @property string $rpm_estado_generado
 * @property string $rpm_hoja_matriculacion
 * @property int $rpm_usuario_apruebareprueba
 * @property string $rpm_fecha_transaccion
 * @property string $rpm_observacion
 * @property string $rpm_estado
 * @property string $rpm_fecha_creacion
 * @property int $rpm_usuario_modifica
 * @property string $rpm_fecha_modificacion
 * @property string $rpm_estado_logico
 *
 * @property Planificacion $pla
 */
class RegistroPagoMatricula extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_pago_matricula';
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
            [['per_id', 'pla_id', 'rpm_archivo', 'rpm_estado', 'rpm_estado_logico'], 'required'],
            [['per_id', 'pla_id', 'rpm_usuario_modifica', 'rpm_usuario_apruebareprueba'], 'integer'],
            [['rpm_archivo'], 'string'],
            [['rpm_fecha_creacion', 'rpm_fecha_modificacion', 'rpm_fecha_transaccion'], 'safe'],
            [['rpm_estado_aprobacion', 'rpm_estado_generado', 'rpm_estado', 'rpm_estado_logico'], 'string', 'max' => 1],
            [['rpm_hoja_matriculacion'], 'string', 'max' => 200],
            [['rpm_observacion'], 'string', 'max' => 1000],
            [['pla_id'], 'exist', 'skipOnError' => true, 'targetClass' => Planificacion::className(), 'targetAttribute' => ['pla_id' => 'pla_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rpm_id' => 'Rpm ID',
            'per_id' => 'Per ID',
            'pla_id' => 'Pla ID',
            'rpm_archivo' => 'Rpm Archivo',
            'rpm_estado_aprobacion' => 'Rpm Estado Aprobacion',
            'rpm_estado_generado' => 'Rpm Estado Generado',
            'rpm_hoja_matriculacion' => 'Rpm Hoja Matriculacion',            
            'rpm_usuario_apruebareprueba' => 'Rpm Usuario Apruebareprueba',
            'rpm_fecha_transaccion' => 'Rpm Fecha Transaccion',
            'rpm_observacion' => 'Rpm Observación',            
            'rpm_estado' => 'Rpm Estado',
            'rpm_fecha_creacion' => 'Rpm Fecha Creacion',
            'rpm_usuario_modifica' => 'Rpm Usuario Modifica',
            'rpm_fecha_modificacion' => 'Rpm Fecha Modificacion',
            'rpm_estado_logico' => 'Rpm Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPla()
    {
        return $this->hasOne(Planificacion::className(), ['pla_id' => 'pla_id']);
    }
    public static function checkPagoEstudiante($per_id, $pla_id) {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $estado_registro = 0;

        $sql = "
            SELECT rpm_id
            FROM " . $con_academico->dbname . ".registro_pago_matricula as rpm            
            WHERE rpm.per_id=:per_id
            -- AND rpm.pla_id=:pla_id            
            AND rpm.rpm_estado=:estado
            AND rpm.rpm_estado_logico=:estado;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();

        return $resultData;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    function getAllRegistroMatriculaxGenerarGrid($search = NULL, $mod_id = NULL, $estado = NULL, $carrera = NULL, $periodo = NULL, $dataProvider = false){
        $con_academico = \Yii::$app->db_academico;
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";

        if (isset($search) && $search != "") {
            $str_search = "(pe.pes_nombres like :search OR ";
            $str_search .= "pe.pes_dni like :search) AND ";
        }
        if(isset($mod_id) && $mod_id != ""){
            $condition .= "m.mod_id = :mod_id AND ";
        }
        if(isset($estado) && $estado != ""){
            $condition .= "reg.rpm_estado_generado = :estado AND ";
        }
        if(isset($carrera) && $carrera != ""){
            $carrera = "%" . $carrera . "%";
            $condition .= "pe.pes_carrera like :carrera AND ";
        }
        if(isset($periodo) && $periodo != ""){
            $periodo = "%" . $periodo . "%";
            $condition .= "p.pla_periodo_academico like :periodo AND ";
        }
        
        $sql = "SELECT 
                    r.ron_id as Id, 
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.pes_carrera as Carrera,
                    m.mod_nombre as Modalidad,
                    p.pla_periodo_academico as Periodo,
                    count(*) as Materias,
                    sum(ri.roi_creditos) as Creditos,
                    reg.rpm_estado_generado as Estado
                FROM " . $con_academico->dbname . ".registro_pago_matricula AS reg
                    INNER JOIN " . $con_academico->dbname . ".planificacion_estudiante as pe on reg.per_id = pe.per_id
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad as m on m.mod_id = p.mod_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online_item as ri on ri.ron_id = r.ron_id
                WHERE 
                    $str_search 
                    $condition
                    reg.rpm_estado = 1 and 
                    reg.rpm_estado_logico = 1 and 
                    pe.pes_estado =1 and
                    pe.pes_estado_logico =1 and
                    p.pla_estado =1 and
                    p.pla_estado_logico =1 and
                    r.ron_estado =1 and
                    r.ron_estado_logico =1 and
                    reg.rpm_estado_aprobacion = 1
                GROUP BY
                    r.ron_id, 
                    pe.pes_nombres,
                    pe.pes_dni,
                    pe.pes_carrera,
                    m.mod_nombre,
                    p.pla_periodo_academico,
                    reg.rpm_estado_generado
                ORDER BY
                    r.ron_id DESC
                ";
        $comando = $con_academico->createCommand($sql);
        if(isset($search) && $search != "")  $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        if(isset($mod_id) && $mod_id != "")  $comando->bindParam(":mod_id",$mod_id, \PDO::PARAM_INT);
        if(isset($estado) && $estado != "")  $comando->bindParam(":estado",$estado, \PDO::PARAM_INT);
        if(isset($carrera) && $carrera != "") $comando->bindParam(":carrera",$carrera, \PDO::PARAM_STR);
        if(isset($periodo) && $periodo != "") $comando->bindParam(":periodo",$periodo, \PDO::PARAM_STR);
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Estudiante', 'Cedula',"Carrera","Modalidad","Periodo","Estado"],
                ],
            ]);

            return $dataProvider;
        }
        return $res;
    }

    function getRegistroPagoMatriculaByRegistroOnline($reg_id, $per_id){
        $con_academico = \Yii::$app->db_academico;
        
        $sql = "SELECT 
                    reg.rpm_id as Id, 
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.pes_carrera as Carrera,
                    p.pla_periodo_academico as Periodo,
                    reg.rpm_estado_generado as Estado
                FROM " . $con_academico->dbname . ".registro_pago_matricula AS reg
                    INNER JOIN " . $con_academico->dbname . ".planificacion_estudiante as pe on reg.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = reg.pla_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id
                WHERE 
                    reg.rpm_estado = 1 and 
                    reg.rpm_estado_logico = 1 and 
                    pe.pes_estado =1 and
                    pe.pes_estado_logico =1 and
                    p.pla_estado =1 and
                    p.pla_estado_logico =1 and
                    r.ron_estado =1 and
                    r.ron_estado_logico =1 and
                    reg.per_id = :per_id and 
                    r.ron_id = :ron_id;";   
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id",$reg_id, \PDO::PARAM_INT);
        $comando->bindParam(":per_id",$per_id, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        return $res;
    }
    /* Grace Viteri
     * Para obtener el estado de generado.
    */
    function getRegistroPagoMatriculaById($id_rpm){
        $con_academico = \Yii::$app->db_academico;
        
        $sql = "SELECT                     
                    rpm_estado_generado as estado
                FROM " . $con_academico->dbname . ".registro_pago_matricula AS reg                    
                WHERE 
                    reg.rpm_estado = 1 and 
                    reg.rpm_estado_logico = 1 and                     
                    reg.rpm_id = :rpm_id";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":rpm_id",$id_rpm, \PDO::PARAM_INT);        
        $res = $comando->queryOne();
        return $res;
    }

     /**
     * Function grabarRechazo (Actualiza registro_pago_matricula)
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function Modificarregsitropagomatricula($per_id, $pla_id, $rpm_estado_aprobacion) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $fecha_modifica = date(Yii::$app->params["dateTimeByDefault"]);
        $usuario_modifica = @Yii::$app->user->identity->usu_id;

        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".registro_pago_matricula
                SET rpm_estado_aprobacion = :rpm_estado_aprobacion,
                    rpm_usuario_apruebareprueba = :usuario_modifica,                   
                    rpm_fecha_modificacion = :fecha_modifica,
                    rpm_usuario_modifica = :usuario_modifica                    
                WHERE per_id = :per_id AND 
                      pla_id = :pla_id AND
                      rpm_estado =:estado AND
                      rpm_estado_logico = :estado");


        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $comando->bindParam(":rpm_estado_aprobacion", $rpm_estado_aprobacion, \PDO::PARAM_STR);
        $comando->bindParam(":usuario_modifica", $usuario_modifica, \PDO::PARAM_STR);
        $comando->bindParam(":fecha_modifica", $fecha_modifica, \PDO::PARAM_STR);
        $response = $comando->execute();
        return $response;
    }
}
