<?php

class core_log {

    /**
     * core_log konstruktora - Itt veszik fel a változók az értéküket az adott funkciókból
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return void      
     */
    public function __construct($array) {
        
    }

    /**
     * Fájlba írja az információt, DebugMode esetén ki is írja
     * @param array $array  ["text"]=>""
     * @global $GLOBALS["awe"]->Logger->setInfo(array("text" => ""))
     * @return int      
     */
    public function setInfo($array = array("text" => "")) {
        if (isset($array["text"])) {
            if ($GLOBALS['awe']->DebugMode == true) {
                echo $array["text"];
            }
            return file_put_contents("./logs/" . $GLOBALS['awe']->SiteAlias . "_info.log", "[" . date("Y-m-d h:i:s") . "][" . $GLOBALS['awe']->Domain . "] " . $array["text"] . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * Fájlba írja az figyelmeztetést, DebugMode esetén ki is írja
     * @param array $array  ["text"]=>""
     * @global $GLOBALS["awe"]->Logger->setWarn(array("text" => ""))
     * @return int      
     */
    public function setWarn($array) {
        if (isset($array["text"])) {
            if ($GLOBALS['awe']->DebugMode == true) {
                echo $array["text"];
            }
            return file_put_contents("./logs/" . $GLOBALS['awe']->SiteAlias . "_warn.log", "[" . date("Y-m-d h:i:s") . "][" . $GLOBALS['awe']->Domain . "] " . $array["text"] . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * Fájlba írja az hibát, DebugMode esetén ki is írja
     * @param array $array  ["text"]=>""
     * @global $GLOBALS["awe"]->Logger->setError(array("text" => ""))
     * @return int      
     */
    public function setError($array) {
        if (isset($array["text"])) {
            if ($GLOBALS['awe']->DebugMode == true) {
                echo $array["text"];
            }
            return file_put_contents("./logs/" . $GLOBALS['awe']->SiteAlias . "_error.log", "[" . date("Y-m-d h:i:s") . "][" . $GLOBALS['awe']->Domain . "] " . $array["text"] . PHP_EOL, FILE_APPEND);
        }
    }

    /*
      public function getInfo($array){
      return file_get_contents("./logs/".$GLOBALS['awe']->SiteAlias."_info.log");
      }
      public function getWarn($array){
      return file_get_contents("./logs/".$GLOBALS['awe']->SiteAlias."_warn.log");
      }
      public function getError($array){
      return file_get_contents("./logs/".$GLOBALS['awe']->SiteAlias."_error.log");
      } */
}

?>