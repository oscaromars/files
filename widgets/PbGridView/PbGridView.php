<?php
/**
 * @package   PbGridView
 * @author    Eduardo Cueva <ecueva@penblu.com>
 * @copyright Copyright &copy; PenBlu S.A, 2014 - 2015
 * @version   1.0.0
 */

namespace app\widgets\PbGridView;

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\widgets\PbGridView\resources\PbGridViewAsset;
use Yii;

class PbGridView extends GridView {

    public $orderIcon = true;
    public $autoUpdate = false;
    private static $widget_name = "PbGridView";
    
    //public $showFooter = true;
    public $headerRowOptions = [];
    public $footerRowOptions = [];
    public $emptyCell = '&nbsp;';
    public $summary = null;
    public $showExport = false;
    public $fnExportPDF = null;
    public $fnExportEXCEL = null;
    public $pajax = true;
    public $timepajax = 20000; // 5000 Milisegundos -> 5 Segundos
    public $addButton = false;
    public $addButton_fn = null;
    public $addButton_link = null;
    public $addButton_popup = false;
    public $selectableRows = false;

    public function init() {
        parent::init();
        $this->registerTranslations();
    }

    public function run() {
        $options = Json::encode($this->getClientOptions());
        $view = parent::getView();
        PbGridViewAsset::register($view);
        $id = $this->options['id'];
        if($this->pajax)
            Pjax::begin(['id' => 'pajax'.$id , 'timeout' => $this->timepajax ]); // Pjax::begin(['id' => 'pajax'.$id, 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false, 'timeout' => $this->timepajax]);
        
        $view->registerJs("jQuery('#".$id."').PbGridView($options);");
        if($this->autoUpdate){
            $view->registerJs("jQuery('#".$this->id."').PbGridView('updatePAjax');");
        }
        if ($this->showOnEmpty || $this->dataProvider->getCount() > 0) {
            $content = preg_replace_callback("/{\\w+}/", function ($matches) {
                $content = $this->renderSection($matches[0]);
                return $content === false ? $matches[0] : $content;
            }, $this->layout);
        } else {
            $content = $this->renderEmpty();
        }
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        echo $this->render('index', [
            'isSummary' => ($this->summary === "")?true:false, 
            'fnExportPDF' => (isset($this->fnExportPDF))?"javascript:".$this->fnExportPDF."()":"javascript:",
            'fnExportEXCEL' => (isset($this->fnExportEXCEL))?"javascript:".$this->fnExportEXCEL."()":"javascript:",
            'showExport' => $this->showExport,
            'addButton' => $this->addButton,
            'addButton_fn' => $this->addButton_fn,
            'addButton_lk' => $this->addButton_link,
            'addButton_pp' => $this->addButton_popup,
            'selectableRows' => $this->selectableRows,
            'totalCount' => $this->dataProvider->getCount(), //$this->dataProvider->totalCount,
            ]);
        echo Html::tag($tag, $content, $this->options);
        if($this->pajax)
            Pjax::end();
    }
    
    /**
     * Renders the table header.
     * @return string the rendering result.
     */
    public function renderTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $col = $column->renderHeaderCell();
            if($this->orderIcon){
                $headlabel = str_replace("<th>", "", $col);
                $headlabel = str_replace("</th>", "", $headlabel);
                if($headlabel != "#" && $headlabel != $this->emptyCell && preg_match("/data-sort=/", $headlabel)){
                    $classSort = preg_match("/class=\".*\"/", $headlabel, $matches);
                    $arr_class = explode(" ", $matches[0]);
                    if(preg_match("/desc/", $arr_class[0]))
                        $col = str_replace("<th>", "<th class='sorting_desc'>", $col);
                    else if(preg_match("/asc/", $arr_class[0])){
                        $col = str_replace("<th>", "<th class='sorting_asc'>", $col);
                    }else
                        $col = str_replace("<th>", "<th class='sorting'>", $col);
                }
            }
            $cells[] = $col;
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if($this->orderIcon){
            $optionsTable = $this->tableOptions;
            $optionsTable["class"] = $optionsTable["class"] . " dataTable";
            $this->tableOptions = $optionsTable;
        }
        if ($this->filterPosition == self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition == self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        return "<thead>\n" . $content . "\n</thead>";
    }
    
    /**
     * Renders the filter.
     * @return string the rendering result.
     */
    public function renderFilters()
    {
        if ($this->filterModel !== null) {
            $cells = [];
            foreach ($this->columns as $column) {
                /* @var $column Column */
                $cells[] = $column->renderFilterCell();
            }

            return Html::tag('tr', implode('', $cells), $this->filterRowOptions);
        } else {
            return '';
        }
    }
    
