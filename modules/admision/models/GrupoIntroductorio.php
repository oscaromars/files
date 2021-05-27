<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "grupo_introductorio".
 *
 * @property int $gint_id
 * @property string $gint_nombre
 * @property string $gint_descripcion
 * @property string $gint_estado
 * @property int $gint_usuario
 * @property int $gint_usuario_modif
 * @property string $gint_fecha_creacion
 * @property string $gint_fecha_modificacion
 * @property string $gint_estado_logico
 *
 * @property InscritoMaestria[] $inscritoMaestrias
 */
class GrupoIntroductorio extends \app\modules\admision\components\CActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grupo_introductorio';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_crm');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gint_estado', 'gint_usuario', 'gint_estado_logico'], 'required'],
            [['gint_usuario', 'gint_usuario_modif'], 'integer'],
            [['gint_fecha_creacion', 'gint_fecha_modificacion'], 'safe'],
            [['gint_nombre'], 'string', 'max' => 250],
            [['gint_descripcion'], 'string', 'max' => 500],
            [['gint_estado', 'gint_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gint_id' => 'Gint ID',
            'gint_nombre' => 'Gint Nombre',
            'gint_descripcion' => 'Gint Descripcion',
            'gint_estado' => 'Gint Estado',
            'gint_usuario' => 'Gint Usuario',
            'gint_usuario_modif' => 'Gint Usuario Modif',
            'gint_fecha_creacion' => 'Gint Fecha Creacion',
            'gint_fecha_modificacion' => 'Gint Fecha Modificacion',
            'gint_estado_logico' => 'Gint Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInscritoMaestrias()
    {
        return $this->hasMany(InscritoMaestria::className(), ['gint_id' => 'gint_id']);
    }
    
    
    /**
     * Function grupo introductorio
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarGrupoIntroductorio() {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT gint_id id, gint_nombre value
                FROM " . $con->dbname . ".grupo_introductorio 
                WHERE gint_estado = :estado 
                      and gint_estado_logico = :estado
                ORDER BY gint_nombre ASC";                       
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryall();
        return $resultData;
    }

}
