<?php

namespace app\modules\academico\models;

use Yii;
use \yii\data\ArrayDataProvider;


/**
 * This is the model class for table "registro_online".
 *
 * @property int $ron_id
 * @property int $per_id
 * @property int $pes_id
 * @property int $ron_num_orden
 * @property string $ron_fecha_registro
 * @property string $ron_anio
 * @property string $ron_semestre
 * @property string $ron_modalidad
 * @property string $ron_carrera
 * @property string $ron_categoria_est
 * @property string $ron_valor_arancel
 * @property string $ron_valor_matricula
 * @property string $ron_valor_gastos_adm
 * @property string $ron_valor_gastos_pendientes
 * @property string $ron_valor_aso_estudiante
 * @property string $ron_estado_registro
 * @property string $ron_estado
 * @property string $ron_fecha_creacion
 * @property int $ron_usuario_modifica
 * @property string $ron_fecha_modificacion
 * @property string $ron_estado_logico
 * @property string $ron_estado_cancelacion
 *
 * @property EnrolamientoAgreement[] $enrolamientoAgreements
 * @property EstudiantePagoCarrera[] $estudiantePagoCarreras
 * @property RegistroAdicionalMaterias[] $registroAdicionalMaterias
 * @property PlanificacionEstudiante $pes
 * @property RegistroOnlineCuota[] $registroOnlineCuotas
 * @property RegistroOnlineItem[] $registroOnlineItems
 * @property RegistroPagoMatricula[] $registroPagoMatriculas
 */
