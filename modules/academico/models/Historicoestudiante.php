<?php

namespace app\modules\academico\models;

use Yii;
use app\models\Utilities;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use yii\base\Exception;
use yii\helpers\VarDumper;
use app\modules\academico\models\Estudiante;

/**
 * This is the model class for table "historico_siga".
 *
 * @property int $id_dummy
 * @property int|null $per_id
 * @property int|null $id_est_siga
 * @property string|null $cedula
 * @property string|null $per_apellido
 * @property string|null $per_apellido_segundo
 * @property string|null $per_nombre
 * @property string|null $per_nombre_segundo
 * @property string|null $carrera
 * @property string|null $modalidad
 * @property string|null $categoria
 * @property int|null $id_materia
 * @property int|null $creditos
 * @property string|null $materia
 * @property float|null $nota
 * @property int|null $id_periodo
 * @property string|null $periodo
 * @property int|null $n_vez
 * @property string|null $cod_legal
 * @property string|null $aprobada
 * @property string|null $bloque_academico
 * @property int|null $asi_id
 * @property int|null $maca_id
 */
class Historicoestudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historico_siga';
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
            [['id_dummy'], 'required'],
            [['per_id'], 'required'],
            [['id_dummy', 'per_id', 'id_est_siga', 'id_materia', 'creditos', 'id_periodo', 'n_vez', 'asi_id', 'maca_id'], 'integer'],
            [['nota'], 'number'],
            [['cedula'], 'string', 'max' => 45],
            [['per_apellido', 'per_apellido_segundo', 'per_nombre', 'per_nombre_segundo', 'carrera', 'materia', 'periodo', 'cod_legal', 'aprobada'], 'string', 'max' => 100],
            [['modalidad', 'categoria'], 'string', 'max' => 1],
            [['bloque_academico'], 'string', 'max' => 300],
            [['id_dummy'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_dummy' => Yii::t('app', 'Id Dummy'),
            'per_id' => Yii::t('app', 'Per ID'),
            'id_est_siga' => Yii::t('app', 'Id Est Siga'),
            'cedula' => Yii::t('app', 'Cedula'),
            'per_apellido' => Yii::t('app', 'Per Apellido'),
            'per_apellido_segundo' => Yii::t('app', 'Per Apellido Segundo'),
            'per_nombre' => Yii::t('app', 'Per Nombre'),
            'per_nombre_segundo' => Yii::t('app', 'Per Nombre Segundo'),
            'carrera' => Yii::t('app', 'Carrera'),
            'modalidad' => Yii::t('app', 'Modalidad'),
            'categoria' => Yii::t('app', 'Categoria'),
            'id_materia' => Yii::t('app', 'Id Materia'),
            'creditos' => Yii::t('app', 'Creditos'),
            'materia' => Yii::t('app', 'Materia'),
            'nota' => Yii::t('app', 'Nota'),
            'id_periodo' => Yii::t('app', 'Id Periodo'),
            'periodo' => Yii::t('app', 'Periodo'),
            'n_vez' => Yii::t('app', 'N Vez'),
            'cod_legal' => Yii::t('app', 'Cod Legal'),
            'aprobada' => Yii::t('app', 'Aprobada'),
            'bloque_academico' => Yii::t('app', 'Bloque Academico'),
            'asi_id' => Yii::t('app', 'Asi ID'),
            'maca_id' => Yii::t('app', 'Maca ID'),
        ];
    }


    public function consultarHistoricoEstudiante($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search = "(per.per_pri_nombre like :search OR ";
                $str_search .= "per.per_seg_nombre like :search OR ";
                $str_search .= "per.per_pri_apellido like :search OR ";
                $str_search .= "per.per_cedula like :search ) AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= " me.uaca_id = :unidad AND";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= " mo.mod_id = :modalidad AND";
            }
            if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
                $str_search .= " ea.eaca_id = :carrera  AND";
            }
        } 
        $sql = "SELECT distinct 
                    CONCAT(per.per_cedula,' - ',per.per_pri_nombre,' ',per.per_pri_apellido) estudiante,
                    ma.maca_nombre as carrera, 
                    md.made_codigo_asignatura,
                    a.asi_nombre,
                    mo.mod_nombre,
                    CONCAT(md.made_semestre,'°Semestre') semestre,
                    n.pmac_nota,
                    e.enac_asig_estado,
                    CONCAT(s.saca_nombre,' - ', s.saca_anio) periodo,
                    b.baca_nombre
                    
                from " . $con->dbname . ".malla_academico_estudiante pa 
                inner join " . $con1->dbname . ".persona per on per.per_id=pa.per_id
                inner join " . $con->dbname . ".estudiante es on es.per_id=per.per_id
                inner join " . $con->dbname . ".estudiante_carrera_programa est on es.est_id=est.est_id
                inner join " . $con->dbname . ".modalidad_estudio_unidad me on me.meun_id=est.meun_id
                inner join " . $con->dbname . ".malla_academica_detalle md on md.made_id=pa.made_id
                inner join " . $con->dbname . ".malla_unidad_modalidad mu on mu.maca_id=pa.maca_id 
                inner join " . $con->dbname . ".malla_academica ma on ma.maca_id=pa.maca_id
                inner join " . $con->dbname . ".asignatura a on pa.asi_id=a.asi_id
                inner join " . $con->dbname . ".estudio_academico ea on ea.eaca_id=me.eaca_id
                -- inner join " . $con->dbname . ".historico_siga_prueba h on h.per_id=pa.per_id
                inner join " . $con->dbname . ".modalidad mo on mo.mod_id=me.mod_id
                inner join " . $con->dbname . ".promedio_malla_academico n on pa.maes_id=n.maes_id
                inner join " . $con->dbname . ".estado_nota_academico e on e.enac_id=n.enac_id
                -- left join " . $con->dbname . ".planificar_periodo_academico pp on pp.maes_id=pa.maes_id
                left join " . $con->dbname . ".periodo_academico pe on n.paca_id=pe.paca_id
                left join " . $con->dbname . ".semestre_academico s on s.saca_id=pe.saca_id
                left join " . $con->dbname . ".bloque_academico b on b.baca_id=pe.baca_id
                WHERE 
                $str_search
                per.per_id=pa.per_id
                ORDER BY semestre";
        $comando = $con->createCommand($sql);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $search_cond = "%" . $arrFiltro["search"] . "%";
                $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            }
            
            $unidad = $arrFiltro["unidad"];
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
            }
            $modalidad = $arrFiltro["modalidad"];
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
            }
            $carrera = $arrFiltro["carrera"];
            if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
                $comando->bindParam(":carrera", $carrera, \PDO::PARAM_INT);
            }
            $resultData = $comando->queryAll();
        }else{
            $resultData = [];
        }
        if ($onlyData){ return $resultData; }        
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["Cedula","Carrera","Semestre","Codigo Asignatura", "Materia","Nota","Estado","Periodo","Bloque"],
            ],
        ]);
        
        return $dataProvider;
    }

    public function consultaPersonaID ($per_id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;

        $sql = "SELECT distinct
                    per.per_cedula,
                    per.per_pri_nombre,
                    per.per_seg_nombre,
                    per.per_pri_apellido,
                    per.per_seg_apellido
                    
                from " . $con->dbname . ".malla_academico_estudiante pa 
                inner join " . $con->dbname . ".historico_siga h on h.per_id=pa.per_id
                inner join " . $con1->dbname . ".persona per on per.per_id=h.per_id
                
                WHERE 
                pa.per_id=:per_id";



        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        
        return $resultData;
    }

    

    public function consultarHistoricoID($per_id,$onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        
        
        $sql = "SELECT distinct 
                    per.per_cedula,
                    CONCAT(per.per_cedula,' - ',per.per_pri_nombre,' ',per.per_pri_apellido) estudiante,
                    ma.maca_nombre, 
                    md.made_codigo_asignatura,
                    a.asi_nombre,
                    mo.mod_nombre,
                    CONCAT(md.made_semestre,'° Semestre') semestre,
                    n.pmac_nota,
                    e.enac_asig_estado,
                    CONCAT(s.saca_nombre,' - ', s.saca_anio) periodo,
                    b.baca_nombre
                    
                from " . $con->dbname . ".malla_academico_estudiante pa 
                inner join " . $con1->dbname . ".persona per on per.per_id=pa.per_id
                inner join " . $con->dbname . ".estudiante es on es.per_id=per.per_id
                inner join " . $con->dbname . ".estudiante_carrera_programa est on es.est_id=est.est_id
                inner join " . $con->dbname . ".modalidad_estudio_unidad me on me.meun_id=est.meun_id
                inner join " . $con->dbname . ".malla_academica_detalle md on md.made_id=pa.made_id
                inner join " . $con->dbname . ".malla_unidad_modalidad mu on mu.maca_id=pa.maca_id 
                inner join " . $con->dbname . ".malla_academica ma on ma.maca_id=pa.maca_id
                inner join " . $con->dbname . ".asignatura a on pa.asi_id=a.asi_id
                inner join " . $con->dbname . ".estudio_academico ea on ea.eaca_id=me.eaca_id
                -- inner join " . $con->dbname . ".historico_siga_prueba h on h.per_id=pa.per_id
                inner join " . $con->dbname . ".modalidad mo on mo.mod_id=me.mod_id
                inner join " . $con->dbname . ".promedio_malla_academico n on pa.maes_id=n.maes_id
                inner join " . $con->dbname . ".estado_nota_academico e on e.enac_id=n.enac_id
                -- left join " . $con->dbname . ".planificar_periodo_academico pp on pp.maes_id=pa.maes_id
                left join " . $con->dbname . ".periodo_academico pe on n.paca_id=pe.paca_id
                left join " . $con->dbname . ".semestre_academico s on s.saca_id=pe.saca_id
                left join " . $con->dbname . ".bloque_academico b on b.baca_id=pe.baca_id
                
                WHERE 
                pa.per_id=:per_id
                ORDER BY semestre";



        $comando = $con->createCommand($sql);
        if (isset($per_id) && $per_id != "") {
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $resultData = $comando->queryAll();
        }
        else{
            $resultData = [];
        }
        if ($onlyData){ return $resultData; }        
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["Cedula","Semestre","Codigo Asignatura", "Materia","Nota","Estado","Periodo","Bloque"],
            ],
        ]);
        
        return $dataProvider;
    }

    public function consultarDataEstudiante($per_id)  {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        
        $estado=1;
        $sql = "SELECT distinct  
                    ua.uaca_id,
                    ea.eaca_id,
                    mo.mod_id
                    
                from " . $con->dbname . ".malla_academico_estudiante pa 
                inner join " . $con->dbname . ".historico_siga_prueba h on h.per_id=pa.per_id
                inner join " . $con1->dbname . ".persona per on per.per_id=h.per_id
                inner join " . $con->dbname . ".malla_unidad_modalidad mu on mu.maca_id=pa.maca_id
                inner join " . $con->dbname . ".modalidad_estudio_unidad me on me.meun_id=mu.meun_id
                inner join " . $con->dbname . ".estudio_academico ea on me.eaca_id=ea.eaca_id
                inner join " . $con->dbname . ".unidad_academica ua on me.uaca_id=ua.uaca_id
                inner join " . $con->dbname . ".asignatura a on pa.asi_id=a.asi_id
                inner join " . $con->dbname . ".modalidad mo on mo.mod_id=h.modalidad
                
                WHERE 
                pa.per_id= :per_id";

        $comando = $con->createCommand($sql);

        //$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;

           
    }   

    public function busquedaEstudianteHistorico() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        
        $sql = "SELECT pers.per_id as id, concat(/*est.per_id, ' - ',*/ pers.per_cedula, ' - ', 
                    ifnull(pers.per_pri_nombre, ' ') ,' ', 
                    ifnull(pers.per_pri_apellido,' ')) as name
                    FROM db_academico.historico_siga_prueba h
                    JOIN db_academico.malla_academico_estudiante m ON m.per_id = h.per_id
                    JOIN db_asgard.persona pers ON pers.per_id = m.per_id
                WHERE pers.per_estado = :estado AND
                      pers.per_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consulta el nombre de unidad academica
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarUnidadAcademicasEmpresa($empresa) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        if ($empresa > 0) {
           $condicion = 'emp_id = :empresa AND '; 
        }
            
        $sql = "
                    SELECT distinct una.uaca_id as id, una.uaca_nombre as name
                        FROM db_academico.modalidad_estudio_unidad meu
                             inner JOIN db_academico.unidad_academica una on una.uaca_id = meu.uaca_id               
                    where $condicion
                        meu.meun_estado = :estado AND
                        meu.meun_estado_logico = :estado AND
                        una.uaca_estado = :estado AND
                        una.uaca_estado_logico = :estado
                    ORDER BY id asc ;
               ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":empresa", $empresa, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    /**
     * Function obtener Modalidad segun nivel interes estudio
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarModalidad($uaca_id, $emp_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
            $sql = "SELECT distinct moda.mod_id as id,
                           moda.mod_nombre as name
                    FROM " . $con->dbname . ".modalidad_estudio_unidad meu "
                    . "inner join " . $con->dbname . ".modalidad moda ON moda.mod_id = meu.mod_id
                    WHERE uaca_id = :uaca_id 
                    and emp_id =:emp_id
                    and meu.meun_estado_logico = :estado
                    and meu.meun_estado = :estado
                    and moda.mod_estado_logico = :estado
                    and moda.mod_estado = :estado
                    ORDER BY 1 asc";        
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    public function consultarCarreraModalidad($unidad, $modalidad) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
                SELECT 
                        eac.eaca_id as id,
                        eac.eaca_nombre as name
                    FROM
                        " . $con->dbname . ".modalidad_estudio_unidad as mcn
                        INNER JOIN " . $con->dbname . ".estudio_academico as eac on eac.eaca_id = mcn.eaca_id
                    WHERE 
                        mcn.uaca_id =:unidad AND
                        mcn.mod_id =:modalidad AND          
                        eac.eaca_estado_logico=:estado AND
                        eac.eaca_estado=:estado AND
                        mcn.meun_estado_logico = :estado AND
                        mcn.meun_estado = :estado
                        ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
        $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }


}
