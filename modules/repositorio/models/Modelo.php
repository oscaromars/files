<?php

namespace app\modules\repositorio\models;

use Yii;

/**
 * This is the model class for table "modelo".
 *
 * @property int $mod_id
 * @property string $mod_codificacion
 * @property string $mod_nombre
 * @property string $mod_descripcion
 * @property int $mod_usuario_ingreso
 * @property int $mod_usuario_modifica
 * @property string $mod_estado
 * @property string $mod_fecha_creacion
 * @property string $mod_fecha_modificacion
 * @property string $mod_estado_logico
 *
 * @property Funcion[] $funcions
 */
class Modelo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modelo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_repositorio');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mod_nombre', 'mod_descripcion', 'mod_usuario_ingreso', 'mod_estado', 'mod_estado_logico'], 'required'],
            [['mod_usuario_ingreso', 'mod_usuario_modifica'], 'integer'],
            [['mod_fecha_creacion', 'mod_fecha_modificacion'], 'safe'],
            [['mod_codificacion'], 'string', 'max' => 100],
            [['mod_nombre'], 'string', 'max' => 300],
            [['mod_descripcion'], 'string', 'max' => 500],
            [['mod_estado', 'mod_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mod_id' => 'Mod ID',
            'mod_codificacion' => 'Mod Codificacion',
            'mod_nombre' => 'Mod Nombre',
            'mod_descripcion' => 'Mod Descripcion',
            'mod_usuario_ingreso' => 'Mod Usuario Ingreso',
            'mod_usuario_modifica' => 'Mod Usuario Modifica',
            'mod_estado' => 'Mod Estado',
            'mod_fecha_creacion' => 'Mod Fecha Creacion',
            'mod_fecha_modificacion' => 'Mod Fecha Modificacion',
            'mod_estado_logico' => 'Mod Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFuncions()
    {
        return $this->hasMany(Funcion::className(), ['mod_id' => 'mod_id']);
    }
    
    public function consultarModelo() {
        $con = \Yii::$app->db_repositorio;
        $estado = 1;
        $sql = "
                    SELECT mod_id id, mod_nombre value
                    FROM 
                         " . $con->dbname . ".modelo                     
                    WHERE                         
                        mod_estado=:estado AND
                        mod_estado_logico=:estado                        
                    ORDER BY mod_nombre ASC
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
