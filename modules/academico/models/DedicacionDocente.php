<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "dedicacion_docente".
 *
 * @property int $ddoc_id
 * @property string $ddoc_nombre
 * @property string $ddoc_estado
 * @property string $ddoc_fecha_creacion
 * @property string $ddoc_fecha_modificacion
 * @property string $ddoc_estado_logico
 *
 * @property Distributivo[] $distributivos
 */
class DedicacionDocente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dedicacion_docente';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ddoc_estado', 'ddoc_estado_logico'], 'required'],
            [['ddoc_fecha_creacion', 'ddoc_fecha_modificacion'], 'safe'],
            [['ddoc_nombre'], 'string', 'max' => 100],
            [['ddoc_estado', 'ddoc_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ddoc_id' => 'Ddoc ID',
            'ddoc_nombre' => 'Ddoc Nombre',
            'ddoc_estado' => 'Ddoc Estado',
            'ddoc_fecha_creacion' => 'Ddoc Fecha Creacion',
            'ddoc_fecha_modificacion' => 'Ddoc Fecha Modificacion',
            'ddoc_estado_logico' => 'Ddoc Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivos()
    {
        return $this->hasMany(Distributivo::className(), ['ddoc_id' => 'ddoc_id']);
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivos()
    {
        return $this->hasMany(Distributivo::className(), ['ddoc_id' => 'ddoc_id']);
    }
    
    public function getDedicacionDocente() {
             $con_academico = \Yii::$app->db_academico;

        $sql = "SELECT
                    ddoc_id AS Id,
                   ddoc_nombre AS name
                FROM 
                    " . $con_academico->dbname . ".dedicacion_docente 
                WHERE 
                    ddoc_estado = 1 AND 
                    ddoc_estado_logico = 1";
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();
        return $res;  
    }
}
