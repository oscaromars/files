<?php
/**
 * @package   PbGridView
 * @author    Eduardo Cueva <ecueva@penblu.com>
 * @copyright Copyright &copy; PenBlu S.A, 2014 - 2015
 * @version   1.0.0
 */

namespace app\widgets\PbGridView\controllers;

use Yii;
use app\widgets\PbGridView\PbGridView;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;
use mPDF;
use PHPExcel;
//use kartik\mpdf\Pdf;
use app\components\CController;

class ExportController extends CController
{
    
    
    public function actionDownload()
    {
        $request = array_merge($_GET, $_POST);
        $type = empty($request['export_filetype']) ? 'html' : $request['export_filetype'];
        $name = empty($request['export_filename']) ? Yii::t('kvgrid', 'export') : $request['export_filename'];
        $content = empty($request['export_content']) ? Yii::t('kvgrid', 'No data found') : $request['export_content'];
        $mime = empty($request['export_mime']) ? 'text/plain' : $request['export_mime'];
        $encoding = empty($request['export_encoding']) ? 'utf-8' : $request['export_encoding'];
        $config = empty($request['export_config']) ? '{}' : $request['export_config'];
        if ($type == "pdf") {
            $config = Json::decode($config);
            $this->generatePDF($content, "{$name}.pdf", $config);
            return;
        }
        $this->setHttpHeaders($type, $name, $mime, $encoding);
        return $content;
    }

    /**
     * Generates the PDF file
     *
     * @param string $content  the file content
     * @param string $filename the file name
     * @param array  $config   the configuration for yii2-mpdf component
     *
     * @return void
     */
    protected function generatePDF($content, $filename, $config = [])
    {
        unset($config['contentBefore'], $config['contentAfter']);
        $config['filename'] = $filename;
        $config['methods']['SetAuthor'] = ['Krajee Solutions'];
        $config['methods']['SetCreator'] = ['Krajee Yii2 Grid Export Extension'];
        $config['content'] = $content;
        $pdf = new Pdf($config);
        echo $pdf->render();
    }

    /**
     * Sets the HTTP headers needed by file download action.
     *
     * @param string $type     the file type
     * @param string $name     the file name
     * @param string $mime     the mime time for the file
     * @param string $encoding the encoding for the file content
     *
     * @return void
     */
    protected function setHttpHeaders($type, $name, $mime, $encoding = 'utf-8')
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") == false) {
            header("Cache-Control: no-cache");
            header("Pragma: no-cache");
        } else {
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: public");
        }
        header("Expires: Sat, 26 Jul 1979 05:00:00 GMT");
        header("Content-Encoding: {$encoding}");
        header("Content-Type: {$mime}; charset={$encoding}");
        header("Content-Disposition: attachment; filename={$name}.{$type}");
        header("Cache-Control: max-age=0");
    }
}