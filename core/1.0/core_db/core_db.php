<?php

class core_db {

    /**
     * Kapcsolat változó
     * @var PDO
     */
    private $Connection;

    /**
     * Kapcsolati konfig változó
     * @var array
     */
    private $Config;

    /**
     * Kapcsolati konfig változó
     * @var AWE
     */
    public AWE $AWE;

    /**
     * core_db konstruktora - Itt veszik fel a változók az értéküket az adott funkciókból
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return void      
     */
    public function __construct($array = array()) {
        $this->AWE = &$GLOBALS["awe"];
        $this->Config = $this->getConfig(array());
        $this->Connection = $this->getConnection(array());
    }

    /**
     * Adatbázis kapcsolat konfigok betöltése
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return array       
     */
    private function getConfig($array = array()) {
        $path = "./sites/" . $this->AWE->SiteAlias . "/config.ini";
        if (!$config = parse_ini_file($path, TRUE)) {
            $this->AWE->Logger->setError(array("text" => "E0001 - Nem található az oldal adatbázis konfigja! - " . $path));
        }
        return $config;
    }

    /**
     * Kapcsolat létrehozása - PDO osztály hívás
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @return PDO       
     */
    private function getConnection($array = array()) {
        $connectionString = $this->Config['database']['driver'] .
                ':host=' . $this->Config['database']['host'] .
                ((!empty($this->Config['database']['port'])) ? (';port=' . $this->Config['database']['port']) : '') .
                ';dbname=' . $this->Config['database']['schema'];
        try {
            $pdo = new PDO($connectionString, $this->Config['database']['username'], $this->Config['database']['password']);
        } catch (Exception $exc) {
            $this->AWE->Logger->setError(array("text" => "E0004 - Hibás adatbázis csatlakozás!"));
        }
        return $pdo;
    }

    /**
     * Custom query
     * @param array $array  [sql]->|SQL parancs kerül | [attr]->|sql parancs változói tömben
     * @global $GLOBALS["awe"]->DB->doQuery(array("sql"=>"","attr"=>""))
     * @return array|FALSE       
     */
    public function doQuery($array = array("sql" => "", "attr" => "")) {
        if (!isset($array['sql'])) {
            $this->AWE->Logger->setWarn(array("text" => "W0001 - Nem adtál meg sql parancsot!"));
            return FALSE;
        }
        $query = $this->Connection->prepare($array['sql']);
        if (isset($array['attr']) && !empty($array['attr'])) {
            if ($query->execute($array['attr']) == FALSE) {
                $this->AWE->Logger->setError(array("text" => "E0005 - Hibás SQL parancs! -> " . $query->errorInfo()[2]));
                return FALSE;
            }
        } else {
            if ($query->execute() == FALSE) {
                $this->AWE->Logger->setError(array("text" => "E0005 - Hibás SQL parancs! -> " . $query->errorInfo()[2]));
                return FALSE;
            }
        }
        return $query;
    }

    function recursiveWhereTree($array = array(), $logical = FALSE, $like = "=") {
        $ret = array("string" => "", "attr" => array());
        if ($logical != FALSE) {
            $logicalvar = " " . $logical . " ";
        } else {
            $logicalvar = "";
        }
        $i = 0;
        foreach ($array as $key => $value) {
            if (count($array) <= $i + 1) {
                $logicalvar = "";
            }
            if ($key == ".") {
                $like = $value;
            }
            else if ($key == "AND NOT" || $key == "AND" || $key == "OR NOT" || $key == "OR") {
                if (!is_array($value)) {
                    $GLOBALS["awe"]->Logger->setError(array("text" => "E0006 - Hibás nem megfelelő array!"));
                    return FALSE;
                }
                $reqursive = $this->recursiveWhereTree($value, $key, $like);
                $ret["string"] .= "(" . $reqursive["string"] . ")" . $logicalvar;
                $ret["attr"] = array_merge($ret["attr"], $reqursive["attr"]);
            } else {
                if ($logical != FALSE) {
                    $id = str_replace(str_split('%_. '), '', $GLOBALS["awe"]->idGenerate());
                    $ret["string"] .= $key . " " . $like . " " . ":" . $id . $logicalvar;
                    $ret["attr"][$id] = $value;
                }
            }
            $i++;
        }
        return $ret;
    }

    private function projection($array = array("oszlop" => "neve", "oszlop2" => "neve")) {
        // SELECT * FROM table WHERE as LIKE asd;
        $str = "";
        $i = 0;
        foreach ($array as $key => $value) {
            if (count($array) - 1 == $i) {
                $str .= $key . " AS " . $value . "";
            } else {
                $str .= $key . " AS " . $value . ", ";
            }
            $i++;
        }
        return $str;
    }

