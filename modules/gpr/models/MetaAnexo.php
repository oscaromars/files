<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "meta_anexo".
 *
 * @property int $mane_id
 * @property int $mseg_id
 * @property string $mane_nombre
 * @property string $mane_descripcion
 * @property string $mane_ruta
 * @property int $mane_usuario_ingreso
 * @property int|null $mane_usuario_modifica
 * @property string $mane_estado
 * @property string $mane_fecha_creacion
 * @property string|null $mane_fecha_modificacion
 * @property string $mane_estado_logico
 *
 * @property MetaSeguimiento $mseg
 */
class MetaAnexo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meta_anexo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gpr');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mseg_id', 'mane_nombre', 'mane_descripcion', 'mane_ruta', 'mane_usuario_ingreso', 'mane_estado', 'mane_estado_logico'], 'required'],
            [['mseg_id', 'mane_usuario_ingreso', 'mane_usuario_modifica'], 'integer'],
            [['mane_fecha_creacion', 'mane_fecha_modificacion'], 'safe'],
            [['mane_descripcion'], 'string'],
            [['mane_nombre'], 'string', 'max' => 300],
            [['mane_ruta'], 'string', 'max' => 500],
            [['mane_estado', 'mane_estado_logico'], 'string', 'max' => 1],
            [['mseg_id'], 'exist', 'skipOnError' => true, 'targetClass' => MetaSeguimiento::className(), 'targetAttribute' => ['mseg_id' => 'mseg_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mane_id' => 'Mane ID',
            'mseg_id' => 'Mseg ID',
            'mane_nombre' => 'Mane Nombre',
            'mane_descripcion' => 'Mane Descripcion',
            'mane_ruta' => 'Mane Ruta',
            'mane_usuario_ingreso' => 'Mane Usuario Ingreso',
            'mane_usuario_modifica' => 'Mane Usuario Modifica',
            'mane_estado' => 'Mane Estado',
            'mane_fecha_creacion' => 'Mane Fecha Creacion',
            'mane_fecha_modificacion' => 'Mane Fecha Modificacion',
            'mane_estado_logico' => 'Mane Estado Logico',
        ];
    }

    /**
     * Gets query for [[Mseg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMseg()
    {
        return $this->hasOne(MetaSeguimiento::className(), ['mseg_id' => 'mseg_id']);
    }

    public static function disableOldDocuments($id){
        $con = \Yii::$app->db_gpr;
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $datemod = date('Y-m-d H:i:s');
        try{
            $sql = "UPDATE meta_anexo p 
                SET p.mane_estado='0', p.mane_fecha_modificacion=:datemod, p.mane_usuario_modifica=:iduser
                WHERE p.mseg_id=:id AND p.mane_estado_logico='1' AND p.mane_estado='1';";

            $comando = $con->createCommand($sql);
            $comando->bindParam(':id' , $id, \PDO::PARAM_INT);
            $comando->bindParam(':iduser' , $iduser, \PDO::PARAM_INT);
            $comando->bindParam(':datemod' , $datemod, \PDO::PARAM_STR);
            $res = $comando->execute();
            if(isset($res)){
                return true;
            }else
                throw new \Exception('Error');
        }catch(\Exception $e){
            return false;
        }
    }
}
