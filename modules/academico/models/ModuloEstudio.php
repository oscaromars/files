<?php

namespace app\modules\academico\models;

use yii\data\ArrayDataProvider;
use DateTime;
use Yii;

/**
 * This is the model class for table "modulo_estudio".
 *
 * @property int $mest_id
 * @property int $uaca_id
 * @property int $mod_id
 * @property string $mest_nombre
 * @property string $mest_descripcion
 * @property int $mest_usuario_ingreso
 * @property int $mest_usuario_modifica
 * @property string $mest_estado
 * @property string $mest_fecha_creacion
 * @property string $mest_fecha_modificacion
 * @property string $mest_estado_logico
 *
 * @property AsignacionParalelo[] $asignacionParalelos
 * @property UnidadAcademica $uaca
 * @property Modalidad $mod
 */
class ModuloEstudio extends \app\modules\academico\components\CActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'modulo_estudio';
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
            [['uaca_id', 'mod_id', 'mest_nombre', 'mest_descripcion', 'mest_usuario_ingreso', 'mest_estado', 'mest_estado_logico'], 'required'],
            [['uaca_id', 'mod_id', 'mest_usuario_ingreso', 'mest_usuario_modifica'], 'integer'],
            [['mest_fecha_creacion', 'mest_fecha_modificacion'], 'safe'],
            [['mest_nombre', 'mest_descripcion'], 'string', 'max' => 300],
            [['mest_estado', 'mest_estado_logico'], 'string', 'max' => 1],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
            [['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidad::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'mest_id' => 'Mest ID',
            'uaca_id' => 'Uaca ID',
            'mod_id' => 'Mod ID',
            'mest_nombre' => 'Mest Nombre',
            'mest_descripcion' => 'Mest Descripcion',
            'mest_usuario_ingreso' => 'Mest Usuario Ingreso',
            'mest_usuario_modifica' => 'Mest Usuario Modifica',
            'mest_estado' => 'Mest Estado',
            'mest_fecha_creacion' => 'Mest Fecha Creacion',
            'mest_fecha_modificacion' => 'Mest Fecha Modificacion',
            'mest_estado_logico' => 'Mest Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignacionParalelos() {
        return $this->hasMany(AsignacionParalelo::className(), ['mest_id' => 'mest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaca() {
        return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMod() {
        return $this->hasOne(Modalidad::className(), ['mod_id' => 'mod_id']);
    }

    /**
     * Function obtener cursos de Educación Continua y Centro de Idiomas según unidad academica y modalidad.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarCursoModalidad($unidad, $modalidad) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT me.mest_id as id, mest_nombre as name
                FROM " . $con->dbname . ".modulo_estudio me inner join " . $con->dbname . ".modalidad m on m.mod_id = me.mod_id
                WHERE me.uaca_id = :unidad
                    and me.mod_id = :modalidad
                    and me.mest_estado = :estado
                    and me.mest_estado_logico = :estado
                    and m.mod_estado = :estado
                    and m.mod_estado_logico = :estado
                ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
        $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function obtener cursos de Educación Continua y Centro de Idiomas según empresa.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarEstudioEmpresa() {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT
                    mest_id as id,
                    mest_nombre as name
                    FROM
                    " . $con->dbname . ".modulo_estudio me
                    WHERE
                    me.mest_estado_logico = :estado AND
                    me.mest_estado = :estado
                    ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function obtener otros estudios academicos
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarOtrosEstudiosAcademicos($uaca_id, $mod_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    oeac.oeac_id as id,
                    oeac.oeac_descripcion as name
                    FROM
                    " . $con->dbname . ".otro_estudio_academico oeac "
                . " inner join " . $con->dbname . ".unidad_academica as uaca on uaca.uaca_id = oeac.uaca_id"
                . " inner join " . $con->dbname . ".modalidad modo on modo.mod_id = oeac.mod_id
                    WHERE
                    uaca.uaca_id = :uaca_id AND
                    oeac.mod_id = :mod_id AND
                    oeac.oeac_estado_logico= :estado AND
                    oeac.oeac_estado= :estado
                    ORDER BY name asc";
        //exit($sql);
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function obtener cursos de Smart y Ulink
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarDesModuloestudio($emp_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT
                    mes.mest_id as id,
                    mes.mest_descripcion as name
                    FROM
                    " . $con->dbname . ".modulo_estudio_empresa mee "
                . "inner join " . $con->dbname . ".modulo_estudio mes on mes.mest_id = mee.mest_id
                    WHERE
                    emp_id = :emp_id AND
                    mes.mest_estado_logico= :estado AND
                    mes.mest_estado= :estado AND
                    mee.meem_estado_logico = :estado AND
                    mee.meem_estado = :estado
                    ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function obtener modalidad de Smart
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarModalidadModestudio() {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT distinct m.mod_id id, m.mod_nombre name
                FROM db_academico.modulo_estudio me inner join db_academico.modalidad m on m.mod_id = me.mod_id
                WHERE me.mest_estado = :estado
                      and me.mest_estado_logico = :estado
                      and m.mod_estado = :estado
                      and m.mod_estado_logico = :estado
                ORDER BY name asc;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function obtener meun_id
     * @author  Giovanni Vergarai <analistadesarrollo02@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarModalidadestudiouni($uaca_id, $mod_id, $eaca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT meun_id
                FROM " . $con->dbname . ".modalidad_estudio_unidad
                WHERE uaca_id = :uaca_id
                      and mod_id = :mod_id
                      and eaca_id = :eaca_id
                      and meun_estado_logico = :estado
                      and meun_estado = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

}
