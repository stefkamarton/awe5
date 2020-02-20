<?php

define("fileManagerDefaultPath", getcwd() . "sites" . $GLOBALS['awe']->SiteAlias . "/tmp");

class adm_filemanager_upload {
    
}

class adm_filemanager_download {
    
}

class adm_filemanager_item_list {

    public array $FileArray;
    private adm_filemanager $Obj;

    public function __construct($obj) {
        $this->Obj = &$obj;
        $this->createDirectoryItemList();
    }

    public function createDirectoryItemList() {
        $array = array();
        $dirs = scandir($this->Obj->CurrentPath);
        foreach ($dirs as $dir) {
            if ($dir != "." && ($dir != ".." && $dir != ".thumbs" || (DefaultFileManagerPath != $this->Obj->CurrentPath && DefaultFileManagerPath . "/" != $this->Obj->CurrentPath))) {

                $array[$dir] = new DirectoryIterator($this->Obj->CurrentPath . "/".$dir);
                //$array[$dir] = new adm_file(array("file" => $directory . "/" . $dir));
            }
        }
        return $array;
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

    public function getMethod(): int {
        if (isset($_POST['__method__']) && !empty($_POST['__method__'])) {
            return $_POST['__method__'];
        } else {

            return Method::Simple;
        }
    }

}

class adm_filemanager extends Method {

    private AWE $Awe;
    public $UrlParams;
    public $CurrentPath;
    public $ComponentId;
    public $Config;

    function __construct($array) {
        $this->Awe = &$GLOBALS['awe'];
        $this->UrlParams = $this->Awe->getUrlParams();
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

    public function listDirectoryItems(): adm_filemanager_list {
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
        return array_merge_recursive($this->Awe->getSettings(array("settings_id" => $this->ComponentId)), $array);
    }

}

define("FILEMANAGER_ROOT_DIR", getcwd() . "/sites/" . $GLOBALS['awe']->SiteAlias . "/tmp");
define("ADM_FILEMANAGER_AJAX_VIEW", array(
    "waiting" => "0",
    "method" => "view",
    "method2" => "view2",
    "url" => "/admin/adm_filemanager/ajax",
    "result" => "#directorylist"));

