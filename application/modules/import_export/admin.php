<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

use import_export\classes\Logger as LOG;

/**
 * Image CMS 
 * Import_export Module Admin
 */
class Admin extends BaseAdminController {
    
    private $languages = null;
    private $checkedFields = array(
        'name',
        'url',
        'prc',
        'var',
        'cat',
        'num'
    );
    private $uploadDir = './application/modules/import_export/backups/';

    public function __construct() {
        parent::__construct();
        $lang = new MY_Lang();
        $lang->load('import_export');    
    }

    /**
     * Index. Render admin tpl 'settings'
     */
    public function index() {
        \CMSFactory\assetManager::create()
                ->renderAdmin('settings');
    }
    
    /**
     * Controller start classes import
     * @param string $method method
     */
    public function getImport($method){
        require_once 'import.php';    
        $n = new Import();
        $n->$method();
    }
    
    public function getExport(){
        $export = new \import_export\classes\Export(array(
            'attributes' => $_POST['attribute'],
            'attributesCF' => $_POST['cf'],
            'import_type' => trim($_POST['import_type']),
            'delimiter' => trim($_POST['delimiter']),
            'enclosure' => trim($_POST['enclosure']),
            'encoding' => trim($_POST['encoding']),
            'currency' => trim($_POST['currency']),
            'languages' => trim($_POST['language']),
            'selectedCats' => $_POST['selectedCats'],
            'withZip' => $_POST['withZip']
        ));
        if ($export->hasErrors() == FALSE) {
            $export->getDataArray();
            if (!$this->input->is_ajax_request()) {
                $this->createFile($_POST['type'], $export);
                $this->downloadFile($_POST['type']);
                return;
            }
            if (FALSE !== $this->createFile($_POST['type'], $export)) {
                if ($_POST['withZip']) {
                    $export->addToArchive($export->resultArray);
                }
                echo $_POST['type'];
                LOG::create()->set("Експорт завершен успешно!");
                return;
            }
            LOG::create()->set("Ошибка при експорте!");
            echo "Ошибка при експорте!";
        } else {
            echo $this->processErrors($export->getErrors());
        }
    }
    
    /**
     * Select File tpl import or export
     * @param string $check (import | export)
     */
    public function getTpl($check){
        if($check == 'import'){
            \CMSFactory\assetManager::create()
                ->registerScript('importAdmin')
                ->renderAdmin('import');
        } 
        if($check == 'export') {
            $customFields = SPropertiesQuery::create()->orderByPosition()->find();
            $cFieldsTemp = $customFields->toArray();
            $cFields = array();
            foreach ($cFieldsTemp as $f) {
                $cFields[] = $f['CsvName'];
            }
            \CMSFactory\assetManager::create()
                ->registerScript('importExportAdmin')
                ->setData('attributes',ImportCSV\BaseImport::create()->makeAttributesList()->possibleAttributes)     
                ->setData('languages',$this->languages)
                ->setData('cFields',$cFields)
                ->setData('checkedFields',$this->checkedFields)
                ->renderAdmin('export');
        }
        if($check == 'archiveList'){
            $dir = './application/modules/import_export/backups/';
            $files = array();
            if(is_dir($dir)){
                if($dh = opendir($dir)){
                    $arr = scandir($dir);
                    foreach($arr as $str){
                        if(strpos($str,'.zip') != FALSE){
                            $files[] = $str;
                        }
                    }
                }
            }
            arsort($files);
            \CMSFactory\assetManager::create()
                ->registerScript('importExportAdmin')    
                ->setData('files',$files)    
                ->renderAdmin('list');
        }
    }
    
    /**
     * File creating
     * @param string $type file type
     * @param ShopExportDataBase $export
     * @return string file name
     */
    protected function createFile($type, $export) {
        switch ($type) {
            case "xls":
                return $export->saveToExcelFile($this->uploadDir, "Excel5");
                break;
            case "xlsx":
                return $export->saveToExcelFile($this->uploadDir, "Excel2007");
                break;
            default: // csv
                return $export->saveToCsvFile($this->uploadDir);
        }
    }
    
    /**
     * Start file downloading
     * @param string $type file type csv|xls|xlsx
     */
    protected function downloadFile($type = 'csv') {
        if (!in_array($type, array('csv', 'xls', 'xlsx'))){
            return;
        }
        $file = 'products.' . $type;
        $path = $this->uploadDir . $file;
        if (file_exists($path)) {
            $this->load->helper('download');
            $data = file_get_contents($path);
            if ($type == 'csv') {
                header('Content-type: text/csv');
            }
            force_download($file, $data);
        } else {
            LOG::create()->set('Невозможно скачать файл!');
        }
    }
    
    /**
     * Create html box with errors.
     *
     * @param  array $errors Errors array
     * @return string
     */
    protected function processErrors(array $errors) {
        $result = '';
        foreach ($errors as $err) {
            $result .= $err . '<br/>';
        }
        return '<p class="errors">' . $result . '</p>';
    }
    
    /**
     * Delete archive with origin and additional photos
     * 
     * @author Oleh
     * @param string $str
     */
    
    public function deleteArchive($str){
        $dir = './application/modules/import_export/backups/';
        unlink($dir . $str);
        $this->getTpl('archiveList');
    }
    
    /**
     * Download ZIP with origin and additional photo
     * 
     * @author Oleh
     * @param string $str
     */
    public function downloadZIP($str){
        $path = './application/modules/import_export/backups/' . $str;
        $this->load->helper('download');
        if(file_exists($path)){
            $data = file_get_contents($path);
            force_download($str,$data);
        } else {
            LOG::create()->set('Невозможно скачать архив ZIP, файт отсутствует!');
        }
    }
    
}