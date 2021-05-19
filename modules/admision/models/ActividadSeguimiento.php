<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "actividad_seguimiento".
 *
 * @property int $aseg_id
 * @property int $bseg_id
 * @property int $bact_id
 * @property string $aseg_estado
 * @property string $aseg_fecha_creacion
 * @property string $aseg_fecha_modificacion
 * @property string $aseg_estado_logico
 */
class ActividadSeguimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actividad_seguimiento';
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
            [['bseg_id', 'bact_id', 'aseg_estado', 'aseg_estado_logico'], 'required'],
            [['bseg_id', 'bact_id'], 'integer'],
            [['aseg_fecha_creacion', 'aseg_fecha_modificacion'], 'safe'],
            [['aseg_estado', 'aseg_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'aseg_id' => 'Aseg ID',
            'bseg_id' => 'Bseg ID',
            'bact_id' => 'Bact ID',
            'aseg_estado' => 'Aseg Estado',
            'aseg_fecha_creacion' => 'Aseg Fecha Creacion',
            'aseg_fecha_modificacion' => 'Aseg Fecha Modificacion',
            'aseg_estado_logico' => 'Aseg Estado Logico',
        ];
    }

    public static function deleteAllActividadSeguimiento($id){
        $con = \Yii::$app->db_crm;
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $datemod = date('Y-m-d H:i:s');
        try{
            $sql = "UPDATE actividad_seguimiento p 
                SET p.aseg_estado='0', p.aseg_estado_logico='0', p.aseg_fecha_modificacion=:datemod 
                WHERE p.bact_id=:id AND p.aseg_estado_logico='1' AND p.aseg_estado='1';";

            $comando = $con->createCommand($sql);
            $comando->bindParam(':id' , $id, \PDO::PARAM_INT);
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
