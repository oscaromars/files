<?php

namespace app\modules\repositorio\models;

use Yii;

/**
 * This is the model class for table "funcion".
 *
 * @property int $fun_id
 * @property int $mod_id
 * @property string $fun_codificacion
 * @property string $fun_nombre
 * @property string $fun_descripcion
 * @property int $fun_usuario_ingreso
 * @property int $fun_usuario_modifica
 * @property string $fun_estado
 * @property string $fun_fecha_creacion
 * @property string $fun_fecha_modificacion
 * @property string $fun_estado_logico
 *
 * @property Estandar[] $estandars
 * @property Modelo $mod
 */
class Funcion extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'funcion';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_repositorio');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['mod_id', 'fun_nombre', 'fun_descripcion', 'fun_usuario_ingreso', 'fun_estado', 'fun_estado_logico'], 'required'],
            [['mod_id', 'fun_usuario_ingreso', 'fun_usuario_modifica'], 'integer'],
            [['fun_fecha_creacion', 'fun_fecha_modificacion'], 'safe'],
            [['fun_codificacion'], 'string', 'max' => 100],
            [['fun_nombre'], 'string', 'max' => 300],
            [['fun_descripcion'], 'string', 'max' => 500],
            [['fun_estado', 'fun_estado_logico'], 'string', 'max' => 1],
            [['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modelo::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'fun_id' => 'Fun ID',
            'mod_id' => 'Mod ID',
            'fun_codificacion' => 'Fun Codificacion',
            'fun_nombre' => 'Fun Nombre',
            'fun_descripcion' => 'Fun Descripcion',
            'fun_usuario_ingreso' => 'Fun Usuario Ingreso',
            'fun_usuario_modifica' => 'Fun Usuario Modifica',
            'fun_estado' => 'Fun Estado',
            'fun_fecha_creacion' => 'Fun Fecha Creacion',
            'fun_fecha_modificacion' => 'Fun Fecha Modificacion',
            'fun_estado_logico' => 'Fun Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstandars() {
        return $this->hasMany(Estandar::className(), ['fun_id' => 'fun_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMod() {
        return $this->hasOne(Modelo::className(), ['mod_id' => 'mod_id']);
    }

    public function consultarFuncion($mod_id) {
        $con = \Yii::$app->db_repositorio;
        $estado = 1;
        $sql = "
                    SELECT fun_id as id, fun_nombre as name  
                    FROM 
                         " . $con->dbname . ".funcion
                    WHERE mod_id = :mod_id AND
                        fun_estado=:estado AND
                        fun_estado_logico=:estado
                    ORDER BY fun_id ASC
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

}
