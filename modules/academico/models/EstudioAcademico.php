<?php

namespace app\modules\academico\models;

use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "estudio_academico".
 *
 * @property int $eaca_id
 * @property int $teac_id
 * @property string $eaca_nombre
 * @property string $eaca_descripcion
 * @property string $eaca_alias
 * @property string $eaca_alias_resumen
 * @property int $eaca_usuario_ingreso
 * @property int $eaca_usuario_modifica
 * @property string $eaca_estado
 * @property string $eaca_fecha_creacion
 * @property string $eaca_fecha_modificacion
 * @property string $eaca_estado_logico
 *
 * @property TipoEstudioAcademico $teac
 * @property MallaAcademica[] $mallaAcademicas
 * @property ModalidadEstudioUnidad[] $modalidadEstudioUnidads
 */
class EstudioAcademico extends \app\modules\admision\components\CActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'estudio_academico';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['teac_id', 'eaca_nombre', 'eaca_descripcion', ], 'required'],
            [['teac_id', 'eaca_usuario_ingreso', 'eaca_usuario_modifica'], 'integer'],
            [['eaca_fecha_creacion', 'eaca_fecha_modificacion'], 'safe'],
            [['eaca_nombre', 'eaca_alias',], 'string', 'max' => 300],
            [['eaca_alias_resumen'], 'string', 'max' => 30],
            [['eaca_descripcion'], 'string', 'max' => 500],
            [['eaca_estado', 'eaca_estado_logico'], 'string', 'max' => 1],
            [['teac_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoEstudioAcademico::className(), 'targetAttribute' => ['teac_id' => 'teac_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'eaca_id' => 'Eaca ID',
            'teac_id' => 'Tipo de Estudio Acad??mico',
            'eaca_nombre' => 'Nombre',
            'eaca_descripcion' => 'Descripci??n',
            'eaca_alias' => 'Alias',
            'eaca_alias_resumen' => 'Resumen',
            'eaca_usuario_ingreso' => '',
            'eaca_usuario_modifica' => '',
            'eaca_estado' => 'Estado',
            'eaca_fecha_creacion' => '',
            'eaca_fecha_modificacion' => '',
            'eaca_estado_logico' => '',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeac() {
        return $this->hasOne(TipoEstudioAcademico::className(), ['teac_id' => 'teac_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaAcademicas() {
        return $this->hasMany(MallaAcademica::className(), ['eaca_id' => 'eaca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModalidadEstudioUnidads() {
        return $this->hasMany(ModalidadEstudioUnidad::className(), ['eaca_id' => 'eaca_id']);
    }

    public function obtenerCarreraXFacu($nint_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    car.car_id AS id,
                    car.car_nombre AS value
               FROM " . $con->dbname . ".modalidad_carrera_nivel mcn
                    INNER JOIN " . $con->dbname . ".carrera as car on car.car_id = mcn.car_id
               WHERE  car.car_estado_logico = :estado AND
                    car.car_estado = :estado AND
                    car.car_estado_logico=:estado AND
                    mcn.mcni_estado=:estado AND
                    mcn.mcni_estado_logico=:estado AND
                    mcn.mod_id=:nint_id
               ORDER BY value asc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":nint_id", $nint_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    public function obtenerNombreCarrera($carrera_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    car.car_nombre
               FROM " . $con->dbname . ".carrera car
               WHERE car.car_id = :carrera_id AND
                    car.car_estado = :estado AND
                    car.car_estado_logico=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":carrera_id", $carrera_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function listadoAreaConocimiento() {
        $con = \Yii::$app->db_academico;
        $estado = 1;


        $sql = "SELECT  acon_id as id,
                        acon_nombre as area_conocimiento
                FROM "
                . $con->dbname . ".area_conocimiento
                WHERE   acon_estado = :estado AND
                        acon_estado_logico = :estado
                ORDER BY area_conocimiento asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    /**
     * Function obtener Facultad segun nivel interes estudio
     * @author
     * @property
     * @return
     */
    public function obtenerFacultad($nivelinteres) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    fact.fac_id as id,
                    fact.fac_nombre as name
                FROM
                    " . $con->dbname . ".facultad as fact
                WHERE
                    fact.nint_id=:nivelinteres AND
                    fact.fac_estado_logico=:estado AND
                    fact.fac_estado=:estado
                ORDER BY name asc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":nivelinteres", $nivelinteres, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function obtener consultarCarrera
     * @author      Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @property
     * @return
     */
    public function consultarCarrera() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    eac.eaca_id AS id,
                    eac.eaca_nombre AS value
               FROM " . $con->dbname . ".estudio_academico eac
               WHERE  eac.eaca_estado_logico = :estado AND
                      eac.eaca_estado = :estado
               ORDER BY 2 ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarIdsCarrera
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public static function consultarIdsEstudioAca($TextAlias) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT eaca_id Ids
                    FROM " . $con->dbname . ".estudio_academico
                WHERE eaca_estado=1 AND eaca_alias=:eaca_alias ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":eaca_alias", $TextAlias, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto
        return $rawData;
    }

    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarIdsCarrera
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public static function consultarIdsModEstudio($CodEmp, $TextAlias) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT A.mest_id Ids
                    FROM " . $con->dbname . ".modulo_estudio A
                            INNER JOIN 	" . $con->dbname . ".modulo_estudio_empresa B
                                    ON A.mest_id=B.mest_id
            WHERE A.mest_estado=1 AND B.emp_id=:emp_id AND A.mest_alias=:mest_alias;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":mest_alias", $TextAlias, \PDO::PARAM_STR);
        $comando->bindParam(":emp_id", $CodEmp, \PDO::PARAM_INT);
        //return $comando->queryAll();
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto
        return $rawData;
    }

    /**
     * Function obtener consultarCarrera x unidad
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property
     * @return
     */
    public function consultarCarreraxunidad($unidad) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    eac.eaca_id AS id,
                    eac.eaca_nombre AS name
               FROM " . $con->dbname . ".estudio_academico eac
               WHERE  eac.eaca_estado_logico = :estado AND
                      eac.eaca_estado = :estado AND
                      eac.teac_id = :unidad
               ORDER BY 2 ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function obtener consultarmodalidadxcarrera
     * @author   Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property
     * @return
     */
    public function consultarmodalidadxcarrera($eaca_id) {
        //\app\models\Utilities::putMessageLogFile('carrera modelo 111..: '. $data['eaca_id']);
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    meu.mod_id AS id,
                    moda.mod_nombre AS name
               FROM " . $con->dbname . ".modalidad_estudio_unidad meu
               INNER JOIN " . $con->dbname . ".modalidad moda ON moda.mod_id = meu.mod_id
               WHERE  meu.eaca_id = :eaca_id AND
                      meu.meun_estado_logico = :estado AND
                      meu.meun_estado = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function obtener carreras segun unidad academica y modalidad
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarCarreraModalidad($unidad, $modalidad) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
                SELECT
                        eac.eaca_id as id,
                        eac.eaca_nombre as name
                    FROM
                        " . $con->dbname . ".modalidad_estudio_unidad as mcn
                        INNER JOIN " . $con->dbname . ".estudio_academico as eac on eac.eaca_id = mcn.eaca_id
                    WHERE
                        mcn.uaca_id =:unidad AND
                        mcn.mod_id =:modalidad AND
                        -- eac.eaca_estado_logico=:estado AND
                        -- eac.eaca_estado=:estado AND
                        mcn.meun_estado_logico = :estado AND
                        mcn.meun_estado = :estado
                        ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
        $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

      /**
     * Function para consultar el estudio acad??mico por el ID del estudiante
     * @author   Jorge Paladines <analista.desarrollo@uteg.edu.ec>
     * @property
     * @return
     */
    public function consultarEstudioAcademicoPorEstudiante($est_id){
        $con = Yii::$app->db_academico;

        $sql = "SELECT eaca.eaca_descripcion as programa
                FROM db_academico.estudiante_carrera_programa AS ecpr
                INNER JOIN db_academico.modalidad_estudio_unidad AS meun ON meun.meun_id = ecpr.meun_id
                INNER JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = meun.eaca_id
                WHERE ecpr.ecpr_estado = 1 AND ecpr.ecpr_estado_logico = 1
                AND meun.meun_estado = 1 AND meun.meun_estado_logico = 1
                AND eaca.eaca_estado = 1 AND eaca.eaca_estado_logico = 1
                AND ecpr.est_id = $est_id";

        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();

        return $resultData;
    }

    /**
     * Function para consultar si el estudio acad??mico est?? siendo usado
     * @author   Jorge Paladines <analista.desarrollo@uteg.edu.ec>
     * @property
     * @return
     */
    public function consultarEstudioAcademicoEnUso($eaca_id){
        $con = Yii::$app->db_academico;

        $sql = "SELECT * FROM " . $con->dbname . ".estudio_academico as eaca
                INNER JOIN " . $con->dbname . ".modalidad_estudio_unidad as meun ON meun.eaca_id = eaca.eaca_id
                INNER JOIN " . $con->dbname . ".estudiante_carrera_programa as ecpr ON ecpr.meun_id = meun.meun_id
                WHERE eaca.eaca_estado = 1 AND eaca.eaca_estado_logico = 1
                AND meun.meun_estado = 1 AND meun.meun_estado_logico = 1
                AND ecpr.ecpr_estado = 1 AND ecpr.ecpr_estado_logico = 1
                AND eaca.eaca_id = $eaca_id";

        $comando = $con->createCommand($sql);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    public function consultarEstudiosSemestreActual() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    car.car_id AS id,
                    car.car_nombre AS value
               FROM " . $con->dbname . ".modalidad_carrera_nivel mcn
                    INNER JOIN " . $con->dbname . ".carrera as car on car.car_id = mcn.car_id
               WHERE  car.car_estado_logico = :estado AND
                    car.car_estado = :estado AND
                    car.car_estado_logico=:estado AND
                    mcn.mcni_estado=:estado AND
                    mcn.mcni_estado_logico=:estado AND
                    mcn.mod_id=:nint_id
               ORDER BY value asc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":nint_id", $nint_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function Obtiene carrera ,modalidad, segun per_id del estudiante.
     * @author  Julio Lopez <analistadesarrollo03@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos).
     */
    public function selectCarreraEst($per_id) {
        $con = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db_asgard;
        //$estado = 1;

        $sql = "SELECT 
                    ea.eaca_id AS eaca_id,
                    ea.eaca_codigo AS eaca_codigo,
                    ea.eaca_nombre AS eaca_nombre,
                    moda.mod_id AS mod_id,
                    moda.mod_nombre AS mod_nombre
                            
                FROM " . $con2->dbname . ".persona as p
                INNER JOIN  " . $con->dbname . ".estudiante AS e on p.per_id = e.per_id
                INNER JOIN  " . $con->dbname . ".estudiante_carrera_programa AS ec ON e.est_id = ec.est_id
                INNER JOIN  " . $con->dbname . ".modalidad_estudio_unidad AS me ON ec.meun_id = me.meun_id
                INNER JOIN  " . $con->dbname . ".modalidad AS moda  ON me.mod_id = moda.mod_id
                INNER JOIN  " . $con->dbname . ".estudio_academico AS ea ON ea.eaca_id = me.eaca_id
                INNER JOIN  " . $con->dbname . ".unidad_academica AS ua ON me.uaca_id = ua.uaca_id
                WHERE p.per_id = :per_id
                  AND p.per_estado = 1 AND p.per_estado_logico =1
                  AND e.est_estado = 1 AND e.est_estado_logico = 1
                  AND ec.ecpr_estado = 1 AND ec.ecpr_estado_logico= 1
                  AND moda.mod_estado = 1 AND moda.mod_estado_logico = 1
                  AND ea.eaca_estado = 1 AND ea.eaca_estado_logico = 1
                  AND ua.uaca_estado = 1 AND ua.uaca_estado_logico = 1;";


        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryOne();
        //\app\models\Utilities::putMessageLogFile('selectCarreraEst: '. $comando->getRawSql());
        return $resultData;
    }

}
