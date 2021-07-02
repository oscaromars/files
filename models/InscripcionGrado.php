<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use \yii\data\ActiveDataProvider;
use Yii;
use app\modules\financiero\models\OrdenPago;
use app\models\Persona;
use app\models\EmpresaPersona;
use \app\modules\admision\models\SolicitudInscripcion;
use app\modules\admision\models\Interesado;
use app\modules\admision\models\InteresadoEmpresa;
use app\models\Usuario;
use app\models\UsuaGrolEper;
use yii\base\Security;
use app\modules\financiero\models\Secuencias;
use app\modules\admision\models\DocumentoAdjuntar;
use yii\base\Exception;

/**
 * Description of InscripcionAdmision
 *
 * @author root
 */
class InscripcionGrado extends \yii\db\ActiveRecord {
	
}