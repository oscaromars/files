<?php

namespace app\modules\academico\models;

use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "distributivo_cabecera".
 *
 * @property int $dcab_id
 * @property int $paca_id
 * @property int $pro_id
 * @property string $dcab_estado_aprobacion
 * @property string $dcab_fecha_aprobacion
 * @property int $dcab_usuario_aprobacion
 * @property string $dcab_fecha_registro
 * @property int $dcab_usuario_ingreso
 * @property int $dcab_usuario_modifica
 * @property string $dcab_estado
 * @property string $dcab_fecha_creacion
 * @property string $dcab_fecha_modificacion
 * @property string $dcab_estado_logico
 *
 * @property Profesor $pro
 * @property PeriodoAcademico $paca
 */
class DistributivoCabecera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'distributivo_cabecera';
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
            [['paca_id', 'pro_id', 'dcab_usuario_revision', 'dcab_usuario_ingreso', 'dcab_usuario_modifica'], 'integer'],
            [['pro_id', 'dcab_usuario_ingreso', 'dcab_estado', 'dcab_estado_logico'], 'required'],
            [['dcab_fecha_revision', 'dcab_fecha_registro', 'dcab_fecha_creacion', 'dcab_fecha_modificacion'], 'safe'],
            [['dcab_estado_revision', 'dcab_estado', 'dcab_estado_logico'], 'string', 'max' => 1],
            [['dcab_observacion_revision'], 'string', 'max' => 1000],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dcab_id' => 'Dcab ID',
            'paca_id' => 'Paca ID',
            'pro_id' => 'Pro ID',
            'dcab_estado_revision' => 'Dcab Estado Revision',
            'dcab_observacion_revision' => 'Dcab Observacion Revision',
            'dcab_fecha_revision' => 'Dcab Fecha Revision',
            'dcab_usuario_revision' => 'Dcab Usuario Revision',
            'dcab_fecha_registro' => 'Dcab Fecha Registro',
            'dcab_usuario_ingreso' => 'Dcab Usuario Ingreso',
            'dcab_usuario_modifica' => 'Dcab Usuario Modifica',
            'dcab_estado' => 'Dcab Estado',
            'dcab_fecha_creacion' => 'Dcab Fecha Creacion',
            'dcab_fecha_modificacion' => 'Dcab Fecha Modificacion',
            'dcab_estado_logico' => 'Dcab Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaca()
    {
        return $this->hasOne(PeriodoAcademico::className(), ['paca_id' => 'paca_id']);
    }

    public function getListadoDistributivoCab($search = NULL, $periodoAcademico = NULL, $estado_aprobacion = NULL, $asignacion = NULL, $onlyData = false)
    {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $search_cond = "%" . $search . "%";
        $estado = "1";
        $str_search = "";
        $str_periodo = "";

        // array("0" => "Todos", "1" => "(M) Matutino", "2" => "(N) Nocturno", "3" => "(S) Semipresencial", "4" => "(D) Distancia")

        if (isset($search) && $search != "") {
            $str_search = "(pe.per_pri_nombre like :search OR ";
            $str_search .= "pe.per_pri_apellido like :search OR ";
            $str_search .= "pe.per_cedula like :search) AND ";
        }

        if (isset($periodoAcademico) && $periodoAcademico > 0) {
            $str_periodo = "pa.paca_id = :periodo AND ";
        }

        if (isset($asignacion) && $asignacion > 0) {
            $str_asignacion = "dc.tdis_id = :asignacion AND ";
        }

        if (isset($estado_aprobacion) && $estado_aprobacion > 0) {
            $str_estado_probacion = "da.dcab_estado_revision = :estado_aprobacion AND ";
        }
        if (!$onlyData) {
            $select = " distinct da.dcab_id AS Id, da.dcab_estado_revision as estado, 
            CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS Nombres,
                    pe.per_cedula AS Cedula,                    
                    ifnull(CONCAT(blq.baca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),blq.baca_anio) AS Periodo,                    
                    CASE
                        WHEN da.dcab_estado_revision = 1 THEN 'Por aprobar'
                        WHEN da.dcab_estado_revision = 2 THEN 'Aprobado'
                        ELSE 'No Aprobado'
                    END AS estadoRevision";
            $select_adicional=" ";

        }
        if ($onlyData) {
            $select = " 
            CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS Nombres,
                    pe.per_cedula AS Cedula,                    
                    ifnull(CONCAT(blq.baca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),blq.baca_anio) AS Periodo,                    
                    CASE
                        WHEN da.dcab_estado_revision = 1 THEN 'Por aprobar'
                        WHEN da.dcab_estado_revision = 2 THEN 'Aprobado'
                        ELSE 'No Aprobado'
                    END AS estadoRevision, 
                        tp.tdis_nombre Tipo_Asignacion, ua.uaca_nombre UnidadAcademica, mo.mod_descripcion Modalidad,
                        asi.asi_descripcion Asignatura, dh.daho_descripcion Horario ";

            $select_adicional = " left join " . $con_academico->dbname . ".tipo_distributivo tp on tp.tdis_id = dc.tdis_id 
                            left join " . $con_academico->dbname . ".unidad_academica ua on ua.uaca_id =  dc.uaca_id
                            left join " . $con_academico->dbname . ".modalidad mo on mo.mod_id = dc.mod_id
                            left join " . $con_academico->dbname . ".asignatura asi on asi.asi_id = dc.asi_id
                            left join " . $con_academico->dbname . ".distributivo_academico_horario dh on dh.daho_id = dc.daho_id ";
        }
        $sql = "SELECT 
                    $select
                    
                   
                FROM 
                    " . $con_academico->dbname . ".distributivo_cabecera AS da                     
                    INNER JOIN " . $con_academico->dbname . ".distributivo_academico AS dc ON dc.pro_id = da.pro_id and dc.paca_id = da.paca_id                    
                    INNER JOIN " . $con_academico->dbname . ".profesor AS p ON da.pro_id = p.pro_id                    
                    INNER JOIN " . $con_academico->dbname . ".periodo_academico AS pa ON da.paca_id = pa.paca_id
                    INNER JOIN " . $con_db->dbname . ".persona AS pe ON p.per_id = pe.per_id
                    LEFT JOIN " . $con_academico->dbname . ".semestre_academico sem  ON sem.saca_id = pa.saca_id 
                    LEFT JOIN " . $con_academico->dbname . ".bloque_academico blq ON blq.baca_id = pa.baca_id 
                    $select_adicional
                    WHERE 
                    $str_search                    
                    $str_periodo  
                    $str_asignacion  
                    $str_estado_probacion
                    pa.paca_activo = 'A' AND
                    pa.paca_estado = :estado AND
                    da.dcab_estado_logico = :estado AND 
                    da.dcab_estado = :estado AND
                    p.pro_estado_logico = :estado AND 
                    p.pro_estado = :estado AND                    
                    pa.paca_estado_logico = :estado";

                    //\app\models\Utilities::putMessageLogFile('existe validacion ' . $sql);     

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($search) && $search != "") {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        if (isset($periodoAcademico) && $periodoAcademico != "") {
            $comando->bindParam(":periodo", $periodoAcademico, \PDO::PARAM_INT);
        }
        if (isset($asignacion) && $asignacion != "") {
            $comando->bindParam(":asignacion", $asignacion, \PDO::PARAM_INT);
        }
        if (isset($estado_aprobacion) && $estado_aprobacion != "") {
            $comando->bindParam(":estado_aprobacion", $estado_aprobacion, \PDO::PARAM_INT);
        }

        $res = $comando->queryAll();
        \app\models\Utilities::putMessageLogFile('existe validacion ' . $res[0]['Tipo_Asignacion']);    
        
        if ($onlyData) 
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Nombres', "Cedula", "Periodo"],
            ],
        ]);

        return $dataProvider;
    }


    /**
     * Function insertar datos distributivo cabecera
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos).
     */
    public function insertarDistributivoCab($paca_id, $pro_id)
    {
        $con = \Yii::$app->db_academico;
        $estado = '1';
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);


        $sql = "INSERT INTO " . $con->dbname . ".distributivo_cabecera
            (paca_id, pro_id, dcab_estado_revision, dcab_fecha_registro, dcab_usuario_ingreso, dcab_estado, dcab_estado_logico) VALUES
            (:paca_id, :pro_id, 1, :fecha, :usuario, :estado, :estado)";

        $command = $con->createCommand($sql);
        $command->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $command->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
        $command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $command->bindParam(":usuario", $usu_id, \PDO::PARAM_INT);
        $command->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $command->execute();
        $idtabla = $con->getLastInsertID($con->dbname . '.distributivo_cabecera');

        //\app\models\Utilities::putMessageLogFile('ing cab');  
        return $idtabla;
    }

    /**
     * Function verifica si existe en distributivo cabecera
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos).
     */
    public function existeDistCabecera($paca_id, $pro_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $sql = "SELECT 
                    dc.dcab_id,
                    dc.dcab_estado_revision
                FROM 
                    " . $con_academico->dbname . ".distributivo_cabecera AS dc
                WHERE
                    dc.paca_id =:paca_id AND 
                    dc.pro_id =:pro_id AND                     
                    dc.dcab_estado = 1 AND
                    dc.dcab_estado_logico = 1";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        if (empty($res)) {
            return 0;
        } else {
            return $res;
        }
    }

    public function EliminaexisteDistCabecera($id, $pro_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);

        $sql = "UPDATE
                    " . $con_academico->dbname . ".distributivo_academico 
                    SET daca_fecha_modificacion = '" . $fecha_transaccion . "', 
                    daca_usuario_modifica = '" . $usu_id . "', 
                    daca_estado = '0', 
                    daca_estado_logico = '0'
                WHERE
                pro_id = '" . $pro_id . "'";

        \app\models\Utilities::putMessageLogFile($sql);


        $command = $con_academico->createCommand($sql);

        $command->execute();

        $sql = "UPDATE
                    " . $con_academico->dbname . ".distributivo_cabecera 
                    SET dcab_fecha_modificacion = '" . $fecha_transaccion . "', 
                    dcab_usuario_modifica = '" . $usu_id . "', 
                    dcab_estado = '0', 
                    dcab_estado_logico = '0'
                WHERE
                dcab_id = '" . $id . "' AND                     
                dcab_estado = 1 AND
                dcab_estado_logico = 1";

        \app\models\Utilities::putMessageLogFile($sql);


        $command = $con_academico->createCommand($sql);
        // $command->bindParam(":id", $id, \PDO::PARAM_INT);                
        // $command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        // $command->bindParam(":usuario", $usu_id, \PDO::PARAM_INT);

        $idtabla = $command->execute();
        \app\models\Utilities::putMessageLogFile($idtabla);
        return $idtabla;
    }

    /**
     * Function obtiene datos de distributivo cabecera
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos).
     */
    public function obtenerDatosCabecera($cab_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $con_asgard = \Yii::$app->db_asgard;
        $sql = "SELECT 
                    dc.paca_id, dc.pro_id as pro_id, per.per_cedula,dcab_id,
                    concat(per.per_pri_apellido, ' ', ifnull(per.per_seg_apellido,'')) apellidos,
                    concat(per.per_pri_nombre, ' ', ifnull(per.per_seg_nombre,'')) nombres,
                    ifnull(CONCAT(ba.baca_nombre,'-',sa.saca_nombre,' ',sa.saca_anio),'') as periodo,
                    ifnull(dc.dcab_estado_revision,0) estado,
                    dcab_observacion_revision observacion
                FROM 
                    " . $con_academico->dbname . ".distributivo_cabecera AS dc inner join " . $con_academico->dbname . ".profesor pr 
                    on pr.pro_id = dc.pro_id inner join " . $con_asgard->dbname . ".persona per on per.per_id = pr.per_id
                    inner join " . $con_academico->dbname . ".periodo_academico pa on pa.paca_id = dc.paca_id
                    inner join " . $con_academico->dbname . ".semestre_academico sa on sa.saca_id = pa.saca_id
                    inner join " . $con_academico->dbname . ".bloque_academico ba on ba.baca_id = pa.baca_id
                WHERE
                    dc.dcab_id =:dcab_id AND                     
                    dc.dcab_estado = 1 AND
                    dc.dcab_estado_logico = 1 AND
                    pr.pro_estado = 1 AND
                    per.per_estado = 1 AND
                    pa.paca_estado = 1 AND
                    sa.saca_estado = 1 AND
                    ba.baca_estado = 1;";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":dcab_id", $cab_id, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        return $res;
    }

    /**
     * Function inactivar datos distributivo cabecera
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos).
     */
    public function inactivarDistributivoCabecera($id)
    {
        $con = \Yii::$app->db_academico;
        $estado = '1';
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);

        $sql = "UPDATE " . $con->dbname . ".distributivo_cabecera
                SET dcab_fecha_modificacion = :fecha, 
                    dcab_usuario_modifica = :usuario, 
                    dcab_estado = '0', 
                    dcab_estado_logico = '0'
                WHERE dcab_id = :id
                      AND dcab_estado = :estado
                      AND dcab_estado_logico = :estado";

        $command = $con->createCommand($sql);
        $command->bindParam(":id", $id, \PDO::PARAM_INT);
        $command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $command->bindParam(":usuario", $usu_id, \PDO::PARAM_INT);
        $command->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $idtabla = $command->execute();
        return $idtabla;
    }

    /**
     * Function inactivar datos distributivo cabecera
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos).
     */
    public function revisarDistributivo($id, $resultado, $observacion)
    {
        $prueba="prueba de git"; 
        $con = \Yii::$app->db_academico;
        $estado = '1';
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $OK = "Ok";

        $sql = "UPDATE " . $con->dbname . ".distributivo_cabecera
                SET dcab_estado_revision = :resultado,       
                    dcab_observacion_revision = :observacion,
                    dcab_fecha_revision = :fecha,
                    dcab_usuario_revision = :usuario,
                    dcab_fecha_modificacion = :fecha, 
                    dcab_usuario_modifica = :usuario                    
                WHERE dcab_id = :id
                      AND dcab_estado = :estado
                      AND dcab_estado_logico = :estado";

        $command = $con->createCommand($sql);
        $command->bindParam(":id", $id, \PDO::PARAM_INT);
        $command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $command->bindParam(":usuario", $usu_id, \PDO::PARAM_INT);
        $command->bindParam(":resultado", $resultado, \PDO::PARAM_INT);
        if ($resultado == 3) {
            $command->bindParam(":observacion", ucfirst($observacion), \PDO::PARAM_STR);
        } else {
            $command->bindParam(":observacion", $OK, \PDO::PARAM_STR);
        }
        $command->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $idtabla = $command->execute();
        return $idtabla;
    }
    public function reversarDistributivo($id, $resultado, $observacion)
    {
        $con = \Yii::$app->db_academico;
        $estado = '1';
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $OK = "Ok";

        $sql = "UPDATE " . $con->dbname . ".distributivo_cabecera
                SET dcab_estado_revision = :resultado,       
                    dcab_observacion_revision = :observacion,
                    dcab_fecha_revision = :fecha,
                    dcab_usuario_revision = :usuario,
                    dcab_fecha_modificacion = :fecha, 
                    dcab_usuario_modifica = :usuario                    
                WHERE dcab_id = :id
                      AND dcab_estado = :estado
                      AND dcab_estado_logico = :estado";

        $command = $con->createCommand($sql);
        $command->bindParam(":id", $id, \PDO::PARAM_INT);
        $command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $command->bindParam(":usuario", $usu_id, \PDO::PARAM_INT);
        $command->bindParam(":resultado", $resultado, \PDO::PARAM_INT);
        if ($resultado == 3) {
            $command->bindParam(":observacion", ucfirst($observacion), \PDO::PARAM_STR);
        } else {
            $command->bindParam(":observacion", $OK, \PDO::PARAM_STR);
        }
        $command->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $idtabla = $command->execute();
        return $idtabla;
    }

    public function consultarCabDistributivo($Ids)
    {
        $con = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db; //Conexin Asgard


        $sql = "SELECT A.*,CONCAT(C.per_pri_nombre,' ',C.per_pri_apellido) Nombres,UPPER(E.baca_descripcion) baca_descripcion,E.baca_anio
                    FROM " . $con->dbname . ".distributivo_cabecera A
                      INNER JOIN (" . $con->dbname . ".profesor B
                        INNER JOIN db_asgard.persona C ON B.per_id=C.per_id)
                      ON A.pro_id=B.pro_id
                      INNER JOIN (db_academico.periodo_academico D
                        INNER JOIN db_academico.bloque_academico E ON E.baca_id=D.baca_id)
                      ON D.paca_id=A.paca_id
                 WHERE A.dcab_estado=1 AND A.dcab_id=:ids and A.dcab_estado_logico=1 ";

        /*$sql = "SELECT A.* FROM " . $con->dbname . ".cabecera_solicitud A
                    WHERE  A.csol_estado=1 AND A.csol_estado_logico=1 AND A.csol_id= :csol_id;";*/
        $comando = $con->createCommand($sql);
        $comando->bindParam(":ids", $Ids, \PDO::PARAM_INT);
        // \app\models\Utilities::putMessageLogFile($Ids);
        return $comando->queryAll();
    }

    public function consultarDetDistributivo($PacaId, $ProId)
    {
        $con = \Yii::$app->db_academico;

        $sql = "SELECT A.*,B.asi_nombre,C.uaca_nombre,D.mod_nombre,
                        DATE_FORMAT(E.paca_fecha_inicio,'%d/%m/%Y') paca_fecha_inicio,DATE_FORMAT(E.paca_fecha_fin,'%d/%m/%Y') paca_fecha_fin
                    FROM " . $con->dbname . ".distributivo_academico A
                    INNER JOIN " . $con->dbname . ".asignatura B ON A.asi_id=B.asi_id
                    INNER JOIN " . $con->dbname . ".unidad_academica C ON C.uaca_id=A.uaca_id
                    INNER JOIN " . $con->dbname . ".modalidad D ON D.mod_id=A.mod_id
                    INNER JOIN " . $con->dbname . ".periodo_academico E ON E.paca_id=A.paca_id
                WHERE A.paca_id = :paca_id AND A.pro_id = :pro_id 
                and A.daca_estado=1 and A.daca_estado_logico=1 ";



        $comando = $con->createCommand($sql);
        $comando->bindParam(":paca_id", $PacaId, \PDO::PARAM_INT);
        $comando->bindParam(":pro_id", $ProId, \PDO::PARAM_INT);
      //  $res = $comando->queryOne();
        //\app\models\Utilities::putMessageLogFile( $res);
        //\app\models\Utilities::putMessageLogFile($ProId);

        return $comando->queryAll();
    }

    public function consultarDetDistributivoTipo($PacaId, $ProId)
    {
        $con = \Yii::$app->db_academico;


        $sql = "SELECT A.daca_id, D.tdis_nombre, m.mod_nombre,C.uaca_nombre,
            DATE_FORMAT(E.paca_fecha_inicio,'%d/%m/%Y') paca_fecha_inicio,DATE_FORMAT(E.paca_fecha_fin,'%d/%m/%Y') paca_fecha_fin
                FROM " . $con->dbname . ".distributivo_academico A
                INNER JOIN " . $con->dbname . ".tipo_distributivo D ON D.tdis_id=A.tdis_id
                INNER JOIN " . $con->dbname . ". periodo_academico E ON E.paca_id=A.paca_id
                INNER JOIN " . $con->dbname . ".modalidad m ON m.mod_id=A.mod_id   
                INNER JOIN " . $con->dbname . ".unidad_academica C ON C.uaca_id=A.uaca_id     
               WHERE A.paca_id = :paca_id AND A.pro_id = :pro_id 
               and A.daca_estado=1 and A.daca_estado_logico=1 and D.tdis_id in (2,3,4)";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":paca_id", $PacaId, \PDO::PARAM_INT);
        $comando->bindParam(":pro_id", $ProId, \PDO::PARAM_INT);

       
        return $comando->queryAll();
    }

    public function consultarDetDistributivoDocenteAutor($PacaId, $ProId)
    {
        $con = \Yii::$app->db_academico;


        $sql = "SELECT A.*,T.tdis_id, T.tdis_nombre, B.asi_nombre,C.uaca_nombre,D.mod_nombre,
            DATE_FORMAT(E.paca_fecha_inicio,'%d/%m/%Y') as paca_fecha_inicio,DATE_FORMAT(E.paca_fecha_fin,'%d/%m/%Y') as paca_fecha_fin
                FROM " . $con->dbname . ".distributivo_academico A
                    INNER JOIN " . $con->dbname . ".tipo_distributivo T ON T.tdis_id=A.tdis_id
                    INNER JOIN " . $con->dbname . ".asignatura B ON A.asi_id=B.asi_id
                    INNER JOIN " . $con->dbname . ".unidad_academica C ON C.uaca_id=A.uaca_id
                    INNER JOIN " . $con->dbname . ".modalidad D ON D.mod_id=A.mod_id
                    INNER JOIN " . $con->dbname . ".periodo_academico E ON E.paca_id=A.paca_id
                    WHERE A.paca_id = :paca_id AND A.pro_id = :pro_id 
                and A.daca_estado=1 and A.daca_estado_logico=1 ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":paca_id", $PacaId, \PDO::PARAM_INT);
        $comando->bindParam(":pro_id", $ProId, \PDO::PARAM_INT);

       
        return $comando->queryAll();
    }

    public function consultarDistHoras($Ids)
    {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT daho_descripcion  HORAS
                       FROM " . $con->dbname . ".distributivo_academico_horario
                WHERE daho_estado_logico=1 AND daho_id = :daho_id ;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":daho_id", $Ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }
    //Total de distributivo posgrado
    public function consultarDetDistributivoTotalposgrado($Ids)
    {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT (h.daho_total_horas*D.paca_semanas_posgrado) total_horas
                    FROM " . $con->dbname . ".distributivo_academico_horario h
                    INNER JOIN " . $con->dbname . ".periodo_academico D ON D.paca_activo='A'
                WHERE daho_estado_logico=1 AND daho_id = :daho_id ;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":daho_id", $Ids, \PDO::PARAM_INT);

        $res = $comando->queryAll();
        //\app\models\Utilities::putMessageLogFile('total del query: ' . $res[0]['total_horas']);
        return (float)$res[0]['total_horas'];
    }
    //Total de horas en Grado
    public function consultarDetDistributivoTotalgrado($Ids)
    {
        $con = \Yii::$app->db_academico;
        
         $sql = "SELECT (h.daho_total_horas*D.paca_semanas_periodo) total_horas
                    FROM  db_academico.distributivo_academico da 
                    inner join  db_academico.distributivo_academico_horario h on h.daho_id = da.daho_id
                    INNER JOIN db_academico.periodo_academico D ON  D.paca_id = da.paca_id and  D.paca_activo='A' 
                    WHERE  da.daho_id = :daho_id ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":daho_id", $Ids, \PDO::PARAM_INT);

        $res = $comando->queryAll();
        //\app\models\Utilities::putMessageLogFile('total del query: ' . $res[0]['total_horas']);
        return (float)$res[0]['total_horas'];
    }
    //Total de horas en Vinculacion e investigacion posgrado
    public function consultarDetDistributivoVinculacionPosgrado($Ids)
    {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT (2*D.paca_semanas_posgrado) total_horas
                FROM " . $con->dbname . ".distributivo_academico h
                INNER JOIN " . $con->dbname . ".periodo_academico D ON h.paca_id =D.paca_id and D.paca_activo='A'
            WHERE h.daca_id = :paca_id ;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":daca_id", $Ids, \PDO::PARAM_INT);

        
        $res = $comando->queryAll();
        //\app\models\Utilities::putMessageLogFile('total del query: ' . $res[0]['total_horas']);
        return (float)$res[0]['total_horas'];
    }
    //Total de horas en Vinculacion e investigacion grado
    public function consultarDetDistributivoVinculaciongrado($Ids)
    {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT (2*D.paca_semanas_periodo) total_horas
                FROM " . $con->dbname . ".distributivo_academico_horario h
                INNER JOIN " . $con->dbname . ".periodo_academico D ON D.paca_activo='A'
            WHERE daho_estado_logico=1 AND daho_id = :daho_id ;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":daho_id", $Ids, \PDO::PARAM_INT);

        $res = $comando->queryAll();
        //\app\models\Utilities::putMessageLogFile('total del query: ' . $res[0]['total_horas']);
        return (float)$res[0]['total_horas'];
    }
    //Total de horas en Preparacion docente Grado
    public function consultarDetDistributivoPreparacionGrado($Ids)
    {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT (daho_total_horas*D.paca_semanas_periodo)*0.30 total_horas
                FROM " . $con->dbname . ".distributivo_academico_horario h
                INNER JOIN " . $con->dbname . ".periodo_academico D ON D.paca_activo='A'
                and mod_id != 1
            WHERE daho_estado_logico=1 AND daho_id = :daho_id ;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":daho_id", $Ids, \PDO::PARAM_INT);

        $res = $comando->queryAll();
        //\app\models\Utilities::putMessageLogFile('total del query: ' . $res[0]['total_horas']);
        return (float)$res[0]['total_horas'];
    }
    //Total de horas en Preparacion docente PosGrado
    public function consultarDetDistributivoPreparacionPosgrado($Ids)
    {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT (daho_total_horas*D.paca_semanas_posgrado)*0.30 total_horas
                FROM " . $con->dbname . ".distributivo_academico_horario h
                INNER JOIN " . $con->dbname . ".periodo_academico D ON D.paca_activo='A'
                and mod_id != 1
            WHERE daho_estado_logico=1 AND daho_id = :daho_id ;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":daho_id", $Ids, \PDO::PARAM_INT);

        $res = $comando->queryAll();
        //\app\models\Utilities::putMessageLogFile('total del query: ' . $res[0]['total_horas']);
        return (float)$res[0]['total_horas'];
    }
}