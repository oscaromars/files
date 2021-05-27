<?php

namespace app\modules\admision\models;

use yii\data\ArrayDataProvider;
use DateTime;
use Yii;

/**
 * This is the model class for table "solicitudins_documento".
 *
 * @property int $sdoc_id
 * @property int $sins_id
 * @property int $int_id
 * @property int $dadj_id
 * @property string $sdoc_archivo
 * @property string $sdoc_observacion
 * @property int $sdoc_usuario_ingreso
 * @property int $sdoc_usuario_modifica
 * @property string $sdoc_estado
 * @property string $sdoc_fecha_creacion
 * @property string $sdoc_fecha_modificacion
 * @property string $sdoc_estado_logico
 *
 * @property SolicitudInscripcion $sins
 * @property Interesado $int
 * @property DocumentoAdjuntar $dadj
 */
class SolicitudinsDocumento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitudins_documento';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_captacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sins_id', 'int_id', 'dadj_id', 'sdoc_archivo', 'sdoc_estado', 'sdoc_estado_logico'], 'required'],
            [['sins_id', 'int_id', 'dadj_id', 'sdoc_usuario_ingreso', 'sdoc_usuario_modifica'], 'integer'],
            [['sdoc_fecha_creacion', 'sdoc_fecha_modificacion'], 'safe'],
            [['sdoc_archivo', 'sdoc_observacion'], 'string', 'max' => 500],
            [['sdoc_estado', 'sdoc_estado_logico'], 'string', 'max' => 1],
            [['sins_id'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudInscripcion::className(), 'targetAttribute' => ['sins_id' => 'sins_id']],
            [['int_id'], 'exist', 'skipOnError' => true, 'targetClass' => Interesado::className(), 'targetAttribute' => ['int_id' => 'int_id']],
            [['dadj_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentoAdjuntar::className(), 'targetAttribute' => ['dadj_id' => 'dadj_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sdoc_id' => 'Sdoc ID',
            'sins_id' => 'Sins ID',
            'int_id' => 'Int ID',
            'dadj_id' => 'Dadj ID',
            'sdoc_archivo' => 'Sdoc Archivo',
            'sdoc_observacion' => 'Sdoc Observacion',
            'sdoc_usuario_ingreso' => 'Sdoc Usuario Ingreso',
            'sdoc_usuario_modifica' => 'Sdoc Usuario Modifica',
            'sdoc_estado' => 'Sdoc Estado',
            'sdoc_fecha_creacion' => 'Sdoc Fecha Creacion',
            'sdoc_fecha_modificacion' => 'Sdoc Fecha Modificacion',
            'sdoc_estado_logico' => 'Sdoc Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSins()
    {
        return $this->hasOne(SolicitudInscripcion::className(), ['sins_id' => 'sins_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInt()
    {
        return $this->hasOne(Interesado::className(), ['int_id' => 'int_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDadj()
    {
        return $this->hasOne(DocumentoAdjuntar::className(), ['dadj_id' => 'dadj_id']);
    }
    /**
     * Function consulta solicitud id del x interesado
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   $usuario_id (id del interesado).  
     * @return  $resultData (id de la ultima solicitud).
     */

    /**
     * Function consultaDatosinteresado
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   $usuario_id (id del usuario).  
     * @return  $resultData (id del interesado).
     */
    public function getSolicitudxInteresado($int_id) {
        $con = \Yii::$app->db_captacion;        
        $estado = 1;
        $sql = "SELECT sins_id             
                FROM  " . $con->dbname . ".solicitudins_documento 
                WHERE 
                    int_id = :int_id AND
                    sdoc_estado_logico=:estado AND
                    sdoc_estado=:estado
                order by sdoc_fecha_creacion desc limit 1";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function insertNewDocument($sins_id, $int_id, $dadj_id, $sdoc_archivo, $sdoc_observacion){
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "INSERT INTO " . \Yii::$app->db_captacion->dbname . ".solicitudins_documento 
                (sins_id, int_id, dadj_id, sdoc_archivo, sdoc_observacion, sdoc_estado, sdoc_estado_logico)
                VALUES(:sins_id, :int_id, :dadj_id, :sdoc_archivo, :sdoc_observacion, :estado, :estado)";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
        $comando->bindParam(":dadj_id", $dadj_id, \PDO::PARAM_INT);
        $comando->bindParam(":sdoc_archivo", $sdoc_archivo, \PDO::PARAM_STR);
        $comando->bindParam(":sdoc_observacion", $sdoc_observacion, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $resultData = $comando->execute();
        return $resultData;
    }
}
