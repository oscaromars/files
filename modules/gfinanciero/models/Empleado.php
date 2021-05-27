<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "empleado".
 *
 * @property string $empl_codigo
 * @property int $sdep_id
 * @property int|null $per_id
 * @property int|null $tcon_id
 * @property int|null $dis_id
 * @property int $tipe_id
 * @property int $tipc_id
 * @property string|null $empl_cod_vendedor
 * @property string|null $empl_cedula_ruc
 * @property string|null $empl_nombre
 * @property string|null $empl_apellido
 * @property string|null $empl_fecha_nacimiento
 * @property string|null $empl_direccion
 * @property string|null $empl_telefono
 * @property string|null $empl_telefono_movil
 * @property int|null $empl_carga_familiar
 * @property string|null $empl_genero
 * @property int|null $empl_ids_ban
 * @property string|null $empl_metodo_pago
 * @property string|null $empl_cuenta_bancaria
 * @property string|null $empl_cuenta_contable
 * @property string|null $empl_fecha_ingreso
 * @property string|null $empl_fecha_salida
 * @property string|null $empl_fecha_seguro_social
 * @property string|null $empl_estado_civil
 * @property string|null $empl_cuenta_catalogo
 * @property string|null $empl_fondo_reserva
 * @property string|null $empl_decimo_tercero
 * @property string|null $empl_decimo_cuarto
 * @property string|null $empl_paga_sobretiempo
 * @property string|null $empl_ruta_foto
 * @property string|null $empl_ruta_cedula
 * @property string|null $empl_ruta_contrato
 * @property string|null $empl_ruta_aviso_entrada
 * @property string|null $empl_email_notificacion
 * @property string|null $empl_porcentaje_discapacidad
 * @property int|null $empl_usuario_ingreso
 * @property int|null $empl_usuario_modifica
 * @property string|null $empl_estado
 * @property string|null $empl_fecha_creacion
 * @property string|null $empl_fecha_modificacion
 * @property string|null $empl_estado_logico
 *
 * @property SubDepartamento $sdep
 * @property SubcentroEmpleado[] $subcentroEmpleados
 */
