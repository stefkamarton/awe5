<?php


class AWE {

    public $loadedpositions = array();
    public $admMenu = "";
    /* NEW */

    /**
     * @category AWE osztály
     * @var bool PHP & AWE hibaüzenetek ki-be kapcsolása
     * @access public
     * @global $GLOBALS['awe']->DebugMode
     */
    public $DebugMode;

    /**
     * @category AWE osztály
     * @var core_log Logolásért felelős osztály
     * @access public
     * @global $GLOBALS['awe']->Logger
     * 
     * -->|Metódusok|<--:
     * @params ->setWarn(array("text"=>"sometext"))
     * @params ->setError(array("text"=>"sometext"))
     * @params ->setInfo(array("text"=>"sometext"))
     */
    public $Logger;

    /**
     * @category AWE osztály
     * @var string Visszatér a site aliassal
     * @access public
     * @global $GLOBALS['awe']->SiteAlias
     */
    public $SiteAlias;

    /**
     * @category AWE osztály
     * @var string Visszatér a [https/http]://domain-nal
     * @access public
     * @global $GLOBALS['awe']->Domain
     */
    public $Domain;

    /**
     * @category AWE osztály
     * @var string Visszatér az adott site adott core verziójával
     * @access public
     * @global $GLOBALS['awe']->CoreVersion
     */
    public $CoreVersion;

    /**
     * @category AWE osztály
     * @var string Visszatér az URL-lel
     * @access public
     * @global $GLOBALS['awe']->Url
     */
    public $Url;

    /**
     * @category AWE osztály
     * @var class  core_user osztály mutatója
     * @access public
     * @global $GLOBALS['awe']->User
     */
    public $User;

    /**
     * @category AWE osztály
     * @var class core_db osztály mutatója
     * @access public
     * @global $GLOBALS['awe']->DB
     */
    public $DB;

    /**
     * @category AWE osztály
     * @var class core_template osztály mutatója
     * @access public
     * @global $GLOBALS['awe']->Template
     * 
     *  -->|Metódusok|<--:
     * @params ->setWarn(array("text"=>"sometext"))
     * @params ->setError(array("text"=>"sometext"))
     * @params ->setInfo(array("text"=>"sometext"))
     */
    public $Template;

    /**
     * @category AWE osztály
     * @var class core_translator osztály mutatója
     * @access public
     * @global $GLOBALS['awe']->Translator
     */
    public $Translator;

    /**
     * @category AWE osztály
     * @var array Visszatér az elérhető nyelvekkel
     * @access public
     * @global $GLOBALS['awe']->AvLanguages
     */
    public $AvLanguages;

    /**
     * @category AWE osztály
     * @var string Visszatér a site aliassal
     * @access public
     * @global $GLOBALS['awe']->SiteAlias
     */
    public $Language; /* Visszatér a jelenlegi nyelv-vel [STRING] */

    /**
     * @category AWE osztály
     * @var string Visszatér a site aliassal
     * @access public
     * @global $GLOBALS['awe']->SiteAlias
     */
    public $Permissions; /* core_permission osztály mutatója */

    /**
     * @category AWE osztály
     * @var string Visszatér a site aliassal
     * @access public
     * @global $GLOBALS['awe']->SiteAlias
     */
    public $MultiSiteId;

    /**
     * @category AWE osztály
     * @var string Visszatér a site aliassal
     * @access public
     * @global $GLOBALS['awe']->SiteAlias
     */
    public $Components;

