<?php

/**
 * @source
 *  */

class AWE {

    /**
     * PHP & AWE hibaüzenetek ki-be kapcsolása
     * @var bool
     * @global $GLOBALS['awe']->DebugMode
     */
    public bool $DebugMode;

    /**
     * Logolásért felelős osztály
     * @var core_log
     * @global $GLOBALS['awe']->Logger
     */
    public core_log $Logger;

    /**
     * Az adott site aliasszalt tartalmazza
     * @var string 
     * @global $GLOBALS['awe']->SiteAlias
     */
    public string $SiteAlias;

    /**
     * [https/http]://domain-t tartalmazza
     * @var string 
     * @global $GLOBALS['awe']->Domain
     */
    public string $Domain;

    /**
     * Az adott site Core verzióját tartalmazza
     * @var string 
     * @global $GLOBALS['awe']->CoreVersion
     */
    public string $CoreVersion;

    /**
     * URL-t tartalmazza
     * @var string
     * @global $GLOBALS['awe']->Url
     */
    public string $Url;

    /**
     * User osztály mutatója
     * @var core_user 
     * @global $GLOBALS['awe']->User
     */
    public core_user $User;

    /**
     * DB osztály mutatója
     * @var core_db 
     * @global $GLOBALS['awe']->DB
     */
    public core_db $DB;

    /**
     * Template osztály mutatója
     * @var core_template 
     * @global $GLOBALS['awe']->Template
     */
    public core_template $Template;

    /**
     * Fordító osztály mutatója
     * @var core_translator
     * @global $GLOBALS['awe']->Translator
     */
    public core_translator $Translator;

    /**
     * Az elérhető nyelvek kódját tartalmazza
     * @var array
     * @global $GLOBALS['awe']->AvLanguages
     */
    public $AvLanguages;

    /**
     * Az adott nyelv kódját tartalmazza
     * @var string
     * @global $GLOBALS['awe']->Language
     */
    public string $Language;

    /**
     * Permission osztály mutatója
     * @var core_permission
     * @global $GLOBALS['awe']-Permissions
     */
    public core_permission $Permissions; /* core_permission osztály mutatója */

    /**
     * Multisiteid-t tartalmaz
     * @var string 
     * @global $GLOBALS['awe']->MultiSiteId
     */
    public string $MultiSiteId;

    /**
     * Komponensek mutatóját tartalmazza egy array-be
     * @var array
     * @global $GLOBALS['awe']->Components
     */
    public $Components;

    /**
     * AWE Osztály konstruktora - Itt veszik fel a változók az értéküket az adott funkciókból
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @global $GLOBALS["awe"]->__construct(array())
     * @return void      
     */
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

    /**
     * Csak a file-lok és osztályok meghívása után futtatja le a konstruktor
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return void       
     */
    private function afterInit($array = array()) {
        $this->AvLanguages = $this->getAvailableLanguage(array());
        //$this->Permission->Get(array("username"=>"admin"));
    }

