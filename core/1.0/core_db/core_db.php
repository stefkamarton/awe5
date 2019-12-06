<?php

class core_db {
    private $Connection;
    private $Config;

    public function __construct($array) {
        $this->Config = $this->getConfig(array());
        $this->Connection = $this->getConnection(array());
    }

    /*DB config betöltés*/
    private function getConfig($array) {
        $path = "./sites/" . $GLOBALS['awe']->SiteAlias . "/config.ini";
        if (!$config = parse_ini_file($path, TRUE)) {
            $GLOBALS['awe']->Logger->setError(array("text" => "E0001 - Nem található az oldal adatbázis konfigja! - " . $path));
        }
        return $config;
    }

    /*PDO osztály meghívás*/
    private function getConnection($array) {
        $connectionString = $this->Config['database']['driver'] .
                ':host=' . $this->Config['database']['host'] .
                ((!empty($this->Config['database']['port'])) ? (';port=' . $this->Config['database']['port']) : '') .
                ';dbname=' . $this->Config['database']['schema'];
        try {
            $pdo=new PDO($connectionString, $this->Config['database']['username'], $this->Config['database']['password']);
        } catch (Exception $exc) {
            $GLOBALS['awe']->Logger->setError(array("text" => "E0004 - Hibás adatbázis csatlakozás!"));
        }
        return $pdo;
    }

    /* Querying PDO */
    public function doQuery($array) {
        if (!isset($array['sql'])) {
            $GLOBALS['awe']->Logger->setWarn(array("text" => "W0001 - Nem adtál meg sql parancsot!"));
            return FALSE;
        }
        $query = $this->Connection->prepare($array['sql']);
        if (isset($array['attr']) && !empty($array['attr'])) {
            if ($query->execute($array['attr']) == FALSE) {
                $GLOBALS['awe']->Logger->setError(array("text" => "E0005 - Hibás SQL parancs! -> ".$query->errorInfo()[2]));
                return FALSE;
            }
        } else {
            if ($query->execute() == FALSE) {
                $GLOBALS['awe']->Logger->setError(array("text" => "E0005 - Hibás SQL parancs! -> ".$query->errorInfo()[2]));
                return FALSE;
            }
        }
        return $query;
    }

    /* Fetching and Querying ---> Array-el tér vissza!*/
    public function fetchWithCount($array, $sets = NULL) {
        if ($array != NULL) {
            $q = $this->doQuery($array);
            return array("result"=>$q->fetch($sets),"count"=>$q->rowCount());
        }
        $GLOBALS['awe']->Logger->setWarn(array("text" => "W0001 - Nem adtál meg sql parancsot!"));
        return FALSE;
    }
    
    /* Fetching and Querying*/
    public function fetch($array, $sets = NULL) {
        if ($array != NULL) {
            return $this->doQuery($array)->fetch($sets);
        }
        $GLOBALS['awe']->Logger->setWarn(array("text" => "W0001 - Nem adtál meg sql parancsot!"));
        return FALSE;
    }
    
    /* FetchAll and Quering*/
    public function fetchAll($array, $sets = NULL) {
        if ($array != NULL) {
            return $this->doQuery($array)->fetchAll($sets);
        }
        $GLOBALS['awe']->Logger->setWarn(array("text" => "W0001 - Nem adtál meg sql parancsot!"));
        return FALSE;
    }
}

?>