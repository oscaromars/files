<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "periodo_academico".
 *
 * @property int $paca_id
 * @property int $saca_id
 * @property int $baca_id
 * @property string $paca_activo
 * @property string $paca_fecha_inicio
 * @property string $paca_fecha_fin
 * @property int $paca_usuario_ingreso
 * @property int $paca_usuario_modifica
 * @property string $paca_estado
 * @property string $paca_fecha_creacion
 * @property string $paca_fecha_modificacion
 * @property string $paca_estado_logico
 *
 * @property DistributivoAcademico[] $distributivoAcademicos
 * @property DistributivoCabecera[] $distributivoCabeceras
 * @property EstudiantePeriodoPago[] $estudiantePeriodoPagos
 * @property SemestreAcademico $saca
 * @property BloqueAcademico $baca
 * @property PlanificacionAcademicaMalla[] $planificacionAcademicaMallas
 */
class PeriodoAcademico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodo_academico';
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
            [['saca_id', 'baca_id', 'paca_usuario_ingreso', 'paca_estado', 'paca_estado_logico'], 'required'],
            [['saca_id', 'baca_id', 'paca_usuario_ingreso', 'paca_usuario_modifica'], 'integer'],
            [['paca_fecha_inicio', 'paca_fecha_fin', 'paca_fecha_creacion', 'paca_fecha_modificacion'], 'safe'],
            [['paca_activo', 'paca_estado', 'paca_estado_logico'], 'string', 'max' => 1],
            [['saca_id'], 'exist', 'skipOnError' => true, 'targetClass' => SemestreAcademico::className(), 'targetAttribute' => ['saca_id' => 'saca_id']],
            [['baca_id'], 'exist', 'skipOnError' => true, 'targetClass' => BloqueAcademico::className(), 'targetAttribute' => ['baca_id' => 'baca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'paca_id' => 'Paca ID',
            'saca_id' => 'Semestre Academico',
            'baca_id' => 'Bloque Academico',
            'paca_activo' => '',
            'paca_fecha_inicio' => '',
            'paca_fecha_fin' => '',
            'paca_usuario_ingreso' => '',
            'paca_usuario_modifica' => '',
            'paca_estado' => 'Estado',
            'paca_fecha_creacion' => '',
            'paca_fecha_modificacion' => '',
            'paca_estado_logico' => '',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivoAcademicos()
    {
        return $this->hasMany(DistributivoAcademico::className(), ['paca_id' => 'paca_id']);
    }

     public function getSem()
    {
        return $this->hasOne(SemestreAcademico::className(), ['saca_id' => 'saca_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivoCabeceras()
    {
        return $this->hasMany(DistributivoCabecera::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstudiantePeriodoPagos()
    {
        return $this->hasMany(EstudiantePeriodoPago::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaca()
    {
        return $this->hasOne(SemestreAcademico::className(), ['saca_id' => 'saca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaca()
    {
        return $this->hasOne(BloqueAcademico::className(), ['baca_id' => 'baca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionAcademicaMallas()
    {
        return $this->hasMany(PlanificacionAcademicaMalla::className(), ['paca_id' => 'paca_id']);
    }
    
     /**
     * Function consulta el período académico actual. 
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function getPeriodoAcademicoActual() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        
        $sql = "SELECT  pera.paca_id as id,
                        ifnull(CONCAT(blq.baca_nombre,'-',sem.saca_nombre,' ',sem.saca_anio),'') as nombre,
                        blq.baca_nombre
                FROM " . $con->dbname . ".periodo_academico pera
                     inner join " . $con->dbname . ".semestre_academico sem  ON sem.saca_id = pera.saca_id
                     inner join " . $con->dbname . ".bloque_academico blq ON blq.baca_id = pera.baca_id
                WHERE pera.paca_activo = 'A' AND
                      ((now() between pera.paca_fecha_inicio and pera.paca_fecha_fin) OR (now() < pera.paca_fecha_inicio)) AND
                      pera.paca_estado = :estado AND
                      pera.paca_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    /**
     * Consultar todos los períodos académicos, así no estén activos
     * @author Jorge Paladies <analista.desarrollo@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarTodosPeriodosAcademicos() {
        $con = Yii::$app->db_academico;
        $estado = 1;
        
        $sql = "SELECT paca.paca_id, ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS paca_nombre, baca.baca_nombre
                FROM " . $con->dbname . ".semestre_academico AS saca
                INNER JOIN " . $con->dbname . ".periodo_academico AS paca ON saca.saca_id = paca.saca_id
                INNER JOIN " . $con->dbname . ".bloque_academico AS baca ON baca.baca_id = paca.baca_id
                WHERE
                paca.paca_estado = 1 AND
                paca.paca_estado_logico = 1 AND
                saca.saca_estado = 1 AND
                saca.saca_estado_logico = 1 AND
                baca.baca_estado = 1 AND
                baca.baca_estado_logico = 1";

        $comando = $con->createCommand($sql);      
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Consultar todos los períodos académicos, sólo si están activos. Probablemente salga el actual nomás.
     * @author Jorge Paladies <analista.desarrollo@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPeriodosActivos() {
        $con = Yii::$app->db_academico;
        $estado = 1;
        
        $sql = "SELECT paca.paca_id, ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS paca_nombre, baca.baca_nombre
                FROM " . $con->dbname . ".semestre_academico AS saca
                INNER JOIN " . $con->dbname . ".periodo_academico AS paca ON saca.saca_id = paca.saca_id
                INNER JOIN " . $con->dbname . ".bloque_academico AS baca ON baca.baca_id = paca.baca_id
                WHERE
                paca.paca_activo = 'A' AND
                paca.paca_estado = 1 AND
                paca.paca_estado_logico = 1 AND
                saca.saca_estado = 1 AND
                saca.saca_estado_logico = 1 AND
                baca.baca_estado = 1 AND
                baca.baca_estado_logico = 1";

        $comando = $con->createCommand($sql);    
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Mostrará un solo período académico basado en el id, con el nombre del Grupo Estación
     * @author Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPeriodo($per_id, $onlyData = false){
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = $this->periodoConsultaSQL($con, $per_id);

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        if($onlyData) { return $resultData; }

        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'id',
                    'estado',
                    'nombre',
                    'fecha_inicio',
                    'fecha_fin',
                    'numero_de_clases_grado',
                    'numero_de_clases_posgrado'
                ],
            ],
        ]);

        return $dataProvider;
    }


     /**
     * Función para modularizar las siguientes 2 funciones. Si se le da el parámetro $per_id, usa WHERE. Si no, no.
     * @author Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @param
     * @return
     */
    private function periodoConsultaSQL($con, $per_id = null){
        if($per_id){
            $sql = "SELECT  pera.paca_id as id,
                            -- ge.gest_id as gest_id,
                            pera.paca_activo as estado,
                            -- ge.gest_descripcion as nombre,
                            pera.paca_fecha_inicio as fecha_inicio,
                            pera.paca_fecha_fin as fecha_fin -- ,
                            -- pera.paca_clases_grado as numero_de_clases_grado,
                            -- pera.paca_clases_posgrado as numero_de_clases_posgrado
                FROM " . $con->dbname . ".periodo_academico as pera
                    -- inner join " . $con->dbname . ".grupo_estacion ge  ON ge.gest_id = pera.gest_id
                WHERE pera.paca_id = " . $per_id . " AND 
                      pera.paca_estado = 1 AND
                      pera.paca_estado_logico = 1
                ORDER BY id";
        }
        else{
            $sql = "SELECT  pera.paca_id as id,
                        pera.paca_activo as estado,
                        -- ge.gest_descripcion as nombre,
                        pera.paca_fecha_inicio as fecha_inicio,
                        pera.paca_fecha_fin as fecha_fin -- ,
                        -- pera.paca_clases_grado as numero_de_clases_grado,
                        -- pera.paca_clases_posgrado as numero_de_clases_posgrado
                FROM " . $con->dbname . ".periodo_academico pera
                    -- inner join " . $con->dbname . ".grupo_estacion ge ON ge.gest_id = pera.gest_id 
                WHERE pera.paca_estado = 1 AND  
                      pera.paca_estado_logico = 1  
                ORDER BY id";
        }

        return $sql;
    }

    /**
     * Function consulta el parcial segun unidad academica. 
     * @author Givanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function getParcialUnidad($uaca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;        
        $sql = " SELECT ecun.ecal_id as id, 
                        esc.ecal_nombre as name                        
                   FROM " . $con->dbname . ".esquema_calificacion_unidad ecun
             INNER JOIN " . $con->dbname . ".esquema_calificacion esc  ON esc.ecal_id = ecun.ecal_id                     
                  WHERE ecun.uaca_id = :uaca_id AND
                        ecun.ecun_estado = :estado AND
                        ecun.ecun_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Retorna todos los parciales
     * @author Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @param
     * @return
     */
    public function getTodosParciales() {
        $con = \Yii::$app->db_academico;
        $estado = 1;        
        $sql = "SELECT DISTINCT ecun.ecal_id as id, 
                        esc.ecal_nombre as name                        
                FROM " . $con->dbname . ".esquema_calificacion_unidad ecun
                INNER JOIN " . $con->dbname . ".esquema_calificacion esc  ON esc.ecal_id = ecun.ecal_id                     
                WHERE ecun.ecun_estado = :estado AND
                      ecun.ecun_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);       
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
