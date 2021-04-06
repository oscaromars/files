<?php

namespace app\controllers;
use app\modules\academico\models\DistributivoAcademicoSearch;
use Yii;
use app\components\CController;
use app\models\Grupo;
use app\models\Rol;
use app\models\Accion;
use app\models\Modulo;
use app\models\ObjetoModulo;
use app\models\Aplicacion;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;
use app\models\Reporte;
use app\models\Empresa;
use app\modules\financiero\models\CargaCartera;
use app\models\ExportFile;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
academico::registerTranslations();
financiero::registerTranslations();

class ReportesController extends CController {
    
    private function estados() {
        return [
            '0' => Yii::t("formulario", "All"),
            'C' => Yii::t("formulario", "Cancelado"),
            'N' => Yii::t("formulario", "Pendiente"),            
        ];
    }

    public function actionIndex() {    
        $empresa_mod = new Empresa();
        $empresa = $empresa_mod->getAllEmpresa();
        return $this->render('index',[
            'arr_empresa' => ArrayHelper::map(array_merge([["id" => "0", "value" => "Todas"]], $empresa), "id", "value"),
        ]);
    }
    public function actionExpexcelreport(){
        $objDat= new Reporte();
        //$data["estado"]= $_GET["estado"];
        $data["op"]= $_GET["op"];
        $data["f_ini"]= $_GET["f_ini"];
        $data["f_fin"]= $_GET["f_fin"];
        $data["search_dni"]= $_GET["searchdni"];
        $data["empresa_id"]= $_GET["empresa_id"];
        //$data["valor"]= $_GET["valor"];
        switch ($data["op"]) {
            case '1'://GRADO
                $arrData=$objDat->consultarActividadporOportunidad($data);
                $arrHeader = array("N° Oport","Fecha","Empresa","Nombres","Apellidos","Unidad Academica","Estado","Observacion");
                $nombarch = "ActividadesOportunidad-" . date("YmdHis").".xls";
                $nameReport = yii::t("formulario", "Gestión de Contactos");
                break;
            case '2'://POSGRADO
                $arrData=$objDat->consultarOportunidadProximaAten($data);
                $arrHeader = array(
                            "N° Oport","Fecha Atención","Hora Atención","F.Próx.At","Hora Próx.At","Empresa","Cédula",
                            "Nombres Completos", "Canal Contacto",
                            "Estado Oport.", "Observación", "Unidad Academica", "Modalidad", "Carrera", "Agente"
                );
                $nombarch = "EstadoOportunidad-" . date("YmdHis").".xls";
                $nameReport = yii::t("formulario", "Gestión de Contactos");
                break;
            case '3'://Aspirantes
                $arrData=$objDat->consultarAspirantesPendientes($data);
                $arrHeader = 
                        array(
                            "DNI",
                            "Fecha Solicitud",
                            "Num. Solicitud",
                            "Nombres",                        
                            "Apellidos",
                            "Empresa",
                            "Unidad Academica",
                            "Carrera",
                            "Modalidad",
                            "Agente",
                            "Estado Solicitud",
                            "Estado Documentos",
                            "Estado Pago"
                        );                
                $nombarch = "Aspirantes-" . date("YmdHis").".xls";
                $nameReport = yii::t("formulario", "Application Reports");
                break;
        }
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");        
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');               
        // $nameReport = yii::t("formulario", "Application Reports");
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L","M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }
    
