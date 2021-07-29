<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "persona".
 *
 * @property int $per_id
 * @property string $per_pri_nombre
 * @property string $per_seg_nombre
 * @property string $per_pri_apellido
 * @property string $per_seg_apellido
 * @property string $per_cedula
 * @property string $per_ruc
 * @property string $per_pasaporte
 * @property int $etn_id
 * @property int $eciv_id
 * @property string $per_genero
 * @property string $per_nacionalidad
 * @property int $pai_id_nacimiento
 * @property int $pro_id_nacimiento
 * @property int $can_id_nacimiento
 * @property string $per_nac_ecuatoriano
 * @property string $per_fecha_nacimiento
 * @property string $per_celular
 * @property string $per_correo
 * @property string $per_foto
 * @property int $tsan_id
 * @property string $per_domicilio_sector
 * @property string $per_domicilio_cpri
 * @property string $per_domicilio_csec
 * @property string $per_domicilio_num
 * @property string $per_domicilio_ref
 * @property string $per_domicilio_telefono
 * @property string $per_domicilio_celular2
 * @property int $pai_id_domicilio
 * @property int $pro_id_domicilio
 * @property int $can_id_domicilio
 * @property string $per_trabajo_nombre
 * @property string $per_trabajo_direccion
 * @property string $per_trabajo_telefono
 * @property string $per_trabajo_ext
 * @property int $pai_id_trabajo
 * @property int $pro_id_trabajo
 * @property int $can_id_trabajo
 * @property int $per_usuario_ingresa
 * @property int $per_usuario_modifica
 * @property string $per_estado
 * @property string $per_fecha_creacion
 * @property string $per_fecha_modificacion
 * @property string $per_estado_logico
 *
 * @property Pais $paiIdNacimiento
 * @property TipoSangre $tsan
 * @property Provincia $proIdNacimiento
 * @property Pais $paiIdDomicilio
 * @property Provincia $proIdDomicilio
 * @property Canton $canIdDomicilio
 * @property Pais $paiIdTrabajo
 * @property Provincia $proIdTrabajo
 * @property Canton $canIdTrabajo
 * @property Etnia $etn
 * @property PersonaContacto[] $personaContactos
 * @property Usuario[] $usuarios
 */
