<?php

namespace app\modules\repositorio\models;

use Yii;

/**
 * This is the model class for table "componente".
 *
 * @property int $com_id
 * @property string $com_codificacion
 * @property string $com_nombre
 * @property string $com_descripcion
 * @property int $com_usuario_ingreso
 * @property int $com_usuario_modifica
 * @property string $com_estado
 * @property string $com_fecha_creacion
 * @property string $com_fecha_modificacion
 * @property string $com_estado_logico
 *
 * @property Estandar[] $estandars
 */
class Componente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'componente';
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
            [['com_nombre', 'com_descripcion', 'com_usuario_ingreso', 'com_estado', 'com_estado_logico'], 'required'],
            [['com_usuario_ingreso', 'com_usuario_modifica'], 'integer'],
            [['com_fecha_creacion', 'com_fecha_modificacion'], 'safe'],
            [['com_codificacion'], 'string', 'max' => 100],
            [['com_nombre'], 'string', 'max' => 300],
            [['com_descripcion'], 'string', 'max' => 500],
            [['com_estado', 'com_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'com_id' => 'Com ID',
            'com_codificacion' => 'Com Codificacion',
            'com_nombre' => 'Com Nombre',
            'com_descripcion' => 'Com Descripcion',
            'com_usuario_ingreso' => 'Com Usuario Ingreso',
            'com_usuario_modifica' => 'Com Usuario Modifica',
            'com_estado' => 'Com Estado',
            'com_fecha_creacion' => 'Com Fecha Creacion',
            'com_fecha_modificacion' => 'Com Fecha Modificacion',
            'com_estado_logico' => 'Com Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstandars()
    {
        return $this->hasMany(Estandar::className(), ['com_id' => 'com_id']);
    }
    
    public function consultarComponente($fun_id) {
        $con = \Yii::$app->db_repositorio;
        $estado = 1;
        $sql = "SELECT distinct c.com_id as id, c.com_nombre as name
                FROM " . $con->dbname . ".componente c inner join " . $con->dbname . ".estandar e on c.com_id = e.com_id
                     inner join " . $con->dbname . ".funcion f on f.fun_id = e.fun_id
                WHERE e.fun_id = :fun_id
                    and c.com_estado = :estado
                    and c.com_estado_logico = :estado
                    and e.est_estado = :estado
                    and e.est_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $comando->bindParam(":fun_id", $fun_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
