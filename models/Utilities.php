<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use app\models\Log_errores;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\TemplateProcessor;
use Svrnm\ExcelDataTables\ExcelDataTable;
use Yii;
use yii\base\Exception;
use yii\helpers\Url;

/**
 * Description of Utilities
 *
 * @author eduardocueva
 */
class Utilities {

	public static function sendEmail($titleMessage = "", $from, $to = array(), $subject, $body, $files = array(), $template = "/mail/layouts/mailing", $fileRoute = "/mail/layouts/files", $basePath = NULL) {
		if (function_exists('proc_open')) {
			//self::putMessageLogFile("Mail function exist");
		} else {
			self::putMessageLogFile("Error Mail function not exist");
		}
		$routeBase = (isset($basePath)) ? ($basePath) : (Yii::$app->basePath);
		$socialNetwork = Yii::$app->params["socialNetworks"];

		$mail = Yii::$app->mailer->compose("@app" . $template, [
			'titleMessage' => $titleMessage,
			'body' => $body,
			'socialNetwork' => $socialNetwork,
			'bannerImg' => 'banner.jpg',
			'facebook' => 'facebook.png',
			'twitter' => 'twitter.png',
			'youtube' => 'youtube.png',
			'pathImg' => $routeBase . "/" . $fileRoute . "/",
		]);
		$mail->setFrom($from);
		$mail->setTo($to);
		$mail->setSubject($subject);
		foreach ($files as $key2 => $value2) {
			$mail->attach($value2);
		}
		try {
			$mail->send();
		} catch (\Exception $ex) {
			self::putMessageLogFile($ex);
		}
	}

	/**
	 * Function getMailMessage
	 * @author  Diana Lopez <dlopez@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public static function getMailMessage($file, $slack = array(), $lang = "es", $basePath = NULL) {
		$routeBase = (isset($basePath)) ? ($basePath . "/mail/layouts/messages/") : (Yii::$app->basePath . "/mail/layouts/messages/");
		$content = "";
		if (is_dir($routeBase . $lang)) {
			$routeBase .= $lang . "/" . $file;
		} elseif (is_dir($routeBase . "en")) {
			$routeBase .= "en/" . $file;
		} else {
			return $content;
		}

		if (is_file($routeBase)) {
			$content = file_get_contents($routeBase);
		}

		if (count($slack) > 0) {
			foreach ($slack as $key => $value) {
				$content = str_replace($key, $value, $content);
			}
		}
		return $content;
	}

	/**
	 * Función escribir en log del sistema
	 *
	 * @access public
	 * @author Eduardo Cueva
	 * @param  string $message       Escribe variable en archivo de logs.
	 */
	public static function putMessageLogFile($message) {
		if (is_array($message)) {
			$message = json_encode($message);
		}

		$message = date("Y-m-d H:i:s") . " " . $message . "\n";
		if (!is_dir(dirname(Yii::$app->params["logfile"]))) {
			mkdir(dirname(Yii::$app->params["logfile"]), 0777, true);
			chmod(dirname(Yii::$app->params["logfile"]), 0777);
			touch(Yii::$app->params["logfile"]);
		}
		/*if(filesize(Yii::$app->params["logfile"]) >= Yii::$app->params["MaxFileLogSize"]){
			            $newName = str_replace(".log", "-" . date("YmdHis") . ".log", Yii::$app->params["logfile"]);
			            rename(Yii::$app->params["logfile"], $newName);
			            touch(Yii::$app->params["logfile"]);
			        }
		*/
		file_put_contents(Yii::$app->params["logfile"], $message, FILE_APPEND | LOCK_EX);
	}

	/**
	 * Función escribir en log del sistema segun el nombre del archivo enviado
	 * en el parametro logfile
	 * @access public
	 * @author Galo Aguirre
	 * @param  string $message       Escribe variable en archivo de logs.
	 */
	public static function putMessageLogFile2($message, $logFile) {
		//'logfile' => __DIR__ . '/../runtime/logs/pb.log',
		$logFile = __DIR__ . "/../runtime/logs/" . $logFile . ".log";
		//print_r($logFile);die();
		if (is_array($message)) {
			$message = json_encode($message);
		}

		$message = date("Y-m-d H:i:s") . " " . $message . "\r\n";

		if (!is_dir(dirname(Yii::$app->params["logfile"]))) {
			mkdir(dirname(Yii::$app->params["logfile"]), 0777, true);
			chmod(dirname(Yii::$app->params["logfile"]), 0777);
			touch(Yii::$app->params["logfile"]);
		}

		//print_r($logFile);die();
		/*
	        if ((filesize($logFile) / pow(1024, 2)) > 100) { // si el log es mayor a 100 MB entonces se debe limpiar el archivo
	            file_put_contents($logFile, $message, LOCK_EX);
	        } else {
	            file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
	        }
*/
		file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
	} //function putMessageLogFile2

