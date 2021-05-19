<?php
namespace app\modules\fe_edoc\models;

use Yii;
use app\models\Utilities;
use app\modules\fe_edoc\models\Empresa;
use app\modules\fe_edoc\models\VSClaveAcceso;
use app\modules\fe_edoc\models\VSValidador;


class Edoc_ApiRest extends \app\modules\fe_edoc\components\CActiveRecord {
    private $tipoEmision=1;//Valor por defecto "NORMAL"
    private $emp_id=1;
    private $est_id=1;    
    private $pemi_id=1;
    public $tipoEdoc = "";
    public $cabEdoc = array();
    public $detEdoc = array();
    public $dadcEdoc = array();//dato adiciona
    public $fpagEdoc = array();//forma de pago

    function __construct($arr_params = array()) {
        //Utilities::putMessageLogFile($arr_params);
        //Las clave son tranformadas en minusculas
        foreach ($arr_params as $key => $value) {
            if ($key == "tipoedoc")
                $this->tipoEdoc = $value;
            if ($key == "cabedoc")
                $this->cabEdoc = json_decode($value,TRUE);
            if ($key == "detedoc")
                $this->detEdoc = json_decode($value,TRUE);
            if ($key == "dadcedoc")
                $this->dadcEdoc = json_decode($value,TRUE);
            if ($key == "fpagedoc")
                $this->fpagEdoc = json_decode($value,TRUE);
        }
    }

    
    public function sendEdoc() {
        try {
            $cabFact = $this->cabEdoc;
            switch ($this->tipoEdoc) {
                case "01"://FACTURAS
                    //Utilities::putMessageLogFile(array("tipoEdoc" => $this->dadcEdoc));
                    //return array("status" => "OK", "tipoEdoc" => $this->tipoEdoc);                    
                    if (is_array($cabFact)) {
                        return $this->insertarFacturas();
                    } else {
                        return array("status" => "NO_OK", "error" => "NO DATA:" . $this->cabEdoc);
                    }
                    break;
                case "04"://NOTA DE CREDITO
                    if (is_array($cabFact)) {
                        return $this->insertarEdocNc();
                    } else {
                        return array("status" => "NO_OK", "error" => "NO DATA:" . $this->cabEdoc);
                    }
                    break;
                case "05"://NOTA DE DEBITO
                    return $this->insertarEdocNd();
                    break;
                case "06"://GUIA DE REMISION
                    return $this->insertarGuiasRemision();
                    break;
                case "07"://RETENCIONES
                    Utilities::putMessageLogFile($this->dadcEdoc);
                    //return array("status" => "OK", "tipoEdoc" => $this->tipoEdoc,"data" => $this->cabEdoc);
                    if (is_array($cabFact)) {
                        return $this->insertarRetenciones();
                    } else {
                        return array("status" => "NO_OK", "error" => "NO DATA:" . $this->cabEdoc);
                    }
                    break;
            }
        } catch (\Exception $e) {
            Utilities::putMessageLogFile($e);
            //throw $e;
            return array("status" => "NO_OK", "error" => $e->getMessage());
        }
    }

    private function retornaTarifaDelIva($tarifa) {
         //TABLA 18 FICHA TECNICA SEGUN SRI
        $codigo=0;
        switch (floatval($tarifa)) {
            Case 0:
                $codigo=0;
                break;
            Case 12:
                $codigo=2;
                break;
            Case 14:
                $codigo=3;
                break;
            Case 6:
                $codigo=6;//NO OBJETO DE IVA
                break;
            default:
                $codigo=7;//EXEPTO DE IVA
        }
        return $codigo;
     }
	
	/*
     * INICIO DE INFO EMPRESA
     */
	private function buscarDataEmpresa($emp_id,$est_id,$pemi_id) {
        $conApp = Yii::$app->db_edoc;
        $rawData = array();
        $sql = "SELECT A.emp_id,A.emp_ruc Ruc,A.emp_razonsocial RazonSocial,A.emp_nom_comercial NombreComercial,
                    A.emp_ambiente Ambiente,A.emp_tipo_emision TipoEmision,A.emp_direccion_matriz DireccionMatriz,B.est_direccion DireccionSucursal,
                    A.emp_obliga_contabilidad ObligadoContabilidad,A.emp_contri_especial ContribuyenteEspecial,A.emp_email_digital,
                    B.est_numero Establecimiento,C.pemi_numero PuntoEmision,A.emp_moneda Moneda,A.emp_email_conta CorreoConta
                    FROM " . $conApp->dbname . ".empresa A
                            INNER JOIN (" . $conApp->dbname . ".establecimiento B
                                            INNER JOIN " . $conApp->dbname . ".punto_emision C
                                                    ON B.est_id=C.est_id AND C.est_log='1')
                                    ON A.emp_id=B.emp_id AND B.est_log='1'
            WHERE A.emp_id='$emp_id' AND A.emp_est_log='1' 
                     AND B.est_id='$est_id' AND C.pemi_id='$pemi_id'";
        //echo $sql;
        //$rawData = $conApp->createCommand($sql)->queryAll(); //Varios registros =>  $rawData[0]['RazonSocial']
        $rawData = $conApp->createCommand($sql)->queryOne();  //Un solo Registro => $rawData['RazonSocial']
        return $rawData;
    }
    
    /*
     * INICIO DE PROCESO DE FACTURAS
     */
    
