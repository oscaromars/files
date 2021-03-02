<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "tipo_publicacion".
 *
 * @property int $tpub_id
 * @property string $tpub_nombre
 * @property string $tpub_descripcion
 * @property string $tpub_estado
 * @property string $tpub_fecha_creacion
 * @property string $tpub_fecha_modificacion
 * @property string $tpub_estado_logico
 *
 * @property DetallePublicacion[] $detallePublicacions
 */
class TipoPublicacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_publicacion';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_general');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tpub_estado', 'tpub_estado_logico'], 'required'],
            [['tpub_fecha_creacion', 'tpub_fecha_modificacion'], 'safe'],
            [['tpub_nombre', 'tpub_descripcion'], 'string', 'max' => 100],
            [['tpub_estado', 'tpub_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tpub_id' => 'Tpub ID',
            'tpub_nombre' => 'Tpub Nombre',
            'tpub_descripcion' => 'Tpub Descripcion',
            'tpub_estado' => 'Tpub Estado',
            'tpub_fecha_creacion' => 'Tpub Fecha Creacion',
            'tpub_fecha_modificacion' => 'Tpub Fecha Modificacion',
            'tpub_estado_logico' => 'Tpub Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetallePublicacions()
    {
        return $this->hasMany(DetallePublicacion::className(), ['tpub_id' => 'tpub_id']);
    }
    
    public function getTipoPublicacion(){                
        $estado = "1";
        $sql = "SELECT
                    tpub_id AS id,
                    tpub_descripcion AS nombre
                FROM tipo_publicacion
                WHERE tpub_estado_logico = :estado
                    AND tpub_estado = :estado
                ORDER BY tpub_id;";
        $comando = Yii::$app->db_general->createCommand($sql);        
        $comando->bindParam(":estado",$estado, \PDO::PARAM_STR);

        $res = $comando->queryAll();        
        return $res;
    }
}
