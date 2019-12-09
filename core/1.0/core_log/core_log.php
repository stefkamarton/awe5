<?php

class core_log {

    public function __construct($array) {
        //$GLOBALS['awe']->addAdminMenu(array());
    }
    public function setInfo($array) {
        if (isset($array["text"])) {
            if ($GLOBALS['awe']->DebugMode == true) {
                echo $array["text"];
            }
            return file_put_contents("./logs/" . $GLOBALS['awe']->SiteAlias . "_info.log", "[" . date("Y-m-d h:i:s") . "][" . $GLOBALS['awe']->Domain . "] " . $array["text"] . PHP_EOL, FILE_APPEND);
        }
    }

    public function setWarn($array) {
        if (isset($array["text"])) {
            if ($GLOBALS['awe']->DebugMode == true) {
                echo $array["text"];
            }
            return file_put_contents("./logs/" . $GLOBALS['awe']->SiteAlias . "_warn.log", "[" . date("Y-m-d h:i:s") . "][" . $GLOBALS['awe']->Domain . "] " . $array["text"] . PHP_EOL, FILE_APPEND);
        }
    }

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