    private function insertarFacturas() {
        $con = Yii::$app->db_edoc;
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction();
        }
        try {
            $idCab= $this->insertarCabFactura($con);            
            $this->InsertarDetFactura($con,$idCab);
            $this->InsertarFacturaFormaPago($con,$idCab);
            $this->InsertarFacturaDatoAdicional($con,$idCab);
            if ($trans !== null){
                $trans->commit();
            }
            //return array("status"=>"OK");
            return array("status"=>"OK", "Ids_Doc"=>$idCab);
        } catch (\Exception $e) {
            $trans->rollBack();
			Utilities::putMessageLogFile($e);
            //throw $e;
            return array("status"=>"NO_OK","error"=>$e);
        }
        
    }
    
    private function insertarCabFactura($con) {
        $valida = new VSValidador();
        $cabFact= $this->cabEdoc;
	$empresaEnt=$this->buscarDataEmpresa($this->emp_id,$this->est_id,$this->pemi_id);  		
        $TipoEmision=$this->tipoEmision;//Valor por Defecto
        $RazonSocial=$empresaEnt['RazonSocial'];
        $NombreComercial=$empresaEnt['NombreComercial'];
        $Ruc=$empresaEnt['Ruc'];//Ruc de la EMpesa		  
	$DireccionMatriz=$empresaEnt['DireccionMatriz'];
        $DireccionEstablecimiento=$empresaEnt['DireccionSucursal'];
        $ContribuyenteEspecial=($cabFact['CONTRIB_ESPECIAL']!=0)?'SI':'';
        $ObligadoContabilidad=$cabFact['OBLIGADOCONTAB'];//($cabFact['OBLIGADOCONTAB']!=0)?'SI':'';
        $CodigoTransaccionERP='FE';//
        $UsuarioCreador="1";//idde la Persona que genera la factura
		
	/* Datos para Genera Clave */
        //$tip_doc,$fec_doc,$ruc,$ambiente,$serie,$numDoc,$tipoemision
        $objCla = new VSClaveAcceso();
        //$ClaveAcceso=$cabFact['CLAVEACCESO']; //Cuando la clave se extrae del sistema ERP
        $codDoc = $cabFact['TIPOCOMPROBANTE'];
        $Ambiente = $cabFact['TIPOAMBIENTE'];
        $serie = $cabFact['COD_ESTAB'] . $cabFact['PTOEMI'];
        $fec_doc = date("Y-m-d", strtotime($cabFact['FECHAEMISION']));
        //$Secuencial=$cabFact['SECUENCIAL'];
        $Secuencial = $valida->ajusteNumDoc($cabFact['SECUENCIAL'], 9);
        $ClaveAcceso = $objCla->claveAcceso($codDoc, $fec_doc, $Ruc, $Ambiente, $serie, $Secuencial, $TipoEmision);
        //Fin generador de Clave
        $tipoIdentificaion = $cabFact['TIPOID_SUJETO'];
        //$tipoIdentificaion = $valida->tipoIdent($cabFact['RUC_SUJETO']);
        $cedulaRucFinal = ($tipoIdentificaion == '07') ? '9999999999999' : $cabFact['RUC_SUJETO'];
        $por_iva = intval($cabFact['IVA_PORCENTAJE']); //12;
        //0=IVa 0% y 2=iva 12%
        //$ImporteTotal = (intval($cabFact['IVA_CODIGO']) == 2) ? (floatval($cabFact['TOTALBRUTO']) * (floatval($por_iva) / 100)) + floatval($cabFact['TOTALBRUTO']) : $cabFact['TOTALBRUTO'];
        //$TotalSinImpuesto=$cabFact['TOTALBRUTO'];
        //TotalSinImpuesto=total_sin_impuestos - total descuento - total devolucion iva  - total compensaciones + total impuestos  + propina + total retenciones + totalExportaciones + total rubros terceros
        $TotalSinImpuesto=floatval($cabFact['TOTALBRUTO'])-floatval($cabFact['TOTALDESC']);
        $ImporteTotal=$cabFact['TOTALDOC'];
        if(intval($cabFact['IVA_CODIGO']) == 2){
            if(floatval($cabFact['IVA_VALOR'])>0){
                $ImporteTotal=floatval($cabFact['TOTALBRUTO'])+floatval($cabFact['IVA_VALOR']);
            }            
        }
	$IdLote=$cabFact['SYS_FACTURANC_ID'];//Numero ID autoIncremetable del ERP
        
        $sql = "INSERT INTO " . $con->dbname . ".NubeFactura
               (Ambiente,TipoEmision, RazonSocial, NombreComercial, Ruc,ClaveAcceso,CodigoDocumento, Establecimiento,
                PuntoEmision, Secuencial, DireccionMatriz, FechaEmision, DireccionEstablecimiento, ContribuyenteEspecial,
                ObligadoContabilidad, TipoIdentificacionComprador, GuiaRemision, RazonSocialComprador, IdentificacionComprador,
                TotalSinImpuesto, TotalDescuento, Propina, ImporteTotal, Moneda,EmailResponsable, SecuencialERP, CodigoTransaccionERP,UsuarioCreador,Estado,FechaCarga,IdLote) VALUES 
               (:Ambiente,:TipoEmision, :RazonSocial, :NombreComercial, :Ruc,:ClaveAcceso,:CodigoDocumento, :Establecimiento,
                :PuntoEmision, :Secuencial, :DireccionMatriz, :FechaEmision, :DireccionEstablecimiento, :ContribuyenteEspecial,
                :ObligadoContabilidad, :TipoIdentificacionComprador, :GuiaRemision, :RazonSocialComprador, :IdentificacionComprador,
                :TotalSinImpuesto, :TotalDescuento, :Propina, :ImporteTotal, :Moneda,:EmailResponsable, :SecuencialERP, :CodigoTransaccionERP,:UsuarioCreador,1,CURRENT_TIMESTAMP(),:IdLote)";
        $comando = $con->createCommand($sql);

        //$comando->bindParam(":id", $id_docElectronico, PDO::PARAM_INT);
        $comando->bindParam(":Ambiente", $cabFact['TIPOAMBIENTE'], \PDO::PARAM_STR);
        $comando->bindParam(":TipoEmision", $TipoEmision, \PDO::PARAM_STR);
        $comando->bindParam(":Secuencial", $Secuencial, \PDO::PARAM_STR);        
        $comando->bindParam(":RazonSocial", $RazonSocial, \PDO::PARAM_STR);
        $comando->bindParam(":NombreComercial", $NombreComercial, \PDO::PARAM_STR);
        $comando->bindParam(":Ruc", $Ruc, \PDO::PARAM_STR);
        $comando->bindParam(":ClaveAcceso", $ClaveAcceso, \PDO::PARAM_STR);
        $comando->bindParam(":CodigoDocumento", $codDoc, \PDO::PARAM_STR);
        $comando->bindParam(":Establecimiento", $cabFact['COD_ESTAB'], \PDO::PARAM_STR);
        $comando->bindParam(":PuntoEmision", $cabFact['PTOEMI'], \PDO::PARAM_STR);
        $comando->bindParam(":DireccionMatriz", $DireccionMatriz, \PDO::PARAM_STR);
        $comando->bindParam(":FechaEmision", $fec_doc, \PDO::PARAM_STR);
        $comando->bindParam(":DireccionEstablecimiento", $DireccionEstablecimiento, \PDO::PARAM_STR);
        $comando->bindParam(":ContribuyenteEspecial", $ContribuyenteEspecial, \PDO::PARAM_STR);
        $comando->bindParam(":ObligadoContabilidad", $ObligadoContabilidad, \PDO::PARAM_STR);
        $comando->bindParam(":TipoIdentificacionComprador", $tipoIdentificaion, \PDO::PARAM_STR);
        $comando->bindParam(":GuiaRemision", $cabFact['NUMGUIA'], \PDO::PARAM_STR);
        $comando->bindParam(":RazonSocialComprador", $cabFact['RAZONSOCIAL_SUJETO'], \PDO::PARAM_STR);
        $comando->bindParam(":IdentificacionComprador", $cedulaRucFinal, \PDO::PARAM_STR);
        $comando->bindParam(":TotalSinImpuesto", $TotalSinImpuesto, \PDO::PARAM_STR);
        $comando->bindParam(":TotalDescuento", $cabFact['TOTALDESC'], \PDO::PARAM_STR);
        $comando->bindParam(":Propina", $cabFact['PROPINA'], \PDO::PARAM_STR);
        $comando->bindParam(":ImporteTotal", $ImporteTotal, \PDO::PARAM_STR);
        $comando->bindParam(":Moneda", $cabFact['MONEDA'], \PDO::PARAM_STR);
        $comando->bindParam(":EmailResponsable", $cabFact['MAILSUJETO'], \PDO::PARAM_STR);
        $comando->bindParam(":SecuencialERP", $Secuencial, \PDO::PARAM_STR);
        $comando->bindParam(":CodigoTransaccionERP", $CodigoTransaccionERP, \PDO::PARAM_STR);
        $comando->bindParam(":UsuarioCreador", $UsuarioCreador, \PDO::PARAM_STR);
	$comando->bindParam(":IdLote", $IdLote, \PDO::PARAM_STR);

        $comando->execute();
        return $con->getLastInsertID();
        
    }
    
    private function InsertarDetFactura($con,$idCab) {
        $cabFact= $this->cabEdoc;
        $detFact= $this->detEdoc;
        //Dim por_iva As Decimal = CDbl(dtsData.Tables("VC010101").Rows(0).Item("POR_IVA")) / 100
		$por_iva=0;
        $idDet=0;
        $valSinImp = 0;
        $val_iva12 = 0;
        $vet_iva12 = 0;
        $val_iva0 = 0;//Valor de Iva
        $vet_iva0 = 0;//Venta total con Iva
        for ($i = 0; $i < sizeof($detFact); $i++) {
			$por_iva=intval($detFact[$i]['IMP_PORCENTAJE']);//TARIFA % DE IVA SEGUN TABLA 17
			//Utilities::putMessageLogFile($por_iva);
            $valSinImp = $detFact[$i]['TOTALSINIMPUESTOS'];//floatval($detFact[$i]['T_VENTA']) - floatval($detFact[$i]['VAL_DES']);
            //%codigo iva segun tabla #17
            $codigoImp=$detFact[$i]['IMP_CODIGO'];
            if ($por_iva > 0) {
                $val_iva12 = $val_iva12 + ((floatval($detFact[$i]['CANTIDAD'])*floatval($detFact[$i]['PRECIOUNITARIO'])-floatval($detFact[$i]['DESC']))* (floatval($por_iva)/100));
                $vet_iva12 = $vet_iva12 + $valSinImp;
            } else {
                $val_iva0 = 0;
                $vet_iva0 = $vet_iva0 + $valSinImp;
            }
            $CodigoAuxiliar=($detFact[$i]['CODIGOPRINCIPAL']!='')?$detFact[$i]['CODIGOPRINCIPAL']:1;
            $sql = "INSERT INTO " . $con->dbname . ".NubeDetalleFactura
                        (CodigoPrincipal,CodigoAuxiliar,Descripcion,Cantidad,PrecioUnitario,Descuento,PrecioTotalSinImpuesto,IdFactura) VALUES 
                        (:CodigoPrincipal,:CodigoAuxiliar,:Descripcion,:Cantidad,:PrecioUnitario,:Descuento,:PrecioTotalSinImpuesto,:IdFactura);";
            
            $comando = $con->createCommand($sql);
            $comando->bindParam(":CodigoPrincipal", $detFact[$i]['CODIGOPRINCIPAL'], \PDO::PARAM_STR);
            $comando->bindParam(":CodigoAuxiliar", $CodigoAuxiliar, \PDO::PARAM_STR);
            $comando->bindParam(":Descripcion", $detFact[$i]['DESCRIPCION'], \PDO::PARAM_STR);
            $comando->bindParam(":Cantidad", $detFact[$i]['CANTIDAD'], \PDO::PARAM_STR);
            $comando->bindParam(":PrecioUnitario", $detFact[$i]['PRECIOUNITARIO'], \PDO::PARAM_STR);
            $comando->bindParam(":Descuento", $detFact[$i]['DESC'], \PDO::PARAM_STR);
            $comando->bindParam(":PrecioTotalSinImpuesto", $valSinImp, \PDO::PARAM_STR);
            $comando->bindParam(":IdFactura", $idCab, \PDO::PARAM_INT);
            $comando->execute();
            $idDet = $con->getLastInsertID();
            
            //Inserta el IVA de cada Item             
            if ($por_iva > 0) {//Verifico si el ITEM tiene Impuesto 12%
                //Segun Datos Sri
                $valIvaImp=(floatval($valSinImp)*floatval($por_iva))/100;//Calculo del Valor del Impuesto Generado por Detalle
                $this->InsertarDetImpFactura($con, $idDet, '2',$por_iva, $valSinImp, $valIvaImp); //12%
            } else {//Caso Contrario no Genera Impuesto 0%
                $this->InsertarDetImpFactura($con, $idDet, '2','0', $valSinImp, '0'); //0%
            }
        }
        //Inserta el Total del Iva Acumulado en el detalle
        //Insertar Datos de Iva 0%
		//Utilities::putMessageLogFile($vet_iva0.' IVA0');
        If ($vet_iva0 > 0) {
            $this->InsertarFacturaImpuesto($con, $idCab, '2','0', $vet_iva0, $val_iva0);
        }
        //Inserta Datos de Iva 12
		//Utilities::putMessageLogFile($vet_iva12.' IVA12');
        If ($vet_iva12 > 0) {
            $this->InsertarFacturaImpuesto($con, $idCab, '2', $por_iva, $vet_iva12, $val_iva12);
        }
    }

    private function InsertarDetImpFactura($con, $idDet, $codigo, $Tarifa, $t_venta, $val_iva) {
        $CodigoPor= $this->retornaTarifaDelIva($Tarifa);
        $sql = "INSERT INTO " . $con->dbname . ".NubeDetalleFacturaImpuesto
                    (Codigo,CodigoPorcentaje,BaseImponible,Tarifa,Valor,IdDetalleFactura)VALUES
                    (:Codigo,:CodigoPorcentaje,:BaseImponible,:Tarifa,:Valor,:IdDetalleFactura);";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":Codigo", $codigo, \PDO::PARAM_STR);
        $comando->bindParam(":CodigoPorcentaje", $CodigoPor, \PDO::PARAM_STR);
        $comando->bindParam(":BaseImponible", $t_venta, \PDO::PARAM_STR);
        $comando->bindParam(":Tarifa", $Tarifa, \PDO::PARAM_STR);
        $comando->bindParam(":Valor", $val_iva, \PDO::PARAM_STR);
        $comando->bindParam(":IdDetalleFactura", $idDet, \PDO::PARAM_INT);
        $comando->execute();        
    }
    
    private function InsertarFacturaImpuesto($con, $idCab, $codigo, $Tarifa, $t_venta, $val_iva) {
        $CodigoPor= $this->retornaTarifaDelIva($Tarifa);
        $sql = "INSERT INTO " . $con->dbname . ".NubeFacturaImpuesto
                    (Codigo,CodigoPorcentaje,BaseImponible,Tarifa,Valor,IdFactura)VALUES
                    (:Codigo,:CodigoPorcentaje,:BaseImponible,:Tarifa,:Valor,:IdFactura);";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":Codigo", $codigo, \PDO::PARAM_STR);
        $comando->bindParam(":CodigoPorcentaje", $CodigoPor, \PDO::PARAM_STR);
        $comando->bindParam(":BaseImponible", $t_venta, \PDO::PARAM_STR);
        $comando->bindParam(":Tarifa", $Tarifa, \PDO::PARAM_STR);
        $comando->bindParam(":Valor", $val_iva, \PDO::PARAM_STR);
        $comando->bindParam(":IdFactura", $idCab, \PDO::PARAM_INT);
        $comando->execute();        
    }

    private function InsertarFacturaFormaPago($con, $idCab) {
        //Utilities::putMessageLogFile("LLEGO A FORMA PAGO");
        $fpagEdoc = $this->fpagEdoc;
        //Implementado 8/08/2016
        //FOR_PAG_SRI,PAG_PLZ,PAG_TMP,VAL_NET =>$cabFact[$i]['VAL_NET']
        //Nota la Tabla Forma de Pago debe ser aigual que la SEA Y WEBSEA los IDS deben conincidir.
        //Si no tiene codigo usa el codigo 1 (SIN UTILIZACION DEL SISTEMA FINANCIERO o Efectivo)
        for ($i = 0; $i < sizeof($fpagEdoc); $i++) {
            $IdsForma = $fpagEdoc[$i]['COD_FORMAPAG']; //($fpagEdoc['COD_FORMAPAG']!='')?$fpagEdoc['FOR_PAG_SRI']:'1';
            $Total = $fpagEdoc[$i]['VALOR']; //($fpagEdoc['VALOR']!='')?$fpagEdoc['VAL_NET']:0;
            $Plazo = $fpagEdoc[$i]['PLAZO']; //($fpagEdoc['PLAZO']>0)?$fpagEdoc['PLAZO']:'30';
            $UnidadTiempo = $fpagEdoc[$i]['UNIDAD_TIEMPO']; //($fpagEdoc['UNIDAD_TIEMPO']!='')?$fpagEdoc['UNIDAD_TIEMPO']:'DIAS';

            $sql = "INSERT INTO " . $con->dbname . ".NubeFacturaFormaPago
                (IdForma,IdFactura,FormaPago,Total,Plazo,UnidadTiempo)VALUES
                (:IdForma,:IdFactura,:FormaPago,:Total,:Plazo,:UnidadTiempo);";

            $comando = $con->createCommand($sql);
            $comando->bindParam(":IdForma", $IdsForma, \PDO::PARAM_STR);
            $comando->bindParam(":IdFactura", $idCab, \PDO::PARAM_INT);
            $comando->bindParam(":FormaPago", $IdsForma, \PDO::PARAM_STR);
            $comando->bindParam(":Total", $Total, \PDO::PARAM_STR);
            $comando->bindParam(":Plazo", $Plazo, \PDO::PARAM_STR);
            $comando->bindParam(":UnidadTiempo", $UnidadTiempo, \PDO::PARAM_STR);
            $comando->execute();
        }
    }

    private function InsertarFacturaDatoAdicional($con, $idCab) {
		try {
            $dadcEdoc = $this->dadcEdoc;
			//Utilities::putMessageLogFile($dadcEdoc);
			for ($i = 0; $i < sizeof($dadcEdoc); $i++) {
				$sql = "INSERT INTO " . $con->dbname . ".NubeDatoAdicionalFactura 
					 (Nombre,Descripcion,IdFactura) VALUES (:Nombre,:Descripcion,:IdFactura);";
				$comando = $con->createCommand($sql);
				$comando->bindParam(":Nombre", $dadcEdoc[$i]['NOMBRECAMPO'], \PDO::PARAM_STR);
				$comando->bindParam(":Descripcion", $dadcEdoc[$i]['VALORCAMPO'], \PDO::PARAM_STR);
				$comando->bindParam(":IdFactura", $idCab, \PDO::PARAM_INT);
				$comando->execute();
			}
        } catch (\Exception $e) {
			Utilities::putMessageLogFile($e);
            //throw $e;
            return array("status"=>"NO_OK","error"=>$e);
        }
		
    }

    /*
     * FIN DE PROCESO DE FACTURAS
     */
    
    /*
     * INICIO DE PROCESO DE RETENCIONES
     */
    
    private function insertarRetenciones() {
        $con = Yii::$app->db_edoc;
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction();
        }
        try {
            $idCDoc= $this->InsertarCabRetencion($con);            
            $this->InsertarDetRetencion($con,$idCDoc);
            $this->InsertarRetencionDatoAdicional($con,$idCDoc);
            if ($trans !== null){
                $trans->commit();
            }
            //return array("status"=>"OK");
            return array("status"=>"OK", "Ids_Doc"=>$idCDoc);
        } catch (\Exception $e) {
            $trans->rollBack();
            //throw $e;
            return array("status"=>"NO_OK","error"=>$e);
        }
        
    }
    
    
    private function InsertarCabRetencion($con) {
        $cabFact= $this->cabEdoc;       
        $valida = new VSValidador();
        $cabFact= $this->cabEdoc;
	$empresaEnt=$this->buscarDataEmpresa($this->emp_id,$this->est_id,$this->pemi_id);  		
        $TipoEmision=$this->tipoEmision;//Valor por Defecto
        $RazonSocial=$empresaEnt['RazonSocial'];
        $NombreComercial=$empresaEnt['NombreComercial'];
        $Ruc=$empresaEnt['Ruc'];//Ruc de la EMpesa		  
	$DireccionMatriz=$empresaEnt['DireccionMatriz'];
        $DireccionEstablecimiento=$empresaEnt['DireccionSucursal'];
        $ContribuyenteEspecial=($cabFact['CONTRIB_ESPECIAL']!=0)?'SI':'';
        $ObligadoContabilidad=$empresaEnt['ObligadoContabilidad'];//$cabFact['OBLIGADOCONTAB'];//($cabFact['OBLIGADOCONTAB']!=0)?'SI':'';
        $CodigoTransaccionERP='RT';//
        $UsuarioCreador="1";//idde la Persona que genera la factura
		
	/* Datos para Genera Clave */
        //$tip_doc,$fec_doc,$ruc,$ambiente,$serie,$numDoc,$tipoemision
        $objCla = new VSClaveAcceso();
        //$ClaveAcceso=$cabFact['CLAVEACCESO']; //Cuando la clave se extrae del sistema ERP
        $codDoc = $cabFact['CODDOC'];
        $Ambiente = $cabFact['TIPOAMBIENTE'];
        $serie = $cabFact['COD_ESTAB'] . $cabFact['PTOEMI'];
        $fec_doc = date("Y-m-d", strtotime($cabFact['FECHAEMISION']));
        //$Secuencial=$cabFact['SECUENCIAL'];
        $Secuencial = $valida->ajusteNumDoc($cabFact['SECUENCIAL'], 9);
        $ClaveAcceso = $objCla->claveAcceso($codDoc, $fec_doc, $Ruc, $Ambiente, $serie, $Secuencial, $TipoEmision);
        //Fin generador de Clave
        $tipoIdentificaion = $cabFact['TIPOID_SUJETO'];
        //$tipoIdentificaion = $valida->tipoIdent($cabFact['RUC_SUJETO']);
        $cedulaRucFinal = ($tipoIdentificaion == '07') ? '9999999999999' : $cabFact['RUC_SUJETO'];
        //$por_iva = intval($cabFact['IVA_PORCENTAJE']); //12;
        //0=IVa 0% y 2=iva 12%
        //$ImporteTotal = (intval($cabFact['IVA_CODIGO']) == 2) ? (floatval($cabFact['TOTALBRUTO']) * (floatval($por_iva) / 100)) + floatval($cabFact['TOTALBRUTO']) : $cabFact['TOTALBRUTO'];

        
        $TotalRetencion=0;//Este valor es actualiado despues de insertar el detalle
        $DocSustentoERP=$cabFact['SECUENCIAL_DOCSUST'];
        $IdLote=$cabFact['SYS_RETENCION_ID'];//Numero ID autoIncremetable del ERP 
        
        
        $sql = "INSERT INTO " . $con->dbname . ".NubeRetencion 
                (Ambiente,TipoEmision,RazonSocial,NombreComercial,Ruc,ClaveAcceso,CodigoDocumento,PuntoEmision,Establecimiento, 
                 Secuencial,DireccionMatriz,FechaEmision,DireccionEstablecimiento,ContribuyenteEspecial,ObligadoContabilidad, 
                 TipoIdentificacionSujetoRetenido,IdentificacionSujetoRetenido,RazonSocialSujetoRetenido,PeriodoFiscal, 
                 TotalRetencion,UsuarioCreador,SecuencialERP,CodigoTransaccionERP,DocSustentoERP,Estado,USU_ID,FechaCarga,EmailResponsable,IdLote)VALUES 
                (:Ambiente,:TipoEmision,:RazonSocial,:NombreComercial,:Ruc,:ClaveAcceso,:CodigoDocumento,:PuntoEmision,:Establecimiento, 
                 :Secuencial,:DireccionMatriz,:FechaEmision,:DireccionEstablecimiento,:ContribuyenteEspecial,:ObligadoContabilidad, 
                 :TipoIdentificacionSujetoRetenido,:IdentificacionSujetoRetenido,:RazonSocialSujetoRetenido,:PeriodoFiscal, 
                 :TotalRetencion,:UsuarioCreador,:SecuencialERP,:CodigoTransaccionERP,:DocSustentoERP,1,:UsuarioCreador,CURRENT_TIMESTAMP(),:EmailResponsable,:IdLote );";
        
        $comando = $con->createCommand($sql);

        //$comando->bindParam(":id", $id_docElectronico, PDO::PARAM_INT);
        $comando->bindParam(":Ambiente", $Ambiente, \PDO::PARAM_STR);
        $comando->bindParam(":TipoEmision", $TipoEmision, \PDO::PARAM_STR);
        $comando->bindParam(":Secuencial", $Secuencial, \PDO::PARAM_STR);        
        $comando->bindParam(":RazonSocial", $RazonSocial, \PDO::PARAM_STR);
        $comando->bindParam(":NombreComercial", $NombreComercial, \PDO::PARAM_STR);
        $comando->bindParam(":Ruc", $Ruc, \PDO::PARAM_STR);
        $comando->bindParam(":ClaveAcceso", $ClaveAcceso, \PDO::PARAM_STR);        
        $comando->bindParam(":CodigoDocumento", $codDoc, \PDO::PARAM_STR);
        $comando->bindParam(":Establecimiento", $cabFact['COD_ESTAB'], \PDO::PARAM_STR);
        $comando->bindParam(":PuntoEmision", $cabFact['PTOEMI'], \PDO::PARAM_STR);        
        $comando->bindParam(":DireccionMatriz", $DireccionMatriz, \PDO::PARAM_STR);
        $comando->bindParam(":FechaEmision", $fec_doc, \PDO::PARAM_STR);
        $comando->bindParam(":DireccionEstablecimiento", $DireccionEstablecimiento, \PDO::PARAM_STR);        
        $comando->bindParam(":ContribuyenteEspecial", $ContribuyenteEspecial, \PDO::PARAM_STR);        
        $comando->bindParam(":ObligadoContabilidad", $ObligadoContabilidad, \PDO::PARAM_STR);        
        $comando->bindParam(":TipoIdentificacionSujetoRetenido", $tipoIdentificaion, \PDO::PARAM_STR);        
        $comando->bindParam(":RazonSocialSujetoRetenido", $cabFact['RAZONSOCIAL_SUJETO'], \PDO::PARAM_STR);
        $comando->bindParam(":IdentificacionSujetoRetenido", $cedulaRucFinal, \PDO::PARAM_STR);
        $comando->bindParam(":PeriodoFiscal", $cabFact['PERIODOFISCAL'], \PDO::PARAM_STR);
        $comando->bindParam(":TotalRetencion", $TotalRetencion, \PDO::PARAM_STR);
        $comando->bindParam(":SecuencialERP", $Secuencial, \PDO::PARAM_STR);
        $comando->bindParam(":CodigoTransaccionERP", $CodigoTransaccionERP, \PDO::PARAM_STR);
        $comando->bindParam(":DocSustentoERP", $DocSustentoERP, \PDO::PARAM_STR);        
        $comando->bindParam(":UsuarioCreador", $UsuarioCreador, \PDO::PARAM_STR);
        $comando->bindParam(":EmailResponsable", $cabFact['MAILSUJETO'], \PDO::PARAM_STR);
        $comando->bindParam(":IdLote", $IdLote, \PDO::PARAM_STR);
        $comando->execute();
        return $con->getLastInsertID();
        
    }

    private function InsertarDetRetencion($con,$idCab) {
        $cabFact= $this->cabEdoc;
        $detDoc= $this->detEdoc;
        
        //INSERTA PROVICIONES DE PASIVOS O GASTOS
        $codigo= 0;//codigo impuesto a retener
        $cod_retencion = ""; //Codigo de Porcentaje de Retencion
        $bas_imponible = 0;//Base imponible para impuesto
        $por_retener= 0 ;//Porcentaje de Retencion  
        $val_retenido  = 0 ;//Valor Retenido
        $codDocRet= "01";
        $n_s_pro  = "";
        $numDocRet = "";
        $fecDocRet = "";
        $TotalRetencion = 0;
        //$sqlRet As New StringBuilder '$sqlRet="";
        
        //Recorre el detalle y identifica el tipo de Retencion
       
        for ($i = 0; $i < sizeof($detDoc); $i++) {//
            $codigo=$detDoc[$i]['TIPO_RETENCION'];
            $cod_retencion=$detDoc[$i]['CODIGO_RETENCION'];
            $bas_imponible=$detDoc[$i]['BASE_IMPONIBLE'];
            $por_retener=$detDoc[$i]['PORCENTAJE_RET'];
            $val_retenido=$detDoc[$i]['VALOR_RET'];
            $codDocRet=$detDoc[$i]['CODDOC_SUST'];
            $numDocRet=$detDoc[$i]['SERIE_DOCSUST'].$detDoc[$i]['PNTEMI_DOCSUST'].$detDoc[$i]['SECUENCIAL_DOCSUST'];
            $FechaEmiDocRetener=date(Yii::$app->params["dateByDefault"] , strtotime($detDoc[$i]['FECHAEMI_DOCSUST']));
            $TotalRetencion = $TotalRetencion + $val_retenido;//valor que actualiza la cabecera Datos 
            
             $sql = "INSERT INTO " . $con->dbname . ".NubeDetalleRetencion 
                    (Codigo,CodigoRetencion,BaseImponible,PorcentajeRetener,ValorRetenido,CodDocRetener,
                     NumDocRetener,FechaEmisionDocRetener,IdRetencion )VALUES 
                    (:Codigo,:CodigoRetencion,:BaseImponible,:PorcentajeRetener,:ValorRetenido,:CodDocRetener,
                     :NumDocRetener,:FechaEmisionDocRetener,:IdRetencion);";
           
            $comando = $con->createCommand($sql);
            $comando->bindParam(":IdRetencion", $idCab, \PDO::PARAM_INT);
            $comando->bindParam(":Codigo", $codigo, \PDO::PARAM_STR);
            $comando->bindParam(":CodigoRetencion", $cod_retencion, \PDO::PARAM_STR);
            $comando->bindParam(":BaseImponible", $bas_imponible, \PDO::PARAM_STR);
            $comando->bindParam(":PorcentajeRetener", $por_retener, \PDO::PARAM_STR);
            $comando->bindParam(":ValorRetenido", $val_retenido, \PDO::PARAM_STR);
            $comando->bindParam(":CodDocRetener", $codDocRet, \PDO::PARAM_STR);
            $comando->bindParam(":NumDocRetener", $numDocRet, \PDO::PARAM_STR);
            $comando->bindParam(":FechaEmisionDocRetener", $FechaEmiDocRetener, \PDO::PARAM_STR);
            $comando->execute();
            // Segun tabl 19 de retenciones
            if($codigo=='1'){//Retencion IMP RENTA=1
                
            }elseif($codigo=='2'){//Retencion de IVA
                
            }elseif($codigo=='6') {//ISD
                
            }
        }
        $this->actualizaTotalCabRetencion($con,$idCab,$TotalRetencion);

    }
    
    //Nota Dato adicional personalizado solo para ALTERSOF
    private function InsertarRetencionDatoAdicional($con, $idCab) {
        $cabFact = $this->cabEdoc;
        $correo="Correo";
        $sql = "INSERT INTO " . $con->dbname . ".NubeDatoAdicionalRetencion 
                 (Nombre,Descripcion,IdRetencion) VALUES (:Nombre,:Descripcion,:IdRetencion);";       
        $comando = $con->createCommand($sql);
        $comando->bindParam(":Nombre", $correo, \PDO::PARAM_STR);
        $comando->bindParam(":Descripcion", $cabFact['MAILSUJETO'], \PDO::PARAM_STR);
        $comando->bindParam(":IdRetencion", $idCab, \PDO::PARAM_INT);
        $comando->execute();
    }

    /*private function InsertarRetencionDatoAdicional($con, $idCab) {
        $dadcEdoc = $this->dadcEdoc;
        for ($i = 0; $i < sizeof($dadcEdoc); $i++) {
            $sql = "INSERT INTO " . $con->dbname . ".NubeDatoAdicionalRetencion 
                 (Nombre,Descripcion,IdRetencion) VALUES (:Nombre,:Descripcion,:IdRetencion);";
            //('Direccion','$direccion','$idCab'),('Destino','$destino','$idCab'),('Contacto','$contacto','$idCab')";

            $comando = $con->createCommand($sql);
            $comando->bindParam(":Nombre", $dadcEdoc[$i]['NOMBRECAMPO'], \PDO::PARAM_STR);
            $comando->bindParam(":Descripcion", $dadcEdoc[$i]['VALORCAMPO'], \PDO::PARAM_STR);
            $comando->bindParam(":IdRetencion", $idCab, \PDO::PARAM_INT);
            $comando->execute();
        }
    }*/
       
    private function actualizaTotalCabRetencion($con, $idCab,$Total) {
        $sql = "UPDATE " . $con->dbname . ".NubeRetencion SET TotalRetencion=:TotalRetencion WHERE IdRetencion=:IdRetencion";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":TotalRetencion", $Total, \PDO::PARAM_STR);
        $comando->bindParam(":IdRetencion", $idCab, \PDO::PARAM_INT);
        $comando->execute();
    }



    
    
     /*
     * FIN DE PROCESO DE RETENCIONES
     */
    
     /*
     * INICIO DE PROCESO DE NC
     */
    private function insertarEdocNc() {
        $con = Yii::$app->db_edoc;
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction();
        }
        try {
            $idCab= $this->InsertarCabNC($con);            
            $this->InsertarDetNC($con,$idCab);
            //$this->InsertarFacturaFormaPago($con,$idCab);
            $this->InsertarNcDatoAdicional($con,$idCab);
            if ($trans !== null){
                $trans->commit();
            }
            //return array("status"=>"OK");
            return array("status"=>"OK", "Ids_Doc"=>$idCab);
        } catch (\Exception $e) {
            $trans->rollBack();
            //throw $e;
            return array("status"=>"NO_OK","error"=>$e);
        }
        
    }
    
    private function InsertarCabNC($con) {      
        $valida = new VSValidador();
        $cabFact= $this->cabEdoc;
	$empresaEnt=$this->buscarDataEmpresa($this->emp_id,$this->est_id,$this->pemi_id);  		
        $TipoEmision=$this->tipoEmision;//Valor por Defecto
        $RazonSocial=$empresaEnt['RazonSocial'];
        $NombreComercial=$empresaEnt['NombreComercial'];
        $Ruc=$empresaEnt['Ruc'];//Ruc de la EMpesa		  
	$DireccionMatriz=$empresaEnt['DireccionMatriz'];
        $DireccionEstablecimiento=$empresaEnt['DireccionSucursal'];
        $ContribuyenteEspecial=($cabFact['CONTRIB_ESPECIAL']!=0)?'SI':'';
        $ObligadoContabilidad=$cabFact['OBLIGADOCONTAB'];//($cabFact['OBLIGADOCONTAB']!=0)?'SI':'';
        $CodigoTransaccionERP='NC';//
        $UsuarioCreador="1";//idde la Persona que genera la factura
		
	/* Datos para Genera Clave */
        //$tip_doc,$fec_doc,$ruc,$ambiente,$serie,$numDoc,$tipoemision
        $objCla = new VSClaveAcceso();
        //$ClaveAcceso=$cabFact['CLAVEACCESO']; //Cuando la clave se extrae del sistema ERP
        $codDoc = $cabFact['TIPOCOMPROBANTE'];
        $Ambiente = $cabFact['TIPOAMBIENTE'];
        $serie = $cabFact['COD_ESTAB'] . $cabFact['PTOEMI'];
        $fec_doc = date("Y-m-d", strtotime($cabFact['FECHAEMISION']));
        //$Secuencial=$cabFact['SECUENCIAL'];
        $Secuencial = $valida->ajusteNumDoc($cabFact['SECUENCIAL'], 9);
        $ClaveAcceso = $objCla->claveAcceso($codDoc, $fec_doc, $Ruc, $Ambiente, $serie, $Secuencial, $TipoEmision);
        //Fin generador de Clave
        $tipoIdentificaion = $cabFact['TIPOID_SUJETO'];
        //$tipoIdentificaion = $valida->tipoIdent($cabFact['RUC_SUJETO']);
        $cedulaRucFinal = ($tipoIdentificaion == '07') ? '9999999999999' : $cabFact['RUC_SUJETO'];
        $por_iva = intval($cabFact['IVA_PORCENTAJE']); //12;
        //0=IVa 0% y 2=iva 12%
        $ImporteTotal = (intval($cabFact['IVA_CODIGO']) == 2) ? (floatval($cabFact['TOTALBRUTO']) * (floatval($por_iva) / 100)) + floatval($cabFact['TOTALBRUTO']) : $cabFact['TOTALBRUTO'];


        $sql = "INSERT INTO " . $con->dbname . ".NubeNotaCredito
               (Ambiente,TipoEmision, RazonSocial, NombreComercial, Ruc,ClaveAcceso,CodigoDocumento, Establecimiento,
                PuntoEmision, Secuencial, DireccionMatriz, FechaEmision, DireccionEstablecimiento, ContribuyenteEspecial,
                ObligadoContabilidad, TipoIdentificacionComprador, RazonSocialComprador, IdentificacionComprador,
                Rise,CodDocModificado,NumDocModificado,FechaEmisionDocModificado,TotalSinImpuesto,ValorModificacion,MotivoModificacion,
                Moneda,EmailResponsable, SecuencialERP, CodigoTransaccionERP,UsuarioCreador,Estado,FechaCarga) VALUES 
               (:Ambiente,:TipoEmision, :RazonSocial, :NombreComercial, :Ruc,:ClaveAcceso,:CodigoDocumento, :Establecimiento,
                :PuntoEmision, :Secuencial, :DireccionMatriz, :FechaEmision, :DireccionEstablecimiento, :ContribuyenteEspecial,
                :ObligadoContabilidad, :TipoIdentificacionComprador, :RazonSocialComprador, :IdentificacionComprador,
                :Rise,:CodDocModificado,:NumDocModificado,:FechaEmisionDocModificado,:TotalSinImpuesto,:ValorModificacion,:MotivoModificacion,
                :Moneda,:EmailResponsable, :SecuencialERP, :CodigoTransaccionERP,:UsuarioCreador,1,CURRENT_TIMESTAMP())";
        $comando = $con->createCommand($sql);

        //$comando->bindParam(":id", $id_docElectronico, PDO::PARAM_INT);
        $comando->bindParam(":Ambiente", $Ambiente, \PDO::PARAM_STR);
        $comando->bindParam(":TipoEmision", $TipoEmision, \PDO::PARAM_STR);
        $comando->bindParam(":Secuencial", $Secuencial, \PDO::PARAM_STR);        
        $comando->bindParam(":RazonSocial", $RazonSocial, \PDO::PARAM_STR);
        $comando->bindParam(":NombreComercial", $NombreComercial, \PDO::PARAM_STR);
        $comando->bindParam(":Ruc", $Ruc, \PDO::PARAM_STR);
        $comando->bindParam(":ClaveAcceso", $ClaveAcceso, \PDO::PARAM_STR);
        $comando->bindParam(":CodigoDocumento", $codDoc, \PDO::PARAM_STR);
        $comando->bindParam(":Establecimiento", $cabFact['COD_ESTAB'], \PDO::PARAM_STR);
        $comando->bindParam(":PuntoEmision", $cabFact['PTOEMI'], \PDO::PARAM_STR);
        $comando->bindParam(":DireccionMatriz", $DireccionMatriz, \PDO::PARAM_STR);
        $comando->bindParam(":FechaEmision", $fec_doc, \PDO::PARAM_STR);
        $comando->bindParam(":DireccionEstablecimiento", $DireccionEstablecimiento, \PDO::PARAM_STR);
        $comando->bindParam(":ContribuyenteEspecial", $ContribuyenteEspecial, \PDO::PARAM_STR);
        $comando->bindParam(":ObligadoContabilidad", $ObligadoContabilidad, \PDO::PARAM_STR);
        $comando->bindParam(":TipoIdentificacionComprador", $tipoIdentificaion, \PDO::PARAM_STR);
        $comando->bindParam(":RazonSocialComprador", $cabFact['RAZONSOCIAL_SUJETO'], \PDO::PARAM_STR);
        $comando->bindParam(":IdentificacionComprador", $cedulaRucFinal, \PDO::PARAM_STR);
              
        $comando->bindParam(":Rise", $cabFact['RISE'], \PDO::PARAM_STR);
        $comando->bindParam(":CodDocModificado", $cabFact['TIPCOMP_MODIFICA'], \PDO::PARAM_STR);
        $comando->bindParam(":NumDocModificado", $cabFact['NUMCOMP_MODIFICA'], \PDO::PARAM_STR);
        $comando->bindParam(":FechaEmisionDocModificado", $cabFact['FECHAEMISION_SUSTENTO'], \PDO::PARAM_STR);                
        $comando->bindParam(":TotalSinImpuesto", $cabFact['TOTALBRUTO'], \PDO::PARAM_STR);
        $comando->bindParam(":ValorModificacion", $cabFact['VALORDOC_MODIFICA'], \PDO::PARAM_STR);
        $comando->bindParam(":MotivoModificacion", $cabFact['MOTIVO_DEVOLUCION'], \PDO::PARAM_STR);
        
        $comando->bindParam(":Moneda", $cabFact['MONEDA'], \PDO::PARAM_STR);
        $comando->bindParam(":EmailResponsable", $cabFact['MAILSUJETO'], \PDO::PARAM_STR);
        $comando->bindParam(":SecuencialERP", $Secuencial, \PDO::PARAM_STR);
        $comando->bindParam(":CodigoTransaccionERP", $CodigoTransaccionERP, \PDO::PARAM_STR);
        $comando->bindParam(":UsuarioCreador", $UsuarioCreador, \PDO::PARAM_STR);

        $comando->execute();
        return $con->getLastInsertID();
        
    }
    
    private function InsertarDetNC($con,$idCab) {
        $cabFact= $this->cabEdoc;
        $detFact= $this->detEdoc;
        //Dim por_iva As Decimal = CDbl(dtsData.Tables("VC010101").Rows(0).Item("POR_IVA")) / 100
        $por_iva=intval($cabFact['IVA_PORCENTAJE']);//12;//Recuperar el impuesto de alguna tabla    o recuperar de la Cabecera    
        $idDet=0;
        $valSinImp = 0;
        $val_iva12 = 0;
        $vet_iva12 = 0;
        $val_iva0 = 0;//Valor de Iva
        $vet_iva0 = 0;//Venta total con Iva
        for ($i = 0; $i < sizeof($detFact); $i++) {
            $valSinImp = $detFact[$i]['TOTALSINIMPUESTOS'];//floatval($detFact[$i]['T_VENTA']) - floatval($detFact[$i]['VAL_DES']);
            //%codigo iva segun tabla #17
            $codigoImp=$detFact[$i]['IMP_CODIGO'];
            if ($codigoImp == '2') {
                $val_iva12 = $val_iva12 + ((floatval($detFact[$i]['CANTIDAD'])*floatval($detFact[$i]['PRECIOUNITARIO'])-floatval($detFact[$i]['DESC']))* (floatval($por_iva)/100));
                $vet_iva12 = $vet_iva12 + $valSinImp;
            } else {
                $val_iva0 = 0;
                $vet_iva0 = $vet_iva0 + $valSinImp;
            }
            $CodigoAuxiliar=($detFact[$i]['CODIGOPRINCIPAL']!='')?$detFact[$i]['CODIGOPRINCIPAL']:1;
            $sql = "INSERT INTO " . $con->dbname . ".NubeDetalleNotaCredito
                        (CodigoPrincipal,CodigoAuxiliar,Descripcion,Cantidad,PrecioUnitario,Descuento,PrecioTotalSinImpuesto,IdNotaCredito) VALUES 
                        (:CodigoPrincipal,:CodigoAuxiliar,:Descripcion,:Cantidad,:PrecioUnitario,:Descuento,:PrecioTotalSinImpuesto,:IdNotaCredito);";
                      
            $comando = $con->createCommand($sql);
            $comando->bindParam(":CodigoPrincipal", $detFact[$i]['CODIGOPRINCIPAL'], \PDO::PARAM_STR);
            $comando->bindParam(":CodigoAuxiliar", $CodigoAuxiliar, \PDO::PARAM_STR);
            $comando->bindParam(":Descripcion", $detFact[$i]['DESCRIPCION'], \PDO::PARAM_STR);
            $comando->bindParam(":Cantidad", $detFact[$i]['CANTIDAD'], \PDO::PARAM_STR);
            $comando->bindParam(":PrecioUnitario", $detFact[$i]['PRECIOUNITARIO'], \PDO::PARAM_STR);
            $comando->bindParam(":Descuento", $detFact[$i]['DESC'], \PDO::PARAM_STR);
            $comando->bindParam(":PrecioTotalSinImpuesto", $valSinImp, \PDO::PARAM_STR);
            $comando->bindParam(":IdNotaCredito", $idCab, \PDO::PARAM_INT);
            $comando->execute();
            $idDet = $con->getLastInsertID();
            
            //Inserta el IVA de cada Item             
            if ($codigoImp == '2') {//Verifico si el ITEM tiene Impuesto 12%
                //Segun Datos Sri
                $valIvaImp=(floatval($valSinImp)*floatval($por_iva))/100;//Calculo del Valor del Impuesto Generado por Detalle
                $this->InsertarDetImpNC($con, $idDet, '2',$por_iva, $valSinImp, $valIvaImp); //12%
            } else {//Caso Contrario no Genera Impuesto 0%
                $this->InsertarDetImpNC($con, $idDet, '2','0', $valSinImp, '0'); //0%
            }
        }
        //Inserta el Total del Iva Acumulado en el detalle
        //Insertar Datos de Iva 0%
        If ($vet_iva0 > 0) {
            $this->InsertarNcImpuesto($con, $idCab, '2','0', $vet_iva0, $val_iva0);
        }
        //Inserta Datos de Iva 12
        If ($vet_iva12 > 0) {
            $this->InsertarNcImpuesto($con, $idCab, '2', $por_iva, $vet_iva12, $val_iva12);
        }
    }

    private function InsertarDetImpNC($con, $idDet, $codigo, $Tarifa, $t_venta, $val_iva) {
        $CodigoPor= $this->retornaTarifaDelIva($Tarifa);
        $sql = "INSERT INTO " . $con->dbname . ".NubeDetalleNotaCreditoImpuesto
                    (Codigo,CodigoPorcentaje,BaseImponible,Tarifa,Valor,IdDetalleNotaCredito)VALUES
                    (:Codigo,:CodigoPorcentaje,:BaseImponible,:Tarifa,:Valor,:IdDetalleNotaCredito);";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":Codigo", $codigo, \PDO::PARAM_STR);
        $comando->bindParam(":CodigoPorcentaje", $CodigoPor, \PDO::PARAM_STR);
        $comando->bindParam(":BaseImponible", $t_venta, \PDO::PARAM_STR);
        $comando->bindParam(":Tarifa", $Tarifa, \PDO::PARAM_STR);
        $comando->bindParam(":Valor", $val_iva, \PDO::PARAM_STR);
        $comando->bindParam(":IdDetalleNotaCredito", $idDet, \PDO::PARAM_INT);
        $comando->execute();        
    }
    
    private function InsertarNcImpuesto($con, $idCab, $codigo, $Tarifa, $t_venta, $val_iva) {
        $CodigoPor= $this->retornaTarifaDelIva($Tarifa);
        $sql = "INSERT INTO " . $con->dbname . ".NubeNotaCreditoImpuesto
                    (Codigo,CodigoPorcentaje,BaseImponible,Tarifa,Valor,IdNotaCredito)VALUES
                    (:Codigo,:CodigoPorcentaje,:BaseImponible,:Tarifa,:Valor,:IdNotaCredito);";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":Codigo", $codigo, \PDO::PARAM_STR);
        $comando->bindParam(":CodigoPorcentaje", $CodigoPor, \PDO::PARAM_STR);
        $comando->bindParam(":BaseImponible", $t_venta, \PDO::PARAM_STR);
        $comando->bindParam(":Tarifa", $Tarifa, \PDO::PARAM_STR);
        $comando->bindParam(":Valor", $val_iva, \PDO::PARAM_STR);
        $comando->bindParam(":IdNotaCredito", $idCab, \PDO::PARAM_INT);
        $comando->execute();        
    }
    
    private function InsertarNcDatoAdicional($con, $idCab) {
        $dadcEdoc = $this->dadcEdoc;
        for ($i = 0; $i < sizeof($dadcEdoc); $i++) {
            $sql = "INSERT INTO " . $con->dbname . ".NubeDatoAdicionalNotaCredito 
                 (Nombre,Descripcion,IdNotaCredito) VALUES (:Nombre,:Descripcion,:IdNotaCredito);";

            $comando = $con->createCommand($sql);
            $comando->bindParam(":Nombre", $dadcEdoc[$i]['NOMBRECAMPO'], \PDO::PARAM_STR);
            $comando->bindParam(":Descripcion", $dadcEdoc[$i]['VALORCAMPO'], \PDO::PARAM_STR);
            $comando->bindParam(":IdNotaCredito", $idCab, \PDO::PARAM_INT);
            $comando->execute();
        }
    }

    
     /*
     * FIN DE PROCESO DE RETENCIONES
     */
    
    
    /*
     * INICIO DE PROCESO DE GUIAS DE REMISION
     */
    
    private function insertarGuiasRemision() {
        $con = Yii::$app->db_edoc;
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction();
        }
        try {
            //$idCab= $this->insertarCabFactura($con);            
            //$this->InsertarDetFactura($con,$idCab);
            //$this->InsertarFacturaFormaPago($con,$idCab);
            //$this->InsertarFacturaDatoAdicional($con,$idCab);
            //if ($trans !== null){
            //    $trans->commit();
            //}
            //return array("status"=>"OK");
            return array("status"=>"OK", "Ids_Doc"=>$idCab);
        } catch (\Exception $e) {
            $trans->rollBack();
            //throw $e;
            return array("status"=>"NO_OK","error"=>$e);
        }
        
    }
    
    /*
     * FIN DE PROCESO DE GUIAS DE REMISION
     */
    
    /*
     * INICIO DE PROCESO DE NOTAS DE DEBITO
     */
    
    private function insertarEdocNd() {
        $con = Yii::$app->db_edoc;
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction();
        }
        try {
            //$idCab= $this->insertarCabFactura($con);            
            //$this->InsertarDetFactura($con,$idCab);
            //$this->InsertarFacturaFormaPago($con,$idCab);
            //$this->InsertarFacturaDatoAdicional($con,$idCab);
            //if ($trans !== null){
            //    $trans->commit();
            //}
            //return array("status"=>"OK");
            return array("status"=>"OK", "Ids_Doc"=>$idCab);
        } catch (\Exception $e) {
            $trans->rollBack();
            //throw $e;
            return array("status"=>"NO_OK","error"=>$e);
        }
        
    }
    
    /*
     * FIN DE PROCESO DE NOTAS DE DEBITO
     */

}