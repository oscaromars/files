 <?php

namespace app\modules\academico\models; 

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use app\models\Utilities;
use \yii\db\Query;
use yii\base\Exception;
use app\modules\academico\models\PeriodoAcademico;
/**
 * This is the model class for table "db_academico.cabecera_asistencia".
 *
 * @property int $casi_id
 * @property int $paca_id
 * @property int $est_id
 * @property int $pro_id
 * @property int $asi_id
 * @property int $aeun_id
 * @property int $casi_cant_total
 * @property double $casi_porc_total
 * @property string $casi_estado
 * @property string $casi_fecha_creacion
 * @property string $casi_fecha_modificacion
 * @property string $casi_estado_logico
 */
class CabeceraAsistencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_academico.cabecera_asistencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paca_id', 'est_id', 'pro_id', 'asi_id', 'aeun_id', 'casi_estado', 'casi_estado_logico'], 'required'],
            [['paca_id', 'est_id', 'pro_id', 'asi_id', 'aeun_id', 'casi_cant_total'], 'integer'],
            [['casi_porc_total'], 'number'],
            [['casi_fecha_creacion', 'casi_fecha_modificacion'], 'safe'],
            [['casi_estado', 'casi_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'casi_id' => 'Casi ID',
            'paca_id' => 'Paca ID',
            'est_id' => 'Est ID',
            'pro_id' => 'Pro ID',
            'asi_id' => 'Asi ID',
            'aeun_id' => 'Aeun ID',
            'casi_cant_total' => 'Casi Cant Total',
            'casi_porc_total' => 'Casi Porc Total',
            'casi_estado' => 'Casi Estado',
            'casi_fecha_creacion' => 'Casi Fecha Creacion',
            'casi_fecha_modificacion' => 'Casi Fecha Modificacion',
            'casi_estado_logico' => 'Casi Estado Logico',
        ];
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
    public function getEst()
    {
        return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
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
    public function getAsi()
    {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
    }

    /**
     * Insertar en la tabla cabecera_asistencia
     * @author  Julio Lopez <analista.desarrollo@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function insertCabeceraAsistencia($paca_id, $est_id, $pro_id, $asi_id, $aeun_id, $casi_cant_total, $casi_porc_total){
        $con = Yii::$app->db_academico;

        try
        {
            $sql = "INSERT INTO " . $con->dbname . ".cabecera_asistencia(paca_id, est_id, pro_id, asi_id, aeun_id, casi_cant_total, casi_porc_total, casi_estado, casi_fecha_creacion, casi_estado_logico) VALUES($paca_id, $est_id, $pro_id, $asi_id, $aeun_id, $casi_cant_total, $casi_porc_total, 1, now(), 1)";

            $comando = $con->createCommand($sql);
            $result = $comando->execute();

            $idtable = $con->getLastInsertID($con->dbname . '.cabecera_asistencia');

            return $idtable;
        }
        catch (Exception $ex){
            return 0;
        }
    }

    /**
     * Insertar en la tabla detalle_asistencia
     * @author  Julio Lopez <analista.desarrollo@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo, $dasi_cantidad){
        $con = Yii::$app->db_academico;
        $dasi_usuario_creacion = @Yii::$app->session->get("PB_iduser");

        try
        {
            $sql = "INSERT INTO " . $con->dbname . ".detalle_asistencia (casi_id, ecal_id, dasi_tipo, dasi_cantidad, dasi_usuario_creacion, dasi_estado, dasi_fecha_creacion, dasi_estado_logico) VALUES($casi_id, $ecal_id, '".$dasi_tipo."', $dasi_cantidad, $dasi_usuario_creacion, 1, now(), 1)";

            $comando = $con->createCommand($sql);
            $result = $comando->execute();

            $idtable = $con->getLastInsertID($con->dbname . '.detalle_asistencia');

            return $idtable;
        }
        catch (Exception $ex){
            return 0;
        }
    }

    /**
     * Function consulta esquema unidad por parcial, y unidad academica
     * @author Julio Lopez <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function selectEsquemaCalificacionUnidad($ecal_id, $uaca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT aseu.aeun_id
                FROM " . $con->dbname . ".asistencia_esquema_unidad AS aseu
                where aseu.ecun_id  = ( select aeu.ecun_id 
                                    from " . $con->dbname . ".esquema_calificacion_unidad AS aeu 
                                    where aeu.ecal_id = :ecal_id and 
                                    aeu.uaca_id = :uaca_id and 
                                    aeu.ecun_estado = :estado and 
                                    aeu.ecun_estado_logico = :estado)
                and aseu.aeun_estado = :estado
                and aseu.aeun_estado_logico = :estado;";                      

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $comando->bindParam(":ecal_id", $ecal_id, \PDO::PARAM_STR);        
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();

        return $resultData;
    }

    /**
     * Actualizar registro en la tabla cabecera_asistencia
     * @author  Julio Lopez <analista.desarrollo@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function updateCabeceraAsistencia($casi_id, $casi_cant_total, $casi_porc_total){
        $con = Yii::$app->db_academico;

        try {
            $sql = "UPDATE " . $con->dbname . ".cabecera_asistencia 
                    SET casi_cant_total = $casi_cant_total, 
                        casi_porc_total = $casi_porc_total,
                        casi_fecha_modificacion = now() 
                    WHERE casi_id = $casi_id";

            $comando = $con->createCommand($sql);
            $result = $comando->execute();

            return 1;
        }
        catch (Exception $ex){
            return 0;
        }
    }

    /**
     * Actualizar registro en la tabla detalle_asistencia
     * @author  Julio Lopez <analista.desarrollo@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function updateDetalleAsistencia($dasi_id, $casi_id, $dasi_cantidad){
        $con = Yii::$app->db_academico;
        $usu_id = @Yii::$app->session->get("PB_iduser");

        try {
            $sql = "UPDATE " . $con->dbname . ".detalle_asistencia 
                    SET dasi_cantidad = $dasi_cantidad, 
                        dasi_usuario_modificacion = $usu_id, 
                        dasi_fecha_modificacion = now() 
                    WHERE dasi_id = $dasi_id AND
                          casi_id = $casi_id";

            $comando = $con->createCommand($sql);
            $result = $comando->execute();

            return 1;
        }
        catch (Exception $ex){
            return 0;
        }
    }

    
     /** 
     * Function Obtiene informacion de estudiante segun profesor, unidad, asug etc.
     * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos).
     */
    public function getAsistencia($arrFiltro){
        $con        = \Yii::$app->db_academico; 
        $con1       = \Yii::$app->db_asgard; 
        $str_search = "";
        $estado     = "1";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {

            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= " AND data.paca_id = :paca_id  ";
            }
            if ($arrFiltro['materia'] != "" && $arrFiltro['materia'] > 0) {
                $str_search .= " AND data.asi_id = :asi_id  ";
            }
            if ($arrFiltro['profesor'] != "" && $arrFiltro['profesor'] > 0) {
                $str_search .= " AND data.pro_id = :pro_id  ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= " AND data.uaca_id = :uaca_id  ";
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= " AND data.mod_id = :mod_id  ";
            } 
            
               
        } 

        $sql = "  SELECT (@row_number:=@row_number + 1) AS row_num
                         ,data.nombre
                         ,data.matricula
                         ,data.materia
                         ,sum(data.u1) as u1
                         ,sum(data.u4) as u2

                        ";
        
                     /*   if ($arrFiltro['parcial'] == 1){
            $sql .= "     ,sum(data.u1) as u1

                             ";   
        }

         if ($arrFiltro['parcial'] == 1){
            $sql .= "     ,sum(data.u4) as u2

                             ";   
        }*/



         $sql .= "
                         ,data.paca_id
                         ,data.est_id
                         ,data.pro_id
                         ,data.asi_id 
                         ,data.uaca_id 
                         ,data.mod_id 
                         ,data.daes_id 
                         ,data.daho_total_horas
                    FROM (
                  SELECT est.est_id
                        ,est.est_matricula as matricula
                        ,concat(per.per_pri_apellido,' ',per.per_pri_nombre) as nombre
                        ,coalesce(casi.casi_id,0) as casi_id
                      ,(SELECT ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS value
                            FROM " . $con->dbname . ".semestre_academico AS saca
                            INNER JOIN " . $con->dbname . ".periodo_academico AS paca ON saca.saca_id = paca.saca_id
                            INNER JOIN " . $con->dbname . ".bloque_academico AS baca ON baca.baca_id = paca.baca_id
                            WHERE
                            paca.paca_id = :paca_id AND
                            paca.paca_activo = 'A' AND
                            paca.paca_estado = 1 AND
                            paca.paca_estado_logico = 1 AND
                            saca.saca_estado = 1 AND
                            saca.saca_estado_logico = 1 AND
                            baca.baca_estado = 1 AND
                            baca.baca_estado_logico = 1) as periodo 
                        ,(SELECT asi.asi_descripcion FROM " . $con->dbname . ".asignatura asi WHERE asi.asi_id = daca.asi_id) as materia
                        ,daca.paca_id as paca_id
                        ,daca.asi_id  as asi_id
                        ,daca.pro_id  as pro_id
                        ,asi.uaca_id  as uaca_id
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                           where dasi.casi_id = casi.casi_id
                             -- and ecun.ecal_id = 1
                             and dasi.dasi_tipo = 'u1') as u1
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                           where dasi.casi_id = casi.casi_id
                             -- and ecun.ecal_id = 1
                             and dasi.dasi_tipo = 'u2') as u2
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                            where dasi.casi_id = casi.casi_id
                            -- and ecun.ecal_id = 2
                            and dasi.dasi_tipo = 'u1') as u3
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                            where dasi.casi_id = casi.casi_id
                            -- and ecun.ecal_id = 2
                            and dasi.dasi_tipo = 'u2') as u4
                        , daca.mod_id
                        , daes.daes_id
                        ,daho.daho_total_horas
                   FROM " . $con->dbname . ".distributivo_academico daca
             INNER JOIN " . $con->dbname . ".distributivo_academico_estudiante daes  ON daes.daca_id = daca.daca_id
             INNER JOIN " . $con->dbname . ".distributivo_academico_horario daho on daho.daho_id = daca.daho_id
              LEFT JOIN " . $con->dbname . ".estudiante est                          ON est.est_id = daes.est_id
                    AND est.est_estado = :estado
                    AND est.est_estado_logico = :estado
             INNER JOIN " . $con1->dbname. ".persona per                             ON per.per_id   = est.per_id
              LEFT JOIN " . $con->dbname . ".cabecera_asistencia casi              
                     ON casi.est_id  = est.est_id 
                    AND casi.asi_id  = daca.asi_id
                    AND casi.pro_id  = daca.pro_id
                    AND casi.paca_id = daca.paca_id
                    AND casi.casi_estado = :estado
                    AND casi.casi_estado_logico = :estado
              LEFT JOIN " . $con->dbname . ".asistencia_esquema_unidad aeun   ON aeun.aeun_id = casi.aeun_id
              -- LEFT JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun ON ecun.ecun_id = aeun.ecun_id 
              LEFT JOIN " . $con->dbname . ".asignatura asi                   ON asi.asi_id = daca.asi_id
            order by 3 asc
            ) as data 
            ,(SELECT @row_number:=0) AS t
           WHERE 1=1 
                 $str_search  
        group by matricula, nombre, est_id, pro_id, materia, asi_id
         ORDER BY nombre ASC
        ";

        $comando = $con->createCommand($sql);

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR); 

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
         
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $unidad = $arrFiltro["unidad"];
                $comando->bindParam(":uaca_id", $unidad, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }  
            
            if ($arrFiltro['materia'] != "" && $arrFiltro['materia'] > 0) {
                $materia = $arrFiltro["materia"];
                $comando->bindParam(":asi_id", $materia, \PDO::PARAM_INT);
            }
           /* if ($arrFiltro['parcial'] != "" && $arrFiltro['parcial'] > 0) {
                $parcial = $arrFiltro["parcial"];
                $comando->bindParam(":ecal_id", $parcial, \PDO::PARAM_INT);
            } */
            if ($arrFiltro['profesor'] != "" && $arrFiltro['profesor'] > 0) {
                $profesor = $arrFiltro["profesor"];
                $comando->bindParam(":pro_id", $profesor, \PDO::PARAM_INT);
            }
        }

        $res = $comando->queryAll();

        \app\models\Utilities::putMessageLogFile('getAsistencia: ' .$comando->getRawSql());
        return $res;
    }//function getAsistencia

    

    /**
     * Function consulta detalle_asistencia
     * @author Julio Lopez <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function selectDetalleAsistencia($casi_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT d.dasi_id
            FROM " . $con->dbname . ".detalle_asistencia d
            WHERE d.casi_id = :casi_id
            AND d.dasi_estado = :estado  
            AND d.dasi_estado_logico = :estado"; 

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $comando->bindParam(":casi_id", $casi_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Actualizar registro en la tabla detalle_calificacion
     * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function ActualizarNotaAsistencia($data/*, $unidad*/){
        try{
            $con    = \Yii::$app->db_academico;
            $estado = '1';
            $usu_id = @Yii::$app->session->get("PB_iduser");
            $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);

            \app\models\Utilities::putMessageLogFile(print_r($data,true));
            /*
            if($unidad == 1){
                $aeun_id_u1_u2 = 1;
                $aeun_id_u3_u4 = 2;
            }
            if($unidad == 2){
                $aeun_id_u1_u2 = 3;
                $aeun_id_u3_u4 = 4;
            }
            if($unidad == 3){
                $aeun_id_u1_u2 = 5;
                $aeun_id_u3_u4 = 6;
            } */

            
            if($data['uaca_id'] == 1){
                $aeun_id_u1_u2 = 1;
                $aeun_id_u3_u4 = 2;
            }
            if($data['uaca_id'] == 2){
                $aeun_id_u1_u2 = 1;
                $aeun_id_u3_u4 = 2;
            }
            /*if($data['uaca_id'] == 3){
                $aeun_id_u1_u2 = 5;
                $aeun_id_u3_u4 = 6;
            }*/
            //////////////////////////////////////////////////////
            //PRIMERO PREGUNTAMOS SI TIENE CABECERA PARA U1 Y U2//
            //////////////////////////////////////////////////////
            $sql = "SELECT casi.casi_id, casi.aeun_id, ecun.ecal_id
                    FROM db_academico.cabecera_asistencia casi
                        ,db_academico.asistencia_esquema_unidad aeun
                        ,db_academico.esquema_calificacion_unidad ecun
                    WHERE casi.aeun_id = aeun.aeun_id
                    and aeun.ecun_id = ecun.ecun_id
                    and casi.paca_id = ".$data['paca_id']."
                    and casi.est_id  = ".$data['est_id']."
                    and casi.pro_id  = ".$data['pro_id']."
                    and casi.asi_id  = ".$data['asi_id']."
                    and ecun.ecal_id = 1";
        
            $command  = $con->createCommand($sql);
            $res_casi = $command->queryOne();

            \app\models\Utilities::putMessageLogFile('cabecera u1 y u2: ' .$command->getRawSql());

            //PREGUNTO SI EXISTE EL REGSITRO
            if(empty($res_casi['casi_id'])){
                //por if true quiere decir q no existo, aqui debo crear primero la cabecera
                $sql = "INSERT INTO db_academico.cabecera_asistencia
                            (
                            `paca_id`,
                            `est_id`,
                            `pro_id`,
                            `asi_id`,
                            `aeun_id`,
                            `casi_cant_total`,
                            `casi_porc_total`,
                            `casi_estado`,
                            `casi_fecha_creacion`,
                            `casi_estado_logico`)
                            VALUES
                            (
                            ".$data['paca_id'].",
                            ".$data['est_id'].",
                            ".$data['pro_id'].",
                            ".$data['asi_id'].",
                            $aeun_id_u1_u2,
                            null,
                            null,
                            1,
                            '$fecha_transaccion',
                            1)";
                $command = $con->createCommand($sql);
                $result  = $command->execute();
                $casi_id = $con->getLastInsertID($con->dbname . '.cabecera_asistencia');

                //\app\models\Utilities::putMessageLogFile("control de errores");
                //\app\models\Utilities::putMessageLogFile("ultimo id: ".$casi_id);
            }else{
                $casi_id = $res_casi['casi_id'];
            }
            ///////////////////////////////////////////////////////////////////
            //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA U1 //
            ///////////////////////////////////////////////////////////////////
            $sql = "SELECT dasi.dasi_id, dasi.casi_id
                    FROM db_academico.detalle_asistencia dasi
                    WHERE dasi.casi_id   = $casi_id 
                    AND dasi.dasi_tipo = 'u1'";

        $command = $con->createCommand($sql);
        $res_u1  = $command->queryOne();

        //\app\models\Utilities::putMessageLogFile('u1: ' .$command->getRawSql());

        //\app\models\Utilities::putMessageLogFile(print_r($res_u1,true));

            //Pregunto si existe el detalle U1
            if(empty($res_u1)){
                $u1      = $data['u1'];
                $sql = "INSERT INTO db_academico.detalle_asistencia
                                    (`casi_id`,
                                    `dasi_cantidad`,
                                    `dasi_tipo`,
                                    `dasi_usuario_creacion`,
                                    `dasi_estado`,
                                    `dasi_fecha_creacion`,
                                    `dasi_estado_logico`)
                                    VALUES
                                    ($casi_id,
                                    $u1,
                                    'u1',
                                    $usu_id,
                                    $estado,
                                    '$fecha_transaccion',
                                    1)";
            }else{
                $dasi_id = $res_u1['dasi_id'];
                $u1      = $data['u1'];
                $sql = "UPDATE db_academico.detalle_asistencia 
                        set dasi_cantidad = $u1
                        WHERE dasi_id = $dasi_id";
            }

       

        if($u1 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();

        }

         \app\models\Utilities::putMessageLogFile("control de errores");
        \app\models\Utilities::putMessageLogFile('insert u1: ' .$command->getRawSql());
       
        
          //Actualizamos la cabecera con los nuevos valores
        $sql = "UPDATE db_academico.cabecera_asistencia
                   SET casi_cant_total = (SELECT sum(dasi_cantidad) as casi_cant_total
                                            FROM db_academico.detalle_asistencia 
                                           where casi_id = $casi_id),
                       casi_porc_total = (SELECT round ((sum(dasi_cantidad) / 0.3),2) as casi_porc_total
                                            FROM db_academico.detalle_asistencia 
                                           where casi_id = $casi_id)
                WHERE casi_id = $casi_id
        ";
        
           $command = $con->createCommand($sql);
        $result  = $command->execute();
        //////////////////////////////////////////////////////
        //PRIMERO PREGUNTAMOS SI TIENE CABECERA PARA U3 Y U4//
        //////////////////////////////////////////////////////
        $sql = "SELECT casi.casi_id, casi.aeun_id, ecun.ecal_id
                  FROM db_academico.cabecera_asistencia casi
                       ,asistencia_esquema_unidad aeun
                       ,esquema_calificacion_unidad ecun
                 WHERE casi.aeun_id = aeun.aeun_id
                   and aeun.ecun_id = ecun.ecun_id
                   and casi.paca_id = ".$data['paca_id']."
                   and casi.est_id  = ".$data['est_id']."
                   and casi.pro_id  = ".$data['pro_id']."
                   and casi.asi_id  = ".$data['asi_id']."
                   and ecun.ecal_id = 2";
    
        $command  = $con->createCommand($sql);
        $res_casi = $command->queryOne();

            //PREGUNTO SI EXISTE EL REGSITRO
            if(empty($res_casi)){
                //por if true quiere decir q no existo, aqui debo crear primero la cabecera
                $sql = "INSERT INTO db_academico.cabecera_asistencia
                            (
                            `paca_id`,
                            `est_id`,
                            `pro_id`,
                            `asi_id`,
                            `aeun_id`,
                            `casi_cant_total`,
                            `casi_porc_total`,
                            `casi_estado`,
                            `casi_fecha_creacion`,
                            `casi_estado_logico`)
                            VALUES
                            (
                            ".$data['paca_id'].",
                            ".$data['est_id'].",
                            ".$data['pro_id'].",
                            ".$data['asi_id'].",
                            $aeun_id_u3_u4,
                            null,
                            null,
                            1,
                            '$fecha_transaccion',
                            1)";
                $command = $con->createCommand($sql);
                $result  = $command->execute();
                $casi_id = $con->getLastInsertID($con->dbname . '.cabecera_asistencia');

                //\app\models\Utilities::putMessageLogFile("ultimo id: ".$casi_id);
            }else{
                $casi_id = $res_casi['casi_id'];}
                ///////////////////////////////////////////////////////////////////
                //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA U2 //
                ///////////////////////////////////////////////////////////////////
                $sql = "SELECT dasi.dasi_id, dasi.casi_id
                        FROM db_academico.detalle_asistencia dasi
                        WHERE dasi.casi_id   = $casi_id 
                        AND dasi.dasi_tipo = 'u2'";

        $command = $con->createCommand($sql);
        $res_u3  = $command->queryOne();

            //Pregunto si existe el detalle U1
            if(empty($res_u3)){
                $u3      = $data['u2'];
                $sql = "INSERT INTO db_academico.detalle_asistencia
                                            (`casi_id`,
                                            `dasi_cantidad`,
                                            `dasi_tipo`,
                                            `dasi_usuario_creacion`,
                                            `dasi_estado`,
                                            `dasi_fecha_creacion`,
                                            `dasi_estado_logico`)
                                            VALUES
                                            ($casi_id,
                                            $u3,
                                            'u2',
                                            $usu_id,
                                            $estado,
                                            '$fecha_transaccion',
                                            1)";
                    }else{
                        $dasi_id = $res_u3['dasi_id'];
                        $u3      = $data['u2'];
                        $sql = "UPDATE db_academico.detalle_asistencia 
                                set dasi_cantidad = $u3
                                WHERE dasi_id = $dasi_id";
                    }
                    
                    if($u3 != ''){
                        $command = $con->createCommand($sql);
                        $result  = $command->execute();
                    }     
                    
                    //Actualizamos la cabecera con los nuevos valores
                    $sql = "UPDATE db_academico.cabecera_asistencia
                            SET casi_cant_total = (SELECT sum(dasi_cantidad) as casi_cant_total
                                                        FROM db_academico.detalle_asistencia 
                                                    where casi_id = $casi_id),
                                casi_porc_total = (SELECT round ((sum(dasi_cantidad) / 0.3),2) as casi_porc_total
                                                        FROM db_academico.detalle_asistencia 
                                                    where casi_id = $casi_id)
                            WHERE casi_id = $casi_id
                    ";

        $command = $con->createCommand($sql);
        $result  = $command->execute();
        
        return true;
    }catch(Exception $e){
        \app\models\Utilities::putMessageLogFile("Error al actualizar: " . $e->getMessage());
        return false;
        }
    }//

    /**
     * Function consulta cabecera_asistencia por medio de 4 IDs
     * @author Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarCabeceraPorIDs($est_id, $asi_id, $pro_id, $paca_id){
        $con = Yii::$app->db_academico;

        $sql = "SELECT * FROM db_academico.cabecera_asistencia AS casi
                WHERE casi.est_id = $est_id AND casi.asi_id = $asi_id AND casi.pro_id = $pro_id AND casi.paca_id = $paca_id
                AND casi.casi_estado = 1 AND casi.casi_estado_logico = 1";

        $comando = $con->createCommand($sql);
        $resultData = $comando->queryAll();

        return $resultData;
    }
    
         /** 
     * Function Obtiene informacion de estudiante segun profesor, unidad, asug etc.
     * @author  Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos).
     */
    public function getAsistenciasemanal($arrFiltro){
        $con        = \Yii::$app->db_academico; 
        $con1       = \Yii::$app->db_asgard; 
        $str_search = "";
        $estado     = "1";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {

            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= " AND data.paca_id = :paca_id  ";
            }
            if ($arrFiltro['materia'] != "" && $arrFiltro['materia'] > 0) {
                $str_search .= " AND data.asi_id = :asi_id  ";
            }
            if ($arrFiltro['profesor'] != "" && $arrFiltro['profesor'] > 0) {
                $str_search .= " AND data.pro_id = :pro_id  ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= " AND data.uaca_id = :uaca_id  ";
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= " AND data.mod_id = :mod_id  ";
            } 
            
               
        } 

        $sql = "  SELECT (@row_number:=@row_number + 1) AS row_num
                         ,data.matricula
                         ,data.nombre
                         ,data.materia
                         ,sum(data.s1) as s1
                         ,sum(data.s2) as s2
                         ,sum(data.s3) as s3
                         ,sum(data.s4) as s4
                         ,sum(data.s5) as s5
                         ,sum(data.s6) as s6
                         ,sum(data.s7) as s7
                         ,sum(data.s8) as s8
                         ,sum(data.s9) as s9
                         ,sum(data.s0) as s0
                         ,data.paca_id
                         ,data.est_id
                         ,data.pro_id
                         ,data.asi_id 
                         ,data.uaca_id 
                         ,data.mod_id 
                         ,data.daes_id 
                         ,data.daho_total_horas
                    FROM (
                  SELECT est.est_id
                        ,est.est_matricula as matricula
                        ,concat(per.per_pri_nombre,' ',per.per_pri_apellido) as nombre
                        ,coalesce(casi.casi_id,0) as casi_id
                      ,(SELECT ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS value
                            FROM " . $con->dbname . ".semestre_academico AS saca
                            INNER JOIN " . $con->dbname . ".periodo_academico AS paca ON saca.saca_id = paca.saca_id
                            INNER JOIN " . $con->dbname . ".bloque_academico AS baca ON baca.baca_id = paca.baca_id
                            WHERE
                            paca.paca_id = :paca_id AND
                            paca.paca_activo = 'A' AND
                            paca.paca_estado = 1 AND
                            paca.paca_estado_logico = 1 AND
                            saca.saca_estado = 1 AND
                            saca.saca_estado_logico = 1 AND
                            baca.baca_estado = 1 AND
                            baca.baca_estado_logico = 1) as periodo 
                        ,(SELECT asi.asi_descripcion FROM " . $con->dbname . ".asignatura asi WHERE asi.asi_id = daca.asi_id) as materia
                        ,daca.paca_id as paca_id
                        ,daca.asi_id  as asi_id
                        ,daca.pro_id  as pro_id
                        ,asi.uaca_id  as uaca_id
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                           where dasi.casi_id = casi.casi_id
                             and ecun.ecal_id = 1
                             and dasi.dasi_tipo = 's1') as s1
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                           where dasi.casi_id = casi.casi_id
                             and ecun.ecal_id = 1
                             and dasi.dasi_tipo = 's2') as s2
                         ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                           where dasi.casi_id = casi.casi_id
                             and ecun.ecal_id = 1
                             and dasi.dasi_tipo = 's3') as s3
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                            where dasi.casi_id = casi.casi_id
                            and ecun.ecal_id = 1
                            and dasi.dasi_tipo = 's4') as s4
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                            where dasi.casi_id = casi.casi_id
                            and ecun.ecal_id = 1
                            and dasi.dasi_tipo = 's5') as s5
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                            where dasi.casi_id = casi.casi_id
                            and ecun.ecal_id = 2
                            and dasi.dasi_tipo = 's6') as s6
                         ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                           where dasi.casi_id = casi.casi_id
                             and ecun.ecal_id = 2
                             and dasi.dasi_tipo = 's7') as s7
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                            where dasi.casi_id = casi.casi_id
                            and ecun.ecal_id = 2
                            and dasi.dasi_tipo = 's8') as s8
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                            where dasi.casi_id = casi.casi_id
                            and ecun.ecal_id = 2
                            and dasi.dasi_tipo = 's9') as s9
                        ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                            where dasi.casi_id = casi.casi_id
                            and ecun.ecal_id = 2
                            and dasi.dasi_tipo = 's0') as s0
                        , daca.mod_id
                        , daes.daes_id
                        ,daho.daho_total_horas
                   FROM " . $con->dbname . ".distributivo_academico daca
             INNER JOIN " . $con->dbname . ".distributivo_academico_estudiante daes  ON daes.daca_id = daca.daca_id
             INNER JOIN " . $con->dbname . ".distributivo_academico_horario daho on daho.daho_id = daca.daho_id
              LEFT JOIN " . $con->dbname . ".estudiante est                          ON est.est_id = daes.est_id
                    AND est.est_estado = :estado
                    AND est.est_estado_logico = :estado
             INNER JOIN " . $con1->dbname. ".persona per                             ON per.per_id   = est.per_id
              LEFT JOIN " . $con->dbname . ".cabecera_asistencia casi              
                     ON casi.est_id  = est.est_id 
                    AND casi.asi_id  = daca.asi_id
                    AND casi.pro_id  = daca.pro_id
                    AND casi.paca_id = daca.paca_id
                    AND casi.casi_estado = :estado
                    AND casi.casi_estado_logico = :estado
              LEFT JOIN " . $con->dbname . ".asistencia_esquema_unidad aeun   ON aeun.aeun_id = casi.aeun_id
              LEFT JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun ON ecun.ecun_id = aeun.ecun_id 
              LEFT JOIN " . $con->dbname . ".asignatura asi                   ON asi.asi_id = daca.asi_id
            order by 3 asc
            ) as data 
            ,(SELECT @row_number:=0) AS t
           WHERE 1=1 
                 $str_search  
        group by matricula, nombre, est_id, pro_id, materia, asi_id
         ORDER BY nombre ASC
        ";

        $comando = $con->createCommand($sql);

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR); 

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
         
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $unidad = $arrFiltro["unidad"];
                $comando->bindParam(":uaca_id", $unidad, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }  
            
            if ($arrFiltro['materia'] != "" && $arrFiltro['materia'] > 0) {
                $materia = $arrFiltro["materia"];
                $comando->bindParam(":asi_id", $materia, \PDO::PARAM_INT);
            }
           /* if ($arrFiltro['parcial'] != "" && $arrFiltro['parcial'] > 0) {
                $parcial = $arrFiltro["parcial"];
                $comando->bindParam(":ecal_id", $parcial, \PDO::PARAM_INT);
            } */
            if ($arrFiltro['profesor'] != "" && $arrFiltro['profesor'] > 0) {
                $profesor = $arrFiltro["profesor"];
                $comando->bindParam(":pro_id", $profesor, \PDO::PARAM_INT);
            }
        }

        $res = $comando->queryAll();

        \app\models\Utilities::putMessageLogFile('getAsistencia: ' .$comando->getRawSql());
        return $res;
    }//function getAsistenciasemanal
    
    
     public function ActualizarNotaAsistenciasemanal($data/*, $unidad*/){
        $con    = \Yii::$app->db_academico;
        $estado = '1';
        $usu_id = @Yii::$app->session->get("PB_iduser");

        \app\models\Utilities::putMessageLogFile(print_r($data,true));
        /*
        if($unidad == 1){
            $aeun_id_u1_u2 = 1;
            $aeun_id_u3_u4 = 2;
        }
        if($unidad == 2){
            $aeun_id_u1_u2 = 3;
            $aeun_id_u3_u4 = 4;
        }
        if($unidad == 3){
            $aeun_id_u1_u2 = 5;
            $aeun_id_u3_u4 = 6;
        } */

        
         if($data['uaca_id'] == 1){
            $aeun_id_u1_u2 = 1;
            $aeun_id_u3_u4 = 2;
        }
        if($data['uaca_id'] == 2){
            $aeun_id_u1_u2 = 1;
            $aeun_id_u3_u4 = 2;
        }
        /*if($data['uaca_id'] == 3){
            $aeun_id_u1_u2 = 5;
            $aeun_id_u3_u4 = 6;
        }*/
        //////////////////////////////////////////////////////
        //PRIMERO PREGUNTAMOS SI TIENE CABECERA PARA U1 Y U2//
        //////////////////////////////////////////////////////
        $sql = "SELECT casi.casi_id, casi.aeun_id, ecun.ecal_id
                  FROM db_academico.cabecera_asistencia casi
                       ,db_academico.asistencia_esquema_unidad aeun
                       ,db_academico.esquema_calificacion_unidad ecun
                 WHERE casi.aeun_id = aeun.aeun_id
                   and aeun.ecun_id = ecun.ecun_id
                   and casi.paca_id = ".$data['paca_id']."
                   and casi.est_id  = ".$data['est_id']."
                   and casi.pro_id  = ".$data['pro_id']."
                   and casi.asi_id  = ".$data['asi_id']."
                   and ecun.ecal_id = 1";
    
        $command  = $con->createCommand($sql);
        $res_casi = $command->queryOne();

        \app\models\Utilities::putMessageLogFile('cabecera u1 y u2: ' .$command->getRawSql());

        //PREGUNTO SI EXISTE EL REGSITRO
        if(empty($res_casi['casi_id'])){
            //por if true quiere decir q no existo, aqui debo crear primero la cabecera
            $sql = "INSERT INTO db_academico.cabecera_asistencia
                        (
                        `paca_id`,
                        `est_id`,
                        `pro_id`,
                        `asi_id`,
                        `aeun_id`,
                        `casi_cant_total`,
                        `casi_porc_total`,
                        `casi_estado`,
                        `casi_fecha_creacion`,
                        `casi_estado_logico`)
                        VALUES
                        (
                        ".$data['paca_id'].",
                        ".$data['est_id'].",
                        ".$data['pro_id'].",
                        ".$data['asi_id'].",
                        $aeun_id_u1_u2,
                        null,
                        null,
                        1,
                        now(),
                        1)";
            $command = $con->createCommand($sql);
            $result  = $command->execute();
            $casi_id = $con->getLastInsertID($con->dbname . '.cabecera_asistencia');

            //\app\models\Utilities::putMessageLogFile("control de errores");
            //\app\models\Utilities::putMessageLogFile("ultimo id: ".$casi_id);
        }else
            $casi_id = $res_casi['casi_id'];
        ///////////////////////////////////////////////////////////////////
        //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA U1 //
        ///////////////////////////////////////////////////////////////////
        $sql = "SELECT dasi.dasi_id, dasi.casi_id
                  FROM db_academico.detalle_asistencia dasi
                 WHERE dasi.casi_id   = $casi_id 
                   AND dasi.dasi_tipo = 's1'";

        $command = $con->createCommand($sql);
        $res_s1  = $command->queryOne();

        //\app\models\Utilities::putMessageLogFile('u1: ' .$command->getRawSql());

        //\app\models\Utilities::putMessageLogFile(print_r($res_u1,true));

        //Pregunto si existe el detalle U1
        if(empty($res_s1)){
            $s1      = $data['s1'];
            $sql = "INSERT INTO db_academico.detalle_asistencia
                                (`casi_id`,
                             --   `ecal_id`,
                                `dasi_cantidad`,
                                `dasi_tipo`,
                                `dasi_usuario_creacion`,
                                `dasi_estado`,
                                `dasi_fecha_creacion`,
                                `dasi_estado_logico`)
                                VALUES
                                ($casi_id,
                            --     1,
                                 $s1,
                                 's1',
                                 $usu_id,
                                 $estado,
                                 now(),
                                 1)";
        }else{
            $dasi_id = $res_s1['dasi_id'];
            $s1      = $data['s1'];
            $sql = "UPDATE db_academico.detalle_asistencia 
                       set dasi_cantidad = $s1
                     WHERE dasi_id = $dasi_id";
        }

       

        if($s1 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();

        }

         \app\models\Utilities::putMessageLogFile("control de errores");
        \app\models\Utilities::putMessageLogFile('insert u1: ' .$command->getRawSql());
        
        
        ///////////////////////////////////////////////////////////////////
        //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA S2 //
        ///////////////////////////////////////////////////////////////////
        $sql = "SELECT dasi.dasi_id, dasi.casi_id
                  FROM db_academico.detalle_asistencia dasi
                 WHERE dasi.casi_id   = $casi_id 
                   AND dasi.dasi_tipo = 's2'";

        $command = $con->createCommand($sql);
        $res_s2  = $command->queryOne();

        //\app\models\Utilities::putMessageLogFile('u1: ' .$command->getRawSql());

        //\app\models\Utilities::putMessageLogFile(print_r($res_u1,true));

        //Pregunto si existe el detalle U1
        if(empty($res_s2)){
            $s2      = $data['s2'];
            $sql = "INSERT INTO db_academico.detalle_asistencia
                                (`casi_id`,
                            --     `ecal_id`,
                                `dasi_cantidad`,
                                `dasi_tipo`,
                                `dasi_usuario_creacion`,
                                `dasi_estado`,
                                `dasi_fecha_creacion`,
                                `dasi_estado_logico`)
                                VALUES
                                ($casi_id,
                           --      1,
                                 $s2,
                                 's2',
                                 $usu_id,
                                 $estado,
                                 now(),
                                 1)";
        }else{
            $dasi_id = $res_s2['dasi_id'];
            $s2      = $data['s2'];
            $sql = "UPDATE db_academico.detalle_asistencia 
                       set dasi_cantidad = $s2
                     WHERE dasi_id = $dasi_id";
        }

       

        if($s2 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();

        }

         \app\models\Utilities::putMessageLogFile("control de errores");
        \app\models\Utilities::putMessageLogFile('insert u1: ' .$command->getRawSql());
        
        ///////////////////////////////////////////////////////////////////
        //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA S3 //
        ///////////////////////////////////////////////////////////////////
        $sql = "SELECT dasi.dasi_id, dasi.casi_id
                  FROM db_academico.detalle_asistencia dasi
                 WHERE dasi.casi_id   = $casi_id 
                   AND dasi.dasi_tipo = 's3'";

        $command = $con->createCommand($sql);
        $res_s3  = $command->queryOne();

        //\app\models\Utilities::putMessageLogFile('u1: ' .$command->getRawSql());

        //\app\models\Utilities::putMessageLogFile(print_r($res_u1,true));

        //Pregunto si existe el detalle U1
        if(empty($res_s3)){
            $s3      = $data['s3'];
            $sql = "INSERT INTO db_academico.detalle_asistencia
                                (`casi_id`,
                            --     `ecal_id`,
                                `dasi_cantidad`,
                                `dasi_tipo`,
                                `dasi_usuario_creacion`,
                                `dasi_estado`,
                                `dasi_fecha_creacion`,
                                `dasi_estado_logico`)
                                VALUES
                                ($casi_id,
                            --    1,
                                 $s3,
                                 's3',
                                 $usu_id,
                                 $estado,
                                 now(),
                                 1)";
        }else{
            $dasi_id = $res_s3['dasi_id'];
            $s3      = $data['s3'];
            $sql = "UPDATE db_academico.detalle_asistencia 
                       set dasi_cantidad = $s3
                     WHERE dasi_id = $dasi_id";
        }

       

        if($s3 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();

        }

         \app\models\Utilities::putMessageLogFile("control de errores");
        \app\models\Utilities::putMessageLogFile('insert u1: ' .$command->getRawSql());
        
        ///////////////////////////////////////////////////////////////////
        //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA S4 //
        ///////////////////////////////////////////////////////////////////
        $sql = "SELECT dasi.dasi_id, dasi.casi_id
                  FROM db_academico.detalle_asistencia dasi
                 WHERE dasi.casi_id   = $casi_id 
                   AND dasi.dasi_tipo = 's4'";

        $command = $con->createCommand($sql);
        $res_s4  = $command->queryOne();

        //\app\models\Utilities::putMessageLogFile('u1: ' .$command->getRawSql());

        //\app\models\Utilities::putMessageLogFile(print_r($res_u1,true));

        //Pregunto si existe el detalle U1
        if(empty($res_s4)){
            $s4      = $data['s4'];
            $sql = "INSERT INTO db_academico.detalle_asistencia
                                (`casi_id`,
                              --   `ecal_id`,
                                `dasi_cantidad`,
                                `dasi_tipo`,
                                `dasi_usuario_creacion`,
                                `dasi_estado`,
                                `dasi_fecha_creacion`,
                                `dasi_estado_logico`)
                                VALUES
                                ($casi_id,
                          --      1,
                                 $s4,
                                 's4',
                                 $usu_id,
                                 $estado,
                                 now(),
                                 1)";
        }else{
            $dasi_id = $res_s4['dasi_id'];
            $s4      = $data['s4'];
            $sql = "UPDATE db_academico.detalle_asistencia 
                       set dasi_cantidad = $s4
                     WHERE dasi_id = $dasi_id";
        }

       

        if($s4 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();

        }

         \app\models\Utilities::putMessageLogFile("control de errores");
        \app\models\Utilities::putMessageLogFile('insert u1: ' .$command->getRawSql());
        
        ///////////////////////////////////////////////////////////////////
        //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA S5 //
        ///////////////////////////////////////////////////////////////////
        $sql = "SELECT dasi.dasi_id, dasi.casi_id
                  FROM db_academico.detalle_asistencia dasi
                 WHERE dasi.casi_id   = $casi_id 
                   AND dasi.dasi_tipo = 's5'";

        $command = $con->createCommand($sql);
        $res_s5  = $command->queryOne();

        //\app\models\Utilities::putMessageLogFile('u1: ' .$command->getRawSql());

        //\app\models\Utilities::putMessageLogFile(print_r($res_u1,true));

        //Pregunto si existe el detalle U1
        if(empty($res_s5)){
            $s5      = $data['s5'];
            $sql = "INSERT INTO db_academico.detalle_asistencia
                                (`casi_id`,
                          --       `ecal_id`,
                                `dasi_cantidad`,
                                `dasi_tipo`,
                                `dasi_usuario_creacion`,
                                `dasi_estado`,
                                `dasi_fecha_creacion`,
                                `dasi_estado_logico`)
                                VALUES
                                ($casi_id,
                         --        1,
                                 $s5,
                                 's5',
                                 $usu_id,
                                 $estado,
                                 now(),
                                 1)";
        }else{
            $dasi_id = $res_s5['dasi_id'];
            $s5      = $data['s5'];
            $sql = "UPDATE db_academico.detalle_asistencia 
                       set dasi_cantidad = $s5
                     WHERE dasi_id = $dasi_id";
        }

       

        if($s5 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();

        }

         \app\models\Utilities::putMessageLogFile("control de errores");
        \app\models\Utilities::putMessageLogFile('insert u1: ' .$command->getRawSql());
        
        
       
        
          //Actualizamos la cabecera con los nuevos valores
        $sql = "UPDATE db_academico.cabecera_asistencia
                   SET casi_cant_total = (SELECT sum(dasi_cantidad) as casi_cant_total
                                            FROM db_academico.detalle_asistencia 
                                           where casi_id = $casi_id),
                       casi_porc_total = (SELECT round ((sum(dasi_cantidad) / 0.3),2) as casi_porc_total
                                            FROM db_academico.detalle_asistencia 
                                           where casi_id = $casi_id)
                WHERE casi_id = $casi_id
        ";
        
           $command = $con->createCommand($sql);
        $result  = $command->execute();
        //////////////////////////////////////////////////////
        //PRIMERO PREGUNTAMOS SI TIENE CABECERA PARA U3 Y U4//
        //////////////////////////////////////////////////////
        $sql = "SELECT casi.casi_id, casi.aeun_id, ecun.ecal_id
                  FROM db_academico.cabecera_asistencia casi
                       ,asistencia_esquema_unidad aeun
                       ,esquema_calificacion_unidad ecun
                 WHERE casi.aeun_id = aeun.aeun_id
                   and aeun.ecun_id = ecun.ecun_id
                   and casi.paca_id = ".$data['paca_id']."
                   and casi.est_id  = ".$data['est_id']."
                   and casi.pro_id  = ".$data['pro_id']."
                   and casi.asi_id  = ".$data['asi_id']."
                   and ecun.ecal_id = 2";
    
        $command  = $con->createCommand($sql);
        $res_casi = $command->queryOne();

        //PREGUNTO SI EXISTE EL REGSITRO
        if(empty($res_casi)){
            //por if true quiere decir q no existo, aqui debo crear primero la cabecera
            $sql = "INSERT INTO db_academico.cabecera_asistencia
                        (
                        `paca_id`,
                        `est_id`,
                        `pro_id`,
                        `asi_id`,
                        `aeun_id`,
                        `casi_cant_total`,
                        `casi_porc_total`,
                        `casi_estado`,
                        `casi_fecha_creacion`,
                        `casi_estado_logico`)
                        VALUES
                        (
                        ".$data['paca_id'].",
                        ".$data['est_id'].",
                        ".$data['pro_id'].",
                        ".$data['asi_id'].",
                        $aeun_id_u3_u4,
                        null,
                        null,
                        1,
                        now(),
                        1)";
            $command = $con->createCommand($sql);
            $result  = $command->execute();
            $casi_id = $con->getLastInsertID($con->dbname . '.cabecera_asistencia');

            //\app\models\Utilities::putMessageLogFile("ultimo id: ".$casi_id);
        }else
            $casi_id = $res_casi['casi_id'];
            
        ///////////////////////////////////////////////////////////////////
        //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA U2 //
        ///////////////////////////////////////////////////////////////////
        $sql = "SELECT dasi.dasi_id, dasi.casi_id
                  FROM db_academico.detalle_asistencia dasi
                 WHERE dasi.casi_id   = $casi_id 
                   AND dasi.dasi_tipo = 's6'";

        $command = $con->createCommand($sql);
        $res_s6  = $command->queryOne();

        //Pregunto si existe el detalle U1
        if(empty($res_s6)){
            $s6      = $data['s6'];
            $sql = "INSERT INTO db_academico.detalle_asistencia
                                (`casi_id`,
                         --        `ecal_id`,
                                `dasi_cantidad`,
                                `dasi_tipo`,
                                `dasi_usuario_creacion`,
                                `dasi_estado`,
                                `dasi_fecha_creacion`,
                                `dasi_estado_logico`)
                                VALUES
                                ($casi_id,
                        --         2,
                                 $s6,
                                 's6',
                                 $usu_id,
                                 $estado,
                                 now(),
                                 1)";
        }else{
            $dasi_id = $res_s6['dasi_id'];
            $s6      = $data['s6'];
            $sql = "UPDATE db_academico.detalle_asistencia 
                       set dasi_cantidad = $s6
                     WHERE dasi_id = $dasi_id";
        }

        if($s6 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();
        }     
        
             ///////////////////////////////////////////////////////////////////
        //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA S7 //
        ///////////////////////////////////////////////////////////////////
        $sql = "SELECT dasi.dasi_id, dasi.casi_id
                  FROM db_academico.detalle_asistencia dasi
                 WHERE dasi.casi_id   = $casi_id 
                   AND dasi.dasi_tipo = 's7'";

        $command = $con->createCommand($sql);
        $res_s7  = $command->queryOne();

        //Pregunto si existe el detalle U1
        if(empty($res_s7)){
            $s7      = $data['s7'];
            $sql = "INSERT INTO db_academico.detalle_asistencia
                                (`casi_id`,
                           --      `ecal_id`,
                                `dasi_cantidad`,
                                `dasi_tipo`,
                                `dasi_usuario_creacion`,
                                `dasi_estado`,
                                `dasi_fecha_creacion`,
                                `dasi_estado_logico`)
                                VALUES
                                ($casi_id,
                           --      2,
                                 $s7,
                                 's7',
                                 $usu_id,
                                 $estado,
                                 now(),
                                 1)";
        }else{
            $dasi_id = $res_s7['dasi_id'];
            $s7      = $data['s7'];
            $sql = "UPDATE db_academico.detalle_asistencia 
                       set dasi_cantidad = $s7
                     WHERE dasi_id = $dasi_id";
        }

        if($s7 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();
        }   
        
        
             ///////////////////////////////////////////////////////////////////
        //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA S8 //
        ///////////////////////////////////////////////////////////////////
        $sql = "SELECT dasi.dasi_id, dasi.casi_id
                  FROM db_academico.detalle_asistencia dasi
                 WHERE dasi.casi_id   = $casi_id 
                   AND dasi.dasi_tipo = 's8'";

        $command = $con->createCommand($sql);
        $res_s8  = $command->queryOne();

        //Pregunto si existe el detalle U1
        if(empty($res_s8)){
            $s8      = $data['s8'];
            $sql = "INSERT INTO db_academico.detalle_asistencia
                                (`casi_id`,
                         --        `ecal_id`,
                                `dasi_cantidad`,
                                `dasi_tipo`,
                                `dasi_usuario_creacion`,
                                `dasi_estado`,
                                `dasi_fecha_creacion`,
                                `dasi_estado_logico`)
                                VALUES
                                ($casi_id,
                       --          2,
                                 $s8,
                                 's8',
                                 $usu_id,
                                 $estado,
                                 now(),
                                 1)";
        }else{
            $dasi_id = $res_s8['dasi_id'];
            $s8      = $data['s8'];
            $sql = "UPDATE db_academico.detalle_asistencia 
                       set dasi_cantidad = $s8
                     WHERE dasi_id = $dasi_id";
        }

        if($s8 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();
        }   
        
        
             ///////////////////////////////////////////////////////////////////
        //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA S9 //
        ///////////////////////////////////////////////////////////////////
        $sql = "SELECT dasi.dasi_id, dasi.casi_id
                  FROM db_academico.detalle_asistencia dasi
                 WHERE dasi.casi_id   = $casi_id 
                   AND dasi.dasi_tipo = 's9'";

        $command = $con->createCommand($sql);
        $res_s9  = $command->queryOne();

        //Pregunto si existe el detalle U1
        if(empty($res_s9)){
            $s9      = $data['s9'];
            $sql = "INSERT INTO db_academico.detalle_asistencia
                                (`casi_id`,
                         --        `ecal_id`,
                                `dasi_cantidad`,
                                `dasi_tipo`,
                                `dasi_usuario_creacion`,
                                `dasi_estado`,
                                `dasi_fecha_creacion`,
                                `dasi_estado_logico`)
                                VALUES
                                ($casi_id,
                         --        2,
                                 $s9,
                                 's9',
                                 $usu_id,
                                 $estado,
                                 now(),
                                 1)";
        }else{
            $dasi_id = $res_s9['dasi_id'];
            $s9      = $data['s9'];
            $sql = "UPDATE db_academico.detalle_asistencia 
                       set dasi_cantidad = $s9
                     WHERE dasi_id = $dasi_id";
        }

        if($s9 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();
        }   
        
             ///////////////////////////////////////////////////////////////////
        //YA CON LA CABECERA VAMOS A VERIFICAR QUE TENGA DETALLE PARA S0 //
        ///////////////////////////////////////////////////////////////////
        $sql = "SELECT dasi.dasi_id, dasi.casi_id
                  FROM db_academico.detalle_asistencia dasi
                 WHERE dasi.casi_id   = $casi_id 
                   AND dasi.dasi_tipo = 's0'";

        $command = $con->createCommand($sql);
        $res_s0  = $command->queryOne();

        //Pregunto si existe el detalle U1
        if(empty($res_s0)){
            $s0      = $data['s0'];
            $sql = "INSERT INTO db_academico.detalle_asistencia
                                (`casi_id`,
                     --            `ecal_id`,
                                `dasi_cantidad`,
                                `dasi_tipo`,
                                `dasi_usuario_creacion`,
                                `dasi_estado`,
                                `dasi_fecha_creacion`,
                                `dasi_estado_logico`)
                                VALUES
                                ($casi_id,
                      --           2,
                                 $s0,
                                 's0',
                                 $usu_id,
                                 $estado,
                                 now(),
                                 1)";
        }else{
            $dasi_id = $res_s0['dasi_id'];
            $s0      = $data['s0'];
            $sql = "UPDATE db_academico.detalle_asistencia 
                       set dasi_cantidad = $s0
                     WHERE dasi_id = $dasi_id";
        }

        if($s0 != ''){
            $command = $con->createCommand($sql);
            $result  = $command->execute();
        }   
        
          //Actualizamos la cabecera con los nuevos valores
        $sql = "UPDATE db_academico.cabecera_asistencia
                   SET casi_cant_total = (SELECT sum(dasi_cantidad) as casi_cant_total
                                            FROM db_academico.detalle_asistencia 
                                           where casi_id = $casi_id),
                       casi_porc_total = (SELECT round ((sum(dasi_cantidad) / 0.3),2) as casi_porc_total
                                            FROM db_academico.detalle_asistencia 
                                           where casi_id = $casi_id)
                WHERE casi_id = $casi_id
        ";

        $command = $con->createCommand($sql);
        $result  = $command->execute();
        
        return true;
    }//actualizarnotaasistenciasemanal
    
        public function getAsistenciadin($arrFiltro){
        $con        = \Yii::$app->db_academico; 
        $con1       = \Yii::$app->db_asgard; 
        $str_search = "";
        $estado     = "1";

 ////////////////////////////////////////////////////////////////////////////////////////
        $modelpaca = new PeriodoAcademico();
        $daes = $modelpaca->getDaesbyperiodo($paca_id, $asi_id, $pro_id);
        $horasasignatura = $modelpaca->getHorasmaxAsistenciaxest($daes[0]['daes_id']);
        $sems = $horasasignatura['paca_semanas_periodo'];  $sems =10;
        $hours = $horasasignatura['daho_total_horas'];   $hours = 6;
        if ($arrFiltro['modalidad'] == 1){
     $sems =2; $hours = 30;
  }

       if ($arrFiltro['modalidad'] == 2){
     $sems =10; $hours = 6;
  }


/////////////////////////////////////////////////////////////////////////////////////////

        if (isset($arrFiltro) && count($arrFiltro) > 0) {

            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= " AND data.paca_id = :paca_id  ";
            }
            if ($arrFiltro['materia'] != "" && $arrFiltro['materia'] > 0) {
                $str_search .= " AND data.asi_id = :asi_id  ";
            }
            if ($arrFiltro['profesor'] != "" && $arrFiltro['profesor'] > 0) {
                $str_search .= " AND data.pro_id = :pro_id  ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= " AND data.uaca_id = :uaca_id  ";
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= " AND data.mod_id = :mod_id  ";
            } 
            
               
        } 
        $sql = "  SELECT (@row_number:=@row_number + 1) AS row_num,  data.*
                            FROM (
                  SELECT est.est_id
                        ,est.est_matricula as matricula
                        ,concat(per.per_pri_nombre,' ',per.per_pri_apellido) as nombre
                       -- ,coalesce(casi.casi_id,0) as casi_id
                      ,(SELECT ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'') AS value
                            FROM " . $con->dbname . ".semestre_academico AS saca
                            INNER JOIN " . $con->dbname . ".periodo_academico AS paca ON saca.saca_id = paca.saca_id
                            INNER JOIN " . $con->dbname . ".bloque_academico AS baca ON baca.baca_id = paca.baca_id
                            WHERE
                            paca.paca_id = :paca_id AND
                            paca.paca_activo = 'A' AND
                            paca.paca_estado = 1 AND
                            paca.paca_estado_logico = 1 AND
                            saca.saca_estado = 1 AND
                            saca.saca_estado_logico = 1 AND
                            baca.baca_estado = 1 AND
                            baca.baca_estado_logico = 1) as periodo 
                        ,(SELECT asi.asi_descripcion FROM " . $con->dbname . ".asignatura asi WHERE asi.asi_id = daca.asi_id) as materia
                       ";



 
              //////////////////////////////////////////////////////////////////////////////////
             for ($x = 0; $x< $sems; ++$x) {
             $xx = $x+1; if ($x==9){ $xx = 0;}
             $nombre ='s'.$xx;
              if ($arrFiltro['modalidad'] == 1){  $nombre ='u'.$xx; 

          }
            $componentes[$nombre] = array(
                'id'=> 's'.$xx ,
                'notamax'=>$hours,
            );
         if ($arrFiltro['modalidad'] == 1){ 

         $componentes[$nombre] = array(
                'id'=> 'u'.$xx ,
                'notamax'=>$hours,
            );

          }
        

           $sql .= "  
            
            ,(select dasi.dasi_cantidad from " . $con->dbname . ".detalle_asistencia dasi 
                              where dasi.casi_id = casi.casi_id
                             and dasi.dasi_tipo = '".$nombre."') as '".$nombre."'

            ";

            }
        //////////////////////////////////////////////////////////////////////////////////


            $sql .= "
                        ,daca.paca_id as paca_id
                        ,daca.asi_id  as asi_id
                        ,daca.pro_id  as pro_id
                        ,asi.uaca_id  as uaca_id
                        , daca.mod_id
                        , daes.daes_id
                        ,daho.daho_total_horas
                   FROM " . $con->dbname . ".distributivo_academico daca
             INNER JOIN " . $con->dbname . ".distributivo_academico_estudiante daes  ON daes.daca_id = daca.daca_id
             INNER JOIN " . $con->dbname . ".distributivo_academico_horario daho on daho.daho_id = daca.daho_id
              LEFT JOIN " . $con->dbname . ".estudiante est                          ON est.est_id = daes.est_id
                    AND est.est_estado = :estado
                    AND est.est_estado_logico = :estado
             INNER JOIN " . $con1->dbname. ".persona per                             ON per.per_id   = est.per_id
              LEFT JOIN " . $con->dbname . ".cabecera_asistencia casi              
                     ON casi.est_id  = est.est_id 
                    AND casi.asi_id  = daca.asi_id
                    AND casi.pro_id  = daca.pro_id
                    AND casi.paca_id = daca.paca_id
                    AND casi.casi_estado = :estado
                    AND casi.casi_estado_logico = :estado
              LEFT JOIN " . $con->dbname . ".asistencia_esquema_unidad aeun   ON aeun.aeun_id = casi.aeun_id
              LEFT JOIN " . $con->dbname . ".esquema_calificacion_unidad ecun ON ecun.ecun_id = aeun.ecun_id 
              LEFT JOIN " . $con->dbname . ".asignatura asi                   ON asi.asi_id = daca.asi_id
            order by 3 asc
            ) as data 
            ,(SELECT @row_number:=0) AS t
           WHERE 1=1 
                 $str_search  
        group by matricula, nombre, est_id, pro_id, materia, asi_id
         ORDER BY nombre ASC
        ";


      

        $comando = $con->createCommand($sql);

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR); 

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
         
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $unidad = $arrFiltro["unidad"];
                $comando->bindParam(":uaca_id", $unidad, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }  
            
            if ($arrFiltro['materia'] != "" && $arrFiltro['materia'] > 0) {
                $materia = $arrFiltro["materia"];
                $comando->bindParam(":asi_id", $materia, \PDO::PARAM_INT);
            }
           /* if ($arrFiltro['parcial'] != "" && $arrFiltro['parcial'] > 0) {
                $parcial = $arrFiltro["parcial"];
                $comando->bindParam(":ecal_id", $parcial, \PDO::PARAM_INT);
            } */
            if ($arrFiltro['profesor'] != "" && $arrFiltro['profesor'] > 0) {
                $profesor = $arrFiltro["profesor"];
                $comando->bindParam(":pro_id", $profesor, \PDO::PARAM_INT);
            }
        }

        $res = $comando->queryAll();

        \app\models\Utilities::putMessageLogFile('getAsistencia: ' .$comando->getRawSql());
        return $res;
    }//function getAsistenciadin



    /**
     * Actualizar registro en la tabla detalle_calificacion
     * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function actualizarDetalleporcomponente($ccal_id,$key , $value){
        $con    = \Yii::$app->db_academico;
        $estado = '1';
        //$usu_id = @Yii::$app->session->get("PB_iduser");
        //$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        
        //$com_nombre = array_key_first($value);    
        $sql = "UPDATE db_academico.detalle_calificacion
                   SET dcal_calificacion = $value
                 WHERE dcal_id = (
                       SELECT valor FROM(
                              SELECT dc1.dcal_id as valor
                                from db_academico.detalle_calificacion dc1,
                                     db_academico.componente_unidad cu1,
                                     db_academico.componente com1
                               where dc1.ccal_id = $ccal_id
                                 and dc1.cuni_id = cu1.cuni_id
                                 and cu1.com_id = com1.com_id
                                 and com1.com_nombre = '$key'
                               ) AS alias_tabla1
                );";
        
        $command = $con->createCommand($sql);
        //$command->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);             
        //$command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $idtabla= $command->execute();  

        \app\models\Utilities::putMessageLogFile('actualizarDetalleCalificacionporcomponente: '.$command->getRawSql());
        return $idtabla;
    }//function actualizarDetalleporcomponente


        /**
     * Actualizar registro en la tabla detalle_calificacion
     * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function crearCabeceraporcomponente($paca_id,$est_id,$pro_id,$asi_id,$ecal_id,$uaca_id){
        $con    = \Yii::$app->db_academico;
        $transaccion = $con->beginTransaction();
        //$estado = '1';
        //$usu_id = @Yii::$app->session->get("PB_iduser");
        
        //$com_nombre = array_key_first($value);    
        $sql = "INSERT INTO " . $con->dbname . ".cabecera_calificacion
                            (
                              `paca_id`,
                              `est_id`,
                              `pro_id`,
                              `asi_id`,
                              `ecun_id`,
                              `ccal_calificacion`,
                              `ccal_estado`,
                              `ccal_fecha_creacion`,
                              `ccal_estado_logico`
                            )
                     VALUES(
                              $paca_id,
                              $est_id,
                              $pro_id,
                              $asi_id,
                              (select ecun_id FROM esquema_calificacion_unidad WHERE ecal_id = $ecal_id and uaca_id = $uaca_id),
                              0,
                              1,
                              now(),
                              1
                )";
        
        $command = $con->createCommand($sql);
        //$command->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);             
        //$command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $idtabla= $command->execute();  

        $idtable = $con->getLastInsertID($con->dbname . '.cabecera_calificacion');

        $transaccion->commit();

        \app\models\Utilities::putMessageLogFile('Crear Cabecera: '.$command->getRawSql());
        return $idtable;
    }//function actualizarDetalleCalificacionporid

        /**
     * Actualizar registro en la tabla detalle_calificacion
     * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function crearDetalleporcomponente($ccal_id,$key,$value,$uaca_id,$mod_id,$ecal_id){
        $con    = \Yii::$app->db_academico;
        $estado = '1';
        $usu_id = @Yii::$app->session->get("PB_iduser");
        
        //$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        if($value == ''){
        
            //$com_nombre = array_key_first($value);    
            $sql = "INSERT INTO `db_academico`.`detalle_calificacion`
                                (
                                   `ccal_id`,
                                   `cuni_id`,
                                   `dcal_usuario_creacion`,
                                   `dcal_estado`,
                                   `dcal_fecha_creacion`,
                                   `dcal_estado_logico`
                                )
                         VALUES (
                                    $ccal_id,
                                    (SELECT cuni.cuni_id
                                       from db_academico.componente_unidad cuni,
                                            db_academico.componente com
                                      where cuni.com_id    = com.com_id
                                        and com.com_nombre = '$key'
                                        and cuni.uaca_id   = $uaca_id
                                        and cuni.mod_id    = $mod_id
                                        /*and cuni.ecal_id   = $ecal_id*/),
                                    $usu_id,
                                    1,
                                    now(),
                                    1
                                )
            ";
        }else{
            //$com_nombre = array_key_first($value);    
            $sql = "INSERT INTO `db_academico`.`detalle_calificacion`
                                (
                                   `ccal_id`,
                                   `cuni_id`,
                                   `dcal_calificacion`,
                                   `dcal_usuario_creacion`,
                                   `dcal_estado`,
                                   `dcal_fecha_creacion`,
                                   `dcal_estado_logico`
                                )
                         VALUES (
                                    $ccal_id,
                                    (SELECT cuni.cuni_id
                                       from db_academico.componente_unidad cuni,
                                            db_academico.componente com
                                      where cuni.com_id = com.com_id
                                        and com.com_nombre = '$key'
                                        and cuni.uaca_id = $uaca_id
                                        and cuni.mod_id    = $mod_id
                                        /*and cuni.ecal_id   = $ecal_id*/),
                                    $value,
                                    $usu_id,
                                    1,
                                    now(),
                                    1
                                )
            ";
        }
        $command = $con->createCommand($sql);
        //$command->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);             
        //$command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $idtabla= $command->execute();  

        \app\models\Utilities::putMessageLogFile('Crear Detalle: '.$command->getRawSql());
        return $idtabla;
    }//function actualizarDetalleCalificacionporid
 
    /**
     * Function consulta cabecera_asistencia por medio de 4 IDs
     * @author Diego Betancourt <analistadesarrollo08@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarAlumnosxSesion($arrFiltro){
        $con = Yii::$app->db_academico;
        $str_search = "";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {

            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= " AND daca.paca_id = :paca_id  ";
            }
            if ($arrFiltro['materia'] != "" && $arrFiltro['materia'] > 0) {
                $str_search .= " AND daca.asi_id = :asi_id  ";
            }
            if ($arrFiltro['profesor'] != "" && $arrFiltro['profesor'] > 0) {
                $str_search .= " AND daca.pro_id = :pro_id  ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= " AND daca.uaca_id = :uaca_id  ";
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= " AND daca.mod_id = :mod_id  ";
            } 
            $sesion = $arrFiltro['sesion']; 
            $parcial = $arrFiltro['parcial'];
               
        } 
        $sql = "SELECT x.* from(
                SELECT per.per_id as id, est.est_matricula as matricula, 
                replace(concat(ifnull(per.per_pri_apellido,''),' ',ifnull(per.per_pri_nombre,'')),'',null) as nombre,
                daca.paca_id as paca_id
                                        ,asi.asi_nombre as materia
                                        ,daca.asi_id  as asi_id
                                        ,daca.pro_id  as pro_id
                                        ,asi.uaca_id  as uaca_id
                                        , daca.mod_id as mod_id
                                        , est.est_id as est_id
                                        ,daho.daho_id as daho_id
                                        ,ifnull($sesion,0) as sesion
                                        ,ifnull($parcial,1) as parcial
                                        ,'checked' as stado
                FROM db_academico.distributivo_academico daca
                            INNER JOIN db_academico.distributivo_academico_estudiante daes  ON daes.daca_id = daca.daca_id
                            INNER JOIN db_academico.distributivo_academico_horario daho on daho.daho_id = daca.daho_id
                            LEFT JOIN db_academico.estudiante est                          ON est.est_id = daes.est_id
                                    AND est.est_estado = '1' AND est.est_estado_logico = '1'
                            inner join db_academico.detalle_asistencia_clases dac on daca.asi_id = dac.asi_id
                            and daca.paca_id = dac.paca_id and daca.mod_id = dac.mod_id and est.est_id = dac.est_id
                            and dac.pro_id = dac.pro_id and  daca.daho_id = dac.daho_id
                            INNER JOIN db_asgard.persona per                             ON per.per_id   = est.per_id
                            inner JOIN db_academico.asignatura asi                   ON asi.asi_id = daca.asi_id
                            where 1=1 
                                $str_search 
                union 

                SELECT per.per_id as id, est.est_matricula as matricula, 
                replace(concat(ifnull(per.per_pri_apellido,''),' ',ifnull(per.per_pri_nombre,'')),'',null) as nombre,
                daca.paca_id as paca_id
                                        ,asi.asi_nombre as materia
                                        ,daca.asi_id  as asi_id
                                        ,daca.pro_id  as pro_id
                                        ,asi.uaca_id  as uaca_id
                                        , daca.mod_id as mod_id
                                        , est.est_id as est_id
                                        ,daho.daho_id as daho_id
                                        ,ifnull($sesion,0) as sesion
                                        ,ifnull($parcial,1) as parcial
                                        ,'' as stado
                FROM db_academico.distributivo_academico daca
                            INNER JOIN db_academico.distributivo_academico_estudiante daes  ON daes.daca_id = daca.daca_id
                            INNER JOIN db_academico.distributivo_academico_horario daho on daho.daho_id = daca.daho_id
                            LEFT JOIN db_academico.estudiante est                          ON est.est_id = daes.est_id
                            AND est.est_estado = '1' AND est.est_estado_logico = '1'
                            left join db_academico.detalle_asistencia_clases dac on daca.asi_id = dac.asi_id
                            and daca.paca_id = dac.paca_id and daca.mod_id = dac.mod_id and est.est_id = dac.est_id
                            and dac.pro_id = dac.pro_id and  daca.daho_id = dac.daho_id
                            INNER JOIN db_asgard.persona per                             ON per.per_id   = est.per_id
                            inner JOIN db_academico.asignatura asi                   ON asi.asi_id = daca.asi_id
                            where 1=1 
                                $str_search ) as x
                            order by x.nombre asc";

        $comando = $con->createCommand($sql);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
         
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $unidad = $arrFiltro["unidad"];
                $comando->bindParam(":uaca_id", $unidad, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }  
            
            if ($arrFiltro['materia'] != "" && $arrFiltro['materia'] > 0) {
                $materia = $arrFiltro["materia"];
                $comando->bindParam(":asi_id", $materia, \PDO::PARAM_INT);
            }

            if ($arrFiltro['profesor'] != "" && $arrFiltro['profesor'] > 0) {
                $profesor = $arrFiltro["profesor"];
                $comando->bindParam(":pro_id", $profesor, \PDO::PARAM_INT);
            }
        }

        $resultData = $comando->queryAll();
        \app\models\Utilities::putMessageLogFile('consultarAlumnosxSesion: '.$comando->getRawSql());
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
        ]);

        return $resultData;
        // return $dataProvider;
    }

    public function consultarAsistenciaInicialFinal($rmtm_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT count(rmtm_id)
            FROM " . $con->dbname . ".detalle_asistencia_clases 
            WHERE rmtm_id = $rmtm_id
            AND deac_hora_fin_clase is not null
            AND deac_minutos_retiro is not null
            AND deac_estado = $estado  
            AND deac_hora_fin_clase is not null;"; 

        $comando = $con->createCommand($sql);
        // $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);      
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultarExisteCabecera($paca_id,$est_id,$pro_id,$asi_id,$parcial) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT count(casi_id) as cant from db_academico.cabecera_asistencia 
                where paca_id = $paca_id and est_id = $est_id and pro_id = $pro_id and asi_id = $asi_id
                and casi_estado = $estado and aeun_id = $parcial and casi_estado_logico = $estado;"; 

        $comando = $con->createCommand($sql);
        // $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);      
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultarIdCabecera($paca_id,$est_id,$pro_id,$asi_id,$parcial) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT casi_id from db_academico.cabecera_asistencia 
                where paca_id = $paca_id and est_id = $est_id and pro_id = $pro_id and asi_id = $asi_id
                and aeun_id = $parcial and casi_estado = $estado and casi_estado_logico = $estado;"; 

        $comando = $con->createCommand($sql);
        // $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);      
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function crearIdCabecera($paca_id,$est_id,$pro_id,$asi_id,$parcial,$porcentaje) {
        $con    = \Yii::$app->db_academico;
        $transaccion = $con->beginTransaction();
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        //$usu_id = @Yii::$app->session->get("PB_iduser");
        
        //$com_nombre = array_key_first($value);    
        $sql = "INSERT INTO " . $con->dbname . ".cabecera_asistencia
                            (
                              `paca_id`,
                              `est_id`,
                              `pro_id`,
                              `asi_id`,
                              `aeun_id`,
                              `casi_porc_total`,
                              `casi_estado`,
                              `casi_fecha_creacion`,
                              `casi_estado_logico`
                            )
                     VALUES(
                              $paca_id,
                              $est_id,
                              $pro_id,
                              $asi_id,
                              $parcial,
                              $porcentaje,
                              1,
                              '$fecha_transaccion',
                              1
                )";
        
        $command = $con->createCommand($sql);
        //$command->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);             
        //$command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $idtabla= $command->execute();  

        $idtable = $con->getLastInsertID($con->dbname . '.cabecera_calificacion');

        $transaccion->commit();

        \app\models\Utilities::putMessageLogFile('crearIdCabecera: '.$command->getRawSql());
        return $idtable;
    }
    public function actualizarAsistenciaCabecera($casi_id,$porcentaje) {
        $con    = \Yii::$app->db_academico;
        $transaccion = $con->beginTransaction();
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        //$usu_id = @Yii::$app->session->get("PB_iduser");
        
        //$com_nombre = array_key_first($value);    
        $sql = "UPDATE " . $con->dbname . ".cabecera_asistencia
                set casi_porc_total = truncate((casi_porc_total + $porcentaje),2),
                casi_fecha_modificacion = '$fecha_transaccion'
                WHERE  casi_id = $casi_id and casi_estado = 1;";
        
        $command = $con->createCommand($sql);
        //$command->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);             
        //$command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $resultData= $command->execute();  

        $transaccion->commit();

        \app\models\Utilities::putMessageLogFile('actualizarAsistenciaCabecera: '.$command->getRawSql());
        return $idtable;
    }

    public function consultarIdDetalle($casi_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT count(casi_id) from db_academico.detalle_asistencia 
                where casi_id = $casi_id and dasi_estado = $estado and dasi_estado_logico = $estado;"; 

        $comando = $con->createCommand($sql);
        // $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);      
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function crearIdDetalle($casi_id,$dasi_tipo,$cantidad) {
        $con    = \Yii::$app->db_academico;
        $transaccion = $con->beginTransaction();
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $usu_id = @Yii::$app->session->get("PB_iduser");
        
        //$com_nombre = array_key_first($value);    
        $sql = "INSERT INTO " . $con->dbname . ".detalle_asistencia
                            (
                              `casi_id`,
                              `dasi_tipo`,
                              `dasi_cantidad`,
                              `dasi_usuario_creacion`,
                              `dasi_estado`,
                              `dasi_fecha_creacion`,
                              `dasi_estado_logico`
                            )
                     VALUES(
                              $casi_id,
                              concat('p',$dasi_tipo),
                              $cantidad,
                              $usu_id,
                              1,
                              '$fecha_transaccion',
                              1
                )";
        
        $command = $con->createCommand($sql);
        //$command->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);             
        //$command->bindParam(":fecha", $fecha_transaccion, \PDO::PARAM_STR);
        $idtabla= $command->execute();  

        $idtable = $con->getLastInsertID($con->dbname . '.cabecera_calificacion');

        $transaccion->commit();

        \app\models\Utilities::putMessageLogFile('crearIdCabecera: '.$command->getRawSql());
        return $idtable;
    }
}
