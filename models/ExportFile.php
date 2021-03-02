<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Documentation MPDF: https://mpdf.github.io/reference/mpdf-functions/construct.html
 */

namespace app\models;

use Yii;
use Mpdf\Mpdf;

class ExportFile {

    /**
     * Send the PDF document in browser with a specific name. The plug-in is used if available.
     * The name given by filename is used when one selects the "Save as" option on the link generating the PDF.
     * @var string
     */
    const OUTPUT_TO_BROWSER = "I";

    /**
     * Forcing the download of PDF via web browser, with a specific name
     * @var string
     */
    const OUTPUT_TO_DOWNLOAD = "D";

    /**
     * Write the contents of a PDF file on the server
     * @var string
     */
    const OUTPUT_TO_FILE = "F";

    /**
     * Retrieve the contents of the PDF and then do whatever you want
     * @var string
     */
    const OUTPUT_TO_STRING = "S";

    public $mpdf;
    public $mode = 'utf-8';
    public $format = 'A4';
    public $default_font_size = '';
    public $default_font = '';
    public $mgl = 15;
    public $mgr = 15;
    public $mgt = 10;
    public $mgb = 10;
    public $mgh = 5;
    public $mgf = 7;
    public $orientation = 'P'; // L, P
    public $footer = TRUE;
    public $typeExport = ""; // OUTPUT_TO_DOWNLOAD, INLINE, DOWNLOAD, FILE, STRING_RETURN
    public $reportName = "";
    public $fontDir = "";
    public $fontdata = array(); // example $fontdata["gothambook"] = ['R' => 'GothamBook.ttf'];

    function __construct() {
        ini_set("pcre.backtrack_limit", "5000000"); //aumento de memoria para generacion de reportes
        if ($this->reportName == "")
            $this->reportName = 'Reporte_' . date("Ymdhis");
        if ($this->typeExport == "")
            $this->typeExport = self::OUTPUT_TO_DOWNLOAD;
    }

    function createReportPdf($content) {
        //error_reporting(E_ERROR); // Se activa esta variable de config para evitar mostrar warnings y solo mostrar mensajes de error.
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $this->mpdf = new Mpdf([
                "mode"   => $this->mode, 
                "format" => $this->format, 
                "default_font_size" => $this->default_font_size, 
                "default_font" => $this->default_font, 
                "margin_left"  => $this->mgl, 
                "margin_right" => $this->mgr, 
                "margin_top"   => $this->mgt, 
                "margin_bottom" => $this->mgb, 
                "margin_header" => $this->mgh, 
                "margin_footer" => $this->mgf, 
                "orientation"   => $this->orientation,
                "fontDir" => (($this->fontDir != "")?(array_merge($fontDirs, [$this->fontDir])):$fontDirs),
                "fontdata" => ((isset($this->fontdata) && $this->fontdata != "")?($fontData + $this->fontdata):$fontData),
        ]);
        
        //Utilities::putMessageLogFile($this->mpdf->fontFileFinder->findFontFile());
        if ($this->footer)
            $this->mpdf->SetHTMLFooter("<div class='footer' style='font-size: 10px;'><div style='float: left; width: 50%;'>Pag: {PAGENO}</div><div style='float: left;width: 50%;text-align: right;'>Hora: " . date("H:i") . "</div></div>");
        $this->mpdf->WriteHTML($content);
    }
}