	/**
	 * Función que devuelve la ip del usuario en session
	 *
	 * @access public
	 * @author Eduardo Cueva
	 * @return string   $ip         Retorna la IP del cliente o usuario
	 */
	public static function getClientRealIP() {
		$ip = $_SERVER['REMOTE_ADDR'];
		if (!empty($_SERVER['HTTP_CLIENT_IP']) && self::validateTypeField($_SERVER['HTTP_CLIENT_IP'], 'ip')) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}

		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && self::validateTypeField($_SERVER['HTTP_X_FORWARDED_FOR'], 'ip')) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		return $ip;
	}

	/**
	 * Function ajaxResponse
	 * @author  Diana Lopez <dlopez@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public static function ajaxResponse($status, $type, $label, $error, $message, $addicionalData = array()) {
		$arroout = array();
		$arroout["status"] = $status;
		$arroout["type"] = $type;
		$arroout["label"] = $label;
		$arroout["error"] = $error;
		$arroout["message"] = $message;
		if (count($addicionalData) > 0) {
			$arroout["data"] = $addicionalData;
		}
		return json_encode($arroout);
	}

	public static function createTemporalFile($filename) {
		$nombre_tmp = tempnam(sys_get_temp_dir() . '/' . $filename . "_" . date("Ymdhis"), "PB");
		return $nombre_tmp;
	}

	public static function removeTemporalFile($filename) {
		unlink($filename);
	}

	/**
	 * Función que mueve un archivo a otro directorio
	 *
	 * @access public
	 * @author Eduardo Cueva
	 * @param string $dirFileIni Directorio Inicial
	 * @param string $dirFileEnd Directorio Final
	 * @return bool       Estado del movimiento del archivo
	 */
	public static function moveUploadFile($dirFileIni, $dirFileEnd) {
		$dirFileEnd = Yii::$app->basePath . str_replace("../", "", $dirFileEnd);
		if (is_file($dirFileIni)) {
			if (self::verificarDirectorio(dirname($dirFileEnd))) {
				if (move_uploaded_file($dirFileIni, $dirFileEnd)) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Función que cambia la dimenision de una imagen
	 *
	 * @access public
	 * @author Eduardo Cueva
	 * @param string $dirImg Ruta de la Imagen
	 * @param string $newwidth Ancho de la imagen. Ejemplo: 600
	 * @param string $newheight Altura de la imagen. Ejemplo: 500
	 * @return bool       Estado del movimiento del archivo
	 */
	public static function changeSizeImage($dirImg, $newwidth, $newheight, $x1 = 0, $y1 = 0, $w = 0, $h = 0) {
		$w = ($w != 0) ? $w : $newwidth;
		$h = ($h != 0) ? $h : $newheight;

		$arrIm = explode(".", $dirImg);
		$typeImage = $arrIm[count($arrIm) - 1];
		$dirImg = Yii::$app->basePath . str_replace("../", "", $dirImg);

		list($width_old, $height_old) = getimagesize($dirImg);

		$thumb = imagecreatetruecolor($newwidth, $newheight);

		if (strtolower($typeImage) == "png") {
			$source = imagecreatefrompng($dirImg);
			imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width_old, $height_old);
			imagepng($thumb, $dirImg, 100);

			$im = imagecreatefrompng($dirImg);
			$dest = imagecreatetruecolor($w, $h);

			imagecopyresampled($dest, $im, 0, 0, $x1, $y1, $w, $h, $w, $h);
			imagepng($dest, $dirImg, 100);
		} else {
			$source = imagecreatefromjpeg($dirImg);
			imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width_old, $height_old);
			imagejpeg($thumb, $dirImg, 100);

			$im = imagecreatefromjpeg($dirImg);
			$dest = imagecreatetruecolor($w, $h);

			imagecopyresampled($dest, $im, 0, 0, $x1, $y1, $w, $h, $w, $h);
			imagejpeg($dest, $dirImg, 100);
		}
	}

	public static function getDocumentRoot($http = null) {
		if ($http) {
			return Url::base(true);
		}

		return Url::base();
		//Url::base('http');
		//Url::base('https');
	}

	public static function getBaseDirRoot() {
		return Yii::$app->basePath;
	}

	/**
	 * Función que cambia la extension de una imagen a jpg
	 *
	 * @access public
	 * @author Eduardo Cueva
	 * @param string $dirImg Ruta de la Imagen
	 * @param string $ext Extension que se desea cambiar
	 * @return bool       Estado del movimiento del archivo
	 */
	public static function changeIMGtoJPG($dirImg, $destino = NULL) {
		$dirImg = Yii::$app->basePath . str_replace("../", "", $dirImg);
		try {
			if (is_file($dirImg)) {
				$arrIm = explode(".", basename($dirImg));
				$typeImage = $arrIm[count($arrIm) - 1];
				if (strtolower($typeImage) == "png") {
					$image = imagecreatefrompng($dirImg);
					$newFile = preg_replace("/\.(png|Png|PNG)$/", '.jpeg', $dirImg);
					if (isset($destino)) {
						$newFile = Yii::$app->basePath . str_replace("../", "", $destino);
					}

					imagejpeg($image, $newFile, "100");
					imagedestroy($image);
					unlink($dirImg);
					return true;
				}if (strtolower($typeImage) == "gif") {
					$image = imagecreatefromgif($dirImg);
					$newFile = preg_replace("/\.(gif|Gif|GIF)$/", '.jpeg', $dirImg);
					if (isset($destino)) {
						$newFile = Yii::$app->basePath . str_replace("../", "", $destino);
					}

					imagejpeg($image, $newFile, "100");
					imagedestroy($image);
					unlink($dirImg);
					return true;
				}
			}
			return false;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Función que cambia la extension de una imagen a jpg
	 *
	 * @access public
	 * @author Eduardo Cueva
	 * @param string    $icon Nombre del Icono
	 * @return string         Devuelve string css del icono
	 */
	public static function getIcon($icon) {
		$cssIcon = "";
		switch ($icon) {
		case 'edit':
			$cssIcon = "glyphicon glyphicon-pencil";
			break;
		case 'view':
			$cssIcon = "glyphicon glyphicon-eye-open";
			break;
		case 'remove':
			$cssIcon = "glyphicon glyphicon-remove";
			break;
		case 'download':
			$cssIcon = "glyphicon glyphicon-save";
			break;
		case 'info':
			$cssIcon = "glyphicon glyphicon-info-sign";
			break;
		}
		return $cssIcon;
	}

	/**
	 * Función que verifica si un directorio existe caso contrario intenta crearlo
	 *
	 * @access public
	 * @author Eduardo Cueva
	 * @param  string   $folder     Directorio a verificar si existe o no
	 * @return bool     $bool       Retorna la IP del cliente o usuario
	 */
	public static function verificarDirectorio($folder) {
		if (!file_exists($folder)) {
			if (mkdir($folder, 0755, true)) {
				//chown($folder, Yii::$app->params['userWebServer']);
				return true;
			} else {
				self::putMessageLogFile("Error: System cannot create folder: $folder");
				return false;
			}
		} else {
			return true;
		}

	}

	/**
	 * Función que crea desencripta un mensaje a traves de una clave utilizando AES con metodo de encriptacion
	 *
	 * @access public
	 * @author Eduardo Cueva
	 * @param  string   $filename        Archivo a obtener el content type.
	 * @return string   $contentType     Content Type del Archivo.
	 */
	public static function mimeContentType($filename) {

		$mime_types = array(
			'txt' => 'text/plain',
			'htm' => 'text/html',
			'html' => 'text/html',
			'php' => 'text/html',
			'css' => 'text/css',
			'js' => 'application/javascript',
			'json' => 'application/json',
			'xml' => 'application/xml',
			'swf' => 'application/x-shockwave-flash',
			'flv' => 'video/x-flv',
			// images
			'png' => 'image/png',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'ico' => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'svg' => 'image/svg+xml',
			'svgz' => 'image/svg+xml',
			// archives
			'zip' => 'application/zip',
			'rar' => 'application/x-rar-compressed',
			'exe' => 'application/x-msdownload',
			'msi' => 'application/x-msdownload',
			'cab' => 'application/vnd.ms-cab-compressed',
			// audio/video
			'mp3' => 'audio/mpeg',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',
			// adobe
			'pdf' => 'application/pdf',
			'psd' => 'image/vnd.adobe.photoshop',
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',
			// ms office
			'doc' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			// open office
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);

		$ext = strtolower(array_pop(explode('.', $filename)));
		if (array_key_exists($ext, $mime_types)) {
			return $mime_types[$ext];
		} elseif (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME);
			$mimetype = finfo_file($finfo, $filename);
			finfo_close($finfo);
			return $mimetype;
		} else {
			return 'application/octet-stream';
		}
	}

	/**
	 * Devuelve un Objeto XML a partir de un Arreglo
	 *
	 * @access public
	 * @author http://darklaunch.com/2009/05/23/php-xml-encode-using-domdocument-convert-array-to-xml-json-encode
	 * @param mixed $mixed  Arreglo de datos
	 * @param mixed $domElement     Nodo del Documento padre o elemento
	 * @param mixed $DOMDocument    Nodo del Documento principal
	 * @return string   $ip         Retorna la IP del cliente o usuario
	 */
	public static function xml_encode($mixed, $domElement = null, $DOMDocument = null) {
		if (is_null($DOMDocument)) {
			$DOMDocument = new \DOMDocument();
			$DOMDocument->formatOutput = true;
			self::xml_encode($mixed, $DOMDocument, $DOMDocument);
			return $DOMDocument->saveXML();
		} else {
			if (is_array($mixed)) {
				foreach ($mixed as $index => $mixedElement) {
					if (is_int($index)) {
						if ($index === 0) {
							$node = $domElement;
						} else {
							$node = $DOMDocument->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					} else {
						$plural = $DOMDocument->createElement($index);
						$domElement->appendChild($plural);
						$node = $plural;
						if (!(rtrim($index, 's') === $index)) {
							$singular = $DOMDocument->createElement(rtrim($index, 's'));
							$plural->appendChild($singular);
							$node = $singular;
						}
					}

					self::xml_encode($mixedElement, $node, $DOMDocument);
				}
			} else {
				$mixed = is_bool($mixed) ? ($mixed ? 'true' : 'false') : $mixed;
				$domElement->appendChild($DOMDocument->createTextNode($mixed));
			}
		}
	}

	public static function base64_url_encode($input) {
		return strtr(base64_encode($input), '+/=', '._-');
	}

	public static function base64_url_decode($input) {
		return base64_decode(strtr($input, '._-', '+/='));
	}

	public static function generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition = array(), $typeExp = "Xls", $emp_id = null) {
		if (is_null($emp_id)) {
			$emp_id = Yii::$app->session->get('PB_idempresa');
		}
		if (count($colPosition) == 0) {
			$colPosition = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U");
		}
		if (count($arrData) == 0) {
			echo Yii::t("reporte", "No Reports");
			return;
		}
		if (count($arrHeader) == 0) {
			echo Yii::t("reporte", "No Reports");
			return;
		}
		$negrita = array(
			'font' => array(
				'bold' => true,
			),
		);
		$border = array(
			'allborders' => array(
				'borders' => array(
					'allborders' => array(
						'style' => Border::BORDER_THIN,
						'color' => array('argb' => 'FF000000'),
					),
				),
			),
			'top' => array(
				'borders' => array(
					'top' => array(
						'style' => Border::BORDER_MEDIUM,
						'color' => array('argb' => 'FF000000'),
					),
				),
			),
			'bottom' => array(
				'borders' => array(
					'bottom' => array(
						'style' => Border::BORDER_MEDIUM,
						'color' => array('argb' => 'FF000000'),
					),
				),
			),
			'right' => array(
				'borders' => array(
					'right' => array(
						'style' => Border::BORDER_MEDIUM,
						'color' => array('argb' => 'FF000000'),
					),
				),
			),
			'left' => array(
				'borders' => array(
					'left' => array(
						'style' => Border::BORDER_MEDIUM,
						'color' => array('argb' => 'FF000000'),
					),
				),
			),
		);
		try {
			$objPHPExcel = new Spreadsheet();
			$objPHPExcel->getProperties()->setCreator(Yii::$app->session->get("PB_nombres"))
				->setLastModifiedBy(Yii::$app->session->get("PB_nombres"))
				->setTitle("Office 2007 XLSX")
				->setSubject("Office 2007 XLSX $nombarch")
				->setDescription("$nombarch for Office 2007 XLSX, generated using PHP classes.")
				->setKeywords("office 2007 openxml php")
				->setCategory("$nombarch result file");
			$objPHPExcel->getActiveSheet()->mergeCells('C6:D6');
			$objPHPExcel->getActiveSheet()->mergeCells('C7:D7');
			$objPHPExcel->getActiveSheet()->mergeCells('C4:N4');
			$objPHPExcel->getActiveSheet()->getStyle("C4")->getFont()->setSize(36);
			$objPHPExcel->getActiveSheet()->getStyle("C4")->getFont()->setBold(True);
			$objPHPExcel->getActiveSheet()->getStyle("C6")->getFont()->setSize(16);
			$objPHPExcel->getActiveSheet()->getStyle("C6")->getFont()->setBold(True);
			$objPHPExcel->getActiveSheet()->getStyle("E6")->getFont()->setSize(16);
			$objPHPExcel->getActiveSheet()->getStyle("C7")->getFont()->setSize(16);
			$objPHPExcel->getActiveSheet()->getStyle("C7")->getFont()->setBold(True);
			$objPHPExcel->getActiveSheet()->getStyle("E7")->getFont()->setSize(16);

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C4', $nameReport)
				->setCellValue('C6', Yii::t("reporte", "Produced by"))
				->setCellValue('E6', Yii::$app->session->get("PB_nombres"))
				->setCellValue('C7', Yii::t("reporte", "Date"))
				->setCellValue('E7', date("Y-m-d H:i:s"));

			// seteo de bordes cabecera de reporte
			$objPHPExcel->getActiveSheet()->getStyle("B2:S2")->applyFromArray($border["top"]);
			$objPHPExcel->getActiveSheet()->getStyle("B10:S10")->applyFromArray($border["bottom"]);
			$objPHPExcel->getActiveSheet()->getStyle("B2:B10")->applyFromArray($border["left"]);
			$objPHPExcel->getActiveSheet()->getStyle("S2:S10")->applyFromArray($border["right"]);
			$objPHPExcel->getActiveSheet()->getStyle("B$i:D$i")->applyFromArray($border);

			$objDrawing = new drawing();
			$objDrawing->setName('Logo');
			$objDrawing->setDescription('Logo');
			$objDrawing->setPath(Yii::$app->basePath . "/themes/" . Yii::$app->view->theme->themeName . "/assets/img/logos/logo_" . $emp_id . ".png");
			//$objDrawing->setHeight(80);
			$objDrawing->setWidth(300);
			$objDrawing->setCoordinates('O4');
			//$objDrawing->setOffsetX(1);
			//$objDrawing->setOffsetY(5);
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

			$i = '12';
			//$i = '1';

			for ($i = 0; $i < count($arrHeader); $i++) {
				$j = 12;
				$objPHPExcel->getActiveSheet()->getStyle($colPosition[$i] . $j)->getFont()->setBold(True);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colPosition[$i] . $j, $arrHeader[$i]);
			}
			$i = 12;
			//$i = 1;
			foreach ($arrData as $key => $value) {
				$k = 0;
				$j = $i + 1;
				foreach ($value as $key2 => $value2) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colPosition[$k] . $j, $value2);
					$k++;
				}
				$i++;
			}
			$objWriter = IOFactory::createWriter($objPHPExcel, $typeExp);
			$objWriter->save('php://output');
		} catch (\Exception $e) {
			echo Yii::t("reporte", "Error to export Excel");
		}

	}

	public static function writeReporteXLS($uriFile, $arrHeader, $arrData, $sheetName = "DATA") {
		if (count($arrData) == 0) {
			echo Yii::t("reporte", "No Reports");
			return;
		}
		if (count($arrHeader) == 0) {
			echo Yii::t("reporte", "No Reports");
			return;
		}
		try {

			$dataTable = new ExcelDataTable();
			$data = array();
			for ($i = 0; $i < count($arrData); $i++) {
				$j = 0;
				foreach ($arrData[$i] as $key => $value) {
					$data[$i][$arrHeader[$j]] = $value;
					$j++;
				}
			}
			$dataTable->setSheetName($sheetName);
			$dataTable->showHeaders()->addRows($data);
			return $dataTable->fillXLSX($uriFile); //attachToFile($uriFile, $out, false);

		} catch (\Exception $e) {
			echo Yii::t("reporte", "Error to export Excel");
		}
	}

	/**
	 * Devuelve un Documento en Word en memoria o en archivo
	 *
	 * @access public
	 *
	 * @author https://github.com/PHPOffice/PHPWord/tree/master
	 * @author https://phpword.readthedocs.io/en/latest/intro.html
	 *
	 * @param mixed $filename           Nombre del archivo de salida o de entrada en el caso de que sea un plantilla
	 * @param mixed $titulo             Es el titulo o Heading Line que va en el Contenido del Archivo
	 * @param mixed $body               Es el cuerpo o texto que tiene el documento. En el caso de estar activado $isHtml entonces el contenido es HTML
	 * @param mixed $isHtml             Si es verdadero se podra ingresar contenido Html en el parametro $body
	 * @param mixed $loadFile           Si es verdadero se podra cargar el archivo que se recibe en el parametro $filename como archivo template
	 * @param mixed $loadFileValues     Arreglo de variables que se van a reemplazar en el archivo plantilla, Ejemplo: ['CODE' => $code, 'id' => 456]  Eso quiere decir que en el archivo template debe haber conenido con las etiquetas: ${CODE} y ${id}
	 * @param mixed $isDownloaded       Si es verdadero el archivo resultado se cargara en memoria, caso contrario se creara un archivo en la ruta definida en el parametro $filename
	 * @param mixed $landscape          Si es verdadero la orientacion de Word sera landscape caso contratio portrait
	 * @param mixed $emp_id             Se ingresa el Id de la Empresa, si se recibe NULL se carga el Id de la empresa que esta en sesion
	 * @return void                 En memoria el contenido del Doc en Word
	 */
	public static function generarReportDoc($filename, $titulo, $body = "", $isHtml = false, $loadFile = false, $loadFileValues = array(), $isDownloaded = true, $landscape = false, $emp_id = null) {

		try {
			if (is_null($emp_id)) {
				$emp_id = Yii::$app->session->get('PB_idempresa');
			}
			$imgEmp = Yii::$app->basePath . "/themes/" . Yii::$app->view->theme->themeName . "/assets/img/logos/logo_" . $emp_id . ".png";
			Settings::loadConfig();

			// Turn output escaping on
			Settings::setOutputEscapingEnabled(true);

			if (!$loadFile) {
				// $phpWord = IOFactoryWd::load($source);
				$phpWord = new PhpWord();
				$setSection = array(
					//'marginLeft'   => 1400,
					//'marginRight'  => 1400,
					//'marginTop'    => 1800,
					//'marginBottom' => 1000,
					'headerHeight' => 50,
					//'footerHeight' => 50,
				);
				if ($landscape) {
					$setSection['orientation'] = 'landscape';
				}

				// IF Doc have a Password
				//$documentProtection = $phpWord->getSettings()->getDocumentProtection();
				//$documentProtection->setEditing(DocProtect::READ_ONLY);
				//$documentProtection->setPassword('myPassword');

				// ADD Watermark
				//$section = $phpWord->addSection();
				//$header = $section->addHeader();
				//$header->addWatermark('image.jpeg', array('marginTop' => 200, 'marginLeft' => 55));
				//$section->addText('Matermark Text');

				// Adding Text element to the Section having font styled by default...
				$fontStylePbTitle = 'rStylePbTitle';
				$phpWord->addFontStyle($fontStylePbTitle, array(
					'name' => 'Arial',
					'bold' => true,
					//'italic' => true,
					'size' => 26,
					'allCaps' => true,
					//'doubleStrikethrough' => true
				));

				$paragraphStylePbTitle = 'pStylePbTitle';
				$phpWord->addParagraphStyle($paragraphStylePbTitle, array(
					'alignment' => Jc::CENTER,
					'spaceAfter' => 100,
					'spaceBefore' => 100,
					'spacing' => 10,
					'lineHeight' => 2,
				));

				$fontStylePbBody = 'rStylePbBody';
				$phpWord->addFontStyle($fontStylePbBody, array(
					'name' => 'Arial',
					'size' => 10,
				));

				$paragraphStylePbBody = 'pStylePbBody';
				$phpWord->addParagraphStyle($paragraphStylePbBody, array(
					'alignment' => Jc::START,
				));

				//$phpWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => 240));

				// Adding an empty Section to the document...
				$section = $phpWord->addSection($setSection);

				// $wrappingStyles = array('inline', 'behind', 'infront', 'square', 'tight');
				// Setting Header
				// Add first page header
				$header = $section->addHeader();
				//$header->firstPage(); // Only Header first page
				$table = $header->addTable(array('cellMargin' => 0, 'cellMarginTop' => 500, 'cellMarginRight' => 0, 'cellMarginBottom' => 0, 'cellMarginLeft' => 0));
				$table->addRow();
				$table->addCell(2500)->addImage($imgEmp, array(
					'width' => 120,
					//'height' => 80,
					'alignment' => Jc::START));

				// Setting Footer
				$footer = $section->addFooter();
				//$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', null, array('alignment' => Jc::CENTER));
				$footer->addPreserveText('- {PAGE} -', null, array('alignment' => Jc::CENTER));

				if (!$isHtml) {
					// $section->addTitle('Title Heading 1', 1); // Titulo
					$section->addTextBreak(); // enter in text
					$section->addText($titulo, $fontStylePbTitle, $paragraphStylePbTitle);
					$section->addTextBreak(); // enter in text
					$section->addText($body, $fontStylePbBody, $paragraphStylePbBody);
					$section->addTextBreak(); // enter in text
				} else {
					Html::addHtml($section, $body, false, false); // IF body is HTML Content
				}

				//// Adding Text element with font customized using explicitly created font style object...
				//$fontStyle = new Font();
				//$fontStyle->setBold(true);
				//$fontStyle->setName('Tahoma');
				//$fontStyle->setSize(13);
				//$myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
				//$myTextElement->setFontStyle($fontStyle);

				// $section->addPageBreak(); // Salto de Linea

				// Load Template and replace for value
				////$templateProcessor = new TemplateProcessor('TemplateRow.docx');
				//// Variables on different parts of document
				//$templateProcessor->setValue('weekday', date('l'));            // On section/content
				//$templateProcessor->setValue('time', date('H:i'));             // On footer
				//$templateProcessor->setValue('serverName', realpath(__DIR__)); // On header
				//// Simple table
				//$templateProcessor->cloneRow('rowValue', 10);
				//$templateProcessor->setValue('rowValue#1', 'Sun');
				//$templateProcessor->setValue('rowValue#2', 'Mercury');
				//$templateProcessor->setValue('rowValue#3', 'Venus');
				//$templateProcessor->setValue('rowNumber#1', '1');
				//$templateProcessor->setValue('rowNumber#2', '2');
				//$templateProcessor->setValue('rowNumber#3', '3');
				//// Table with a spanned cell
				/*$values = array(
	                    array(
	                        'userId'        => 1,
	                        'userFirstName' => 'James',
	                        'userName'      => 'Taylor',
	                        'userPhone'     => '+1 428 889 773',
	                    ),
*/
				//$templateProcessor->cloneRowAndSetValues('userId', $values);
				//$templateProcessor->saveAs('results.docx');

				// Load Template and replace for Block
				//$templateProcessor = new TemplateProcessor('TemplateBlock.docx');
				// Will clone everything between ${tag} and ${/tag}, the number of times. By default, 1.
				//$templateProcessor->cloneBlock('CLONEME', 3);
				// Everything between ${tag} and ${/tag}, will be deleted/erased.
				//$templateProcessor->deleteBlock('DELETEME');
				//$templateProcessor->saveAs('results.docx');

				// Saving the document
				//$objWriter = IOFactoryWd::createWriter($phpWord, 'Word2007');
				//$objWriter->save('php://output', $typeExp);
				if (!$isDownloaded) {
					$phpWord->save($filename);
				} else {
					$phpWord->save('php://output');
				}

			} else {
				$tempFile = Utilities::createTemporalFile(basename($filename));
				// Load Template and replace for value
				$templateProcessor = new TemplateProcessor($filename);
				//// Variables on different parts of document
				foreach ($loadFileValues as $key => $value) {
					$templateProcessor->setValue($key, $value);
				}
				$templateProcessor->saveAs($tempFile);
				readfile($tempFile);
				Utilities::removeTemporalFile($tempFile);
			}

		} catch (\Exception $e) {
			echo Yii::t("reporte", "Error to export Word");
		}
	}

	public static function zipFiles($nombreZip, $arr_files = array()) {
		$zip = new \ZipArchive();
		$filename = self::createTemporalFile($nombreZip);

		if ($zip->open($filename, \ZipArchive::CREATE) !== TRUE) {
			self::putMessageLogFile("cannot open <$filename>");
		}
		for ($i = 0; $i < count($arr_files); $i++) {
			$zip->addFile($arr_files[$i]["ruta"], $arr_files[$i]["name"]);
		}
		$zip->close();
		return $filename;
	}

	public static function getLoginUrl() {
		$link = '/site/login';
		if (Yii::$app->session->get('PB_idempresa') != null && Yii::$app->session->get('PB_idempresa') != 1) {
			$link = '/site/loginemp';
		}
		return $link;
	}

	public static function genero() {
		return [
			//'0' => Yii::t("formulario", "-Select-"),
			'M' => Yii::t("perfil", "Male"),
			'F' => Yii::t("perfil", "Female"),
			'G' => Yii::t("perfil", "GLBT"),
		];
	}

	public static function validateTypeField($field, $type) {
		$status = false;
		switch ($type) {
		case 'ip': //solo ip
			if (preg_match("/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $field)) {
				$status = true;
			}

			break;
		case 'number': //solo numeros
			if (preg_match("/^(?:\+|-)?\d+$/", $field)) {
				$status = true;
			}

			break;
		case 'alfa': //solo letras
			if (preg_match("/^([a-zA-ZáéíóúÁÉÍÓÚÑñ '])+$/", $field)) {
				$status = true;
			}

			break;
		case 'alfanumerico':
			if (preg_match("/^([a-zA-Z áéíóúÁÉÍÓÚÑñ0-9])+$/", $field)) {
				$status = true;
			}

			break;
		case 'direccion':
			if (preg_match("/^([a-zA-Z áéíóúÁÉÍÓÚÑñ0-9 ./-])+$/", $field)) {
				$status = true;
			}

			break;
		case 'email': //email
			if (preg_match("/^[\w\-\.]{3,}@([\w\-]{2,}\.)*([\w\-]{2,}\.)[\w\-]{2,4}$/", $field)) {
				$status = true;
			}

			break;
		case 'telefono':
			if (preg_match("/^(((\d{6,9}[ ]?\/[ ]?)(\d{6,9}[ ]?\/[ ]?)*\d{6,9})|(\d{6,9}))$/", $field)) {
				$status = true;
			}

			break;
		case 'celular':
			if (preg_match("/^(((\d{9,13}[ ]?\/[ ]?)(\d{9,10}[ ]?\/[ ]?)*\d{9,13})|(\d{9,13}))$/", $field)) {
				$status = true;
			}

			break;
		case 'dinero':
			if (preg_match("/^((\d{1,9})(\.\d{1,2})?)$/", $field)) {
				$status = true;
			}

			break;
		case 'fecha':
			if (preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])/", $field)) {
				$status = true;
			}

			break;
		case 'tiempo':
			if (preg_match("/^(0[1-9]|1\d|2[0-3]):([0-5]\d)$/", $field)) {
				$status = true;
			}

			break;
		case 'url':
			if (preg_match("/^(http|https)\:\/\/[a-z0-9\.-]+\.[a-z]{2,4}/", $field)) {
				$status = true;
			}

			break;
		default: // all
			if (preg_match("/^(.|\n)+$/", $field)) {
				$status = true;
			}

			break;
		}
		return $status;
	}

	public static function validateToken($tokenID, $numberSecret) {
		return true; // remover esto porque se debe validar
		if ($tokenID === Yii::$app->params['tokenid'] && $numberSecret === Yii::$app->params['numbersecret']) {
			return true;
		}
		return false;
	}

	public static function Meses() {
		return [
			//'0' => Yii::t("formulario", "-Select-"),
			'1' => Yii::t("perfil", "Enero"),
			'2' => Yii::t("perfil", "Febrero"),
			'3' => Yii::t("perfil", "Marzo"),
			'4' => Yii::t("perfil", "Abril"),
			'5' => Yii::t("perfil", "Mayo"),
			'6' => Yii::t("perfil", "Junio"),
			'7' => Yii::t("perfil", "Julio"),
			'8' => Yii::t("perfil", "Agosto"),
			'9' => Yii::t("perfil", "Septiembre"),
			'10' => Yii::t("perfil", "Octubre"),
			'11' => Yii::t("perfil", "Noviembre"),
			'12' => Yii::t("perfil", "Diciembre"),
		];
	}

	public static function add_ceros($numero, $ceros) {
		/* Ejemplos para usar.
			          $numero="123";
		*/
		$order_diez = explode(".", $numero);
		$dif_diez = $ceros - strlen($order_diez[0]);
		for ($m = 0; $m < $dif_diez; $m++) {
			@$insertar_ceros .= 0;
		}
		return $insertar_ceros .= $numero;
	}

	public static function getDiffDate($fechaInicio, $fechaFin) {
		$ano1 = date('Y', strtotime($fechaInicio));
		$mes1 = date('m', strtotime($fechaInicio));
		$dia1 = date('d', strtotime($fechaInicio));
		$hora1 = date('H', strtotime($fechaInicio));
		$min1 = date('i', strtotime($fechaInicio));
		$seg1 = date('s', strtotime($fechaInicio));

		//defino fecha 2
		$ano2 = date('Y', strtotime($fechaFin));
		$mes2 = date('m', strtotime($fechaFin));
		$dia2 = date('d', strtotime($fechaFin));
		$hora2 = date('H', strtotime($fechaFin));
		$min2 = date('i', strtotime($fechaFin));
		$seg2 = date('s', strtotime($fechaFin));

		//calculo timestam de las dos fechas
		$timestamp1 = mktime($hora1, $min1, $seg1, $mes1, $dia1, $ano1);
		$timestamp2 = mktime($hora2, $min2, $seg2, $mes2, $dia2, $ano2);

		//resto a una fecha la otra
		$segundos_diferencia = $timestamp2 - $timestamp1;

		//convierto segundos en días
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

		//obtengo el valor absoulto de los días (quito el posible signo negativo)
		//$dias_diferencia = abs($dias_diferencia);

		//quito los decimales a los días de diferencia
		$dias_diferencia = floor($dias_diferencia);

		return $dias_diferencia;
	}

	/**
	 * Devuelve una conexion al Web Service de Educativa
	 * @access public
	 * @author Galo Aguirre
	 */
	public static function getWseducativa() {
		$contextOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			));

		$sslContext = stream_context_create($contextOptions);

		$client = 0;

		$params = array(
			"login" => Yii::$app->params["wsLogin"],
			"password" => Yii::$app->params["wsPassword"],
			'trace' => 1,
			'exceptions' => true,
			'cache_wsdl' => WSDL_CACHE_NONE,
			'soap_version' => SOAP_1_1,
			'stream_context' => $sslContext,
		);

		try {
			$client = new \SoapClient(Yii::$app->params["url"], $params);
		} catch (\Exception $e) {
			Self::logerror("getWseducativa",
				"Error de Conexion",
				$e->getMessage(),
				$e->getCode());
			//var_dump(libxml_get_last_error());
			//var_dump($proxy);
			$client = 0;
		} catch (\SoapFault $e) {
			// \
			Self::logerror("getWseducativa",
				"Error de Conexion",
				$e->getMessage(),
				$e->getCode());
			//var_dump(libxml_get_last_error());
			//var_dump($proxy);
			$client = 0;
		}
		return $client;
	} //function getWseducativa

	/**
	 * Realiza el pago de stripe y devuelve los valores de exito o los
	 * mensajes de error correspondiente
	 * @access public
	 * @author Galo Aguirre
	 */
	public static function stripecheckout($token, $name, $email, $value, $mensajepago) {
		//MENSAJES DE RESPUESTA
		$ordStatus = 'error';
		$detallepago = '';
		$mensaje_error = '';
		$mensaje_cod = '';

		/******************************************************************/
		/********** Clave de Conexión de Stripe ***************************/
		/******************************************************************/
		$stripe = array(
			'secret_key' => Yii::$app->params["secret_key"],
		);

		//Se hace invocacion a libreria de stripe que se encuentra en el vendor
		\Stripe\Stripe::setApiKey($stripe['secret_key']);

		try {
			//Se crea el usuario para stripe
			$customer = \Stripe\Customer::create(array(
				'email' => $email,
				'source' => $token,
			));
		} catch (\Stripe\Exception\CardException $e) {
			$mensaje_error = $e->getError()->message . "(" . $e->getError()->code . ")";
			$mensaje_cod = $e->getError()->code;
			$bandera = 1;
		} catch (\Stripe\Exception\RateLimitException $e) {
			//Too many requests made to the API too quickly
			$mensaje_error = $e->getError()->message;
			$mensaje_cod = $e->getError()->code;
			$bandera = 2;
		} catch (\Stripe\Exception\InvalidRequestException $e) {
			//Invalid parameters were supplied to Stripe's API
			$mensaje_error = $e->getError()->message;
			$mensaje_cod = $e->getError()->code;
			$bandera = 3;
		} catch (\Stripe\Exception\AuthenticationException $e) {
			//Authentication with Stripe's API failed
			//(maybe you changed API keys recently)
			$mensaje_error = $e->getError()->message;
			$mensaje_cod = $e->getError()->code;
			//print_r($e->getError());die();
			$bandera = 4;
		} catch (\Stripe\Exception\ApiConnectionException $e) {
			//Network communication with Stripe failed
			$mensaje_error = $e->getError()->message;
			$mensaje_cod = $e->getError()->code;
			$bandera = 5;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			//Display a very generic error to the user, and maybe send
			//yourself an email
			$mensaje_error = $e->getError()->message;
			$mensaje_cod = $e->getError()->code;
			$bandera = 6;
		} catch (\Stripe\Error\Base $e) {
			$mensaje_error = $e->getError()->message;
			$mensaje_cod = $e->getError()->code;
			$bandera = 7;
		} catch (Exception $e) {
			// Something else happened, completely unrelated to Stripe
			$mensaje_error = $e->getError()->message;
			$mensaje_cod = $e->getError()->code;
			$bandera = 8;
		}

		if ($mensaje_cod != '' || $mensaje_error != '') {
			if ($mensaje_cod == '') {
				$mensaje_cod = $mensaje_error;
			}

			$message = array(
				"wtmessage" => Yii::t("facturacion", $mensaje_cod),
				"title" => Yii::t('jslang', 'Error'),
				"ordStatus" => $ordStatus,
				"detallepago" => $detallepago,
				"mensaje_error" => $mensaje_error,
				"mensaje_cod" => $mensaje_cod,
			);
			//echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message); return;
			return $message;
		} //if

		//Si se creo el usuario y no hay error
		//print_r($mensaje_cod);die();
		if (empty($mensaje_cod) && $customer) {
			//El valor se multiplica por 100 para convertirlo a centavos
			$itemPriceCents = ($value * 100);

			//Se crea el cobro
			/*
	            print_r($customer);
	            print_r("--------------- <br>");
	            print_r($itemPriceCents);
	            print_r("--------------- <br>");
	            print_r($mensajepago);
*/

			try {
				$charge = \Stripe\Charge::create(array(
					'customer' => $customer->id,
					'amount' => $itemPriceCents,
					'currency' => "usd",
					'description' => $mensajepago,
				));
			} catch (Exception $e) {
				$mensaje_cod = $e->getMessage();
				$message = array(
					"wtmessage" => Yii::t("Pago en Linea", $mensaje_cod),
					"title" => Yii::t('jslang', 'Error'),
					"ordStatus" => $ordStatus,
					"detallepago" => $detallepago,
					"mensaje_error" => $mensaje_error,
					"mensaje_cod" => $mensaje_cod,
				);
				return $message;
				//echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);return;
			} //catch

			/*
				            print_r("--------------- <br>");
				            print_r($charge);
				            die();
			*/
			//Si no hubo error se devuelve el resultado de la transaccion
			if ($charge) {
				//Cargamos los datos
				$chargeJson = $charge->jsonSerialize();

				// Check whether the charge is successful
				if ($chargeJson['amount_refunded'] == 0 &&
					empty($chargeJson['failure_code']) &&
					$chargeJson['paid'] == 1 &&
					$chargeJson['captured'] == 1) {
					// Si el pago fue correcto
					if ($chargeJson['status'] == 'succeeded') {
						$ordStatus = 'success';
						//$detallepago2 = $chargeJson;

						$detallepago = array(
							// Transaction details
							"transactionID" => $chargeJson['balance_transaction'],
							"paidAmount" => $chargeJson['amount'],
							"paidAmount" => ($paidAmount / 100),
							"paidCurrency" => $chargeJson['currency'],
							"payment_status" => $chargeJson['status'],
							"tarjeta" => $chargeJson['payment_method_details']['card']['brand'],
							"tipo" => $chargeJson['payment_method_details']['card']['funding'],
							"digito" => $chargeJson['payment_method_details']['card']['last4'],
							"recibo" => $chargeJson['receipt_url'],
							"certificado" => $chargeJson['receipt_url'],
						);
					} else {
						$mensaje_error = "Your Payment has Failed!";
					}

				} else {
					$mensaje_error = "Transaction has been failed!";
				}

			} else {
				$mensaje_error = "Charge creation failed! $mensaje_cod";
			}

		} else {
			$mensaje_error = "Invalid card details! bandera = $bandera";
		}

		$message = array(
			"wtmessage" => Yii::t('facturacion', "Online payment"),
			"title" => Yii::t('jslang', 'Ok'),
			"ordStatus" => $ordStatus,
			"detallepago" => $detallepago,
			"mensaje_error" => $mensaje_error,
			"mensaje_cod" => $mensaje_cod,
		);
		return json_encode($message);
	} //function stripecheckout

	/**
	 * Realiza el ingreso de errores a la tabla de log
	 * Esta funcion se puede llamar a travez del modelo utilitites
	 * @access public
	 * @author Galo Aguirre
	 */
	public static function logerror($nombremodulo, $tituloerror, $mensajerror, $datos) {
		//$con_asgard = \Yii::$app->db_asgard;

		$mod_log = new Log_errores;
		$mod_log->nombre_modulo = $nombremodulo;
		$mod_log->titulo_error = $tituloerror;
		$mod_log->mensaje_error_1 = $mensajerror;
		$mod_log->datos = $datos;
		$mod_log->loge_estado = "1";
		$mod_log->loge_estado_logico = "1";
		$mod_log->save();

		$message = array(
			"wtmessage" => "Registro realizado",
			"title" => "Log Errores",
		);
		return json_encode($message);
	} //function lorerrores

	public static function sendEmailicp($titleMessage = "", $from, $to = array(), $subject, $body, $files = array(), $template = "/mail/layouts/mailing", $fileRoute = "/mail/layouts/files", $basePath = NULL) {
		if (function_exists('proc_open')) {
			//self::putMessageLogFile("Mail function exist");
		} else {
			self::putMessageLogFile("Error Mail function not exist");
		}
		$routeBase = (isset($basePath)) ? ($basePath) : (Yii::$app->basePath);
		$socialNetwork = Yii::$app->params["socialNetworks"];

		$mail = Yii::$app->mailer->compose("@app" . $template, [
			'titleMessage' => $titleMessage,
			'body' => $body,
			'socialNetwork' => $socialNetwork,
			'bannerImg' => 'bannericp.png',
			'facebookicp' => 'facebook.png',
			'twittericp' => 'twitter.png',
			'instagramicp' => 'youtube.png',
			'pathImg' => $routeBase . "/" . $fileRoute . "/",
		]);
		$mail->setFrom($from);
		$mail->setTo($to);
		$mail->setSubject($subject);
		foreach ($files as $key2 => $value2) {
			$mail->attach($value2);
		}
		try {
			$mail->send();
		} catch (\Exception $ex) {
			self::putMessageLogFile($ex);
		}
	}
}
