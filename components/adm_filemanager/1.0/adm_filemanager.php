<?php

define("fileManagerDefaultPath", getcwd() . "sites" . $GLOBALS['awe']->SiteAlias . "/tmp");

abstract class Component {

    protected AWE $awe;

    public function __construct() {
        $this->awe = &$GLOBALS['awe'];
    }

}

abstract class adm_filemanager_child_abstract {

    protected adm_filemanager $Obj;

    public function __construct($obj) {
        $this->Obj = &$obj;
    }

}

class adm_filemanager_upload extends adm_filemanager_child_abstract {

    public function __construct($obj) {
        parent::__construct($obj);
    }

    public function Handler() {
        
    }

    function reArrayFiles(&$file_post): array {
        $files = array();
        foreach ($file_post as $input_name => $input_value) {
            $file_ary = array();
            $file_count = count($file_post['name']);
            $file_keys = array_keys($file_post);

            for ($i = 0; $i < $file_count; $i++) {
                foreach ($file_keys as $key) {
                    $file_ary[$i][$key] = $file_post[$key][$i];
                }
            }
            $files[$input_name] = $file_ary;
        }
        return $files;
    }

    public function saveFiles(): bool {
        if (!empty($_FILES) && isset($_FILES)) {
            $files = $this->reArrayFiles($_FILES);
            foreach ($files as $files_key => $files_value) {
                foreach ($files_value as $item_value) {
                    move_uploaded_file($item_value["tmp_name"], $this->Obj->CurrentPath."/".$item_value["name"]);
                }
            }
            return TRUE;
        }
        return FALSE;
    }

}

class adm_filemanager_download extends adm_filemanager_child_abstract {

    public function __construct($obj) {
        parent::__construct($obj);
    }

}

class adm_filemanager_directory extends adm_filemanager_child_abstract {

    public function __construct($obj) {
        parent::__construct($obj);
    }

}

class adm_filemanager_items extends adm_filemanager_child_abstract {

    public DirectoryIterator $Items;

    public function __construct($obj) {
        parent::__construct($obj);
        $this->Items = $this->createDirectoryItemList();
    }

    public function createDirectoryItemList(): DirectoryIterator {
        $iterator = new DirectoryIterator($this->Obj->CurrentPath);
        return $iterator;
    }

}

class adm_file_img_convert {

    public function convertToWebP($path, $img) {
        
    }

}

define("DefaultFileManagerPath", getcwd());

abstract class Method {

    const Simple = 1;
    const Post = 100;
    const Insert = 110;
    const Upload = 120;
    const Delete = 130;
    const Modification = 140;
    const Filter = 150;
    const Verification = 160;
    const Refresh = 170;

    static public function getMethod(): int {
        if (isset($_POST['__method__']) && !empty($_POST['__method__'])) {
            return $_POST['__method__'];
        } else {

            return Method::Simple;
        }
    }

}

class adm_filemanager extends Component {

    public $UrlParams;
    public $CurrentPath;
    public $ComponentId;
    public $Config;

    function __construct($array) {
        parent::__construct();
        $this->UrlParams = $this->awe->getUrlParams();
        /* Component Id */
        $this->ComponentId = $this->getComponentId($array);
        /* get Config */
        $this->Config = $this->getConfigurations($array);
        /* Directory Path */
        $this->CurrentPath = $this->getCurrentPath();
        var_dump($this->CurrentPath);

        switch (Method::getMethod()) {
            case Method::Simple:
                $this->listDirectoryItems();

                break;
            case Method::Refresh:

                break;
            case Method::Upload:
                break;
            case Method::Delete:
                break;
            case Method::Verification:
                break;
            default :
                die("Unknown method");
        }
    }

    public function listDirectoryItems(): adm_filemanager_item_list {
        return new adm_filemanager_item_list($this);
    }

    /**
     * Visszatér az adott komponens adott id-jével
     * @return string       
     */
    public function getComponentId($array): string {
        if (isset($_POST['__comid__']) && !empty($_POST['__comid__'])) {
            return $_POST['__comid__'];
        }
        return $array['url_id'];
    }

    /**
     * Visszatér az adott komponens adott templatejével
     * @return string       getCurrentPath
     */
    public function getComponentTemplate(): string {
        return $this->Config['template'];
    }

    /**
     * Meghívja a template file-t
     * @return bool       
     */
    public function callComponentTemplate(): bool {
        $path = __DIR__ . "/templates/" . $file . "/" . $file . ".php";
        if (is_file($path)) {
            require_once($path);
            return true;
        }
        return false;
    }

    /**
     * Visszatér az adott directory path-el
     * @return string       
     */
    public function getCurrentPath(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['path']) && !empty($_POST['path'])) {
            return $_POST['path'];
        } else if (!empty($this->urlParams['path'])) {
            echo "asd1";
            return $this->urlParams['path'];
        } else {
            echo "asd";
            return DefaultFileManagerPath;
        }
    }

    public function getConfigurations($array): array {
        return array_merge_recursive($this->awe->getSettings(array("settings_id" => $this->ComponentId)), $array);
    }

}

define("FILEMANAGER_ROOT_DIR", getcwd() . "/sites/" . $GLOBALS['awe']->SiteAlias . "/tmp");
define("ADM_FILEMANAGER_AJAX_VIEW", array(
    "waiting" => "0",
    "method" => "view",
    "method2" => "view2",
    "url" => "/admin/adm_filemanager/ajax",
    "result" => "#directorylist"));

