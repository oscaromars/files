<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "distributivo_academico".
 *
 * @property int $daca_id
 * @property int $dcab_id
 * @property string $daca_fecha_inicio_post
 * @property string $daca_fecha_fin_post
 * @property int $paca_id
 * @property int $tdis_id
 * @property int $asi_id
 * @property int $pro_id
 * @property int $uaca_id
 * @property int $mod_id
 * @property int $daho_id
 * @property string $daca_jornada
 * @property string $daca_horario
 * @property string $daca_fecha_registro
 * @property int $daca_usuario_ingreso
 * @property int $daca_usuario_modifica
 * @property string $daca_estado
 * @property string $daca_fecha_creacion
 * @property string $daca_fecha_modificacion
 * @property string $daca_estado_logico
 * @property int $mpp_id
 * @property int $daca_num_estudiantes_online
 * @property int $daca_horas_otras_actividades
 * @property int $meun_id
 * @property int $pppr_id
 *
 * @property CursoEducativaDistributivo[] $cursoEducativaDistributivos
 * @property PeriodoAcademico $paca
 * @property Asignatura $asi
 * @property UnidadAcademica $uaca
 * @property Modalidad $mod
 * @property MateriaParaleloPeriodo $mpp
 * @property ParaleloPromocionPrograma $pppr
 * @property DistributivoAcademicoEstudiante[] $distributivoAcademicoEstudiantes
 */
