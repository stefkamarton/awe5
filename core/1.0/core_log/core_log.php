<?php

class core_log {

    private AWE $AWE;

    /**
     * core_log konstruktora - Itt veszik fel a változók az értéküket az adott funkciókból
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return void      
     */
    public function __construct($array) {
        $this->AWE = &$GLOBALS["awe"];
    }

    /**
     * Fájlba írja az információt, DebugMode esetén ki is írja
     * @param array $array  ["text"]=>""
     * @global $GLOBALS["awe"]->Logger->setInfo(array("text" => ""))
     * @return int      
     */
    public function setInfo($array = array("text" => "","line"=>__LINE__,"file"=>__FILE__)) {
        if (isset($array["text"])) {
            if ($this->AWE->DebugMode == true) {
                echo $array["text"];
            }
            return file_put_contents("./logs/" . $this->AWE->SiteAlias . "_info.log", "[" . date("Y-m-d h:i:s") . "][" . $this->AWE->Domain . "][". $this->AWE->getLocation($array["file"])." - Line:".$array["line"] ."]".$array["text"] . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * Fájlba írja az figyelmeztetést, DebugMode esetén ki is írja
     * @param array $array  ["text"]=>""
     * @global $GLOBALS["awe"]->Logger->setWarn(array("text" => ""))
     * @return int      
     */
    public function setWarn($array = array("text" => "","line"=>__LINE__,"file"=>__FILE__)) {
        if (isset($array["text"])) {
            if ($this->AWE->DebugMode == true) {
                echo $array["text"];
            }
            return file_put_contents("./logs/" . $this->AWE->SiteAlias . "_warn.log", "[" . date("Y-m-d h:i:s") . "][" . $this->AWE->Domain . "][". $this->AWE->getLocation($array["file"])." - Line:".$array["line"] ."]". $array["text"] . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * Fájlba írja az hibát, DebugMode esetén ki is írja
     * @param array $array  ["text"]=>""
     * @global $GLOBALS["awe"]->Logger->setError(array("text" => ""))
     * @return int      
     */
    public function setError($array = array("text" => "","line"=>__LINE__,"file"=>__FILE__)) {
        if (isset($array["text"])) {
            if ($this->AWE->DebugMode == true) {
                echo $array["text"];
            }
            return file_put_contents("./logs/" . $this->AWE->SiteAlias . "_error.log", "[" . date("Y-m-d h:i:s") . "][" . $this->AWE->Domain . "][". $this->AWE->getLocation($array["file"])." - Line:".$array["line"] ."]". $array["text"] . PHP_EOL, FILE_APPEND);
        }
    }

    /*
      public function getInfo($array){
      return file_get_contents("./logs/".$this->AWE->SiteAlias."_info.log");
      }
      public function getWarn($array){
      return file_get_contents("./logs/".$this->AWE->SiteAlias."_warn.log");
      }
      public function getError($array){
      return file_get_contents("./logs/".$this->AWE->SiteAlias."_error.log");
      } */
}

?>