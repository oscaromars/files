<?php

namespace app\modules\academico\models;

use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "distributivo".
 *
 * @property int $dis_id
 * @property int $pro_id
 * @property int $asi_id
 * @property int $saca_id
 * @property string $dis_estado
 * @property string $dis_fecha_creacion
 * @property string $dis_fecha_modificacion
 * @property string $dis_estado_logico
 *
 * @property SemestreAcademico $saca
 * @property Profesor $pro
 * @property Asignatura $asi
 */
class Distributivo extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'distributivo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pro_id', 'asi_id', 'saca_id', 'dis_estado', 'dis_estado_logico'], 'required'],
            [['pro_id', 'asi_id', 'saca_id'], 'integer'],
            [['dis_fecha_creacion', 'dis_fecha_modificacion'], 'safe'],
            [['dis_estado', 'dis_estado_logico'], 'string', 'max' => 1],
            [['saca_id'], 'exist', 'skipOnError' => true, 'targetClass' => SemestreAcademico::className(), 'targetAttribute' => ['saca_id' => 'saca_id']],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
            [['asi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asi_id' => 'asi_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'dis_id' => 'Dis ID',
            'pro_id' => 'Pro ID',
            'asi_id' => 'Asi ID',
            'saca_id' => 'Saca ID',
            'dis_estado' => 'Dis Estado',
            'dis_fecha_creacion' => 'Dis Fecha Creacion',
            'dis_fecha_modificacion' => 'Dis Fecha Modificacion',
            'dis_estado_logico' => 'Dis Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaca() {
        return $this->hasOne(SemestreAcademico::className(), ['saca_id' => 'saca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro() {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsi() {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
    }

    /**
     * Function Obtiene información de distributivo.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDistributivo($arrFiltro = array()) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_seg_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= " ua.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['semestre'] != "" && $arrFiltro['semestre'] > 0) {
                $str_search .= "d.saca_id = :semestre AND ";
            }
        }
        $sql = "SELECT  d.dis_id,
                        p.pro_id,
                        per.per_cedula,
                        per.per_pri_nombre,
                        per.per_seg_nombre,
                        per.per_pri_apellido,
                        per.per_seg_apellido,
                        concat(per.per_pri_nombre,' ', per.per_pri_apellido) as docente,
                        d.asi_id,
                        a.asi_nombre as asignatura,
                        ua.uaca_id,
                        ua.uaca_nombre as unidad,
                        dd.ddoc_nombre as dedicacion,
                        d.saca_id,
                        concat(sa.saca_nombre,sa.saca_anio) as semestre,
                        d.dis_descripcion
                FROM " . $con->dbname . ".distributivo d inner join " . $con->dbname . ".profesor p on p.pro_id = d.pro_id
                inner join " . $con1->dbname . ".persona per on per.per_id = p.per_id
                inner join " . $con->dbname . ".asignatura a on a.asi_id = d.asi_id
                inner join " . $con->dbname . ".unidad_academica ua on ua.uaca_id = a.uaca_id 
                inner join " . $con->dbname . ".dedicacion_docente dd on dd.ddoc_id = d.ddoc_id 
                inner join " . $con->dbname . ".semestre_academico sa on sa.saca_id = d.saca_id
                WHERE $str_search
                      d.dis_estado = '1'
                      and d.dis_estado_logico = '1'
                      and p.pro_estado = '1'
                      and p.pro_estado_logico = '1'
                      and per.per_estado = '1'
                      and per.per_estado_logico = '1'
                      and a.asi_estado = '1'
                      and a.asi_estado_logico = '1'
                      and ua.uaca_estado = '1'
                      and ua.uaca_estado_logico = '1'
                      and dd.ddoc_estado = '1'
                      and dd.ddoc_estado_logico = '1'
                      and sa.saca_estado = '1'
                      and sa.saca_estado_logico = '1' 
                ORDER BY d.dis_id desc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['semestre'] != "" && $arrFiltro['semestre'] > 0) {
                $search_semestre = $arrFiltro["semestre"];
                $comando->bindParam(":semestre", $search_semestre, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);
        return $dataProvider;
    }

    /**
     * Function Obtiene información de distributivo para excel.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDistributivoReporte($arrFiltro = array()) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_seg_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= " ua.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['semestre'] != "" && $arrFiltro['semestre'] > 0) {
                $str_search .= "d.saca_id = :semestre AND ";
            }
        }
        $sql = "SELECT  per.per_cedula,                        
                        concat(per.per_pri_nombre,' ', per.per_pri_apellido) as docente,   
                        dd.ddoc_nombre as dedicacion,                        
                        ua.uaca_nombre as unidad,
                        a.asi_nombre as asignatura,                                                
                        concat(sa.saca_nombre,' ',sa.saca_anio) as semestre,
                        d.dis_descripcion
                FROM " . $con->dbname . ".distributivo d inner join " . $con->dbname . ".profesor p on p.pro_id = d.pro_id
                inner join " . $con1->dbname . ".persona per on per.per_id = p.per_id
                inner join " . $con->dbname . ".asignatura a on a.asi_id = d.asi_id
                inner join " . $con->dbname . ".unidad_academica ua on ua.uaca_id = a.uaca_id 
                inner join " . $con->dbname . ".dedicacion_docente dd on dd.ddoc_id = d.ddoc_id 
                inner join " . $con->dbname . ".semestre_academico sa on sa.saca_id = d.saca_id
                WHERE $str_search
                      d.dis_estado = '1'
                      and d.dis_estado_logico = '1'
                      and p.pro_estado = '1'
                      and p.pro_estado_logico = '1'
                      and per.per_estado = '1'
                      and per.per_estado_logico = '1'
                      and a.asi_estado = '1'
                      and a.asi_estado_logico = '1'
                      and ua.uaca_estado = '1'
                      and ua.uaca_estado_logico = '1'
                      and dd.ddoc_estado = '1'
                      and dd.ddoc_estado_logico = '1'
                      and sa.saca_estado = '1'
                      and sa.saca_estado_logico = '1' 
                ORDER BY d.dis_id desc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['semestre'] != "" && $arrFiltro['semestre'] > 0) {
                $search_semestre = $arrFiltro["semestre"];
                $comando->bindParam(":semestre", $search_semestre, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function Obtiene información de carga horaria.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarCargaHoraria($arrFiltro = array()) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_seg_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";

            if ($arrFiltro['tipo'] != "" && $arrFiltro['tipo'] > 0) {
                $str_search .= " d.tdis_id = :tipo AND d.dcho_horas > 0 AND ";
            }
            if ($arrFiltro['semestre'] != "" && $arrFiltro['semestre'] > 0) {
                $str_search .= "d.saca_id = :semestre AND ";
            }
        }
        $sql = "SELECT  p.pro_id,
                        ifnull(per.per_cedula,'') as per_cedula,
                        per.per_pri_nombre,
                        per.per_seg_nombre,
                        per.per_pri_apellido,
                        per.per_seg_apellido,
                        ifnull(concat(per.per_pri_nombre,' ', per.per_pri_apellido),'') as docente,
                        d.saca_id,
                        (case when d.saca_id > 0 then
                            CONCAT(sa.saca_nombre,' ',sa.saca_anio)
                            else '' end) as semestre,
                        ifnull(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 1 THEN dcho_horas end),'') as docencia,
                        ifnull(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 2 THEN dcho_horas end),'') as tutoria,
			ifnull(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 3 THEN dcho_horas end),'') as investigacion,
			ifnull(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 4 THEN dcho_horas end),'') as vinculacion,
			ifnull(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 5 THEN dcho_horas end),'') as administrativa,
			ifnull(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 6 THEN dcho_horas end),'') as otras,
                        ifnull(SUM(dcho_horas),'') as total
                FROM " . $con->dbname . ".distributivo_carga_horaria d
                inner join " . $con->dbname . ".profesor p on p.pro_id = d.pro_id
                inner join " . $con1->dbname . ".persona per on per.per_id = p.per_id						
                inner join " . $con->dbname . ".semestre_academico sa on sa.saca_id = d.saca_id
                inner join " . $con->dbname . ".tipo_distributivo t on t.tdis_id = d.tdis_id
                WHERE $str_search					  
                      d.dcho_estado = :estado
                      and d.dcho_estado_logico = :estado
                      and p.pro_estado = :estado
                      and p.pro_estado_logico = :estado
                      and per.per_estado = :estado
                      and per.per_estado_logico = :estado                      
                      and sa.saca_estado = :estado
                      and sa.saca_estado_logico = :estado
                GROUP BY  d.saca_id, d.pro_id";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['tipo'] != "" && $arrFiltro['tipo'] > 0) {
                $search_tipo = $arrFiltro["tipo"];
                $comando->bindParam(":tipo", $search_tipo, \PDO::PARAM_INT);
            }
            if ($arrFiltro['semestre'] != "" && $arrFiltro['semestre'] > 0) {
                $search_semestre = $arrFiltro["semestre"];
                $comando->bindParam(":semestre", $search_semestre, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);
        return $dataProvider;
    }

    /**
     * Function Obtiene información de carga horaria.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarCargaHorariaReporte($arrFiltro = array()) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_seg_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";

            if ($arrFiltro['tipo'] != "" && $arrFiltro['tipo'] > 0) {
                $str_search .= " d.tdis_id = :tipo AND d.dcho_horas > 0 AND ";
            }
            if ($arrFiltro['semestre'] != "" && $arrFiltro['semestre'] > 0) {
                $str_search .= "d.saca_id = :semestre AND ";
            }
        }
        $sql = "SELECT  per.per_cedula,                        
                        IFNULL(concat(per.per_pri_nombre,' ', per.per_pri_apellido),'') as docente,                        
                        (CASE WHEN d.saca_id > 0 then
                            CONCAT(sa.saca_nombre,' ',sa.saca_anio)
                            else '' END) as semestre,
                        IFNULL(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 1 THEN dcho_horas end),'') as docencia,
			IFNULL(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 2 THEN dcho_horas end),'') as tutoria,
			IFNULL(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 3 THEN dcho_horas end),'') as investigacion,
			IFNULL(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 4 THEN dcho_horas end),'') as vinculacion,
			IFNULL(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 5 THEN dcho_horas end),'') as administrativa,
			IFNULL(GROUP_CONCAT(CASE
                            WHEN d.tdis_id = 6 THEN dcho_horas end),'') as otras,
			SUM(dcho_horas) as total
                FROM " . $con->dbname . ".distributivo_carga_horaria d
                inner join " . $con->dbname . ".profesor p on p.pro_id = d.pro_id
                inner join " . $con1->dbname . ".persona per on per.per_id = p.per_id						
                inner join " . $con->dbname . ".semestre_academico sa on sa.saca_id = d.saca_id
                inner join " . $con->dbname . ".tipo_distributivo t on t.tdis_id = d.tdis_id
                WHERE $str_search					  
                      d.dcho_estado = :estado
                      and d.dcho_estado_logico = :estado
                      and p.pro_estado = :estado
                      and p.pro_estado_logico = :estado
                      and per.per_estado = :estado
                      and per.per_estado_logico = :estado                      
                      and sa.saca_estado = :estado
                      and sa.saca_estado_logico = :estado
                GROUP BY  d.saca_id, d.pro_id";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['tipo'] != "" && $arrFiltro['tipo'] > 0) {
                $search_tipo = $arrFiltro["tipo"];
                $comando->bindParam(":tipo", $search_tipo, \PDO::PARAM_INT);
            }
            if ($arrFiltro['semestre'] != "" && $arrFiltro['semestre'] > 0) {
                $search_semestre = $arrFiltro["semestre"];
                $comando->bindParam(":semestre", $search_semestre, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function Obtiene información de distributivo por profesor.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDistributivoxProfesor($arrFiltro = array(), $id_profesor, $reporte) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(p.per_pri_nombre like :search OR ";
            $str_search .= "p.per_seg_nombre like :search OR ";
            $str_search .= "p.per_pri_apellido like :search OR ";
            $str_search .= "p.per_seg_apellido like :search OR ";
            $str_search .= "p.per_cedula like :search) AND ";

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "a.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "a.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "a.paca_id = :periodo AND ";
            }
            if ($arrFiltro['estado'] == "C" or $arrFiltro['estado'] == "N") {
                $str_search .= "ifnull(m.ccar_estado_cancela,'N') = :estpago AND ";
            }
            if ($arrFiltro['jornada'] != "" && $arrFiltro['jornada'] > 0) {
                $str_search .= "a.daca_jornada = :jornada AND ";
            }
        }
        $sql = "SELECT  distinct d.uaca_nombre as unidad, e.mod_nombre as modalidad,
                        p.per_cedula as identificacion, 
                        concat(p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,''), ' ', p.per_pri_nombre) as estudiante,
                        concat(saca_nombre, '-', baca_nombre,'-',baca_anio) as periodo,
                        z.asi_nombre as asignatura,
                        -- case when m.eppa_estado_pago = '1' then 'Autorizado' else 'No Autorizado' end as pago
                        -- case when m.ccar_estado_cancela = 'C' then 'Autorizado' else 'No Autorizado' end as pago
                        case 
                                when m.ccar_fecha_vencepago <= NOW() then  ifnull((SELECT
                                            CASE WHEN mi.ccar_estado_cancela = 'C'
                                            THEN 'Autorizado'
                                            ELSE 'No Autorizado' END AS pago
                                            FROM db_facturacion.carga_cartera mi
                                            WHERE mi.est_id = g.est_id and mi.ccar_fecha_vencepago <= NOW()
                                            ORDER BY mi.ccar_fecha_vencepago desc
                                            LIMIT 1),'No Autorizado')
                                when m.ccar_fecha_vencepago >= NOW() then ifnull((SELECT
                                            CASE WHEN mi.ccar_estado_cancela = 'C'
                                            THEN 'Autorizado'
                                            ELSE 'No Autorizado' END AS pago
                                            FROM db_facturacion.carga_cartera mi
                                            WHERE mi.est_id = g.est_id and mi.ccar_fecha_vencepago >= NOW()
                                            ORDER BY mi.ccar_fecha_vencepago asc
                                            LIMIT 1),'No Autorizado')						 
                                else 'No Autorizado'
                                end as pago 
                FROM " . $con->dbname . ".distributivo_academico a inner join " . $con->dbname . ".profesor b
                    on b.pro_id = a.pro_id 
                    inner join " . $con1->dbname . ".persona c on c.per_id = b.per_id
                    inner join " . $con->dbname . ".unidad_academica d on d.uaca_id = a.uaca_id
                    inner join " . $con->dbname . ".modalidad e on e.mod_id = a.mod_id
                    inner join " . $con->dbname . ".periodo_academico f on f.paca_id = a.paca_id
                    inner join " . $con->dbname . ".distributivo_academico_estudiante g on g.daca_id = a.daca_id
                    inner join " . $con->dbname . ".estudiante h on h.est_id = g.est_id
                    inner join " . $con1->dbname . ".persona p on p.per_id = h.per_id
                    inner join " . $con->dbname . ".semestre_academico s on s.saca_id = f.saca_id
                    inner join " . $con->dbname . ".bloque_academico t on t.baca_id = f.baca_id
                    inner join " . $con->dbname . ".asignatura z on a.asi_id = z.asi_id
                    -- left join " . $con->dbname . ".estudiante_periodo_pago m on (m.est_id = g.est_id and m.paca_id = f.paca_id)
                    left join " . $con2->dbname . ".carga_cartera m on (m.est_id = g.est_id /* and m.paca_id = f.paca_id */)
                WHERE $str_search c.per_id = :profesor
                    and f.paca_activo = 'A'
                    and a.daca_estado = :estado
                    and a.daca_estado_logico = :estado
                    and g.daes_estado = :estado
                    and g.daes_estado_logico = :estado 
                order by p.per_pri_apellido, p.per_seg_apellido";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":profesor", $id_profesor, \PDO::PARAM_INT);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $search_mod = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $search_mod, \PDO::PARAM_INT);
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $search_per = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $search_per, \PDO::PARAM_INT);
            }
            if ($arrFiltro['estado'] == "C" or $arrFiltro['estado'] == "N") {
                $search_estado = $arrFiltro["estado"];
                $comando->bindParam(":estpago", $search_estado, \PDO::PARAM_STR);
            }
            if ($arrFiltro['jornada'] != "" && $arrFiltro['jornada'] > 0) {
                $search_jor = $arrFiltro["jornada"];
                $comando->bindParam(":jornada", $search_jor, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);
        if ($reporte == 1) {
            return $dataProvider;
        } else {
            return $resultData;
        }
    }

    /**
     * Function Obtiene información de distributivo todos los estudiantes.
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDistributivoxEstudiante($arrFiltro = array(), $reporte) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(p.per_pri_nombre like :search OR ";
            $str_search .= "p.per_seg_nombre like :search OR ";
            $str_search .= "p.per_pri_apellido like :search OR ";
            $str_search .= "p.per_seg_apellido like :search OR ";
            $str_search .= "p.per_cedula like :search) AND ";

            if (!empty($arrFiltro['profesor'])) {
                $str_search .= "(pe.per_pri_nombre like :profesor OR ";
                $str_search .= "pe.per_seg_nombre like :profesor OR ";
                $str_search .= "pe.per_pri_apellido like :profesor OR ";
                $str_search .= "pe.per_seg_apellido like :profesor) AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "a.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "a.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "a.paca_id = :periodo AND ";
            }
            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $str_search .= "a.asi_id = :asignatura AND ";
            }
            /*********** comentar y cambiar tomando el estado de tabla anterior */
           /* if ($arrFiltro['estado_pago'] != '-1') {
                if ($arrFiltro['estado_pago'] == '0') {
                    $str_search .= " (m.eppa_estado_pago = :estado_pago OR m.eppa_estado_pago IS NULL) AND ";
                } else {
                    $str_search .= " m.eppa_estado_pago = :estado_pago AND ";
                }
            }*/
            if ($arrFiltro['estado_pago'] == "0" or $arrFiltro['estado_pago'] == "1") {            
                if ($arrFiltro['estado_pago'] == "0") {            
                $str_search .= " (m.ccar_estado_cancela is null OR m.ccar_estado_cancela = :estado_pago) AND ";
            }else{
                $str_search .= " m.ccar_estado_cancela = :estado_pago AND ";
            } 
        } 
            /**************************************************************  **/ 
            if ($arrFiltro['jornada'] != "" && $arrFiltro['jornada'] > 0) {
                $str_search .= "a.daca_jornada = :jornada AND ";
            }
        }
        $sql = "SELECT  distinct h.est_id, 
                        d.uaca_nombre as unidad, 
                        e.mod_nombre as modalidad,
                        p.per_cedula as identificacion, 
                        concat(p.per_pri_nombre, ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as estudiante,
                        concat(saca_nombre, '-', baca_nombre,'-',baca_anio) as periodo,
                        z.asi_nombre as asignatura,
                        -- case 
                          --   when m.eppa_estado_pago = '0' then 'No Autorizado' 
                          --   when m.eppa_estado_pago = '1' then 'Autorizado'
                          --   else 'No Autorizado'
                          --   end as 'pago',                           
                          case 
                                when m.ccar_fecha_vencepago <= NOW() then  ifnull((SELECT
                                            CASE WHEN mi.ccar_estado_cancela = 'C'
                                            THEN 'Autorizado'
                                            ELSE 'No Autorizado' END AS pago
                                            FROM db_facturacion.carga_cartera mi
                                            WHERE mi.est_id = g.est_id and mi.ccar_fecha_vencepago <= NOW()
                                            ORDER BY mi.ccar_fecha_vencepago desc
                                            LIMIT 1),'No Autorizado')
                                when m.ccar_fecha_vencepago >= NOW() then ifnull((SELECT
                                            CASE WHEN mi.ccar_estado_cancela = 'C'
                                            THEN 'Autorizado'
                                            ELSE 'No Autorizado' END AS pago
                                            FROM db_facturacion.carga_cartera mi
                                            WHERE mi.est_id = g.est_id and mi.ccar_fecha_vencepago >= NOW()
                                            ORDER BY mi.ccar_fecha_vencepago asc
                                            LIMIT 1),'No Autorizado')						 
                                else 'No Autorizado'
                                end as pago  
                        -- ifnull(DATE_FORMAT(m.eppa_fecha_registro, '%Y-%m-%d'), ' ') as fecha_pago 
                FROM " . $con->dbname . ".distributivo_academico a inner join " . $con->dbname . ".profesor b
                    on b.pro_id = a.pro_id 
                    inner join " . $con1->dbname . ".persona c on c.per_id = b.per_id
                    inner join " . $con1->dbname . ".persona pe on pe.per_id = b.per_id
                    inner join " . $con->dbname . ".unidad_academica d on d.uaca_id = a.uaca_id
                    inner join " . $con->dbname . ".modalidad e on e.mod_id = a.mod_id
                    inner join " . $con->dbname . ".periodo_academico f on f.paca_id = a.paca_id
                    inner join " . $con->dbname . ".distributivo_academico_estudiante g on g.daca_id = a.daca_id
                    inner join " . $con->dbname . ".estudiante h on h.est_id = g.est_id
                    inner join " . $con1->dbname . ".persona p on p.per_id = h.per_id
                    inner join " . $con->dbname . ".semestre_academico s on s.saca_id = f.saca_id
                    inner join " . $con->dbname . ".bloque_academico t on t.baca_id = f.baca_id
                    inner join " . $con->dbname . ".asignatura z on a.asi_id = z.asi_id
                    -- left join " . $con->dbname . ".estudiante_periodo_pago m on (m.est_id = g.est_id and m.paca_id = f.paca_id)
                    left join " . $con2->dbname . ".carga_cartera m on (m.est_id = g.est_id /* and m.paca_id = f.paca_id */)
                    WHERE $str_search /* f.paca_activo = 'A'
                    and*/ a.daca_estado = :estado
                    and a.daca_estado_logico = :estado
                    and g.daes_estado = :estado
                    and g.daes_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);

            if (!empty($arrFiltro['profesor'])) {
                $search_profe = "%" . $arrFiltro["profesor"] . "%";
                $comando->bindParam(":profesor", $search_profe, \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $search_mod = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $search_mod, \PDO::PARAM_INT);
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $search_per = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $search_per, \PDO::PARAM_INT);
            }
            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $search_asi = $arrFiltro["asignatura"];
                $comando->bindParam(":asignatura", $search_asi, \PDO::PARAM_INT);
            }
            /*********** comentar y cambiar tomando el estado de tabla anterior */
            /*if ($arrFiltro['estado_pago'] != '-1') {
                $comando->bindParam(":estado_pago", $arrFiltro['estado_pago'], \PDO::PARAM_STR);
            }*/
            if ($arrFiltro['estado_pago'] != '-1') {
                if ($arrFiltro['estado_pago'] == '0') {
                    $filestado = 'N';
                } else {
                    $filestado = 'C';
              } 
                $comando->bindParam(":estado_pago", $filestado, \PDO::PARAM_STR);
            }
            /***************************************************************** */

            if ($arrFiltro['jornada'] != "" && $arrFiltro['jornada'] > 0) {
                $search_jor = $arrFiltro["jornada"];
                $comando->bindParam(":jornada", $search_jor, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);
        if ($reporte == 1) {
            return $dataProvider;
        } else {
            return $resultData;
        }
    }

    /**
     * Function obtener asigantura segun unidad academico y modalidad
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarAsiganturaxuniymoda($uaca_id, $mod_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT distinct asig.asi_id as id,
                           asig.asi_nombre as name
                    FROM " . $con->dbname . ".distributivo_academico diac "
                . "inner join " . $con->dbname . ".asignatura asig ON asig.asi_id = diac.asi_id
                    WHERE diac.uaca_id = :uaca_id 
                    and diac.mod_id =:mod_id
                    and diac.daca_estado_logico = :estado
                    and diac.daca_estado = :estado
                    and asig.asi_estado_logico = :estado
                    and asig.asi_estado = :estado
                    ORDER BY 1 asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function Consulta si ya tiene data en la tabla period pago, segun periodo academico y est_id.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  estudiante_periodo_pago_id eppa_id
     */
    public function consultarPeriodopago($paca_id, $ppro_id, $est_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        if ($paca_id > 0) {
            $busqueda = "paca_id= :idbusqueda AND";
        }
        if ($ppro_id > 0) {
            $busqueda = "ppro_id= :idbusqueda AND";
        }
        $sql = "
                    SELECT eppa_id as eppa_id
                    FROM 
                        " . $con->dbname . ".estudiante_periodo_pago                   
                    WHERE
                        -- paca_id= :idbusqueda AND
                        $busqueda
                        est_id= :est_id AND                                               
                        eppa_estado = :estado AND
                        eppa_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if ($paca_id > 0) {
            $comando->bindParam(":idbusqueda", $paca_id, \PDO::PARAM_INT);
        }
        if ($ppro_id > 0) {
            $comando->bindParam(":idbusqueda", $ppro_id, \PDO::PARAM_INT);
        }
        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function insertarPagoestudiante crea pagos estudiante.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarPagoestudiante($paca_id, $ppro_id, $est_id, $eppa_estado_pago, $usu_id) {
        $con = \Yii::$app->db_academico;
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "eppa_estado";
        $bdet_sql = "1";

        $param_sql .= ", eppa_estado_logico";
        $bdet_sql .= ", 1";

        if (isset($paca_id)) {
            $param_sql .= ", paca_id";
            $bdet_sql .= ", :paca_id";
        }
        if (isset($ppro_id)) {
            $param_sql .= ", ppro_id";
            $bdet_sql .= ", :ppro_id";
        }
        if (isset($est_id)) {
            $param_sql .= ", est_id";
            $bdet_sql .= ", :est_id";
        }
        if (isset($eppa_estado_pago)) {
            $param_sql .= ", eppa_estado_pago";
            $bdet_sql .= ", :eppa_estado_pago";
        }
        if (isset($fecha)) {
            $param_sql .= ", eppa_fecha_registro";
            $bdet_sql .= ", :eppa_fecha_registro";
        }
        if (isset($usu_id)) {
            $param_sql .= ", eppa_usuario_ingreso";
            $bdet_sql .= ", :eppa_usuario_ingreso";
        }
        if (isset($fecha)) {
            $param_sql .= ", eppa_fecha_creacion";
            $bdet_sql .= ", :eppa_fecha_creacion";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".estudiante_periodo_pago ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($paca_id)) {
                $comando->bindParam(':paca_id', $paca_id, \PDO::PARAM_INT);
            }
            if (isset($ppro_id)) {
                $comando->bindParam(':ppro_id', $ppro_id, \PDO::PARAM_INT);
            }
            if (isset($est_id)) {
                $comando->bindParam(':est_id', $est_id, \PDO::PARAM_INT);
            }
            if (isset($fecha)) {
                $comando->bindParam(':eppa_fecha_registro', $fecha, \PDO::PARAM_STR);
            }
            if (isset($eppa_estado_pago)) {
                $comando->bindParam(':eppa_estado_pago', $eppa_estado_pago, \PDO::PARAM_STR);
            }
            if (!empty((isset($usu_id)))) {
                $comando->bindParam(':eppa_usuario_ingreso', $usu_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($fecha)))) {
                $comando->bindParam(':eppa_fecha_creacion', $fecha, \PDO::PARAM_STR);
            }
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.estudiante_periodo_pago');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function modificarPagoestudiante.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function modificarPagoestudiante($paca_id, $ppro_id, $est_id, $eppa_estado_pago, $usu_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        if ($paca_id > 0) {
            $modifica = "paca_id= :modifica AND";
        }
        if ($ppro_id > 0) {
            $modifica = "ppro_id= :modifica AND";
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".estudiante_periodo_pago		       
                      SET eppa_estado_pago = :eppa_estado_pago,
                          eppa_usuario_modifica = :eppa_usuario_modifica,
                          eppa_fecha_registro = :fecha,
                          eppa_fecha_modificacion = :fecha
                      WHERE                     
                        $modifica
                        est_id =  :est_id AND
                        eppa_estado = :estado AND
                        eppa_estado_logico = :estado");

            if ($paca_id > 0) {
                $comando->bindParam(':modifica', $paca_id, \PDO::PARAM_INT);
            }
            if ($ppro_id > 0) {
                $comando->bindParam(':modifica', $ppro_id, \PDO::PARAM_INT);
            }
            $comando->bindParam(':est_id', $est_id, \PDO::PARAM_INT);
            $comando->bindParam(':eppa_estado_pago', $eppa_estado_pago, \PDO::PARAM_STR);
            $comando->bindParam(':eppa_usuario_modifica', $usu_id, \PDO::PARAM_INT);
            $comando->bindParam(":fecha", $fecha, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.estudiante_periodo_pago');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function Obtiene información de distributivo todos los estudiantes de posgrado.
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDistributivoxEstudiantepos($arrFiltro = array(), $reporte) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(p.per_pri_nombre like :search OR ";
            $str_search .= "p.per_seg_nombre like :search OR ";
            $str_search .= "p.per_pri_apellido like :search OR ";
            $str_search .= "p.per_seg_apellido like :search OR ";
            $str_search .= "p.per_cedula like :search) AND ";

            if (!empty($arrFiltro['profesor'])) {
                $str_search .= "(pe.per_pri_nombre like :profesor OR ";
                $str_search .= "pe.per_seg_nombre like :profesor OR ";
                $str_search .= "pe.per_pri_apellido like :profesor OR ";
                $str_search .= "pe.per_seg_apellido like :profesor) AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "a.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "a.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['promocion'] != "" && $arrFiltro['promocion'] > 0) {
                $str_search .= "a.ppro_id = :promocion AND ";
            }
            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $str_search .= "a.asi_id = :asignatura AND ";
            }
            if ($arrFiltro['estado_pago'] != '-1') {
                if ($arrFiltro['estado_pago'] == '0') {
                    $str_search .= " (m.eppa_estado_pago = :estado_pago OR m.eppa_estado_pago IS NULL) AND ";
                } else {
                    $str_search .= " m.eppa_estado_pago = :estado_pago AND ";
                }
            }
            if ($arrFiltro['paralelo'] != "" && $arrFiltro['paralelo'] > 0) {
                $str_search .= "a.daca_jornada = :paralelo AND ";
            }
        }
        $sql = "SELECT  h.est_id, 
                        d.uaca_nombre as unidad, 
                        e.mod_nombre as modalidad,
                        p.per_cedula as identificacion, 
                        concat(p.per_pri_nombre, ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as estudiante,
                        pp.ppro_codigo as promocion,                        
                        z.asi_nombre as asignatura,
                        case 
                             when m.eppa_estado_pago = '0' then 'No Autorizado' 
                             when m.eppa_estado_pago = '1' then 'Autorizado'
                             else 'No Autorizado'
                             end as 'pago',                           
                        ifnull(DATE_FORMAT(m.eppa_fecha_registro, '%Y-%m-%d'), ' ') as fecha_pago 
                FROM " . $con->dbname . ".distributivo_academico a inner join " . $con->dbname . ".profesor b
                    on b.pro_id = a.pro_id 
                    inner join " . $con1->dbname . ".persona c on c.per_id = b.per_id
                    inner join " . $con1->dbname . ".persona pe on pe.per_id = b.per_id
                    inner join " . $con->dbname . ".unidad_academica d on d.uaca_id = a.uaca_id
                    inner join " . $con->dbname . ".modalidad e on e.mod_id = a.mod_id
                    inner join " . $con->dbname . ".promocion_programa pp on pp.ppro_id = a.ppro_id
                    inner join " . $con->dbname . ".distributivo_academico_estudiante g on g.daca_id = a.daca_id
                    inner join " . $con->dbname . ".estudiante h on h.est_id = g.est_id
                    inner join " . $con1->dbname . ".persona p on p.per_id = h.per_id                  
                    inner join " . $con->dbname . ".asignatura z on a.asi_id = z.asi_id
                    left join " . $con->dbname . ".estudiante_periodo_pago m on (m.est_id = g.est_id)
                WHERE $str_search  a.uaca_id = 2                 
                    and a.daca_estado = :estado
                    and a.daca_estado_logico = :estado
                    and g.daes_estado = :estado
                    and g.daes_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);

            if (!empty($arrFiltro['profesor'])) {
                $search_profe = "%" . $arrFiltro["profesor"] . "%";
                $comando->bindParam(":profesor", $search_profe, \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $search_mod = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $search_mod, \PDO::PARAM_INT);
            }
            if ($arrFiltro['promocion'] != "" && $arrFiltro['promocion'] > 0) {
                $search_per = $arrFiltro["promocion"];
                $comando->bindParam(":promocion", $search_per, \PDO::PARAM_INT);
            }
            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $search_asi = $arrFiltro["asignatura"];
                $comando->bindParam(":asignatura", $search_asi, \PDO::PARAM_INT);
            }
            if ($arrFiltro['estado_pago'] != '-1') {
                $comando->bindParam(":estado_pago", $arrFiltro['estado_pago'], \PDO::PARAM_STR);
            }
            if ($arrFiltro['paralelo'] != "" && $arrFiltro['paralelo'] > 0) {
                $search_jor = $arrFiltro["paralelo"];
                $comando->bindParam(":paralelo", $search_jor, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);
        if ($reporte == 1) {
            return $dataProvider;
        } else {
            return $resultData;
        }
    }

}
