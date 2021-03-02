<?php

namespace app\modules\marketing\models;

use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "suscriptor".
 *
 * @property int $sus_id
 * @property int $per_id
 * @property int $pges_id
 * @property string $sus_estado
 * @property string $sus_fecha_creacion
 * @property string $sus_fecha_modificacion
 * @property string $sus_estado_logico
 *
 * @property BitacoraEnvio[] $bitacoraEnvios
 * @property ListaSuscriptor[] $listaSuscriptors
 */
class Suscriptor extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'suscriptor';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_mailing');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['per_id', 'pges_id'], 'integer'],
            [['sus_estado', 'sus_estado_logico'], 'required'],
            [['sus_fecha_creacion', 'sus_fecha_modificacion'], 'safe'],
            [['sus_estado', 'sus_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'sus_id' => 'Sus ID',
            'per_id' => 'Per ID',
            'pges_id' => 'Pges ID',
            'sus_estado' => 'Sus Estado',
            'sus_fecha_creacion' => 'Sus Fecha Creacion',
            'sus_fecha_modificacion' => 'Sus Fecha Modificacion',
            'sus_estado_logico' => 'Sus Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBitacoraEnvios() {
        return $this->hasMany(BitacoraEnvio::className(), ['sus_id' => 'sus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListaSuscriptors() {
        return $this->hasMany(ListaSuscriptor::className(), ['sus_id' => 'sus_id']);
    }

    public function insertarSuscritor($con, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $param_sql .= "" . $keys[0];
        $bdet_sql .= "'" . $parameters[0] . "'";
        for ($i = 1; $i < count($parameters); $i++) {
            if (isset($parameters[$i])) {
                $param_sql .= ", " . $keys[$i];
                $bdet_sql .= ", '" . $parameters[$i] . "'";
            }
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . '.' . $name_table . " ($param_sql) VALUES($bdet_sql);";
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.' . $name_table);
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function consultarSuscriptoresxLista
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    public function consultarSuscriptoresxLista($arrFiltro = array(), $list_id, $subscrito = 0, $onlyData = false) {
        $con = \Yii::$app->db_mailing;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_academico;
        $con3 = \Yii::$app->db_crm;
        $con4 = \Yii::$app->db_captacion;
        $estado = 1;
        $str_search = '';
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['estado'] == 1) {  //suscritos
                $str_search = " AND ifnull(sus.sus_id,0) > 0 and sus.sus_estado ='1' and ls.lsus_estado = '1' and ls.lsus_estado_mailchimp IS NULL";
            }
            if ($arrFiltro['estado'] == 3) {  //Mailchimp
                $str_search = " AND (ifnull(ls.lsus_estado_mailchimp,0) = '1' and sus.sus_estado = '1' and ls.lsus_estado = '1') ";
            }
        }
        if ($subscrito == 0) {
            $sql = "
                SELECT -- suscritos
                        lst.lis_id,
                        IFNULL(per.per_id, 0) per_id,
                        0 id_pges,	
                        CONCAT(per.per_pri_nombre,' ',per.per_pri_apellido) AS contacto,
                        IF(ISNULL(mest.mest_nombre),
                                eaca.eaca_nombre,
                                mest.mest_nombre) carrera,
                        per_correo,
                        acon.acon_id,
                        acon.acon_nombre,
                        ifnull(ls.lsus_estado_mailchimp,0) as estado_mailchimp,
                        if(ifnull(ls.sus_id,0)>0 and ls.lis_id = :list_id and ls.lsus_estado =1,1,0) as estado
                FROM
                        db_mailing.lista lst
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        JOIN db_mailing.lista_suscriptor ls ON ls.lis_id = lst.lis_id
                        JOIN db_mailing.suscriptor AS sus ON sus.sus_id = ls.sus_id
                        JOIN db_asgard.persona per ON per.per_id = sus.per_id
                WHERE
                        lst.lis_id = :list_id                    
                        and ls.lsus_estado=1
                        and ifnull(ls.lsus_estado_mailchimp,0)=0
                        AND lst.lis_estado = 1
                        AND lst.lis_estado_logico = 1                
                UNION
                SELECT -- mailchimp
                        lst.lis_id,
                        IFNULL(per.per_id, 0) per_id,
                        0 id_pges,
                        CONCAT(per.per_pri_nombre,' ',per.per_pri_apellido) AS contacto,
                        IF(ISNULL(mest.mest_nombre),
                                eaca.eaca_nombre,
                                mest.mest_nombre) carrera,
                        per_correo,
                        acon.acon_id,
                        acon.acon_nombre,
                        ifnull(ls.lsus_estado_mailchimp,0) as estado_mailchimp,
                        if(ifnull(ls.sus_id,0)>0 and ls.lis_id = :list_id and ls.lsus_estado =1,1,0) as estado
                FROM
                        db_mailing.lista lst
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        JOIN db_mailing.lista_suscriptor ls ON ls.lis_id = lst.lis_id
                        JOIN db_mailing.suscriptor AS sus ON sus.sus_id = ls.sus_id
                        JOIN db_asgard.persona per ON per.per_id = sus.per_id
                WHERE
                        lst.lis_id = :list_id
                        AND (ifnull(ls.lsus_estado_mailchimp,0) = '1' and sus.sus_estado = '1' and ls.lsus_estado = '1') 
                        AND lst.lis_estado = 1
                        AND lst.lis_estado_logico = 1                
                UNION
                SELECT -- no suscritos
                        lst.lis_id,
                        IFNULL(per.per_id, 0) per_id,
                        0 id_pges,
                        CONCAT(per.per_pri_nombre,' ',per.per_pri_apellido) AS contacto,
                        IF(ISNULL(mest.mest_nombre),
                                eaca.eaca_nombre,
                                mest.mest_nombre) carrera,
                        per_correo,
                        acon.acon_id,
                        acon.acon_nombre,
                        0 as estado_mailchimp,
                        0 as estado
                FROM
                        db_mailing.lista lst
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        left join db_captacion.solicitud_inscripcion AS sins ON (sins.eaca_id = eaca.eaca_id OR sins.mest_id = mest.mest_id)
                        LEFT JOIN db_captacion.interesado i ON i.int_id = sins.int_id
                        JOIN db_asgard.persona per ON per.per_id = i.per_id	
                        WHERE
                                lst.lis_id = :list_id
                                and lst.lis_estado = 1
                                and lst.lis_estado_logico = 1
                                and  per.per_id 
                                not in(
                                           select sus.per_id 
                                                from db_mailing.lista_suscriptor as ls
                                                join db_mailing.suscriptor as sus on sus.sus_id=ls.sus_id
                                           where lis_id =:list_id
                                           and ls.lsus_estado=1
                                           )
                UNION -- persona gestion
                SELECT -- suscritos
                        lst.lis_id,
                        0 per_id,
                        IFNULL(pges.pges_id, 0) id_pges,
                        CONCAT(pges.pges_pri_nombre,' ',IFNULL(pges.pges_pri_apellido, ''))	AS contacto,
                        IF(ISNULL(mest.mest_nombre),
                                eaca.eaca_nombre,
                                mest.mest_nombre) carrera,
                        pges.pges_correo as per_correo,
                        acon.acon_id,
                        acon.acon_nombre,
                        ifnull(ls.lsus_estado_mailchimp,0) as estado_mailchimp,
                        if(ifnull(ls.sus_id,0)>0 and ls.lis_id = :list_id and ls.lsus_estado =1,1,0) as estado
                FROM
                        db_mailing.lista lst
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        JOIN db_mailing.lista_suscriptor ls ON ls.lis_id = lst.lis_id
                        JOIN db_mailing.suscriptor AS sus ON sus.sus_id = ls.sus_id
                        join db_crm.persona_gestion AS pges ON pges.pges_id = sus.pges_id
                WHERE
                        lst.lis_id = :list_id
                        and ls.lsus_estado=1
                        and ifnull(ls.lsus_estado_mailchimp,0)=0
                        AND lst.lis_estado = 1
                        AND lst.lis_estado_logico = 1                
                UNION
                SELECT -- mailchimp
                        lst.lis_id,
                        0 per_id,
                        IFNULL(pges.pges_id, 0) id_pges,
                        CONCAT(pges.pges_pri_nombre,' ',IFNULL(pges.pges_pri_apellido, '')) AS contacto,
                        IF(ISNULL(mest.mest_nombre),
                                eaca.eaca_nombre,
                                mest.mest_nombre) carrera,
                        pges.pges_correo as per_correo,
                        acon.acon_id,
                        acon.acon_nombre,
                        ifnull(ls.lsus_estado_mailchimp,0) as estado_mailchimp,
                        if(ifnull(ls.sus_id,0)>0 and ls.lis_id = :list_id and ls.lsus_estado =1,1,0) as estado
                FROM
                    db_mailing.lista lst
                    left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                    left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                    left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                    JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                    JOIN db_mailing.lista_suscriptor ls ON ls.lis_id = lst.lis_id
                    JOIN db_mailing.suscriptor AS sus ON sus.sus_id = ls.sus_id
                    join db_crm.persona_gestion AS pges ON pges.pges_id = sus.pges_id
            WHERE
                    lst.lis_id = :list_id
                    AND (ifnull(ls.lsus_estado_mailchimp,0) = '1' and sus.sus_estado = '1' and ls.lsus_estado = '1') 
                    AND lst.lis_estado = 1
                    AND lst.lis_estado_logico = 1                
            UNION
            SELECT -- no suscritos
                    lst.lis_id,
                    0 per_id,
                    IFNULL(pges.pges_id, 0) id_pges,
                    CONCAT(pges.pges_pri_nombre,' ',IFNULL(pges.pges_pri_apellido, '')) AS contacto,
                    IF(ISNULL(mest.mest_nombre),
                            eaca.eaca_nombre,
                            mest.mest_nombre) carrera,
                    pges.pges_correo as per_correo,
                    acon.acon_id,
                    acon.acon_nombre,
                    0 as estado_mailchimp,
                    0 as estado
            FROM
                    db_mailing.lista lst
                    left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                    left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                    left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                    JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                    left join db_crm.oportunidad AS opo ON (opo.eaca_id = eaca.eaca_id OR opo.mest_id = mest.mest_id)
                    JOIN db_crm.persona_gestion AS pges ON pges.pges_id = opo.pges_id                                
            WHERE
		lst.lis_id = :list_id
		and lst.lis_estado = 1
		and lst.lis_estado_logico = 1
		and pges.pges_id 
		not in(
			   select sus.pges_id 
				from db_mailing.lista_suscriptor as ls
				join db_mailing.suscriptor as sus on sus.sus_id=ls.sus_id
			   where lis_id =:list_id
                           and ls.lsus_estado=1
			   )                      
                and pges.pges_correo
                    not in(
                        select per_correo
                        from db_asgard.persona as per
                        where per.per_estado=1 and per.per_estado_logico=1 and per_correo = pges.pges_correo
                    )
            ";
        } else if ($subscrito == 1) {
            $sql = "
                SELECT -- suscritos
                        lst.lis_id,
                        IFNULL(per.per_id, 0) per_id,
                        0 id_pges,	
                        CONCAT(per.per_pri_nombre,' ',per.per_pri_apellido) AS contacto,
                        IF(ISNULL(mest.mest_nombre),
                                eaca.eaca_nombre,
                                mest.mest_nombre) carrera,
                        per_correo,
                        acon.acon_id,
                        acon.acon_nombre,
                        ifnull(ls.lsus_estado_mailchimp,0) as estado_mailchimp,
                        if(ifnull(ls.sus_id,0)>0 and ls.lis_id = :list_id and ls.lsus_estado =1,1,0) as estado
                FROM
                        db_mailing.lista lst
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        JOIN db_mailing.lista_suscriptor ls ON ls.lis_id = lst.lis_id
                        JOIN db_mailing.suscriptor AS sus ON sus.sus_id = ls.sus_id
                        JOIN db_asgard.persona per ON per.per_id = sus.per_id
                WHERE
                        lst.lis_id = :list_id                    
                        and ls.lsus_estado=1
                        and ifnull(ls.lsus_estado_mailchimp,0)=0
                        AND lst.lis_estado = 1
                        AND lst.lis_estado_logico = 1                
                UNION -- persona gestion
                SELECT -- suscritos
                        lst.lis_id,
                        0 per_id,
                        IFNULL(pges.pges_id, 0) id_pges,
                        CONCAT(pges.pges_pri_nombre,' ',IFNULL(pges.pges_pri_apellido, ''))	AS contacto,
                        IF(ISNULL(mest.mest_nombre),
                                eaca.eaca_nombre,
                                mest.mest_nombre) carrera,
                        pges.pges_correo as per_correo,
                        acon.acon_id,
                        acon.acon_nombre,
                        ifnull(ls.lsus_estado_mailchimp,0) as estado_mailchimp,
                        if(ifnull(ls.sus_id,0)>0 and ls.lis_id = :list_id and ls.lsus_estado =1,1,0) as estado
                FROM
                        db_mailing.lista lst
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        JOIN db_mailing.lista_suscriptor ls ON ls.lis_id = lst.lis_id
                        JOIN db_mailing.suscriptor AS sus ON sus.sus_id = ls.sus_id
                        join db_crm.persona_gestion AS pges ON pges.pges_id = sus.pges_id
                WHERE
                        lst.lis_id = :list_id
                        and ls.lsus_estado=1
                        and ifnull(ls.lsus_estado_mailchimp,0)=0
                        AND lst.lis_estado = 1
                        AND lst.lis_estado_logico = 1                                
            ";
        } else if ($subscrito == 2) {
            $sql = "
                SELECT -- no suscritos
                        lst.lis_id,
                        IFNULL(per.per_id, 0) per_id,
                        0 id_pges,
                        CONCAT(per.per_pri_nombre,' ',per.per_pri_apellido) AS contacto,
                        IF(ISNULL(mest.mest_nombre),
                                eaca.eaca_nombre,
                                mest.mest_nombre) carrera,
                        per_correo,
                        acon.acon_id,
                        acon.acon_nombre,
                        0 as estado_mailchimp,
                        0 as estado
                FROM
                        db_mailing.lista lst
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        left join db_captacion.solicitud_inscripcion AS sins ON (sins.eaca_id = eaca.eaca_id OR sins.mest_id = mest.mest_id)
                        LEFT JOIN db_captacion.interesado i ON i.int_id = sins.int_id
                        JOIN db_asgard.persona per ON per.per_id = i.per_id	
                        WHERE
                                lst.lis_id = :list_id
                                and lst.lis_estado = 1
                                and lst.lis_estado_logico = 1
                                and  per.per_id 
                                not in(
                                           select sus.per_id 
                                                from db_mailing.lista_suscriptor as ls
                                                join db_mailing.suscriptor as sus on sus.sus_id=ls.sus_id
                                           where lis_id =:list_id
                                           and ls.lsus_estado=1
                                           )
                UNION -- persona gestion
                SELECT -- no suscritos
                    lst.lis_id,
                    0 per_id,
                    IFNULL(pges.pges_id, 0) id_pges,
                    CONCAT(pges.pges_pri_nombre,' ',IFNULL(pges.pges_pri_apellido, '')) AS contacto,
                    IF(ISNULL(mest.mest_nombre),
                            eaca.eaca_nombre,
                            mest.mest_nombre) carrera,
                    pges.pges_correo as per_correo,
                    acon.acon_id,
                    acon.acon_nombre,
                    0 as estado_mailchimp,
                    0 as estado
                FROM
                        db_mailing.lista lst
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        left join db_crm.oportunidad AS opo ON (opo.eaca_id = eaca.eaca_id OR opo.mest_id = mest.mest_id)
                        JOIN db_crm.persona_gestion AS pges ON pges.pges_id = opo.pges_id                                
                WHERE
                    lst.lis_id = :list_id
                    and lst.lis_estado = 1
                    and lst.lis_estado_logico = 1
                    and pges.pges_id 
                    not in(
                               select sus.pges_id 
                                    from db_mailing.lista_suscriptor as ls
                                    join db_mailing.suscriptor as sus on sus.sus_id=ls.sus_id
                               where lis_id =:list_id
                               and ls.lsus_estado=1
                               )      
                    and pges.pges_correo
                    not in(
                        select per_correo
                        from db_asgard.persona as per
                        where per.per_estado=1 and per.per_estado_logico=1 and per_correo=pges.pges_correo
                    )
            ";
        } else if ($subscrito == 3) {
            $sql = "
                SELECT -- mailchimp
                        lst.lis_id,
                        IFNULL(per.per_id, 0) per_id,
                        0 id_pges,
                        CONCAT(per.per_pri_nombre,' ',per.per_pri_apellido) AS contacto,
                        IF(ISNULL(mest.mest_nombre),
                                eaca.eaca_nombre,
                                mest.mest_nombre) carrera,
                        per_correo,
                        acon.acon_id,
                        acon.acon_nombre,
                        ifnull(ls.lsus_estado_mailchimp,0) as estado_mailchimp,
                        if(ifnull(ls.sus_id,0)>0 and ls.lis_id = :list_id and ls.lsus_estado =1,1,0) as estado
                FROM
                        db_mailing.lista lst
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        JOIN db_mailing.lista_suscriptor ls ON ls.lis_id = lst.lis_id
                        JOIN db_mailing.suscriptor AS sus ON sus.sus_id = ls.sus_id
                        JOIN db_asgard.persona per ON per.per_id = sus.per_id
                WHERE
                        lst.lis_id = :list_id
                        AND (ifnull(ls.lsus_estado_mailchimp,0) = '1' and sus.sus_estado = '1' and ls.lsus_estado = '1') 
                        AND lst.lis_estado = 1
                        AND lst.lis_estado_logico = 1                
                UNION -- persona gestion
                SELECT -- mailchimp
                        lst.lis_id,
                        0 per_id,
                        IFNULL(pges.pges_id, 0) id_pges,
                        CONCAT(pges.pges_pri_nombre,' ',IFNULL(pges.pges_pri_apellido, '')) AS contacto,
                        IF(ISNULL(mest.mest_nombre),
                                eaca.eaca_nombre,
                                mest.mest_nombre) carrera,
                        pges.pges_correo as per_correo,
                        acon.acon_id,
                        acon.acon_nombre,
                        ifnull(ls.lsus_estado_mailchimp,0) as estado_mailchimp,
                        if(ifnull(ls.sus_id,0)>0 and ls.lis_id = :list_id and ls.lsus_estado =1,1,0) as estado
                FROM
                    db_mailing.lista lst
                    left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                    left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                    left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                    JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                    JOIN db_mailing.lista_suscriptor ls ON ls.lis_id = lst.lis_id
                    JOIN db_mailing.suscriptor AS sus ON sus.sus_id = ls.sus_id
                    join db_crm.persona_gestion AS pges ON pges.pges_id = sus.pges_id
            WHERE
                    lst.lis_id = :list_id
                    AND (ifnull(ls.lsus_estado_mailchimp,0) = '1' and sus.sus_estado = '1' and ls.lsus_estado = '1') 
                    AND lst.lis_estado = 1
                    AND lst.lis_estado_logico = 1                
            ";
        }
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":list_id", $list_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'contacto',
                    'carrera',
                    'per_correo',
                    'estado',
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    /**
     * Function insertarListaSuscritor
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    public function insertarListaSuscritor($con, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $param_sql .= "" . $keys[0];
        $bdet_sql .= "'" . $parameters[0] . "'";
        for ($i = 1; $i < count($parameters); $i++) {
            if (isset($parameters[$i])) {
                $param_sql .= ", " . $keys[$i];
                $bdet_sql .= ", '" . $parameters[$i] . "'";
            }
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . '.' . $name_table . " ($param_sql) VALUES($bdet_sql);";
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.' . $name_table);
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function eliminarSuscriptor
     * @author  Gioavanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property 
     * @return  
     */
    public function actualizarEstadoChimp($sus_id, $lis_id) {
        $con = \Yii::$app->db_mailing;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        try {
            $comando = $con->createCommand
                    ("                    
                        UPDATE " . $con->dbname . ".lista_suscriptor lsus 
                        SET 
                            lsus.lsus_estado_mailchimp = 1,
                            lsus.lsus_fecha_modificacion = :fecha_modificacion                          
                        WHERE lsus.lis_id = $lis_id AND
                              lsus.sus_id = $sus_id AND
                              lsus.lsus_estado = 1 AND
                              lsus.lsus_estado_logico = 1
                    ");

            $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $response = $comando->execute();
            $trans->commit();
            return $response;
        } catch (Exception $ex) {
            $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function eliminar logica Suscriptor, cambia el estado a 0
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property 
     * @return  
     */
    public function updateSuscripto($per_id, $pges_id, $lista_id, $estado_cambio) {
        $con = \Yii::$app->db_mailing;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("  
                      UPDATE " . $con->dbname . ".suscriptor sus
                      INNER JOIN " . $con->dbname . ".lista_suscriptor lsus 
                      ON sus.sus_id = lsus.sus_id  
                      SET sus.sus_estado = :estado_cambio, 
                          lsus.lsus_estado = :estado_cambio,
                          sus.sus_fecha_modificacion = :fecha_modificacion, 
                          lsus.lsus_fecha_modificacion = :fecha_modificacion
                      WHERE sus.per_id = :per_id AND
                            sus.pges_id = :pges_id AND
                            lsus.lis_id = :lista_id ");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
            $comando->bindParam(":lista_id", $lista_id, \PDO::PARAM_INT);
            $comando->bindParam(":estado_cambio", $estado_cambio, \PDO::PARAM_STR);
            $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $response = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultarSuscriptoxPerylis
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function consultarSuscrito_rxlista($list_id) {
        $con = \Yii::$app->db_mailing;
        $sql = "
                    select list.lis_codigo as codigo,sus.sus_id, if(ifnull(pges.pges_id,0)>0,pges.pges_correo,per.per_correo) as correo
                    FROM 
                                db_mailing.suscriptor sus                         
                    JOIN        db_mailing.lista_suscriptor lsus ON sus.sus_id = lsus.sus_id
                    join        db_mailing.lista as list on list.lis_id=lsus.lis_id
                    left join   db_asgard.persona as per on per.per_id=sus.per_id	
                    left join   db_crm.persona_gestion as pges on pges.pges_id=sus.pges_id	
                    WHERE 
                        lsus.lis_id = $list_id and
                    sus.sus_estado=1 and sus.sus_estado_logico=1;
        ";

        $comando = $con->createCommand($sql);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consultarSuscriptoxPerylis
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function consultarSuscriptoxPer($per_id, $pges_id) {
        $con = \Yii::$app->db_mailing;
        // $estado = 1;
        $sql = "
                select 	sus.sus_id
                FROM db_mailing.suscriptor sus 
                WHERE (sus.per_id = :per_id and sus.pges_id = :pges_id)
                and sus.sus_estado_logico=1 and sus.sus_estado=1
                ";

        $comando = $con->createCommand($sql);
        // $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData['sus_id'];
    }

    /**
     * Function consultarSuscriptoxPerylis
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function consultarSuscriptoxPerylis($per_id, $pges_id, $list_id) {
        $con = \Yii::$app->db_mailing;
        // $estado = 1;
        $sql = "
                select count(*) as inscantes	
                FROM " . $con->dbname . ".suscriptor sus 
                INNER JOIN " . $con->dbname . ".lista_suscriptor lsus     
                ON sus.sus_id = lsus.sus_id
                WHERE (sus.per_id = :per_id and sus.pges_id = :pges_id) AND
                    lsus.lis_id = :list_id 
                -- AND sus.sus_estado_logico = :estado
                -- AND lsus.lsus_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        // $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":list_id", $list_id, \PDO::PARAM_INT);
        $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarSuscriptoresxLista
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>    
     * @property integer $userid
     * @return  
     */
    public function consultarSuscriptoexcel($arrFiltro = array(), $list_id, $subscrito = 0, $mpid) {
        $con = \Yii::$app->db_mailing;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_academico;
        $con3 = \Yii::$app->db_crm;
        $con4 = \Yii::$app->db_captacion;
        $estado = 1;
        $str_search = '';
        $query_subscrito = ($subscrito == 1) ? "AND ifnull(sus.sus_id,0)>0" : (($subscrito == 2) ? "AND ifnull(sus.sus_id,0)<1" : "");
        $nosuscrito = " left join db_mailing.suscriptor as sus on sus.per_id = per.per_id or sus.pges_id=pges.pges_id  ";
        $suscrito = " join db_mailing.suscriptor as sus on sus.per_id = per.per_id or sus.pges_id=pges.pges_id";
        $join_subscrito = ($subscrito == 1) ? $suscrito : (($subscrito == 2) ? $nosuscrito : $nosuscrito);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['estado'] == 1) {
                $str_search = " AND ifnull(sus.sus_id,0) > 0 and sus.sus_estado ='1' and ls.lsus_estado = '1' and ls.lsus_estado_mailchimp IS NULL ";
            }
            if ($arrFiltro['estado'] == 2) {
                $str_search = " AND (ifnull(ls.sus_id,0) = 0 or (ls.lsus_estado = '0'  and ls.lis_id = :list_id)) ";
                //$str_search = " AND (ifnull(sus.sus_id,0) = 0 or (sus.sus_estado ='0' and ls.lsus_estado = '0')) ";
            }
            if ($arrFiltro['estado'] == 3) {
                $str_search = " AND (ifnull(ls.lsus_estado_mailchimp,0) = '1' and ls.lsus_estado = '1' and sus.sus_estado = '1') ";
            }
        }
        if ($mpid == 1) {
            $mostraper_id = 'ifnull(per.per_id,0) as per_id,';
            $mostrapges_id = 'ifnull(pges.pges_id,0) as pges_id,';        // ESTO DUPLICA LA DATA            
        }
        $sql = "
                SELECT
                    $mostraper_id
                    $mostrapges_id                    
                    if(ifnull(per.per_id,0)=0,concat(pges.pges_pri_nombre, ' ', ifnull(pges.pges_pri_apellido,'')),concat(per.per_pri_nombre,' ',per.per_pri_apellido)) as contacto,	                                        
                    if(isnull(mest.mest_nombre),eaca.eaca_nombre,mest.mest_nombre) carrera,
                    ifnull(per.per_correo, pges.pges_correo) per_correo, 
                    CASE   
                      WHEN ifnull(ls.sus_id,0) > 0 and ls.lsus_estado = '1' and ls.lis_id = :list_id and ls.lsus_estado_mailchimp IS NULL THEN 'Suscrito'                       
                      WHEN ifnull(ls.sus_id,0) = 0 or (ls.lsus_estado = '0' and ls.lis_id = :list_id) THEN 'No suscrito'   
                      WHEN ifnull(ls.lsus_estado_mailchimp,0) = '1' and ls.lsus_estado = '1' and sus.sus_estado = '1' THEN 'Mailing'
                    END as estado                    
                FROM                     
                    " . $con->dbname . ".lista lst
                    LEFT JOIN " . $con2->dbname . ".estudio_academico as eaca on eaca.eaca_id= lst.eaca_id 
                    LEFT JOIN " . $con2->dbname . ".modulo_estudio as mest on mest.mest_id = lst.mest_id
                    LEFT JOIN " . $con3->dbname . ".oportunidad as opo on (opo.eaca_id=eaca.eaca_id or opo.mest_id=mest.mest_id) 
                    LEFT JOIN " . $con3->dbname . ".persona_gestion as pges on pges.pges_id=opo.pges_id
                    LEFT JOIN " . $con1->dbname . ".persona as per on per.per_correo = pges.pges_correo
                    LEFT JOIN " . $con4->dbname . ".interesado as inte on inte.per_id = per.per_id 
                    LEFT JOIN " . $con4->dbname . ".solicitud_inscripcion as sins on ((sins.int_id = inte.int_id) and (sins.eaca_id = eaca.eaca_id or sins.mest_id = mest.mest_id))
                    $join_subscrito
                    LEFT JOIN " . $con2->dbname . ".estudio_acad_area_con as eaac on eaac.eaca_id=eaca.eaca_id
                    LEFT JOIN " . $con2->dbname . ".area_conocimiento as acon on acon.acon_id=eaac.acon_id
                    LEFT JOIN " . $con->dbname . ".lista_suscriptor ls on (sus.sus_id = ls.sus_id and ls.lis_id = lst.lis_id)
                WHERE 
                    lst.lis_id= :list_id AND
                    lst.lis_estado = :estado AND
                    lst.lis_estado_logico = :estado
                    $query_subscrito
                    $str_search
                UNION
                SELECT  
                        $mostraper_id
                        $mostrapges_id                        
                        if(ifnull(per.per_id,0)=0,concat(pges.pges_pri_nombre, ' ', ifnull(pges.pges_pri_apellido,'')),concat(per.per_pri_nombre,' ',per.per_pri_apellido)) as contacto,
                        if(isnull(mest.mest_nombre),eaca.eaca_nombre,mest.mest_nombre) carrera,                        
                        ifnull(per.per_correo, pges.pges_correo) per_correo, 
                        CASE   
                            WHEN ifnull(ls.sus_id,0) > 0 and ls.lsus_estado = '1' and ls.lis_id =:list_id and ls.lsus_estado_mailchimp IS NULL THEN 'Suscrito'                             
                            WHEN ifnull(ls.sus_id,0) = 0 or (ls.lsus_estado = '0' and ls.lis_id = :list_id) THEN 'No suscrito'   
                            WHEN ifnull(ls.lsus_estado_mailchimp,0) = '1' and ls.lsus_estado = '1' and sus.sus_estado = '1' THEN 'Mailing'
                    END as estado                        
                FROM " . $con->dbname . ".lista lst
                    LEFT JOIN " . $con2->dbname . ".estudio_academico as eaca on eaca.eaca_id= lst.eaca_id 
                    LEFT JOIN " . $con2->dbname . ".modulo_estudio as mest on mest.mest_id = lst.mest_id
                    LEFT JOIN " . $con4->dbname . ".solicitud_inscripcion as sins on (sins.eaca_id = eaca.eaca_id or sins.mest_id = mest.mest_id)
                    LEFT JOIN " . $con4->dbname . ".interesado i on i.int_id = sins.int_id
                    LEFT JOIN " . $con1->dbname . ".persona per on per.per_id = i.per_id
                    LEFT JOIN " . $con3->dbname . ".persona_gestion as pges on per.per_correo = pges.pges_correo
                    LEFT JOIN " . $con2->dbname . ".estudio_acad_area_con as eaac on eaac.eaca_id=eaca.eaca_id
                    LEFT JOIN " . $con2->dbname . ".area_conocimiento as acon on acon.acon_id=eaac.acon_id
                    $join_subscrito
                    LEFT JOIN " . $con->dbname . ".lista_suscriptor ls on (sus.sus_id = ls.sus_id and ls.lis_id = lst.lis_id)
                    WHERE lst.lis_id = :list_id AND
                    lst.lis_estado = :estado AND
                    lst.lis_estado_logico = :estado
                    $str_search";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":list_id", $list_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    public function consultarsuscritos($list_id) {
        $con = \Yii::$app->db_mailing;
        $estado = 1;
        $sql = "
            SELECT 
                    count(lst.lis_id) as num_suscr
                    FROM db_mailing.lista lst 
                    join db_mailing.lista_suscriptor as lsu on lsu.lis_id=lst.lis_id
            WHERE
                    lst.lis_id = :list_id
                    and lsu.lsus_estado=:estado
                    and ifnull(lsu.lsus_estado_mailchimp,0)=0
                    and lst.lis_estado = :estado
                    and lst.lis_estado_logico = :estado
                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":list_id", $list_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultarsuschimp($list_id) {
        $con = \Yii::$app->db_mailing;
        $estado = 1;
        $sql = "
            SELECT 
                    count(lst.lis_id) as num_suscr_chimp
                    FROM db_mailing.lista lst 
                    join db_mailing.lista_suscriptor as lsu on lsu.lis_id=lst.lis_id
            WHERE
                    lst.lis_id = :list_id
                    and ifnull(lsu.lsus_estado_mailchimp,0)=1
                    and lst.lis_estado = :estado
                    and lst.lis_estado_logico = :estado
                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":list_id", $list_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consulta numero de no suscritos. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarNumnoescritos($list_id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_captacion;
        $con3 = \Yii::$app->db_mailing;
        $con4 = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "
                select sum(noescritos) as noescritos
                from(
                SELECT 
                        count(lst.lis_id) as noescritos
                        FROM db_mailing.lista lst 
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        join db_captacion.solicitud_inscripcion AS sins ON (sins.eaca_id = eaca.eaca_id OR sins.mest_id = mest.mest_id)    
                        JOIN db_captacion.interesado i ON i.int_id = sins.int_id
                        JOIN db_asgard.persona per ON per.per_id = i.per_id
                WHERE
                        lst.lis_id = :list_id
                        and lst.lis_estado = 1
                        and lst.lis_estado_logico = 1
                        and  per.per_id 
                        not in(
                                select sus.per_id 
                                from db_mailing.lista_suscriptor as ls
                                join db_mailing.suscriptor as sus on sus.sus_id=ls.sus_id
                                where lis_id =:list_id
                                and ls.lsus_estado=1
                        )
                union
                SELECT 
                        count(lst.lis_id) as noescritos
                        FROM db_mailing.lista lst 
                        left JOIN db_academico.estudio_academico AS eaca ON eaca.eaca_id = lst.eaca_id
                        left JOIN db_academico.modulo_estudio AS mest ON mest.mest_id = lst.mest_id                
                        left JOIN db_academico.estudio_acad_area_con AS eaac ON eaac.eaca_id = eaca.eaca_id
                        JOIN db_academico.area_conocimiento AS acon ON acon.acon_id = eaac.acon_id
                        join db_crm.oportunidad as opo on (opo.eaca_id=eaca.eaca_id or opo.mest_id=mest.mest_id)
                        JOIN db_crm.persona_gestion AS pges ON pges.pges_id=opo.pges_id                               
                WHERE
                        lst.lis_id = :list_id
                        and lst.lis_estado = 1
                        and lst.lis_estado_logico = 1
                    and pges.pges_id 
                                not in(
                                           select 
                                                sus.pges_id 
                                           from db_mailing.lista_suscriptor as ls
                                                join db_mailing.suscriptor as sus on sus.sus_id=ls.sus_id
                                           where 
                                                lis_id =:list_id
                                                and ls.lsus_estado=1
                                           )      
                    and pges.pges_correo
                    not in(
                        select per_correo
                        from db_asgard.persona as per
                        where per.per_estado=1 and per.per_estado_logico=1 and per_correo=pges.pges_correo
                    )
                ) as tabla_no       
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":list_id", $list_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function suscribe todos segunlista. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarListaTodos($asuscribir) {
        $con = \Yii::$app->db_mailing;
        $trans = $con->getTransaction();

        try {
            $sql = $asuscribir;
            $command = $con->createCommand($sql);
            $command->execute();
            return $con->getLastInsertID();
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function consultarSuscriptoresxLista
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>    
     * @property integer $userid
     * @return  
     */
    public function consultarSuscritosbtn($condicion, $ids) {
        $con = \Yii::$app->db_mailing;
        $sql = "
               SELECT sus_id 
               FROM db_mailing.suscriptor
               WHERE $condicion in ($ids) 
               and sus_estado=1 and sus_estado_logico=1
               ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function suscribe todos segunlista. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarListaSuscritorTodos($asuscribirli) {
        $con = \Yii::$app->db_mailing;
        $trans = $con->getTransaction();
        try {
            $sql = $asuscribirli;
            $command = $con->createCommand($sql);
            $command->execute();
            return $con->getLastInsertID();
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function eliminar todos los Suscriptor 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function updateSuscriptodos($suscribirtodos) {
        $con = \Yii::$app->db_mailing;
        $trans = $con->getTransaction();
        try {
            $sql = $suscribirtodos;
            $command = $con->createCommand($sql);
            $command->execute();
            return $con->getLastInsertID();
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function consultarListaSuscxsusidylis
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function consultarListaSuscxsusidylis($list_id, $sus_id) {
        $con = \Yii::$app->db_mailing;
        // $estado = 1;

        $sql = "
                select count(*) as suscrito	
                FROM " . $con->dbname . ".lista_suscriptor lsus                 
                WHERE lsus.lis_id = :list_id AND
                lsus.sus_id = :sus_id ";

        $comando = $con->createCommand($sql);
        // $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $comando->bindParam(":list_id", $list_id, \PDO::PARAM_INT);
        $comando->bindParam(":sus_id", $sus_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        $out = 0;
        if (count($resultData) > 0) {
            if ($resultData['suscrito'] > 0) {
                $out = 1;
            }
        }
        return $out;
    }

    /**
     * Function modificarListaSuscritor
     * @author  Gioavanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property 
     * @return  
     */
    public function modificarListaSuscritor($list_id, $sus_id) {
        $con = \Yii::$app->db_mailing;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        try {
            $comando = $con->createCommand
                    ("                    
                        UPDATE " . $con->dbname . ".lista_suscriptor lsus 
                        SET 
                            lsus.lsus_estado = 1,
                            lsus.lsus_fecha_modificacion = :fecha_modificacion                          
                        WHERE 
                        lsus.lis_id = :list_id AND
                        lsus.sus_id = :sus_id
                    ");

            $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":list_id", $list_id, \PDO::PARAM_INT);
            $comando->bindParam(":sus_id", $sus_id, \PDO::PARAM_INT);
            $response = $comando->execute();
            $trans->commit();
            return $response;
        } catch (Exception $ex) {
            $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function insertarCampaniaxLista
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    public function insertarCampaniaxLista($con, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $param_sql .= "" . $keys[0];
        $bdet_sql .= "'" . $parameters[0] . "'";
        for ($i = 1; $i < count($parameters); $i++) {
            if (isset($parameters[$i])) {
                $param_sql .= ", " . $keys[$i];
                $bdet_sql .= ", '" . $parameters[$i] . "'";
            }
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . '.' . $name_table . " ($param_sql) VALUES($bdet_sql);";
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.' . $name_table);
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function eliminar logica Lista suscriptor, cambia el estado a 0
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @property 
     * @return  
     */
    public function eliminarListaSuscriptor($per_id, $pges_id, $lista_id) {
        $con = \Yii::$app->db_mailing;
        $estado = 0;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".suscriptor sus 
                      INNER JOIN " . $con->dbname . ".lista_suscriptor lsus 
                      ON sus.sus_id = lsus.sus_id  
                      SET lsus.lsus_estado = :estado,                          
                          lsus.lsus_fecha_modificacion = :fecha_modificacion
                      WHERE sus.per_id = :per_id AND
                            sus.pges_id = :pges_id AND
                            lsus.lis_id = :lista_id ");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
            $comando->bindParam(":lista_id", $lista_id, \PDO::PARAM_INT);
            $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);

            $response = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultarMasListaXsuscriptor
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function consultarMasListaXsuscriptor($list_id, $per_id, $pges_id) {
        $con = \Yii::$app->db_mailing;
        $estado = 1;

        $sql = "SELECT count(*) as suscrito	
                FROM " . $con->dbname . ".lista_suscriptor lsus INNER JOIN " . $con->dbname . ".lista lis on (lis.lis_id = lsus.lis_id)
                     INNER JOIN " . $con->dbname . ".suscriptor sus on (sus.sus_id = lsus.sus_id)               
                WHERE sus.per_id = :per_id AND
                      sus.pges_id = :pges_id AND
                      lsus.lis_id != :list_id AND
                      lsus.lsus_estado = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":list_id", $list_id, \PDO::PARAM_INT);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

}
