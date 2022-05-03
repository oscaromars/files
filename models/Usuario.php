<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "usuario".
 *
 * @property integer $usu_id
 * @property integer $per_id
 * @property string $usu_user
 * @property string $usu_password
 * @property string $usu_time_pass
 * @property string $usu_sha
 * @property string $usu_session
 * @property string $usu_last_login
 * @property string $usu_link_activo
 * @property string $usu_upreg
 * @property string $usu_estado
 * @property string $usu_fecha_creacion
 * @property string $usu_fecha_modificacion
 * @property string $usu_estado_logico
 *
 * @property ConfiguracionCuenta[] $configuracionCuentas
 * @property UsuaGrolEper[] $usuaGrolsEper
 * @property Persona $per
 * @property UsuarioCorreo[] $usuarioCorreos
 */
class Usuario extends \yii\db\ActiveRecord implements IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'usuario';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['per_id', 'usu_estado', 'usu_estado_logico'], 'required'],
                [['per_id'], 'integer'],
                [['usu_time_pass', 'usu_last_login', 'usu_fecha_creacion', 'usu_fecha_modificacion'], 'safe'],
                [['usu_sha', 'usu_session', 'usu_link_activo'], 'string'],
                [['usu_user'], 'string', 'max' => 45],
                [['usu_password'], 'string', 'max' => 255],
                [['usu_upreg','usu_estado', 'usu_estado_logico'], 'string', 'max' => 1],
                [['per_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persona::className(), 'targetAttribute' => ['per_id' => 'per_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'usu_id' => 'Usu ID',
            'per_id' => 'Per ID',
            'usu_user' => 'Usu User',
            'usu_password' => 'Usu Password',
            'usu_time_pass' => 'Usu Time Pass',
            'usu_sha' => 'Usu Sha',
            'usu_session' => 'Usu Session',
            'usu_last_login' => 'Usu Last Login',
            'usu_link_activo' => 'Usu Link Activo',
            'usu_estado' => 'Usu Estado',
            'usu_fecha_creacion' => 'Usu Fecha Creacion',
            'usu_fecha_modificacion' => 'Usu Fecha Modificacion',
            'usu_estado_logico' => 'Usu Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguracionCuentas() {
        return $this->hasMany(ConfiguracionCuenta::className(), ['usu_id' => 'usu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuaGrolEpers() {
        return $this->hasMany(UsuaGrolEper::className(), ['usu_id' => 'usu_id']);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPer() {
        return $this->hasOne(Persona::className(), ['per_id' => 'per_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCorreos() {
        return $this->hasMany(UsuarioCorreo::className(), ['usu_id' => 'usu_id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['Hash' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->Hash;
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function findByCondition($condition) {
        return parent::findByCondition($condition);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        $user = static::findOne(['usu_user' => $username, 'usu_estado' => 1]);
        if (isset($user->usu_id))
            return $user;
        else
            return NULL;
    }

    /**
     * Validates password
     *
     * @author Eduardo Cueva <ecueva@penblu.com>
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        $security = new Security();
        return ($this->usu_sha === $security->decryptByPassword(base64_decode($this->usu_password), $password));
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @author Eduardo Cueva <ecueva@penblu.com>
     * @param string $password
     */
    public function setPassword($password) {
        $security = new Security();
        $hash = (isset($this->usu_sha) ? $this->usu_sha : ($this->generateAuthKey()));
        $this->usu_password = base64_encode($security->encryptByPassword($hash, $password));
    }

    /**
     * Función para generar el Salt o token de clave de manera aleatoria
     *
     * @author Eduardo Cueva <ecueva@penblu.com>
     * @access public
     * 
     */
    public function generateAuthKey() {
        $security = new Security();
        $this->usu_sha = $security->generateRandomString();
        return $this->usu_sha;
    }

    /**
     * Function createSession
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function createSession($id_empresa = NULL) {
        $session = Yii::$app->session;
        if ($session->isActive) {
            $session->open();
            //$session->close();
            $model_persona = Persona::findIdentity($this->per_id);
            $model_empresa = Empresa::getEmpresasXUsuario($this->usu_id);
            $nombre_empresa = "";
            if(!isset($id_empresa) || array_search($id_empresa, array_column($model_empresa, 'emp_id')) === FALSE){
                $id_empresa = $model_empresa[0]["emp_id"];
                $nombre_empresa = $model_empresa[0]["emp_nombre_comercial"];
            }else{
                $empresa_model = Empresa::findIdentity($id_empresa);
                $nombre_empresa = $empresa_model->emp_nombre_comercial;
            }
            
            $nombre_persona = $model_persona->per_pri_nombre;
            $apellido_persona = $model_persona->per_pri_apellido;
            $session->set('PB_isuser', true);
            $session->set('PB_username', $this->usu_user);
            $session->set('PB_nombres', $nombre_persona . " " . $apellido_persona);
            $session->set('PB_idempresa', $id_empresa);
            $session->set('PB_empresa', $nombre_empresa);
            $session->set('PB_perid', $this->per_id);
            $session->set('PB_iduser', $this->usu_id);
            $session->set('PB_yii_lang', Yii::$app->language);
            $session->set('PB_yii_theme', Yii::$app->view->theme->themeName);
	    $session->set('PB_p_establecimiento', '');
	    $session->set('PB_p_emision', '');
        } else {
            $session->destroy();
        }
    }
    
    public static function addVarSession($alias, $value){
        $session = Yii::$app->session;
        if ($session->isActive) {
            $session->set($alias, $value);
        }
    }

    /**
     * Function regenerateSession
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function regenerateSession() {
        $session = Yii::$app->session;
        if ($session->isActive) {
            $id = Yii::$app->session->getId();
            Yii::$app->session->regenerateID($id);
        }
    }

    /**
     * Function destroySession
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function destroySession() {
        $usuario = $this->findIdentity(Yii::$app->session->get("PB_iduser"));
        $session = Yii::$app->session;
        $session->close();
        $session->destroy();
    }

    /**
     * Function crearUsuario
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function crearUsuario($username, $password, $id_persona) {
        // se debe verificar de que el usuario no exista
        $this->usu_user = $username;
        $this->generateAuthKey(); // generacion de hash
        $this->setPassword($password);
        $this->per_id = $id_persona;
        if ($this->save())
            return true;
        return false;
    }
    /**
     * Function crearUsuario
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function crearUsuarioTemporal($con,$parameters,$keys,$name_table) {
        $trans = $con->getTransaction(); 
        $param_sql .= "" . $keys[0];
        $bdet_sql .= "'" . $parameters[0]."'";
        for ($i = 1; $i < count($parameters); $i++) {
            if (isset($parameters[$i])) {
                $param_sql .= ", " . $keys[$i];
                $bdet_sql .= ", '" . $parameters[$i]."'";
            }
        }
        try {
            $sql = "INSERT INTO " . $con->dbname.'.'.$name_table . " ($param_sql) VALUES($bdet_sql);";                        
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $idtable=$con->getLastInsertID($con->dbname . '.' . $name_table);
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null){
                $trans->rollback();            
            }
            return 0;
        }
    }
    /**
     * Function consultarIdPersona 
     * @author  Kleber Loayza
     * @property      
     * @return  
     */
    public function consultarIdUsuario($per_id=null, $usuario=null) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $qryPer = "";
        if(isset($per_id)){
            $qryPer = "per_id=$per_id and ";
        }
        $sql = "
                SELECT ifnull(usu_id,0) as usu_id
                FROM usuario
                WHERE 
                    $qryPer
                    usu_user='$usuario' and
                    usu_estado = $estado AND
                    usu_estado_logico=$estado
              ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if(empty($resultData['usu_id']))
            return 0;
        else {
            return $resultData['usu_id'];    
        }
    }
    /**
     * Función genera un link de acceso para ser enviado por correo
     *
     * @access  public
     * @author  Eduard Cueva <ecueva@penblu.com>
     * @return  string         Link de acceso
     */
    public function generarLinkActivacion() {
        $security = new Security();
        $hash = $security->generateRandomString();
        $sublink = urlencode($hash);
        $sublink = str_replace("/", "", $sublink);
        $sublink = str_replace("+", "", $sublink);
        $sublink = str_replace("-", "", $sublink);
        $sublink = str_replace("_", "", $sublink);
        $sublink = str_replace(" ", "", $sublink);
        $sublink = str_replace("?", "", $sublink);
        $link = Url::base(true) . "/site/activation?wg=" . $sublink;
        $this->usu_link_activo = $link;
        $this->usu_estado = "0";
        $this->usu_estado_logico = "1";
        $this->save();
        return $link;
    }

    /**
     * Function activarLinkCuenta
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function activarLinkCuenta($link) {
        $user = static::findOne(['usu_link_activo' => $link]);
        $dbLink = $user->usu_link_activo;
        if (isset($dbLink) && $dbLink != "") {
            if ($dbLink == $link) {
                $user->usu_link_activo = "";
                $user->usu_estado = "1";
                $id = $user->usu_id;
                $user->update(true, array("usu_link_activo", "usu_estado"));
                return true;
            }
        }
        return false;
    }

    /**
     * Function listadoUsuarios
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     *          Byron Villacreses <developer@uteg.edu.ec>
     * @param      //Funcion pendiente revisar el query
     * @return  
     */
    public static function listadoUsuarios($search = NULL) {
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $idempresa = Yii::$app->session->get('PB_idempresa', FALSE);
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";

        if (isset($search)) {
            $str_search = "(B.per_pri_nombre like :search OR ";
            $str_search .= "B.per_pri_apellido like :search OR ";
            $str_search .= "A.usu_user like :search) AND ";
        }

        $sql = "SELECT A.usu_id id,F.ugep_id,A.usu_user Username,B.per_pri_nombre Nombres,B.per_pri_apellido Apellidos,
                    C.emp_id,E.emp_razon_social Empresa,D.gru_id,H.gru_nombre Grupo,D.rol_id,R.rol_nombre Rol
                    FROM " . $con->dbname . ".usuario A
                    INNER JOIN " . $con->dbname . ".persona B ON A.per_id=B.per_id
                            INNER JOIN (" . $con->dbname . ".usua_grol_eper F 
                                INNER JOIN (" . $con->dbname . ".empresa_persona C
                                            INNER JOIN " . $con->dbname . ".empresa E ON C.emp_id=E.emp_id)						
                                        ON F.eper_id=C.eper_id
                                INNER JOIN (" . $con->dbname . ".grup_rol D 
                                            INNER JOIN " . $con->dbname . ".grupo H ON H.gru_id=D.gru_id
                                            INNER JOIN " . $con->dbname . ".rol R ON R.rol_id=D.rol_id)
                                        ON D.grol_id=F.grol_id)
                                    ON F.usu_id=A.usu_id
                    WHERE A.usu_estado_logico=1 AND A.usu_estado=1 
                            ORDER BY A.usu_user; ";
        
        $comando = Yii::$app->db->createCommand($sql);
        if ($iduser == 1) {
            $comando->bindParam(":emp_id", $idempresa, \PDO::PARAM_INT);
        }
        if (isset($search)) {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'Ids',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Username', 'Nombres', 'Apellidos', 'Empresa'],
            ],
        ]);

        return $dataProvider;
    }

    /**
     * Function listadoUsuariosP
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public static function listadoUsuariosP($search = NULL) {
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $idempresa = Yii::$app->session->get('PB_idempresa', FALSE);
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";
        if ($iduser == 1) {
            $condition = "eper.emp_id=:emp_id AND ";
        }
        if (isset($search)) {
            $str_search = "(per.per_nombres like :search OR ";
            $str_search .= "per.per_apellidos like :search OR ";
            $str_search .= "usu.usu_username like :search) AND ";
        }
        $sql = "SELECT 
                    DISTINCT(usu.usu_username) as Username,
                    usu.usu_id as id,
                    per.per_nombres as Nombres,
                    per.per_apellidos as Apellidos,
                    emp.emp_nombre_comercial as Empresa,
                    gru.gru_nombre as Grupo,
                    rol.rol_nombre as Rol
                FROM 
                    usua_grol_eper as ugep 
                    INNER JOIN empresa_persona as eper on ugep.eper_id=eper.eper_id 
                    INNER JOIN empresa as emp on emp.emp_id=eper.emp_id
                    INNER JOIN usuario as usu on ugep.usu_id=usu.usu_id 
                    INNER JOIN persona as per on per.per_id=eper.per_id
                    INNER JOIN grupo_rol as grol on ugep.grol_id=grol.grol_id 
                    INNER JOIN rol as rol on rol.rol_id=grol.rol_id
                    INNER JOIN grupo as gru on gru.gru_id=grol.gru_id
                WHERE 
                    $condition 
                    $str_search
                    usu.usu_estado_logico=1 AND 
                    usu.usu_estado_activo=1 AND 
                    per.per_estado_logico=1 AND
                    per.per_estado_activo=1 AND
                    eper.eper_estado_logico=1 AND 
                    eper.eper_estado_activo=1 AND 
                    ugep.ugep_estado_logico=1 AND 
                    ugep.ugep_estado_activo=1 AND
                    rol.rol_estado_activo=1 AND
                    rol.rol_estado_logico=1 AND
                    gru.gru_estado_logico=1 AND
                    gru.gru_estado_activo=1 
                ORDER BY usu.usu_username, emp.emp_nombre_comercial;";
        $comando = Yii::$app->db->createCommand($sql);
        if ($iduser == 1) {
            $comando->bindParam(":emp_id", $idempresa, \PDO::PARAM_INT);
        }
        if (isset($search)) {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Username', 'Nombres', 'Apellidos', 'Empresa'],
            ],
        ]);

        return $dataProvider;
    }
    
     /**
     * Function inactivarUsuarioId  que inactiva los datos de experiencia en docencia.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function inactivarUsuarioId($usu_id) {
        $con = \Yii::$app->db;
        $estado = 1;
        $estadoInactiva = 0;
        $fecha_modificacion = date("Y-m-d H:i:s");

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una.
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".usuario             
                      SET usu_estado = :estadoInactiva,
                          usu_fecha_modificacion = :fecha_modificacion
                      WHERE 
                        usu_id = :usu_id AND                        
                        usu_estado = :estado AND
                        usu_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
            $comando->bindParam(":estadoInactiva", $estadoInactiva, \PDO::PARAM_STR);
            $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);

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
    
    public function consultarUsuario($ids){
        $con = \Yii::$app->db;          
        $sql = "SELECT A.usu_id,A.per_id,G.grol_id,A.usu_user,B.per_pri_nombre,B.per_pri_apellido,B.per_cedula,
                                B.per_genero,B.per_fecha_nacimiento,B.per_correo,B.per_celular,
                                C.emp_id,E.emp_razon_social,D.gru_id,H.gru_nombre,D.rol_id,R.rol_nombre,D.eper_id
                        FROM " . $con->dbname . ".usuario A
                        INNER JOIN " . $con->dbname . ".persona B ON A.per_id=B.per_id
                                INNER JOIN (" . $con->dbname . ".usua_grol_eper F 
                                                INNER JOIN (" . $con->dbname . ".empresa_persona C
                                                                        INNER JOIN " . $con->dbname . ".empresa E ON C.emp_id=E.emp_id)						
                                                        ON F.eper_id=C.eper_id)
                                        ON F.usu_id=A.usu_id
                                INNER JOIN (" . $con->dbname . ".usua_grol_eper G 
                                                INNER JOIN (" . $con->dbname . ".grup_rol D 
                                                                INNER JOIN " . $con->dbname . ".grupo H ON H.gru_id=D.gru_id
                                        INNER JOIN " . $con->dbname . ".rol R ON R.rol_id=D.rol_id)
                                                        ON D.grol_id=G.grol_id)
                            ON G.usu_id=A.usu_id
                WHERE $str_search A.usu_estado_logico=1 AND A.usu_estado=1 "
                . "  AND A.usu_id=:usu_id ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":usu_id", $ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }
    
    
    public function consultarEmpGruRol($ids){
        $con = \Yii::$app->db;          
        $sql = "SELECT A.eper_id,A.emp_id,B.emp_razon_social,H.gru_id,H.gru_nombre,R.rol_id,R.rol_nombre
                    FROM " . $con->dbname . ".empresa_persona A
                            INNER JOIN " . $con->dbname . ".empresa B ON A.emp_id=B.emp_id
                    INNER JOIN (" . $con->dbname . ".grup_rol C
                                            INNER JOIN " . $con->dbname . ".grupo H ON H.gru_id=C.gru_id
                                            INNER JOIN " . $con->dbname . ".rol R ON R.rol_id=C.rol_id)
                                    ON C.eper_id=A.eper_id
                WHERE A.eper_estado_logico=1 AND A.per_id=:per_id; ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }
    
    
    /**
     * Function listadoUsuariosP
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function insertarUsuarioEmpRolGru($data) {
        $arroout = array();
        $persona=new Persona();
        $empPers=new EmpresaPersona();
        $usuGREP=new UsuaGrolEper();
        $gruRol=new GrupRol();                
        $con = \Yii::$app->db;
        $trans = $con->beginTransaction();
        try {
            $data = isset($data['DATA']) ? $data['DATA'] : array();
            $per_id=$persona->insertarDataPersona($con, $data);
            $data[0]['per_id']=$per_id;            
            $usu_id= $this->insertarDataUsuario($con, $data);
            $data[0]['usu_id']=$usu_id;            
            $persona->insertarDataCorreo($con, $data);
            
            $dts=json_decode($data[0]['data_Empresa']);
            for ($i = 0; $i < sizeof($dts); $i++) {
                $data[0]['emp_id']=$dts[$i]->emp_id;
                $data[0]['gru_id']=$dts[$i]->gru_id;
                $data[0]['rol_id']=$dts[$i]->rol_id;
                $eper_id=$empPers->consultarIdEmpresaPersona($data[0]['per_id'],$data[0]['emp_id']);
                if ($eper_id==0) {//Si es 0 No existe en la tabla
                    if($dts[$i]->eper_id==0){//Verifica que No tenga Ids para que no se duplicquen los Ids
                        $eper_id=$empPers->insertarDataEmpresaPersona($con, $data);//Inserto Empresa Persona 
                        $this->actualizarArrayEmpresa($dts, $data[0]['emp_id'], $eper_id);
                    }else{
                        $eper_id=$dts[$i]->eper_id;
                    }
                    
                }                
                $data[0]['eper_id']=$eper_id;  
                $grol_id=$gruRol->existeGrupoRol($data[0]['gru_id'], $data[0]['rol_id']);
                if ($grol_id==0) {
                    $grol_id=$gruRol->insertarDataGrupRol($con, $data);
                }                
                $data[0]['grol_id']=$grol_id; 
                $usuGREP->insertarDataUsuaGrolEper($con, $data);
            }
            $trans->commit();
            $con->close();
            //RETORNA DATOS 
            $arroout["status"]= true;

            return $arroout;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();
            //throw $e;
            $arroout["status"]= false;
            return $arroout;
        }
    }
    private function actualizarArrayEmpresa($dts,$emp_id,$eper_id) {
        for ($i = 0; $i < sizeof($dts); $i++) {
            if($dts[$i]->emp_id==$emp_id){
                $dts[$i]->eper_id=$eper_id;
            }
        }
    }
    /**
     * Function listadoUsuariosP
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @param      
     * @return  
     */
    
    /* ACTUALIZAR DATOS */
    public function actualizarUsuarioEmpRolGru($data) {
        $arroout = array();
        $con = \Yii::$app->db;
        $trans = $con->beginTransaction();
        try {
            $data = isset($data['DATA']) ? $data['DATA'] : array();
            //$usu_id=$data[0]['usu_id'];
            $this->actualizarDataPersona($con, $data);
            $this->deletePersGrupRol($con, $data);
            //$this->actualizarDataGrupoRol($con, $data);
            $dts=json_decode($data[0]['data_Empresa']);
            for ($i = 0; $i < sizeof($dts); $i++) {
                $data[0]['emp_id']=$dts[$i]->emp_id;
                $data[0]['gru_id']=$dts[$i]->gru_id;
                $data[0]['rol_id']=$dts[$i]->rol_id;
                $eper_id=$empPers->insertarDataEmpresaPersona($con, $data);
                $data[0]['eper_id']=$eper_id;                
                $grol_id=$gruRol->insertarDataGrupRol($con, $data);
                $data[0]['grol_id']=$grol_id; 
                $usuGREP->insertarDataUsuaGrolEper($con, $data);
            }
            
            $trans->commit();
            $con->close();
            //RETORNA DATOS 
            $arroout["status"]= true;
            return $arroout;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();
            //throw $e;
            $arroout["status"]= false;
            return $arroout;
        }
    }
    
    /**
     * Function listadoUsuariosP
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function actualizarDataPersona($con, $data) {
        $sql = "UPDATE " . $con->dbname . ".persona
                    SET per_pri_nombre = :per_pri_nombre,per_pri_apellido = :per_pri_apellido,
                    per_fecha_nacimiento=:per_fecha_nacimiento,per_celular=:per_celular,
                    per_genero=:per_genero,per_correo=:per_correo,per_fecha_modificacion=CURRENT_TIMESTAMP()
                  WHERE per_id = :per_id ; ";
        
        $command = $con->createCommand($sql);
        $command->bindParam(":per_id", $data[0]['per_id'], \PDO::PARAM_INT); //Id Comparacion
        $command->bindParam(":per_pri_nombre", $data[0]['per_pri_nombre'], \PDO::PARAM_STR);
        $command->bindParam(":per_pri_apellido", $data[0]['per_pri_apellido'], \PDO::PARAM_STR);        
        $command->bindParam(":per_fecha_nacimiento", $data[0]['per_fecha_nacimiento'], \PDO::PARAM_STR);
        $command->bindParam(":per_celular", $data[0]['per_celular'], \PDO::PARAM_STR);
        $command->bindParam(":per_genero", $data[0]['per_genero'], \PDO::PARAM_STR);
        $command->bindParam(":per_correo", $data[0]['per_correo'], \PDO::PARAM_STR);        
        $command->execute();
    }
    public function actualizarDataGrupoRol($con, $data) {
        $sql = "UPDATE " . $con->dbname . ".grup_rol
                        SET gru_id = :gru_id,rol_id = :rol_id,grol_fecha_modificacion = CURRENT_TIMESTAMP()
                    WHERE grol_id = :grol_id; ";
        $command = $con->createCommand($sql);
        $command->bindParam(":grol_id", $data[0]['grol_id'], \PDO::PARAM_INT); //Id Comparacion
        $command->bindParam(":gru_id", $data[0]['gru_id'], \PDO::PARAM_INT);
        $command->bindParam(":rol_id", $data[0]['rol_id'], \PDO::PARAM_INT);
        $command->execute();
    }
    
    private function deletePersGrupRol($con,$data) {        
        $sql = "DELETE FROM " . $con->dbname . ".usua_grol_eper WHERE usu_id=:usu_id";
        $command = $con->createCommand($sql);
        //$command->bindParam(":eper_id", $dts[$i]->eper_id, PDO::PARAM_INT);
        $command->bindParam(":usu_id", $data[0]['usu_id'], PDO::PARAM_INT);
        $command->execute();
        
        $sql = "DELETE FROM " . $con->dbname . ".grup_rol "
                    . " WHERE eper_id IN "
                    . "  (SELECT eper_id FROM db_asgard.empresa_persona WHERE per_id=:per_id);" ;                     
        $command = $con->createCommand($sql);
        $command->bindParam(":per_id", $data[0]['per_id'], PDO::PARAM_INT);
        $command->execute();
        

        $sql = "DELETE FROM " . $con->dbname . ".empresa_persona WHERE per_id=:per_id ";
        $command = $con->createCommand($sql);
        $command->bindParam(":per_id", $data[0]['per_id'], PDO::PARAM_INT);
        $command->execute();
    }
    
    /**
     * Function eliminar Grupo ROl
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @param      
     * @return  
     */
    public static function eliminarUsuarioGruRol($data) {
        $con = \Yii::$app->db;
        $trans = $con->beginTransaction();
        try {
            //$ids = isset($data['ids']) ? base64_decode($data['ids']) :NULL;
            $ids = isset($data['id']) ? $data['id'] :NULL;
            $sql = "UPDATE " . $con->dbname . ".usua_grol_eper "
                    . "SET ugep_estado=0 WHERE ugep_id=:ugep_id; ";            
            $command = $con->createCommand($sql);
            $command->bindParam(":ugep_id", $ids, \PDO::PARAM_INT);
            $command->execute();
            $trans->commit();
            $con->close();
            return true;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();
            //throw $e;
            return false;
        }
    }
    
    /**
     * Function listadoUsuariosP
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @param      
     * @return  
     */
    
    public function insertarDataUsuario($con,$data) {
        //usu_id
        $security = new Security();
        $hash = $this->generateAuthKey();
        $password = base64_encode($security->encryptByPassword($hash, $data[0]['usu_clave'])); 
        $sql = "INSERT INTO " . $con->dbname . ".usuario
            (per_id,usu_user,usu_sha,usu_password,usu_fecha_creacion,usu_estado,usu_estado_logico)VALUES
            (:per_id,:usu_user,:usu_sha,:usu_password,CURRENT_TIMESTAMP(),1,1) ";
        
        $command = $con->createCommand($sql);
        $command->bindParam(":per_id",$data[0]['per_id'], \PDO::PARAM_INT);
        $command->bindParam(":usu_user",$data[0]['usu_user'], \PDO::PARAM_STR);
        $command->bindParam(":usu_sha",$hash, \PDO::PARAM_STR);  
        $command->bindParam(":usu_password",$password, \PDO::PARAM_STR);        
        $command->execute();
        return $con->getLastInsertID();
    }
    
    /**
     * Function consultarDataUsuario para profesores.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param      
     * @return  
     */    
    public function consultarDataUsuario($id_inicio, $id_final) {
        $con = \Yii::$app->db;          
        $sql = "select usu_id, p.per_cedula from " . $con->dbname . ".usuario u inner join " . $con->dbname . ".persona p on (p.per_id = u.per_id) 
                where usu_id between :id_inicio and :id_final               
                 and u.usu_estado = '1' and p.per_estado = '1'
                ";
        $command = $con->createCommand($sql);
        $command->bindParam(":id_inicio", $id_inicio, \PDO::PARAM_INT);
        $command->bindParam(":id_final", $id_final, \PDO::PARAM_INT); 
        return $command->queryAll();        
    }
    
    /**
     * Function actualizarDataUsuario para profesores.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param      
     * @return  
     */    
    public function actualizarDataUsuario($usu_sha, $usu_pass, $usu_id) {
        $con = \Yii::$app->db;
        $trans = $con->beginTransaction();
        try {            
            
            $sql = "UPDATE " . $con->dbname . ".usuario 
                    SET usu_sha = :usu_sha,
                    usu_password= :usu_password,
                    usu_estado= 1
                    WHERE usu_id=:usu_id; ";
            $command = $con->createCommand($sql);
            $command->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
            $command->bindParam(":usu_sha", $usu_sha, \PDO::PARAM_STR);
            $command->bindParam(":usu_password", $usu_pass, \PDO::PARAM_STR);
            $command->execute();
            $trans->commit();
            $con->close();
            return true;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();            
            return false;
        }
    }  
    
    /**
     * Function consultarUsuarioNuevo.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param      
     * @return  
     */    
    public function consultarUsuarioNuevo() {
        $con = \Yii::$app->db;          
        $sql = "SELECT p.per_id, p.per_cedula, u.usu_id, u.usu_user, ue.ugep_id, ue.grol_id
                FROM db_academico.estudiante e inner join db_asgard.persona p on p.per_id = e.per_id
                    inner join db_asgard.usuario u on u.per_id = p.per_id
                    inner join db_asgard.usua_grol_eper ue on ue.usu_id = u.usu_id
                WHERE ue.grol_id in (30,31)
                    and p.per_estado = '1' and p.per_estado_logico = '1'";
        $command = $con->createCommand($sql);        
        return $command->queryAll();        
    }

    public static function getListUsers($search = NULL, $idempresa = NULL, $onlyData = false, $removeSelfUser = false){
        $iduser    = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = (isset($idempresa)?$idempresa:(Yii::$app->session->get('PB_idempresa', FALSE)));
        $search_cond = "%".$search."%";
        $condition = "";
        $str_search = "";
        $from = "";
        if($iduser != 1){
            $condition .= "eper.emp_id=:emp_id AND ";
            $condition .= "eper.eper_estado_logico=1 AND ";
            $condition .= "eper.eper_estado=1 AND ";
            $condition .= "emp.emp_estado=1 AND ";
            $condition .= "emp.emp_estado_logico=1 AND ";
            $condition .= "usu.usu_id <> 1 AND ";
            if($removeSelfUser){
                $condition .= "usu.usu_id <> $iduser AND ";
            }
            $from .= "INNER JOIN empresa_persona as eper on eper.per_id=per.per_id ";
            $from .= "INNER JOIN empresa as emp on emp.emp_id=eper.emp_id ";
        }
        if(isset($search)){
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "usu.usu_user like :search) AND ";
        }
        $sql = "SELECT 
                    usu.usu_id as id,
                    usu.usu_user as Username,
                    per.per_pri_nombre as Nombres,
                    per.per_pri_apellido as Apellidos,
                    CONCAT(per.per_pri_apellido, ' ', per.per_pri_nombre) as Persona
                    -- emp.emp_nombre_comercial as Empresa
                FROM 
                    usuario as usu
                    INNER JOIN persona as per on per.per_id=usu.per_id
                    $from
                WHERE 
                    $condition 
                    $str_search
                    usu.usu_estado_logico=1 AND 
                    usu.usu_estado=1 AND 
                    per.per_estado_logico=1 AND 
                    per.per_estado=1 
                GROUP BY usu.usu_id, usu.usu_user, per.per_pri_nombre, per.per_pri_apellido
                ORDER BY per.per_pri_apellido;";
        $comando = Yii::$app->db->createCommand($sql);
        if($iduser != 1){
            $comando->bindParam(":emp_id",$emp_id, \PDO::PARAM_INT);
        }
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($onlyData)   return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Username', 'Nombres', 'Apellidos', 'Empresa'],
            ],
        ]);
        
        return $dataProvider;
    }
    /**
     * Function consultarIdPersonaICP
     * @author  Giovanni Vergara
     * @property
     * @return
     */
    public function consultarIdUsuarioICP($per_id=null) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $qryPer = "";
        if(isset($per_id)){
            $qryPer = "per_id=$per_id and ";
        }
        $sql = "
                SELECT ifnull(usu_id,0) as usu_id
                FROM usuario
                WHERE
                    $qryPer
                    usu_estado = $estado AND
                    usu_estado_logico=$estado
              ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if(empty($resultData['usu_id']))
            return 0;
        else {
            return $resultData['usu_id'];
        }
    }
}
