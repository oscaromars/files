<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\models\Utilities;

/**
 * This is the model class for table "profesor".
 *
 * @property int $pro_id
 * @property int $per_id
 * @property string $pro_fecha_contratacion
 * @property string $pro_fecha_terminacion
 * @property int $pro_usuario_ingreso
 * @property int $pro_usuario_modifica
 * @property string $pro_estado
 * @property string $pro_fecha_creacion
 * @property string $pro_fecha_modificacion
 * @property string $pro_estado_logico
 *
 * @property Distributivo[] $distributivos
 * @property DistributivoAcademico[] $distributivoAcademicos
 * @property EvaluacionDocente[] $evaluacionDocentes
 * @property HorarioAsignaturaPeriodo[] $horarioAsignaturaPeriodos
 * @property DedicacionDocente $ddoc
 * @property ProfesorCapacitacion[] $profesorCapacitacions
 * @property ProfesorConferencia[] $profesorConferencias
 * @property ProfesorCoordinacion[] $profesorCoordinacions
 * @property ProfesorEvaluacion[] $profesorEvaluacions
 * @property ProfesorExpDoc[] $profesorExpDocs
 * @property ProfesorExpProf[] $profesorExpProfs
 * @property ProfesorIdiomas[] $profesorIdiomas
 * @property ProfesorInstruccion[] $profesorInstruccions
 * @property ProfesorInvestigacion[] $profesorInvestigacions
 * @property ProfesorPublicacion[] $profesorPublicacions
 * @property ProfesorReferencia[] $profesorReferencias
 * @property RegistroMarcacion[] $registroMarcacions
 * @property ResultadoEvaluacion[] $resultadoEvaluacions
 */
class Profesor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesor';
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
            [['per_id', 'pro_usuario_ingreso', 'pro_estado', 'pro_estado_logico','ddoc_id'], 'required'],
            [['per_id', 'pro_usuario_ingreso', 'pro_usuario_modifica'], 'integer'],
            [['pro_fecha_contratacion', 'pro_fecha_terminacion', 'pro_fecha_creacion', 'pro_fecha_modificacion'], 'safe'],
            [['pro_cv'], 'string', 'max' => 255],
            [['pro_estado', 'pro_estado_logico'], 'string', 'max' => 1],
            [['pro_num_contrato'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pro_id' => 'Pro ID',
            'per_id' => 'Per ID',
            'pro_cv' => 'pro_cv',
            'ddoc_id' => 'dedicacion_docente',
            'pro_fecha_contratacion' => 'Pro Fecha Contratacion',
            'pro_fecha_terminacion' => 'Pro Fecha Terminacion',
            'pro_usuario_ingreso' => 'Pro Usuario Ingreso',
            'pro_usuario_modifica' => 'Pro Usuario Modifica',
            'pro_estado' => 'Pro Estado',
            'pro_fecha_creacion' => 'Pro Fecha Creacion',
            'pro_fecha_modificacion' => 'Pro Fecha Modificacion',
            'pro_estado_logico' => 'Pro Estado Logico',
            'pro_num_contrato'=> 'Contraro',
        ];
    }
