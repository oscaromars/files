<?php

namespace app\models;

use Yii;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "grup_obmo".
 *
 * @property int $gmod_id
 * @property int $gru_id
 * @property int $omod_id
 * @property string $gmod_estado
 * @property string $gmod_fecha_creacion
 * @property string $gmod_fecha_modificacion
 * @property string $gmod_estado_logico
 *
 * @property Grupo $gru
 * @property ObjetoModulo $omod
 * @property GrupObmoGrupRol[] $grupObmoGrupRols
 */
class GrupObmo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grup_obmo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gru_id', 'omod_id', 'gmod_estado', 'gmod_estado_logico'], 'required'],
            [['gru_id', 'omod_id'], 'integer'],
            [['gmod_fecha_creacion', 'gmod_fecha_modificacion'], 'safe'],
            [['gmod_estado', 'gmod_estado_logico'], 'string', 'max' => 1],
            [['gru_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grupo::className(), 'targetAttribute' => ['gru_id' => 'gru_id']],
            [['omod_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjetoModulo::className(), 'targetAttribute' => ['omod_id' => 'omod_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gmod_id' => 'Gmod ID',
            'gru_id' => 'Gru ID',
            'omod_id' => 'Omod ID',
            'gmod_estado' => 'Gmod Estado',
            'gmod_fecha_creacion' => 'Gmod Fecha Creacion',
            'gmod_fecha_modificacion' => 'Gmod Fecha Modificacion',
            'gmod_estado_logico' => 'Gmod Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGru()
    {
        return $this->hasOne(Grupo::className(), ['gru_id' => 'gru_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOmod()
    {
        return $this->hasOne(ObjetoModulo::className(), ['omod_id' => 'omod_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupObmoGrupRols()
    {
        return $this->hasMany(GrupObmoGrupRol::className(), ['gmod_id' => 'gmod_id']);
    }
    
    public function getObjModuleByGrol($id_grol){
        $sql = "SELECT 
                    om.omod_id
                FROM 
                    grup_rol as gr 
                    INNER JOIN grup_obmo go ON go.gru_id = gr.gru_id
                    INNER JOIN objeto_modulo om ON om.omod_id = go.omod_id
                WHERE 
                    gr.grol_estado=1 AND 
                    go.gmod_estado=1 AND
                    om.omod_estado=1 AND
                    gr.grol_id = :id AND 
                    gr.grol_estado_logico=1 AND 
                    go.gmod_estado_logico=1 AND
                    om.omod_estado_logico=1 
                ORDER BY gr.grol_id;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":id",$id_grol, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        return $res;
    }
    
    public function getObjModuleNameByGroup($id_grol){
        $sql = "SELECT 
                    gr.grol_id as id,
                    om.omod_nombre as ObjNombre
                FROM 
                    grup_rol as gr 
                    INNER JOIN grup_obmo go ON go.gru_id = gr.gru_id
                    INNER JOIN objeto_modulo om ON om.omod_id = go.omod_id
                WHERE 
                    gr.grol_estado=1 AND 
                    go.gmod_estado=1 AND
                    om.omod_estado=1 AND
                    gr.grol_id = :id AND 
                    gr.grol_estado_logico=1 AND 
                    go.gmod_estado_logico=1 AND
                    om.omod_estado_logico=1 
                ORDER BY gr.grol_id;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":id",$id_grol, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        return $res;
    }
    
    function getAllGrupObmoGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search  = "(g.gru_nombre like :search OR ";
            $str_search .= "r.rol_nombre like :search) AND ";
            //$str_search .= "om.omod_nombre like :search) AND ";
        }
        $sql = "SELECT 
                    distinct gr.grol_id as id,
                    g.gru_id as GrupoId,
                    g.gru_nombre as Grupo,
                    r.rol_nombre as Rol,
                    go.gmod_estado as Estado
                FROM 
                    grup_rol as gr 
                    INNER JOIN grupo as g ON gr.gru_id = g.gru_id 
                    INNER JOIN rol as r ON r.rol_id = gr.rol_id
                    LEFT OUTER JOIN grup_obmo go ON go.gru_id = gr.gru_id
                    LEFT OUTER JOIN objeto_modulo om ON om.omod_id = go.omod_id
                WHERE 
                    $str_search
                    -- g.gru_estado=1 AND
                    -- r.rol_estado=1 AND
                    -- om.omod_estado=1 AND 
                    gr.grol_estado=1 AND 
                    go.gmod_estado=1 AND
                    g.gru_estado_logico=1 AND 
                    r.rol_estado_logico=1 AND 
                    om.omod_estado_logico=1 AND 
                    go.gmod_estado_logico=1 AND
                    gr.grol_estado_logico=1  
                ORDER BY g.gru_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Grupo', 'Rol', 'GrupoId', 'Estado'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
}
