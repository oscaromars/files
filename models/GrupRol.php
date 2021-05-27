<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grup_rol".
 *
 * @property integer $grol_id
 * @property integer $gru_id
 * @property integer $rol_id
 * @property string $grol_estado
 * @property string $grol_fecha_creacion
 * @property string $grol_fecha_modificacion
 * @property string $grol_estado_logico
 *
 * @property GrupObmoGrupRol[] $grupObmoGrupRols
 * @property Grupo $gru
 * @property Rol $rol
 * @property UsuaGrolEper[] $usuaGrols
 */
class GrupRol extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grup_rol';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gru_id', 'rol_id', 'grol_estado', 'grol_estado_logico'], 'required'],
            [['gru_id', 'rol_id'], 'integer'],
            [['grol_fecha_creacion', 'grol_fecha_modificacion'], 'safe'],
            [['grol_estado', 'grol_estado_logico'], 'string', 'max' => 1],
            [['gru_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grupo::className(), 'targetAttribute' => ['gru_id' => 'gru_id']],
            [['rol_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rol::className(), 'targetAttribute' => ['rol_id' => 'rol_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'grol_id' => 'Grol ID',
            'gru_id' => 'Gru ID',
            'rol_id' => 'Rol ID',
            'grol_estado' => 'Grol Estado',
            'grol_fecha_creacion' => 'Grol Fecha Creacion',
            'grol_fecha_modificacion' => 'Grol Fecha Modificacion',
            'grol_estado_logico' => 'Grol Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupObmoGrupRols()
    {
        return $this->hasMany(GrupObmoGrupRol::className(), ['grol_id' => 'grol_id']);
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
    public function getRol()
    {
        return $this->hasOne(Rol::className(), ['rol_id' => 'rol_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuaGrolEpers()
    {
        return $this->hasMany(UsuaGrolEper::className(), ['grol_id' => 'grol_id']);
    }
    
    /**
     * Function listadoUsuariosP
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @param      
     * @return  
     */
    
    public function insertarDataGrupRol($con,$data) {
        //grol_id
        $sql = "INSERT INTO " . $con->dbname . ".grup_rol
            (gru_id,rol_id,grol_fecha_creacion,grol_estado,grol_estado_logico)VALUES
            (:gru_id,:rol_id,CURRENT_TIMESTAMP(),1,1) ";
       
        $command = $con->createCommand($sql);
        $command->bindParam(":gru_id",$data[0]['gru_id'], \PDO::PARAM_INT);
        $command->bindParam(":rol_id",$data[0]['rol_id'], \PDO::PARAM_INT);  
        
        $command->execute();
        return $con->getLastInsertID();
    }

    public function getRolesByGroup($gru_id){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $sql = "SELECT 
                    distinct r.rol_id as id,
                    r.rol_nombre as name
                FROM 
                    grupo as g 
                    INNER JOIN grup_rol as gr ON g.gru_id = gr.gru_id
                    INNER JOIN rol as r ON r.rol_id = gr.rol_id
                WHERE 
                    g.gru_id = :id AND
                    g.gru_estado=1 AND
                    r.rol_estado=1 AND
                    gr.grol_estado=1 AND
                    g.gru_estado_logico=1 AND 
                    r.rol_estado_logico=1 AND 
                    gr.grol_estado_logico=1 
                ORDER BY r.rol_id;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":id", $gru_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        return $res;
    }

     /** ***
     * Function Existe ROL Y GRUPO
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id      
     * @return  
     */
    public static function existeGrupoRol($gru_id = null,$rol_id = null) {
        $con = \Yii::$app->db;         
        $sql = "SELECT grol_id FROM " . $con->dbname . ".grup_rol 
            WHERE gru_id=:gru_id AND rol_id=:rol_id AND grol_estado_logico=1;";        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":gru_id", $gru_id, \PDO::PARAM_INT);
        $comando->bindParam(":rol_id", $rol_id, \PDO::PARAM_INT);
        //return $comando->queryAll();
        //$rawData=$comando->queryScalar();
        $rawData = $comando->queryOne(); 
        if(empty($rawData['grol_id']))
            return 0; //Falso si no Existe
        return $rawData['grol_id'];//Si Existe en la Tabla
        
    }
    
    /** ***
     * Function Obtiene grol_id a partir de Id Persona y Empresa
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id      
     * @return  
     */
    public function consultarIdUsuGrolEper($per_id,$emp_id) {
        $con = \Yii::$app->db;          
        $sql = "SELECT B.grol_id
                    FROM " . $con->dbname . ".empresa_persona A
                            INNER JOIN " . $con->dbname . ".usua_grol_eper B
                                    ON A.eper_id=B.eper_id
                WHERE A.eper_estado=1 AND A.eper_estado_logico=1 
                        AND A.per_id=:per_id AND A.emp_id=:emp_id ;";
       
        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $rawData=$comando->queryScalar();        
        if ($rawData === false)
            return 0; //Falso si no Existe
        return $rawData;//Si Existe en la Tabla
        
    }
    
}
