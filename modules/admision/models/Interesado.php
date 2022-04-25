<?php

namespace app\modules\admision\models;

use yii\data\ArrayDataProvider;
use app\models\Utilities;
use DateTime;
use Yii;

/**
 * This is the model class for table "interesado".
 *
 * @property integer $int_id
 * @property integer $per_id
 * @property string $int_nombres
 * @property string $int_apellidos
 * @property string $int_estado
 * @property string $int_fecha_creacion
 * @property string $int_fecha_modificacion
 * @property string $int_estado_logico
 *
 * @property Aspirante[] $aspirantes
 * @property InformacionAcademica[] $informacionAcademicas
 * @property InformacionDiscapacidad[] $informacionDiscapacidads
 * @property InformacionEnfermedad[] $informacionEnfermedads
 * @property InformacionFamilia[] $informacionFamilias
 * @property SolicitudInscripcion[] $solicitudInscripcions
 */
class Interesado extends \app\modules\admision\components\CActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        //return 'interesado';
        return \Yii::$app->db_captacion->dbname . '.interesado';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['per_id', 'int_nombres', 'int_apellidos', 'int_estado', 'int_estado_logico'], 'required'],
            [['per_id'], 'integer'],
            [['int_fecha_creacion', 'int_fecha_modificacion'], 'safe'],
            [['int_nombres', 'int_apellidos'], 'string', 'max' => 100],
            [['int_estado', 'int_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'int_id' => 'Int ID',
            'per_id' => 'Per ID',
            'int_nombres' => 'Int Nombres',
            'int_apellidos' => 'Int Apellidos',
            'int_estado' => 'Int Estado',
            'int_fecha_creacion' => 'Int Fecha Creacion',
            'int_fecha_modificacion' => 'Int Fecha Modificacion',
            'int_estado_logico' => 'Int Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAspirantes() {
        return $this->hasMany(Aspirante::className(), ['int_id' => 'int_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInformacionAcademicas() {
        return $this->hasMany(InformacionAcademica::className(), ['int_id' => 'int_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInformacionDiscapacidads() {
        return $this->hasMany(InformacionDiscapacidad::className(), ['int_id' => 'int_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInformacionEnfermedads() {
        return $this->hasMany(InformacionEnfermedad::className(), ['int_id' => 'int_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInformacionFamilias() {
        return $this->hasMany(InformacionFamilia::className(), ['int_id' => 'int_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudInscripcions() {
        return $this->hasMany(SolicitudInscripcion::className(), ['int_id' => 'int_id']);
    }

    /**
     * Function findByCondition
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $pint_id
     * @property string $pcon_nombre
     * @property integer $tpar_id
     * @property string $pcon_telefono
     * @property string $pcon_celular
     * @property string $pcon_estado
     * @property string $pcon_estado_logico
     *
     */
    public function crearInteresado($pint_id, $user_id) {
        $con = \Yii::$app->db_captacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "int_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", int_estado";
        $bsol_sql .= ", 1";

        $param_sql .= ", int_estado_interesado";
        $bsol_sql .= ", 1";

        if (isset($pint_id)) {
            $param_sql .= ", pint_id";
            $bsol_sql .= ", :pint_id";
        }

        if (isset($user_id)) {
            $param_sql .= ", int_usuario_ingreso";
            $bsol_sql .= ", :int_usuario_ingreso";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".interesado ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($pint_id))
                $comando->bindParam(':pint_id', $pint_id, \PDO::PARAM_INT);

            if (isset($user_id))
                $comando->bindParam(':int_usuario_ingreso', $user_id, \PDO::PARAM_INT);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.interesado');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    public function consultaInteresadoByPerId($per_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "
                    SELECT
                    ifnull(int_id,0) as int_id
                    FROM db_captacion.interesado
                    WHERE 
                    per_id = :per_id
                    and int_estado = :estado
                    and int_estado_logico=:estado
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(':per_id', $per_id, \PDO::PARAM_INT);
        $comando->bindParam(':estado', $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        if (empty($resultData['int_id']))
            return 0;
        else {
            return $resultData['int_id'];
        }
    }

    public function insertarInteresado($con, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $param_sql .= "" . $keys[0];
        $bdet_sql .= "'" . $parameters[0] . "'";
        for ($i = 1; $i < count($parameters); $i++) {
            if (isset($parameters[$i])) {
                $param_sql .= ", " . $keys[$i];
                $bdet_sql .= ", '" . $parameters[$i] . "'";
            }
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . '.' . $name_table . " ($param_sql) VALUES($bdet_sql);";
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.' . $name_table);
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    public function crearInfoAcaInteresado($int_id, $pai_id, $pro_id, $can_id, $tiac_id, $tnes_id, $iaca_institucion, $iaca_titulo, $iaca_anio_grado) {

        $con = \Yii::$app->db_captacion;
        //$estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "iaca_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", iaca_estado";
        $bsol_sql .= ", 1";
        if (isset($int_id)) {
            $param_sql .= ", int_id";
            $bsol_sql .= ", :int_id";
        }

        if (isset($pai_id)) {
            $param_sql .= ", pai_id";
            $bsol_sql .= ", :pai_id";
        }

        if (isset($pro_id)) {
            $param_sql .= ", pro_id";
            $bsol_sql .= ", :pro_id";
        }

        if (isset($can_id)) {
            $param_sql .= ", can_id";
            $bsol_sql .= ", :can_id";
        }

        if (isset($tiac_id)) {
            $param_sql .= ", tiac_id";
            $bsol_sql .= ", :tiac_id";
        }
        if (isset($tnes_id)) {
            $param_sql .= ", tnes_id";
            $bsol_sql .= ", :tnes_id";
        }

        if (isset($iaca_institucion)) {
            $param_sql .= ", iaca_institucion";
            $bsol_sql .= ", :iaca_institucion";
        }

        if (isset($iaca_titulo)) {
            $param_sql .= ", iaca_titulo";
            $bsol_sql .= ", :iaca_titulo";
        }

        if (isset($iaca_anio_grado)) {
            $param_sql .= ", iaca_anio_grado";
            $bsol_sql .= ", :iaca_anio_grado";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".info_academico ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($int_id))
                $comando->bindParam(':int_id', $int_id, \PDO::PARAM_INT);

            if (isset($pai_id))
                $comando->bindParam(':pai_id', $pai_id, \PDO::PARAM_INT);

            if (isset($pro_id))
                $comando->bindParam(':pro_id', $pro_id, \PDO::PARAM_INT);

            if (isset($can_id))
                $comando->bindParam(':can_id', $can_id, \PDO::PARAM_INT);

            if (isset($tiac_id))
                $comando->bindParam(':tiac_id', $tiac_id, \PDO::PARAM_INT);

            if (isset($tnes_id))
                $comando->bindParam(':tnes_id', $tnes_id, \PDO::PARAM_INT);

            if (isset($iaca_institucion))
                $comando->bindParam(':iaca_institucion', ucwords(strtolower($iaca_institucion)), \PDO::PARAM_STR);

            if (isset($iaca_titulo))
                $comando->bindParam(':iaca_titulo', ucwords(strtolower($iaca_titulo)), \PDO::PARAM_STR);

            if (isset($iaca_anio_grado))
                $comando->bindParam(':iaca_anio_grado', $iaca_anio_grado, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.info_academico');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    public function crearInfoFamInteresado($int_id, $nins_padre, $nins_madre, $ifam_miembro, $ifam_salario) {

        $con = \Yii::$app->db_captacion;
        //$estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "ifam_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", ifam_estado";
        $bsol_sql .= ", 1";

        if (isset($int_id)) {
            $param_sql .= ", int_id";
            $bsol_sql .= ", :int_id";
        }
        if (isset($nins_padre)) {
            $param_sql .= ", nins_padre";
            $bsol_sql .= ", :nins_padre";
        }
        if (isset($nins_madre)) {
            $param_sql .= ", nins_madre";
            $bsol_sql .= ", :nins_madre";
        }
        if (isset($ifam_miembro)) {
            $param_sql .= ", ifam_miembro";
            $bsol_sql .= ", :ifam_miembro";
        }

        if (isset($ifam_salario)) {
            $param_sql .= ", ifam_salario";
            $bsol_sql .= ", :ifam_salario";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".informacion_familia ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($int_id))
                $comando->bindParam(':int_id', $int_id, \PDO::PARAM_INT);

            if (isset($nins_padre))
                $comando->bindParam(':nins_padre', $nins_padre, \PDO::PARAM_INT);

            if (isset($nins_madre))
                $comando->bindParam(':nins_madre', $nins_madre, \PDO::PARAM_INT);

            if (isset($ifam_miembro))
                $comando->bindParam(':ifam_miembro', $ifam_miembro, \PDO::PARAM_STR);

            if (isset($ifam_salario))
                $comando->bindParam(':ifam_salario', $ifam_salario, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.informacion_familia');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    public function crearInfoDisInteresado($int_id, $tdis_id, $idis_discapacidad, $idis_porcentaje, $idis_archivo) {

        $con = \Yii::$app->db_captacion;
        //$estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "idis_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", idis_estado";
        $bsol_sql .= ", 1";

        if (isset($int_id)) {
            $param_sql .= ", int_id";
            $bsol_sql .= ", :int_id";
        }

        if (isset($tdis_id)) {
            $param_sql .= ", tdis_id";
            $bsol_sql .= ", :tdis_id";
        }

        if (isset($idis_discapacidad)) {
            $param_sql .= ", idis_discapacidad";
            $bsol_sql .= ", :idis_discapacidad";
        }

        if (isset($idis_porcentaje)) {
            $param_sql .= ", idis_porcentaje";
            $bsol_sql .= ", :idis_porcentaje";
        }

        if (isset($idis_archivo)) {
            $param_sql .= ", idis_archivo";
            $bsol_sql .= ", :idis_archivo";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".info_discapacidad ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($int_id))
                $comando->bindParam(':int_id', $int_id, \PDO::PARAM_INT);

            if (isset($tdis_id))
                $comando->bindParam(':tdis_id', $tdis_id, \PDO::PARAM_INT);

            if (isset($idis_discapacidad))
                $comando->bindParam(':idis_discapacidad', $idis_discapacidad, \PDO::PARAM_STR);

            if (isset($idis_porcentaje))
                $comando->bindParam(':idis_porcentaje', $idis_porcentaje, \PDO::PARAM_STR);

            if (isset($idis_archivo))
                $comando->bindParam(':idis_archivo', $idis_archivo, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.info_discapacidad');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    public function crearInfoEnfInteresado($int_id, $ienf_enfermedad, $ienf_tipoenfermedad, $ienf_archivo) {

        $con = \Yii::$app->db_captacion;
        //$estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "ienf_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", ienf_estado";
        $bsol_sql .= ", 1";

        if (isset($int_id)) {
            $param_sql .= ", int_id";
            $bsol_sql .= ", :int_id";
        }

        if (isset($ienf_enfermedad)) {
            $param_sql .= ", ienf_enfermedad";
            $bsol_sql .= ", :ienf_enfermedad";
        }

        if (isset($ienf_tipoenfermedad)) {
            $param_sql .= ", ienf_tipoenfermedad";
            $bsol_sql .= ", :ienf_tipoenfermedad";
        }

        if (isset($ienf_archivo)) {
            $param_sql .= ", ienf_archivo";
            $bsol_sql .= ", :ienf_archivo";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".info_enfermedad ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($int_id))
                $comando->bindParam(':int_id', $int_id, \PDO::PARAM_INT);

            if (isset($ienf_enfermedad))
                $comando->bindParam(':ienf_enfermedad', $ienf_enfermedad, \PDO::PARAM_STR);

            if (isset($ienf_tipoenfermedad))
                $comando->bindParam(':ienf_tipoenfermedad', $ienf_tipoenfermedad, \PDO::PARAM_STR);

            if (isset($ienf_archivo))
                $comando->bindParam(':ienf_archivo', $ienf_archivo, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.info_enfermedad');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    public function crearInfoEnfFamiliadisc($int_id, $tpar_id, $tdis_id, $ifdi_discapacidad, $ifdi_porcentaje, $ifdi_archivo) {

        $con = \Yii::$app->db_captacion;
        //$estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "ifdi_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", ifdi_estado";
        $bsol_sql .= ", 1";

        if (isset($int_id)) {
            $param_sql .= ", int_id";
            $bsol_sql .= ", :int_id";
        }

        if (isset($tpar_id)) {
            $param_sql .= ", tpar_id";
            $bsol_sql .= ", :tpar_id";
        }

        if (isset($tdis_id)) {
            $param_sql .= ", tdis_id";
            $bsol_sql .= ", :tdis_id";
        }

        if (isset($ifdi_discapacidad)) {
            $param_sql .= ", ifdi_discapacidad";
            $bsol_sql .= ", :ifdi_discapacidad";
        }

        if (isset($ifdi_porcentaje)) {
            $param_sql .= ", ifdi_porcentaje";
            $bsol_sql .= ", :ifdi_porcentaje";
        }

        if (isset($ifdi_archivo)) {
            $param_sql .= ", ifdi_archivo";
            $bsol_sql .= ", :ifdi_archivo";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".info_familia_discapacidad ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($int_id))
                $comando->bindParam(':int_id', $int_id, \PDO::PARAM_INT);

            if (isset($tpar_id))
                $comando->bindParam(':tpar_id', $tpar_id, \PDO::PARAM_INT);

            if (isset($tdis_id))
                $comando->bindParam(':tdis_id', $tdis_id, \PDO::PARAM_STR);

            if (isset($ifdi_discapacidad))
                $comando->bindParam(':ifdi_discapacidad', $ifdi_discapacidad, \PDO::PARAM_STR);

            if (isset($ifdi_porcentaje))
                $comando->bindParam(':ifdi_porcentaje', $ifdi_porcentaje, \PDO::PARAM_STR);

            if (isset($ifdi_archivo))
                $comando->bindParam(':ifdi_archivo', $ifdi_archivo, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.info_familia_discapacidad');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    public function crearInfoEnfFamilia($int_id, $tpar_id, $ifen_tipoenfermedad, $ifen_enfermedad, $ifen_archivo) {

        $con = \Yii::$app->db_captacion;
        //$estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "ifen_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", ifen_estado";
        $bsol_sql .= ", 1";

        if (isset($int_id)) {
            $param_sql .= ", int_id";
            $bsol_sql .= ", :int_id";
        }

        if (isset($tpar_id)) {
            $param_sql .= ", tpar_id";
            $bsol_sql .= ", :tpar_id";
        }

        if (isset($ifen_tipoenfermedad)) {
            $param_sql .= ", ifen_tipoenfermedad";
            $bsol_sql .= ", :ifen_tipoenfermedad";
        }

        if (isset($ifen_enfermedad)) {
            $param_sql .= ", ifen_enfermedad";
            $bsol_sql .= ", :ifen_enfermedad";
        }

        if (isset($ifen_archivo)) {
            $param_sql .= ", ifen_archivo";
            $bsol_sql .= ", :ifen_archivo";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".info_familia_enfermedad ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($int_id))
                $comando->bindParam(':int_id', $int_id, \PDO::PARAM_INT);

            if (isset($tpar_id))
                $comando->bindParam(':tpar_id', $tpar_id, \PDO::PARAM_INT);

            if (isset($ifen_tipoenfermedad))
                $comando->bindParam(':ifen_tipoenfermedad', $ifen_tipoenfermedad, \PDO::PARAM_STR);

            if (isset($ifen_enfermedad))
                $comando->bindParam(':ifen_enfermedad', $ifen_enfermedad, \PDO::PARAM_STR);

            if (isset($ifen_archivo))
                $comando->bindParam(':ifen_archivo', $ifen_archivo, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.info_familia_enfermedad');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    public static function getInteresados() {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db;
        $estado = 1;

        $sql = "SELECT 
                    per.per_cedula as per_dni,
                    per.per_pri_nombre as per_pri_nombre,                    
                    per.per_seg_nombre as per_seg_nombre,
                    per.per_pri_apellido as per_pri_apellido,
                    per.per_seg_apellido as per_seg_apellido,
                    concat(per.per_pri_nombre ,' ', ifnull(per.per_seg_nombre,' ')) as per_nombres,
                    concat(per.per_pri_apellido ,' ', ifnull(per.per_seg_apellido,' ')) as per_apellidos,
                FROM 
                    " . $con->dbname . ".interesado as inte
                    INNER JOIN " . $con->dbname . ".pre_interesado as pint on inte.pint_id = pint.pint_id
                    INNER JOIN " . $con2->dbname . ".persona as per on pint.per_id = per.per_id 
                WHERE 
                    inte.int_estado_logico=:estado AND 
                    pint.pint_estado_logico=:estado AND
                    per.per_estado_logico=:estado AND 
                    inte.int_estado=:estado AND 
                    pint.pint_estado=:estado AND
                    per.per_estado=:estado";


        $comando = $con->createCommand($sql);
        $estado = 1;
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'per_dni',
                    'per_pri_nombre',
                    'per_seg_nombre',
                    'per_pri_apellido',
                    'per_seg_apellido',
                    'per_nombres',
                    'per_apellidos',
                ],
            ],
        ]);
        return $dataProvider;
    }

    public function consultaInfofamilia($per_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT 
                    inte.pint_id as persona, 
                    inte.int_id as interesado, 
                    infa.nins_padre as inst_padre, 
                    infa.nins_madre as inst_madre, 
                    infa.ifam_miembro as miembro, 
                    infa.ifam_salario as salario                    
                FROM 
                    " . $con->dbname . ".interesado inte                 
                    INNER JOIN informacion_familia infa ON infa.int_id = inte.int_id
                    INNER JOIN pre_interesado prei ON prei.pint_id = inte.pint_id
                WHERE  
                    prei.per_id = :per_id AND
                    inte.int_estado_logico = :estado AND 
                    inte.int_estado = :estado AND 
                    infa.ifam_estado_logico = :estado AND 
                    infa.ifam_estado = :estado";


        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultaInfoacademico($per_id, $tnes_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT 
                   inte.pint_id AS  persona, 
                   inte.int_id AS interesado, 
                   infa.pai_id AS pais, 
                   infa.pro_id AS provincia, 
                   infa.can_id AS canton, 
                   infa.tiac_id AS tipo_instituto, 
                   infa.tnes_id AS tipo_estudio, 
                   infa.iaca_institucion AS instituto, 
                   infa.iaca_titulo AS titulo, 
                   infa.iaca_anio_grado AS grado                   
                FROM 
                   " . $con->dbname . ".interesado inte                 
                   INNER JOIN info_academico infa ON infa.int_id = inte.int_id
                   INNER JOIN pre_interesado prei ON prei.pint_id = inte.pint_id
                WHERE 
                   prei.per_id = :per_id AND
                   inte.int_estado_logico = :estado AND 
                   inte.int_estado = :estado AND 
                   infa.iaca_estado_logico = :estado AND 
                   infa.iaca_estado = :estado AND 
                   infa.tnes_id = :tnes_id";
        //inte.pint_id = :per_id AND 
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":tnes_id", $tnes_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultaInfodiscapacidad($per_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT 
                    inte.pint_id AS persona, 
                    inte.int_id AS interesado,
                    infd.tdis_id AS tipo_discapacidad,
                    infd.idis_discapacidad AS discapacidad,
                    infd.idis_porcentaje AS porcentaje, 
                    infd.idis_archivo AS img_discapacidad
                    
                FROM 
                   " . $con->dbname . ".interesado inte                 
                    INNER JOIN info_discapacidad infd ON infd.int_id = inte.int_id                     
                    INNER JOIN pre_interesado prei ON prei.pint_id = inte.pint_id
                WHERE 
                    prei.per_id = :per_id AND
                    inte.int_estado_logico = :estado AND 
                    inte.int_estado = :estado AND
                    infd.idis_estado_logico = :estado AND 
                    infd.idis_estado = :estado";
        //inte.pint_id = :per_id AND 
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultaInfoenfermedad($per_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT 
                    inte.pint_id AS persona, 
                    inte.int_id AS interesado,               
                    infe.ienf_enfermedad AS enfermedad, 
                    infe.ienf_archivo AS img_enfermedad
                    
                FROM 
                   " . $con->dbname . ".interesado inte 
                    INNER JOIN info_enfermedad infe ON infe.int_id = inte.int_id                     
                    INNER JOIN pre_interesado prei ON prei.pint_id = inte.pint_id
                WHERE 
                    prei.per_id = :per_id AND
                    inte.int_estado_logico = :estado AND 
                    inte.int_estado = :estado AND                    
                    infe.ienf_estado_logico = :estado AND 
                    infe.ienf_estado = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultaInfofamailiadisc($per_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT 
                    inte.pint_id AS persona, 
                    inte.int_id AS interesado,                    
                    infdf.tpar_id AS parentescofa, 
                    infdf.tdis_id AS tipo_descapacidadfa,
                    infdf.ifdi_discapacidad AS discapacidadfa,
                    infdf.ifdi_porcentaje AS porcentajefa,
                    infdf.ifdi_archivo AS img_discapacidadfam
                FROM 
                   " . $con->dbname . ".interesado inte
                    INNER JOIN info_familia_discapacidad infdf ON infdf.int_id = inte.int_id 
                    INNER JOIN pre_interesado prei ON prei.pint_id = inte.pint_id
                WHERE 
                    prei.per_id = :per_id AND
                    inte.int_estado_logico = :estado AND 
                    inte.int_estado = :estado AND                      
                    infdf.ifdi_estado_logico = :estado AND 
                    infdf.ifdi_estado = :estado";
        //inte.pint_id = :per_id AND 
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultaInfoenfermedadfam($per_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT 
                    inte.pint_id AS persona, 
                    inte.int_id AS interesado,                   
                    infef.tpar_id AS parentescoen,
                    infef.ifen_enfermedad AS enfermedaden,
                    infef.ifen_tipoenfermedad AS tipoenfermedaden,
                    infef.ifen_archivo AS img_enfermedadfam
                FROM 
                   " . $con->dbname . ".interesado inte
                    INNER JOIN info_familia_enfermedad infef ON infef.int_id = inte.int_id 
                    INNER JOIN pre_interesado prei ON prei.pint_id = inte.pint_id
                WHERE 
                    prei.per_id = :per_id AND
                    inte.int_estado_logico = :estado AND 
                    inte.int_estado = :estado AND                     
                    infef.ifen_estado_logico = :estado AND 
                    infef.ifen_estado = :estado";
        //inte.pint_id = :per_id AND 
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultaInfoadicional($per_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT 
                    inte.pint_id AS persona, 
                    inte.int_id AS interesado,
                    infd.tdis_id AS tipo_discapacidad,
                    infd.idis_discapacidad AS discapacidad,
                    infd.idis_porcentaje AS porcentaje, 
                    infd.idis_archivo AS img_discapacidad,
                    infe.ienf_enfermedad AS enfermedad, 
                    infe.ienf_archivo AS img_enfermedad,
                    infdf.tpar_id AS parentescofa, 
                    infdf.tdis_id AS tipo_descapacidadfa,
                    infdf.ifdi_discapacidad AS discapacidadfa,
                    infdf.ifdi_porcentaje AS porcentajefa,
                    infdf.ifdi_archivo AS img_discapacidadfam,
                    infef.tpar_id AS parentescoen,
                    infef.ifen_enfermedad AS enfermedaden,
                    infef.ifen_tipoenfermedad AS tipoenfermedaden,
                    infef.ifen_archivo AS img_enfermedadfam
                FROM 
                   " . $con->dbname . ".interesado inte                 
                    INNER JOIN info_discapacidad infd ON infd.int_id = inte.int_id 
                    INNER JOIN info_enfermedad infe ON infe.int_id = inte.int_id 
                    INNER JOIN info_familia_discapacidad infdf ON infdf.int_id = inte.int_id 
                    INNER JOIN info_familia_enfermedad infef ON infef.int_id = inte.int_id 
                    INNER JOIN pre_interesado prei ON prei.pint_id = inte.pint_id
                WHERE 
                    prei.per_id = :per_id AND
                    inte.int_estado_logico = :estado AND 
                    inte.int_estado = :estado AND 
                    infd.idis_estado_logico = :estado AND 
                    infd.idis_estado = :estado AND 
                    infe.ienf_estado_logico = :estado AND 
                    infe.ienf_estado = :estado AND 
                    infdf.ifdi_estado_logico = :estado AND 
                    infdf.ifdi_estado = :estado AND 
                    infef.ifen_estado_logico = :estado AND 
                    infef.ifen_estado = :estado";
        //inte.pint_id = :per_id AND 
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function getInteresadoxIdPersona($per_id) {
        $con = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT 
                    inte.int_id AS int_id                            
                FROM 
                   " . $con2->dbname . ".interesado inte                  
                INNER JOIN " . $con->dbname . ".persona per on inte.per_id = per.per_id               
                WHERE                    
                    inte.int_estado_logico=:estado AND
                    inte.int_estado=:estado AND                    
                    per.per_estado_logico=:estado AND 
                    per.per_estado=:estado AND
                    per.per_id =:per_id";
        \app\models\Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData['int_id'];
    }

    public function consultaInteresadoById($per_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "
                    SELECT
                    ifnull(int_id,0) as int_id
                    FROM db_captacion.interesado
                    WHERE 
                    per_id = $per_id
                    and int_estado = $estado
                    and int_estado_logico=$estado
                ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if (empty($resultData['int_id']))
            return 0;
        else {
            return $resultData['int_id'];
        }
    }

    public function getPersonaxIdInteresado($int_id) {
        $con = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT 
                    per.per_id AS per_id                            
                FROM 
                   " . $con2->dbname . ".interesado inte                  
                INNER JOIN " . $con->dbname . ".persona per on inte.per_id = per.per_id               
                WHERE                    
                    inte.int_estado_logico=:estado AND 
                    inte.int_estado=:estado AND
                    inte.int_id =:int_id";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        if (count($resultData) > 0) {
            return $resultData['per_id'];
        } else {
            return 0;
        }
    }

    public function consultarIdinteresado($per_id) {
        $con = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT 
                    inte.int_id AS int_id
                FROM 
                   " . $con2->dbname . ".interesado inte                  
                INNER JOIN " . $con->dbname . ".persona per on inte.per_id = per.per_id               
                WHERE  per.per_id = :per_id AND
                    per.per_estado = :estado AND
                    per.per_estado_logico = :estado AND
                    inte.int_estado_logico=:estado AND 
                    inte.int_estado=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        //if(count($resultData)>0){
        return $resultData['int_id'];
        //}else{
        //return $resultData;
        //}
    }

    public function consultarInteresados($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_academico;
        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_pasaporte like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "inte.int_fecha_creacion >= :fec_ini AND ";
                $str_search .= "inte.int_fecha_creacion <= :fec_fin AND ";
            }
            if ($arrFiltro['company'] != "" && $arrFiltro['company'] > 0) {
                $str_search .= "iemp.emp_id  = :emp_id AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
              $str_search .= "soli.uaca_id = :unidad /*is null or ' ' */ AND ";
            }
        }
        $sql = "
                SELECT
                    DISTINCT
                    inte.int_id as id,
                    concat(ifnull(per.per_pri_nombre,''),' ',ifnull(per.per_seg_nombre,'')) as nombres,
                    concat(ifnull(per.per_pri_apellido,''),' ',ifnull(per.per_seg_apellido,'')) as apellidos,
                    ifnull(per.per_cedula,per.per_pasaporte) as DNI,
                    emp.emp_nombre_comercial as empresa,
                    DATE(inte.int_fecha_creacion) as fecha_interes,
                    per.per_id,
                    ifnull((SELECT uaca.uaca_nombre
                        FROM " . $con->dbname . ".solicitud_inscripcion sins
                        INNER JOIN " . $con2->dbname . ".unidad_academica uaca on uaca.uaca_id = sins.uaca_id
                        WHERE int_id = inte.int_id
                        and sins.sins_estado = :estado
                        and sins.sins_estado_logico = :estado
                        ORDER BY sins_fecha_solicitud desc
                        LIMIT 1),'') as unidad,
                        case emp.emp_id
                        when 1 then (select eaca.eaca_nombre from " . $con2->dbname . ".estudio_academico eaca inner join " . $con->dbname . ".solicitud_inscripcion sins on sins.eaca_id = eaca.eaca_id  WHERE int_id = inte.int_id ORDER BY sins_fecha_solicitud desc LIMIT 1)
                        when 2 then (select mes.mest_nombre from " . $con2->dbname . ".modulo_estudio mes inner join " . $con->dbname . ".solicitud_inscripcion sins on sins.mest_id = mes.mest_id WHERE int_id = inte.int_id ORDER BY sins_fecha_solicitud desc LIMIT 1)
                        when 3 then (select mes.mest_nombre from " . $con2->dbname . ".modulo_estudio mes inner join " . $con->dbname . ".solicitud_inscripcion sins on sins.mest_id = mes.mest_id WHERE int_id = inte.int_id ORDER BY sins_fecha_solicitud desc LIMIT 1)
                        else null
                        end as 'carrera',
                    concat(pges.per_pri_nombre, ' ', pges.per_pri_apellido) as Agente
                FROM " . $con->dbname . ".interesado inte
                    join " . $con1->dbname . ".persona as per on inte.per_id=per.per_id
                    join " . $con->dbname . ".interesado_empresa as iemp on iemp.int_id=inte.int_id
                    join " . $con1->dbname . ".empresa as emp on emp.emp_id=iemp.emp_id
                    left join " . $con1->dbname . ".usuario as uges on inte.int_usuario_ingreso=uges.usu_id
                    left join " . $con1->dbname . ".persona as pges on pges.per_id=uges.per_id
                    left join " . $con->dbname . ".solicitud_inscripcion as soli on soli.int_id =inte.int_id
                    left join " . $con2->dbname . ".unidad_academica as uni on uni.uaca_id =soli.uaca_id
                WHERE $str_search
                    inte.int_estado_logico=:estado AND
                    inte.int_estado=:estado AND
                    per.per_estado_logico=:estado AND
                    per.per_estado=:estado AND
                    iemp.iemp_estado_logico=:estado AND
                    iemp.iemp_estado=:estado AND
                    emp.emp_estado_logico=:estado AND
                    emp.emp_estado=:estado
                    order by inte.int_fecha_creacion desc
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $unidad = $arrFiltro["unidad"];
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            $empresa = $arrFiltro["company"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['company'] != "" && $arrFiltro['company'] > 0) {
                $comando->bindParam(":emp_id", $empresa, \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
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
                    'DNI',
                    'nombres',
                    'apellidos',
                    'empresa',
                ],
            ],
        ]);

        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    /**
     * Function consultagruporol
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param     
     * @return  
     */
    public function consultagruporol($per_id) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT                 
                  usug.grol_id as grol_id
                FROM " . $con->dbname . ".usuario usu 
                  INNER JOIN " . $con->dbname . ".usua_grol_eper usug ON usug.usu_id = usu.usu_id
                WHERE 
                  usu.per_id = :per_id AND
                  usu.usu_estado_logico = :estado AND
                  usu.usu_estado = :estado AND
                  usug.ugep_estado_logico = :estado AND
                  usug.ugep_estado = :estado;
               ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function modificaGruporol (modifica el grupo rol id)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @property integer $per_id       
     * @return  
     */
    public function modificaGruporol($per_id, $grol_id) {
        $con = \Yii::$app->db;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $estado = 1;
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".usua_grol_eper ugrol INNER JOIN " . $con->dbname . ".usuario usu 
		             ON ugrol.usu_id = usu.usu_id
                      SET ugrol.grol_id = :grol_id
                      WHERE usu.per_id = :per_id AND 
                            usu.usu_estado = :estado AND
                            usu.usu_estado_logico = :estado AND
                            ugrol.ugep_estado = :estado AND
                            ugrol.ugep_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":grol_id", $grol_id, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function modificarInfoAcaInteresado
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function modificarInfoAcaInteresado($int_id, $pai_id, $pro_id, $can_id, $tiac_id, $tnes_id, $iaca_institucion, $iaca_titulo, $iaca_anio_grado) {
        $con = \Yii::$app->db_captacion;
        $iaca_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_academico 		       
                      SET 
                        pai_id = :pai_id,    
                        pro_id = :pro_id,
                        can_id = :can_id,
                        tiac_id = :tiac_id,
                        tnes_id = :tnes_id, 
                        iaca_institucion = :iaca_institucion,
                        iaca_titulo = :iaca_titulo,
                        iaca_anio_grado = :iaca_anio_grado,
                        iaca_fecha_modificacion = :iaca_fecha_modificacion
                      WHERE 
                        int_id = :int_id AND
                        iaca_estado = :estado AND
                        iaca_estado_logico = :estado");
            $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
            $comando->bindParam(":pai_id", $pai_id, \PDO::PARAM_INT);
            $comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
            $comando->bindParam(":can_id", $can_id, \PDO::PARAM_INT);
            $comando->bindParam(":tiac_id", $tiac_id, \PDO::PARAM_INT);
            $comando->bindParam(":tnes_id", $tnes_id, \PDO::PARAM_INT);
            $comando->bindParam(":iaca_institucion", $iaca_institucion, \PDO::PARAM_STR);
            $comando->bindParam(":iaca_titulo", $iaca_titulo, \PDO::PARAM_STR);
            $comando->bindParam(":iaca_anio_grado", $iaca_anio_grado, \PDO::PARAM_STR);
            $comando->bindParam(":iaca_fecha_modificacion", $iaca_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function modificarInfoFamInteresado
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function modificarInfoFamInteresado($int_id, $nins_padre, $nins_madre, $ifam_miembro, $ifam_salario) {
        $con = \Yii::$app->db_captacion;
        $ifam_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".informacion_familia 		       
                      SET 
                        nins_padre = :nins_padre,    
                        nins_madre = :nins_madre,
                        ifam_miembro = :ifam_miembro,
                        ifam_salario = :ifam_salario,                        
                        ifam_fecha_modificacion = :ifam_fecha_modificacion
                      WHERE 
                        int_id = :int_id AND
                        ifam_estado = :estado AND
                        ifam_estado_logico = :estado");
            $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
            $comando->bindParam(":nins_padre", $nins_padre, \PDO::PARAM_INT);
            $comando->bindParam(":nins_madre", $nins_madre, \PDO::PARAM_INT);
            $comando->bindParam(":ifam_miembro", $ifam_miembro, \PDO::PARAM_STR);
            $comando->bindParam(":ifam_salario", $ifam_salario, \PDO::PARAM_STR);
            $comando->bindParam(":ifam_fecha_modificacion", $ifam_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function modificarInfoDisInteresado
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function modificarInfoDisInteresado($int_id, $tdis_id, $idis_discapacidad, $idis_porcentaje, $idis_archivo) {
        $con = \Yii::$app->db_captacion;
        $idis_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_discapacidad		       
                      SET 
                        tdis_id = :tdis_id,    
                        idis_discapacidad = :idis_discapacidad,
                        idis_porcentaje = :idis_porcentaje,
                        idis_archivo = :idis_archivo,                        
                        idis_fecha_modificacion = :idis_fecha_modificacion
                      WHERE 
                        int_id = :int_id AND
                        idis_estado = :estado AND
                        idis_estado_logico = :estado");
            $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
            $comando->bindParam(":tdis_id", $tdis_id, \PDO::PARAM_INT);
            $comando->bindParam(":idis_discapacidad", $idis_discapacidad, \PDO::PARAM_STR);
            $comando->bindParam(":idis_porcentaje", $idis_porcentaje, \PDO::PARAM_STR);
            $comando->bindParam(":idis_archivo", $idis_archivo, \PDO::PARAM_STR);
            $comando->bindParam(":idis_fecha_modificacion", $idis_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function modificarInfoEnfInteresado
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function modificarInfoEnfInteresado($int_id, $ienf_enfermedad, $ienf_tipoenfermedad, $ienf_archivo) {
        $con = \Yii::$app->db_captacion;
        $ienf_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_enfermedad		       
                      SET 
                        ienf_enfermedad = :ienf_enfermedad,    
                        ienf_tipoenfermedad = :ienf_tipoenfermedad,
                        ienf_archivo = :ienf_archivo,                        
                        ienf_fecha_modificacion = :ienf_fecha_modificacion
                      WHERE 
                        int_id = :int_id AND
                        ienf_estado = :estado AND
                        ienf_estado_logico = :estado");
            $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
            $comando->bindParam(":ienf_enfermedad", $ienf_enfermedad, \PDO::PARAM_STR);
            $comando->bindParam(":ienf_tipoenfermedad", $ienf_tipoenfermedad, \PDO::PARAM_STR);
            $comando->bindParam(":ienf_archivo", $ienf_archivo, \PDO::PARAM_STR);
            $comando->bindParam(":ienf_fecha_modificacion", $ienf_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function modificarInfoEnfFamiliadisc
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function modificarInfoEnfFamiliadisc($int_id, $tpar_id, $tdis_id, $ifdi_discapacidad, $ifdi_porcentaje, $ifdi_archivo) {
        $con = \Yii::$app->db_captacion;
        $ifdi_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_familia_discapacidad		       
                      SET 
                        tpar_id = :tpar_id,    
                        tdis_id = :tdis_id,
                        ifdi_discapacidad = :ifdi_discapacidad,
                        ifdi_porcentaje = :ifdi_porcentaje,
                        ifdi_archivo = :ifdi_archivo,                        
                        ifdi_fecha_modificacion = :ifdi_fecha_modificacion
                      WHERE 
                        int_id = :int_id AND
                        ifdi_estado = :estado AND
                        ifdi_estado_logico = :estado");
            $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
            $comando->bindParam(":tpar_id", $tpar_id, \PDO::PARAM_INT);
            $comando->bindParam(":tdis_id", $tdis_id, \PDO::PARAM_INT);
            $comando->bindParam(":ifdi_discapacidad", $ifdi_discapacidad, \PDO::PARAM_STR);
            $comando->bindParam(":ifdi_porcentaje", $ifdi_porcentaje, \PDO::PARAM_STR);
            $comando->bindParam(":ifdi_archivo", $ifdi_archivo, \PDO::PARAM_STR);
            $comando->bindParam(":ifdi_fecha_modificacion", $ifdi_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function modificarInfoEnfFamilia
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function modificarInfoEnfFamilia($int_id, $tpar_id, $ifen_tipoenfermedad, $ifen_enfermedad, $ifen_archivo) {
        $con = \Yii::$app->db_captacion;
        $ifen_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_familia_enfermedad		       
                      SET 
                        tpar_id = :tpar_id,    
                        ifen_tipoenfermedad = :ifen_tipoenfermedad,
                        ifen_enfermedad = :ifen_enfermedad,                        
                        ifen_archivo = :ifen_archivo,                        
                        ifen_fecha_modificacion = :ifen_fecha_modificacion
                      WHERE 
                        int_id = :int_id AND
                        ifdi_estado = :estado AND
                        ifdi_estado_logico = :estado");
            $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
            $comando->bindParam(":tpar_id", $tpar_id, \PDO::PARAM_INT);
            $comando->bindParam(":ifen_tipoenfermedad", $ifen_tipoenfermedad, \PDO::PARAM_INT);
            $comando->bindParam(":ifen_enfermedad", $ifen_enfermedad, \PDO::PARAM_STR);
            $comando->bindParam(":ifen_archivo", $ifen_archivo, \PDO::PARAM_STR);
            $comando->bindParam(":ifen_fecha_modificacion", $ifen_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function inactivarInfoDisInteresado
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function inactivarInfoDisInteresado($int_id) {
        $con = \Yii::$app->db_captacion;
        $idis_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;
        $estado_inactiva = 0;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_discapacidad            
                      SET 
                        idis_estado = :estado_inactiva,
                        idis_fecha_modificacion = :idis_fecha_modificacion
                      WHERE 
                        int_id = :int_id AND
                        idis_estado = :estado AND
                        idis_estado_logico = :estado");
            $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
            $comando->bindParam(":idis_fecha_modificacion", $idis_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":estado_inactiva", $estado_inactiva, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function inactivarInfoEnfInteresado
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function inactivarInfoEnfInteresado($int_id) {
        $con = \Yii::$app->db_captacion;
        $ienf_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;
        $estado_inactiva = 0;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_enfermedad              
                      SET 
                        ienf_estado = :estado_inactiva,
                        ienf_fecha_modificacion = :ienf_fecha_modificacion
                      WHERE 
                        int_id = :int_id AND
                        ienf_estado = :estado AND
                        ienf_estado_logico = :estado");
            $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
            $comando->bindParam(":ienf_fecha_modificacion", $ienf_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":estado_inactiva", $estado_inactiva, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function inactivarInfoEnfFamiliadisc
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function inactivarInfoEnfFamiliadisc($int_id) {
        $con = \Yii::$app->db_captacion;
        $ifdi_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;
        $estado_inactiva = 0;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_familia_discapacidad            
                      SET 
                        ifdi_estado = :estado_inactiva,
                        ifdi_fecha_modificacion = :ifdi_fecha_modificacion
                      WHERE 
                        int_id = :int_id AND
                        ifdi_estado = :estado AND
                        ifdi_estado_logico = :estado");
            $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
            $comando->bindParam(":ifdi_fecha_modificacion", $ifdi_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":estado_inactiva", $estado_inactiva, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function inactivarInfoEnfFamilia
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function inactivarInfoEnfFamilia($int_id) {
        $con = \Yii::$app->db_captacion;
        $ifen_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;
        $estado_inactiva = 0;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_familia_enfermedad              
                      SET 
                        ifdi_estado = :estado_inactiva,        
                        ifen_fecha_modificacion = :ifen_fecha_modificacion
                      WHERE 
                        int_id = :int_id AND
                        ifdi_estado = :estado AND
                        ifdi_estado_logico = :estado");
            $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
            $comando->bindParam(":ifen_fecha_modificacion", $ifen_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":estado_inactiva", $estado_inactiva, \PDO::PARAM_STR);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultaGruporol (consulta el grupo rol id)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @property integer $per_id       
     * @return  
     */
    public function consultaGruporolinteresado($per_id) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT ugrol.grol_id 
                FROM " . $con->dbname . ".usua_grol_eper ugrol INNER JOIN " . $con->dbname . ".usuario usu 
                        ON ugrol.usu_id = usu.usu_id
                 WHERE usu.per_id = :per_id AND 
                       usu.usu_estado = :estado AND
                       usu.usu_estado_logico = :estado AND
                       ugrol.ugep_estado = :estado AND
                       ugrol.ugep_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":grol_id", $grol_id, \PDO::PARAM_STR);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function enviarCorreoBienvenida($email_info) {
        $tituloMensaje = Yii::t("interesado", "UTEG - Registration");
        $asunto = Yii::t("interesado", "UTEG - Registration Online");
        $body = Utilities::getMailMessage("BienvenidaADContacto", array(
                    "[[nombres]]" => $email_info['nombres'],
                    "[[apellidos]]" => $email_info['apellidos'],
                    "[[correo]]" => $email_info['correo'],
                    "[[telefono]]" => $email_info['telefono'],
                    "[[webmail]]" => Yii::$app->params["adminEmail"],
                    "[[identificacion]]" => $email_info['identificacion'],
                    "[[link_asgard]]" => $email_info["link_asgard"],
                        ), Yii::$app->language);
        Utilities::sendEmail($tituloMensaje, Yii::$app->params["admisiones"], // a quien se envia el correo
                [$email_info['correo'] => $email_info['nombres'] . " " . $email_info['apellidos']], // quien envia el correo
                $asunto, $body);
    }

    /**
     * Function consultapermisoopcion.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param     
     * @return  
     */
    public function consultapermisoopcion($per_id, $opcion) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT                   
                    gogr.gmod_id as gmod_id
                FROM " . $con->dbname . ".usuario usu 
                    INNER JOIN " . $con->dbname . ".usua_grol_eper ug ON ug.usu_id = usu.usu_id
                    INNER JOIN " . $con->dbname . ".grup_rol gr ON gr.grol_id = ug.grol_id
                    INNER JOIN " . $con->dbname . ".grup_obmo go ON go.gru_id = gr.gru_id
                    INNER JOIN " . $con->dbname . ".grup_obmo_grup_rol gogr ON (gogr.gmod_id = go.gmod_id and gogr.grol_id = gr.grol_id)	 
                WHERE 
                    usu.per_id = :per_id AND
                    go.omod_id = :opcion AND
                    usu.usu_estado_logico = :estado AND
                    usu.usu_estado = :estado AND
                    gr.grol_estado_logico = :estado AND
                    gr.grol_estado = :estado AND
                    ug.ugep_estado_logico = :estado AND
                    ug.ugep_estado = :estado AND
                    go.gmod_estado = :estado AND
                    go.gmod_estado_logico = :estado AND
                    gogr.gogr_estado = :estado AND
                    gogr.gogr_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":opcion", $opcion, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarReportAspirantes.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarReportAspirantes($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_academico;
        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_pasaporte like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "inte.int_fecha_creacion >= :fec_ini AND ";
                $str_search .= "inte.int_fecha_creacion <= :fec_fin AND ";
            }
            if ($arrFiltro['company'] != "" && $arrFiltro['company'] > 0) {
                $str_search .= "iemp.emp_id  = :emp_id AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "soli.uaca_id = :unidad /*is null or ' ' */ AND ";
            }
        }
        $sql = "
                SELECT
                    DISTINCT
                    ifnull(per.per_cedula,per.per_pasaporte) as DNI,
                    DATE(inte.int_fecha_creacion) as fecha_interes,
                    concat(ifnull(per.per_pri_nombre,''),' ',ifnull(per.per_seg_nombre,'')) as nombres,
                    concat(ifnull(per.per_pri_apellido,''),' ',ifnull(per.per_seg_apellido,'')) as apellidos,
                    concat(pges.per_pri_nombre, ' ', pges.per_pri_apellido) as agente,
                    emp.emp_nombre_comercial as empresa,
                    ifnull((SELECT uaca.uaca_nombre
                            FROM " . $con->dbname . ".solicitud_inscripcion sins
                            INNER JOIN " . $con2->dbname . ".unidad_academica uaca on uaca.uaca_id = sins.uaca_id
                            WHERE int_id = inte.int_id
                            and sins.sins_estado = :estado
                            and sins.sins_estado_logico = :estado
                            ORDER BY sins_fecha_solicitud desc
                            LIMIT 1),'') as unidad,
                    case emp.emp_id
                    when 1 then (select eaca.eaca_nombre from " . $con2->dbname . ".estudio_academico eaca inner join " . $con->dbname . ".solicitud_inscripcion sins on sins.eaca_id = eaca.eaca_id  WHERE int_id = inte.int_id ORDER BY sins_fecha_solicitud desc LIMIT 1)
                    when 2 then (select mes.mest_nombre from " . $con2->dbname . ".modulo_estudio mes inner join " . $con->dbname . ".solicitud_inscripcion sins on sins.mest_id = mes.mest_id WHERE int_id = inte.int_id ORDER BY sins_fecha_solicitud desc LIMIT 1)
                    when 3 then (select mes.mest_nombre from " . $con2->dbname . ".modulo_estudio mes inner join " . $con->dbname . ".solicitud_inscripcion sins on sins.mest_id = mes.mest_id WHERE int_id = inte.int_id ORDER BY sins_fecha_solicitud desc LIMIT 1)
                    else null
                    end as 'carrera'
                FROM    " . $con->dbname . ".interesado inte
                        join " . $con1->dbname . ".persona as per on inte.per_id=per.per_id
                        join " . $con->dbname . ".interesado_empresa as iemp on iemp.int_id=inte.int_id
                        join " . $con1->dbname . ".empresa as emp on emp.emp_id=iemp.emp_id
                        left join " . $con1->dbname . ".usuario as uges on inte.int_usuario_ingreso=uges.usu_id
                        left join " . $con1->dbname . ".persona as pges on pges.per_id=uges.per_id
                        left join " . $con->dbname . ".solicitud_inscripcion as soli on soli.int_id =inte.int_id
                        left join " . $con2->dbname . ".unidad_academica as uni on uni.uaca_id =soli.uaca_id
                WHERE $str_search
                    inte.int_estado_logico=:estado AND
                    inte.int_estado=:estado AND
                    per.per_estado_logico=:estado AND
                    per.per_estado=:estado AND
                    iemp.iemp_estado_logico=:estado AND
                    iemp.iemp_estado=:estado AND
                    emp.emp_estado_logico=:estado AND
                    emp.emp_estado=:estado
                ORDER BY inte.int_fecha_creacion DESC
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $empresa = $arrFiltro["company"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $unidad = $arrFiltro["unidad"];
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['company'] != "" && $arrFiltro['company'] > 0) {
                $comando->bindParam(":emp_id", $empresa, \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
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
                    'DNI',
                    'nombres',
                    'apellidos',
                    'empresa',
                ],
            ],
        ]);

        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

}
