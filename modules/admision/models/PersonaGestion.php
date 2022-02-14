<?php

namespace app\modules\admision\models;

use app\modules\admision\models\Oportunidad;
use app\models\GrupRol;
use app\models\Persona;
use app\modules\admision\models\PersonaGestion;
use app\modules\admision\models\EstadoOportunidad;
use app\modules\admision\models\EstadoContacto;
use app\modules\admision\models\TipoOportunidadVenta;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\financiero\models\Secuencias;
use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "persona_gestion".
 *
 * @property int $pges_id
 * @property string $pges_codigo
 * @property int $tper_id
 * @property int $cser_id
 * @property int $car_id
 * @property int $etn_id
 * @property int $eciv_id
 * @property int $pai_id_nacimiento
 * @property int $pro_id_nacimiento
 * @property int $can_id_nacimiento
 * @property int $tsan_id
 * @property int $pai_id_domicilio
 * @property int $pro_id_domicilio
 * @property int $can_id_domicilio
 * @property int $pai_id_trabajo
 * @property int $pro_id_trabajo
 * @property int $can_id_trabajo
 * @property int $econ_id
 * @property int $ccan_id
 * @property string $pges_pri_nombre
 * @property string $pges_seg_nombre
 * @property string $pges_pri_apellido
 * @property string $pges_seg_apellido
 * @property string $pges_razon_social
 * @property string $pges_cedula
 * @property string $pges_ruc
 * @property string $pges_pasaporte
 * @property string $pges_contacto_empresa
 * @property string $pges_num_empleados
 * @property string $pges_telefono_empresa
 * @property string $pges_direccion_empresa
 * @property string $pges_cargo
 * @property string $pges_genero
 * @property string $pges_nacionalidad
 * @property string $pges_nac_ecuatoriano
 * @property string $pges_fecha_nacimiento
 * @property string $pges_celular
 * @property string $pges_correo
 * @property string $pges_foto
 * @property string $pges_domicilio_sector
 * @property string $pges_domicilio_cpri
 * @property string $pges_domicilio_csec
 * @property string $pges_domicilio_num
 * @property string $pges_domicilio_ref
 * @property string $pges_domicilio_telefono
 * @property string $pges_domicilio_celular2
 * @property string $pges_trabajo_nombre
 * @property string $pges_trabajo_direccion
 * @property string $pges_trabajo_telefono
 * @property string $pges_trabajo_ext
 * @property string $pges_estado_contacto
 * @property int $pges_usuario_ingreso
 * @property int $pges_usuario_modifica
 * @property string $pges_estado
 * @property string $pges_fecha_creacion
 * @property string $pges_fecha_modificacion
 * @property string $pges_estado_logico
 *
 * @property Oportunidad[] $oportunidads
 * @property ConocimientoServicio $cser
 * @property EstadoContacto $econ
 * @property ConocimientoCanal $ccan
 * @property PersonaGestionContacto[] $personaGestionContactos
 */
