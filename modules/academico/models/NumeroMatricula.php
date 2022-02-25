<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "numero_matricula".
 *
 * @property int $nmat_id
 * @property int $nmat_codigo
 * @property string $nmat_descripcion
 * @property string $nmat_anio
 * @property string $nmat_numero
 * @property int $nmat_usuario_ingreso
 * @property int $nmat_usuario_modifica
 * @property string $nmat_estado
 * @property string $nmat_fecha_creacion
 * @property string $nmat_fecha_modificacion
 * @property string $nmat_estado_logico
 */
class NumeroMatricula extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'numero_matricula';
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
            [['nmat_codigo', 'nmat_descripcion', 'nmat_anio', 'nmat_estado', 'nmat_estado_logico'], 'required'],
            [['nmat_codigo', 'nmat_usuario_ingreso', 'nmat_usuario_modifica'], 'integer'],
            [['nmat_fecha_creacion', 'nmat_fecha_modificacion'], 'safe'],
            [['nmat_descripcion'], 'string', 'max' => 300],
            [['nmat_anio'], 'string', 'max' => 4],
            [['nmat_numero'], 'string', 'max' => 10],
            [['nmat_estado', 'nmat_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nmat_id' => 'Nmat ID',
            'nmat_codigo' => 'Nmat Codigo',
            'nmat_descripcion' => 'Nmat Descripcion',
            'nmat_anio' => 'Nmat Anio',
            'nmat_numero' => 'Nmat Numero',
            'nmat_usuario_ingreso' => 'Nmat Usuario Ingreso',
            'nmat_usuario_modifica' => 'Nmat Usuario Modifica',
            'nmat_estado' => 'Nmat Estado',
            'nmat_fecha_creacion' => 'Nmat Fecha Creacion',
            'nmat_fecha_modificacion' => 'Nmat Fecha Modificacion',
            'nmat_estado_logico' => 'Nmat Estado Logico',
        ];
    }
    /**
     * Function consultaNumatriculacod
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer
     * @return
     */
    public function consultaNumatriculacod($nmat_codigo) {
        $con = \Yii::$app->db_academico;
        //$numero = 0;
        $estado = 1;
                $sql = "SELECT nmat_id,
                            IFNULL(CAST(nmat_numero AS UNSIGNED),0) secuencia
                        FROM " . $con->dbname . ".numero_matricula
                        WHERE nmat_estado = :estado AND
                            nmat_estado_logico= :estado AND
                            nmat_codigo = :nmat_codigo";
                $sql .= "  ";
                $comando = $con->createCommand($sql);
                $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
                $comando->bindParam(":nmat_codigo", $nmat_codigo, \PDO::PARAM_INT);
                $resultData = $comando->queryOne();
                return $resultData;
    }
    /**
     * Function modifica numero_matricula.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function actualizarSecmatricula($nmat_id, $nmat_numero) {

        $con = \Yii::$app->db_academico;
        $nmat_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $nmat_usuario_modifica = @Yii::$app->session->get("PB_iduser");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".numero_matricula
                      SET nmat_numero = :nmat_numero,
                          nmat_usuario_modifica = :nmat_usuario_modifica,
                          nmat_fecha_modificacion = :nmat_fecha_modificacion
                      WHERE
                      nmat_id = :nmat_id ");
            $comando->bindParam(":nmat_id", $nmat_id, \PDO::PARAM_INT);
            $comando->bindParam(":nmat_usuario_modifica", $nmat_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":nmat_fecha_modificacion", $nmat_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":nmat_numero", $nmat_numero, \PDO::PARAM_STR);
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

     /**
     * Function consultaNumatricula año y secuencial
     * @author
     * @property integer
     * @return
     */
    public function consultaNumatricula() {
        \app\models\Utilities::putMessageLogFile('entro ???...: ');
        $con = \Yii::$app->db_academico;
        //$numero = 0;
        $estado = 1;
                $sql = "SELECT
                          nmat_id,
                          nmat_anio,
                          -- IFNULL(CAST(nmat_numero AS UNSIGNED),0) secuencia
                          nmat_numero as secuencia
                        FROM " . $con->dbname . ".numero_matricula
                        WHERE nmat_estado = :estado AND
                            nmat_estado_logico= :estado /*AND
                            nmat_anio = :nmat_anio */";
                //\app\models\Utilities::putMessageLogFile('sql...: '.$sql);
                $comando = $con->createCommand($sql);
                $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
                // $comando->bindParam(":nmat_anio", $nmat_anio, \PDO::PARAM_STR);
                $resultData = $comando->queryOne();
                return $resultData;
    }

    /**
     * Function modifica actualizarAniomatricula.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function actualizarAniomatricula($nmat_id, $nmat_anio) {

        $con = \Yii::$app->db_academico;
        $nmat_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $nmat_usuario_modifica = @Yii::$app->session->get("PB_iduser");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".numero_matricula
                      SET nmat_anio = :nmat_anio,
                          nmat_usuario_modifica = :nmat_usuario_modifica,
                          nmat_fecha_modificacion = :nmat_fecha_modificacion
                      WHERE
                      nmat_id = :nmat_id ");
            $comando->bindParam(":nmat_id", $nmat_id, \PDO::PARAM_INT);
            $comando->bindParam(":nmat_usuario_modifica", $nmat_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":nmat_fecha_modificacion", $nmat_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":nmat_anio", $nmat_anio, \PDO::PARAM_STR);
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