class RegistroOnline extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_online';
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
    /*
    public function rules()
    {
        return [
            [['per_id', 'pes_id', 'ron_num_orden', 'ron_estado_registro', 'ron_estado', 'ron_estado_logico'], 'required'],
            [['per_id', 'pes_id', 'ron_usuario_modifica'], 'integer'],
            [['ron_fecha_registro', 'ron_fecha_creacion', 'ron_fecha_modificacion'], 'safe'],
            [['ron_valor_arancel', 'ron_valor_matricula', 'ron_valor_gastos_adm', 'ron_valor_aso_estudiante'], 'number'],
            [['ron_anio'], 'string', 'max' => 4],
            [['ron_num_orden'], 'string', 'max' => 10],
            [['ron_semestre', 'ron_estado_registro', 'ron_estado', 'ron_estado_logico', 'ron_estado_cancelacion'], 'string', 'max' => 1],
            [['ron_modalidad'], 'string', 'max' => 80],
            [['ron_carrera'], 'string', 'max' => 500],
            [['ron_categoria_est'], 'string', 'max' => 2],
            [['pes_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionEstudiante::className(), 'targetAttribute' => ['pes_id' => 'pes_id']],
        ];
    }
    */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ron_id' => 'Ron ID',
            'per_id' => 'Per ID',
            'pes_id' => 'Pes ID',
            'ron_num_orden' => 'Ron Num Orden',
            'ron_fecha_registro' => 'Ron Fecha Registro',
            'ron_anio' => 'Ron Anio',
            'ron_semestre' => 'Ron Semestre',
            'ron_modalidad' => 'Ron Modalidad',
            'ron_carrera' => 'Ron Carrera',
            'ron_categoria_est' => 'Ron Categoria Est',
            'ron_valor_arancel' => 'Ron Valor Arancel',
            'ron_valor_matricula' => 'Ron Valor Matricula',
            'ron_valor_gastos_adm' => 'Ron Valor Gastos Adm',
            'ron_valor_gastos_pendientes' => 'Ron Valor Gastos Pendientes',
            'ron_valor_aso_estudiante' => 'Ron Valor Aso Estudiante',
            'ron_estado_registro' => 'Ron Estado Registro',
            'ron_estado' => 'Ron Estado',
            'ron_fecha_creacion' => 'Ron Fecha Creacion',
            'ron_usuario_modifica' => 'Ron Usuario Modifica',
            'ron_fecha_modificacion' => 'Ron Fecha Modificacion',
            'ron_estado_logico' => 'Ron Estado Logico',
            'ron_estado_cancelacion' => 'Ron Estado Cancelacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnrolamientoAgreements()
    {
        return $this->hasMany(EnrolamientoAgreement::className(), ['ron_id' => 'ron_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstudiantePagoCarreras()
    {
        return $this->hasMany(EstudiantePagoCarrera::className(), ['ron_id' => 'ron_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroAdicionalMaterias()
    {
        return $this->hasMany(RegistroAdicionalMaterias::className(), ['ron_id' => 'ron_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPes()
    {
        return $this->hasOne(PlanificacionEstudiante::className(), ['pes_id' => 'pes_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroOnlineCuotas()
    {
        return $this->hasMany(RegistroOnlineCuota::className(), ['ron_id' => 'ron_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroOnlineItems()
    {
        return $this->hasMany(RegistroOnlineItem::className(), ['ron_id' => 'ron_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroPagoMatriculas()
    {
        return $this->hasMany(RegistroPagoMatricula::className(), ['ron_id' => 'ron_id']);
    }
    
     public function getcurrentRon($per_id){
        $con = \Yii::$app->db_academico;

        $sql = "
                 SELECT
                 ron_id as ronid                 
                from db_academico.registro_online
                where per_id = :per_id and ron_estado ='1'

                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        
            return $resultData;
        
    

    }

    public function getPes_id($per_id){
        $con = \Yii::$app->db_academico;

        $sql = "SELECT
                pes_id as id
                from " . $con->dbname . ".registro_online
                where per_id = :per_id and ron_estado = 1 order by pes_id desc limit 0,1;";

        if ($per_id == NULL) {
            $resultData = [];
        }else{
            $comando = $con->createCommand($sql);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $resultData = $comando->queryAll();
        }
        return $resultData;
    }

    public function getFindOne($per_id){
        $con = \Yii::$app->db_academico;

        $sql = "SELECT * from " . $con->dbname . ".registro_online
                where per_id = :per_id and ron_estado = 1 limit 0,1;";
        
        if($per_id == NULL){
            $resultData = [];
        }else{
            $comando = $con->createCommand($sql);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $resultData = $comando->queryAll();
        }
        /*$dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);*/
        return $resultData;//$dataProvider;
    }

    public function getRonId($per_id){
        $con = \Yii::$app->db_academico;

        $sql = "SELECT
                ron_id as id
                from " . $con->dbname . ".registro_online
                where per_id = :per_id and ron_estado = 1 order by ron_id desc limit 0,1;";

        if ($per_id == NULL) {
            $resultData = [];
        }else{
            $comando = $con->createCommand($sql);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $resultData = $comando->queryAll();
        }
        return $resultData;
    }

    public function insertRegistroOnline(
        $per_id, 
        $pes_id, 
        $numOrden, 
        $modalidad, 
        $carrera, 
        $semestre, 
        $est_categoria, 
        //$ron_valor_arancel, 
        $ron_valor_aso_estudiante, 
        $ron_valor_gastos_adm, 
        $ron_valor_gastos_pendientes,
        $ron_valor_matricula,
        $ron_estado_cancelacion
    ){

        $con = Yii::$app->db_academico;
        //$transaction=$con->beginTransaction();
        $date = date(Yii::$app->params['dateTimeByDefault']);
        $anio = strval(date("Y"));

        $sql = "INSERT INTO " . $con->dbname . ".registro_online
                (per_id, 
                pes_id, 
                ron_num_orden, 
                ron_anio, 
                ron_modalidad, 
                ron_carrera, 
                ron_semestre, 
                ron_categoria_est, 
                /*ron_valor_arancel, */
                ron_valor_aso_estudiante, 
                ron_valor_gastos_adm, 
                ron_valor_gastos_pendientes,
                ron_valor_matricula, 
                ron_estado_registro, 
                ron_fecha_registro, 
                ron_fecha_creacion, 
                ron_estado, 
                ron_estado_logico,
                ron_estado_cancelacion)
                VALUES (
                    $per_id, 
                    $pes_id, 
                    '$numOrden', 
                    '$anio',
                    '$modalidad', 
                    '$carrera', 
                    $semestre, 
                    '$est_categoria',
                    $ron_valor_aso_estudiante,
                    $ron_valor_gastos_adm, 
                    $ron_valor_gastos_pendientes,
                    $ron_valor_matricula,
                    1,
                    '$date', 
                    '$date', 
                    1, 
                    1,
                    $ron_estado_cancelacion
                )";

        $command = $con->createCommand($sql);
        \app\models\Utilities::putMessageLogFile($command->getRawSql());
        $command->execute();

        return $con->getLastInsertID($con->dbname . '.registro_online');
    }

    public function insertarActualizacionGastos($ron_id,$gastosAdm,$gastos_pendientes) {        
        $con = \Yii::$app->db_academico;
        $ron_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".registro_online               
                      SET ron_valor_gastos_adm = :gastosAdm,
                        ron_valor_gastos_pendientes = :gastos_pendientes,
                        ron_fecha_modificacion = :ron_fecha_modificacion
                        
                      WHERE 
                        ron_id = :ron_id
                        AND ron_estado = :estado 
                        AND ron_estado_logico = :estado");

            if (isset($gastosAdm)) {
                $comando->bindParam(':gastosAdm', $gastosAdm, \PDO::PARAM_INT);
            }
            if (isset($gastos_pendientes)) {
                $comando->bindParam(':gastos_pendientes', $gastos_pendientes, \PDO::PARAM_INT);
            }
            if (isset($ron_id)) {
                $comando->bindParam(':ron_id', $ron_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($ron_fecha_modificacion)))) {
                $comando->bindParam(':ron_fecha_modificacion', $ron_fecha_modificacion, \PDO::PARAM_STR);
            }
            
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $result = $comando->execute();
            \app\models\Utilities::putMessageLogFile('insertarActualizacionGastos: '.$comando->getRawSql());
            if ($trans !== null)
                $trans->commit();
            return TRUE;
            \app\models\Utilities::putMessageLogFile('insertarActualizacionGastos: '.$comando->getRawSql());
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
   
}