class PersonaGestion extends \app\modules\admision\components\CActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'persona_gestion';
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
            [['tper_id', 'econ_id', 'ccan_id', 'pges_estado_contacto', 'pges_usuario_ingreso', 'pges_estado', 'pges_estado_logico'], 'required'],
            [['tper_id', 'cser_id', 'car_id', 'etn_id', 'eciv_id', 'pai_id_nacimiento', 'pro_id_nacimiento', 'can_id_nacimiento', 'tsan_id', 'pai_id_domicilio', 'pro_id_domicilio', 'can_id_domicilio', 'pai_id_trabajo', 'pro_id_trabajo', 'can_id_trabajo', 'econ_id', 'ccan_id', 'pges_usuario_ingreso', 'pges_usuario_modifica'], 'integer'],
            [['pges_fecha_nacimiento', 'pges_fecha_creacion', 'pges_fecha_modificacion'], 'safe'],
            [['pges_codigo'], 'string', 'max' => 7],
            [['pges_pri_nombre', 'pges_seg_nombre', 'pges_pri_apellido', 'pges_seg_apellido', 'pges_nacionalidad', 'pges_correo', 'pges_domicilio_sector', 'pges_trabajo_nombre'], 'string', 'max' => 250],
            [['pges_razon_social', 'pges_contacto_empresa', 'pges_direccion_empresa', 'pges_foto', 'pges_domicilio_cpri', 'pges_domicilio_csec', 'pges_domicilio_ref', 'pges_trabajo_direccion'], 'string', 'max' => 500],
            [['pges_cedula', 'pges_ruc'], 'string', 'max' => 15],
            [['pges_pasaporte', 'pges_celular', 'pges_domicilio_telefono', 'pges_domicilio_celular2', 'pges_trabajo_telefono', 'pges_trabajo_ext'], 'string', 'max' => 50],
            [['pges_num_empleados'], 'string', 'max' => 5],
            [['pges_telefono_empresa'], 'string', 'max' => 10],
            [['pges_cargo', 'pges_domicilio_num'], 'string', 'max' => 100],
            [['pges_genero', 'pges_nac_ecuatoriano', 'pges_estado_contacto', 'pges_estado', 'pges_estado_logico'], 'string', 'max' => 1],
            [['econ_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstadoContacto::className(), 'targetAttribute' => ['econ_id' => 'econ_id']],
            [['ccan_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConocimientoCanal::className(), 'targetAttribute' => ['ccan_id' => 'ccan_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pges_id' => 'Pges ID',
            'pges_codigo' => 'Pges Codigo',
            'tper_id' => 'Tper ID',
            'cser_id' => 'Cser ID',
            'car_id' => 'Car ID',
            'etn_id' => 'Etn ID',
            'eciv_id' => 'Eciv ID',
            'pai_id_nacimiento' => 'Pai Id Nacimiento',
            'pro_id_nacimiento' => 'Pro Id Nacimiento',
            'can_id_nacimiento' => 'Can Id Nacimiento',
            'tsan_id' => 'Tsan ID',
            'pai_id_domicilio' => 'Pai Id Domicilio',
            'pro_id_domicilio' => 'Pro Id Domicilio',
            'can_id_domicilio' => 'Can Id Domicilio',
            'pai_id_trabajo' => 'Pai Id Trabajo',
            'pro_id_trabajo' => 'Pro Id Trabajo',
            'can_id_trabajo' => 'Can Id Trabajo',
            'econ_id' => 'Econ ID',
            'ccan_id' => 'Ccan ID',
            'pges_pri_nombre' => 'Pges Pri Nombre',
            'pges_seg_nombre' => 'Pges Seg Nombre',
            'pges_pri_apellido' => 'Pges Pri Apellido',
            'pges_seg_apellido' => 'Pges Seg Apellido',
            'pges_razon_social' => 'Pges Razon Social',
            'pges_cedula' => 'Pges Cedula',
            'pges_ruc' => 'Pges Ruc',
            'pges_pasaporte' => 'Pges Pasaporte',
            'pges_contacto_empresa' => 'Pges Contacto Empresa',
            'pges_num_empleados' => 'Pges Num Empleados',
            'pges_telefono_empresa' => 'Pges Telefono Empresa',
            'pges_direccion_empresa' => 'Pges Direccion Empresa',
            'pges_cargo' => 'Pges Cargo',
            'pges_genero' => 'Pges Genero',
            'pges_nacionalidad' => 'Pges Nacionalidad',
            'pges_nac_ecuatoriano' => 'Pges Nac Ecuatoriano',
            'pges_fecha_nacimiento' => 'Pges Fecha Nacimiento',
            'pges_celular' => 'Pges Celular',
            'pges_correo' => 'Pges Correo',
            'pges_foto' => 'Pges Foto',
            'pges_domicilio_sector' => 'Pges Domicilio Sector',
            'pges_domicilio_cpri' => 'Pges Domicilio Cpri',
            'pges_domicilio_csec' => 'Pges Domicilio Csec',
            'pges_domicilio_num' => 'Pges Domicilio Num',
            'pges_domicilio_ref' => 'Pges Domicilio Ref',
            'pges_domicilio_telefono' => 'Pges Domicilio Telefono',
            'pges_domicilio_celular2' => 'Pges Domicilio Celular2',
            'pges_trabajo_nombre' => 'Pges Trabajo Nombre',
            'pges_trabajo_direccion' => 'Pges Trabajo Direccion',
            'pges_trabajo_telefono' => 'Pges Trabajo Telefono',
            'pges_trabajo_ext' => 'Pges Trabajo Ext',
            'pges_estado_contacto' => 'Pges Estado Contacto',
            'pges_usuario_ingreso' => 'Pges Usuario Ingreso',
            'pges_usuario_modifica' => 'Pges Usuario Modifica',
            'pges_estado' => 'Pges Estado',
            'pges_fecha_creacion' => 'Pges Fecha Creacion',
            'pges_fecha_modificacion' => 'Pges Fecha Modificacion',
            'pges_estado_logico' => 'Pges Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOportunidads() {
        return $this->hasMany(Oportunidad::className(), ['pges_id' => 'pges_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcon() {
        return $this->hasOne(EstadoContacto::className(), ['econ_id' => 'econ_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCcan() {
        return $this->hasOne(ConocimientoCanal::className(), ['ccan_id' => 'ccan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaGestionContactos() {
        return $this->hasMany(PersonaGestionContacto::className(), ['pges_id' => 'pges_id']);
    }

    /**
     * Function insertarPersonaGest
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @property integer $userid
     * @return
     */
    public function actualizarPersonaGestion($con, $id, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $params_sql = "";
        for ($i = 0; $i < (count($parameters) - 1); $i++) {
            if (isset($parameters[$i])) {
                $params_sql .= $keys[$i] . " = '" . $parameters[$i] . "',";
            }
        }
        $params_sql .= $keys[count($parameters) - 1] . " = '" . $parameters[count($parameters) - 1] . "'";
        try {
            $sql = "UPDATE " . $con->dbname . '.' . $name_table .
                    " SET $params_sql" .
                    " WHERE pges_id=$id";
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            if ($trans !== null) {
                return true;
            } else {
                $transaction->commit();
                return true;
            }
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function insertarPersonaGest
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @property integer $userid
     * @return
     */
    public function insertarPersonaGest($con, $parameters, $keys, $name_table) {
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
            if ($trans !== null) {
                $trans->commit();
                return $idtable;
            }
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function insertarPersonaGestion grabar la inserción personas.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarPersonaGestion($pges_codigo, $tper_id, $cser_id, $car_id, $pges_pri_nombre, $pges_seg_nombre, $pges_pri_apellido, $pges_seg_apellido, $pges_cedula, $pges_ruc, $pges_pasaporte, $etn_id, $eciv_id, $pges_genero, $pges_nacionalidad, $pai_id_nacimiento, $pro_id_nacimiento, $can_id_nacimiento, $pges_nac_ecuatoriano, $pges_fecha_nacimiento, $pges_celular, $pges_correo, $pges_foto, $tsan_id, $pges_domicilio_sector, $pges_domicilio_cpri, $pges_domicilio_csec, $pges_domicilio_num, $pges_domicilio_ref, $pges_domicilio_telefono, $pges_domicilio_celular2, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $pges_trabajo_nombre, $pges_trabajo_direccion, $pges_trabajo_telefono, $pges_trabajo_ext, $pges_id_trabajo, $pro_id_trabajo, $can_id_trabajo, $econ_id, $ccan_id, $pges_razon_social, $pges_contacto_empresa, $pges_num_empleados, $telefono_empresa, $direccion, $cargo, $pges_usuario_ingreso) {
        $con = \Yii::$app->db_crm;

        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "pges_estado";
        $bdet_sql = "1";

        $param_sql .= ", pges_estado_logico";
        $bdet_sql .= ", 1";

        $param_sql .= ", pges_estado_contacto";
        $bdet_sql .= ", 1";

        if (isset($pges_codigo)) {
            $param_sql .= ", pges_codigo";
            $bdet_sql .= ", :pges_codigo";
        }
        if (isset($tper_id)) {
            $param_sql .= ", tper_id";
            $bdet_sql .= ", :tper_id";
        }
        if (isset($cser_id)) {
            $param_sql .= ", cser_id";
            $bdet_sql .= ", :cser_id";
        }
        if (isset($car_id)) {
            $param_sql .= ", car_id";
            $bdet_sql .= ", :car_id";
        }
        if (isset($pges_pri_nombre)) {
            $param_sql .= ", pges_pri_nombre";
            $bdet_sql .= ", :pges_pri_nombre";
        }
        if (isset($pges_seg_nombre)) {
            $param_sql .= ", pges_seg_nombre";
            $bdet_sql .= ", :pges_seg_nombre";
        }
        if (isset($pges_pri_apellido)) {
            $param_sql .= ", pges_pri_apellido";
            $bdet_sql .= ", :pges_pri_apellido";
        }
        if (isset($pges_seg_apellido)) {
            $param_sql .= ", pges_seg_apellido";
            $bdet_sql .= ", :pges_seg_apellido";
        }
        if (isset($pges_cedula)) {
            $param_sql .= ", pges_cedula";
            $bdet_sql .= ", :pges_cedula";
        }
        if (isset($pges_ruc)) {
            $param_sql .= ", pges_ruc";
            $bdet_sql .= ", :pges_ruc";
        }
        if (isset($pges_pasaporte)) {
            $param_sql .= ", pges_pasaporte";
            $bdet_sql .= ", :pges_pasaporte";
        }
        if (isset($etn_id)) {
            $param_sql .= ", etn_id";
            $bdet_sql .= ", :etn_id";
        }
        if (isset($eciv_id)) {
            $param_sql .= ", eciv_id";
            $bdet_sql .= ", :eciv_id";
        }
        if (isset($pges_genero)) {
            $param_sql .= ", pges_genero";
            $bdet_sql .= ", :pges_genero";
        }
        if (isset($pges_nacionalidad)) {
            $param_sql .= ", pges_nacionalidad";
            $bdet_sql .= ", :pges_nacionalidad";
        }
        if (isset($pai_id_nacimiento)) {
            $param_sql .= ", pai_id_nacimiento";
            $bdet_sql .= ", :pai_id_nacimiento";
        }
        if (isset($pro_id_nacimiento)) {
            $param_sql .= ", pro_id_nacimiento";
            $bdet_sql .= ", :pro_id_nacimiento";
        }
        if (isset($can_id_nacimiento)) {
            $param_sql .= ", can_id_nacimiento";
            $bdet_sql .= ", :can_id_nacimiento";
        }
        if (isset($pges_nac_ecuatoriano)) {
            $param_sql .= ", pges_nac_ecuatoriano";
            $bdet_sql .= ", :pges_nac_ecuatoriano";
        }
        if (isset($pges_fecha_nacimiento)) {
            $param_sql .= ", pges_fecha_nacimiento";
            $bdet_sql .= ", :pges_fecha_nacimiento";
        }
        if (isset($pges_celular)) {
            $param_sql .= ", pges_celular";
            $bdet_sql .= ", :pges_celular";
        }
        if (isset($pges_correo)) {
            $param_sql .= ", pges_correo";
            $bdet_sql .= ", :pges_correo";
        }
        if (isset($pges_foto)) {
            $param_sql .= ", pges_foto";
            $bdet_sql .= ", :pges_foto";
        }
        if (isset($tsan_id)) {
            $param_sql .= ", tsan_id";
            $bdet_sql .= ", :tsan_id";
        }
        if (isset($pges_domicilio_sector)) {
            $param_sql .= ", pges_domicilio_sector";
            $bdet_sql .= ", :pges_domicilio_sector";
        }
        if (isset($pges_domicilio_cpri)) {
            $param_sql .= ", pges_domicilio_cpri";
            $bdet_sql .= ", :pges_domicilio_cpri";
        }
        if (isset($pges_domicilio_csec)) {
            $param_sql .= ", pges_domicilio_csec";
            $bdet_sql .= ", :pges_domicilio_csec";
        }
        if (isset($pges_domicilio_num)) {
            $param_sql .= ", pges_domicilio_num";
            $bdet_sql .= ", :pges_domicilio_num";
        }
        if (isset($pges_domicilio_ref)) {
            $param_sql .= ", pges_domicilio_ref";
            $bdet_sql .= ", :pges_domicilio_ref";
        }
        if (isset($pges_domicilio_telefono)) {
            $param_sql .= ", pges_domicilio_telefono";
            $bdet_sql .= ", :pges_domicilio_telefono";
        }
        if (isset($pges_domicilio_celular2)) {
            $param_sql .= ", pges_domicilio_celular2";
            $bdet_sql .= ", :pges_domicilio_celular2";
        }
        if (isset($pai_id_domicilio)) {
            $param_sql .= ", pai_id_domicilio";
            $bdet_sql .= ", :pai_id_domicilio";
        }
        if (isset($pro_id_domicilio)) {
            $param_sql .= ", pro_id_domicilio";
            $bdet_sql .= ", :pro_id_domicilio";
        }
        if (isset($can_id_domicilio)) {
            $param_sql .= ", can_id_domicilio";
            $bdet_sql .= ", :can_id_domicilio";
        }
        if (isset($pges_trabajo_nombre)) {
            $param_sql .= ", pges_trabajo_nombre";
            $bdet_sql .= ", :pges_trabajo_nombre";
        }
        if (isset($pges_trabajo_direccion)) {
            $param_sql .= ", pges_trabajo_direccion";
            $bdet_sql .= ", :pges_trabajo_direccion";
        }
        if (isset($pges_trabajo_telefono)) {
            $param_sql .= ", pges_trabajo_telefono";
            $bdet_sql .= ", :pges_trabajo_telefono";
        }
        if (isset($pges_trabajo_ext)) {
            $param_sql .= ", pges_trabajo_ext";
            $bdet_sql .= ", :pges_trabajo_ext";
        }
        if (isset($pges_id_trabajo)) {
            $param_sql .= ", pges_id_trabajo";
            $bdet_sql .= ", :pges_id_trabajo";
        }
        if (isset($pro_id_trabajo)) {
            $param_sql .= ", pro_id_trabajo";
            $bdet_sql .= ", :pro_id_trabajo";
        }
        if (isset($can_id_trabajo)) {
            $param_sql .= ", can_id_trabajo";
            $bdet_sql .= ", :can_id_trabajo";
        }
        if (isset($econ_id)) {
            $param_sql .= ", econ_id";
            $bdet_sql .= ", :econ_id";
        }
        if (isset($ccan_id)) {
            $param_sql .= ", ccan_id";
            $bdet_sql .= ", :ccan_id";
        }
        if (isset($pges_razon_social)) {
            $param_sql .= ", pges_razon_social";
            $bdet_sql .= ", :pges_razon_social";
        }
        if (isset($pges_contacto_empresa)) {
            $param_sql .= ", pges_contacto_empresa";
            $bdet_sql .= ", :pges_contacto_empresa";
        }
        if (isset($pges_num_empleados)) {
            $param_sql .= ", pges_num_empleados";
            $bdet_sql .= ", :pges_num_empleados";
        }
        if (isset($telefono_empresa)) {
            $param_sql .= ", pges_telefono_empresa";
            $bdet_sql .= ", :pges_telefono_empresa";
        }
        if (isset($direccion)) {
            $param_sql .= ", pges_direccion_empresa";
            $bdet_sql .= ", :pges_direccion_empresa";
        }
        if (isset($cargo)) {
            $param_sql .= ", pges_cargo";
            $bdet_sql .= ", :pges_cargo";
        }
        if (isset($pges_usuario_ingreso)) {
            $param_sql .= ", pges_usuario_ingreso";
            $bdet_sql .= ", :pges_usuario_ingreso";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".persona_gestion ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);
            if (isset($pges_codigo)) {
                $comando->bindParam(':pges_codigo', $pges_codigo, \PDO::PARAM_STR);
            }
            if (isset($tper_id)) {
                $comando->bindParam(':tper_id', $tper_id, \PDO::PARAM_INT);
            }
            if (isset($cser_id)) {
                $comando->bindParam(':cser_id', $cser_id, \PDO::PARAM_INT);
            }
            if (isset($car_id)) {
                $comando->bindParam(':car_id', $car_id, \PDO::PARAM_INT);
            }
            if (isset($pges_pri_nombre)) {
                $comando->bindParam(':pges_pri_nombre', $pges_pri_nombre, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_seg_nombre)))) {
                $comando->bindParam(':pges_seg_nombre', $pges_seg_nombre, \PDO::PARAM_STR);
            }
            if (isset($pges_pri_apellido)) {
                $comando->bindParam(':pges_pri_apellido', $pges_pri_apellido, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_seg_apellido)))) {
                $comando->bindParam(':pges_seg_apellido', $pges_seg_apellido, \PDO::PARAM_STR);
            }
            if (isset($pges_cedula)) {
                $comando->bindParam(':pges_cedula', $pges_cedula, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_ruc)))) {
                $comando->bindParam(':pges_ruc', $pges_ruc, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_pasaporte)))) {
                $comando->bindParam(':pges_pasaporte', $pges_pasaporte, \PDO::PARAM_STR);
            }
            if (!empty((isset($etn_id)))) {
                $comando->bindParam(':etn_id', $etn_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($eciv_id)))) {
                $comando->bindParam(':eciv_id', $eciv_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($pges_genero)))) {
                $comando->bindParam(':pges_genero', $pges_genero, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_nacionalidad)))) {
                $comando->bindParam(':pges_nacionalidad', $pges_nacionalidad, \PDO::PARAM_STR);
            }
            if (!empty((isset($pai_id_nacimiento)))) {
                $comando->bindParam(':pai_id_nacimiento', $pai_id_nacimiento, \PDO::PARAM_INT);
            }
            if (!empty((isset($pro_id_nacimiento)))) {
                $comando->bindParam(':pro_id_nacimiento', $pro_id_nacimiento, \PDO::PARAM_INT);
            }
            if (!empty((isset($can_id_nacimiento)))) {
                $comando->bindParam(':can_id_nacimiento', $can_id_nacimiento, \PDO::PARAM_INT);
            }
            if (!empty((isset($pges_nac_ecuatoriano)))) {
                $comando->bindParam(':pges_nac_ecuatoriano', $pges_nac_ecuatoriano, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_fecha_nacimiento)))) {
                $comando->bindParam(':pges_fecha_nacimiento', $pges_fecha_nacimiento, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_celular)))) {
                $comando->bindParam(':pges_celular', $pges_celular, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_correo)))) {
                $comando->bindParam(':pges_correo', $pges_correo, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_foto)))) {
                $comando->bindParam(':pges_foto', $pges_foto, \PDO::PARAM_STR);
            }
            if (!empty((isset($tsan_id)))) {
                $comando->bindParam(':tsan_id', $tsan_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($pges_domicilio_sector)))) {
                $comando->bindParam(':pges_domicilio_sector', $pges_domicilio_sector, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_domicilio_cpri)))) {
                $comando->bindParam(':pges_domicilio_cpri', $pges_domicilio_cpri, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_domicilio_csec)))) {
                $comando->bindParam(':pges_domicilio_csec', $pges_domicilio_csec, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_domicilio_num)))) {
                $comando->bindParam(':pges_domicilio_num', $pges_domicilio_num, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_domicilio_ref)))) {
                $comando->bindParam(':pges_domicilio_ref', $pges_domicilio_ref, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_domicilio_telefono)))) {
                $comando->bindParam(':pges_domicilio_telefono', $pges_domicilio_telefono, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_domicilio_celular2)))) {
                $comando->bindParam(':pges_domicilio_celular2', $pges_domicilio_celular2, \PDO::PARAM_STR);
            }
            if (!empty((isset($pai_id_domicilio)))) {
                $comando->bindParam(':pai_id_domicilio', $pai_id_domicilio, \PDO::PARAM_INT);
            }
            if (!empty((isset($pro_id_domicilio)))) {
                $comando->bindParam(':pro_id_domicilio', $pro_id_domicilio, \PDO::PARAM_INT);
            }
            if (!empty((isset($can_id_domicilio)))) {
                $comando->bindParam(':can_id_domicilio', $can_id_domicilio, \PDO::PARAM_INT);
            }
            if (!empty((isset($pges_trabajo_nombre)))) {
                $comando->bindParam(':pges_trabajo_nombre', $pges_trabajo_nombre, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_trabajo_direccion)))) {
                $comando->bindParam(':pges_trabajo_direccion', $pges_trabajo_direccion, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_trabajo_telefono)))) {
                $comando->bindParam(':pges_trabajo_telefono', $pges_trabajo_telefono, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_trabajo_ext)))) {
                $comando->bindParam(':pges_trabajo_ext', $pges_trabajo_ext, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_id_trabajo)))) {
                $comando->bindParam(':pges_id_trabajo', $pges_id_trabajo, \PDO::PARAM_INT);
            }
            if (!empty((isset($pro_id_trabajo)))) {
                $comando->bindParam(':pro_id_trabajo', $pro_id_trabajo, \PDO::PARAM_INT);
            }
            if (!empty((isset($can_id_trabajo)))) {
                $comando->bindParam(':can_id_trabajo', $can_id_trabajo, \PDO::PARAM_INT);
            }
            if (isset($econ_id)) {
                $comando->bindParam(':econ_id', $econ_id, \PDO::PARAM_INT);
            }
            if (isset($ccan_id)) {
                $comando->bindParam(':ccan_id', $ccan_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($pges_razon_social)))) {
                $comando->bindParam(':pges_razon_social', $pges_razon_social, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_contacto_empresa)))) {
                $comando->bindParam(':pges_contacto_empresa', $pges_contacto_empresa, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_num_empleados)))) {
                $comando->bindParam(':pges_num_empleados', $pges_num_empleados, \PDO::PARAM_STR);
            }
            if (!empty((isset($telefono_empresa)))) {
                $comando->bindParam(':pges_telefono_empresa', $telefono_empresa, \PDO::PARAM_STR);
            }
            if (!empty((isset($direccion)))) {
                $comando->bindParam(':pges_direccion_empresa', $direccion, \PDO::PARAM_STR);
            }
            if (!empty((isset($cargo)))) {
                $comando->bindParam(':pges_cargo', $cargo, \PDO::PARAM_STR);
            }
            if (!empty((isset($pges_usuario_ingreso)))) {
                $comando->bindParam(':pges_usuario_ingreso', $pges_usuario_ingreso, \PDO::PARAM_INT);
            }

            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.persona_gestion');
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return 0;
        }
    }

    /**
     * Function modifica datos generales del benificiario.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function modificarDatoGeneral($pges_pri_nombre, $pges_seg_nombre, $pges_pri_apellido, $pges_seg_apellido, $pges_id, $pges_celular, $pges_domicilio_celular2, $pges_domicilio_telefono, $pges_correo, $pges_contacto_empresa) {

        $con = \Yii::$app->db_crm;
        $estado = 1;
        $filtro = '';
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        if (!empty($pges_celular)) {
            $filtro .= 'pges_celular = :pges_celular, ';
        }
        if (!empty($pges_seg_nombre)) {
            $filtro .= 'pges_seg_nombre = :pges_seg_nombre, ';
        }
        if (!empty($pges_seg_apellido)) {
            $filtro .= ' pges_seg_apellido = :pges_seg_apellido, ';
        }
        if (!empty($pges_domicilio_celular2)) {
            $filtro .= ' pges_domicilio_celular2 = :pges_domicilio_celular2, ';
        }
        if (!empty($pges_domicilio_telefono)) {
            $filtro .= ' pges_domicilio_telefono = :pges_domicilio_telefono, ';
        }
        if (!empty($pges_correo)) {
            $filtro .= ' pges_correo = :pges_correo, ';
        }
        if (!empty($pges_contacto_empresa)) {
            $filtro .= ' pges_contacto_empresa = :pges_contacto_empresa, ';
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".persona_gestion
                      SET pges_pri_nombre = :pges_pri_nombre,
                        $filtro
                        pges_pri_apellido = :pges_pri_apellido
                      WHERE
                        pges_id = :pges_id AND
                        pges_estado = :estado AND
                        pges_estado_logico = :estado");
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
            $comando->bindParam(":pges_pri_nombre", $pges_pri_nombre, \PDO::PARAM_STR);
            $comando->bindParam(":pges_pri_apellido", $pges_pri_apellido, \PDO::PARAM_STR);
            if (!empty($pges_seg_nombre)) {
                $comando->bindParam(":pges_seg_nombre", $pges_seg_nombre, \PDO::PARAM_STR);
            }
            if (!empty($pges_seg_apellido)) {
                $comando->bindParam(":pges_seg_apellido", $pges_seg_apellido, \PDO::PARAM_STR);
            }
            if (!empty($pges_celular)) {
                $comando->bindParam(":pges_celular", $pges_celular, \PDO::PARAM_STR);
            }
            if (!empty($pges_domicilio_celular2)) {
                $comando->bindParam(":pges_domicilio_celular2", $pges_domicilio_celular2, \PDO::PARAM_STR);
            }
            if (!empty($pges_domicilio_telefono)) {
                $comando->bindParam(":pges_domicilio_telefono", $pges_domicilio_telefono, \PDO::PARAM_STR);
            }
            if (!empty($pges_correo)) {
                $comando->bindParam(":pges_correo", $pges_correo, \PDO::PARAM_STR);
            }
            if (!empty($pges_contacto_empresa)) {
                $comando->bindParam(":pges_contacto_empresa", $pges_contacto_empresa, \PDO::PARAM_STR);
            }
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
     * Function consultarIdPersonaGestion
     * @author  Kleber Loayza
     * @property
     * @return
     */
    public function consultarIdPersonaGestion($correo) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                SELECT  ifnull(pges_id,0) as pges_id
                FROM   db_crm.persona_gestion
                WHERE
                pges_correo = '$correo' AND
                pges_estado = 1 AND
                pges_estado_logico=1
                    ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if (empty($resultData['pges_id']))
            return 0;
        else {
            return $resultData['pges_id'];
        }
    }

    /**
     * Function consulta si existe los numeros de telefonos o correo en algun otro contacto.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDatosExiste($pges_celular, $pges_correo, $pges_domicilio_telefono, $pges_domicilio_celular2, $pges_cedula, $pges_pasaporte, $opcion = null) {
        $con = \Yii::$app->db_crm;
        $estado = 1;

        if (!empty($pges_celular)) {
            $filtro .= "pges_celular like :pges_celular ";
        }
        if (!empty($pges_correo)) {
            if (!empty($pges_celular)) {
                $filtro .= " OR ";
            }
            $filtro .= " pges_correo = :pges_correo ";
        }
        if (!empty($pges_domicilio_telefono)) {
            if (!empty($pges_correo) || !empty($pges_celular)) {
                $filtro .= " OR ";
            }
            $filtro .= " pges_domicilio_telefono like :pges_domicilio_telefono ";
        }
        if (!empty($pges_domicilio_celular2)) {
            if (!empty($pges_domicilio_telefono) || !empty($pges_celular)) {
                $filtro .= " OR ";
            }
            $filtro .= " pges_domicilio_celular2 like :pges_domicilio_celular2";
        }
        if (!empty($pges_cedula)) {

            $filtro .= " OR pges_cedula = :pges_cedula";
        }
        if (!empty($pges_pasaporte)) {

            $filtro .= " OR pges_pasaporte = :pges_pasaporte";
        }
        if (empty($opcion)) {
            $sql = "SELECT
                    count(*) as registro ";
        } else {
            $sql = "SELECT
                      pges_id as registro ";
        }
        $sql .= "FROM
                   " . $con->dbname . ".persona_gestion "
                . "WHERE ";
        if (!empty($filtro)) {
            $sql .= "(";
            $sql .= $filtro;
            $sql .= ") AND ";
        }
        $sql .= "pges_estado_logico = :estado AND
                 pges_estado = :estado";
        \app\models\Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (!empty(($pges_celular))) {
            $pges_celularc = "%" . substr($pges_celular, -9) . "%";
            $comando->bindParam(':pges_celular', $pges_celularc, \PDO::PARAM_STR);
        }
        if (!empty($pges_correo)) {
            $comando->bindParam(':pges_correo', $pges_correo, \PDO::PARAM_STR);
        }
        if (!empty($pges_domicilio_telefono)) {
            $pges_domicilio_telefonoc = "%" . $pges_domicilio_telefono . "%";
            $comando->bindParam(':pges_domicilio_telefono', $pges_domicilio_telefonoc, \PDO::PARAM_STR);
        }
        if (!empty($pges_domicilio_celular2)) {
            $pges_domicilio_celular2c = "%" . $pges_domicilio_celular2 . "%";
            $comando->bindParam(':pges_domicilio_celular2', $pges_domicilio_celular2c, \PDO::PARAM_STR);
        }
        if (!empty($pges_cedula)) {
            $comando->bindParam(':pges_cedula', $pges_cedula, \PDO::PARAM_STR);
        }
        if (!empty($pges_pasaporte)) {
            $comando->bindParam(':pges_pasaporte', $pges_pasaporte, \PDO::PARAM_STR);
        }
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarIdPersonaContratante
     * @author  Kleber Loayza
     * @property
     * @return
     */
    public function consultarPersonaGestion($pges_id) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                    SELECT *, pai.pai_nombre as pais
                    FROM  db_crm.persona_gestion pges
                    INNER JOIN " . $con1->dbname . ".pais pai on pai.pai_id = pges.pai_id_nacimiento
                    WHERE
                    pges_id = :pges_id
                    AND pges_estado = $estado
                    AND pges_estado_logico=$estado
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        if (empty($resultData))
            return 0;
        else {
            return $resultData;
        }
    }

    /**
     * Function consultarIdPersonaContratante
     * @author  Kleber Loayza
     * @property
     * @return
     */
    public function consultarIdPersonaContratante($pges_id) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                SELECT
                ifnull(pcon_id,0) as pcon_id
                FROM   db_crm.persona_contratante
                WHERE
                pges_id = $pges_id
                AND pcon_estado = 1
                AND pcon_estado_logico=1
                    ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if (empty($resultData['pcon_id']))
            return 0;
        else {
            return $resultData['pcon_id'];
        }
    }

    /**
     * Function Consultar los clientes contratantes.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @modify Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarClienteCont($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_academico;
        $estado = 1;
        $str_search = "";
        $columnsAdd = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(a.pges_pri_nombre like :search OR ";
            $str_search .= "a.pges_seg_nombre like :search OR ";
            $str_search .= "a.pges_pri_apellido like :search OR ";
            $str_search .= "a.pges_seg_apellido like :search OR ";
            $str_search .= "a.pges_codigo like :search )  AND ";
            if ($arrFiltro['estado'] != "" && $arrFiltro['estado'] > 0) {
                $str_search .= " pg.econ_id = :estcontacto AND ";
            }
            if ($arrFiltro['medio'] != "" && $arrFiltro['medio'] > 0) {
                $str_search .= " a.ccan_id = :medio AND ";
            }
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "a.pges_fecha_creacion >= :fec_ini AND ";
                $str_search .= "a.pges_fecha_creacion <= :fec_fin AND ";
            }
            if ($arrFiltro['agente'] != "" && $arrFiltro['agente'] > 0) {
                $str_search .= " a.pges_usuario_ingreso = :agente AND ";
            }
            if ($arrFiltro['correo'] != "") {
                $str_search .= " a.pges_correo like :correo AND ";
            }
            if ($arrFiltro['telefono'] != "") {
                $str_search .= "(a.pges_celular like :telefono OR ";
                $str_search .= "a.pges_domicilio_telefono like :telefono OR ";
                $str_search .= "a.pges_domicilio_celular2 like :telefono OR ";
                $str_search .= "a.pges_trabajo_telefono like :telefono )  AND ";
            }
            if ($arrFiltro['empresa'] != "" && $arrFiltro['empresa'] > 0) {
                $str_search .= " a.emp_id = :empresa AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= " a.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['gestion'] != "" && $arrFiltro['gestion'] > 0) {
                $str_search .= " a.gestion = :gestion AND ";
            }
        } else {
            $columnsAdd = "
                pg.pges_pri_nombre as pges_pri_nombre,
                pg.pges_seg_nombre as pges_seg_nombre,
                pg.pges_pri_apellido as pges_pri_apellido,
                pg.pges_seg_apellido as pges_seg_apellido,";
        }
        $sql = "SELECT * FROM (
                SELECT
                        tp.tper_id tipo_persona,
                        tp.tper_nombre des_tipo_persona,
                        pges_pri_nombre,
                        pges_seg_nombre,
                        pges_pri_apellido,
                        pges_seg_apellido,
                        concat(pges_pri_nombre, ' ', ifnull(pges_seg_nombre,' ')) as nombres,
                        concat(pges_pri_apellido, ' ', ifnull(pges_seg_apellido,' ')) as apellidos,
                        concat(pges_pri_nombre, ' ', ifnull(pges_pri_apellido,' ')) as cliente,
                        ifnull((select pai.pai_nombre from " . $con1->dbname . ".pais pai where pai.pai_id = pg.pai_id_nacimiento),'') as pais,
                        pg.pges_id as pestion_id,
                        ifnull(emp.emp_nombre_comercial,'Sin Empresa') as empresa,
                        pg.econ_id,
                        ifnull(uaca.uaca_nombre,'Sin Unidad') as unidad_academica,
                        pg.ccan_id,
                        cc.ccan_nombre as canal,
                        pg.pges_codigo,
                        pg.pges_correo,
                        pg.pges_fecha_creacion,
                        concat(pe.per_pri_nombre, ' ', pe.per_pri_apellido) as agente,
                        ifnull((select concat(pers.per_pri_nombre, ' ', ifnull(pers.per_pri_apellido,' '))
                                  from " . $con1->dbname . ".usuario usu
                                  inner join " . $con1->dbname . ".persona pers on pers.per_id = usu.per_id
                                  where usu.usu_id = pg.pges_usuario_ingreso),'') as usuario_ing,
                        (select count(*) from " . $con->dbname . ".oportunidad o where o.pges_id = pg.pges_id and o.eopo_id in(1,2,3) and o.opo_estado = :estado and o.opo_estado_logico = :estado) as num_oportunidad_abiertas,
                        (select count(*) from " . $con->dbname . ".oportunidad o where o.pges_id = pg.pges_id and o.eopo_id in(4,5) and o.opo_estado = :estado and o.opo_estado_logico = :estado) as num_oportunidad_cerradas,
                        DATE(pg.pges_fecha_creacion) as fecha_creacion,
                        pg.pges_usuario_ingreso,
                        pg.pges_celular,
                        pg.pges_domicilio_telefono,
                        pg.pges_domicilio_celular2,
                        pg.pges_trabajo_telefono,
                        emp.emp_id,
                        uaca.uaca_id,
                        case when opo.eopo_id >= 3 then 2
                            when (select ifnull(count(ba.bact_id),0)
                            from db_crm.oportunidad o
                            inner join db_crm.bitacora_actividades ba on ba.opo_id = o.opo_id
                            inner join db_asgard.usua_grol_eper uge on uge.usu_id = ba.usu_id
                            where o.pges_id = pg.pges_id
                                            -- and ba.eopo_id = 3
                            and o.opo_estado = :estado
                            and o.opo_estado_logico = :estado) < 2 then 1 else 2 end as gestion
                FROM " . $con->dbname . ".persona_gestion pg
                INNER JOIN " . $con->dbname . ".estado_contacto ec on ec.econ_id = pg.econ_id
                INNER JOIN " . $con1->dbname . ".tipo_persona tp on tp.tper_id = pg.tper_id
                INNER JOIN " . $con->dbname . ".conocimiento_canal cc on cc.ccan_id = pg.ccan_id
                left JOIN (
                    select max(opo.opo_id) as opo_id,opo.pges_id
                    from " . $con->dbname . ".oportunidad as opo
                    group by opo.pges_id
                ) AS max_opor on max_opor.pges_id=pg.pges_id
                left JOIN " . $con->dbname . ".oportunidad opo on opo.opo_id = max_opor.opo_id
                left join " . $con1->dbname . ".empresa as emp on emp.emp_id=opo.emp_id
                left JOIN " . $con2->dbname . ".unidad_academica uaca on uaca.uaca_id = opo.uaca_id
                left JOIN ". $con1->dbname ." usu ON usu.usu_id=pg.pges_usuario_ingreso
                left JOIN ". $con1->dbname .".persona pe ON pe.per_id=pg.pges_usuario_ingreso
                WHERE
                        pg.pges_estado = :estado
                        and pg.pges_estado_logico = :estado
                        and tp.tper_estado = :estado
                        and tp.tper_estado_logico = :estado
                        and ec.econ_estado = :estado
                        and ec.econ_estado_logico = :estado
                        and cc.ccan_estado = :estado
                        and cc.ccan_estado_logico = :estado
                ) a ";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $sql .= "WHERE $str_search
                           a.pges_codigo = a.pges_codigo";
        }
        if (isset($arrFiltro) && count($arrFiltro) == 0) { //Aqui se filtran solo los pendientes de gestionar, si debe borrar el if para q salgan todos
            $sql .= "WHERE a.gestion < 2";
        }

        $sql .= " ORDER BY a.pges_fecha_creacion desc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);

            if ($arrFiltro['estado'] != "" && $arrFiltro['estado'] > 0) {
                $estcontacto = $arrFiltro["estado"];
                $comando->bindParam(":estcontacto", $estcontacto, \PDO::PARAM_INT);
            }

            if ($arrFiltro['medio'] != "" && $arrFiltro['medio'] > 0) {
                $medio = $arrFiltro["medio"];
                $comando->bindParam(":medio", $medio, \PDO::PARAM_INT);
            }
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['agente'] != "" && $arrFiltro['agente'] > 0) {
                $agente = $arrFiltro["agente"];
                $comando->bindParam(":agente", $agente, \PDO::PARAM_INT);
            }
            if ($arrFiltro['correo'] != "") {
                $search_cro = "%" . $arrFiltro["correo"] . "%";
                $comando->bindParam(":correo", $search_cro, \PDO::PARAM_STR);
            }
            if ($arrFiltro['telefono'] != "") {
                $search_tfn = "%" . $arrFiltro["telefono"] . "%";
                $comando->bindParam(":telefono", $search_tfn, \PDO::PARAM_STR);
            }
            if ($arrFiltro['empresa'] != "" && $arrFiltro['empresa'] > 0) {
                $search_emp = $arrFiltro["empresa"];
                $comando->bindParam(":empresa", $search_emp, \PDO::PARAM_INT);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['gestion'] != "" && $arrFiltro['gestion'] > 0) {
                $search_gest = $arrFiltro["gestion"];
                $comando->bindParam(":gestion", $search_gest, \PDO::PARAM_INT);
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
     * Function modifica subestado del cliente
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function modificarSestaclixId($pges_id, $ecli_id) {

        $con = \Yii::$app->db_crm;
        $estado = 1;
        $filtro = '';
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".persona_gestion
                      SET ecli_id = :ecli_id
                      WHERE
                        pges_id = :pges_id AND
                        pges_estado = :estado AND
                        pges_estado_logico = :estado");
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
            $comando->bindParam(":ecli_id", $ecli_id, \PDO::PARAM_INT);
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

    public function modificarSestaclixIdLeads($con, $pges_id, $scli_id) {
        $sql = "UPDATE " . $con->dbname . ".persona_gestion
                      SET scli_id = :scli_id
                  WHERE pges_id = :pges_id AND pges_estado=1 AND pges_estado_logico = 1";
        $command = $con->createCommand($sql);
        $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $comando->bindParam(":scli_id", $scli_id, \PDO::PARAM_INT);
        $command->execute();
        //return $con->getLastInsertID();
    }

    public static function uploadFile($file) {
        $chk_ext = explode(".", $file);
        $con = \Yii::$app->db_crm;
        $mod_gestion = new Oportunidad();
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        if (strtolower(end($chk_ext)) == "csv") {
            try {
                $handle = fopen($file, "r");
                $cont = 0;
                while (($data = fgetcsv($handle, ",")) !== FALSE) {
                    if ($cont != 0) {
                        $model = new PersonaGestion();
                        $model->tper_id = "1";
                        $model->pges_pri_nombre = "$data[3]";
                        $model->pges_seg_nombre = " ";
                        $model->pges_pri_apellido = " ";
                        $model->pges_seg_apellido = " ";
                        $model->pges_razon_social = " ";
                        $model->pai_id_nacimiento = 57;
                        $model->pro_id_nacimiento = 1;
                        $model->can_id_nacimiento = 1;
                        $model->pges_celular = "$data[4]";
                        $model->pges_correo = "$data[5]";
                        $model->scli_id = 1;
                        $model->ccan_id = 3;
                        $model->pges_estado = "1";
                        $model->pges_estado_logico = "1";
                        if ($model->save()) {
                            $resp_persona = $model->pges_id;
                            $res_contratante = $mod_gestion->insertarPersonaContratante($resp_persona, null);
                            if ($res_contratante) {
                                $res_beneficiario = $mod_gestion->insertarPersonaBeneficiaria($resp_persona, null, $res_contratante);
                            }
                        }
                    }
                    $cont++;
                }
                fclose($handle);
                if ($trans !== null)
                    $trans->commit();
                return true;
            } catch (Exception $ex) {
                fclose($handle);
                if ($trans !== null)
                    $trans->rollback();
                return false;
            }
        }
    }

    /**
     * Function obtener como conocio a la uteg el contacto
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarConociouteg() {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT
                    cser.cser_id as id,
                    cser.cser_nombre as name
                FROM
                    " . $con->dbname . ".conocimiento_servicio as cser
                WHERE
                    cser.cser_estado_logico=:estado AND
                    cser.cser_estado=:estado
                ORDER BY name asc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarCodigoArea
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function consultarPerGesTemp($op) {
        $con = \Yii::$app->db_crm;
        if (($op == "LEADS") || ($op == "OTROS_CANALES")) {
            $sql = "SELECT * FROM " . $con->dbname . ".persona_gestion_tmp;";
        } else {
            //PROCESO LOTES LOTES
            $sql = "SELECT * FROM " . $con->dbname . ".temporal_contactos LIMIT 150;";
        }
        $comando = $con->createCommand($sql);
        return $comando->queryAll();
    }

    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarCodigoArea
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function insertarDtosPersonaGestion($emp_id, $tipoProceso) {
        $contError = 0;
        $Data = $this->consultarPerGesTemp($tipoProceso);

        $rawData = ''; //array();
        $mensError = '';
        $con = \Yii::$app->db_crm;
        $mod_gruprol = new GrupRol();
        $mod_oportunidad = new Oportunidad();
        $mod_pergestion = new PersonaGestion();
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            //Obtener Datos Persona Admision
            $per_id = @Yii::$app->session->get("PB_perid");
            $idEmpresa = @Yii::$app->session->get("PB_idempresa");
            $grol_id = $mod_gruprol->consultarIdUsuGrolEper($per_id, $idEmpresa);
            //$padm_id=$mod_pergestion->consultarIdPersonaAdmision($per_id, $grol_id);
            //$padm_id=($padm_id!=0)?$padm_id:1;//Valor por Defecto en el Caso de que no Existe Persona Admision
            //-------------------------------
            //$opo_codigo = Secuencias::nuevaSecuencia($con, 1, 1, 1, 'SOL') ;//
            $opo_codigo = intval($mod_oportunidad->consultarUltimoCodcrm()) + 1;
            $pges_codigo = intval($mod_pergestion->consultarUltimoCodPerGest()) + 1;
            for ($i = 0; $i < sizeof($Data); $i++) {
                //Verifico si Existe lOS datos en la tabla
                $pges_id = PersonaGestion::existePersonaGestLeads($Data[$i]['pgest_correo'], $Data[$i]['pgest_numero']);
                if ($pges_id == 0) {
                    //if (!PersonaGestion::existePersonaGestLeads($Data[$i]['correo'], $Data[$i]['telefono'])) {
                    //Si no Existe lo inserta en la Tabla
                    $nombre = $Data[$i]['pgest_nombre'];
                    $telefono = $Data[$i]['pgest_numero'];
                    $correo = $Data[$i]['pgest_correo'];
                    if ($tipoProceso == "LEADS") {
                        $contacto = 2; //$Data[$i]['pgest_contacto'];
                    } else {
                        $contacto = $Data[$i]['pgest_contacto'];
                    }

                    $tper_id = 1; //Por defecto Natural
                    $econ_id = 1; //=>En Contacto por defecto
                    $pges_id = PersonaGestion::insertarPersonaGestionLeads($con, $pges_codigo, $tper_id, $nombre, $telefono, $correo, $contacto, $econ_id);
                    $pges_codigo++;
                    if ($pges_id > 0) {
                        //-------------------------------------------
                        //Opciones por defecto segun los indicado por Ing. Geovanni
                        $uaca_id = $Data[$i]['pgest_unidad_academica'];
                        $mod_id = $Data[$i]['pgest_modalidad'];

                        if ($emp_id == "1") {
                            switch ($uaca_id) { // esto cambiarlo hacer funcion que consulte el usuario y traer el id
                                case "1":
                                    $tipoportunidad = 1;
                                    if ($modalidad == "1") {
                                        $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1); // 1 uteg//15;
                                    } else {
                                        $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1); //14;
                                    }
                                    break;
                                case "2":
                                    $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1); //16;
                                    $tipoportunidad = 5;
                                    break;
                            }
                        } elseif ($emp_id == "2" || $emp_id == "3") { //UNLINK Y SMART
                            /* switch ($uaca_id) {
                              case "2":
                              $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1);
                              //$tipoportunidad = 5;
                              break;
                              case "3":
                              $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1);
                              //$tipoportunidad = 5;

                              case "5":
                              $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1);
                              //$tipoportunidad = 5;
                              case "6":
                              $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1);
                              //$tipoportunidad = 5;
                              default:
                              } */
                            $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1);
                        }
                        $padm_id = $agente['agente_id'];
                        //-------------------------------------------
                        //$pgco_id = $mod_oportunidad->insertarPersonaGestionContactoLeads($con, $pges_id,$Data[$i]);
                        $opo_id = $mod_oportunidad->insertarOportunidadLeads($con, $opo_codigo, $emp_id, $pges_id, $padm_id, $Data[$i]);
                        $opo_codigo++;
                        if ($opo_id > 0) {
                            $bact_id = $mod_oportunidad->insertarActividadLeads($con, $opo_id, $padm_id, $Data[$i]['pgest_comentario']);
                        }
                    }
                } else {
                    //Si existen en gestion Persona
                    //$rawData[] = $Data[$i]['pgest_nombre'].'-'.$Data[$i]['pgest_correo'].'<br/>';
                    \app\models\Utilities::putMessageLogFile('Contacto existente:'. $Data[$i]['pgest_correo']);
                    $mensError .= 'Nombre: ' . $Data[$i]['pgest_nombre'] . ' - ' . $Data[$i]['pgest_correo'] . '<br/>';
                    $contError++;
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
            return $arroout;
        } catch (Exception $ex) {
            fclose($handle);
            if ($trans !== null)
                $trans->rollback();

            $arroout["status"] = FALSE;
            $arroout["error"] = $ex->getCode();
            $arroout["message"] = $ex->getMessage();
            $arroout["data"] = $rawData;
            return $arroout;
        }
    }

    /**
     * Function consulta el ultimo codigo de gestion generado.
     * @author Byron Villacreses <developer@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarDtosPersonaGestionLotes($emp_id, $tipoProceso) {
        //pgest_nombre,pgest_numero,pgest_correo
        $contError = 0;
        $Data = $this->consultarPerGesTemp($tipoProceso);
        $rawData = ''; //array();
        $mensError = '';
        $con = \Yii::$app->db_crm;
        $mod_persona = new Persona();
        $mod_gruprol = new GrupRol();
        $mod_estaOport = new EstadoOportunidad();
        $mod_estaCont = new EstadoContacto();
        $mod_tipOportVent = new TipoOportunidadVenta();
        $mod_oportunidad = new Oportunidad();
        $mod_pergestion = new PersonaGestion();
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        //INSERT INTO `db_crm`.`temporal_contactos`
        //(`id`,`id_contacto`,`fecha_registro`,`unidad_academica`,`canal_contacto`,`ultima_modalidad_interes`,`ultima_carrera_interes`,`medio_contacto_solicitado`,
        //`horario_contacto_solicitado`,`nombre`,`telefono`,`telefono_ext`,`correo`,`ciudad`,`pais`,`ultimo_estado`,`carrera_interes`,`modalidad`,`tipo_cliente`,
        //`agente_atencion`,`fecha_atencion`,`tipo_oportunidad`,`estado_contacto`,`estado_oportunidad`,`fecha_siguiente_atencion`,`hora_siguiente_atencion`,
        //`fecha_tentativa_pago`,`observacion`,`motivo_oportunidad_perdida`,`otra_universidad`,`tipo_observacion`)

        try {
            //Obtener Datos Persona Admision
            $per_id = @Yii::$app->session->get("PB_perid");
            $idEmpresa = @Yii::$app->session->get("PB_idempresa");
            $grol_id = $mod_gruprol->consultarIdUsuGrolEper($per_id, $idEmpresa);
            //$padm_id=$mod_pergestion->consultarIdPersonaAdmision($per_id, $grol_id);
            //$padm_id=($padm_id!=0)?$padm_id:1;//Valor por Defecto en el Caso de que no Existe Persona Admision
            //-------------------------------

            $opo_codigo = intval($mod_oportunidad->consultarUltimoCodcrm()) + 1; //
            $pges_codigo = intval($mod_pergestion->consultarUltimoCodPerGest()) + 1; //
            for ($i = 0; $i < sizeof($Data); $i++) {

                //Verifico si Existe lOS datos en la tabla
                $pges_id = PersonaGestion::existePersonaGestLeads($Data[$i]['correo'], $Data[$i]['telefono']);
                //if ($pges_id>0) {
                $nombre = $Data[$i]['nombre'];
                $telefono = $Data[$i]['telefono'];
                $correo = $Data[$i]['correo'];
                // \app\models\Utilities::putMessageLogFile($i." ".$nombre." ".$correo);
                $contacto = PersonaGestionTmp::consultarIdsConocimientoCanal($Data[$i]['medio_contacto_solicitado']);
                $tper_id = $mod_persona->consultarTipoPersona($Data[$i]['tipo_cliente']);
                $econ_id = $mod_estaCont->consultarIdsEstadoContacto($Data[$i]['estado_contacto']); //=>En Contacto por defecto
                $econ_id = ($econ_id != 0) ? $econ_id : 1; //1 VALOR POR DEFECTO
                //Si No existe se crea y si existe se extrae el ID
                //$pges_id = ($pges_id == 0) ? PersonaGestion::insertarPersonaGestionLeads($con, $pges_codigo, $tper_id, $nombre, $telefono, $correo, $contacto, $econ_id) : $pges_id;
                if ($pges_id == 0) {
                    //si no existe lo crea
                    $pges_id = PersonaGestion::insertarPersonaGestionLeads($con, $pges_codigo, $tper_id, $nombre, $telefono, $correo, $contacto, $econ_id);
                    $pges_codigo++;
                }

                if ($pges_id > 0) {
                    $tove_id = ($Data[$i]['tipo_oportunidad'] != "") ? $mod_tipOportVent->consultarIdsOporxUnidad($Data[$i]['tipo_oportunidad']) : 1; //se puede obtener a partir d ela unidad academica
                    //$pgco_id = $mod_oportunidad->insertarPersonaGestionContactoLeads($con, $pges_id,$Data[$i]);
                    $eopo_id = $mod_estaOport->consultarIdsEstadOportunidad($Data[$i]['estado_oportunidad']); //estado oportunidad => En Curso
                    $eopo_id = ($eopo_id != 0) ? $eopo_id : 1; //1 VALOR POR DEFECTO
                    $uaca_id = UnidadAcademica::consultarIdsUnid_Academica($Data[$i]['unidad_academica']);
                    $mod_id = Modalidad::consultarIdsModalidad($Data[$i]['ultima_modalidad_interes']);

                    //-------------------------------------------
                    //Opciones por defecto segun los indicado por Ing. Geovanni
                    switch ($uaca_id) { // esto cambiarlo hacer funcion que consulte el usuario y traer el id
                        case "1":
                            $tipoportunidad = 1;
                            if ($modalidad == "1") {
                                $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1); // 1 uteg//15;
                            } else {
                                $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1); //14;
                            }
                            break;
                        case "2":
                            $agente = $mod_oportunidad->consultarAgentebyCod($uaca_id, $mod_id, 1); //16;
                            $tipoportunidad = 5;
                            break;
                    }
                    $padm_id = $agente['agente_id'];
                    //-------------------------------------------

                    $opo_id = $mod_oportunidad->existeOportunidad_Unidad_Modalidad($pges_id, $uaca_id, $mod_id);
                    if ($opo_id == 0) {
                        //Ingresa una nueva oportunidad
                        $opo_id = $mod_oportunidad->insertarOportunidadLotes($con, $opo_codigo, $emp_id, $pges_id, $padm_id, $eopo_id, $tove_id, $uaca_id, $mod_id, $Data[$i]);
                        $opo_codigo++;
                    }

                    if ($opo_id > 0) {
                        //Solo si esta perdida se ingresa la Tipo de Obeservacion
                        //caso contrario se ingresa la observacion Normal
                        $bact_descripcion = ($eopo_id == 5) ? $Data[$i]['tipo_observacion'] : $Data[$i]['observacion'];
                        $bact_id = $mod_oportunidad->insertarActividadLotes($con, $opo_id, $padm_id, $eopo_id, $bact_descripcion);
                    }
                }
                //}else{
                //$mensError .= 'Nombre: '.$Data[$i]['pgest_nombre'].' - '.$Data[$i]['pgest_correo'].'<br/>';
                //$contError++;
                //}
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
            return $arroout;
        } catch (Exception $ex) {
            fclose($handle);
            if ($trans !== null)
                $trans->rollback();

            $arroout["status"] = FALSE;
            $arroout["error"] = $ex->getCode();
            $arroout["message"] = $ex->getMessage();
            $arroout["data"] = $rawData;
            return $arroout;
        }
    }

    /**
     * Function consulta el ultimo codigo de gestion generado.
     * @author Byron Villacreses <developer@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarUltimoCodPerGest() {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT ifnull(MAX(CAST(pges_codigo AS UNSIGNED)),0) AS id
                FROM
                   " . $con->dbname . ".persona_gestion ";
        $sql .= "  WHERE
                   pges_estado = :estado AND
                   pges_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryScalar(); //$comando->queryOne();
        return $resultData;
    }

    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarCodigoArea
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function insertarPersonaGestionLeads($con, $pges_codigo, $tper_id, $nombre, $telefono, $correo, $contacto, $econ_id) {
        //$econ_id=1;//=>En Contacto por defecto
        $pges_codigo = str_pad((int) $pges_codigo, 7, "0", STR_PAD_LEFT);
        $usuingreso = @Yii::$app->session->get("PB_iduser");
        $sql = "INSERT INTO " . $con->dbname . ".persona_gestion
                    (pges_codigo,tper_id,pges_pri_nombre,pai_id_nacimiento,pro_id_nacimiento,can_id_nacimiento,
                    pges_estado_contacto,pges_usuario_ingreso,
                    pges_celular,pges_correo,econ_id,ccan_id,pges_estado,pges_estado_logico) VALUES
                    (:pges_codigo,:tper_id,:pges_pri_nombre,1,1,1,:pges_estado_contacto,:pges_usuario_ingreso,
                    :pges_celular,:pges_correo,:econ_id,:ccan_id,1,1)";
        $command = $con->createCommand($sql);
        //$command->bindParam(":per_id", $per_id, \PDO::PARAM_INT); //Id Comparacion
        $command->bindParam(":pges_codigo", $pges_codigo, \PDO::PARAM_STR);
        $command->bindParam(":tper_id", $tper_id, \PDO::PARAM_STR);
        $command->bindParam(":pges_pri_nombre", $nombre, \PDO::PARAM_STR);
        $command->bindParam(":pges_celular", $telefono, \PDO::PARAM_STR);
        $command->bindParam(":pges_correo", $correo, \PDO::PARAM_STR);
        $command->bindParam(":ccan_id", $contacto, \PDO::PARAM_STR);
        $command->bindParam(":econ_id", $econ_id, \PDO::PARAM_STR);
        $command->bindParam(":pges_estado_contacto", $econ_id, \PDO::PARAM_STR);
        $command->bindParam(":pges_usuario_ingreso", $usuingreso, \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    /**     * **
     * Function Existe Persona Gestion
     * @author  Kleber loayza <developer@uteg.edu.ec>
     * @return
     */
    public function existeCedulaPersonaGest($cedula) {
        $con = \Yii::$app->db_crm;
        $sql = "
                    SELECT pges_id Ids
                        FROM " . $con->dbname . ".persona_gestion
                    WHERE pges_estado_logico=1 AND pges_cedula=:pges_cedula
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":pges_cedula", $cedula, \PDO::PARAM_STR);
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return false; //Falso si no Existe
        return true; //Si Existe en la Tabla
    }

    /**     * **
     * Function Existe Persona Gestion
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public static function existePersonaGestLeads($correo, $numero) {
        //pgest_nombre,pgest_numero,pgest_correo
        $con = \Yii::$app->db_crm;
        $celular = "%" . substr($numero, -9) . "%";
        $sql = "SELECT pges_id Ids
                    FROM " . $con->dbname . ".persona_gestion
                WHERE pges_estado_logico=1 AND (pges_correo=:pges_correo OR pges_celular=:pges_celular)";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":pges_correo", $correo, \PDO::PARAM_STR);
        $comando->bindParam(":pges_celular", $celular, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData = $comando->queryScalar();
        if ($rawData === false)
        //return false; //Falso si no Existe
            return 0;
        return $rawData; //Si Existe en la Tabla
    }

    /**
     * Function insertarPersGestionContac grabar a personas contacto.
     * @author Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     *         Actualizado: Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarPersGestionContac($pges_id, $pgco_primer_nombre, $pgco_segundo_nombre, $pgco_primer_apellido, $pgco_segundo_apellido, $pgco_correo, $pgco_telefono, $pgco_celular, $pai_id_contacto) {
        $con = \Yii::$app->db_crm;
        $pgco_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "pgco_estado";
        $bdet_sql = "1";

        $param_sql .= ", pgco_estado_logico";
        $bdet_sql .= ", 1";

        if (isset($pges_id)) {
            $param_sql .= ", pges_id";
            $bdet_sql .= ", :pges_id";
        }
        if (isset($pgco_primer_nombre)) {
            $param_sql .= ", pgco_primer_nombre";
            $bdet_sql .= ", :pgco_primer_nombre";
        }
        if (isset($pgco_segundo_nombre)) {
            $param_sql .= ", pgco_segundo_nombre";
            $bdet_sql .= ", :pgco_segundo_nombre";
        }
        if (isset($pgco_primer_apellido)) {
            $param_sql .= ", pgco_primer_apellido";
            $bdet_sql .= ", :pgco_primer_apellido";
        }
        if (isset($pgco_segundo_apellido)) {
            $param_sql .= ", pgco_segundo_apellido";
            $bdet_sql .= ", :pgco_segundo_apellido";
        }
        if (isset($pgco_correo)) {
            $param_sql .= ", pgco_correo";
            $bdet_sql .= ", :pgco_correo";
        }
        if (isset($pgco_telefono)) {
            $param_sql .= ", pgco_telefono";
            $bdet_sql .= ", :pgco_telefono";
        }
        if (isset($pgco_celular)) {
            $param_sql .= ", pgco_celular";
            $bdet_sql .= ", :pgco_celular";
        }
        if (isset($pai_id_contacto)) {
            $param_sql .= ", pai_id_contacto";
            $bdet_sql .= ", :pai_id_contacto";
        }

        $param_sql .= ", pgco_fecha_creacion";
        $bdet_sql .= ", '$pgco_fecha_creacion'";


        try {
            $sql = "INSERT INTO " . $con->dbname . ".persona_gestion_contacto ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($pges_id)) {
                $comando->bindParam(':pges_id', $pges_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($pgco_primer_nombre)))) {
                $comando->bindParam(':pgco_primer_nombre', $pgco_primer_nombre, \PDO::PARAM_STR);
            }
            if (!empty((isset($pgco_segundo_nombre)))) {
                $comando->bindParam(':pgco_segundo_nombre', $pgco_segundo_nombre, \PDO::PARAM_STR);
            }
            if (!empty((isset($pgco_primer_apellido)))) {
                $comando->bindParam(':pgco_primer_apellido', $pgco_primer_apellido, \PDO::PARAM_STR);
            }
            if (!empty((isset($pgco_segundo_apellido)))) {
                $comando->bindParam(':pgco_segundo_apellido', $pgco_segundo_apellido, \PDO::PARAM_STR);
            }
            if (!empty((isset($pgco_correo)))) {
                $comando->bindParam(':pgco_correo', $pgco_correo, \PDO::PARAM_STR);
            }
            if (!empty((isset($pgco_telefono)))) {
                $comando->bindParam(':pgco_telefono', $pgco_telefono, \PDO::PARAM_STR);
            }
            if (!empty((isset($pgco_celular)))) {
                $comando->bindParam(':pgco_celular', $pgco_celular, \PDO::PARAM_STR);
            }
            if (!empty((isset($pai_id_contacto)))) {
                $comando->bindParam(':pai_id_contacto', $pai_id_contacto, \PDO::PARAM_INT);
            }

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.persona_gestion_contacto');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function modifica persona_gestion_contacto.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function modificarPercontXid($pgco_id, $pgco_primer_nombre, $pgco_segundo_nombre, $pgco_primer_apellido, $pgco_segundo_apellido, $pgco_correo, $pgco_telefono, $pgco_celular, $pai_id_contacto) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".persona_gestion_contacto
                      SET pgco_primer_nombre = :pgco_primer_nombre,
                          pgco_segundo_nombre = :pgco_segundo_nombre,
                          pgco_primer_apellido = :pgco_primer_apellido,
                          pgco_segundo_apellido = :pgco_segundo_apellido,
                          pgco_correo = :pgco_correo,
                          pgco_telefono = :pgco_telefono,
                          pgco_celular = :pgco_celular,
                          pai_id_contacto = :pai_id_contacto
                      WHERE
                        pgco_id = :pgco_id AND
                        pgco_estado = :estado AND
                        pgco_estado_logico = :estado");
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":pgco_id", $pgco_id, \PDO::PARAM_INT);
            $comando->bindParam(":pgco_primer_nombre", $pgco_primer_nombre, \PDO::PARAM_STR);
            $comando->bindParam(":pgco_segundo_nombre", $pgco_segundo_nombre, \PDO::PARAM_STR);
            $comando->bindParam(":pgco_primer_apellido", $pgco_primer_apellido, \PDO::PARAM_STR);
            $comando->bindParam(":pgco_segundo_apellido", $pgco_segundo_apellido, \PDO::PARAM_STR);
            $comando->bindParam(":pgco_correo", $pgco_correo, \PDO::PARAM_STR);
            $comando->bindParam(":pgco_telefono", $pgco_telefono, \PDO::PARAM_STR);
            $comando->bindParam(":pgco_celular", $pgco_celular, \PDO::PARAM_STR);
            $comando->bindParam(":pai_id_contacto", $pai_id_contacto, \PDO::PARAM_INT);
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

    /**     * **
     * Function consultarMaxPergest: Obtiene el id máximo para colocarlo en el campo pges_codigo.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @return
     */
    public function consultarMaxPergest() {
        $con = \Yii::$app->db_crm;
        $estado = '1';
        $sql = "
                    SELECT lpad(ifnull(max(pges_id),0)+1,7,'0') as maximo
                    FROM " . $con->dbname . ".persona_gestion
                    WHERE pges_estado_logico=:estado AND pges_estado=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryOne();
        if (empty($resultData))
            return 0;
        else {
            return $resultData;
        }
    }

    /**
     * Function consultarReportAspirantes.
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     *          Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarReportContactos($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_academico;
        $estado = 1;
        $columnsAdd = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(a.pges_pri_nombre like :search OR ";
            $str_search .= "a.pges_seg_nombre like :search OR ";
            $str_search .= "a.pges_pri_apellido like :search OR ";
            $str_search .= "a.pges_seg_apellido like :search OR ";
            $str_search .= "a.pges_codigo like :search )  AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "a.pges_fecha_creacion >= :fec_ini AND ";
                $str_search .= "a.pges_fecha_creacion <= :fec_fin AND ";
            }
            if ($arrFiltro['medio'] != "" && $arrFiltro['medio'] > 0) {
                $str_search .= " a.ccan_id = :medio AND ";
            }
            if ($arrFiltro['agente'] != "" && $arrFiltro['agente'] > 0) {
                $str_search .= "a.pges_usuario_ingreso = :agente AND ";
            }
            if ($arrFiltro['correo'] != "") {
                $str_search .= "a.pges_correo like :correo AND ";
            }
            if ($arrFiltro['telefono'] != "") {
                $str_search .= "(a.pges_celular like :telefono OR ";
                $str_search .= "a.pges_domicilio_telefono like :telefono )  AND ";
            }
            if ($arrFiltro['empresa'] != "" && $arrFiltro['empresa'] > 0) {
                $str_search .= "a.emp_id = :empresa AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "a.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['gestion'] != "" && $arrFiltro['gestion'] > 0) {
                $str_search .= "a.gestion = :gestion AND ";
            }
        } else {
            $columnsAdd = "
                pg.pges_pri_nombre as pges_pri_nombre,
                pg.pges_seg_nombre as pges_seg_nombre,
                pg.pges_pri_apellido as pges_pri_apellido,
                pg.pges_seg_apellido as pges_seg_apellido,";
        }
        $sql = "SELECT  contacto,
                        pais,
                        pges_correo,
                        pges_celular,
                        pges_domicilio_telefono,
                        fecha_creacion,
                        unidad_academica,
                        canal,
                        usuario_ing,
                        oportunidad_abiertas,
                        oportunidad_cerradas,
                        case when (gestion=1) then 'Pendiente Gestionar' else 'Gestionado' end as gestion
                FROM (
                SELECT
                        concat(ifnull(pges_pri_nombre,''), ' ',ifnull(pges_seg_nombre,' '),ifnull(pges_pri_apellido,''), ' ', ifnull(pges_seg_apellido,' ')) as contacto,
                        ifnull((select pai.pai_nombre from " . $con1->dbname . ".pais pai where pai.pai_id = pg.pai_id_nacimiento),'') as pais,
                        pges_pri_nombre,
                        pges_seg_nombre,
                        pges_pri_apellido,
                        pges_seg_apellido,
                        pg.pges_correo,
                        pg.pges_celular,
                        pg.pges_domicilio_telefono,
                        DATE(pg.pges_fecha_creacion) as fecha_creacion,
                        pg.pges_fecha_creacion,
                        ifnull(uaca.uaca_nombre,'Sin Unidad') as unidad_academica,
                        pg.pges_codigo,
                        pg.ccan_id,
                        cc.ccan_nombre as canal,
                        pg.pges_usuario_ingreso,
                        emp.emp_id,
                        uaca.uaca_id,
                        ifnull((select concat(pers.per_pri_nombre, ' ', ifnull(pers.per_pri_apellido,' '))
                                  from " . $con1->dbname . ".usuario usu
                                  inner join " . $con1->dbname . ".persona pers on pers.per_id = usu.usu_id
                                  where usu.usu_id = pg.pges_usuario_ingreso),'') as usuario_ing,
                        (select count(*) from " . $con->dbname . ".oportunidad o where o.pges_id = pg.pges_id and o.eopo_id in(1,2,3) and o.opo_estado = :estado and o.opo_estado_logico = :estado) as oportunidad_abiertas,
                        (select count(*) from " . $con->dbname . ".oportunidad o where o.pges_id = pg.pges_id and o.eopo_id in(4,5) and o.opo_estado = :estado and o.opo_estado_logico = :estado) as oportunidad_cerradas,
                        case when (select ifnull(count(ba.bact_id),0)
                            from db_crm.oportunidad o -- anna
                            inner join db_crm.bitacora_actividades ba on ba.opo_id = o.opo_id
                            inner join db_asgard.usua_grol_eper uge on uge.usu_id = ba.usu_id
                            where o.pges_id = pg.pges_id
                            -- and ba.eopo_id = 3
                            and o.opo_estado = '1'
                            and o.opo_estado_logico = '1') < 2 then 1 else 2 end as gestion
                        /*(select
                            case  count(ba.bact_id)
                               when 0 then 1
                               when 1 and (o.eopo_id <> 3) then 1
                               when 1 and (o.eopo_id = 3) then 2
                               when 2 then 2
                            end as bact_id
                            from db_crm.oportunidad o
                            inner join db_crm.bitacora_actividades ba on ba.opo_id = o.opo_id
                            inner join db_asgard.usua_grol_eper uge on uge.usu_id = ba.usu_id
                            where o.pges_id = pg.pges_id
                            and o.opo_estado = :estado
                            and o.opo_estado_logico = :estado
                            group by o.eopo_id) as gestion*/

                FROM " . $con->dbname . ".persona_gestion pg inner join " . $con->dbname . ".estado_contacto ec on ec.econ_id = pg.econ_id
                INNER JOIN " . $con1->dbname . ".tipo_persona tp on tp.tper_id = pg.tper_id
                INNER JOIN " . $con->dbname . ".conocimiento_canal cc on cc.ccan_id = pg.ccan_id
                 left JOIN (
                    select max(opo.opo_id) as opo_id,opo.pges_id
                    from " . $con->dbname . ".oportunidad as opo
                    group by opo.pges_id
                ) AS max_opor on max_opor.pges_id=pg.pges_id
                left JOIN " . $con->dbname . ".oportunidad opo on opo.opo_id = max_opor.opo_id
                LEFT JOIN " . $con1->dbname . ".empresa as emp on emp.emp_id=opo.emp_id
                LEFT JOIN " . $con2->dbname . ".unidad_academica uaca on uaca.uaca_id = opo.uaca_id
                WHERE
                        pg.pges_estado = :estado
                        and pg.pges_estado_logico = :estado
                        and tp.tper_estado = :estado
                        and tp.tper_estado_logico = :estado
                        and ec.econ_estado = :estado
                        and ec.econ_estado_logico = :estado
                ORDER BY pg.pges_fecha_creacion desc) a ";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $sql .= "WHERE $str_search
                           a.pges_codigo = a.pges_codigo";
        }

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['medio'] != "" && $arrFiltro['medio'] > 0) {
                $medio = $arrFiltro["medio"];
                $comando->bindParam(":medio", $medio, \PDO::PARAM_INT);
            }
            if ($arrFiltro['agente'] != "" && $arrFiltro['agente'] > 0) {
                $agente = $arrFiltro["agente"];
                $comando->bindParam(":agente", $agente, \PDO::PARAM_INT);
            }
            if ($arrFiltro['correo'] != "") {
                $search_cro = "%" . $arrFiltro["correo"] . "%";
                $comando->bindParam(":correo", $search_cro, \PDO::PARAM_STR);
            }
            if ($arrFiltro['telefono'] != "") {
                $search_tfn = "%" . $arrFiltro["telefono"] . "%";
                $comando->bindParam(":telefono", $search_tfn, \PDO::PARAM_STR);
            }
            if ($arrFiltro['empresa'] != "" && $arrFiltro['empresa'] > 0) {
                $search_emp = $arrFiltro["empresa"];
                $comando->bindParam(":empresa", $search_emp, \PDO::PARAM_INT);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['gestion'] != "" && $arrFiltro['gestion'] > 0) {
                $search_gest = $arrFiltro["gestion"];
                $comando->bindParam(":gestion", $search_gest, \PDO::PARAM_INT);
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

    /**     * **
     * Function Obtiene grol_id a partir de Id Persona y Empresa
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function consultarIdPersonaAdmision($per_id, $grol_id) {
        $con = \Yii::$app->db_crm;
        $sql = "SELECT A.padm_id FROM " . $con->dbname . ".personal_admision A
                    WHERE A.padm_estado=1 AND A.padm_estado_logico=1
                            AND A.per_id=:per_id AND A.grol_id=:grol_id;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":grol_id", $grol_id, \PDO::PARAM_INT);
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0; //Falso si no Existe
        return $rawData; //Si Existe en la Tabla
    }

    /**     * **
     * Function Existe Persona Gestion
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @property
     * @return
     */
    public static function existePersonaGestion($correo, $numero) {
        //pgest_nombre,pgest_numero,pgest_correo
        $con = \Yii::$app->db_crm;
        $sql = "SELECT pges_id Ids
                    FROM " . $con->dbname . ".persona_gestion
                WHERE pges_estado_logico=1 AND (pges_correo=:pges_correo OR pges_celular=:pges_celular)";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":pges_correo", $correo, \PDO::PARAM_STR);
        $comando->bindParam(":pges_celular", $numero, \PDO::PARAM_STR);
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0;
        return $rawData; //Si Existe en la Tabla
    }

}
