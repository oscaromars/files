<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "modulo".
 *
 * @property integer $mod_id
 * @property integer $apl_id
 * @property string $mod_nombre
 * @property string $mod_tipo
 * @property string $mod_dir_imagen
 * @property string $mod_url
 * @property integer $mod_orden
 * @property string $mod_lang_file
 * @property string $mod_estado_visible
 * @property string $mod_estado
 * @property string $mod_fecha_creacion
 * @property string $mod_fecha_actualizacion
 * @property string $mod_estado_logico
 *
 * @property Aplicacion $apl
 * @property ObjetoModulo[] $objetoModulos
 */
class Modulo extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'modulo';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['apl_id', 'mod_estado', 'mod_estado_logico'], 'required'],
            [['apl_id', 'mod_orden'], 'integer'],
            [['mod_fecha_creacion', 'mod_fecha_actualizacion'], 'safe'],
            [['mod_nombre'], 'string', 'max' => 50],
            [['mod_tipo'], 'string', 'max' => 45],
            [['mod_dir_imagen', 'mod_url'], 'string', 'max' => 100],
            [['mod_lang_file'], 'string', 'max' => 60],
            [['mod_estado_visible', 'mod_estado', 'mod_estado_logico'], 'string', 'max' => 1],
            [['apl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Aplicacion::className(), 'targetAttribute' => ['apl_id' => 'apl_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mod_id' => 'Mod ID',
            'apl_id' => 'Apl ID',
            'mod_nombre' => 'Mod Nombre',
            'mod_tipo' => 'Mod Tipo',
            'mod_dir_imagen' => 'Mod Dir Imagen',
            'mod_url' => 'Mod Url',
            'mod_orden' => 'Mod Orden',
            'mod_lang_file' => 'Mod Lang File',
            'mod_estado_visible' => 'Mod Estado Visible',
            'mod_estado' => 'Mod Estado',
            'mod_fecha_creacion' => 'Mod Fecha Creacion',
            'mod_fecha_actualizacion' => 'Mod Fecha Actualizacion',
            'mod_estado_logico' => 'Mod Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApl() {
        return $this->hasOne(Aplicacion::className(), ['apl_id' => 'apl_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjetoModulos() {
        return $this->hasMany(ObjetoModulo::className(), ['mod_id' => 'mod_id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * Funcion que retorna los modulos que puede visualizar un usuario
     *
     * @access public
     * @author Eduardo Cueva <ecueva@penblu.com>
     * @return mixed $menu      Arreglos de Modulos
     */
    public function getModulos() {
        $iduser    = Yii::$app->session->get('PB_iduser', FALSE);
        $idempresa = Yii::$app->session->get('PB_idempresa', FALSE);
        $sql = "SELECT 
                    DISTINCT(modu.mod_id),modu.*
                FROM 
                    usua_grol_eper as ugep 
                    JOIN empresa_persona as eper on ugep.eper_id=eper.eper_id 
                    JOIN usuario as usu on ugep.usu_id=usu.usu_id 
                    JOIN grup_rol as grol on ugep.grol_id=grol.grol_id 
                    JOIN grup_obmo_grup_rol as gogr on grol.grol_id=gogr.grol_id 
                    JOIN grup_obmo as gob on gogr.gmod_id=gob.gmod_id 
                    JOIN objeto_modulo as omod on gob.omod_id=omod.omod_id 
                    JOIN modulo as modu on omod.mod_id=modu.mod_id 
                WHERE 
                    usu.usu_id=$iduser AND 
                    eper.emp_id=$idempresa AND 
                    usu.usu_estado_logico=1 AND 
                    usu.usu_estado=1 AND 
                    eper.eper_estado_logico=1 AND 
                    eper.eper_estado=1 AND 
                    grol.grol_estado_logico=1 AND 
                    grol.grol_estado=1 AND 
                    gogr.gogr_estado_logico=1 AND 
                    gogr.gogr_estado=1 AND 
                    gob.gmod_estado_logico=1 AND 
                    gob.gmod_estado=1 AND 
                    omod.omod_estado_logico=1 AND 
                    omod.omod_estado=1 AND 
                    modu.mod_estado_logico=1 AND 
                    modu.mod_estado=1 AND 
                    ugep.ugep_estado_logico=1 AND 
                    ugep.ugep_estado=1 
                ORDER BY modu.mod_orden;";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }

    /**
     * Funcion que retorna el link del primer modulo
     *
     * @access public
     * @author Eduardo Cueva <ecueva@penblu.com>
     * @return mixed $menu      Arreglos de Modulos
     */
    function getFirstModuleLink() {
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $idempresa = Yii::$app->session->get('PB_idempresa', FALSE);

        $sql = "SELECT 
                    omod_entidad AS url
                FROM 
                    grup_rol as grol 
                    JOIN usua_grol_eper as ugrol on ugrol.grol_id = grol.grol_id
                    JOIN usuario as usu on ugrol.usu_id=usu.usu_id 
                    JOIN empresa_persona as eper on ugrol.eper_id=eper.eper_id 
                    JOIN grup_obmo_grup_rol as gogr on grol.grol_id=gogr.grol_id 
                    JOIN grup_obmo as gob on gogr.gmod_id=gob.gmod_id 
                    JOIN objeto_modulo as omod on gob.omod_id=omod.omod_id 
                    JOIN modulo as modu on omod.mod_id=modu.mod_id 
                    JOIN empresa AS em ON eper.emp_id = em.emp_id
                WHERE 
                    usu.usu_id=$iduser AND 
                    eper.emp_id=$idempresa AND
                    usu.usu_estado_logico=1 AND 
                    usu.usu_estado=1 AND 
                    em.emp_estado_logico=1 AND 
                    em.emp_estado=1 AND 
                    eper.eper_estado_logico=1 AND 
                    eper.eper_estado=1 AND
                    ugrol.ugep_estado_logico=1 AND 
                    ugrol.ugep_estado=1 AND 
                    grol.grol_estado_logico=1 AND 
                    grol.grol_estado=1 AND 
                    gogr.gogr_estado_logico=1 AND 
                    gogr.gogr_estado=1 AND 
                    gob.gmod_estado_logico=1 AND 
                    gob.gmod_estado=1 AND 
                    omod.omod_estado_logico=1 AND 
                    omod.omod_estado=1 AND 
                    modu.mod_estado_logico=1 AND 
                    modu.mod_estado=1  
                ORDER BY modu.mod_orden;";
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        return $res;
    }
    
    function getAllModules($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search  = "(m.mod_nombre like :search OR ";
            $str_search .= "a.apl_nombre like :search OR ";
            $str_search .= "m.mod_tipo like :search) AND ";
        }
        $sql = "SELECT 
                    m.mod_id as id,
                    m.mod_nombre as Nombre,
                    m.mod_tipo as Tipo,
                    a.apl_nombre as Aplicacion,
                    m.mod_orden as Orden,
                    m.mod_estado as Estado
                FROM 
                    modulo as m 
                    INNER JOIN aplicacion as a on m.apl_id = a.apl_id
                WHERE 
                    $str_search
                    m.mod_estado_logico=1 AND 
                    -- m.mod_estado=1 AND 
                    -- a.apl_estado=1 AND
                    a.apl_estado_logico=1 
                ORDER BY m.mod_orden;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'mod_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'Aplicacion', 'Tipo', 'Orden', 'Estado'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

}
