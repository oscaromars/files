<?php

namespace app\modules\academico\models;

use yii\data\ArrayDataProvider;
use app\modules\academico\models\PeriodoAcademico;
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
 * @property string $dis_fecha_modificacionconsultarAsiganturaxuniymoda
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
     * Function Obtiene informaci??n de distributivo.
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
     * Function Obtiene informaci??n de distributivo para excel.
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
     * Function Obtiene informaci??n de carga horaria.
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
     * Function Obtiene informaci??n de carga horaria.
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
     * Function Obtiene informaci??n de distributivo por profesor.
     * Consultar por tipo de pago: pago total
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @modifier Julio Lopez <analistadesarrollo03@uteg.edu.ec>;
     * Date of modification: 11 mayo 2022
     * @param
     * @return
     */
    public function consultarDistributivoxProfesor($arrFiltro = array(), $id_profesor, $reporte) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        $estado = 1;

        /*if (isset($arrFiltro) && count($arrFiltro) > 0) {
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
            //if ($arrFiltro['estado'] == "C" or $arrFiltro['estado'] == "N") {
            //    $str_search .= "ifnull(m.ccar_estado_cancela,'N') = :estpago AND ";
            //}
            if ($arrFiltro['estado_pago'] == "0" or $arrFiltro['estado_pago'] == "1") {            
                if ($arrFiltro['estado_pago'] == "0") {            
                $str_search .= " ((m.ccar_estado_cancela is null OR m.ccar_estado_cancela = :estado_pago) AND NOW() > m.ccar_fecha_vencepago ) AND ";
            }else{
                $str_search .= " (m.ccar_estado_cancela = :estado_pago OR NOW() < m.ccar_fecha_vencepago) ";
            } 
        } 
            if ($arrFiltro['jornada'] != "" && $arrFiltro['jornada'] > 0) {
                $str_search .= "a.daca_jornada = :jornada AND ";
            }
        }else{
          $mod_paca        = new PeriodoAcademico(); 
          $paca_actual_id  = $mod_paca->getPeriodoAcademicoActual();
          $str_search      = "a.paca_id = ".$paca_actual_id['id']." AND ";
        }*/

        // VERSION ORIGINAL.
        //COMENTADO 10 MAYO 2022
        /*$sql = "SELECT  distinct d.uaca_nombre as unidad, e.mod_nombre as modalidad,
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
                                            CASE WHEN mi.ccar_estado_cancela = 'C' or mi.ccar_estado_cancela = 'N'
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
                    left join " . $con2->dbname . ".carga_cartera m on (m.est_id = g.est_id -- and m.paca_id = f.paca_id 
                    )
                WHERE $str_search c.per_id = :profesor
                    and f.paca_activo = 'A'
                    and a.daca_estado = :estado
                    and a.daca_estado_logico = :estado
                    and g.daes_estado = :estado
                    and g.daes_estado_logico = :estado 
                order by p.per_pri_apellido, p.per_seg_apellido";*/


        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_seg_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "daca.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "daca.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "daca.paca_id = :periodo AND ";
            }
            /*if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $str_search .= "daca.asi_id = :asignatura AND ";
            }*/
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
                //$str_search .= " ( ( (ccar.ccar_estado_cancela is null OR ccar.ccar_estado_cancela = :estado_pago) AND NOW() > ccar.ccar_fecha_vencepago ) ) AND ";
                $str_search_CD = " ( ( (ccar.ccar_estado_cancela is null OR ccar.ccar_estado_cancela = :estado_pago) AND NOW() > ccar.ccar_fecha_vencepago ) ) AND ";

                $str_search_PT = "(SELECT COUNT(pfes_comp.pfes_concepto)
                                 FROM db_facturacion.pagos_factura_estudiante as pfes_comp, db_facturacion.detalle_pagos_factura as dpfa2 
                                 WHERE pfes_comp.est_id = est.est_id AND
                                 pfes_comp.pfes_id = dpfa2.pfes_id AND
                                 pfes_comp.pfes_concepto = 'PT' AND
                                 date(pfes_comp.pfes_fecha_creacion) = date(rpm.rpm_fecha_creacion)) = 0 AND ";

            }else{
                //$str_search .= " ( ( ccar.ccar_estado_cancela = :estado_pago OR NOW() < ccar.ccar_fecha_vencepago) ) AND ";

                $str_search_CD = " ( ( ccar.ccar_estado_cancela = :estado_pago OR NOW() < ccar.ccar_fecha_vencepago) ) AND "; 

                $str_search_PT = "(SELECT COUNT(pfes_comp.pfes_concepto)
                                 FROM db_facturacion.pagos_factura_estudiante as pfes_comp, db_facturacion.detalle_pagos_factura as dpfa2 
                                 WHERE pfes_comp.est_id = est.est_id AND
                                 pfes_comp.pfes_id = dpfa2.pfes_id AND
                                 pfes_comp.pfes_concepto = 'PT' AND
                                 date(pfes_comp.pfes_fecha_creacion) = date(rpm.rpm_fecha_creacion)) = 1 AND ";
            }
        }
            /**************************************************************  **/
            if ($arrFiltro['jornada'] != "" && $arrFiltro['jornada'] > 0) {
                $str_search .= "daca.daca_jornada = :jornada AND ";
            }
        }else{
          $mod_paca        = new PeriodoAcademico();
          $paca_actual_id  = $mod_paca->getPeriodoAcademicoActual();
          $str_search      = "daca.paca_id = ".$paca_actual_id[0]['id']." AND /*a.mod_id = 0 AND*/ ";
        }       

        // VERSION 2 MODIFICADA
                    $sql ="
                        SELECT DISTINCT
                        est.est_id,
                        uaca.uaca_nombre as unidad,
                        moda.mod_nombre as modalidad,
                        per.per_cedula as identificacion,
                        concat(per.per_pri_nombre, ' ', per.per_pri_apellido, ' ', ifnull(per.per_seg_apellido,'')) as estudiante,
                        concat(saca.saca_nombre, '-',baca.baca_nombre,'-',baca.baca_anio) as periodo,
                        asi.asi_nombre as asignatura,

                        CASE
                            -- WHEN rpm.rpm_tipo_pago=2 THEN 'Autorizado'
                            WHEN rpm.rpm_tipo_pago=3 THEN
                            CASE 
                                WHEN
                                    (SELECT count(ccar_estado_cancela) FROM " . $con2->dbname . ".carga_cartera ccar1
                                    WHERE ccar1.ccar_estado_cancela='N' AND ccar1.ccar_id=ccar.ccar_id ) =  0
                                    THEN 'Autorizado'
                                WHEN
                                    (SELECT count(ccar_estado_cancela) FROM " . $con2->dbname . ".carga_cartera ccar1
                                    WHERE ccar1.ccar_estado_cancela='N' AND ccar1.ccar_id=ccar.ccar_id ) >  0
                                    THEN 'No Autorizado'
                            END
                        END pago
                        FROM " . $con->dbname . ".distributivo_academico_estudiante daes
                        INNER JOIN " . $con->dbname . ".estudiante est on daes.est_id = est.est_id
                        INNER JOIN " . $con1->dbname . ".persona per on per.per_id = est.per_id
                        INNER JOIN " . $con->dbname . ".planificacion_estudiante pes on pes.per_id = est.per_id
                        INNER JOIN " . $con->dbname . ".planificacion pla on pla.pla_id = pes.pla_id
                        INNER JOIN " . $con->dbname . ".semestre_academico saca on saca.saca_id =  pla.saca_id
                        INNER JOIN " . $con->dbname . ".periodo_academico paca on  paca.saca_id = saca.saca_id
                        INNER JOIN " . $con->dbname . ".bloque_academico baca on baca.baca_id = paca.baca_id
                        -- INNER JOIN " . $con->dbname . ".modalidad moda on moda.mod_id = pla.mod_id
                        INNER JOIN " . $con->dbname . ".registro_online ron on ron.per_id = est.per_id AND
                        ron.pes_id = pes.pes_id
                        INNER JOIN " . $con->dbname . ".registro_online_item roi on roi.ron_id = ron.ron_id AND baca.baca_nombre = roi.roi_bloque
                        INNER JOIN " . $con->dbname . ".malla_academica_detalle made on made.made_codigo_asignatura = roi.roi_materia_cod
                        INNER JOIN " . $con->dbname . ".asignatura asi on asi.asi_id = made.asi_id
                        INNER JOIN " . $con->dbname . ".registro_pago_matricula rpm on rpm.ron_id = ron.ron_id
                        INNER JOIN " . $con->dbname . ".registro_online_cuota roc on roc.ron_id = ron.ron_id
                        INNER JOIN " . $con->dbname . ".registro_adicional_materias as rama on rpm.rpm_id = rama.rpm_id  
                        and ( roi.roi_id = rama.roi_id_1 or roi.roi_id = rama.roi_id_2 or roi.roi_id = rama.roi_id_3 or roi.roi_id = rama.roi_id_4
                        or roi.roi_id = rama.roi_id_5 or roi.roi_id = rama.roi_id_6 or roi.roi_id = rama.roi_id_7 or roi.roi_id = rama.roi_id_8
                        )
                        INNER JOIN " . $con->dbname . ".distributivo_academico daca on daca.daca_id =  daes.daca_id
                        AND daca.paca_id = paca.paca_id AND asi.asi_id = daca.asi_id
                        INNER JOIN " . $con->dbname . ".modalidad moda on moda.mod_id = daca.mod_id
                        INNER JOIN " . $con->dbname . ".profesor b on b.pro_id = daca.pro_id
                        INNER JOIN " . $con1->dbname . ".persona as t on b.per_id = t.per_id
                        INNER JOIN " . $con->dbname . ".distributivo_cabecera dcab on dcab.dcab_id = daca.dcab_id
                        INNER JOIN " . $con->dbname . ".materia_paralelo_periodo mpp on mpp.mpp_id = daca.mpp_id
                        INNER JOIN " . $con2->dbname . ".carga_cartera ccar on ccar.ccar_fecha_vencepago=roc.roc_vencimiento
                        AND est.est_id = ccar.est_id
                        INNER JOIN " . $con->dbname . ".unidad_academica uaca on uaca.uaca_id =  daca.uaca_id
                        WHERE
                        $str_search
                        t.per_id = :profesor AND
                        rpm.rpm_tipo_pago = 3 AND
                        ccar.ccar_fecha_vencepago<=now() AND
                        dcab.dcab_estado_revision = 2 AND
                        daca.daca_estado = :estado AND
                        daca.daca_estado_logico =  :estado AND
                        daes.daes_estado =  :estado AND
                        daes.daes_estado_logico =  :estado 

                        UNION


                        SELECT DISTINCT
                        est.est_id,
                        uaca.uaca_nombre as unidad,
                        moda.mod_nombre as modalidad,
                        per.per_cedula as identificacion,
                        concat(per.per_pri_nombre, ' ', per.per_pri_apellido, ' ', ifnull(per.per_seg_apellido,'')) as estudiante,
                        concat(saca.saca_nombre, '-',baca.baca_nombre,'-',baca.baca_anio) as periodo,
                        asi.asi_nombre as asignatura,
                        CASE
                            WHEN
                                (SELECT COUNT(pfes_comp.pfes_concepto)
                                 FROM db_facturacion.pagos_factura_estudiante as pfes_comp, db_facturacion.detalle_pagos_factura as dpfa2 
                                 WHERE pfes_comp.est_id = est.est_id AND
                                 pfes_comp.pfes_id = dpfa2.pfes_id AND
                                 pfes_comp.pfes_concepto = 'PT' AND
                                 date(pfes_comp.pfes_fecha_creacion) = date(rpm.rpm_fecha_creacion)) > 0
                            THEN 'Autorizado'
                            WHEN
                                (SELECT COUNT(pfes_comp.pfes_concepto)
                                 FROM db_facturacion.pagos_factura_estudiante as pfes_comp, db_facturacion.detalle_pagos_factura as dpfa2 
                                 WHERE pfes_comp.est_id = est.est_id AND
                                 pfes_comp.pfes_id = dpfa2.pfes_id AND
                                 pfes_comp.pfes_concepto = 'PT' AND
                                 date(pfes_comp.pfes_fecha_creacion) = date(rpm.rpm_fecha_creacion)) = 0
                            THEN 'No Autorizado'
                        END as pago


                        FROM " . $con->dbname . ".distributivo_academico_estudiante daes
                        INNER JOIN " . $con->dbname . ".estudiante est on daes.est_id = est.est_id
                        INNER JOIN " . $con1->dbname . ".persona per on per.per_id = est.per_id
                        INNER JOIN " . $con->dbname . ".planificacion_estudiante pes on pes.per_id = est.per_id
                        INNER JOIN " . $con->dbname . ".planificacion pla on pla.pla_id = pes.pla_id
                        INNER JOIN " . $con->dbname . ".semestre_academico saca on saca.saca_id =  pla.saca_id
                        INNER JOIN " . $con->dbname . ".periodo_academico paca on  paca.saca_id = saca.saca_id
                        INNER JOIN " . $con->dbname . ".bloque_academico baca on baca.baca_id = paca.baca_id
                        -- INNER JOIN " . $con->dbname . ".modalidad moda on moda.mod_id = pla.mod_id
                        INNER JOIN " . $con->dbname . ".registro_online ron on ron.per_id = est.per_id AND
                        ron.pes_id = pes.pes_id
                        INNER JOIN " . $con->dbname . ".registro_online_item roi on roi.ron_id = ron.ron_id AND baca.baca_nombre = roi.roi_bloque
                        INNER JOIN " . $con->dbname . ".malla_academica_detalle made on made.made_codigo_asignatura = roi.roi_materia_cod
                        INNER JOIN " . $con->dbname . ".asignatura asi on asi.asi_id = made.asi_id
                        INNER JOIN " . $con->dbname . ".registro_pago_matricula rpm on rpm.ron_id = ron.ron_id
                        INNER JOIN " . $con->dbname . ".registro_online_cuota roc on roc.ron_id = ron.ron_id
                        INNER JOIN " . $con->dbname . ".registro_adicional_materias as rama on rpm.rpm_id = rama.rpm_id  
                        and ( roi.roi_id = rama.roi_id_1 or roi.roi_id = rama.roi_id_2 or roi.roi_id = rama.roi_id_3 or roi.roi_id = rama.roi_id_4
                        or roi.roi_id = rama.roi_id_5 or roi.roi_id = rama.roi_id_6 or roi.roi_id = rama.roi_id_7 or roi.roi_id = rama.roi_id_8
                        )
                        INNER JOIN " . $con->dbname . ".distributivo_academico daca on daca.daca_id =  daes.daca_id
                        AND daca.paca_id = paca.paca_id AND asi.asi_id = daca.asi_id
                        INNER JOIN " . $con->dbname . ".modalidad moda on moda.mod_id = daca.mod_id
                        INNER JOIN " . $con->dbname . ".profesor b on b.pro_id = daca.pro_id
                        INNER JOIN " . $con1->dbname . ".persona as t on b.per_id = t.per_id
                        INNER JOIN " . $con->dbname . ".distributivo_cabecera dcab on dcab.dcab_id = daca.dcab_id
                        INNER JOIN " . $con->dbname . ".materia_paralelo_periodo mpp on mpp.mpp_id = daca.mpp_id
                        -- INNER JOIN " . $con2->dbname . ".carga_cartera ccar on ccar.ccar_fecha_vencepago=roc.roc_vencimiento
                        -- AND est.est_id = ccar.est_id
                        INNER JOIN " . $con->dbname . ".unidad_academica uaca on uaca.uaca_id =  daca.uaca_id

                        INNER JOIN " . $con2->dbname . ".pagos_factura_estudiante as pfes on est.est_id = pfes.est_id
                        INNER JOIN " . $con2->dbname . ".detalle_pagos_factura as dpfa on pfes.pfes_id = dpfa.pfes_id
                        and pfes_concepto in('MA','PT')
                            and date(pfes.pfes_fecha_creacion) = date(rpm.rpm_fecha_creacion)

                        WHERE
                        $str_search
                        $str_search_PT
                        t.per_id = :profesor AND
                        rpm.rpm_tipo_pago = 2 AND
                        -- ccar.ccar_fecha_vencepago<=now() AND
                        dcab.dcab_estado_revision = 2 AND
                        daca.daca_estado = :estado AND
                        daca.daca_estado_logico =  :estado AND
                        daes.daes_estado =  :estado AND
                        daes.daes_estado_logico =  :estado 


                        ";

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
           /* if ($arrFiltro['estado'] == "C" or $arrFiltro['estado'] == "N") {
                $search_estado = $arrFiltro["estado"];
                $comando->bindParam(":estpago", $search_estado, \PDO::PARAM_STR);
            } */

            //if ($arrFiltro['estado_pago'] != '2') { // JLC. comentado 11 abril 2022.
            if ($arrFiltro['estado_pago'] != '-1') {
                if ($arrFiltro['estado_pago'] == '0') {
                    $filestado = 'N';
                } else {
                    $filestado = 'C';
              } 
                $comando->bindParam(":estado_pago", $filestado, \PDO::PARAM_STR);
            }

            if ($arrFiltro['jornada'] != "" && $arrFiltro['jornada'] > 0) {
                $search_jor = $arrFiltro["jornada"];
                $comando->bindParam(":jornada", $search_jor, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();

        $resultData2 = array();

        foreach ($resultData as $key => $value) {
            /*$band = 1;

            if(empty($resultData2))
              $resultData2[] = $value;

            foreach ($resultData2 as $key2 => $value2) {

                if ($resultData2[$key2]['est_id'] == $value['est_id']) {
                    if($resultData2[$key2]['asignatura'] == $value['asignatura']){
                        $band = 0;
                    }
                }
            }

            if($band == 1)*/
                $resultData2[] = $value;
        }//foreach
        \app\models\Utilities::putMessageLogFile( 'consultarDistributivoxProfesor: '.$comando->getRawSql());

        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData2,
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
            return $resultData2;
        }
    }

    /**
     * Function Obtiene informaci??n de distributivo todos los estudiantes.
     * Consultar por tipo de pago: pago total
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @modifier Julio Lopez <analistadesarrollo03@uteg.edu.ec>;
     * Date of modification: 10 mayo 2022
     * @param
     * @return
     */
    public function consultarDistributivoxEstudiante($arrFiltro = array(), $reporte) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_seg_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";

            if (!empty($arrFiltro['profesor'])) {
                $str_search .= "(per.per_pri_nombre like :profesor OR ";
                $str_search .= "per.per_seg_nombre like :profesor OR ";
                $str_search .= "per.per_pri_apellido like :profesor OR ";
                $str_search .= "per.per_seg_apellido like :profesor) AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "daca.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "daca.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "daca.paca_id = :periodo AND ";
            }
            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $str_search .= "daca.asi_id = :asignatura AND ";
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
                //$str_search .= " ( (  (ccar.ccar_estado_cancela is null OR ccar.ccar_estado_cancela = :estado_pago) AND NOW() > ccar.ccar_fecha_vencepago) ) AND ";
                $str_search_CD = " ( ( (ccar.ccar_estado_cancela is null OR ccar.ccar_estado_cancela = :estado_pago) AND NOW() > ccar.ccar_fecha_vencepago) ) AND ";

                $str_search_PT = "(SELECT COUNT(pfes_comp.pfes_concepto)
                                 FROM db_facturacion.pagos_factura_estudiante as pfes_comp, db_facturacion.detalle_pagos_factura as dpfa2 
                                 WHERE pfes_comp.est_id = est.est_id AND
                                 pfes_comp.pfes_id = dpfa2.pfes_id AND
                                 pfes_comp.pfes_concepto = 'PT' AND
                                 date(pfes_comp.pfes_fecha_creacion) = date(rpm.rpm_fecha_creacion)) = 0 AND ";
                
            }else{
                // $str_search .= " ( ( ccar.ccar_estado_cancela = :estado_pago OR NOW() < ccar.ccar_fecha_vencepago) ) AND ";
                $str_search_CD = " ( ( ccar.ccar_estado_cancela = :estado_pago OR NOW() < ccar.ccar_fecha_vencepago) ) AND ";  


                $str_search_PT = "(SELECT COUNT(pfes_comp.pfes_concepto)
                                 FROM db_facturacion.pagos_factura_estudiante as pfes_comp, db_facturacion.detalle_pagos_factura as dpfa2 
                                 WHERE pfes_comp.est_id = est.est_id AND
                                 pfes_comp.pfes_id = dpfa2.pfes_id AND
                                 pfes_comp.pfes_concepto = 'PT' AND
                                 date(pfes_comp.pfes_fecha_creacion) = date(rpm.rpm_fecha_creacion)) = 1 AND ";

            }
        }
            /**************************************************************  **/
            if ($arrFiltro['jornada'] != "" && $arrFiltro['jornada'] > 0) {
                $str_search .= "daca.daca_jornada = :jornada AND ";
            }
        }else{
          $mod_paca        = new PeriodoAcademico();
          $paca_actual_id  = $mod_paca->getPeriodoAcademicoActual();
          $str_search      = "daca.paca_id = ".$paca_actual_id[0]['id']." AND /*a.mod_id = 0 AND*/ ";
        }
    // VERSION ORIGINAL
    /* $sql =  "SELECT  distinct h.est_id,
                        d.uaca_nombre as unidad,
                        e.mod_nombre as modalidad,
                        p.per_cedula as identificacion,
                        concat(p.per_pri_nombre, ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as estudiante,
                        concat(saca_nombre, '-', baca_nombre,'-',baca_anio) as periodo,
                        z.asi_nombre as asignatura,
                        case
                                when m.ccar_fecha_vencepago <= NOW() then  ifnull((SELECT
                                            CASE WHEN mi.ccar_estado_cancela =  'C'
                                            THEN 'Autorizado'
                                            ELSE 'No Autorizado' END AS pago
                                            FROM db_facturacion.carga_cartera mi
                                            WHERE mi.est_id =  g.est_id and mi.ccar_fecha_vencepago <= NOW()
                                            ORDER BY mi.ccar_fecha_vencepago desc
                                            LIMIT 1),'No Autorizado')
                                when m.ccar_fecha_vencepago >= NOW() then ifnull((SELECT
                                            CASE WHEN mi.ccar_estado_cancela =  'N' or mi.ccar_estado_cancela =  'C'
                                            THEN 'Autorizado'
                                            ELSE 'No Autorizado' END AS pago
                                            FROM db_facturacion.carga_cartera mi
                                            WHERE mi.est_id =  g.est_id and mi.ccar_fecha_vencepago >= NOW()
                                            ORDER BY mi.ccar_fecha_vencepago asc
                                            LIMIT 1),'No Autorizado')
                                else 'No Autorizado'
                                end as pago
                FROM " . $con->dbname . ".distributivo_academico a inner join " . $con->dbname . ".profesor b
                    on b.pro_id =  a.pro_id
                    inner join " . $con1->dbname . ".persona c on c.per_id =  b.per_id
                    inner join " . $con1->dbname . ".persona pe on pe.per_id =  b.per_id
                    inner join " . $con->dbname . ".unidad_academica d on d.uaca_id =  a.uaca_id
                    inner join " . $con->dbname . ".modalidad e on e.mod_id =  a.mod_id
                    inner join " . $con->dbname . ".periodo_academico f on f.paca_id =  a.paca_id
                    inner join " . $con->dbname . ".distributivo_academico_estudiante g on g.daca_id =  a.daca_id
                    inner join " . $con->dbname . ".estudiante h on h.est_id =  g.est_id
                    inner join " . $con1->dbname . ".persona p on p.per_id =  h.per_id
                    inner join " . $con->dbname . ".semestre_academico s on s.saca_id =  f.saca_id
                    inner join " . $con->dbname . ".bloque_academico t on t.baca_id =  f.baca_id
                    inner join " . $con->dbname . ".asignatura z on a.asi_id =  z.asi_id
                    left join " . $con2->dbname . ".carga_cartera m on (m.est_id =  g.est_id)
                    WHERE $str_search a.daca_estado =  :estado
                    and a.daca_estado_logico =  :estado
                    and g.daes_estado =  :estado
                    and g.daes_estado_logico =  :estado";*/

        // VERSION 1 MODIFICADA
                    /*$sql =  "SELECT DISTINCT
                    est.est_id,
                    uaca.uaca_nombre as unidad,
                    moda.mod_nombre as modalidad,
                    per.per_cedula as identificacion,
                    concat(per.per_pri_nombre, ' ', per.per_pri_apellido, ' ', IFNULL(per.per_seg_apellido,'')) as estudiante,
                    concat(saca.saca_nombre, '-',baca.baca_nombre,'-',baca.baca_anio) as periodo,
                    asi.asi_nombre as asignatura,
                    CASE
                        WHEN rpm.rpm_tipo_pago = 2 THEN 'Autorizado'
                        WHEN rpm.rpm_tipo_pago = 3 THEN
                        CASE WHEN
                            (SELECT count(ccar_estado_cancela) FROM " . $con2->dbname . ".carga_cartera ccar1
                             WHERE ccar1.ccar_estado_cancela='N' AND ccar1.ccar_id=ccar.ccar_id ) =  0
                             THEN 'Autorizado'
                        ELSE 'No Autorizado'
                        END
                    END pago
                    FROM " . $con->dbname . ".distributivo_academico_estudiante daes
                    INNER JOIN " . $con->dbname . ".distributivo_academico daca ON daca.daca_id =  daes.daca_id
                    INNER JOIN " . $con->dbname . ".distributivo_cabecera dcab ON dcab.dcab_id = daca.dcab_id
                    INNER JOIN " . $con->dbname . ".asignatura asi ON asi.asi_id = daca.asi_id
                    INNER JOIN " . $con->dbname . ".materia_paralelo_periodo mpp ON mpp.mpp_id = daca.mpp_id
                    INNER JOIN " . $con->dbname . ".estudiante est ON daes.est_id = est.est_id
                    INNER JOIN " . $con1->dbname . ".persona per ON per.per_id = est.per_id
                    INNER JOIN " . $con->dbname . ".planificacion_estudiante pes ON pes.per_id=est.per_id
                    INNER JOIN " . $con->dbname . ".planificacion pla ON pla.pla_id = pes.pla_id
                    INNER JOIN " . $con->dbname . ".semestre_academico saca ON saca.saca_id =  pla.saca_id
                    INNER JOIN " . $con->dbname . ".periodo_academico paca ON  paca.saca_id=saca.saca_id
                    INNER JOIN " . $con->dbname . ".bloque_academico baca ON baca.baca_id = paca.baca_id
                    INNER JOIN " . $con->dbname . ".modalidad moda ON moda.mod_id = pla.mod_id
                    INNER JOIN " . $con->dbname . ".registro_online ron ON ron.per_id=est.per_id AND
                    ron.pes_id = pes.pes_id
                    INNER JOIN " . $con->dbname . ".registro_pago_matricula rpm ON rpm.ron_id=ron.ron_id
                    INNER JOIN " . $con->dbname . ".registro_online_cuota roc ON roc.ron_id =  ron.ron_id
                    INNER JOIN " . $con2->dbname . ".carga_cartera ccar ON ccar.ccar_fecha_vencepago=roc.roc_vencimiento
                    AND est.est_id = ccar.est_id
                    INNER JOIN " . $con->dbname . ".unidad_academica uaca on uaca.uaca_id =  daca.uaca_id
                    WHERE  $str_search
                           ccar.ccar_fecha_vencepago<=now() AND
                           dcab.dcab_estado_revision = 2 AND
                           daca.daca_estado =  :estado and
                           daca.daca_estado_logico =  :estado and
                           daes.daes_estado =  :estado AND
                           daes.daes_estado_logico =  :estado ";*/
        // VERSION 2 MODIFICADA
                    $sql =  "SELECT DISTINCT
                        est.est_id,
                        uaca.uaca_nombre as unidad,
                        moda.mod_nombre as modalidad,
                        per.per_cedula as identificacion,
                        concat(per.per_pri_nombre, ' ', per.per_pri_apellido, ' ', ifnull(per.per_seg_apellido,'')) as estudiante,
                        concat(saca.saca_nombre, '-',baca.baca_nombre,'-',baca.baca_anio) as periodo,
                        asi.asi_nombre as asignatura,

                        CASE
                           -- WHEN rpm.rpm_tipo_pago=2 THEN 'Autorizado'
                            WHEN rpm.rpm_tipo_pago=3 THEN
                            CASE 
                                WHEN
                                    (SELECT count(ccar_estado_cancela) FROM " . $con2->dbname . ".carga_cartera ccar1
                                    WHERE ccar1.ccar_estado_cancela='N' AND ccar1.ccar_id=ccar.ccar_id ) =  0
                                    THEN 'Autorizado'
                                WHEN
                                    (SELECT count(ccar_estado_cancela) FROM " . $con2->dbname . ".carga_cartera ccar1
                                    WHERE ccar1.ccar_estado_cancela='N' AND ccar1.ccar_id=ccar.ccar_id ) >  0
                                    THEN 'No Autorizado'
                            END
                        END pago
                        FROM " . $con->dbname . ".distributivo_academico_estudiante daes
                        INNER JOIN " . $con->dbname . ".estudiante est on daes.est_id = est.est_id
                        INNER JOIN " . $con1->dbname . ".persona per on per.per_id = est.per_id
                        INNER JOIN " . $con->dbname . ".planificacion_estudiante pes on pes.per_id = est.per_id
                        INNER JOIN " . $con->dbname . ".planificacion pla on pla.pla_id = pes.pla_id
                        INNER JOIN " . $con->dbname . ".semestre_academico saca on saca.saca_id =  pla.saca_id
                        INNER JOIN " . $con->dbname . ".periodo_academico paca on  paca.saca_id = saca.saca_id
                        INNER JOIN " . $con->dbname . ".bloque_academico baca on baca.baca_id = paca.baca_id
                        -- INNER JOIN " . $con->dbname . ".modalidad moda on moda.mod_id = pla.mod_id
                        INNER JOIN " . $con->dbname . ".registro_online ron on ron.per_id = est.per_id AND
                        ron.pes_id = pes.pes_id
                        INNER JOIN " . $con->dbname . ".registro_online_item roi on roi.ron_id = ron.ron_id AND baca.baca_nombre = roi.roi_bloque
                        INNER JOIN " . $con->dbname . ".malla_academica_detalle made on made.made_codigo_asignatura = roi.roi_materia_cod
                        INNER JOIN " . $con->dbname . ".asignatura asi on asi.asi_id = made.asi_id
                        INNER JOIN " . $con->dbname . ".registro_pago_matricula rpm on rpm.ron_id = ron.ron_id
                        INNER JOIN " . $con->dbname . ".registro_online_cuota roc on roc.ron_id = ron.ron_id
                        INNER JOIN " . $con->dbname . ".registro_adicional_materias as rama on rpm.rpm_id = rama.rpm_id  
                        AND ( roi.roi_id = rama.roi_id_1 or roi.roi_id = rama.roi_id_2 or roi.roi_id = rama.roi_id_3 or roi.roi_id = rama.roi_id_4
                        or roi.roi_id = rama.roi_id_5 or roi.roi_id = rama.roi_id_6 or roi.roi_id = rama.roi_id_7 or roi.roi_id = rama.roi_id_8
                        )
                        INNER JOIN " . $con->dbname . ".distributivo_academico daca on daca.daca_id =  daes.daca_id
                        AND daca.paca_id = paca.paca_id AND asi.asi_id = daca.asi_id
                        INNER JOIN " . $con->dbname . ".modalidad moda on moda.mod_id = daca.mod_id
                        INNER JOIN " . $con->dbname . ".distributivo_cabecera dcab on dcab.dcab_id = daca.dcab_id
                        INNER JOIN " . $con->dbname . ".materia_paralelo_periodo mpp on mpp.mpp_id = daca.mpp_id
                        INNER JOIN " . $con2->dbname . ".carga_cartera ccar on ccar.ccar_fecha_vencepago=roc.roc_vencimiento
                        AND est.est_id = ccar.est_id
                        INNER JOIN " . $con->dbname . ".unidad_academica uaca on uaca.uaca_id =  daca.uaca_id
                        WHERE
                        $str_search
                        $str_search_CD
                        rpm.rpm_tipo_pago=3 and
                        ccar.ccar_fecha_vencepago<=now() AND
                        dcab.dcab_estado_revision = 2 AND
                        daca.daca_estado =  :estado AND
                        daca.daca_estado_logico =  :estado AND
                        daes.daes_estado =  :estado AND
                        daes.daes_estado_logico =  :estado

                        UNION

                        SELECT DISTINCT
                        est.est_id,
                        uaca.uaca_nombre as unidad,
                        moda.mod_nombre as modalidad,
                        per.per_cedula as identificacion,
                        concat(per.per_pri_nombre, ' ', per.per_pri_apellido, ' ', ifnull(per.per_seg_apellido,'')) as estudiante,
                        concat(saca.saca_nombre, '-',baca.baca_nombre,'-',baca.baca_anio) as periodo,
                        asi.asi_nombre as asignatura,
                        CASE
                            WHEN
                                (SELECT COUNT(pfes_comp.pfes_concepto)
                                 FROM db_facturacion.pagos_factura_estudiante as pfes_comp, db_facturacion.detalle_pagos_factura as dpfa2 
                                 WHERE pfes_comp.est_id = est.est_id AND
                                 pfes_comp.pfes_id = dpfa2.pfes_id AND
                                 pfes_comp.pfes_concepto = 'PT' AND
                                 date(pfes_comp.pfes_fecha_creacion) = date(rpm.rpm_fecha_creacion)) > 0
                            THEN 'Autorizado'
                            WHEN
                                (SELECT COUNT(pfes_comp.pfes_concepto)
                                 FROM db_facturacion.pagos_factura_estudiante as pfes_comp, db_facturacion.detalle_pagos_factura as dpfa2 
                                 WHERE pfes_comp.est_id = est.est_id AND
                                 pfes_comp.pfes_id = dpfa2.pfes_id AND
                                 pfes_comp.pfes_concepto = 'PT' AND
                                 date(pfes_comp.pfes_fecha_creacion) = date(rpm.rpm_fecha_creacion)) = 0
                            THEN 'No Autorizado'
                        END as pago
                        FROM " . $con->dbname . ".distributivo_academico_estudiante daes
                        INNER JOIN " . $con->dbname . ".estudiante est on daes.est_id = est.est_id
                        INNER JOIN " . $con1->dbname . ".persona per on per.per_id = est.per_id
                        INNER JOIN " . $con->dbname . ".planificacion_estudiante pes on pes.per_id = est.per_id
                        INNER JOIN " . $con->dbname . ".planificacion pla on pla.pla_id = pes.pla_id
                        INNER JOIN " . $con->dbname . ".semestre_academico saca on saca.saca_id =  pla.saca_id
                        INNER JOIN " . $con->dbname . ".periodo_academico paca on  paca.saca_id = saca.saca_id
                        INNER JOIN " . $con->dbname . ".bloque_academico baca on baca.baca_id = paca.baca_id
                        -- INNER JOIN " . $con->dbname . ".modalidad moda on moda.mod_id = pla.mod_id
                        INNER JOIN " . $con->dbname . ".registro_online ron on ron.per_id = est.per_id AND
                        ron.pes_id = pes.pes_id
                        INNER JOIN " . $con->dbname . ".registro_online_item roi on roi.ron_id = ron.ron_id AND baca.baca_nombre = roi.roi_bloque
                        INNER JOIN " . $con->dbname . ".malla_academica_detalle made on made.made_codigo_asignatura = roi.roi_materia_cod
                        INNER JOIN " . $con->dbname . ".asignatura asi on asi.asi_id = made.asi_id
                        INNER JOIN " . $con->dbname . ".registro_pago_matricula rpm on rpm.ron_id = ron.ron_id
                        INNER JOIN " . $con->dbname . ".registro_online_cuota roc on roc.ron_id = ron.ron_id
                        INNER JOIN " . $con->dbname . ".registro_adicional_materias as rama on rpm.rpm_id = rama.rpm_id  
                        AND ( roi.roi_id = rama.roi_id_1 or roi.roi_id = rama.roi_id_2 or roi.roi_id = rama.roi_id_3 or roi.roi_id = rama.roi_id_4
                        or roi.roi_id = rama.roi_id_5 or roi.roi_id = rama.roi_id_6 or roi.roi_id = rama.roi_id_7 or roi.roi_id = rama.roi_id_8
                        )
                        INNER JOIN " . $con->dbname . ".distributivo_academico daca on daca.daca_id =  daes.daca_id
                        AND daca.paca_id = paca.paca_id AND asi.asi_id = daca.asi_id
                        INNER JOIN " . $con->dbname . ".modalidad moda on moda.mod_id = daca.mod_id
                        INNER JOIN " . $con->dbname . ".distributivo_cabecera dcab on dcab.dcab_id = daca.dcab_id
                        INNER JOIN " . $con->dbname . ".materia_paralelo_periodo mpp on mpp.mpp_id = daca.mpp_id
                        INNER JOIN " . $con->dbname . ".unidad_academica uaca on uaca.uaca_id =  daca.uaca_id
                        INNER JOIN " . $con2->dbname . ".pagos_factura_estudiante as pfes on est.est_id = pfes.est_id
                        INNER JOIN " . $con2->dbname . ".detalle_pagos_factura as dpfa on pfes.pfes_id = dpfa.pfes_id
                        and pfes_concepto in('MA','PT')
                            and date(pfes.pfes_fecha_creacion) = date(rpm.rpm_fecha_creacion)
                        WHERE
                        $str_search
                        $str_search_PT
                        rpm.rpm_tipo_pago=2 AND
                        dcab.dcab_estado_revision = 2 AND
                        daca.daca_estado =  :estado AND
                        daca.daca_estado_logico =  :estado AND
                        daes.daes_estado =  :estado AND
                        daes.daes_estado_logico =  :estado";

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

        $resultData2 = array();

        foreach ($resultData as $key => $value) {
            //$band = 1;

            //if(empty($resultData2))
              //$resultData2[] = $value;

            /*foreach ($resultData2 as $key2 => $value2) {

                if ($resultData2[$key2]['est_id'] == $value['est_id']) {
                    if($resultData2[$key2]['asignatura'] == $value['asignatura']){
                        $band = 0;
                    }
                }
            }*/

           // if($band == 1)
                $resultData2[] = $value;
        }//foreach
        //\app\models\Utilities::putMessageLogFile(print_r($resultData2,true));
        \app\models\Utilities::putMessageLogFile( 'consultarDistributivoxEstudiante: '.$comando->getRawSql());


        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData2,
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
            return $resultData2;
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
                    FROM  " . $con->dbname . ".asignatura asig
                    inner join " . $con->dbname . ".distributivo_academico diac ON asig.asi_id = diac.asi_id
                    WHERE diac.uaca_id = :uaca_id 
                    and diac.mod_id =:mod_id
                    and diac.daca_estado_logico = :estado
                    and diac.daca_estado = :estado
                    and asig.asi_estado_logico = :estado
                    and asig.asi_estado = :estado
                    ORDER BY asig.asi_nombre asc";

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
        $trans = $con->getTransaction(); // se obtiene la transacci??n actual
        if ($trans !== null) {
            $trans = null; // si existe la transacci??n entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacci??n entonces se crea una
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
        $trans = $con->getTransaction(); // se obtiene la transacci??n actual
        if ($trans !== null) {
            $trans = null; // si existe la transacci??n entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacci??n entonces se crea una
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
     * Function Obtiene informaci??n de distributivo todos los estudiantes de posgrado.
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


    /**
     * Function consulta el nombre de unidad academica
     * @author  Luis Cajamarca <analistadesarrollo04@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarAsignaturaList() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
            
        $sql = "SELECT
                    asi_id,asi_nombre
                from " . $con->dbname . ".asignatura 
                WHERE uaca_id=1 
                and asi_estado = :estado
                and asi_estado_logico = :estado
                order by asi_nombre ASC ;
               ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryAll();
        return $resultData;
    }
}
