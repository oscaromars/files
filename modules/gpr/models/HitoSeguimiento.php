<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "hito_seguimiento".
 *
 * @property int $hseg_id
 * @property int $hito_id
 * @property string $hseg_nombre
 * @property string $hseg_descripcion
 * @property string $hseg_fecha_compromiso
 * @property string|null $hseg_fecha_real
 * @property float $hseg_presupuesto
 * @property string $hseg_cumplido
 * @property string $hseg_peso
 * @property string $hseg_progreso
 * @property string $hseg_cumplimiento_poa
 * @property string|null $hseg_cerrado
 * @property int $hseg_usuario_ingreso
 * @property int|null $hseg_usuario_modifica
 * @property string $hseg_estado
 * @property string $hseg_fecha_creacion
 * @property string|null $hseg_fecha_modificacion
 * @property string $hseg_estado_logico
 *
 * @property HitoAnexo[] $hitoAnexos
 * @property Hito $hito
 */
class HitoSeguimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hito_seguimiento';
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
            [['hito_id', 'hseg_nombre', 'hseg_descripcion', 'hseg_fecha_compromiso', 'hseg_presupuesto', 'hseg_peso', 'hseg_progreso', 'hseg_cumplimiento_poa', 'hseg_usuario_ingreso', 'hseg_estado', 'hseg_estado_logico'], 'required'],
            [['hito_id', 'hseg_usuario_ingreso', 'hseg_usuario_modifica'], 'integer'],
            [['hseg_fecha_compromiso', 'hseg_fecha_real', 'hseg_fecha_creacion', 'hseg_fecha_modificacion'], 'safe'],
            [['hseg_presupuesto'], 'number'],
            [['hseg_nombre'], 'string', 'max' => 300],
            [['hseg_descripcion'], 'string', 'max' => 500],
            [['hseg_cumplido', 'hseg_cerrado', 'hseg_estado', 'hseg_estado_logico'], 'string', 'max' => 1],
            [['hseg_peso', 'hseg_progreso', 'hseg_cumplimiento_poa'], 'string', 'max' => 10],
            [['hito_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hito::className(), 'targetAttribute' => ['hito_id' => 'hito_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hseg_id' => 'Hseg ID',
            'hito_id' => 'Hito ID',
            'hseg_nombre' => 'Hseg Nombre',
            'hseg_descripcion' => 'Hseg Descripcion',
            'hseg_fecha_compromiso' => 'Hseg Fecha Compromiso',
            'hseg_fecha_real' => 'Hseg Fecha Real',
            'hseg_presupuesto' => 'Hseg Presupuesto',
            'hseg_cumplido' => 'Hseg Cumplido',
            'hseg_peso' => 'Hseg Peso',
            'hseg_progreso' => 'Hseg Progreso',
            'hseg_cumplimiento_poa' => 'Hseg Cumplimiento Poa',
            'hseg_cerrado' => 'Hseg Cerrado',
            'hseg_usuario_ingreso' => 'Hseg Usuario Ingreso',
            'hseg_usuario_modifica' => 'Hseg Usuario Modifica',
            'hseg_estado' => 'Hseg Estado',
            'hseg_fecha_creacion' => 'Hseg Fecha Creacion',
            'hseg_fecha_modificacion' => 'Hseg Fecha Modificacion',
            'hseg_estado_logico' => 'Hseg Estado Logico',
        ];
    }

    /**
     * Gets query for [[HitoAnexos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHitoAnexos()
    {
        return $this->hasMany(HitoAnexo::className(), ['hseg_id' => 'hseg_id']);
    }

    /**
     * Gets query for [[Hito]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHito()
    {
        return $this->hasOne(Hito::className(), ['hito_id' => 'hito_id']);
    }

    public static function deleteAllHitosSegByIdHito($id){
        $con = \Yii::$app->db_gpr;
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $datemod = date('Y-m-d H:i:s');
        try{
            $sql = "UPDATE hito_seguimiento h
                SET h.hseg_estado_logico='0', h.hseg_estado='0', h.hseg_fecha_modificacion=:datemod, h.hseg_usuario_modifica=:iduser
                WHERE h.hito_id=:id AND h.hseg_estado_logico='1'";

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

    public static function deleteAllHitosSegByProId($id){
        $con = \Yii::$app->db_gpr;
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $datemod = date('Y-m-d H:i:s');
        try{
            $sql = "
                UPDATE hito_seguimiento h
                INNER JOIN hito ht ON h.hito_id = ht.hito_id
                INNER JOIN proyecto p ON p.pro_id = ht.pro_id
                SET h.hseg_estado_logico='0', h.hseg_estado='0', h.hseg_fecha_modificacion=:datemod, h.hseg_usuario_modifica=:iduser                
                WHERE p.pro_id=:id AND h.hseg_estado_logico='1'";

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