    /**
     * Renders the summary text.
     */
    public function renderSummary()
    {
        $count = $this->dataProvider->getCount();
        if ($count <= 0) {
            return '';
        }
        $tag = ArrayHelper::remove($this->summaryOptions, 'tag', 'div');
        if (($pagination = $this->dataProvider->getPagination()) !== false) {
            $totalCount = $this->dataProvider->getTotalCount();
            $begin = $pagination->getPage() * $pagination->pageSize + 1;
            $end = $begin + $count - 1;
            if ($begin > $end) {
                $begin = $end;
            }
            $page = $pagination->getPage() + 1;
            $pageCount = $pagination->pageCount;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', 'Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one{item} other{items}}.', [
                        'begin' => $begin,
                        'end' => $end,
                        'count' => $count,
                        'totalCount' => $totalCount,
                        'page' => $page,
                        'pageCount' => $pageCount,
                    ]), $this->summaryOptions);
            }
        } else {
            $begin = $page = $pageCount = 1;
            $end = $totalCount = $count;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', 'Total <b>{count, number}</b> {count, plural, one{item} other{items}}.', [
                    'begin' => $begin,
                    'end' => $end,
                    'count' => $count,
                    'totalCount' => $totalCount,
                    'page' => $page,
                    'pageCount' => $pageCount,
                ]), $this->summaryOptions);
            }
        }

        return Yii::$app->getI18n()->format($summaryContent, [
            'begin' => $begin,
            'end' => $end,
            'count' => $count,
            'totalCount' => $totalCount,
            'page' => $page,
            'pageCount' => $pageCount,
        ], Yii::$app->language);
    }

    /**
     * Renders the pager.
     * @return string the rendering result
     */
    public function renderPager()
    {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class LinkPager */
        $pager = $this->pager;
        $class = ArrayHelper::remove($pager, 'class', LinkPager::className());
        $pager['pagination'] = $pagination;
        $pager['view'] = $this->getView();

        return $class::widget($pager);
    }

    /**
     * Renders the sorter.
     * @return string the rendering result
     */
    public function renderSorter()
    {
        $sort = $this->dataProvider->getSort();
        if ($sort === false || empty($sort->attributes) || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class LinkSorter */
        $sorter = $this->sorter;
        $class = ArrayHelper::remove($sorter, 'class', LinkSorter::className());
        $sorter['sort'] = $sort;
        $sorter['view'] = $this->getView();

        return $class::widget($sorter);
    }
    
    /**
     * Returns the options for the grid view JS widget.
     * @return array the options
     */
    protected function getClientOptions()
    {
        $filterUrl = isset($this->filterUrl) ? $this->filterUrl : Yii::$app->request->url;
        $id = $this->filterRowOptions['id'];
        $timeUpdate = 0;
        $filterSelector = "#$id input, #$id select";
        if (isset($this->filterSelector)) {
            $filterSelector .= ', ' . $this->filterSelector;
        }
        if ($this->autoUpdate)
            $timeUpdate = $this->timepajax;

        return [
            'filterUrl' => Url::to($filterUrl),
            'filterSelector' => $filterSelector,
            'timepajax' => $timeUpdate,
        ];
    }

    public function registerTranslations() {
        $fileMap = $this->getMessageFileMap();
        $i18n = Yii::$app->i18n;
        $i18n->translations['widgets/' . self::$widget_name . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            //'sourceLanguage' => 'en',
            //'language' => 'es',
            'basePath' => '@app/widgets/' . self::$widget_name . '/messages',
            'fileMap' => $fileMap,
        ];
    }
    
    private function getMessageFileMap() {
        // read directory message
        $arrLangFiles = array();
        $dir_messages = __DIR__ . DIRECTORY_SEPARATOR . "messages";
        $fileMap = array();
        $listDirs = scandir($dir_messages);
        foreach ($listDirs as $dir) {
            if ($dir != "." && $dir != "..") {
                $langDir = scandir($dir_messages . DIRECTORY_SEPARATOR . $dir);
                foreach ($langDir as $langFile) {
                    if (preg_match("/\.php$/", trim($langFile))) {
                        if (!in_array($langFile, $arrLangFiles)) {
                            $arrLangFiles[] = $langFile;
                            $file = str_replace(".php", "", $langFile);
                            $key = "widgets/" . self::$widget_name . "/" . $file;
                            $fileMap[$key] = $langFile;
                        }
                    }
                }
            }
        }
        return $fileMap;
    }

    public static function t($category, $message, $params = [], $language = null) {
        return Yii::t('widgets/' . self::$widget_name . '/' . $category, $message, $params, $language);
    }

}