    /**
     * Core file-lokat és osztályokat hívja meg
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @global $GLOBALS["awe"]->coreInit(array())
     * @return void       
     */
    public function coreInit($array = array()) {
        $this->Logger = new core_log(array());
        $this->DB = new core_db($this, array());
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
    
    /**
     * Megvizsgálja, hogy az adott string JSON-e
     * @param array $array  [string]=>""
     * @global $GLOBALS["awe"]->isJSON(array("string"=>""))
     * @return bool       
     */
    public function isJSON($array = array("string"=>"")) {
        return is_string($array['string']) && is_array(json_decode($array['string'], true)) ? true : false;
    }
    
    /**
     * Visszaadja a gyökértől az elérési utat
     * @param string $__dir__  string | \__DIR\__ | \__FILE__
     * @global $GLOBALS["awe"]->getLocation($__dir__)
     * @return string       
     */
    public function getLocation($__dir__) {
        return str_replace(getcwd(), "", $__dir__);
    }

    /**
     * Visszaadja az URL-t kötöjelekkel / helyett
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @global $GLOBALS["awe"]->getUrlId(array())
     * @return string       
     */
    public function getUrlId($array = array()) {
        return substr(str_replace("/", "-", $this->Url), 1);
    }

    /**
     * Megkeresi és be include-olja a core filelokat!
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return void       
     */
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

    /**
     * Egy random id-t generál a siteAlias alapján
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @global $GLOBALS["awe"]->idGenerate(array())
     * @return string       
     */
    public function idGenerate($array = array()) {
        return uniqid($this->getSiteAlias(array()) . "_", true);
    }

    /**
     * Megnézi hogy az adott string-ben megtalálható az adott substring
     * @param array $array  ['string'],['substring']
     * @global $GLOBALS["awe"]->stringContains(array("string"=>"","substring"=>""))
     * @return bool       
     */
    public function stringContains($array=array("string"=>"","substring"=>"")) {
        $array['string'] = mb_strtolower($array['string']);
        $array['substring'] = mb_strtolower($array['substring']);

        if (mb_substr_count($array['string'], $array['substring']) > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Megnézi hogy az adott string az adott substring-el végződik
     * @param array $array  ['string'],['substring']
     * @global $GLOBALS["awe"]->stringEndsWith(array("string"=>"","substring"=>""))
     * @return bool       
     */
    public function stringEndsWith($array=array("string"=>"","substring"=>"")) {
        $array['string'] = mb_strtolower($array['string']);
        $array['substring'] = mb_strtolower($array['substring']);

        $strLength = strlen($array['string']);
        $substrLength = strlen($array['substring']);

        if ($strLength < $substrLength)
            return FALSE;

        return substr_compare($array['string'], $array['substring'], ($strLength - $substrLength), $substrLength) === 0;
    }

    /**
     * Megnézi hogy az adott string az adott substring-el kezdődik
     * @param array $array  ['string'],['substring']
     * @global $GLOBALS["awe"]->stringStartsWith(array("string"=>"","substring"=>""))
     * @return bool       
     */
    public function stringStartsWith($array=array("string"=>"","substring"=>"")) {
        $array['string'] = mb_strtolower($array['string']);
        $array['substring'] = mb_strtolower($array['substring']);

        if (mb_substr($array['string'], 0, mb_strlen($array['substring'])) == $array['substring']) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * BASE64 ENCODEING - csak az url specifikus karaktareket kicseréli másra így lehet használni url-ként is
     * @param array $array  ['data']
     * @global $GLOBALS["awe"]->base64url_encode(array("string"=>""))
     * @return string       
     */
    public function base64url_encode($array=array("data"=>"")) {
        $b64 = base64_encode($array['data']);
        if ($b64 === FALSE)
            return FALSE;
        $url = strtr($b64, '+/', '-_');
        return rtrim($url, '=');
    }

    /**
     * BASE64 DECODEING - csak az url specifikus karaktareket visszacseréli másra így lehet használni
     * @param array $array  ['data'],['strict']->|base64_decode strict paraméter
     * @global $GLOBALS["awe"]->base64url_decode(array("data"=>"","strict"=>""))
     * @return array       
     */
    public function base64url_decode($array=array("data")) {
        if (!isset($array['strict'])) {
            $array['strict'] = FALSE;
        }
        if (!isset($array['data'])) {
            $array['data'] = "";
        }
        $b64 = strtr($array['data'], '-_', '+/');
        return base64_decode($b64, $array['strict']);
    }

    /**
     * Lekérdezi ha van a ?params= paramétert majd hozzáadja az új változókat
     * @param array $array  [0],[1]...,['forced_merge']->|Mindenképpen beleírandó adat
     * @global $GLOBALS["awe"]->addUrlParams(array(0=>"",1=>"","forced_merge"=>""))
     * @return string|NULL      
     */
    public function addUrlParams($array=array(0=>"",1=>"","forced_merge"=>"")) {
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

    /**
     * Lekérdezi ha van a ?params= paramétert
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @global $GLOBALS["awe"]->getUrlParams(array()))
     * @return string|NULL       
     */
    public function getUrlParams($array=array()) {
        if (isset($_GET['params']) && $_GET['params'] != "") {
            return (array) json_decode($this->base64url_decode(array("data" => $_GET['params'])));
        }
        return NULL;
    }

    /**
     * Lekérdezi az url-t
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return string       
     */
    private function getUrl($array=array()) {
        $url = explode("?", $_SERVER['REQUEST_URI']);
        return $url[0];
    }

    /**
     * Lekérdezi az core vezióját
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return string       
     */
    private function getCoreVersion($array=array()) {
        $json = file_get_contents("./sites/sites.json");
        $array = json_decode($json, true);
        if (isset($array[$this->SiteAlias]["core"])) {
            return $array[$this->SiteAlias]["core"];
        }
        return $array["default"]["core"];
    }

    /**
     * Lekérdezi a site aliaszát
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return string       
     */
    private function getSiteAlias($array=array()) {
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

    /**
     * Lekérdezi a multisite id-jét
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return string       
     */
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

    /**
     * Lekérdezi a jelenlegi nyelvet
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return string       
     */
    private function getCurrentLanguage($array=array()) {
        return "hu_HU";
    }

    /**
     * Lekérdezi az elérhető nyelveket
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return string       
     */
    private function getAvailableLanguage($array=array()) {
        $result = $this->DB->fetch(array("sql" => "SELECT defaults_obj FROM defaults WHERE defaults_id=:defaults_id", "attr" => array("defaults_id" => "available_languages")));
        $result = (array) json_decode($result['defaults_obj']);
        return $result['languages'];
    }

    /**
     * Lekérdezi a domainnevet
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return string       
     */
    private function getDomain($array) {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    }

    /**
     * Lekérdezi a domainnevet
     * @param array $array  [defaults_id]->| adott objektum default értékeivel tér vissza
     * @global $GLOBALS["awe"]->getDomain(array("defaults_id"=>"")))
     * @return string       
     */
    public function getDefaults($array) {
        return $GLOBALS['awe']->DB->fetchAll(array("sql" => "SELECT defaults_obj FROM defaults WHERE defaults_id=:defaults_id", "attr" => array("defaults_id" => $array["defaults_id"])), PDO::FETCH_ASSOC);
    }

}

/* ------------------------ */
/* Funkciók */
/* ------------------------ */
    /**
     * Visszatér a kulcs fordításával
     * @param string $expression  Fordítandó kifejezés
     * @param bool $comment  HTML komment kirakása
     * @global T($expression,$comment=FALSE)
     * @return string       
     */
function T($expression, $comment = FALSE) {
    $exp = $GLOBALS['awe']->Translator->getExpression(array("key" => $expression));
    if ($comment == true)
        return $exp . "<!-- key: " . $exp . " -->";
    return $exp;
}

    /**
     * Meghívja az adott pozíciók metódusait
     * @param string $position  Pozíció neve
     * @global getPos(array($position)
     * @return bool       
     */
function getPos($position) {
    return $GLOBALS['awe']->Template->getPosition(array("position" => $position));
}
