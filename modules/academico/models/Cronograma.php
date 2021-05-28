<?php

namespace app\modules\academico\models;
use app\models\Utilities;
use Yii;

/**
 * This is the model class for table "cronograma".
 *
 * @property int $cro_id
 * @property int $uaca_id
 * @property int $paca_id
 * @property string $cro_archivo
 * @property string $cro_descripcion
 * @property int $cro_usuario_ingreso
 * @property int $cro_usuario_modifica
 * @property string $cro_estado
 * @property string $cro_fecha_creacion
 * @property string $cro_fecha_modificacion
 * @property string $cro_estado_logico
 *
 * @property UnidadAcademica $uaca
 * @property PeriodoAcademico $paca
 */
class Cronograma extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cronograma';
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
            [['uaca_id', 'paca_id', 'cro_archivo', 'cro_usuario_ingreso', 'cro_estado', 'cro_estado_logico'], 'required'],
            [['uaca_id', 'paca_id', 'cro_usuario_ingreso', 'cro_usuario_modifica'], 'integer'],
            [['cro_fecha_creacion', 'cro_fecha_modificacion'], 'safe'],
            [['cro_archivo', 'cro_descripcion'], 'string', 'max' => 500],
            [['cro_estado', 'cro_estado_logico'], 'string', 'max' => 1],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cro_id' => 'Cro ID',
            'uaca_id' => 'Uaca ID',
            'paca_id' => 'Paca ID',
            'cro_archivo' => 'Cro Archivo',
            'cro_descripcion' => 'Cro Descripcion',
            'cro_usuario_ingreso' => 'Cro Usuario Ingreso',
            'cro_usuario_modifica' => 'Cro Usuario Modifica',
            'cro_estado' => 'Cro Estado',
            'cro_fecha_creacion' => 'Cro Fecha Creacion',
            'cro_fecha_modificacion' => 'Cro Fecha Modificacion',
            'cro_estado_logico' => 'Cro Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaca()
    {
        return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaca()
    {
        return $this->hasOne(PeriodoAcademico::className(), ['paca_id' => 'paca_id']);
    }
     /**
     * Function grabar insertarCronograma
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec> 
     * @param
     * @return
     */
    public function insertarCronograma($uaca_id, $paca_id, $cro_archivo, $cro_descripcion, $cro_usuario_ingreso)
    {
        $con = \Yii::$app->db_academico;
        $estado = '1';
        $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
        $sql = "INSERT INTO " . $con->dbname . ".cronograma
        (uaca_id,paca_id,cro_archivo,cro_descripcion,cro_usuario_ingreso,cro_estado,
        cro_fecha_creacion,cro_estado_logico) VALUES
        (:uaca_id,:paca_id,:cro_archivo,:cro_descripcion,:cro_usuario_ingreso,
        :cro_estado,:cro_fecha_creacion,:cro_estado_logico)";
        $command = $con->createCommand($sql);
       
        $command->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $command->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $command->bindParam(":cro_archivo", $cro_archivo, \PDO::PARAM_STR);
        $command->bindParam(":cro_descripcion", $cro_descripcion, \PDO::PARAM_STR);
        $command->bindParam(":cro_usuario_ingreso", $cro_usuario_ingreso, \PDO::PARAM_STR);
        $command->bindParam(":cro_estado", $estado, \PDO::PARAM_STR);
        $command->bindParam(":cro_fecha_creacion", $fecha_registro, \PDO::PARAM_STR);
        $command->bindParam(":cro_estado_logico", $estado, \PDO::PARAM_STR);        
        $command->execute();
        return $con->getLastInsertID();        
    }

     /**
     * Function grabar consulta la informacin de Cronograma
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec> 
     * @param
     * @return
     */

    public function consultaCronograma($uaca_id, $paca_id) {
        $con = \Yii::$app->db_academico;        
        $estado = 1;
        $sql = "SELECT cro_archivo
                    FROM " . $con->dbname . ".cronograma                             
                WHERE cro_estado =:estado AND 
                      cro_estado_logico =:estado AND 
                      uaca_id =:uaca_id AND 
                      paca_id =:paca_id;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $rawData = $comando->queryOne();
        return $rawData;
    }
}