class Persona extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'persona';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_asgard');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['per_cedula', 'per_estado', 'per_estado_logico'], 'required'],
            [['etn_id', 'eciv_id', 'pai_id_nacimiento', 'pro_id_nacimiento', 'can_id_nacimiento', 'tsan_id', 'pai_id_domicilio', 'pro_id_domicilio', 'can_id_domicilio', 'pai_id_trabajo', 'pro_id_trabajo', 'can_id_trabajo', 'per_usuario_ingresa', 'per_usuario_modifica'], 'integer'],
            [['per_fecha_nacimiento', 'per_fecha_creacion', 'per_fecha_modificacion'], 'safe'],
            [['per_pri_nombre', 'per_seg_nombre', 'per_pri_apellido', 'per_seg_apellido', 'per_nacionalidad', 'per_correo', 'per_domicilio_sector', 'per_trabajo_nombre'], 'string', 'max' => 250],
            [['per_cedula', 'per_ruc'], 'string', 'max' => 15],
            [['per_pasaporte', 'per_celular', 'per_domicilio_telefono', 'per_domicilio_celular2', 'per_trabajo_telefono', 'per_trabajo_ext'], 'string', 'max' => 50],
            [['per_genero', 'per_nac_ecuatoriano', 'per_estado', 'per_estado_logico'], 'string', 'max' => 1],
            [['per_foto', 'per_domicilio_cpri', 'per_domicilio_csec', 'per_domicilio_ref', 'per_trabajo_direccion'], 'string', 'max' => 500],
            [['per_domicilio_num'], 'string', 'max' => 100],
            [['per_cedula'], 'unique'],
            [['pai_id_nacimiento'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::className(), 'targetAttribute' => ['pai_id_nacimiento' => 'pai_id']],
            [['tsan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoSangre::className(), 'targetAttribute' => ['tsan_id' => 'tsan_id']],
            [['pro_id_nacimiento'], 'exist', 'skipOnError' => true, 'targetClass' => Provincia::className(), 'targetAttribute' => ['pro_id_nacimiento' => 'pro_id']],
            [['pai_id_domicilio'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::className(), 'targetAttribute' => ['pai_id_domicilio' => 'pai_id']],
            [['pro_id_domicilio'], 'exist', 'skipOnError' => true, 'targetClass' => Provincia::className(), 'targetAttribute' => ['pro_id_domicilio' => 'pro_id']],
            [['can_id_domicilio'], 'exist', 'skipOnError' => true, 'targetClass' => Canton::className(), 'targetAttribute' => ['can_id_domicilio' => 'can_id']],
            [['pai_id_trabajo'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::className(), 'targetAttribute' => ['pai_id_trabajo' => 'pai_id']],
            [['pro_id_trabajo'], 'exist', 'skipOnError' => true, 'targetClass' => Provincia::className(), 'targetAttribute' => ['pro_id_trabajo' => 'pro_id']],
            [['can_id_trabajo'], 'exist', 'skipOnError' => true, 'targetClass' => Canton::className(), 'targetAttribute' => ['can_id_trabajo' => 'can_id']],
            [['etn_id'], 'exist', 'skipOnError' => true, 'targetClass' => Etnia::className(), 'targetAttribute' => ['etn_id' => 'etn_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'per_id' => 'Per ID',
            'per_pri_nombre' => 'Per Pri Nombre',
            'per_seg_nombre' => 'Per Seg Nombre',
            'per_pri_apellido' => 'Per Pri Apellido',
            'per_seg_apellido' => 'Per Seg Apellido',
            'per_cedula' => 'Per Cedula',
            'per_ruc' => 'Per Ruc',
            'per_pasaporte' => 'Per Pasaporte',
            'etn_id' => 'Etn ID',
            'eciv_id' => 'Eciv ID',
            'per_genero' => 'Per Genero',
            'per_nacionalidad' => 'Per Nacionalidad',
            'pai_id_nacimiento' => 'Pai Id Nacimiento',
            'pro_id_nacimiento' => 'Pro Id Nacimiento',
            'can_id_nacimiento' => 'Can Id Nacimiento',
            'per_nac_ecuatoriano' => 'Per Nac Ecuatoriano',
            'per_fecha_nacimiento' => 'Per Fecha Nacimiento',
            'per_celular' => 'Per Celular',
            'per_correo' => 'Per Correo',
            'per_foto' => 'Per Foto',
            'tsan_id' => 'Tsan ID',
            'per_domicilio_sector' => 'Per Domicilio Sector',
            'per_domicilio_cpri' => 'Per Domicilio Cpri',
            'per_domicilio_csec' => 'Per Domicilio Csec',
            'per_domicilio_num' => 'Per Domicilio Num',
            'per_domicilio_ref' => 'Per Domicilio Ref',
            'per_domicilio_telefono' => 'Per Domicilio Telefono',
            'per_domicilio_celular2' => 'Per Domicilio Celular2',
            'pai_id_domicilio' => 'Pai Id Domicilio',
            'pro_id_domicilio' => 'Pro Id Domicilio',
            'can_id_domicilio' => 'Can Id Domicilio',
            'per_trabajo_nombre' => 'Per Trabajo Nombre',
            'per_trabajo_direccion' => 'Per Trabajo Direccion',
            'per_trabajo_telefono' => 'Per Trabajo Telefono',
            'per_trabajo_ext' => 'Per Trabajo Ext',
            'pai_id_trabajo' => 'Pai Id Trabajo',
            'pro_id_trabajo' => 'Pro Id Trabajo',
            'can_id_trabajo' => 'Can Id Trabajo',
            'per_usuario_ingresa' => 'Per Usuario Ingresa',
            'per_usuario_modifica' => 'Per Usuario Modifica',
            'per_estado' => 'Per Estado',
            'per_fecha_creacion' => 'Per Fecha Creacion',
            'per_fecha_modificacion' => 'Per Fecha Modificacion',
            'per_estado_logico' => 'Per Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaiIdNacimiento() {
        return $this->hasOne(Pais::className(), ['pai_id' => 'pai_id_nacimiento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTsan() {
        return $this->hasOne(TipoSangre::className(), ['tsan_id' => 'tsan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProIdNacimiento() {
        return $this->hasOne(Provincia::className(), ['pro_id' => 'pro_id_nacimiento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaiIdDomicilio() {
        return $this->hasOne(Pais::className(), ['pai_id' => 'pai_id_domicilio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProIdDomicilio() {
        return $this->hasOne(Provincia::className(), ['pro_id' => 'pro_id_domicilio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCanIdDomicilio() {
        return $this->hasOne(Canton::className(), ['can_id' => 'can_id_domicilio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaiIdTrabajo() {
        return $this->hasOne(Pais::className(), ['pai_id' => 'pai_id_trabajo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProIdTrabajo() {
        return $this->hasOne(Provincia::className(), ['pro_id' => 'pro_id_trabajo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCanIdTrabajo() {
        return $this->hasOne(Canton::className(), ['can_id' => 'can_id_trabajo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtn() {
        return $this->hasOne(Etnia::className(), ['etn_id' => 'etn_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaContactos() {
        return $this->hasMany(PersonaContacto::className(), ['per_id' => 'per_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios() {
        return $this->hasMany(Usuario::className(), ['per_id' => 'per_id']);
    }

    /**
     * Function findIdentity
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * Function findByCondition
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public static function findByCondition($condition) {
        return parent::findByCondition($condition);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
 public function getProfesor() {
        return $this->hasMany(\app\modules\academico\models\Profesor::className(), ['per_id' => 'per_id']);
    }

    public static function getPersonas() {
        $con = \Yii::$app->db;

        $sql = "SELECT
                  P.per_pri_nombre AS P_Nombre, 
                  P.per_pri_apellido AS P_Apellido 
                
                FROM " . $con->dbname . ".persona P 
                WHERE 
                  P.per_estado_logico=:estado";
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
                'attributes' => ['P_Nombre', 'P_Apellido'],
            ],
        ]);
        return $dataProvider;
    }

    /**
     * Function consultaPersonaId
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $perid       
     * @return  
     */
    public function consultaPersonaId($perid) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $estadoinactivo = 0;

        $sql = "SELECT
                   usu.usu_id,
                   usu.usu_user,
                   per.per_pri_nombre, 
                   per.per_seg_nombre, 
                   per.per_pri_apellido, 
                   per.per_seg_apellido, 
                   per.per_cedula, 
                   per.per_genero, 
                   per.etn_id,                
                   (case when ifnull((select etn.etn_descripcion                                      
                    from " . $con->dbname . ".etnia etn
                    where etn.etn_id = per.etn_id AND
                          etn.etn_estado = :estado AND
                          etn.etn_estado_logico = :estado ),'') ='' then '' 
                               else (select etn.etn_descripcion                                      
                    from " . $con->dbname . ".etnia etn
                    where etn.etn_id = per.etn_id AND
                          etn.etn_estado = :estado AND
                          etn.etn_estado_logico = :estado) end )as etn_descripcion,
                           '' as etn_descripcion,    
                   per.per_fecha_nacimiento, 
                   can_id_nacimiento, 
                   per.eciv_id, 
                   (case when ifnull((select eci.eciv_nombre                                     
                    from " . $con->dbname . ".estado_civil eci 
                    where eci.eciv_id = per.eciv_id AND
                          eci.eciv_estado = :estado AND
                          eci.eciv_estado_logico = :estado ),'') ='' then '' 
                               else (select eci.eciv_nombre                                      
                    from " . $con->dbname . ".estado_civil eci 
                    where eci.eciv_id = per.eciv_id  AND
                          eci.eciv_estado = :estado AND
                          eci.eciv_estado_logico = :estado ) end )as eciv_descripcion,
                           '' as eciv_descripcion, 
                   per.per_correo, 
                   per.per_celular,                   
                   per.tsan_id, 
                   (case when ifnull((select tsa.tsan_nombre                                     
                    from " . $con->dbname . ".tipo_sangre tsa 
                    where tsa.tsan_id = per.tsan_id AND
                          tsa.tsan_estado = :estado AND
                          tsa.tsan_estado_logico = :estado ),'') ='' then '' 
                               else (select tsa.tsan_nombre                                      
                    from " . $con->dbname . ".tipo_sangre tsa 
                    where tsa.tsan_id = per.tsan_id AND
                          tsa.tsan_estado = :estado AND
                          tsa.tsan_estado_logico = :estado ) end )as tsan_nombre,
                    '' as tsan_nombre,
                    per.pai_id_domicilio, 
                    per.pro_id_domicilio, 
                    per.can_id_domicilio, 
                    per.per_domicilio_telefono, 
                    per.per_domicilio_csec as secundaria , 
                    per.per_domicilio_cpri, 
                    per.per_domicilio_sector as sector, 
                    per.per_domicilio_num, 
                    per.per_domicilio_ref, 
                    per.pai_id_nacimiento, 
                    per.pro_id_nacimiento, 
                    per.can_id_nacimiento, 
                    per.per_id,
                    per.per_nac_ecuatoriano,
                    per.per_nacionalidad,
                    per.per_pasaporte,
                    per.per_foto
                   
                FROM 
                   " . $con->dbname . ".usuario usu
                   INNER JOIN " . $con->dbname . ".persona per ON per.per_id = usu.per_id                
                   
                WHERE 
                   usu.per_id = :perid  AND
                   usu.usu_estado = :estado AND
                   usu.usu_estado_logico = :estado AND
                   per.per_estado = :estado AND
                   per.per_estado_logico = :estado
                UNION
                SELECT 
                   usu.usu_id,
                   usu.usu_user,
                   per.per_pri_nombre, 
                   per.per_seg_nombre, 
                   per.per_pri_apellido, 
                   per.per_seg_apellido, 
                   per.per_cedula, 
                   per.per_genero, 
                   per.etn_id,                    
                   (case when ifnull((select etn.etn_descripcion                                      
                    from " . $con->dbname . ".etnia etn
                    where etn.etn_id = per.etn_id AND
                          etn.etn_estado = :estado AND
                          etn.etn_estado_logico = :estado ),'') ='' then '' 
                               else (select etn.etn_descripcion                                      
                    from " . $con->dbname . ".etnia etn
                    where etn.etn_id = per.etn_id AND
                          etn.etn_estado = :estado AND
                          etn.etn_estado_logico = :estado) end )as etn_descripcion,
                           '' as etn_descripcion,    
                   per.per_fecha_nacimiento, 
                   can_id_nacimiento, 
                   per.eciv_id, 
                   (case when ifnull((select eci.eciv_nombre                                     
                    from " . $con->dbname . ".estado_civil eci 
                    where eci.eciv_id = per.eciv_id AND
                          eci.eciv_estado = :estado AND
                          eci.eciv_estado_logico = :estado ),'') ='' then '' 
                               else (select eci.eciv_nombre                                      
                    from " . $con->dbname . ".estado_civil eci 
                    where eci.eciv_id = per.eciv_id  AND
                          eci.eciv_estado = :estado AND
                          eci.eciv_estado_logico = :estado ) end )as eciv_descripcion,
                           '' as eciv_descripcion, 
                   per.per_correo, 
                   per.per_celular, 
                   per.tsan_id, 
                   (case when ifnull((select tsa.tsan_nombre                                     
                    from " . $con->dbname . ".tipo_sangre tsa 
                    where tsa.tsan_id = per.tsan_id AND
                          tsa.tsan_estado = :estado AND
                          tsa.tsan_estado_logico = :estado ),'') ='' then '' 
                               else (select tsa.tsan_nombre                                      
                    from " . $con->dbname . ".tipo_sangre tsa 
                    where tsa.tsan_id = per.tsan_id AND
                          tsa.tsan_estado = :estado AND
                          tsa.tsan_estado_logico = :estado ) end )as tsan_nombre,
                    '' as tsan_nombre,
                   per.pai_id_domicilio, 
                   per.pro_id_domicilio, 
                   per.can_id_domicilio, 
                   per.per_domicilio_telefono, 
                   per.per_domicilio_csec as secundaria , 
                   per.per_domicilio_cpri, 
                   per.per_domicilio_sector as sector, 
                   per.per_domicilio_num, 
                   per.per_domicilio_ref, 
                   per.pai_id_nacimiento, 
                   per.pro_id_nacimiento, 
                   per.can_id_nacimiento, 
                   per.per_id,
                   per.per_nac_ecuatoriano,
                   per.per_nacionalidad,
                   per.per_pasaporte,
                   per.per_foto

                    FROM 
                    " . $con->dbname . ".usuario usu
                    INNER JOIN " . $con->dbname . ".persona per ON per.per_id = usu.per_id 
                    WHERE 
                    usu.per_id = :perid  AND
                    usu.usu_link_activo <> '' AND
                    usu.usu_estado = :estadoinactivo AND
                    usu.usu_estado_logico = :estado AND
                    per.per_estado = :estado AND
                    per.per_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":perid", $perid, \PDO::PARAM_INT);
        $comando->bindParam(":estadoinactivo", $estadoinactivo, \PDO::PARAM_STR);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function insertarPersona
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    public function insertarPersona($con, $parameters, $keys, $name_table) {
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
            \app\models\Utilities::putMessageLogFile('insert persona:'.$sql);
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

    /**
     * Function modificaPersona
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $userid       
     * @return  
     */
    public function modificaPersona($per_id, $per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $etn_id, $eciv_id, $per_genero, $pai_id_nacimiento, $pro_id_nacimiento, $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $tsan_id, $per_domicilio_sector, $per_domicilio_cpri, $per_domicilio_csec, $per_domicilio_num, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nac_ecuatoriano, $per_nacionalidad, $per_foto) {
        $con = \Yii::$app->db_asgard;
        $usuario_modifica = @Yii::$app->session->get("PB_iduser");
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        $estado = 1;
        $per_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".persona            
                      SET 
                        per_pri_nombre = :per_pri_nombre,
                        per_seg_nombre = :per_seg_nombre,
                        per_pri_apellido = :per_pri_apellido,
                        per_seg_apellido = :per_seg_apellido,
                        etn_id = :etn_id,                        
                        eciv_id = :eciv_id,
                        per_genero = :per_genero,
                        pai_id_nacimiento = :pai_id_nacimiento,
                        pro_id_nacimiento = :pro_id_nacimiento,
                        can_id_nacimiento = :can_id_nacimiento,
                        per_fecha_nacimiento = :per_fecha_nacimiento,
                        per_celular = :per_celular,
                        per_correo = :per_correo,
                        tsan_id = :tsan_id,
                        per_domicilio_sector = :per_domicilio_sector,
                        per_domicilio_cpri = :per_domicilio_cpri,
                        per_domicilio_csec = :per_domicilio_csec,
                        per_domicilio_num = :per_domicilio_num,
                        per_domicilio_ref = :per_domicilio_ref,
                        per_domicilio_telefono = :per_domicilio_telefono,
                        pai_id_domicilio = :pai_id_domicilio,
                        pro_id_domicilio = :pro_id_domicilio,
                        can_id_domicilio = :can_id_domicilio,
                        per_nac_ecuatoriano = :per_nac_ecuatoriano,
                        per_nacionalidad = :per_nacionalidad,
                        per_fecha_modificacion = :per_fecha_modificacion,
                        per_foto = :per_foto,
                        per_usuario_modifica = :usuario_modifica
                      WHERE 
                        per_id = :per_id AND 
                        per_estado = :estado AND
                        per_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":per_pri_nombre", ucwords(strtolower($per_pri_nombre)), \PDO::PARAM_STR);
            $comando->bindParam(":per_seg_nombre", ucwords(strtolower($per_seg_nombre)), \PDO::PARAM_STR);
            $comando->bindParam(":per_pri_apellido", ucwords(strtolower($per_pri_apellido)), \PDO::PARAM_STR);
            $comando->bindParam(":per_seg_apellido", ucwords(strtolower($per_seg_apellido)), \PDO::PARAM_STR);
            $comando->bindParam(":etn_id", $etn_id, \PDO::PARAM_INT);
            $comando->bindParam(":eciv_id", $eciv_id, \PDO::PARAM_INT);
            $comando->bindParam(":per_genero", $per_genero, \PDO::PARAM_STR);
            $comando->bindParam(":pai_id_nacimiento", $pai_id_nacimiento, \PDO::PARAM_INT);
            $comando->bindParam(":pro_id_nacimiento", $pro_id_nacimiento, \PDO::PARAM_INT);
            $comando->bindParam(":can_id_nacimiento", $can_id_nacimiento, \PDO::PARAM_INT);
            $comando->bindParam(":per_fecha_nacimiento", $per_fecha_nacimiento, \PDO::PARAM_STR);
            $comando->bindParam(":per_celular", $per_celular, \PDO::PARAM_STR);
            $comando->bindParam(":per_correo", strtolower($per_correo), \PDO::PARAM_STR);
            $comando->bindParam(":tsan_id", $tsan_id, \PDO::PARAM_INT);
            $comando->bindParam(":per_domicilio_sector", ucwords(strtolower($per_domicilio_sector)), \PDO::PARAM_STR);
            $comando->bindParam(":per_domicilio_cpri", ucwords(strtolower($per_domicilio_cpri)), \PDO::PARAM_STR);
            $comando->bindParam(":per_domicilio_csec", ucwords(strtolower($per_domicilio_csec)), \PDO::PARAM_STR);
            $comando->bindParam(":per_domicilio_num", $per_domicilio_num, \PDO::PARAM_STR);
            $comando->bindParam(":per_domicilio_ref", ucwords(strtolower($per_domicilio_ref)), \PDO::PARAM_STR);
            $comando->bindParam(":per_domicilio_telefono", $per_domicilio_telefono, \PDO::PARAM_STR);
            $comando->bindParam(":pai_id_domicilio", $pai_id_domicilio, \PDO::PARAM_INT);
            $comando->bindParam(":pro_id_domicilio", $pro_id_domicilio, \PDO::PARAM_INT);
            $comando->bindParam(":can_id_domicilio", $can_id_domicilio, \PDO::PARAM_INT);
            $comando->bindParam(":per_nac_ecuatoriano", $per_nac_ecuatoriano, \PDO::PARAM_STR);
            $comando->bindParam(":per_nacionalidad", ucwords(strtolower($per_nacionalidad)), \PDO::PARAM_STR);
            $comando->bindParam(":per_fecha_modificacion", $per_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":per_foto", $per_foto, \PDO::PARAM_STR);
            $comando->bindParam(":usuario_modifica", $usuario_modifica, \PDO::PARAM_INT);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
        //UPDATE (table name, column values, condition)        
    }

    /**
     * Function crearOtraEtnia
     * @author  Giovanni Vergara
     * @property      
     * @return  
     */
    public function crearOtraEtnia($per_id, $oetn_nombre) {
        $con = \Yii::$app->db_asgard;
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "oetn_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", oetn_estado";
        $bsol_sql .= ", 1";

        if (isset($per_id)) {
            $param_sql .= ", per_id";
            $bsol_sql .= ", :per_id";
        }

        if (isset($oetn_nombre)) {
            $param_sql .= ", oetn_nombre";
            $bsol_sql .= ", :oetn_nombre";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".otra_etnia ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($per_id))
                $comando->bindParam(':per_id', $per_id, \PDO::PARAM_INT);

            if (isset($oetn_nombre))
                $comando->bindParam(':oetn_nombre', $oetn_nombre, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.otra_etnia');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultarOtraetnia
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $perid       
     * @return  
     */
    public function consultarOtraetnia($perid) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT 
                   oetn_id,
                   per_id,
                   oetn_nombre
                FROM 
                   " . $con->dbname . ".otra_etnia  
                WHERE 
                   per_id = :perid  AND
                   oetn_estado = :estado AND
                   oetn_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":perid", $perid, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultaDatosRegion
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $pais       
     * @return  
     */
    public function consultaDatosRegion($pai_id) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT                     
                pai_nacionalidad,
                pai_codigo_fono
               FROM " . $con->dbname . ".pais                     
               WHERE 
                    pai_id = :pai_id AND
                    pai_estado = :estado AND
                    pai_estado_logico=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pai_id", $pai_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function ConsultaRegistroExiste
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $pais       
     * @return  
     */
    public function ConsultaRegistroExiste($correo, $cedula, $pasaporte) {
        \app\models\Utilities::putMessageLogFile(' yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy:  '.$cedula);
        $con = \Yii::$app->db_asgard;
        $filtro = '';
        $estado = 1;
        if (!empty($correo)) {
            $filtro = "per.per_correo = :correo ";
        }
        if (!empty($cedula)) {
            if (!empty($correo)) {
                $filtro .= "OR ";
            }
            $filtro .= "per.per_cedula = :cedula ";
        }
        if (!empty($pasaporte)) {
            if (!empty($correo) || !empty($cedula)) {
                $filtro .= "OR ";
            }
            $filtro .= "per.per_cedula =:pasaporte ";
        }
        $sql = "SELECT                     
               count(*) as existen 
               FROM " . $con->dbname . ".persona per                    
               WHERE";
        if (!empty($correo) || !empty($cedula) || !empty($pasaporte)) {
            $sql .= "($filtro) AND";
        }
        $sql .= " per.per_estado = :estado AND
                    per.per_estado_logico=:estado";        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (!empty($correo)) {
            $comando->bindParam(":correo", $correo, \PDO::PARAM_STR);
        }
        if (!empty($cedula)) {
            $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);
        }
        if (!empty($pasaporte)) {
            $comando->bindParam(":pasaporte", $pasaporte, \PDO::PARAM_STR);
        }
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function modificarOtraEtnia
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function modificarOtraEtnia($per_id, $oetn_nombre, $estado_inactiva) {
        $con = \Yii::$app->db_asgard;
        $oetn_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".otra_etnia           
                      SET 
                        oetn_nombre = :oetn_nombre,                        
                        oetn_fecha_modificacion = :oetn_fecha_modificacion,
                        oetn_estado = :estado_inactiva
                      WHERE 
                        per_id = :per_id AND
                        oetn_estado = :estado AND
                        oetn_estado_logico = :estado");
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":oetn_nombre", $oetn_nombre, \PDO::PARAM_STR);
            $comando->bindParam(":oetn_fecha_modificacion", $oetn_fecha_modificacion, \PDO::PARAM_STR);
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
     * Function consultarNacionalidad 
     * @author  Grace Viteri
     * @property      
     * @return  
     */
    public function consultarNacionalidad($pai_id) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT  pai_nacionalidad as nacionalidad
                 FROM pais                
                 WHERE pai_id = :pai_id and
                       pai_estado = :estado AND
                       pai_estado_logico= :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pai_id", $pai_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarIdPersona 
     * @author  Kleber Loayza
     * @property      
     * @return  
     */
    public function consultarIdPersona($cedula = null, $pasaporte = null, $correo = null, $celular = null) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                SELECT  ifnull(per_id,0) as per_id
                FROM    persona as per           
                 WHERE 
                    (
                        (per_cedula='$cedula' and per_correo='$correo') or
                        (per_cedula='$cedula' and per_celular='$celular') or
                        (per_pasaporte='$pasaporte' and per_correo='$correo') or
                        (per_pasaporte='$pasaporte' and per_celular='$celular')
                    )
                        AND per.per_estado = $estado AND
                            per.per_estado_logico=$estado
                    ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if (empty($resultData['per_id']))
            return 0;
        else {
            return $resultData['per_id'];
        }
    }

    /**
     * Function listadoUsuariosP
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function insertarDataPersona($con, $data) {
        //per_id
        //$per_cedula='99999999999';
        $sql = "INSERT INTO " . $con->dbname . ".persona
            (per_pri_nombre,per_pri_apellido,per_fecha_nacimiento,per_celular,per_cedula,per_genero,per_correo,
             per_fecha_creacion,per_estado,per_estado_logico)VALUES
            (:per_pri_nombre,:per_pri_apellido,:per_fecha_nacimiento,:per_celular,:per_cedula,:per_genero,:per_correo,
             CURRENT_TIMESTAMP(),1,1) ";

        $command = $con->createCommand($sql);
        $command->bindParam(":per_pri_nombre", $data[0]['per_pri_nombre'], \PDO::PARAM_STR);
        $command->bindParam(":per_pri_apellido", $data[0]['per_pri_apellido'], \PDO::PARAM_STR);
        $command->bindParam(":per_fecha_nacimiento", $data[0]['per_fecha_nacimiento'], \PDO::PARAM_STR);
        $command->bindParam(":per_celular", $data[0]['per_celular'], \PDO::PARAM_STR);
        $command->bindParam(":per_cedula", $data[0]['per_cedula'], \PDO::PARAM_STR); //VALOR UNIQUE en la  base de Datos  
        $command->bindParam(":per_genero", $data[0]['per_genero'], \PDO::PARAM_STR);
        $command->bindParam(":per_correo", $data[0]['per_correo'], \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    /**
     * Function listadoUsuariosP
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function insertarDataCorreo($con, $data) {
        //ucor_id
        $sql = "INSERT INTO " . $con->dbname . ".usuario_correo
            (usu_id,ucor_user,ucor_fecha_creacion,ucor_estado_logico)VALUES
            (:usu_id,:ucor_user,CURRENT_TIMESTAMP(),1) ";
        $command = $con->createCommand($sql);
        $command->bindParam(":usu_id", $data[0]['usu_id'], \PDO::PARAM_INT);
        $command->bindParam(":ucor_user", $data[0]['per_correo'], \PDO::PARAM_STR);
        $command->execute();
    }

    /**
     * Function 
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id      
     * @return  
     */
    public function consultarTipoPersona($TextAlias) {
        $con = \Yii::$app->db_asgard;
        $sql = "SELECT tper_id Ids 
                    FROM " . $con->dbname . ".tipo_persona  
                WHERE tper_nombre=:tper_nombre AND tper_estado_logico=1 ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":tper_nombre", $TextAlias, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    }

        /**
     * Funcion para verificar si una persona existe, no esta y si eliminada por cedula, pasaporte o ruc
     * @author Emilio Moran 
     */

    public static function VerificarPersonaExiste($cedula = null , $pasaporte = null, $ruc = null) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                SELECT per_id, per_estado, per_estado_logico
                FROM persona as per           
                WHERE 
                    (
                        (per_cedula='$cedula' or per_pasaporte='$pasaporte' or per_ruc='$ruc')                        
                    )";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if (empty($resultData['per_id'])){        
            return -1; //No se encuentra registrado
        } else if($resultData['per_estado_logico']=='0') {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * Funcion para obtener el per_id de una persona existente eliminada o no por cedula, pasaporte o ruc
     * @author Emilio Moran 
     */

    public static function ObtenerPersonabyCedulaPasaporteRuc($cedula = null , $pasaporte = null, $ruc = null) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                SELECT per_id
                FROM persona as per           
                WHERE 
                    (
                        (per_cedula='$cedula' or per_pasaporte='$pasaporte' or per_ruc='$ruc')                        
                    )";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        return $resultData['per_id'];
    }

    /**
     * Function to get Cedula or Pasaporte or RUC from an user validating the type doc
     * @author Emilio Moran <emiliojmp9@gmail.com>
     */

    public static function getDNIbyTipoDoc($per_id, $type_doc) {
        $con = \Yii::$app->db_asgard;        
        $dni = "per_cedula";
        if ($type_doc == "PASS") {
            $dni = "per_pasaporte";
        } else if ($type_doc == "RUC") {
            $dni = "per_ruc";
        }

        $sql = "
            SELECT $dni
            FROM persona as per
            WHERE(
                per_id = $per_id
            )
        ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        return $resultData[$dni];
    }


/**
     * Function consultaDatosPersonaid
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer        
     * @return  
     */
    public function consultaDatosPersonaid($per_id) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT   
                per_pri_nombre,
                per_seg_nombre,
                per_pri_apellido,
                per_seg_apellido,                  
                per_correo,
                per_cedula
               FROM " . $con->dbname . ".persona                     
               WHERE 
                    per_id = :per_id AND
                    per_estado = :estado AND
                    per_estado_logico=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

/**
     * Function Insertar Datos de Inscripcion Grado
     * @author  Lisbeth Gonzalez <analista.desarrollo@uteg.edu.ec>
     * @property integer        
     * @return  
     */
    public function insertarPersonaInscripciongrado($per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $per_dni, $eciv_id, $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $per_domicilio_csec, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nacionalidad) {
        //per_id
        //$per_cedula='99999999999';
        $con = \Yii::$app->db_asgard;
        $sql = "INSERT INTO " . $con->dbname . ".persona
            (per_pri_nombre,per_seg_nombre,per_pri_apellido,per_seg_apellido,per_cedula,eciv_id,can_id_nacimiento,per_fecha_nacimiento,per_celular,per_correo,per_domicilio_csec,per_domicilio_ref,per_domicilio_telefono,pai_id_domicilio,pro_id_domicilio,can_id_domicilio,per_nacionalidad,per_fecha_creacion,per_estado,per_estado_logico)VALUES
            (:per_pri_nombre,:per_seg_nombre,:per_pri_apellido,:per_seg_apellido,:per_dni,:eciv_id,:can_id_nacimiento,:per_fecha_nacimiento,:per_celular,:per_correo,:per_domicilio_csec,:per_domicilio_ref,:per_domicilio_telefono,:pai_id_domicilio,:pro_id_domicilio,:can_id_domicilio,:per_nacionalidad,CURRENT_TIMESTAMP(),1,1) ";

        $command = $con->createCommand($sql);
        $command->bindParam(":per_pri_nombre", $per_pri_nombre, \PDO::PARAM_STR);
        $command->bindParam(":per_seg_nombre", $per_seg_nombre, \PDO::PARAM_STR);
        $command->bindParam(":per_pri_apellido", $per_pri_apellido, \PDO::PARAM_STR);
        $command->bindParam(":per_seg_apellido", $per_seg_apellido, \PDO::PARAM_STR);
        $command->bindParam(":per_dni", $per_dni, \PDO::PARAM_STR);
        $command->bindParam(":eciv_id", $eciv_id, \PDO::PARAM_STR);
        $command->bindParam(":can_id_nacimiento", $can_id_nacimiento, \PDO::PARAM_STR);
        $command->bindParam(":per_fecha_nacimiento", $per_fecha_nacimiento, \PDO::PARAM_STR);
        $command->bindParam(":per_celular", $per_celular, \PDO::PARAM_STR);
        $command->bindParam(":per_correo", $per_correo, \PDO::PARAM_STR);
        $command->bindParam(":per_domicilio_csec", $per_domicilio_csec, \PDO::PARAM_STR);
        $command->bindParam(":per_domicilio_ref", $per_domicilio_ref, \PDO::PARAM_STR);
        $command->bindParam(":per_domicilio_telefono", $per_domicilio_telefono, \PDO::PARAM_STR); //VALOR UNIQUE en la  base de Datos  
        $command->bindParam(":pai_id_domicilio", $pai_id_domicilio, \PDO::PARAM_STR);
        $command->bindParam(":pro_id_domicilio", $pro_id_domicilio, \PDO::PARAM_STR);
        $command->bindParam(":can_id_domicilio", $can_id_domicilio, \PDO::PARAM_STR);
        $command->bindParam(":per_nacionalidad", $per_nacionalidad, \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    public function modificaPersonaInscripciongrado($per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $per_dni, $eciv_id,  $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $per_domicilio_csec, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nacionalidad) {
        $con = \Yii::$app->db_asgard;
        $usuario_modifica = @Yii::$app->session->get("PB_iduser");
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        $estado = 1;
        $per_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".persona            
                      SET 
                        per_pri_nombre = :per_pri_nombre,
                        per_seg_nombre = :per_seg_nombre,
                        per_pri_apellido = :per_pri_apellido,
                        per_seg_apellido = :per_seg_apellido,
                        per_cedula = :per_dni,                        
                        eciv_id = :eciv_id,
                        can_id_nacimiento = :can_id_nacimiento,
                        per_fecha_nacimiento = :per_fecha_nacimiento,
                        per_celular = :per_celular,
                        per_correo = :per_correo,
                        per_domicilio_csec = :per_domicilio_csec,
                        per_domicilio_ref = :per_domicilio_ref,
                        per_domicilio_telefono = :per_domicilio_telefono,
                        pai_id_domicilio = :pai_id_domicilio,
                        pro_id_domicilio = :pro_id_domicilio,
                        can_id_domicilio = :can_id_domicilio,
                        per_nacionalidad = :per_nacionalidad,
                        per_fecha_modificacion = :per_fecha_modificacion,
                        per_usuario_modifica = :usuario_modifica
                      WHERE 
                        per_cedula = :per_dni AND 
                        per_estado = :estado AND
                        per_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":per_pri_nombre", ucwords(strtolower($per_pri_nombre)), \PDO::PARAM_STR);
            $comando->bindParam(":per_seg_nombre", ucwords(strtolower($per_seg_nombre)), \PDO::PARAM_STR);
            $comando->bindParam(":per_pri_apellido", ucwords(strtolower($per_pri_apellido)), \PDO::PARAM_STR);
            $comando->bindParam(":per_seg_apellido", ucwords(strtolower($per_seg_apellido)), \PDO::PARAM_STR);
            $comando->bindParam(":per_dni", $per_dni, \PDO::PARAM_INT);
            $comando->bindParam(":eciv_id", $eciv_id, \PDO::PARAM_INT);
            $comando->bindParam(":can_id_nacimiento", $can_id_nacimiento, \PDO::PARAM_INT);
            $comando->bindParam(":per_fecha_nacimiento", $per_fecha_nacimiento, \PDO::PARAM_STR);
            $comando->bindParam(":per_celular", $per_celular, \PDO::PARAM_STR);
            $comando->bindParam(":per_correo", strtolower($per_correo), \PDO::PARAM_STR);
            $comando->bindParam(":per_domicilio_csec", ucwords(strtolower($per_domicilio_csec)), \PDO::PARAM_STR);
            $comando->bindParam(":per_domicilio_ref", ucwords(strtolower($per_domicilio_ref)), \PDO::PARAM_STR);
            $comando->bindParam(":per_domicilio_telefono", $per_domicilio_telefono, \PDO::PARAM_STR);
            $comando->bindParam(":pai_id_domicilio", $pai_id_domicilio, \PDO::PARAM_INT);
            $comando->bindParam(":pro_id_domicilio", $pro_id_domicilio, \PDO::PARAM_INT);
            $comando->bindParam(":can_id_domicilio", $can_id_domicilio, \PDO::PARAM_INT);
            $comando->bindParam(":per_nacionalidad", ucwords(strtolower($per_nacionalidad)), \PDO::PARAM_STR);
            $comando->bindParam(":per_fecha_modificacion", $per_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":usuario_modifica", $usuario_modifica, \PDO::PARAM_INT);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
        //UPDATE (table name, column values, condition)        
    }

    public function consultarUltimoPer_id() {
        $con = \Yii::$app->db_asgard;
        $estado = '1';
        $sql = "
                    SELECT lpad(ifnull(max(per_id),0)+1,7,'0') as ultimo
                    FROM " . $con->dbname . ".persona 
                    WHERE per_estado_logico=:estado AND per_estado=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryOne();
        if (empty($resultData))
            return 0;
        else {
            return $resultData;
        }
    }
    public function consultPer_id() {
        $con = \Yii::$app->db_asgard;
        $estado = '1';
        $sql = "
                    SELECT lpad(ifnull(max(per_id),0),7,' ') as ultimo
                    FROM " . $con->dbname . ".persona 
                    WHERE per_estado_logico=:estado AND per_estado=:estado";
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
     * Function Insertar Datos de Inscripcion Grado
     * @author  Lisbeth Gonzalez <analista.desarrollo@uteg.edu.ec>
     * @property integer        
     * @return  
     */
    public function insertarPersonaInscripcionposgrado($per_dni, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $can_id_nacimiento, $per_fecha_nacimiento, $per_nacionalidad, $eciv_id, $pai_id_nacimiento, $pro_id_nacimiento, $can_id_nacimientos, $per_domicilio_ref, $per_celular, $per_domicilio_telefono, $per_correo) {
        //per_id
        //$per_cedula='99999999999';
        $con = \Yii::$app->db_asgard;
        $sql = "INSERT INTO " . $con->dbname . ".persona
            (per_cedula,per_pri_nombre,per_seg_nombre,per_pri_apellido,per_seg_apellido,can_id_nacimiento,per_fecha_nacimiento,per_nacionalidad,eciv_id,pai_id_domicilio,pro_id_domicilio,can_id_domicilio,per_domicilio_ref,per_celular,per_domicilio_telefono,per_correo,per_fecha_creacion,per_estado,per_estado_logico)VALUES
            (:per_dni,:primer_nombre,:segundo_nombre,:primer_apellido,:segundo_apellido,:can_id_nacimiento,:per_fecha_nacimiento,:per_nacionalidad,:eciv_id,:pai_id_domicilio,:pro_id_domicilio,:can_id_domicilio,:per_domicilio_ref,:per_celular,:per_domicilio_telefono,:per_correo,CURRENT_TIMESTAMP(),1,1) ";

        $command = $con->createCommand($sql);
        $command->bindParam(":per_dni", $per_dni, \PDO::PARAM_STR);
        $command->bindParam(":primer_nombre", $primer_nombre, \PDO::PARAM_STR);
        $command->bindParam(":segundo_nombre", $segundo_nombre, \PDO::PARAM_STR);
        $command->bindParam(":primer_apellido", $primer_apellido, \PDO::PARAM_STR);
        $command->bindParam(":segundo_apellido", $segundo_apellido, \PDO::PARAM_STR);
        $command->bindParam(":can_id_nacimiento", $can_id_nacimiento, \PDO::PARAM_STR);
        $command->bindParam(":per_fecha_nacimiento", $per_fecha_nacimiento, \PDO::PARAM_STR);
        $command->bindParam(":per_nacionalidad", $per_nacionalidad, \PDO::PARAM_STR);
        $command->bindParam(":eciv_id", $eciv_id, \PDO::PARAM_STR);
        $command->bindParam(":pai_id_domicilio", $pai_id_domicilio, \PDO::PARAM_STR);
        $command->bindParam(":pro_id_domicilio", $pro_id_domicilio, \PDO::PARAM_STR);
        $command->bindParam(":can_id_domicilio", $can_id_domicilio, \PDO::PARAM_STR);
        $command->bindParam(":per_domicilio_ref", $per_domicilio_ref, \PDO::PARAM_STR);
        $command->bindParam(":per_celular", $per_celular, \PDO::PARAM_STR);
        $command->bindParam(":per_domicilio_telefono", $per_domicilio_telefono, \PDO::PARAM_STR); 
        $command->bindParam(":per_correo", $per_correo, \PDO::PARAM_STR);
        $command->execute();
        \app\models\Utilities::putMessageLogFile('ultimo registro:  '.$id);
        $id = $con->getLastInsertID();
        //return $con->getLastInsertID();
        return $id;
    }

    public function modificaPersonaInscripcionposgrado($per_dni, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $can_id_nacimiento, $per_fecha_nacimiento, $per_nacionalidad, $eciv_id, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_domicilio_ref, $per_celular, $per_domicilio_telefono, $per_correo) {
        $con = \Yii::$app->db_asgard;
        $usuario_modifica = @Yii::$app->session->get("PB_iduser");
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        $estado = 1;
        $per_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".persona            
                      SET 
                        per_cedula = :per_dni,
                        per_pri_nombre = :primer_nombre,
                        per_seg_nombre = :segundo_nombre,
                        per_pri_apellido = :primer_apellido,
                        per_seg_apellido = :segundo_apellido, 
                        can_id_nacimiento = :can_id_nacimiento, 
                        per_fecha_nacimiento = :per_fecha_nacimiento,   
                        per_nacionalidad = :per_nacionalidad,                   
                        eciv_id = :eciv_id,
                        pai_id_domicilio = :pai_id_domicilio,
                        pro_id_domicilio = :pro_id_domicilio,
                        can_id_domicilio = :can_id_domicilio,
                        per_domicilio_ref = :per_domicilio_ref,
                        per_celular = :per_celular,
                        per_domicilio_telefono = :per_domicilio_telefono,
                        per_correo = :per_correo,
                        per_fecha_modificacion = :per_fecha_modificacion,
                        per_usuario_modifica = :usuario_modifica
                      WHERE 
                        per_cedula = :per_dni AND 
                        per_estado = :estado AND
                        per_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $command->bindParam(":per_dni", $per_dni, \PDO::PARAM_STR);
            $command->bindParam(":primer_nombre", $primer_nombre, \PDO::PARAM_STR);
            $command->bindParam(":segundo_nombre", $segundo_nombre, \PDO::PARAM_STR);
            $command->bindParam(":primer_apellido", $primer_apellido, \PDO::PARAM_STR);
            $command->bindParam(":segundo_apellido", $segundo_apellido, \PDO::PARAM_STR);
            $command->bindParam(":can_id_nacimiento", $can_id_nacimiento, \PDO::PARAM_STR);
            $command->bindParam(":per_fecha_nacimiento", $per_fecha_nacimiento, \PDO::PARAM_STR);
            $command->bindParam(":per_nacionalidad", $per_nacionalidad, \PDO::PARAM_STR);
            $command->bindParam(":eciv_id", $eciv_id, \PDO::PARAM_STR);
            $command->bindParam(":pai_id_domicilio", $pai_id_domicilio, \PDO::PARAM_STR);
            $command->bindParam(":pro_id_domicilio", $pro_id_domicilio, \PDO::PARAM_STR);
            $command->bindParam(":can_id_domicilio", $can_id_domicilio, \PDO::PARAM_STR);
            $command->bindParam(":per_domicilio_ref", $per_domicilio_ref, \PDO::PARAM_STR);
            $command->bindParam(":per_celular", $per_celular, \PDO::PARAM_STR);
            $command->bindParam(":per_domicilio_telefono", $per_domicilio_telefono, \PDO::PARAM_STR); 
            $command->bindParam(":per_correo", $per_correo, \PDO::PARAM_STR);
            $comando->bindParam(":per_fecha_modificacion", $per_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":usuario_modifica", $usuario_modifica, \PDO::PARAM_INT);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
        //UPDATE (table name, column values, condition)        
    }
    

}
