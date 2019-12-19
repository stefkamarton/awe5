<?php

class core_translator {
    private AWE $AWE;
    public function __construct($array) {
        $this->AWE = &$GLOBALS["awe"];
    }

    public function getExpression($array) {
        if (!isset($array['key']) && $array['key'] == "") {
            $this->AWE->Logger->setError(array("text" => "E0002 - Nem adtál meg kifejezést!"));
            return "<b>#E0002 - Nem adtál meg kifejezést!#</b>";
        }

        $query = $this->AWE->DB->fetchWithCount(array("sql" => "SELECT *, tra_obj -> 'language' ->> :lang AS expression FROM core_translator WHERE tra_key=:tra_key", "attr" => array("tra_key" => $array['key'], "lang" => $this->AWE->Language)));

        if ($query['count'] == 0) {
            $this->AWE->Logger->setWarn(array("key" => "W0002 - Nem található {" . $array['key'] . "} kulcs a fordítóban! Létrehozás..."));

            /* Rekord létrehozása */
            /* Json létrehozás */
            $json = array("language" => array($this->AWE->Language => $array['key']));
            $json = json_encode($json);
            $this->AWE->DB->doQuery(array("sql" => "INSERT INTO core_translator (tra_key, tra_obj) VALUES(:tra_key, :tra_obj)", "attr" => array("tra_obj" => $json, "tra_key" => $array['key'])));

            $this->AWE->Logger->setInfo(array("key" => "I0001 - " . $array["key"] . " kulcs létrehozva a fordítóba!"));

            return $array["key"];
        } else if ($query['result']['expression'] == NULL || !isset($query['result']['expression'])) {
            $this->AWE->Logger->setWarn(array("key" => "W0003 - Nem található {" . $array['key'] . "} kulcs {" . $this->AWE->Language . "} nyelven a fordítóban! Létrehozás..."));

            /* Rekord frissítése */
            /* Json létrehozás */
            $json = (array) json_decode($query['result']['tra_obj']);
            $json['language'] = (array) $json['language'];
            $json['language'][$this->AWE->Language] = $array["key"];
            $json = json_encode($json);
            $this->AWE->DB->doQuery(array("sql" => "UPDATE core_translator SET tra_obj=:tra_obj WHERE tra_key=:tra_key", "attr" => array("tra_obj" => $json, "tra_key" => $array['key'])));

            $this->AWE->Logger->setInfo(array("key" => "I0002 - " . $array["key"] . " kulcs frissítve {" . $this->AWE->Language . "} nyelven a fordítóba!"));

            return $array["key"];
        }
        return $query['result']['expression'];
    }
}
?>
