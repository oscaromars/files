<?php

namespace app\modules\repositorio\models;

use Yii;

/**
 * This is the model class for table "estandar".
 *
 * @property int $est_id
 * @property int $com_id
 * @property int $fun_id
 * @property string $est_codificacion
 * @property string $est_nombre
 * @property string $est_descripcion
 * @property int $est_usuario_ingreso
 * @property int $est_usuario_modifica
 * @property string $est_estado
 * @property string $est_fecha_creacion
 * @property string $est_fecha_modificacion
 * @property string $est_estado_logico
 *
 * @property DocumentoRepositorio[] $documentoRepositorios
 * @property Componente $com
 * @property Funcion $fun
 */
class Estandar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estandar';
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
            [['com_id', 'fun_id', 'est_usuario_ingreso', 'est_usuario_modifica'], 'integer'],
            [['fun_id', 'est_nombre', 'est_descripcion', 'est_usuario_ingreso', 'est_estado', 'est_estado_logico'], 'required'],
            [['est_fecha_creacion', 'est_fecha_modificacion'], 'safe'],
            [['est_codificacion'], 'string', 'max' => 100],
            [['est_nombre'], 'string', 'max' => 300],
            [['est_descripcion'], 'string', 'max' => 500],
            [['est_estado', 'est_estado_logico'], 'string', 'max' => 1],
            [['com_id'], 'exist', 'skipOnError' => true, 'targetClass' => Componente::className(), 'targetAttribute' => ['com_id' => 'com_id']],
            [['fun_id'], 'exist', 'skipOnError' => true, 'targetClass' => Funcion::className(), 'targetAttribute' => ['fun_id' => 'fun_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'est_id' => 'Est ID',
            'com_id' => 'Com ID',
            'fun_id' => 'Fun ID',
            'est_codificacion' => 'Est Codificacion',
            'est_nombre' => 'Est Nombre',
            'est_descripcion' => 'Est Descripcion',
            'est_usuario_ingreso' => 'Est Usuario Ingreso',
            'est_usuario_modifica' => 'Est Usuario Modifica',
            'est_estado' => 'Est Estado',
            'est_fecha_creacion' => 'Est Fecha Creacion',
            'est_fecha_modificacion' => 'Est Fecha Modificacion',
            'est_estado_logico' => 'Est Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentoRepositorios()
    {
        return $this->hasMany(DocumentoRepositorio::className(), ['est_id' => 'est_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCom()
    {
        return $this->hasOne(Componente::className(), ['com_id' => 'com_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFun()
    {
        return $this->hasOne(Funcion::className(), ['fun_id' => 'fun_id']);
    }
    
    public function consultarEstandar($fun_id, $comp_id) {
        $con = \Yii::$app->db_repositorio;
        $estado = 1;
        if (empty($comp_id)){
            $com_id = 0;
        }else {
            $com_id = $comp_id;
        }        
        $sql = "SELECT est.est_id id, est_nombre name
                FROM " . $con->dbname . ".estandar est left join " . $con->dbname . ".componente c 
                    on c.com_id = est.com_id
                    inner join " . $con->dbname . ".funcion f on f.fun_id = est.fun_id
                WHERE est.fun_id = :fun_id and ifnull(est.com_id,0)=:comp_id
                      and est.est_estado = :estado
                      and est.est_estado_logico = :estado;
                ORDER BY est_nombre ASC";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":fun_id", $fun_id, \PDO::PARAM_INT);
        $comando->bindParam(":comp_id", $com_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