    private function joins($array = array("0" => array("type" => "INNER", "table" => "tábla", "ON" => ""))) {
        $str = " ";
        $i = 0;
        foreach ($array as $value) {
            if (count($array) - 1 == $i) {
                $str .= $value["type"] . " JOIN " . $value["table"] . " ON " . $value["ON"];
            } else {
                $str .= $value["type"] . " JOIN " . $value["table"] . " ON " . $value["ON"] . " ";
            }
            $i++;
        }
        return $str;
    }

    private function groupBy($array = array("0" => "column")) {
        $str = " GROUP BY ";
        $i = 0;
        foreach ($array as $value) {
            if (count($array) - 1 == $i) {
                $str .= $value;
            } else {
                $str .= $value . ", ";
            }
            $i++;
        }
        return $str;
    }

    private function orderby($array = array("column" => "ASC", "column2" => "DESC")) {
        $str = "";
        $i = 0;
        foreach ($array as $key => $value) {
            if (count($array) - 1 == $i) {
                $str .= $key . " " . $value;
            } else {
                $str .= $key . " " . $value . ", ";
            }
            $i++;
        }
        return $str;
    }

    private function having($array) {
        $str = "";
        $i = 0;
        foreach ($array as $key => $value) {
            if (count($array) - 1 == $i) {
                $str .= $value;
            } else {
                $str .= $value . ", ";
            }
            $i++;
        }
        return $str;
    }

    private function limit($array) {
        
    }

    private function offset($array) {
        
    }

    function Select($array = array("orderby" => array("column" => "ASC", "column2" => "DESC"), "groupby" => array("column1", "column2"), "having" => array("column1 > 2"), "distinct" => FALSE, "projection" => array(), "where" => array("oszlop" => "érték", "AND" => array("oszlop" => "érték")), "joins" => array("0" => array("type" => "INNER", "table" => "tábla", "ON" => "")))) {
        $projection = !empty($array['projection']) ? $this->projection($array['projection']) : "*";
        if (empty($array["table"])) {
            $this->AWE->Logger->setError(array("text" => "E0007 - Nem adtál meg táblát az SQL lekérdezésnél"));
            return false;
        }
        $distinct = (!empty($array["distinct"]) && $array['distinct'] == true) ? "DISTINCT " : "";
        $joins = !empty($array["joins"]) ? $this->joins($array["joins"]) : "";
        $groupby = !empty($array["groupby"]) ? $this->groupBy($array["groupby"]) : "";
        $having = !empty($groupby) && !empty($array["having"]) ? " HAVING " . $this->having($array["having"]) : "";
        $orderby = !empty($array["orderby"]) ? " ORDER BY " . $this->orderby($array["orderby"]) : "";
        $limit = !empty($array["limit"]) ? " LIMIT " . $array["limit"] : "";
        $offset = !empty($array["limit"]) && !empty($array["offset"]) ? " OFFSET " . $array["offset"] : "";
        $where = !empty($array["where"]) ? $this->recursiveWhereTree($array["where"]) : "";
        $wherestr = !empty($where) ? " WHERE " . $where["string"] : "";
        var_dump($where);
        $str = "SELECT " . $distinct . $projection . " FROM " . $array["table"] . $joins . $wherestr . $groupby . $having . $orderby . $limit . $offset;
        echo $str;
        return $this->fetchAll(array("sql" => $str, "attr" => $where["attr"]), PDO::FETCH_ASSOC);
    }

    /* Fetching and Querying ---> Array-el tér vissza! */

    public function fetchWithCount($array, $sets = NULL) {
        if ($array != NULL) {
            $q = $this->doQuery($array);
            return array("result" => $q->fetch($sets), "count" => $q->rowCount());
        }
        $this->AWE->Logger->setWarn(array("text" => "W0001 - Nem adtál meg sql parancsot!"));
        return FALSE;
    }

    /* Fetching and Querying */

    public function fetch($array, $sets = NULL) {
        if ($array != NULL) {
            return $this->doQuery($array)->fetch($sets);
        }
        $this->AWE->Logger->setWarn(array("text" => "W0001 - Nem adtál meg sql parancsot!"));
        return FALSE;
    }

    /* FetchAll and Quering */

    public function fetchAll($array, $sets = NULL) {
        if ($array != NULL) {
            return $this->doQuery($array)->fetchAll($sets);
        }
        $this->AWE->Logger->setWarn(array("text" => "W0001 - Nem adtál meg sql parancsot!"));
        return FALSE;
    }

}

?>