    public function __construct($array = array()) {
        $this->SiteAlias = $this->getSiteAlias(array());
        $this->Domain = $this->getDomain(array());
        $this->CoreVersion = $this->getCoreVersion(array());
        $this->Url = $this->getUrl(array());
        $this->Language = $this->getCurrentLanguage(array());
        $this->DebugMode = true;
        /* Core fileok betöltése */
        $this->coreLoader(array());
        /* Debug Mode Settings */
        if ($this->DebugMode == true) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(0);
        }
    }

    /* Inicializálás után */

    private function afterInit($array = array()) {
        $this->AvLanguages = $this->getAvailableLanguage(array());
        //$this->Permission->Get(array("username"=>"admin"));
    }

    /* Core class-ok inicialízálása */

    public function coreInit($array = array()) {
        $this->Logger = new core_log(array());
        $this->DB = new core_db(array());
        $this->MultiSiteId = $this->getMultiSiteId(array());
        $this->Translator = new core_translator(array());
        $this->User = new core_user(array());
        $this->Permissions = new core_permission(array());
        $this->Template = new core_template(array());
        //ha admin oldalit akar elérni...
        if (FALSE && $this->stringStartsWith(array("string" => $this->Url, "substring" => "/admin"))) {
            if (!$this->User->isLoggedIn()) {
                $this->User->LoginPage();
                // szerezzen sessiont- joggal
                //$this->Auth = new core_auth(array());
            }
        }
        $this->afterInit(array());
    }

    function isJSON($array) {
        return is_string($array['string']) && is_array(json_decode($array['string'], true)) ? true : false;
    }

    public function getLocation($__dir__) {
        return str_replace(getcwd(), "", $__dir__);
    }

    /* Admin menu */

    /* public function addAdminMenu($array) {
      return "";
      } */

    /* Visszatér az URL-lel speciális karakterek nélkül */

    public function getUrlId($array = array()) {
        return substr(str_replace("/", "-", $this->Url), 1);
    }

    /* Core file-ok betöltése */

    private function coreLoader($array = array()) {
        $directories = scandir("./core/" . $this->CoreVersion);
        foreach ($directories as $directory) {
            if (is_dir("./core/" . $this->CoreVersion . "/" . $directory) && substr_count($directory, 'core_') > 0) {
                if (file_exists('./core/' . $this->CoreVersion . "/" . $directory . '/' . $directory . '.php')) {
                    require_once './core/' . $this->CoreVersion . "/" . $directory . '/' . $directory . '.php';
                } else {
                    throw new Exception("### E0000 - Hibás core hivás -> " . $this->CoreVersion . "/" . $directory . ' ###');
                }
            }
        }
    }

    /* Site Alias alapján generál egy uniq ID-t */

    public function idGenerate($array = array()) {
        return uniqid($this->getSiteAlias(array()) . "_", true);
    }

    /* Megnézi  hogy azzal tartalmazza-e a string - [BOOL] */

    public function stringContains($array) {
        $array['string'] = mb_strtolower($array['string']);
        $array['substring'] = mb_strtolower($array['substring']);

        if (mb_substr_count($array['string'], $array['substring']) > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /* Megnézi  hogy azzal végződik-e a string - [BOOL] */

    public function stringEndsWith($array) {
        $array['string'] = mb_strtolower($array['string']);
        $array['substring'] = mb_strtolower($array['substring']);

        $strLength = strlen($array['string']);
        $substrLength = strlen($array['substring']);

        if ($strLength < $substrLength)
            return FALSE;

        return substr_compare($array['string'], $array['substring'], ($strLength - $substrLength), $substrLength) === 0;
    }

    /* Megnézi  hogy azzal kezdődik-e a string - [BOOL] */

    public function stringStartsWith($array) {
        $array['string'] = mb_strtolower($array['string']);
        $array['substring'] = mb_strtolower($array['substring']);

        if (mb_substr($array['string'], 0, mb_strlen($array['substring'])) == $array['substring']) {
            return TRUE;
        }
        return FALSE;
    }

    /* BASE64 ENCODEING - URL SUPPORTED -  Böngésző nem támogatja a +/= jeleket ezért kell ez a módosítás - [STRING] */

    public function base64url_encode($array) {
        $b64 = base64_encode($array['data']);
        if ($b64 === FALSE)
            return FALSE;
        $url = strtr($b64, '+/', '-_');
        return rtrim($url, '=');
    }

    /* BASE64 DECODEING - URL SUPPORTED -  Böngésző nem támogatja a +/= jeleket ezért kell ez a módosítás [STRING] */

    public function base64url_decode($array) {
        if (!isset($array['strict'])) {
            $array['strict'] = FALSE;
        }
        if (!isset($array['data'])) {
            $array['data'] = "";
        }
        $b64 = strtr($array['data'], '-_', '+/');
        return base64_decode($b64, $array['strict']);
    }

    /* Visszatér a ?params= értékével és a hozzáadott értékkel amit encode-ol */

    public function addUrlParams($array) {
        if (isset($array) && $array != NULL) {
            $getParams = $this->getUrlParams(array());
            if ($getParams != NULL) {
                $array = array_merge($array, $getParams);
            }
            if (isset($array['forced_merge']) && $array['forced_merge'] != NULL) {
                $array = array_merge($array, $array['forced_merge']);
            }
            unset($array["forced_merge"]);
            return $this->base64url_encode(array("data" => json_encode($array)));
        }
        return NULL;
    }

    /* Visszatér a ?params= értékével amit decode-ol */

    public function getUrlParams($array) {
        if (isset($_GET['params']) && $_GET['params'] != "") {
            return (array) json_decode($this->base64url_decode(array("data" => $_GET['params'])));
        }
        return NULL;
    }

    /* Visszatér az URL-lel */

    private function getUrl($array) {
        $url = explode("?", $_SERVER['REQUEST_URI']);
        return $url[0];
    }

    /* Visszatér az adott site core verziójával */

    private function getCoreVersion($array) {
        $json = file_get_contents("./sites/sites.json");
        $array = json_decode($json, true);
        if (isset($array[$this->SiteAlias]["core"])) {
            return $array[$this->SiteAlias]["core"];
        }
        return $array["default"]["core"];
    }

    /* Visszatér az adott site alias-szal */

    private function getSiteAlias($array) {
        $domain = $_SERVER['HTTP_HOST'];
        $json = file_get_contents("./sites/sites.json");
        $json = json_decode($json, true);
        foreach ($json as $key => $value) {
            if (isset($value["domains"])) {
                if (in_array($domain, $value["domains"])) {
                    return $key;
                }
            }
        }
        return "default";
    }

    /* Visszatér a multisite id-jével */

    private function getMultiSiteId($array) {
        $result = $this->DB->fetch(array("sql" => "SELECT defaults_obj FROM defaults WHERE defaults_id=:defaults_id", "attr" => array("defaults_id" => "multisite_id")), PDO::FETCH_ASSOC);
        $result = (array) json_decode($result["defaults_obj"]);
        foreach ($result as $key => $value) {
            if ($key == $_SERVER['HTTP_HOST']) {
                return $value;
            }
        }

        return FALSE;
    }

    /* Visszatér az adott site nyelvével */

    private function getCurrentLanguage($array) {
        return "hu_HU";
    }

    /* Visszatér az adott site elérhető nyelveivel */

    private function getAvailableLanguage($array) {
        $result = $this->DB->fetch(array("sql" => "SELECT defaults_obj FROM defaults WHERE defaults_id=:defaults_id", "attr" => array("defaults_id" => "available_languages")));
        $result = (array) json_decode($result['defaults_obj']);
        return $result['languages'];
    }

    /* Visszatér az adott site domain-nevével */

    private function getDomain($array) {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    }

    public function getDefaults($array) {
        return $GLOBALS['awe']->DB->fetchAll(array("sql" => "SELECT defaults_obj FROM defaults WHERE defaults_id=:defaults_id", "attr" => array("defaults_id" => $array["defaults_id"])), PDO::FETCH_ASSOC);
    }

}

/* ------------------------ */
/* Funkciók */
/* ------------------------ */

function T($expression, $comment = FALSE) {
    $exp = $GLOBALS['awe']->Translator->getExpression(array("key" => $expression));
    if ($comment == true)
        return $exp . "<!-- key: " . $exp . " -->";
    return $exp;
}

function getPos($position) {
    return $GLOBALS['awe']->Template->getPosition(array("position" => $position));
}