/**
     * @return \yii\db\ActiveQuery
     */
    public function getPer()
    {
        return $this->hasOne(\app\models\Persona::className(), ['per_id' => 'per_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivos()
    {
        return $this->hasMany(Distributivo::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivoAcademicos()
    {
        return $this->hasMany(DistributivoAcademico::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluacionDocentes()
    {
        return $this->hasMany(EvaluacionDocente::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHorarioAsignaturaPeriodos()
    {
        return $this->hasMany(HorarioAsignaturaPeriodo::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorCapacitacions()
    {
        return $this->hasMany(ProfesorCapacitacion::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorConferencias()
    {
        return $this->hasMany(ProfesorConferencia::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorCoordinacions()
    {
        return $this->hasMany(ProfesorCoordinacion::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorEvaluacions()
    {
        return $this->hasMany(ProfesorEvaluacion::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorExpDocs()
    {
        return $this->hasMany(ProfesorExpDoc::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorExpProfs()
    {
        return $this->hasMany(ProfesorExpProf::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorIdiomas()
    {
        return $this->hasMany(ProfesorIdiomas::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorInstruccions()
    {
        return $this->hasMany(ProfesorInstruccion::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorInvestigacions()
    {
        return $this->hasMany(ProfesorInvestigacion::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorPublicacions()
    {
        return $this->hasMany(ProfesorPublicacion::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesorReferencias()
    {
        return $this->hasMany(ProfesorReferencia::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroMarcacions()
    {
        return $this->hasMany(RegistroMarcacion::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResultadoEvaluacions()
    {
        return $this->hasMany(ResultadoEvaluacion::className(), ['pro_id' => 'pro_id']);
    }
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getDedoc()
    {
        return $this->hasOne(DedicacionDocente::className(), ['ddoc_id' => 'ddoc_id']);
    }
    
    function getAllProfesorGrid($search = NULL, $perfil){
        $con_asgard = \Yii::$app->db_asgard;
        $con_academico = \Yii::$app->db_academico;
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";

        if (isset($search)) {
            $str_search = "(pe.per_pri_nombre like :search OR ";
            $str_search .= "pe.per_pri_apellido like :search) OR pe.per_id=:Id AND ";
        }

        $sql = "SELECT distinct(pro.pro_id), pe.per_id,
                    pe.per_pri_nombre as PrimerNombre,
                    pe.per_seg_nombre as SegundoNombre, 
                    pe.per_pri_apellido as PrimerApellido, 
                    pe.per_seg_apellido as SegundoApellido, 
                    pe.per_celular as Celular, 
                    pe.per_correo as Correo, 
                    pe.per_cedula as Cedula,
                    pro.pro_cv as Cv,
                    pro.pro_estado_logico as estado,
                    $perfil as perfil
                FROM " . $con_academico->dbname . ".profesor AS pro
                inner JOIN " . $con_asgard->dbname . ".persona as pe on pro.per_id = pe.per_id
                -- inner JOIN " . $con_academico->dbname . ".distributivo as di on di.pro_id = pro.pro_id
                WHERE $str_search pro.pro_estado = 1 -- and pe.per_estado_logico = 1 and di.dis_declarado = 'S'";
        $comando = $con_academico->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
            $comando->bindParam(":Id",$search, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'Ids',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['PrimerNombre', 'SegundoNombre',"PrimerApellido","SegundoApellido","Celular","Correo","Cedula"],
            ],
        ]);

        return $dataProvider;
    }

    public function getProfesores(){
        $con_asgard = \Yii::$app->db_asgard;
        $con_academico = \Yii::$app->db_academico;

        $sql = "SELECT
                    pro.pro_id AS Id,
                    CONCAT(pe.per_pri_apellido, ' ', pe.per_pri_nombre) AS Nombres,
                    ddoc_id as dedica
                FROM 
                    " . $con_academico->dbname . ".profesor AS pro
                    INNER JOIN " . $con_asgard->dbname . ".persona AS pe ON pro.per_id = pe.per_id
                WHERE 
                    pro.pro_estado = 1 AND 
                    pro.pro_estado_logico = 1 AND 
                    pe.per_estado = 1 AND
                    pe.per_estado_logico = 1
                ORDER BY 
                    pe.per_pri_apellido ASC";
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();
        return $res;
    }
    
    
    public function getProfesoresDistributivo(){
        $con_asgard = \Yii::$app->db_asgard;
        $con_academico = \Yii::$app->db_academico;

        $sql = "SELECT
                    pro.pro_id AS Id,
                    CONCAT(pe.per_pri_apellido, ' ', pe.per_pri_nombre) AS Nombres,
                    ddoc_id as dedica
                FROM 
                    " . $con_academico->dbname . ".profesor AS pro
                    INNER JOIN " . $con_asgard->dbname . ".persona AS pe ON pro.per_id = pe.per_id
                    INNER JOIN " . $con_academico->dbname . ".distributivo_cabecera AS dc ON dc.pro_id = pro.pro_id
                WHERE 
                    dc.dcab_estado_revision = 0 AND
                    dc.dcab_estado = 1 AND
                    pro.pro_estado = 1 AND 
                    pro.pro_estado_logico = 1 AND 
                    pe.per_estado = 1 AND
                    pe.per_estado_logico = 1
                ORDER BY 
                    pe.per_pri_apellido ASC";
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();
        return $res;
    }

    /**
     * Consulta los profesores que estén dando alguna asignatura
     * @author 
     * @param
     * @return Arreglo con per_id, pro_id, cedula y nombres de los profesores que tengan materias registradas para disctar y hayan sido aprobados
     */
     public function getProfesoresEnAsignaturasByPerId($per_id, $onlyData = true){
        $con_asgard = Yii::$app->db_asgard;
        $con_academico = Yii::$app->db_academico;

        $sql = "SELECT DISTINCT per.per_id, pro.pro_id, per.per_cedula, per.per_correo, CONCAT(per.per_pri_nombre, ' ', per.per_pri_apellido) AS nombres
                FROM " . $con_asgard->dbname . ".persona as per
                INNER JOIN " . $con_academico->dbname . ".profesor AS pro ON pro.per_id = per.per_id
                INNER JOIN " . $con_academico->dbname . ".distributivo_academico AS daca ON daca.pro_id = pro.pro_id
                INNER JOIN " . $con_academico->dbname . ".distributivo_cabecera AS dcab ON dcab.pro_id = pro.pro_id
                WHERE per.per_id = :per_id AND dcab.dcab_estado_revision = 2
                AND per.per_estado = 1 AND per.per_estado_logico = 1
                AND pro.pro_estado = 1 AND pro.pro_estado_logico = 1
                AND daca.daca_estado = 1 AND daca.daca_estado_logico = 1
                AND dcab.dcab_estado = 1 AND dcab.dcab_estado_logico = 1
                ORDER BY nombres DESC";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id",$per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();

        /*if($onlyData){
            return $resultData;
        }*/

        return $resultData;
    }

    /**
     * Consulta los profesores que estén dando alguna asignatura
     * @author Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @param
     * @return Arreglo con per_id, pro_id, cedula y nombres de los profesores que tengan materias registradas para disctar y hayan sido aprobados
     */
    public function getProfesoresEnAsignaturas($onlyData = true){
        $con_asgard = Yii::$app->db_asgard;
        $con_academico = Yii::$app->db_academico;

        $sql = "SELECT DISTINCT per.per_id, pro.pro_id, per.per_cedula, per.per_correo, CONCAT(per.per_pri_nombre, ' ', per.per_pri_apellido) AS nombres
                FROM " . $con_asgard->dbname . ".persona as per
                INNER JOIN " . $con_academico->dbname . ".profesor AS pro ON pro.per_id = per.per_id
                INNER JOIN " . $con_academico->dbname . ".distributivo_academico AS daca ON daca.pro_id = pro.pro_id
                INNER JOIN " . $con_academico->dbname . ".distributivo_cabecera AS dcab ON dcab.pro_id = pro.pro_id
                WHERE dcab.dcab_estado_revision = 2
                AND per.per_estado = 1 AND per.per_estado_logico = 1
                AND pro.pro_estado = 1 AND pro.pro_estado_logico = 1
                AND daca.daca_estado = 1 AND daca.daca_estado_logico = 1
                AND dcab.dcab_estado = 1 AND dcab.dcab_estado_logico = 1
                ORDER BY nombres DESC";

        $comando = $con_academico->createCommand($sql);
        $resultData = $comando->queryAll();

        if($onlyData){
            return $resultData;
        }

        return $resultData;
    }

    public function getProfesoresxid($per_id){
        $con_asgard = Yii::$app->db_asgard;
        $con_academico = Yii::$app->db_academico;
        $estado = '1';

        $sql = "SELECT
                    pro.pro_id AS Id,
                    CONCAT(pe.per_pri_apellido, ' ', pe.per_pri_nombre) AS Nombres
                FROM 
                    " . $con_academico->dbname . ".profesor AS pro
                    INNER JOIN " . $con_asgard->dbname . ".persona AS pe ON pro.per_id = pe.per_id
                WHERE 
                    pro.per_id = :per_id AND
                    pro.pro_estado = :estado AND 
                    pro.pro_estado_logico = :estado AND 
                    pe.per_estado = :estado AND
                    pe.per_estado_logico = :estado";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id",$per_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado",$estado, \PDO::PARAM_STR);
        $res = $comando->queryOne();
        return $res;
    }

    /**
     * Retorna si el profesor está aprobado en el distributivo
     * @author Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @param
     * @return 
     */
    public function isAprobado($pro_id){
        $con = Yii::$app->db_academico;

        $sql = "SELECT * FROM " . $con->dbname . ".distributivo_cabecera AS dcab
                INNER JOIN " . $con->dbname . ".profesor AS pro ON pro.pro_id = dcab.pro_id
                WHERE dcab.dcab_estado = 1 AND dcab.dcab_estado_logico = 1
                AND pro.pro_estado = 1 AND pro.pro_estado_logico = 1
                AND pro.pro_id = $pro_id";

        $comando = $con->createCommand($sql);
        $resultData = $comando->queryAll();

        // Si aún no tiene registro en la tabla
        if(empty($resultData)){
            return 0;
        }

        // Si no está aprobado
        $estado = $resultData[0]['dcab_estado_revision'];
        if($estado != 2){
            return 0;
        }

        // Si llega aquí, tiene registro en la tabla como aprobado
        return 1;
    }

    /**
     * Function consulta si el profesor.
     * @author Julio Lopez <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function getProfesoresDist($pro_id){
        $con_asgard = \Yii::$app->db_asgard;
        $con_academico = \Yii::$app->db_academico;

        $sql = "SELECT
                    pro.pro_id AS id,
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS name
                FROM 
                    " . $con_academico->dbname . ".profesor AS pro
                    INNER JOIN " . $con_asgard->dbname . ".persona AS pe ON pro.per_id = pe.per_id
                WHERE 
                    pro.pro_estado = 1 AND 
                    pro.pro_estado_logico = 1 AND 
                    pe.per_estado = 1 AND
                    pe.per_estado_logico = 1 AND
                    pro.pro_id = :pro_id
                ORDER BY pe.per_pri_apellido ASC;";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":pro_id",$pro_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        return $res;
    }
}