    public function actionInscriptos() {  
        $arrAnio = array();
        $objDat= new Reporte();
        $anio=$objDat->getAllAnios();
        for($i=0; $i<count($anio); $i++){
            $arrAnio[$i]['id']=$i;
            $arrAnio[$i]['value']=$anio[$i]['anio'];
        }
        //Utilities::putMessageLogFile($arrAnio);
        return $this->render('inscriptos',[
            'arr_anio' => ArrayHelper::map(array_merge($arrAnio), "id", "value"),
        ]);
    }
    public function actionExpexcelinscriptos(){
        $objDat= new Reporte();
        $arrHeader = array();
        $TotEst = array();
        $EmpCol = array();
        $arrDataNew = array();
        $anio= $_GET["anio"];

        $arrData=$objDat->consultarInscriptos($anio);
        //$arrCabData=$objDat->getAllConvenioEmpresa();
        $nombarch = "Inscriptos-" . date("YmdHis").".xls";
        //Utilities::putMessageLogFile($arrCabData);
        $aux="";
        //Obtener datos de cabercera
        $arrHeader[]="Venta x Mes";
        $arrHeader[]="Estudiantes";
        for($i=0; $i<count($arrData); $i++){            
            if($arrData[$i]['cemp_nombre']!=$aux){
                $columna=$arrData[$i]['cemp_nombre'];
                if(!$this->existeColumna($columna, $arrHeader)){
                    $arrHeader[]=$columna;
                }                
                $aux=$columna;
            }         
        }
        //Utilities::putMessageLogFile($arrHeader);
        //Crear Cuerpo de Datos
        $aux="";
        for($i=0; $i<count($arrData); $i++){  
            if($arrData[$i]['cemp_nombre']!=$aux){
                $sumCol=0;
                $conEmp=$arrData[$i]['cemp_nombre'];//FIjar la columna
                if(!$this->existeColumna($conEmp, $EmpCol)){                    
                    $EmpCol[]=$conEmp;
                    for($j=0; $j<12; $j++){//Recorrer el nuevo Array
                        $arrDataNew[$j]['Mes']=$this->retornaMes($j+1);
                        $arrDataNew[$j]['Total']=0;
                        $cant=$this->numEstudiantes($conEmp, $j+1, $arrData);
                        $arrDataNew[$j][$conEmp]=$cant;
                        $TotEst[$j]+=$cant;
                        $sumCol+=$cant;
                    }
                    $arrDataNew[12]['Mes']='TOTALES';
                    $arrDataNew[12]['Total']=0;
                    $arrDataNew[12][$conEmp]=$sumCol;
                    
                }                
                $aux=$conEmp; 
                
            }
            
        }
        
        //Actualiza Totales
        $sumCol=0;
        for($j=0; $j<12; $j++){
            $arrDataNew[$j]['Total']=$TotEst[$j];
            $sumCol+=$TotEst[$j];
        }
        $arrDataNew[12]['Total']=$sumCol;
        
        
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");        
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');               
        $nameReport = yii::t("formulario", "Application Reports");
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L","M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrDataNew, $colPosition);
        exit;
       
    }
    
    private function existeColumna($columna,$arrHeader) {
        for($i=0; $i<count($arrHeader); $i++){
            //Utilities::putMessageLogFile($arrHeader[$i]);
            if($arrHeader[$i]==$columna){
               return true; 
            }
        }
        return false;
    }
    private function numEstudiantes($Empresa,$Mes,$arrData) {
        $valor =0;
        for($i=0; $i<count($arrData); $i++){
            if($arrData[$i]['MES']==$Mes && $arrData[$i]['cemp_nombre']==$Empresa){
               return $arrData[$i]['CANT'];
            }
        }
        return $valor;
    }      
       
    private function retornaMes($number){
        $valor = "";
        switch ($number){
            case 1: 
                $valor = "Enero";
                break;
            case 2: 
                $valor = "Febrero";
                break;
            case 3: 
                $valor = "Marzo";
                break;
            case 4: 
                $valor = "Abril";
                break;
            case 5: 
                $valor = "Mayo";
                break;
            case 6: 
                $valor = "Junio";
                break;
            case 7: 
                $valor = "Julio";
                break;
            case 8: 
                $valor = "Agosto";
                break;
            case 9: 
                $valor = "Septiembre";
                break;
            case 10: 
                $valor = "Octubre";
                break;
            case 11: 
                $valor = "Noviembre";
                break;
            case 12: 
                $valor = "Diciembre";
                break;
            
        }
        return $valor;
    }
    public function actionCartera() {    
        return $this->render('cartera',[
            'arrEstados' => $this->estados(),
        ]);
    }

    public function actionExpexcelreportcartera(){       
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
        $arrHeader = array(
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "First Names"),
            financiero::t("Pagos", "# Voucher"),
            financiero::t("Pagos", "Amount Fees"),
            financiero::t("Pagos", "Date Bill"),
            financiero::t("Pagos", "Expiration date"),
            financiero::t("Pagos", "Quota value"),
            financiero::t("Pagos", "Value") . ' '. financiero::t("Pagos", "Bill"),
            financiero::t("Pagos", "Pass"),
            Yii::t("formulario", "Payment Status"),
            financiero::t("Pagos", "Balance")
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["f_inif"] = $data['f_inif'];
            $arrSearch["f_finf"] = $data['f_finf'];
            $arrSearch["f_iniv"] = $data['f_iniv'];
            $arrSearch["f_finv"] = $data['f_finv'];
            $arrSearch["estadopago"] = $data['estadopago'];            
        }
        $arrData = array();
        $carga_model = new CargaCartera();
        if (count($arrSearch) > 0) {
            $arrData = $carga_model->consultarReportcartera($arrSearch, true);
        } else {
            $arrData = $carga_model->consultarReportcartera(array(), true);
        }
        $nameReport = academico::t("Pagos", "Cartera");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }
    
    
    public function actionReportdistributivo() {
        $searchModel = new DistributivoAcademicoSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->getListadoDistributivoBloqueDocente($params,false,1);
        return $this->render('reportdistributivo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
         
    }
   
    public function actionExportexcellistadodocente() {
          
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Distributivo -" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("A", "B", "C", "D", "E", "F", "G","H","I","J","K","L");
        $arrHeader = array(           
            "DOCENTE",
            "NO. CÉDULA",
            "TÍTULO TERCER NIVEL",
            "TÍTULO CUARTO NIVEL",
            "CORREO ELECTRÓNICO",
            "TIEMPO DE DEDICACIÓN",
            "DESEMPEÑO",
            "MATERIA",
            "NIVEL",
            "CRÉDITOS",
            "HORAS POR CRÉDITO",
            "TOTAL HORAS A DICTAR",
            
        );
        $params = Yii::$app->request->queryParams;
      //  $model = new \app\modules\academico\models\DistributivoAcademico();
       // $model->mod_id = $params["mod_id"];
      //    \app\models\Utilities::putMessageLogFile($params["mod_id"]);
        $searchModel = new DistributivoAcademicoSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       // $params = Yii::$app->request->queryParams;
        $arrData = $searchModel->getListadoDistributivoBloqueDocente($params,true,2);
       // $distributivo_model = new \app\modules\academico\models\DistributivoAcademico();
        // $arrData = $distributivo_model->getListadoDistributivoBloqueDocente(true);
         foreach ($arrData as $key => $value) {
            unset($arrData[$key]["Id"]);
        }
         $nameReport = "UNIVERSIDAD TECNOLÓGICA EMPRESARIAL DE GUAYAQUIL\n MODALIDAD ONLINE \n DOCENTES AUTORES OCTUBRE - FEBRERO 2021 \n PRIMER BLOQUE";
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
        
    } 
    
    
}