class DistributivoAcademico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'distributivo_academico';
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
            [['dcab_id', 'paca_id', 'tdis_id', 'asi_id', 'pro_id', 'uaca_id', 'mod_id', 'daho_id', 'daca_usuario_ingreso', 'daca_usuario_modifica', 'mpp_id', 'daca_num_estudiantes_online', 'daca_horas_otras_actividades', 'meun_id', 'pppr_id'], 'integer'],
            [['daca_fecha_inicio_post', 'daca_fecha_fin_post', 'daca_fecha_registro', 'daca_fecha_creacion', 'daca_fecha_modificacion'], 'safe'],
            [['pro_id', 'daca_usuario_ingreso', 'daca_estado', 'daca_estado_logico'], 'required'],
            [['daca_jornada', 'daca_estado', 'daca_estado_logico'], 'string', 'max' => 1],
            [['daca_horario'], 'string', 'max' => 10],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
            [['asi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asi_id' => 'asi_id']],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
            [['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidad::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
            [['mpp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MateriaParaleloPeriodo::className(), 'targetAttribute' => ['mpp_id' => 'mpp_id']],
            [['pppr_id'], 'exist', 'skipOnError' => true, 'targetClass' => ParaleloPromocionPrograma::className(), 'targetAttribute' => ['pppr_id' => 'pppr_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'daca_id' => 'Daca ID',
            'dcab_id' => 'Dcab ID',
            'daca_fecha_inicio_post' => 'Daca Fecha Inicio Post',
            'daca_fecha_fin_post' => 'Daca Fecha Fin Post',
            'paca_id' => 'Paca ID',
            'tdis_id' => 'Tdis ID',
            'asi_id' => 'Asi ID',
            'pro_id' => 'Pro ID',
            'uaca_id' => 'Uaca ID',
            'mod_id' => 'Mod ID',
            'daho_id' => 'Daho ID',
            'daca_jornada' => 'Daca Jornada',
            'daca_horario' => 'Daca Horario',
            'daca_fecha_registro' => 'Daca Fecha Registro',
            'daca_usuario_ingreso' => 'Daca Usuario Ingreso',
            'daca_usuario_modifica' => 'Daca Usuario Modifica',
            'daca_estado' => 'Daca Estado',
            'daca_fecha_creacion' => 'Daca Fecha Creacion',
            'daca_fecha_modificacion' => 'Daca Fecha Modificacion',
            'daca_estado_logico' => 'Daca Estado Logico',
            'mpp_id' => 'Mpp ID',
            'daca_num_estudiantes_online' => 'Daca Num Estudiantes Online',
            'daca_horas_otras_actividades' => 'Daca Horas Otras Actividades',
            'meun_id' => 'Meun ID',
            'pppr_id' => 'Pppr ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCursoEducativaDistributivos()
    {
        return $this->hasMany(CursoEducativaDistributivo::className(), ['daca_id' => 'daca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaca()
    {
        return $this->hasOne(PeriodoAcademico::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsi()
    {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
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
    public function getMod()
    {
        return $this->hasOne(Modalidad::className(), ['mod_id' => 'mod_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMpp()
    {
        return $this->hasOne(MateriaParaleloPeriodo::className(), ['mpp_id' => 'mpp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPppr()
    {
        return $this->hasOne(ParaleloPromocionPrograma::className(), ['pppr_id' => 'pppr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivoAcademicoEstudiantes()
    {
        return $this->hasMany(DistributivoAcademicoEstudiante::className(), ['daca_id' => 'daca_id']);
    }

    public function buscarEstudiantesPosgrados($daca_id) {
        $con_academico = \Yii::$app->db_academico;

        /*
               CODIGO DE PEDRO
        $sql = "  SELECT dae.est_id
                    FROM db_academico.estudiante_carrera_programa as ecp
              INNER JOIN db_academico.distributivo_academico as da
                      ON ecp.meun_id = da.meun_id
                     and uaca_id = 2
               LEFT JOIN db_academico.distributivo_academico_estudiante as dae
                      ON dae.est_id = ecp.est_id
                   where ecp.est_id is null
                     and da.daca_id=".$id;
        */
        $sql = "  SELECT ecpr.est_id
                         ,CONCAT(per.per_pri_nombre, ' ', per.per_pri_apellido) AS nombres
                         ,ecpr.meun_id
                         ,daes.daes_id
                         ,daca.daca_id
                    FROM db_academico.distributivo_academico as daca
               left JOIN db_academico.estudiante_carrera_programa as ecpr
                      ON daca.meun_id = ecpr.meun_id
                     AND daca.uaca_id = 2
                     AND ecpr.ecpr_estado = 1 and ecpr.ecpr_estado_logico = 1
               LEFT JOIN db_academico.distributivo_academico_estudiante as daes
                      ON daes.est_id = ecpr.est_id
                     AND daes.daes_estado = 1 and daes.daes_estado_logico = 1
               INNER JOIN db_academico.estudiante est
                      ON est.est_id = ecpr.est_id
                     AND est.est_estado = 1 and est.est_estado_logico = 1
              INNER JOIN db_asgard.persona per
                      ON per.per_id = est.per_id
                     AND per.per_estado = 1 and per.per_estado_logico = 1
                   where 1 = 1
                     and daca.daca_id = $daca_id";

        $comando = $con_academico->createCommand($sql);

        \app\models\Utilities::putMessageLogFile($comando->getRawSql());

        $res = $comando->queryAll();
        return $res;
    }//function buscarEstudiantesPosgrados

    public function buscarEstudiantesMatriculados($id, $num_paralelo, $daca_id) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        /*
            CODIGO DE PEDRO
         $sql = " SELECT e.est_id as est_id, daes_id
                   FROM db_academico.registro_online as ron
		inner join db_academico.registro_online_item as roi on roi.ron_id=ron.ron_id and roi_estado='1'
		inner join db_asgard.persona as p on p.per_id = ron.per_id
                inner join db_academico.registro_pago_matricula as pm on ron.per_id=pm.per_id
                inner join db_academico.estudiante as e on e.per_id=p.per_id
                inner join db_academico.planificacion_estudiante as pe on pe.pes_id=ron.pes_id
                inner join db_academico.malla_academica_detalle as mad on  mad.made_codigo_asignatura=roi.roi_materia_cod
		inner join db_academico.asignatura as a on a.asi_id = mad.asi_id
                left join db_academico.distributivo_academico_estudiante as dae on dae.est_id = e.est_id
                 where 1=1
                   and dae.est_id is null
                   and mad.asi_id=".$id;
                   */
        $sql = "SELECT est.est_id as est_id
                       ,daes.daes_id
                  FROM db_academico.registro_online as ron
            inner join db_academico.registro_online_item as roi
                    on roi.ron_id=ron.ron_id
                   and roi.roi_estado = 1 and roi.roi_estado_logico
            inner join db_asgard.persona as per
                    on per.per_id = ron.per_id
                   and per.per_estado = 1 and per.per_estado_logico
            #inner join db_academico.registro_pago_matricula as rpm
                    #on rpm.per_id = ron.per_id
            inner join db_academico.estudiante as est
                    on est.per_id = per.per_id
                   and est.est_estado = 1 and est.est_estado_logico
            inner join db_academico.planificacion_estudiante as pes
                    on pes.pes_id = ron.pes_id
                   and pes.per_id = per.per_id
                   and pes.pes_estado = 1 and pes.pes_estado_logico
            inner join db_academico.malla_academica_detalle as made
                    on made.made_codigo_asignatura = roi.roi_materia_cod
                   and made.made_estado = 1 and made.made_estado_logico
            #inner join db_academico.asignatura as asi on asi.asi_id = mad.asi_id
            left join db_academico.distributivo_academico_estudiante as daes
                    on daes.est_id = est.est_id
                   and daes.daes_estado = 1 and daes.daes_estado_logico
                 where 1=1
                   and made.asi_id = $id
                   and ron.ron_estado = 1
                   and ron.ron_estado_logico = 1";

        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();
        \app\models\Utilities::putMessageLogFile($comando->getRawSql());
        return $res;
    }//function buscarEstudiantesMatriculados

    public function buscarEstudiantesAsignados($id, $num_paralelo, $daca_id) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        /*
            CODIGO DE PEDRO
        $sql = " select e.est_id as est_id, daes_id
                 from db_academico.registro_online as ron
		inner join db_academico.registro_online_item as roi on roi.ron_id=ron.ron_id and roi_estado='1'
		inner join db_asgard.persona as p on p.per_id = ron.per_id
                inner join db_academico.registro_pago_matricula as pm on ron.per_id=pm.per_id
                inner join db_academico.estudiante as e on e.per_id=p.per_id
                inner join db_academico.planificacion_estudiante as pe on pe.pes_id=ron.pes_id
                inner join db_academico.malla_academica_detalle as mad on  mad.made_codigo_asignatura=roi.roi_materia_cod
		inner join db_academico.asignatura as a on a.asi_id = mad.asi_id
                inner join db_academico.distributivo_academico_estudiante as dae on dae.est_id = e.est_id
                inner join db_academico.distributivo_academico as da on dae.daca_id = da.daca_id
                inner join db_academico.materia_paralelo_periodo as mpp on mpp.mpp_id =da.mpp_id and mpp.mpp_num_paralelo=".$num_paralelo.
                " where  mad.asi_id=".$id;
        */

        $sql = "SELECT est.est_id as est_id
                       ,daes.daes_id
                       ,mpp.mpp_num_paralelo
                       ,daes.daca_id
                       ,made.asi_id
                  FROM db_academico.registro_online as ron
            inner join db_academico.registro_online_item as roi
                    on roi.ron_id=ron.ron_id
                   and roi.roi_estado = 1 and roi.roi_estado_logico = 1
            inner join db_asgard.persona as per
                    on per.per_id = ron.per_id
                   and per.per_estado = 1 and per.per_estado_logico = 1
            /*inner join db_academico.registro_pago_matricula as rpm
                    on rpm.per_id = ron.per_id  */
            inner join db_academico.estudiante as est
                    on est.per_id = per.per_id
                   /*and est.est_id = $est_id*/
                   and est.est_estado = 1 and est.est_estado_logico = 1
            inner join db_academico.planificacion_estudiante as pes
                    on pes.pes_id = ron.pes_id
                   and pes.per_id = per.per_id
                   and pes.pes_estado = 1 and pes.pes_estado_logico = 1
            inner join db_academico.malla_academica_detalle as made
                    on made.made_codigo_asignatura = roi.roi_materia_cod
                   and made.made_estado = 1 and made.made_estado_logico = 1
            /*inner join db_academico.asignatura as asi on asi.asi_id = mad.asi_id*/
            left join db_academico.distributivo_academico_estudiante as daes
                    on daes.est_id = est.est_id
                   and daes.daca_id = $daca_id
                   and daes.daes_estado = 1 and daes.daes_estado_logico = 1
            left join db_academico.distributivo_academico as daca
                    on daca.daca_id = daes.daca_id
                   and daca.asi_id = made.asi_id
                   and daca.uaca_id = 1
                   and daca.daca_estado = 1 and daca.daca_estado_logico = 1
            left join db_academico.materia_paralelo_periodo as mpp
                    on mpp.mpp_id = daca.mpp_id
                   and mpp.mpp_num_paralelo = $num_paralelo
                   and mpp.mpp_estado = 1 and daca.daca_estado_logico = 1
                 where 1=1
                   /*and daes.est_id is null*/
                   and made.asi_id = $id
                   and ron.ron_estado = 1
                   and ron.ron_estado_logico = 1";

        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();
         \app\models\Utilities::putMessageLogFile($comando->getRawSql());
        return $res;
    }// buscarEstudiantesAsignados

     public function getParalelo($asi_id) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $sql = " select da.daca_id as id, mpp_num_paralelo as name from db_academico.distributivo_academico as da
                   inner join db_academico.materia_paralelo_periodo as mpp on mpp.mpp_id = da.mpp_id
                   where da.asi_id = ".$asi_id;


         $comando = $con_academico->createCommand($sql);
          $res = $comando->queryAll();
          return $res;
    }

    public function getListadoDistributivoBloqueDocente($onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $sql = "select (@row_number:=@row_number + 1) AS Id, UPPER(CONCAT(persona.per_pri_apellido,' ' ,persona.per_seg_apellido,' ' ,persona.per_pri_nombre,' ' ,persona.per_seg_nombre)) as docente,
                per_cedula as no_cedula,
                UPPER(pi3.pins_titulo) as  titulo_tercel_nivel,
                UPPER(pi4.pins_titulo) as  titulo_cuarto_nivel,
                persona.per_correo as correo,
                UPPER(dd.ddoc_nombre)  as  tiempo_dedicacion,
                'DICTADO' as desempeno,
                '' as materia,
                '1' as nivel,
                '2' as credito,
                '40' as horas_por_credito,
                '80' as TOTAL_HORAS_A_DICTAR
                from " . $con_db->dbname . ".persona persona
                inner join " . $con_academico->dbname . ".profesor profesor on profesor.per_id = persona.per_id
                inner join " . $con_academico->dbname . ".profesor_instruccion pi3 on pi3.pro_id = profesor.per_id and pi3.nins_id =3 and pi3.pins_estado=1 and pi3.pins_estado_logico=1
                inner join " . $con_academico->dbname . ".profesor_instruccion pi4 on pi4.pro_id = profesor.per_id and pi4.nins_id =4 and pi4.pins_estado=1 and pi4.pins_estado_logico=1
                inner join " . $con_academico->dbname . ".dedicacion_docente dd on dd.ddoc_id = profesor.ddoc_id
                ";
        $comando = $con_academico->createCommand($sql);

        $res = $comando->queryAll();
        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['no_cedula', "titulo_tercel_nivel", "titulo_cuarto_nivel", "correo", "tiempo_dedicacion", "desempeno"],
            ],
        ]);

        return $dataProvider;
    }

    public function getListadoDistributivoGrado($search = NULL, $modalidad = NULL, $asignatura = NULL, $jornada = NULL, $unidadAcademico = NULL, $periodoAcademico = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db        = \Yii::$app->db;
        $search_cond   = "%" . $search . "%";
        $estado        = "1";
        $str_search    = "";
        $str_unidad    = "";
        $str_periodo   = "";
        $str_modalidad = "";
        $str_jornada   = "";
        // array("0" => "Todos", "1" => "(M) Matutino", "2" => "(N) Nocturno", "3" => "(S) Semipresencial", "4" => "(D) Distancia")

        if (isset($search) && $search != "") {
            $str_search = "(per.per_pri_nombre   like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_cedula       like :search) AND ";
        }
        if (isset($modalidad) && $modalidad > 0) {
            $str_modalidad = "m.mod_id = :modalidad AND ";
        }
        if (isset($asignatura) && $asignatura > 0) {
            $str_asignatura = "asi.asi_id = :asignatura AND ";
        }
        if (isset($unidadAcademico) && $unidadAcademico > 0) {
            $str_unidad  = "uaca.uaca_id = :unidad AND ";
        }
        if (isset($periodoAcademico) && $periodoAcademico > 0) {
            $str_periodo = "paca.paca_id = :periodo AND ";
        }
        if (isset($jornada) && $jornada > 0) {
            $str_jornada = "daho.daho_jornada = :jornada AND ";
        }

        /*
        $sql = "SELECT
                    da.daca_id AS Id,
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS Nombres,
                    pe.per_cedula AS Cedula,
                    ua.uaca_nombre AS UnidadAcademica,
                    m.mod_nombre AS Modalidad,
                    ifnull(CONCAT(blq.baca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),blq.baca_anio) AS Periodo,
                    a.asi_nombre AS Asignatura,
                    CASE
                        WHEN dh.daho_jornada = 1 THEN '(M) Matutino'
                        WHEN dh.daho_jornada = 2 THEN '(N) Nocturno'
                        WHEN dh.daho_jornada = 3 THEN '(S) Semipresencial'
                        WHEN dh.daho_jornada = 4 THEN '(D) Distancia'
                        ELSE ''
                    END AS Jornada,
                    mpp.mpp_num_paralelo as mpp_num_paralelo,
                    dhp.dhpa_paralelo as dhpa_paralelo,
                  (select count(dae.daca_id) from db_academico.distributivo_academico_estudiante  as dae where dae.daca_id =da.daca_id ) as total_est
                FROM
                    " . $con_academico->dbname . ".distributivo_academico AS da
                    INNER JOIN " . $con_academico->dbname . ".distributivo_cabecera dc on dc.dcab_id=da.dcab_id
                    LEFT  JOIN " . $con_academico->dbname . ".distributivo_academico_horario AS dh ON da.daho_id = dh.daho_id
                    INNER JOIN " . $con_academico->dbname . ".profesor AS p ON da.pro_id = p.pro_id
                    LEFT JOIN " . $con_academico->dbname . ".materia_paralelo_periodo as mpp
                           on mpp.mpp_id=da.mpp_id
                          and da.uaca_id = 1
                    left JOIN db_academico.distributivo_horario_paralelo as dhp
                             on dhp.daho_id = da.daho_id
                            and da.uaca_id = 2
                    INNER JOIN " . $con_academico->dbname . ".modalidad AS m ON da.mod_id = m.mod_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON da.uaca_id = ua.uaca_id
                    INNER JOIN " . $con_academico->dbname . ".asignatura AS a ON da.asi_id = a.asi_id
                    INNER JOIN " . $con_academico->dbname . ".periodo_academico AS pa ON da.paca_id = pa.paca_id
                    INNER JOIN " . $con_db->dbname        . ".persona AS pe ON p.per_id = pe.per_id
                    LEFT  JOIN " . $con_academico->dbname . ".semestre_academico sem  ON sem.saca_id = pa.saca_id
                    LEFT  JOIN " . $con_academico->dbname . ".bloque_academico blq ON blq.baca_id = pa.baca_id
                WHERE
                    $str_search
                    $str_modalidad
                    $str_asignatura
                    $str_unidad
                    $str_periodo
                    $str_jornada
                    pa.paca_activo = 'A' AND
                    pa.paca_estado = :estado AND
                    da.daca_estado_logico = :estado AND
                    da.daca_estado = :estado AND
                    da.daca_estado = :estado AND
                    p.pro_estado_logico = :estado AND
                    p.pro_estado = :estado AND
                    m.mod_estado_logico = :estado AND
                    m.mod_estado = :estado AND
                    ua.uaca_estado_logico = :estado AND
                    ua.uaca_estado = :estado AND
                    pa.paca_estado_logico = :estado and
                    dcab_estado_revision=2
                    order by 4,2 asc";
        */
        $sql = "
             SELECT daca.daca_id AS Id,
                    CONCAT(per.per_pri_nombre, ' ', per.per_pri_apellido) AS Nombres,
                    per.per_cedula AS Cedula,
                    uaca.uaca_nombre AS UnidadAcademica,
                    m.mod_nombre AS Modalidad,
                    ifnull(CONCAT(baca.baca_anio,' (',baca.baca_nombre,'-',saca.saca_nombre,')'),baca.baca_anio) AS Periodo,
                    asi.asi_nombre AS Asignatura,
                    CASE
                        WHEN daho.daho_jornada = 1 THEN '(M) Matutino'
                        WHEN daho.daho_jornada = 2 THEN '(N) Nocturno'
                        WHEN daho.daho_jornada = 3 THEN '(S) Semipresencial'
                        WHEN daho.daho_jornada = 4 THEN '(D) Distancia'
                        ELSE ''
                    END AS Jornada,
                    mpp.mpp_num_paralelo as mpp_num_paralelo,
                    -- dhpa.dhpa_paralelo as dhpa_paralelo,
                    (select count(dae.daca_id) FROM db_academico.distributivo_academico_estudiante  as dae where dae.daca_id =daca.daca_id ) as total_est
               FROM db_academico.distributivo_academico AS daca
         INNER JOIN db_academico.distributivo_cabecera dcab
                 ON dcab.dcab_id = daca.dcab_id
                AND dcab.dcab_estado_revision = 2
                AND dcab.dcab_estado = 1 AND dcab.dcab_estado_logico = 1
          LEFT JOIN db_academico.distributivo_academico_horario AS daho
                 ON daho.daho_id = daca.daho_id
                #AND daho.uaca_id = daca.uaca_id AND daho.mod_id = daca.mod_id
                AND daho.daho_estado = 1 and daho.daho_estado_logico = 1
         INNER JOIN db_academico.profesor AS pro
                 ON pro.pro_id = daca.pro_id
                AND pro.pro_estado = 1 and pro.pro_estado_logico = 1
          LEFT JOIN db_academico.materia_paralelo_periodo as mpp
                 on mpp.mpp_id = daca.mpp_id
                and daca.uaca_id = 1
                AND mpp.mpp_estado = 1 and mpp.mpp_estado_logico = 1
          /*left JOIN db_academico.distributivo_horario_paralelo as dhpa
                 ON dhpa.dhpa_id = daca.dhpa_id
                #ON dhpa.daho_id = daca.daho_id
                AND daca.uaca_id = 2
                AND dhpa.dhpa_estado = 1 and dhpa.dhpa_estado_logico = 1*/
         INNER JOIN db_academico.modalidad AS m
                 ON m.mod_id = daca.mod_id
                AND m.mod_estado = 1 and m.mod_estado_logico = 1
         INNER JOIN db_academico.unidad_academica AS uaca
                 ON uaca.uaca_id = daca.uaca_id
                AND uaca.uaca_estado = 1 and uaca.uaca_estado_logico = 1
         INNER JOIN db_academico.asignatura AS asi
                 ON asi.asi_id = daca.asi_id
                AND asi.asi_estado = 1 and asi.asi_estado_logico = 1
         INNER JOIN db_academico.periodo_academico AS paca
                 ON paca.paca_id = daca.paca_id
                AND paca.paca_activo = 'A'
                AND paca.paca_estado = 1 and paca.paca_estado_logico = 1
         INNER JOIN db_asgard.persona AS per
                 ON per.per_id = pro.per_id
                AND per.per_estado = 1 and per.per_estado_logico = 1
          LEFT JOIN db_academico.semestre_academico saca
                 ON saca.saca_id = paca.saca_id
                AND saca.saca_estado = 1 and saca.saca_estado_logico = 1
          LEFT JOIN db_academico.bloque_academico baca
                 ON baca.baca_id = paca.baca_id
                AND baca.baca_estado = 1 AND baca.baca_estado_logico = 1
              WHERE $str_search 1 = 1
                AND daca.daca_estado = 1 AND daca.daca_estado_logico = 1
           ORDER BY 4,2 asc";

        $comando = $con_academico->createCommand($sql);

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        if (isset($search) && $search != "") {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        if (isset($modalidad) && $modalidad != "") {
            $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        }
        if (isset($asignatura) && $asignatura != "") {
            $comando->bindParam(":asignatura", $asignatura, \PDO::PARAM_INT);
        }
        if (isset($unidadAcademico) && $unidadAcademico != "") {
            $comando->bindParam(":unidad", $unidadAcademico, \PDO::PARAM_INT);
        }
        if (isset($periodoAcademico) && $periodoAcademico != "") {
            $comando->bindParam(":periodo", $periodoAcademico, \PDO::PARAM_INT);
        }

        if (isset($jornada) && $jornada > 0) {
            $comando->bindParam(":jornada", $jornada, \PDO::PARAM_INT);
        }

        $res = $comando->queryAll();

        \app\models\Utilities::putMessageLogFile($comando->getRawSql());

        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['no_cedula', "titulo_tercel_nivel", "titulo_cuarto_nivel", "correo", "tiempo_dedicacion", "desempeno"],
            ],
        ]);

        return $dataProvider;
    }//function getListadoDistributivoGrado

     public function getListadoDistributivoPosgrado($search = NULL, $modalidad = NULL, $asignatura = NULL, $jornada = NULL, $unidadAcademico = NULL, $periodoAcademico = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $search_cond = "%" . $search . "%";
        $estado = "1";
        $str_search = "";
        $str_unidad = "";
        $str_periodo = "";
        $str_modalidad = "";
        $str_jornada = "";
        // array("0" => "Todos", "1" => "(M) Matutino", "2" => "(N) Nocturno", "3" => "(S) Semipresencial", "4" => "(D) Distancia")

        if (isset($search) && $search != "") {
            $str_search = "(pe.per_pri_nombre like :search OR ";
            $str_search .= "pe.per_pri_apellido like :search OR ";
            $str_search .= "pe.per_cedula like :search) AND ";
        }
        if (isset($modalidad) && $modalidad > 0) {
            $str_modalidad = "m.mod_id = :modalidad AND ";
        }
        if (isset($asignatura) && $asignatura > 0) {
            $str_asignatura = "a.asi_id = :asignatura AND ";
        }
        if (isset($unidadAcademico) && $unidadAcademico > 0) {
            $str_unidad = "ua.uaca_id = :unidad AND ";
        }
        if (isset($periodoAcademico) && $periodoAcademico > 0) {
            $str_periodo = "pa.paca_id = :periodo AND ";
        }
        if (isset($jornada) && $jornada > 0) {
            $str_jornada = "dh.daho_jornada = :jornada AND ";
        }

        $sql = "SELECT
                    da.daca_id AS Id,
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS Nombres,
                    pe.per_cedula AS Cedula,
                    ua.uaca_nombre AS UnidadAcademica,
                    m.mod_nombre AS Modalidad,
                    ifnull(CONCAT(blq.baca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),blq.baca_anio) AS Periodo,
                    a.asi_nombre AS Asignatura,
                    CASE
                        WHEN dh.daho_jornada = 1 THEN '(M) Matutino'
                        WHEN dh.daho_jornada = 2 THEN '(N) Nocturno'
                        WHEN dh.daho_jornada = 3 THEN '(S) Semipresencial'
                        WHEN dh.daho_jornada = 4 THEN '(D) Distancia'
                        ELSE ''
                    END AS Jornada,
                    dhpa_paralelo,
                  (select count(dae.daca_id) from db_academico.distributivo_academico_estudiante  as dae where dae.daca_id =da.daca_id ) as total_est
                FROM
                    " . $con_academico->dbname . ".distributivo_academico AS da
                    INNER JOIN " . $con_academico->dbname . ".distributivo_cabecera dc on dc.dcab_id=da.dcab_id
                    LEFT  JOIN " . $con_academico->dbname . ".distributivo_academico_horario AS dh ON da.daho_id = dh.daho_id
                    INNER JOIN " . $con_academico->dbname . ".profesor AS p ON da.pro_id = p.pro_id
                    INNER JOIN " . $con_academico->dbname . ".distributivo_horario_paralelo as dhp on dhp.daho_id=da.daho_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad AS m ON da.mod_id = m.mod_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON da.uaca_id = ua.uaca_id
                    INNER JOIN " . $con_academico->dbname . ".asignatura AS a ON da.asi_id = a.asi_id
                    INNER JOIN " . $con_academico->dbname . ".periodo_academico AS pa ON da.paca_id = pa.paca_id
                    INNER JOIN " . $con_db->dbname        . ".persona AS pe ON p.per_id = pe.per_id
                    LEFT  JOIN " . $con_academico->dbname . ".semestre_academico sem  ON sem.saca_id = pa.saca_id
                    LEFT  JOIN " . $con_academico->dbname . ".bloque_academico blq ON blq.baca_id = pa.baca_id
                WHERE
                    $str_search
                    $str_modalidad
                    $str_asignatura
                    $str_unidad
                    $str_periodo
                    $str_jornada
                    pa.paca_activo = 'A' AND
                    pa.paca_estado = :estado AND
                    da.daca_estado_logico = :estado AND
                    da.daca_estado = :estado AND
                    da.daca_estado = :estado AND
                    p.pro_estado_logico = :estado AND
                    p.pro_estado = :estado AND
                    m.mod_estado_logico = :estado AND
                    m.mod_estado = :estado AND
                    ua.uaca_estado_logico = :estado AND
                    ua.uaca_estado = :estado AND
                    pa.paca_estado_logico = :estado  and
                    da.uaca_id=2 and
                    dcab_estado_revision=2
                    order by 2 asc";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($search) && $search != "") {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        if (isset($modalidad) && $modalidad != "") {
            $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        }
        if (isset($asignatura) && $asignatura != "") {
            $comando->bindParam(":asignatura", $asignatura, \PDO::PARAM_INT);
        }
        if (isset($unidadAcademico) && $unidadAcademico != "") {
            $comando->bindParam(":unidad", $unidadAcademico, \PDO::PARAM_INT);
        }
        if (isset($periodoAcademico) && $periodoAcademico != "") {
            $comando->bindParam(":periodo", $periodoAcademico, \PDO::PARAM_INT);
        }

        if (isset($jornada) && $jornada > 0) {
            $comando->bindParam(":jornada", $jornada, \PDO::PARAM_INT);
        }

        $res = $comando->queryAll();



        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Nombres', "Cedula", "UnidadAcademica", "Modalidad", "Periodo"],
            ],
        ]);

        return $dataProvider;
    }




    public function getHorariosByUnidadAcad($uaca_id = null, $mod_id = null, $jornada_id = null) {
        $con_academico = \Yii::$app->db_academico;
        $str_condition = "";
        if (isset($uaca_id) && $uaca_id > 0) {
            $str_condition .= "uaca_id = ".$uaca_id." AND ";
        }
        if (isset($mod_id) && $mod_id > 0) {
            $str_condition .= "mod_id = ".$mod_id." AND ";
        }
        if (isset($jornada_id) && $jornada_id > 0) {
            $str_condition .= "daho_jornada = ".$jornada_id." AND ";
        }
        $sql = "SELECT
                    daho_id as id,
                    daho_descripcion AS name
                FROM
                    " . $con_academico->dbname . ".distributivo_academico_horario, (SELECT @row_number:=0) AS t
                WHERE
                    $str_condition
                    daho_estado = 1 AND
                    daho_estado_logico = 1
                GROUP BY
                    daho_horario
                ORDER BY
                    daho_horario ASC";
        $comando = $con_academico->createCommand($sql);

        $res = $comando->queryAll();
        return $res;
    }

    public function getJornadasByUnidadAcad($uaca_id = null, $mod_id = null) {
        $con_academico = \Yii::$app->db_academico;
        $str_condicion = "";
        if (isset($uaca_id) && $uaca_id > 0) {
            $str_condicion .= "uaca_id = :uaca_id AND ";
        }
        if (isset($mod_id) && $mod_id > 0) {
            $str_condicion .= "mod_id = :mod_id AND ";
        }
        $sql = "SELECT
                    daho_jornada as id,
                    CASE
                        WHEN daho_jornada = 1 THEN '(M) Matutino'
                        WHEN daho_jornada = 2 THEN '(N) Nocturno'
                        WHEN daho_jornada = 3 THEN '(S) Semipresencial'
                        WHEN daho_jornada = 4 THEN '(D) Distancia'
                        ELSE ''
                    END AS name
                FROM
                    " . $con_academico->dbname . ".distributivo_academico_horario
                WHERE
                    $str_condicion
                    daho_estado = 1 AND
                    daho_estado_logico = 1
                GROUP BY
                    daho_jornada
                ORDER BY
                    daho_jornada DESC";
//\app\models\Utilities::putMessageLogFile($uaca_id.'**'.$mod_id);
        $comando = $con_academico->createCommand($sql);
        if (isset($uaca_id) && $uaca_id > 0)
            $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        if (isset($mod_id) && $mod_id > 0)
            $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        return $res;
    }

    /**
     * Function Verifica si ya existe el mismo tipo de distributivo con el profesor.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Retornar los datos).
     */
    public function existsDistribucionAcademico($pro_id, $tdis_id, $uaca_id, $asi_id, $paca_id, $horario, $paralelo) {
        $con_academico = \Yii::$app->db_academico;
        if ($tdis_id == 1) {
            \app\models\Utilities::putMessageLogFile('ingresa porque es docencia');
            $sql = "SELECT
                    da.daca_id as id
                FROM
                    " . $con_academico->dbname . ".distributivo_academico AS da
                WHERE
                    da.paca_id =:paca_id AND
                    da.pro_id =:pro_id AND
                    da.asi_id =:asi_id AND
                    da.daho_id =:horario AND ";

            if ($uaca_id == 1) {
                $sql .= "da.mpp_id = :dhpa_id AND
                        da.daca_estado = 1 AND
                        da.daca_estado_logico = 1;";
            } else {
                $sql .= "da.mpp_id = :dhpa_id AND
                         da.daca_estado = 1 AND
                         da.daca_estado_logico = 1;";
            }
        } else { // Verificación de otros tipos de distributivo.
            $sql = "SELECT
                    da.daca_id as id
                FROM
                    " . $con_academico->dbname . ".distributivo_academico AS da
                WHERE
                    da.paca_id =:paca_id AND
                    da.pro_id =:pro_id AND
                    da.tdis_id =:tdis_id AND
                    da.daca_estado = 1 AND
                    da.daca_estado_logico = 1;";
        }

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
        $comando->bindParam(":tdis_id", $tdis_id, \PDO::PARAM_INT);
        if ($tdis_id == 1) {
            $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
            $comando->bindParam(":horario", $horario, \PDO::PARAM_INT);
            $comando->bindParam(":paralelo", $paralelo, \PDO::PARAM_INT);
        }
        $res = $comando->queryOne();
        if (empty($res)) {
            return 0;
        }
        return $res;
    }

    public function getDistribucionAcademicoHorario($uaca_id, $mod_id, $jornada, $horario) {
        $con_academico = \Yii::$app->db_academico;
        $sql = "SELECT
                    dah.daho_id as daho_id
                FROM
                    " . $con_academico->dbname . ".distributivo_academico_horario AS dah
                WHERE
                    dah.uaca_id =:uaca_id AND
                    dah.mod_id =:mod_id AND
                    dah.daho_horario =:horario AND
                    dah.daho_jornada =:jornada AND
                    dah.daho_estado = 1 AND
                    dah.daho_estado_logico = 1
                ORDER BY
                    dah.daho_id DESC";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":jornada", $jornada, \PDO::PARAM_STR);
        $comando->bindParam(":horario", $horario, \PDO::PARAM_STR);
        $res = $comando->queryOne();
        return $res;
    }

    public function getDistribAcadXprofesorXperiodo($periodo, $profesor) {
        $con_academico = \Yii::$app->db_academico;
        $estado = "1";
        $sql = "SELECT  d.tdis_id as daca_tipo,
                        t.tdis_nombre as des_tipo,
                        d.asi_id, a.asi_nombre,
                        d.mod_id, m.mod_nombre,
                        d.uaca_id, u.uaca_nombre,
                        d.daho_id, h.daho_horario
                FROM " . $con_academico->dbname . ".distributivo_academico d inner join " . $con_academico->dbname . ".asignatura a on a.asi_id = d.asi_id
                    inner join " . $con_academico->dbname . ".modalidad m on m.mod_id = d.mod_id
                    inner join " . $con_academico->dbname . ".unidad_academica u on u.uaca_id = d.uaca_id
                    inner join " . $con_academico->dbname . ".distributivo_academico_horario h on h.daho_id = d.daho_id
                    inner join " . $con_academico->dbname . ".tipo_distributivo t on t.tdis_id = d.tdis_id
                WHERE d.paca_id = :paca_id
                    and d.pro_id = :pro_id
                    and d.daca_estado = :estado
                    and d.daca_estado_logico = :estado
                    and t.tdis_estado_logico = :estado
                    and t.tdis_estado = :estado;";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
        $comando->bindParam(":pro_id", $profesor, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $res = $comando->queryAll();

        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['unidad', "modalidad"],
            ],
        ]);

        return $dataProvider;
    }

    /**
     * Function insertar datos distributivo académico
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Retornar los datos).
     */
    public function insertarDistributivoAcademico($i, $data, $pro_id, $paca_id, $id_cab) {
        $con = \Yii::$app->db_academico;
        $estado = '1';
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $meun_id = "null";
        $asi_id = "null";
        $uni_id = "null";
        $mod_id = "null";
        $hor_id = "null";
        $par_id = "null";
        $par_posgrado = "null";
        $jor_id = "null";
        $txt_horas_otros = "null";
        $fecha_inicio = "null";
        $fecha_fin = "null";
        $num_estudiantes = "null";

        if ($data[$i]->programa) {
            $meun_id = $data[$i]->programa;
        }
        if ($data[$i]->fecha_inicio && $data[$i]->fecha_inicio != 'N/A') {
            $fecha_inicio = "'" . $data[$i]->fecha_inicio . "'";
        }
        if ($data[$i]->fecha_fin && $data[$i]->fecha_fin != 'N/A') {
            $fecha_fin = "'" . $data[$i]->fecha_fin . "'";
        }
        if ($data[$i]->asi_id) {
            $asi_id = $data[$i]->asi_id;
        }
        if ($data[$i]->uni_id) {
            $uni_id = $data[$i]->uni_id;
        }

        if ($data[$i]->mod_id) {
            $mod_id = $data[$i]->mod_id;
        }
        if ($data[$i]->hor_id) {
            $hor_id = $data[$i]->hor_id;
        }
        if ($uni_id == 1) {
            if ($data[$i]->par_id) {
                $par_id = $data[$i]->par_id;
            }
        } else {
            if ($data[$i]->par_id) {
                $par_posgrado = $data[$i]->par_id;
            }
        }

        if ($data[$i]->jor_id) {
            $jor_id = $data[$i]->jor_id;
        }
        if ($data[$i]->num_estudiantes) {
            $num_estudiantes = $data[$i]->num_estudiantes;
        }
        if ($data[$i]->txt_horas_otros) {
            $txt_horas_otros = $data[$i]->txt_horas_otros;
        }

        $sql = "INSERT INTO " . $con->dbname . ".distributivo_academico
                        (paca_id,        dcab_id,            tdis_id,             asi_id,          pro_id,       uaca_id,              mod_id,                daho_id,                mpp_id,                   daca_num_estudiantes_online,                   daca_fecha_registro,       daca_usuario_ingreso, daca_estado, daca_estado_logico,daca_jornada,         daca_horario, daca_fecha_inicio_post,daca_fecha_fin_post,daca_horas_otras_actividades,meun_id,pppr_id) VALUES
                        (" . $paca_id . "," . $id_cab . "," . $data[$i]->tasi_id . " , " . $asi_id . ", " . $pro_id . ", " . $uni_id . ", " . $mod_id . ", " . $hor_id . ", " . $par_id . ",    " . $num_estudiantes . ",                  '" . $fecha_transaccion . "', " . $usu_id . ",          " . $estado . ", " . $estado . ",       " . $jor_id . ",        " . $hor_id . "," . $fecha_inicio . "," . $fecha_fin . "," . $txt_horas_otros . "," . $meun_id . "," . $par_posgrado . ")";

        \app\models\Utilities::putMessageLogFile("Sql: " . $sql);
        $command = $con->createCommand($sql);

        $command->execute();
        $idtabla = $con->getLastInsertID($con->dbname . '.distributivo_academico');
        return $idtabla;
    }

    public function getListarDistribProfesor($pro_id = null, $paca_id = null, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $estado = "1";

        $sql = "SELECT
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS profesor,
                    da.daca_id AS Id,
                    ifnull(ua.uaca_nombre,'') AS UnidadAcademica,
                    ifnull(m.mod_nombre,'') AS Modalidad,
                    ifnull(a.asi_nombre,'') AS Asignatura,
                    t.tdis_nombre AS tipo_asignacion,
                    ifnull(dh.daho_descripcion,'') as horario,
                    ifnull(t.tdis_id,0) idTipoAsignacion,
                    ifnull(ua.uaca_id,0) idUnidadAcademica,
                    ifnull(m.mod_id,0) idModalidad,
                    ifnull(da.paca_id,0) idPeriodo,
                    ifnull(dh.daho_jornada,0) idJornada ,
                    ifnull(a.asi_id,0) idMateria,
                    ifnull(dh.daho_id,0)  idHorario,
                    ifnull(da.mpp_id,0) idParalelo,
                   case when da.tdis_id=7 then 30 else (pc.paca_semanas_periodo * case  when dh.daho_total_horas is null then tdis_num_semanas else dh.daho_total_horas end) end as total_horas,
                    CASE
                        WHEN daho_jornada = 1 THEN '(M) Matutino'
                        WHEN daho_jornada = 2 THEN '(N) Nocturno'
                        WHEN daho_jornada = 3 THEN '(S) Semipresencial'
                        WHEN daho_jornada = 4 THEN '(D) Distancia'
                        ELSE ''
                    END AS jornada,
                    ifnull(daca_num_estudiantes_online,0) nroEstudiantes,
                    replace(UUID(),'-','') indice
                FROM
                    " . $con_academico->dbname . ".distributivo_academico AS da
                    INNER JOIN " . $con_academico->dbname . ".periodo_academico pc on da.paca_id  = pc.paca_id and  pc.paca_activo='A'
                    INNER JOIN " . $con_academico->dbname . ".profesor AS p ON da.pro_id = p.pro_id
                    INNER JOIN " . $con_db->dbname . ".persona AS pe ON p.per_id = pe.per_id
                    LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario AS dh ON da.daho_id = dh.daho_id
                    LEFT JOIN " . $con_academico->dbname . ".modalidad AS m ON da.mod_id = m.mod_id
                    LEFT JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON da.uaca_id = ua.uaca_id
                    LEFT JOIN " . $con_academico->dbname . ".asignatura AS a ON da.asi_id = a.asi_id
                    INNER JOIN " . $con_academico->dbname . ".tipo_distributivo AS t ON da.tdis_id = t.tdis_id
                WHERE
                    da.paca_id = :paca_id AND
                    da.pro_id = :pro_id AND
                    da.daca_estado_logico = :estado AND
                    da.daca_estado = :estado AND
                    t.tdis_estado = :estado AND
                    t.tdis_estado_logico = :estado";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        Utilities::putMessageLogFile('sql:' . $sql);
        $res = $comando->queryAll();
        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Nombres', "Cedula", "UnidadAcademica", "Modalidad", "Periodo"],
            ],
        ]);

        return $dataProvider;
    }

    public function getDestalleDistributivo($id, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $estado = "1";

        $sql = "SELECT
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS profesor,
                    da.daca_id AS Id,
                     da.dcab_id ,
                    ifnull(ua.uaca_nombre,'') AS UnidadAcademica,
                    ifnull(m.mod_nombre,'') AS Modalidad,
                    ifnull(a.asi_nombre,'') AS Asignatura,
                    t.tdis_nombre AS tipo_asignacion,
                    ifnull(dh.daho_descripcion,'') as horario,
                    ifnull(t.tdis_id,0) idTipoAsignacion,
                    ifnull(ua.uaca_id,0) idUnidadAcademica,
                    ifnull(m.mod_id,0) idModalidad,
                    ifnull(da.paca_id,0) idPeriodo,
                    ifnull(dh.daho_jornada,0) idJornada ,
                    ifnull(a.asi_id,0) idMateria,
                    ifnull(dh.daho_id,0)  idHorario,
                    ifnull(da.mpp_id,0) idParalelo,

                     case when m.mod_id=1 and da.tdis_id<> 7  then
                    (case
                         when (daca_num_estudiantes_online between 0 and 10) then  round(2  *pc.paca_semanas_periodo *(1.3))
			 when (daca_num_estudiantes_online between 11 and 20) then round(3  *pc.paca_semanas_periodo *(1.3))
                         when (daca_num_estudiantes_online between 21 and 30) then round(4  *pc.paca_semanas_periodo *(1.3))
                         when (daca_num_estudiantes_online between 31 and 40) then round(5  *pc.paca_semanas_periodo *(1.3))
                         when (daca_num_estudiantes_online >40) then round(7  *pc.paca_semanas_periodo *(1.3)) end)
                       else
                        case when da.tdis_id=7 then tdis_num_semanas else (pc.paca_semanas_periodo * case  when dh.daho_total_horas is null then tdis_num_semanas else dh.daho_total_horas end) end
                        end as total_horas,


                     case when m.mod_id=1 and da.tdis_id<> 7  then
                    (case
                         when (daca_num_estudiantes_online between 0 and 10)  then  round( 2  *(1.3))
			 when (daca_num_estudiantes_online between 11 and 20) then  round( 3  *(1.3))
                         when (daca_num_estudiantes_online between 21 and 30) then  round( 4  *(1.3))
                         when (daca_num_estudiantes_online between 31 and 40) then  round( 5  *(1.3))
                         when (daca_num_estudiantes_online >40) then round(7 *(1.3))  end)
                       else
                          case when da.tdis_id=7  then round(tdis_num_semanas/paca_semanas_periodo) else ( case  when dh.daho_total_horas is null then tdis_num_semanas else dh.daho_total_horas end) end
                        end as promedio,

                      CASE
                        WHEN daho_jornada = 1 THEN '(M) Matutino'
                        WHEN daho_jornada = 2 THEN '(N) Nocturno'
                        WHEN daho_jornada = 3 THEN '(S) Semipresencial'
                        WHEN daho_jornada = 4 THEN '(D) Distancia'
                        ELSE ''
                    END AS jornada,
                    ifnull(daca_num_estudiantes_online,0) nroEstudiantes,
                    replace(UUID(),'-','') indice
                FROM
                    " . $con_academico->dbname . ".distributivo_academico AS da
                    INNER JOIN " . $con_academico->dbname . ".periodo_academico pc on da.paca_id  = pc.paca_id and  pc.paca_activo='A'
                    INNER JOIN " . $con_academico->dbname . ".profesor AS p ON da.pro_id = p.pro_id
                    INNER JOIN " . $con_db->dbname . ".persona AS pe ON p.per_id = pe.per_id
                    LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario AS dh ON da.daho_id = dh.daho_id
                    LEFT JOIN " . $con_academico->dbname . ".modalidad AS m ON da.mod_id = m.mod_id
                    LEFT JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON da.uaca_id = ua.uaca_id
                    LEFT JOIN " . $con_academico->dbname . ".asignatura AS a ON da.asi_id = a.asi_id
                    INNER JOIN " . $con_academico->dbname . ".tipo_distributivo AS t ON da.tdis_id = t.tdis_id
                WHERE
                    da.dcab_id = :dcab_id AND
                   da.daca_estado_logico = :estado AND
                    da.daca_estado = :estado AND
                    t.tdis_estado = :estado AND
                    t.tdis_estado_logico = :estado
                   and  da.tdis_id<>6";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dcab_id", $id, \PDO::PARAM_INT);
        Utilities::putMessageLogFile('sql:' . $sql);
        $res = $comando->queryAll();
        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Nombres', "Cedula", "UnidadAcademica", "Modalidad", "Periodo"],
            ],
        ]);

        return $dataProvider;
    }

    public function getListarReview($id, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $DistADO = new DistributivoCabecera();
        $con_db = \Yii::$app->db;
        $estado = "1";

        $sql = "SELECT
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS profesor,
                    da.daca_id AS Id,
                    da.dcab_id,
                    ifnull(ua.uaca_nombre,'') AS UnidadAcademica,
                    ifnull(m.mod_nombre,'') AS Modalidad,
                    ifnull(a.asi_nombre,'') AS Asignatura,
                    t.tdis_nombre AS tipo_asignacion,
                    ifnull(dh.daho_descripcion,'') as horario,
                    ifnull(t.tdis_id,0) idTipoAsignacion,
                    ifnull(ua.uaca_id,0) idUnidadAcademica,
                    ifnull(m.mod_id,0) idModalidad,
                    ifnull(da.paca_id,0) idPeriodo,
                    ifnull(dh.daho_jornada,0) idJornada ,
                    ifnull(a.asi_id,0) idMateria,
                    ifnull(dh.daho_id,0)  idHorario,
                    ifnull(da.mpp_id,0) idParalelo,
                    case when m.mod_id=1 and da.tdis_id =1 then
                    (case
                         when (da.uaca_id= 1 and daca_num_estudiantes_online between 0 and 10) then  round(2  * pc.paca_semanas_periodo /**(1.3)*/)
			             when (da.uaca_id= 1 and daca_num_estudiantes_online between 11 and 20) then round(3  *pc.paca_semanas_periodo /**(1.3)*/)
                         when (da.uaca_id= 1 and daca_num_estudiantes_online between 21 and 30) then round(4  * pc.paca_semanas_periodo /**(1.3)*/)
                         when (da.uaca_id= 1 and daca_num_estudiantes_online between 31 and 40) then round(5  * pc.paca_semanas_periodo /**(1.3)*/)
                         when (da.uaca_id= 1 and daca_num_estudiantes_online >40) then round(7  * pc.paca_semanas_periodo /**(1.3)*/)
                         when (da.uaca_id= 2 ) then round((select ifnull(TRUNCATE(timestampdiff(day, das.daca_fecha_inicio_post, das.daca_fecha_fin_post)/7,0),'')
                         FROM " . $con->dbname . ".distributivo_academico das
                        WHERE das.uaca_id = 2 and das.dcab_id=da.dcab_id) * dh.daho_total_horas) -- OJO ESTE SI ESTA BIEN O NO EL CALCULO
                         end)
                       else
                        case when da.tdis_id=7 then tdis_num_semanas else (pc.paca_semanas_periodo * case  when dh.daho_total_horas is null then tdis_num_semanas else dh.daho_total_horas end) end
                        end as total_horas, -- AQUI


                     case when m.mod_id=1 and da.tdis_id =1 then
                    (case
                         when (da.uaca_id= 1 and daca_num_estudiantes_online between 0 and 10)  then 2 /*round( 2  *(1.3))*/
			             when (da.uaca_id= 1 and daca_num_estudiantes_online between 11 and 20) then 3 /*round( 3  *(1.3))*/
                         when (da.uaca_id= 1 and daca_num_estudiantes_online between 21 and 30) then 4 /*round( 4  *(1.3))*/
                         when (da.uaca_id= 1 and daca_num_estudiantes_online between 31 and 40) then 5 /*round( 5  *(1.3))*/
                         when (da.uaca_id= 1 and daca_num_estudiantes_online >40) then 7 /*round(7 *(1.3))*/  when (da.uaca_id= 2 ) then round(dh.daho_total_horas / (select ifnull(TRUNCATE(timestampdiff(day, das.daca_fecha_inicio_post, das.daca_fecha_fin_post)/7,0),'')
                         FROM " . $con->dbname . ".distributivo_academico das
                        WHERE das.uaca_id = 2 and das.dcab_id=da.dcab_id)) -- OJO ESTE SI ESTA BIEN O NO EL CALCULO
                         end)
                       else
                          case when da.tdis_id=7 then round(tdis_num_semanas/paca_semanas_periodo) else ( case  when dh.daho_total_horas is null then tdis_num_semanas else dh.daho_total_horas end) end
                        end as promedio, -- AQUI
                        ifnull(mpp_num_paralelo,'') mpp_num_paralelo,

                    CASE
                        WHEN daho_jornada = 1 THEN '(M) Matutino'
                        WHEN daho_jornada = 2 THEN '(N) Nocturno'
                        WHEN daho_jornada = 3 THEN '(S) Semipresencial'
                        WHEN daho_jornada = 4 THEN '(D) Distancia'
                        ELSE ''
                    END AS jornada,
                    ifnull(daca_num_estudiantes_online,0) nroEstudiantes,
                    ifnull(daca_fecha_inicio_post,'') as fechaI,
                    ifnull(daca_fecha_fin_post,'') as fechaf,
                    ifnull(daca_horas_otras_actividades,0) nroHoras,
                    replace(UUID(),'-','') indice
                FROM
                    " . $con_academico->dbname . ".distributivo_academico AS da
                    INNER JOIN " . $con_academico->dbname . ".periodo_academico pc on da.paca_id  = pc.paca_id and  pc.paca_activo='A'
                    INNER JOIN " . $con_academico->dbname . ".profesor AS p ON da.pro_id = p.pro_id
                    INNER JOIN " . $con_db->dbname . ".persona AS pe ON p.per_id = pe.per_id
                    LEFT JOIN " . $con_academico->dbname . ".materia_paralelo_periodo mpp on mpp.mpp_id =da.mpp_id
                    LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario AS dh ON da.daho_id = dh.daho_id
                    LEFT JOIN " . $con_academico->dbname . ".modalidad AS m ON da.mod_id = m.mod_id
                    LEFT JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON da.uaca_id = ua.uaca_id
                    LEFT JOIN " . $con_academico->dbname . ".asignatura AS a ON da.asi_id = a.asi_id
                    INNER JOIN " . $con_academico->dbname . ".tipo_distributivo AS t ON da.tdis_id = t.tdis_id
                WHERE
                    da.dcab_id = :dcab_id AND
                   da.daca_estado_logico = :estado AND
                    da.daca_estado = :estado AND
                    t.tdis_estado = :estado AND
                    t.tdis_estado_logico = :estado
                   and  da.tdis_id<>6";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dcab_id", $id, \PDO::PARAM_INT);
        //Utilities::putMessageLogFile('sqlaaaaa:' . $sql);
        $res = $comando->queryAll();

        // calculando el promedio_ajustado
        if (!empty($id)){
            $valores_promedio =$DistADO->promedio($id);
            $valores_promedio[0]['preparacion_docencia'] = /*(( $valores_promedio[0]['total_hora_semana_docencia_prese'] + $valores_promedio[0]['total_hora_semana_docencia_online']) **/ 0.30/*)*/;
            $total_hora_semana_docenciaposgrado = $valores_promedio[0]['total_hora_semana_docencia_posgrado'];
            $promedio =$DistADO->Calcularpromedioajustado($total_hora_semana_docenciaposgrado, $valores_promedio[0]['total_hora_semana_docencia'], $valores_promedio[0]['total_hora_semana_tutoria'], $valores_promedio[0]['total_hora_semana_investigacion'], $valores_promedio[0]['total_hora_semana_vinculacion'], $valores_promedio[0]['preparacion_docencia'], $valores_promedio[0]['semanas_docencia'], $valores_promedio[0]['semanas_tutoria_vinulacion_investigacion'], $valores_promedio[0]['semanas_posgrado']);

            foreach ($res as $key => $value) {
                $value['promedioajustado'] = round($promedio);
                $res[$key] =  $value;
            }
        }
        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Nombres', "Cedula", "UnidadAcademica", "Modalidad", "Periodo"],
            ],
        ]);

        return $dataProvider;
    }

    public function getListarDistribProf($id, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $estado = "1";

        $sql = "SELECT
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS profesor,
                    da.daca_id AS Id,
                     da.dcab_id ,
                    ifnull(ua.uaca_nombre,'') AS UnidadAcademica,
                    ifnull(m.mod_nombre,'') AS Modalidad,
                    ifnull(a.asi_nombre,'') AS Asignatura,
                    t.tdis_nombre AS tipo_asignacion,
                    ifnull(dh.daho_descripcion,'') as horario,
                    ifnull(t.tdis_id,0) idTipoAsignacion,
                    ifnull(ua.uaca_id,0) idUnidadAcademica,
                    ifnull(m.mod_id,0) idModalidad,
                    ifnull(da.paca_id,0) idPeriodo,
                    ifnull(dh.daho_jornada,0) idJornada ,
                    ifnull(a.asi_id,0) idMateria,
                    ifnull(dh.daho_id,0)  idHorario,
                    ifnull(da.mpp_id,0) idParalelo,
                  case when m.mod_id=1 and da.tdis_id<> 7  then
                    (case
                         when (daca_num_estudiantes_online between 0 and 10) then  round(2  *pc.paca_semanas_periodo *(1.3))
			 when (daca_num_estudiantes_online between 11 and 20) then round(3  *pc.paca_semanas_periodo *(1.3))
                         when (daca_num_estudiantes_online between 21 and 30) then round(4  *pc.paca_semanas_periodo *(1.3))
                         when (daca_num_estudiantes_online between 31 and 40) then round(5  *pc.paca_semanas_periodo *(1.3))
                         when (daca_num_estudiantes_online >40) then round(7  *pc.paca_semanas_periodo *(1.3)) end)
                       else
                        case when da.tdis_id=7 then tdis_num_semanas else (pc.paca_semanas_periodo * case  when dh.daho_total_horas is null then tdis_num_semanas else dh.daho_total_horas end) end
                        end as total_horas,


                     case when m.mod_id=1 and da.tdis_id<> 7  then
                    (case
                         when (daca_num_estudiantes_online between 0 and 10)  then  round( 2  *(1.3))
			 when (daca_num_estudiantes_online between 11 and 20) then  round( 3  *(1.3))
                         when (daca_num_estudiantes_online between 21 and 30) then  round( 4  *(1.3))
                         when (daca_num_estudiantes_online between 31 and 40) then  round( 5  *(1.3))
                         when (daca_num_estudiantes_online >40) then round(7 *(1.3))  end)
                       else
                          case when da.tdis_id=7  then round(tdis_num_semanas/paca_semanas_periodo) else ( case  when dh.daho_total_horas is null then tdis_num_semanas else dh.daho_total_horas end) end
                        end as promedio,

                 --  case when da.tdis_id=7 then tdis_num_semanas else (pc.paca_semanas_periodo * case  when dh.daho_total_horas is null then tdis_num_semanas else dh.daho_total_horas end) end as total_horas,
                 -- case when da.tdis_id=7 then round(tdis_num_semanas/paca_semanas_periodo) else ( case  when dh.daho_total_horas is null then tdis_num_semanas else dh.daho_total_horas end) end as promedio,

                     CASE
                        WHEN daho_jornada = 1 THEN '(M) Matutino'
                        WHEN daho_jornada = 2 THEN '(N) Nocturno'
                        WHEN daho_jornada = 3 THEN '(S) Semipresencial'
                        WHEN daho_jornada = 4 THEN '(D) Distancia'
                        ELSE ''
                    END AS jornada,
                    ifnull(daca_num_estudiantes_online,0) nroEstudiantes,
                    ifnull(daca_fecha_inicio_post,'') as fechaI,
                    ifnull(daca_fecha_fin_post,'') as fechaf,
                    ifnull(daca_horas_otras_actividades,0) nroHoras,
                    replace(UUID(),'-','') indice
                FROM
                    " . $con_academico->dbname . ".distributivo_academico AS da
                    INNER JOIN " . $con_academico->dbname . ".periodo_academico pc on da.paca_id  = pc.paca_id and  pc.paca_activo='A'
                    INNER JOIN " . $con_academico->dbname . ".profesor AS p ON da.pro_id = p.pro_id
                    INNER JOIN " . $con_db->dbname . ".persona AS pe ON p.per_id = pe.per_id
                    LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario AS dh ON da.daho_id = dh.daho_id
                    LEFT JOIN " . $con_academico->dbname . ".modalidad AS m ON da.mod_id = m.mod_id
                    LEFT JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON da.uaca_id = ua.uaca_id
                    LEFT JOIN " . $con_academico->dbname . ".asignatura AS a ON da.asi_id = a.asi_id
                    INNER JOIN " . $con_academico->dbname . ".tipo_distributivo AS t ON da.tdis_id = t.tdis_id
                WHERE
                    da.dcab_id = :dcab_id AND
                   da.daca_estado_logico = :estado AND
                    da.daca_estado = :estado AND
                    t.tdis_estado = :estado AND
                    t.tdis_estado_logico = :estado
                   and  da.tdis_id<>6";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dcab_id", $id, \PDO::PARAM_INT);
        Utilities::putMessageLogFile('sql:' . $sql);
        $res = $comando->queryAll();
        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Nombres', "Cedula", "UnidadAcademica", "Modalidad", "Periodo"],
            ],
        ]);

        return $dataProvider;
    }

    /**
     * Function inactivar datos distributivo académico
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Retornar los datos).
     */
    public function inactivarDistributivoAcademico($pro_id, $paca_id) {
        $con = \Yii::$app->db_academico;
        $estado = '1';
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);

        $sql = "UPDATE " . $con->dbname . ".distributivo_academico
                SET daca_fecha_modificacion = :fecha,
                    daca_usuario_modifica = :usuario,
                    daca_estado = '0',
                    daca_estado_logico = '0'
                WHERE paca_id = :paca_id
                     AND pro_id = :pro_id
                     AND daca_estado = :estado
                     AND daca_estado = :estado";

        $command = $con->createCommand($sql);
        $command->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $command->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
        $command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $command->bindParam(":usuario", $usu_id, \PDO::PARAM_INT);
        $command->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $idtabla = $command->execute();
        return $idtabla;
    }

    /**
     * Function Verifica si ya existe el mismo tipo de distributivo en otro profesor.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Retornar los datos).
     */
    public function existsDistribAcadOtroProf($uaca_id, $tasi_id, $asi_id, $paca_id, $horario, $paralelo) {
        $con_academico = \Yii::$app->db_academico;
        $con_asgard = \Yii::$app->db_asgard;
        $estado = "1";

        $sql = "Select daca_id,
                       concat(p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,''), ' ', p.per_pri_nombre, ' ', ifnull(p.per_seg_nombre,'')) as profesor,
                       a.asi_nombre as asignatura
                from db_academico.distributivo_academico d
                    inner  join db_academico.profesor pr on pr.pro_id = d.pro_id
                     inner join db_asgard.persona p on p.per_id = pr.per_id
                     inner join db_academico.asignatura a on a.asi_id = d.asi_id
                  where d.paca_id = " . $paca_id . " and d.asi_id =" . $asi_id .
                " and d.tdis_id =" . $tasi_id . " and d.daho_id =" . $horario .
                "  and pr.pro_estado = 1
                      and pr.pro_estado_logico = 1
                      and p.per_estado = 1
                      and p.per_estado_logico = 1
                      and d.daca_estado = 1
                      and d.daca_estado_logico = 1
                      and d.mpp_id =" . $paralelo;
        if ($uaca_id == 1) {

            $sql .= " order by d.daca_id asc
                     limit 1;";
        } else {

            $sql .= " order by d.daca_id asc
            limit 1;";
        }

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
        $comando->bindParam(":horario", $horario, \PDO::PARAM_INT);
        $comando->bindParam(":paralelo", $paralelo, \PDO::PARAM_INT);
        $comando->bindParam(":tasi_id", $tasi_id, \PDO::PARAM_INT);
        $comando->bindParam("estado", $estado, \PDO::PARAM_STR);

        $res = $comando->queryOne();

        return $res;
    }

    /**
     * Function Verifica los programas de estudio por unidad y modalidad.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Retornar los datos).
     */
    public function getModalidadEstudio($uaca_id, $mod_id) {
        \app\models\Utilities::putMessageLogFile('UnidadAcademica modelo en carrera: ' . $uaca_id);
        \app\models\Utilities::putMessageLogFile('Modalidad modelo: ' . $mod_id);
        $con_academico = \Yii::$app->db_academico;
        $estado = "1";

        $sql = "SELECT a.meun_id id, b.eaca_nombre name
                FROM db_academico.modalidad_estudio_unidad a
                inner join db_academico.estudio_academico b   on b.eaca_id = a.eaca_id
                WHERE a.uaca_id = $uaca_id
                      and a.mod_id = $mod_id
                      and a.meun_estado = 1
                      and a.meun_estado_logico = 1";

        $comando = $con_academico->createCommand($sql);
//   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
//  $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
//    $comando->bindParam("estado", $estado, \PDO::PARAM_STR);

        $res = $comando->queryAll();
        return $res;
    }
/**
* Function getHorariosmppid
* @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
* @property integer
* @return horario de un paralelo especifico
 */
    public function getHorariosmppid($mpp_id) {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    daho.daho_id as id,
                    daho.daho_descripcion as name
                FROM
                    " . $con_academico->dbname . ".materia_paralelo_periodo mpp
                INNER JOIN " . $con_academico->dbname . ".distributivo_academico_horario daho
                ON daho.daho_id = mpp.daho_id
                WHERE
                    mpp.mpp_id = :mpp_id AND
                    daho.daho_estado = :estado AND
                    daho.daho_estado_logico = :estado";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":mpp_id", $mpp_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        return $res;
    }

}