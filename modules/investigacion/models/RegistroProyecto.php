<?php

namespace app\modules\investigacion\models;

use Yii;
use app\models\Utilities;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use yii\base\Exception;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "registro_proyecto".
 *
 * @property int $rpro_id
 * @property int $per_id
 * @property int $proy_id
 * @property int $linv_id
 * @property int $mpro_id
 * @property int $rfin_id
 * @property int $rpin_id
 * @property string $rpro_titulo
 * @property string $rpro_resumen
 * @property int $rpro_estado_formulario
 * @property int $rpro_estado
 * @property string $rpro_fecha_creacion
 * @property int $rpro_usuario_ingreso
 * @property int $rpro_usuario_modifica
 * @property string $rpro_fecha_modificacion
 * @property int $rpro_estado_logico
 *
 * @property RegistroAdicionales[] $registroAdicionales
 * @property RegistroAvances[] $registroAvances
 * @property RegistroIntegrante[] $registroIntegrantes
 * @property RegistroPlanificacion[] $registroPlanificacions
 * @property Proyectos $proy
 * @property LineaInvestigacion $linv
 * @property RegistroFinanciamiento $rfin
 * @property RegistroProgramaInvestigación $rpin
 */
class RegistroProyecto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_proyecto';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_investigacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['per_id', 'proy_id', 'linv_id', 'mpro_id',  'rpro_titulo', 'rpro_resumen', 'rpro_estado', 'rpro_usuario_ingreso', 'rpro_estado_logico'], 'required'],
            [['per_id', 'proy_id', 'linv_id', 'rfin_id', 'rpin_id', 'rpro_estado_formulario', 'rpro_estado', 'rpro_usuario_ingreso', 'rpro_usuario_modifica', 'rpro_estado_logico'], 'integer'],
            [['rpro_fecha_creacion', 'rpro_fecha_modificacion'], 'safe'],
            [['rpro_titulo'], 'string', 'max' => 350],
            [['rpro_resumen'], 'string', 'max' => 3000],
            [['proy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proyectos::className(), 'targetAttribute' => ['proy_id' => 'proy_id']],
            [['linv_id'], 'exist', 'skipOnError' => true, 'targetClass' => LineaInvestigacion::className(), 'targetAttribute' => ['linv_id' => 'linv_id']],
            [['rfin_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroFinanciamiento::className(), 'targetAttribute' => ['rfin_id' => 'rfin_id']],
            [['rpin_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroProgramaInvestigación::className(), 'targetAttribute' => ['rpin_id' => 'rpin_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rpro_id' => 'Rpro ID',
            'per_id' => 'Per ID',
            'proy_id' => 'Proy ID',
            'linv_id' => 'Linv ID',
            'mpro_id' => 'Mpro ID',
            'rfin_id' => 'Rfin ID',
            'rpin_id' => 'Rpin ID',
            'rpro_titulo' => 'Rpro Titulo',
            'rpro_resumen' => 'Rpro Resumen',
            'rpro_estado_formulario' => 'Rpro Estado Formulario',
            'rpro_estado' => 'Rpro Estado',
            'rpro_fecha_creacion' => 'Rpro Fecha Creacion',
            'rpro_usuario_ingreso' => 'Rpro Usuario Ingreso',
            'rpro_usuario_modifica' => 'Rpro Usuario Modifica',
            'rpro_fecha_modificacion' => 'Rpro Fecha Modificacion',
            'rpro_estado_logico' => 'Rpro Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroAdicionales()
    {
        return $this->hasMany(RegistroAdicionales::className(), ['rpro_id' => 'rpro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroAvances()
    {
        return $this->hasMany(RegistroAvances::className(), ['rpro_id' => 'rpro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroIntegrantes()
    {
        return $this->hasMany(RegistroIntegrante::className(), ['rpro_id' => 'rpro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroPlanificacions()
    {
        return $this->hasMany(RegistroPlanificacion::className(), ['rpro_id' => 'rpro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProy()
    {
        return $this->hasOne(Proyectos::className(), ['proy_id' => 'proy_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinv()
    {
        return $this->hasOne(LineaInvestigacion::className(), ['linv_id' => 'linv_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMpro()
    {
        return $this->hasOne(Macroproyecto::className(), ['mpro_id' => 'mpro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRfin()
    {
        return $this->hasOne(RegistroFinanciamiento::className(), ['rfin_id' => 'rfin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRpin()
    {
        return $this->hasOne(RegistroProgramaInvestigación::className(), ['rpin_id' => 'rpin_id']);
    }


    public function consultaDataDirectoProy($per_id) {
        $con = \Yii::$app->db_asgard;
        $estado=1;
        $sql = "SELECT
                    CONCAT(per_pri_nombre,' ',per_seg_nombre) as nombreDirector,
                    CONCAT(per_pri_apellido,' ',per_seg_apellido) as apellidoDirector,
                    per_cedula as cedula,
                    per_nacionalidad as nacionalidad,
                    per_correo as correo,
                    per_celular as cell
                from  " . $con->dbname . ".persona
                WHERE per_id= :per_id 
                and per_estado= :estado 
                and per_estado_logico= :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        
        return $resultData;
    }
    public function consultaDataDirectoInv() {
        $con = \Yii::$app->db_asgard;
        $estado=1;
        $sql = "SELECT
                    CONCAT('Universidad Tecnologica Empresarial de Guayaquil - ',e.emp_razon_social) as entidad,
                    g.gru_nombre as departamento,
                    CONCAT(p.per_pri_nombre,' ',p.per_pri_apellido) as manager,
                    p.per_correo as correo,
                    e.emp_direccion as direccion,
                    e.emp_telefono as telf,
                    'Costa'as region,
                    'Zona 8' as zona,
                    pr.pro_nombre as provincia,
                    cn.can_nombre as canton
                from  " . $con->dbname . ".grupo g,
                " . $con->dbname . ".persona p,
                " . $con->dbname . ".empresa e
                inner join " . $con->dbname . ".pais pa on pa.pai_id=e.pai_id
                inner join " . $con->dbname . ".provincia pr on pa.pai_id=pr.pai_id
                inner join " . $con->dbname . ".canton cn on cn.pro_id=pr.pro_id
                WHERE e.emp_id=1 and pr.pro_id=10 
                and g.gru_id=20 and p.per_id=56
                and p.per_estado= :estado and p.per_estado_logico= :estado
                and e.emp_estado= :estado and e.emp_estado_logico= :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        
        return $resultData;
    }

    public function insertarregistrobas(
        $per_id,
        $proy_id,
        $linv_id,
        $mpro_id,
        $rpro_titulo, 
        $rpro_resumen
    ) {
        $con = \Yii::$app->db_investigacion;
        $transaction=$con->beginTransaction(); 
        $date = date(Yii::$app->params["dateTimeByDefault"]);
        // se obtiene la transacción actual
          
        
        try {
            \app\models\Utilities::putMessageLogFile('Entro insert...: ');
            $sql = "INSERT INTO " . $con->dbname . ".registro_proyecto 
                (per_id, 
                proy_id, 
                linv_id,
                mpro_id, 
                rfin_id, 
                rpin_id, 
                rpro_titulo, 
                rpro_resumen, 
                rpro_estado_formulario, 
                rpro_estado, 
                rpro_fecha_creacion, 
                rpro_usuario_ingreso, 
                rpro_usuario_modifica, 
                rpro_fecha_modificacion, 
                rpro_estado_logico
                ) VALUES(
                    $per_id, 
                    $proy_id,
                    $linv_id,
                    $mpro_id,
                    Null,
                    Null,
                    '$rpro_titulo',
                    '$rpro_resumen',
                    0,
                    1,
                    '$date',
                    1,
                    Null,
                    Null,
                    1
                )";
            $comando = $con->createCommand($sql);
            $comando->execute();
            \app\models\Utilities::putMessageLogFile('insertRegBasc: ' . $comando->getRawSql());

            if ($transaction !== null)
                $transaction->commit();
            return TRUE;
        } catch (Exception $ex) {
            if ($transaction !== null)
                $transaction->rollback();
            return FALSE;
        }
    }
}
