<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "periodo_metodo_ingreso".
 *
 * @property integer $pmin_id
 * @property integer $pmin_anio
 * @property integer $pmin_mes
 * @property integer $nint_id
 * @property integer $ming_id
 * @property string $pmin_codigo
 * @property string $pmin_descripcion
 * @property string $pmin_fecha_desde
 * @property string $pmin_fecha_hasta
 * @property integer $pmin_usuario_ingreso
 * @property integer $pmin_usuario_modifica
 * @property string $pmin_estado
 * @property string $pmin_fecha_creacion
 * @property string $pmin_fecha_modificacion
 * @property string $pmin_estado_logico
 *
 * @property Curso[] $cursos
 */
class PeriodoMetodoIngreso extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'periodo_metodo_ingreso';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_academico');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['pmin_anio', 'pmin_mes', 'nint_id', 'ming_id', 'pmin_codigo', 'pmin_descripcion', 'pmin_usuario_ingreso', 'pmin_estado', 'pmin_estado_logico'], 'required'],
            [['pmin_anio', 'pmin_mes', 'nint_id', 'ming_id', 'pmin_usuario_ingreso', 'pmin_usuario_modifica'], 'integer'],
            [['pmin_fecha_desde', 'pmin_fecha_hasta', 'pmin_fecha_creacion', 'pmin_fecha_modificacion'], 'safe'],
            [['pmin_codigo'], 'string', 'max' => 10],
            [['pmin_descripcion'], 'string', 'max' => 100],
            [['pmin_estado', 'pmin_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'pmin_id' => 'Pmin ID',
            'pmin_anio' => 'Pmin Anio',
            'pmin_mes' => 'Pmin Mes',
            'nint_id' => 'Nint ID',
            'ming_id' => 'Ming ID',
            'pmin_codigo' => 'Pmin Codigo',
            'pmin_descripcion' => 'Pmin Descripcion',
            'pmin_fecha_desde' => 'Pmin Fecha Desde',
            'pmin_fecha_hasta' => 'Pmin Fecha Hasta',
            'pmin_usuario_ingreso' => 'Pmin Usuario Ingreso',
            'pmin_usuario_modifica' => 'Pmin Usuario Modifica',
            'pmin_estado' => 'Pmin Estado',
            'pmin_fecha_creacion' => 'Pmin Fecha Creacion',
            'pmin_fecha_modificacion' => 'Pmin Fecha Modificacion',
            'pmin_estado_logico' => 'Pmin Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCursos() {
        return $this->hasMany(Curso::className(), ['pmin_id' => 'pmin_id']);
    }

    

    /**
     * Function modificAsignacion
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @property integer $userid       
     * @return  
     */
    public function modificAsignacion($asp_id, $sins_id, $usuario_modifica) {
        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        $estado = 1;
        $estado_inactiva = 0;
        $fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".asignacion_curso 		       
                      SET 
                        acur_usuario_modificacion = :usuario_modifica,
                        acur_fecha_modificacion = :fecha_modificacion,
                        acur_estado = :estado_inactiva                      
                      WHERE 
                        asp_id = :asp_id AND 
                        sins_id = :sins_id AND                        
                        acur_estado = :estado AND
                        acur_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":estado_inactiva", $estado_inactiva, \PDO::PARAM_STR);
            $comando->bindParam(":usuario_modifica", $usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":asp_id", $asp_id, \PDO::PARAM_INT);
            $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

}