class Empleado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empleado';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gfinanciero');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empl_codigo', 'sdep_id', 'tipe_id', 'tipc_id'], 'required'],
            [['sdep_id', 'per_id', 'dis_id', 'tipe_id', 'tipc_id', 'empl_carga_familiar', 'empl_ids_ban', 'empl_usuario_ingreso', 'empl_usuario_modifica'], 'integer'],
            [['empl_fecha_nacimiento', 'empl_fecha_ingreso', 'empl_fecha_salida', 'empl_fecha_seguro_social', 'empl_fecha_creacion', 'empl_fecha_modificacion'], 'safe'],
            [['empl_codigo', 'empl_cedula_ruc', 'empl_telefono', 'empl_telefono_movil', 'empl_cuenta_catalogo'], 'string', 'max' => 20],
            [['empl_cod_vendedor', 'empl_metodo_pago', 'empl_porcentaje_discapacidad'], 'string', 'max' => 3],
            [['empl_nombre', 'empl_apellido', 'empl_direccion'], 'string', 'max' => 200],
            [['empl_genero', 'empl_fondo_reserva', 'empl_decimo_tercero', 'empl_decimo_cuarto', 'empl_paga_sobretiempo', 'empl_estado', 'empl_estado_logico'], 'string', 'max' => 1],
            [['empl_cuenta_bancaria'], 'string', 'max' => 45],
            [['empl_cuenta_contable'], 'string', 'max' => 15],
            [['empl_estado_civil'], 'string', 'max' => 30],
            [['tcon_id'], 'string', 'max' => 2],
            [['empl_ruta_foto', 'empl_ruta_cedula', 'empl_ruta_contrato', 'empl_ruta_aviso_entrada', 'empl_email_notificacion'], 'string', 'max' => 100],
            [['empl_codigo'], 'unique'],
            [['sdep_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubDepartamento::className(), 'targetAttribute' => ['sdep_id' => 'sdep_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'empl_codigo' => 'Empl Codigo',
            'sdep_id' => 'Sdep ID',
            'per_id' => 'Per ID',
            'tcon_id' => 'Tcon ID',
            'dis_id' => 'Dis ID',
            'tipe_id' => 'Tipe ID',
            'tipc_id' => 'Tipc ID',
            'empl_cod_vendedor' => financiero::t('empleado', 'Employee Code'),
            'empl_cedula_ruc' => 'Empl Cedula Ruc',
            'empl_nombre' => 'Empl Nombre',
            'empl_apellido' => 'Empl Apellido',
            'empl_fecha_nacimiento' => 'Empl Fecha Nacimiento',
            'empl_direccion' => 'Empl Direccion',
            'empl_telefono' => 'Empl Telefono',
            'empl_telefono_movil' => 'Empl Telefono Movil',
            'empl_carga_familiar' => 'Empl Carga Familiar',
            'empl_genero' => 'Empl Genero',
            'empl_ids_ban' => 'Empl Ids Ban',
            'empl_metodo_pago' => 'Empl Metodo Pago',
            'empl_cuenta_bancaria' => 'Empl Cuenta Bancaria',
            'empl_cuenta_contable' => 'Empl Cuenta Contable',
            'empl_fecha_ingreso' => 'Empl Fecha Ingreso',
            'empl_fecha_salida' => 'Empl Fecha Salida',
            'empl_fecha_seguro_social' => 'Empl Fecha Seguro Social',
            'empl_estado_civil' => 'Empl Estado Civil',
            'empl_cuenta_catalogo' => 'Empl Cuenta Catalogo',
            'empl_fondo_reserva' => 'Empl Fondo Reserva',
            'empl_decimo_tercero' => 'Empl Decimo Tercero',
            'empl_decimo_cuarto' => 'Empl Decimo Cuarto',
            'empl_paga_sobretiempo' => 'Empl Paga Sobretiempo',
            'empl_ruta_foto' => 'Empl Ruta Foto',
            'empl_ruta_cedula' => 'Empl Ruta Cedula',
            'empl_ruta_contrato' => 'Empl Ruta Contrato',
            'empl_ruta_aviso_entrada' => 'Empl Ruta Aviso Entrada',
            'empl_email_notificacion' => 'Empl Email Notificacion',
            'empl_porcentaje_discapacidad' => 'Empl Porcentaje Discapacidad',
            'empl_usuario_ingreso' => 'Empl Usuario Ingreso',
            'empl_usuario_modifica' => 'Empl Usuario Modifica',
            'empl_estado' => 'Empl Estado',
            'empl_fecha_creacion' => 'Empl Fecha Creacion',
            'empl_fecha_modificacion' => 'Empl Fecha Modificacion',
            'empl_estado_logico' => 'Empl Estado Logico',
        ];
    }

    /**
     * Gets query for [[Sdep]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdep()
    {
        return $this->hasOne(SubDepartamento::className(), ['sdep_id' => 'sdep_id']);
    }

    /**
     * Gets query for [[SubcentroEmpleados]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubcentroEmpleados()
    {
        return $this->hasMany(SubcentroEmpleado::className(), ['empl_codigo' => 'empl_codigo']);
    }

    /**
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Item Name
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
    public function getAllItemsGrid($search, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;
        $con2 = Yii::$app->db;
        $emp_id = Yii::$app->session->get("PB_idempresa");
        $gru_estudiante = 12;
        $gru_interesado = 10;
        $gru_aspirante = 11;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(e.empl_nombre like :search OR e.empl_apellido like :search) AND ";
        }
        $cols  = "e.empl_codigo as Id, e.empl_nombre as Nombre, e.empl_apellido as Apellido, ";
        $cols .= "e.empl_cedula_ruc as dni, e.empl_genero as Genero, ec.eciv_nombre as ECivil, ";
        $cols .= "e.empl_fecha_ingreso as FIngreso, de.dep_nombre as Departamento, sd.sdep_nombre as SubDepartamento, ";
        $cols .= "d.dis_nombre as DisId, IFNULL(d.dis_nombre, '') as Discapacidad, IFNULL(e.empl_porcentaje_discapacidad, '') as PDiscapacidad ";

        if($export){
            $cols  = "e.empl_nombre as Nombre, e.empl_apellido as Apellido, ";
            $cols .= "e.empl_cedula_ruc as dni, e.empl_genero as Genero, ec.eciv_nombre as ECivil, ";
            $cols .= "e.empl_fecha_ingreso as FIngreso, de.dep_nombre as Departamento, sd.sdep_nombre as SubDepartamento, ";
            $cols .= "IFNULL(d.dis_nombre, '') as Discapacidad, IFNULL(e.empl_porcentaje_discapacidad, '') as PDiscapacidad ";
        } 
        $sql = "SELECT 
                    $cols 
                FROM 
                    ".$con->dbname.".empleado AS e 
                    INNER JOIN ".$con->dbname.".sub_departamento AS sd ON sd.sdep_id = e.sdep_id 
                    INNER JOIN ".$con->dbname.".departamentos AS de ON de.dep_id = sd.dep_id 
                    INNER JOIN ".$con2->dbname.".persona AS p ON e.per_id = p.per_id 
                    INNER JOIN ".$con2->dbname.".empresa_persona AS ep ON ep.per_id = p.per_id
                    INNER JOIN ".$con2->dbname.".usua_grol_eper AS ug ON ug.eper_id = ep.eper_id
                    INNER JOIN ".$con2->dbname.".grup_rol AS gr ON gr.grol_id = ug.grol_id
                    INNER JOIN ".$con2->dbname.".estado_civil AS ec ON ec.eciv_id = e.empl_estado_civil
                    LEFT JOIN ".$con->dbname.".discapacidad AS d ON d.dis_id = e.dis_id AND d.dis_estado_logico = 1 AND d.dis_estado = 1 
                WHERE 
                    $str_search
                    ep.emp_id = :emp_id AND 
                    gr.gru_id NOT IN (:gru1, :gru2, :gru3) AND 
                    p.per_estado_logico = 1 AND p.per_estado = 1 AND 
                    e.empl_estado_logico = 1 AND e.empl_estado = 1 AND 
                    ug.ugep_estado = 1 AND ug.ugep_estado_logico = 1 
                ORDER BY e.empl_codigo;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $comando->bindParam(":gru1", $gru_estudiante, \PDO::PARAM_INT);
        $comando->bindParam(":gru2", $gru_interesado, \PDO::PARAM_INT);
        $comando->bindParam(":gru3", $gru_aspirante, \PDO::PARAM_INT);

        $result = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $result,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre'],
                ],
            ]);
            return $dataProvider;
        }
        return $result;
    }

    /**
     * Return columns to dataset of create a query to widget Search.
     *
     * @return mixed Return a Record Array
     */
    public static function getDataColumnsQueryWidget(){
        $arr_data = [];
        $arr_data['con'] = Yii::$app->db_gfinanciero;
        $arr_data['table'] = "empleado";
        $arr_data['cols'] = [
            "empl_codigo", 
            "CONCAT(empl_apellido, ' ', empl_nombre)",
        ];
        $arr_data['aliasCols'] = [
            financiero::t('empleado', 'Cod'), 
            financiero::t('empleado', 'Employee'),
        ];
        $arr_data['colVisible'] = [
            financiero::t('empleado', 'Cod'), 
            financiero::t('empleado', 'Employee'),
        ];
        $arr_data['where'] = "empl_estado = 1 AND empl_estado_logico = 1";
        $arr_data['order'] = "empl_codigo ASC";
        $arr_data['limitPages'] = Yii::$app->params['pageSize'];
        return $arr_data;
    }

    /**
     * Return columns to dataset of create a query to widget Search.
     *
     * @return mixed Return a Record Array
     */
    public static function getDataPersonaColumnsQueryWidget(){
        $gru_estudiante = 12;
        $gru_interesado = 10;
        $gru_aspirante  = 11;
        $con  = Yii::$app->db_gfinanciero;
        $con2 = Yii::$app->db;

        $from = "persona AS p 
        INNER JOIN ".$con2->dbname.".empresa_persona AS ep ON ep.per_id = p.per_id
        INNER JOIN ".$con2->dbname.".usua_grol_eper AS ug ON ug.eper_id = ep.eper_id
        INNER JOIN ".$con2->dbname.".grup_rol AS gr ON gr.grol_id = ug.grol_id
        LEFT JOIN ".$con->dbname.".empleado AS em ON em.empl_cedula_ruc = p.per_cedula OR em.empl_cedula_ruc <> p.per_ruc OR em.empl_cedula_ruc <> p.per_pasaporte ";

        $arr_data = [];
        $arr_data['con'] = $con2;
        $arr_data['table'] = $from;
        $arr_data['cols'] = [
            "p.per_cedula", 
            "CONCAT(p.per_pri_apellido, ' ', p.per_pri_nombre)",
            //"p.per_pri_apellido",
        ];
        $arr_data['aliasCols'] = [
            financiero::t('empleado', 'Cod'), 
            financiero::t('empleado', 'Employee'),
        ];
        $arr_data['colVisible'] = [
            financiero::t('empleado', 'Cod'), 
            financiero::t('empleado', 'Employee'),
        ];
        //$arr_data['where'] = "p.per_estado = 1 AND p.per_estado_logico = 1 AND gr.gru_id NOT IN ($gru_estudiante, $gru_interesado, $gru_aspirante) AND em.empl_codigo <> NULL ";
        $arr_data['where'] = "p.per_estado = 1 AND p.per_estado_logico = 1 AND gr.gru_id NOT IN ($gru_estudiante, $gru_interesado, $gru_aspirante) AND em.empl_cedula_ruc IS NULL";
        $arr_data['order'] = "p.per_pri_apellido ASC";
        $arr_data['limitPages'] = Yii::$app->params['pageSize'];
        return $arr_data;
    }

    /**
     * Return payment Types.
     *
     * @return mixed Return a Payment Types Array
     */
    public static function getMetodosPagos(){
        return [
            "TRA" => financiero::t('empleado', 'Bank Transfer'), 
            "EFE" => financiero::t('empleado', 'Cash'), 
            "CHE" => financiero::t('empleado', 'Bank Draft'), 
        ];
    }

    /**
     * Return data Employee by Code.
     *
     * @param   string  $code   EmployeeCode
     * @return mixed Return a Data Employee Array
     */
    public static function getPersonaByCode($code){
        $emp_id = Yii::$app->session->get("PB_idempresa");
        $con = Yii::$app->db;
        $con2 = Yii::$app->db_gfinanciero;
        $sql = "SELECT 
                    p.per_id as id,
                    IFNULL(p.per_pri_nombre, '') as pri_nombre,
                    IFNULL(p.per_seg_nombre, '') as seg_nombre,
                    IFNULL(p.per_pri_apellido, '') as pri_apellido,
                    IFNULL(p.per_seg_apellido, '') as seg_apellido,
                    IFNULL(p.per_cedula, '') as cedula,
                    IFNULL(p.per_ruc, '') as ruc,
                    IFNULL(p.per_pasaporte, '') as pasaporte,
                    IFNULL(p.etn_id, 0) as etcnia,
                    IFNULL(p.eciv_id, 0) as estado_civil,
                    IFNULL(p.per_genero, 'M') as genero,
                    IFNULL(p.per_nacionalidad, '') as nacionalidad,
                    IFNULL(p.pai_id_nacimiento, 0) as nac_pais,
                    IFNULL(p.pro_id_nacimiento, 0) as nac_provincia,
                    IFNULL(p.can_id_nacimiento, 0) as nac_ciudad,
                    IFNULL(p.per_nac_ecuatoriano, '') as nac_ecu,
                    IFNULL(p.per_fecha_nacimiento, '') as fecha_nac,
                    IFNULL(p.per_celular, '') as celular,
                    IFNULL(p.per_correo, '') as correo,
                    IFNULL(p.per_foto, '') as foto,
                    IFNULL(p.tsan_id, 0) as tipo_sangre,
                    IFNULL(p.per_domicilio_sector, '') as domicilio_sector,
                    IFNULL(p.per_domicilio_cpri, '') as domicilio_cpri,
                    IFNULL(p.per_domicilio_csec, '') as domicilio_csec,
                    IFNULL(p.per_domicilio_num, '') as domicilio_num,
                    IFNULL(p.per_domicilio_ref, '') as domicilio_referencia,
                    IFNULL(p.per_domicilio_telefono, '') as domicilio_telefono,
                    IFNULL(p.per_domicilio_celular2, '') as domicilio_celular,
                    IFNULL(p.pai_id_domicilio, 0) as domicilio_pais,
                    IFNULL(p.pro_id_domicilio, 0) as domicilio_provincia,
                    IFNULL(p.can_id_domicilio, 0) as domicilio_ciudad,
                    IFNULL(p.per_trabajo_nombre, '') as trabajo_nombre,
                    IFNULL(p.per_trabajo_direccion, '') as trabajo_direccion,
                    IFNULL(p.per_trabajo_telefono, '') as trabajo_telefono,
                    IFNULL(p.per_trabajo_ext, '') as trabajo_extension,
                    IFNULL(u.usu_user, '') as username,
                    g.gru_id as gru_id,
                    r.rol_id as rol_id,
                    ug.grol_id as grol_id,
                    r.rol_nombre as rol,
                    g.gru_nombre as grupo,
                    IFNULL(em.per_id, '0') as existeEmpleado
                FROM 
                    ".$con->dbname.".persona as p
                    INNER JOIN ".$con->dbname.".empresa_persona as ep ON ep.per_id = p.per_id
                    INNER JOIN ".$con->dbname.".empresa as e ON e.emp_id = ep.emp_id
                    INNER JOIN ".$con->dbname.".usua_grol_eper as ug ON ug.eper_id = ep.eper_id
                    INNER JOIN ".$con->dbname.".usuario as u ON u.usu_id = ug.usu_id
                    INNER JOIN ".$con->dbname.".grup_rol as gr ON gr.grol_id = ug.grol_id
                    INNER JOIN ".$con->dbname.".grupo as g ON g.gru_id = gr.gru_id
                    INNER JOIN ".$con->dbname.".rol as r ON r.rol_id = gr.rol_id
                    LEFT JOIN ".$con2->dbname.".empleado as em ON em.per_id = p.per_id
                WHERE 
                    e.emp_id = :emp_id AND 
                    (p.per_cedula = :code OR p.per_pasaporte = :code OR p.per_ruc = :code) AND
                    p.per_estado_logico = '1' AND p.per_estado = '1'
                LIMIT 1;";
        //// Code End
        $comando = $con->createCommand($sql);
        $comando->bindParam(":code", $code, \PDO::PARAM_STR);
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        return $comando->queryOne();
    }

}
