<?php

/**
 * @package AWE
 * @subpackage AWE
 * @filesource
 *  */
class core_template{

    public $Template;
    public $BodyClass;
    public $TemplateObj;
    private $JS;
    private $CSS;
    private $LoadedPositions;
    public $Params;

    public function __construct() {
        $this->Template = $this->getTemplate(array());
        $this->BodyClass = $GLOBALS['awe']->getUrlId(array());
        $this->JS = array();
        $this->CSS = array();
        $this->addCSS(array("css" => "/core/" . $GLOBALS['awe']->CoreVersion . "/core_template/gfx/table.css"));
    }

    /* Template/Oldal betöltés */

    public function Load($array) {
        $this->PreLoadComponents(array());
        if (!isset($this->TemplateObj['nohtml']))
            $this->TemplateObj['nohtml'] = FALSE;

        if ($this->TemplateObj['nohtml'] == false) {
            echo "<!DOCTYPE html>" . PHP_EOL;
            echo "<html lang='" . substr($GLOBALS['awe']->Language, 0, 2) . "'>" . PHP_EOL;
            echo "<head>" . PHP_EOL;
            echo $this->getCSS(array());
            echo $this->getJS(array());
            echo "</head>" . PHP_EOL;

            echo "<body class='$this->BodyClass'>" . PHP_EOL;
        }
        if ($this->Template != NULL) {
            require_once("./templates/" . $this->Template . "/" . $this->Template . ".php");
        }
        if ($this->TemplateObj['nohtml'] == false) {
            echo "</body>";
            echo "</html>" . PHP_EOL;
        }
        return TRUE;
    }

    public function AddBodyClass($array) {
        if (isset($array['string'])) {
            $this->BodyClass = " " . $array['string'];
            return TRUE;
        }
        return FALSE;
    }

    /* doctype lekérdezés */

    public function getDocType($array) {
        $result = $GLOBALS["awe"]->DB->fetch(array("sql" => "SELECT defaults_obj FROM defaults WHERE defaults_id=:defaults_id", "attr" => array("defaults_id" => "document_type")));
        $result = (array) json_decode($result['defaults_obj']);
        return $result['document_type'];
    }

    /* Head lekérdezés */

    public function getHead($array) {
        
    }

    /* Template lekérdezése */

    private function getTemplate($array) {
        $template = $GLOBALS['awe']->DB->fetchAll(array("sql" => "SELECT * FROM core_url_template WHERE (multisite_id=:multisite_id OR multisite_id=:null) ORDER BY url_template_obj->>'priority' ASC", "attr" => array("null" => 0, "multisite_id" => $GLOBALS['awe']->MultiSiteId)), PDO::FETCH_ASSOC);
        foreach ($template as $value) {
            $urlWithoutStar = str_replace("*", "", $value['url_url']);
            if ($GLOBALS['awe']->stringContains(array("string" => $GLOBALS['awe']->Url, "substring" => $urlWithoutStar)) && $GLOBALS['awe']->stringContains(array("string" => $value['url_url'], "substring" => "*"))) {
                if ($GLOBALS['awe']->stringStartsWith(array("string" => $value['url_url'], "substring" => "*")) && $GLOBALS['awe']->stringEndsWith(array("string" => $GLOBALS['awe']->Url, "substring" => $urlWithoutStar))) {
                    if (isset($value['url_template_obj'])) {
                        $this->TemplateObj = (array) json_decode($value["url_template_obj"]);
                    }
                    return $value['template_name'];
                } else if ($GLOBALS['awe']->stringEndsWith(array("string" => $value['url_url'], "substring" => "*")) && $GLOBALS['awe']->stringStartsWith(array("string" => $GLOBALS['awe']->Url, "substring" => $urlWithoutStar))) {
                    if (isset($value['url_template_obj'])) {
                        $this->TemplateObj = (array) json_decode($value["url_template_obj"]);
                    }
                    return $value['template_name'];
                }
            } else if ($GLOBALS['awe']->Url == $value['url_url']) {
                return $value['template_name'];
            }
        }
        return "default";
    }

    public function PreLoadComponents($array) {
        $obj = $this->getPositionElements(array());
        if (count($obj) > 0) {
            foreach ($obj as $val) {
                $json = (array) json_decode($val['url_obj']);
                if (!isset($json['name']))
                    $json['name'] = "";
                $results = $GLOBALS['awe']->getDefaults(array("defaults_id" => $json["name"]));

                if (count($results) > 0) {
                    foreach ($results as $result) {
                        $result = (array) json_decode($result['defaults_obj']);
                        if (isset($result["js"])) {
                            foreach ($result["js"] as $js) {
                                $this->addJS(array("js" => $js));
                            }
                        }
                        if (isset($result["css"])) {
                            foreach ($result["css"] as $css) {
                                $this->addCSS(array("css" => $css));
                            }
                        }
                    }
                }
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            die("404 - Page Not Found");
        }
    }

    public function addJS($array) {
        if (isset($array["js"]) && !in_array($array["js"], $this->JS)) {
            $this->JS[] = $array["js"];
            return TRUE;
        }
        return FALSE;
    }

    public function getJS($array) {
        $str = "";
        foreach ($this->JS as $js) {
            if ($GLOBALS['awe']->stringContains(array("string" => $js, "substring" => "http")) && $GLOBALS['awe']->stringContains(array("string" => $js, "substring" => "https"))) {
                $str .= "<script src=" . $js . "></script>" . PHP_EOL;
            } else {
                $str .= "<script src=" . $GLOBALS['awe']->Domain . "/" . $js . "></script>" . PHP_EOL;
            }
        }
        return $str;
    }

    public function getCSS($array) {
        $str = "";
        foreach ($this->CSS as $css) {
            if ($GLOBALS['awe']->stringContains(array("string" => $css, "substring" => "http")) && $GLOBALS['awe']->stringContains(array("string" => $css, "substring" => "https"))) {
                $str .= "<link rel='stylesheet' type='text/css' href=" . $css . " />" . PHP_EOL;
            } else {
                $str .= "<link rel='stylesheet' type='text/css' href=" . $GLOBALS['awe']->Domain . "/" . $css . " />" . PHP_EOL;
            }
        }
        return $str;
    }

    public function addCSS($array) {
        if (isset($array["css"]) && !in_array($array["css"], $this->CSS)) {
            $this->CSS[] = $array["css"];
            return TRUE;
        }
        return FALSE;
    }

    /* URL-hez tartozó elemek */

    public function getPositionElements($array) {
        if (isset($array['position'])) {
            if ($array['position'] == "*") {
                return $GLOBALS['awe']->DB->fetchAll(array("sql" => "SELECT * FROM core_url WHERE url_url=:url_url AND (multisiteid LIKE :multisiteid OR multisiteid LIKE :null) ORDER BY url_seq ASC", "attr" => array("multisiteid" => "%" . $GLOBALS['awe']->MultiSiteId . "%", "null" => "%" . '*' . "%", "url_url" => $GLOBALS['awe']->Url)), PDO::FETCH_ASSOC);
            } else {
                return $GLOBALS['awe']->DB->fetchAll(array("sql" => "SELECT * FROM core_url WHERE url_url=:url_url AND url_pos=:url_pos AND (multisiteid LIKE :multisiteid OR multisiteid LIKE :null) ORDER BY url_seq ASC", "attr" => array("multisiteid" => "%" . $GLOBALS['awe']->MultiSiteId . "%", "null" => "%" . '*' . "%", "url_url" => $GLOBALS['awe']->Url, "url_pos" => $array['position'])), PDO::FETCH_ASSOC);
            }
        }
        return $GLOBALS['awe']->DB->fetchAll(array("sql" => "SELECT * FROM core_url WHERE url_url=:url_url AND (multisiteid LIKE :multisiteid OR multisiteid LIKE :null) ORDER BY url_seq ASC", "attr" => array("multisiteid" => "%" . $GLOBALS['awe']->MultiSiteId . "%", "null" => "%" . '*' . "%", "url_url" => $GLOBALS['awe']->Url)), PDO::FETCH_ASSOC);
    }

    public function getIncludes($array) {
        $include = "";
        foreach ($this->JS as $value) {
            $include .= '<script src="' . $value . '"></script>' . PHP_EOL;
        }
        foreach ($this->CSS as $value) {
            $include .= '<link rel="stylesheet" type="text/css" href="' . $value . '">' . PHP_EOL;
        }
        return $include;
    }

    /* Pozíció lekérdezés */

    public function getPosition($array) {
        if (!isset($array['position'])) {
            return FALSE;
        }
        $loadedpositions[] = $array['position'];
        $objs = $this->getPositionElements($array);
//var_dump($objs);
        if (count($objs) <= 0) {
            $GLOBALS['awe']->Logger->setWarn(array("text" => "Nincs mit megjeleníteni ebben a pozicióba: {" . $array['position'] . "}"));
            return false;
        }
        foreach ($objs as $obj) {
            $json = (array) json_decode($obj['url_obj']);
            if ($json["auth"] == NULL || $GLOBALS['awe']->Permissions->Check(array("permissions" => $json["auth"]))) {
                if ($json["public"] == TRUE) {
                    $this->LoadComponents($json);
                    eval($json["command"]);
                }
            } else {
                echo "<br>Nincs jog<br>";
            }
        }
    }

    public function LoadComponents($array) {
        if (!isset($array["params"]))
            $array["params"] = array();
        if (isset($array["name"])) {
            $result = $GLOBALS['awe']->DB->fetch(array("sql" => "SELECT defaults_obj FROM defaults WHERE defaults_id=:defaults_id", "attr" => array("defaults_id" => $array["name"])), PDO::FETCH_ASSOC);
            if ($result != NULL || $result != FALSE) {
                $result = (array) json_decode($result['defaults_obj']);
                if (isset($result["js"])) {
                    foreach ($result["js"] as $js) {
                        $GLOBALS['awe']->Template->addJS(array("js" => $js));
                    }
                }
                if (isset($result["css"])) {
                    foreach ($result["css"] as $css) {
                        $GLOBALS['awe']->Template->addCSS(array("css" => $css));
                    }
                }
                if (isset($result["version"])) {
                    $file = "./components/" . $array["name"] . "/" . $result["version"] . "/" . $array["name"] . ".php";
                    if (file_exists($file)) {
                        require_once $file;
                        $array["params"] = (array) $array["params"];
                        $GLOBALS['awe']->Components[$array["name"]] = new $array["name"]($array["params"]);
                        return TRUE;
                    }
                }
            } else {
                $file = "./components/" . $array["name"] . "/";
                $dir = scandir($file);

                $file = $file . $dir[(count($dir) - 1)] . "/" . $array["name"] . ".php";
                if (file_exists($file)) {
                    require_once $file;
                    $array["params"] = array("install" => "true");
                    $GLOBALS['awe']->Components[$array["name"]] = new $array["name"]($array["params"]);
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /* ------------------ SETTINGS SAVE ------------------ */

    public function AjaxSettingsSave($array) {

        foreach ($_POST as $key => $value) {
            if (is_array($value)) {
                for ($i = 0; $i < count($_POST[$key]); $i++) {
                    if ($_POST[$key][$i] == NULL && $_POST[$key][$i] == "") {
                        unset($_POST[$key][$i]);
                        $_POST[$key] = array_values($_POST[$key]);
                    }
                }
            }
        }
        if (isset($array['table']) && isset($array['columns']) && isset($array['settings']) && isset($array['settings']['edit']) && isset($array['data-result']) && isset($array['data-url']) && isset($array['filters'])) {
            $i = 0;
            $where = "";
            $whereval = "";
            $wherekey = "";
            $attr = array();
            $update = "";
            $obj = "";
            foreach ($array['settings']['edit'] as $key => $value) {
                if ($i == 0) {
                    $where .= $key . "=:where";
                    $wherekey = "where";
                    $whereval = $_POST[$value['name']];
                } else {
                    break;
                }
                $i++;
            }
            $obj = $GLOBALS['awe']->DB->fetch(array("sql" => "SELECT * FROM " . $array['table'] . " WHERE " . $where, "attr" => array($wherekey => $whereval)), PDO::FETCH_ASSOC);
            foreach ($obj as $key => $value) {
                if ($GLOBALS['awe']->isJSON(array("string" => $value))) {
                    $obj[$key] = (array) json_decode($value, true);
                }
            }
            foreach ($array['settings']['edit'] as $key => $value) {
                if (isset($_POST[$value['name']])) {
                    if ($GLOBALS['awe']->stringContains(array("string" => $key, "substring" => "->"))) {
                        $key = str_replace("->>", "->", $key);
                        $keys = explode("->", $key);
                        $sobj = array();


                        for ($i = count($keys) - 1; $i >= 0; $i--) {
                            $keys[$i] = str_replace("'", "", $keys[$i]);
                            if ($i == count($keys) - 1) {
                                $sobj[$keys[$i]] = $_POST[$value['name']];
                            } else {
                                $sobj[$keys[$i]] = $sobj;
                                unset($sobj[$keys[$i + 1]]);
                            }
                        }
                        if (is_array($_POST[$value['name']])) {
                            $obj = array_merge_recursive($sobj);
                        } else {
                            $obj = array_replace_recursive($obj, $sobj);
                        }
                    } else {
                        $obj[$value["name"]] = $_POST[$value['name']];
                    }
                }
            }
            $update = "";
            $j = 0;
            foreach ($obj as $key => $value) {
                if ($j == 0) {
                    $update .= $key . "=:" . $key;
                } else {
                    $update .= ", " . $key . "=:" . $key;
                }
                $j++;
            }
            foreach ($obj as $key => $value) {
                if (is_array($value)) {
                    $obj[$key] = json_encode($value);
                }
            }
            //var_dump($obj);
            $obj[$wherekey] = $whereval;
            $GLOBALS['awe']->DB->doQuery(array("sql" => "UPDATE " . $array['table'] . " SET " . $update . " WHERE " . $where, "attr" => $obj));
            return true;
        } else {
            echo "Hiba - Nincsenek paraméterek";
        }
    }

    /* ------------------ SETTINGS ---------------------- */

    public function AjaxSettingsView($array) {
        if (isset($array['table']) && isset($array['columns']) && isset($array['settings']) && isset($array['settings']['edit']) && isset($array['data-result']) && isset($array['data-url']) && isset($array['filters'])) {
            foreach ($array["settings"]["edit"] as $value) {
                if (!isset($value['type'])) {
                    $value['type'] = "text";
                }
                if (!isset($value['name'])) {
                    $value['name'] = "unknown_field";
                }
                if (!isset($value['readonly'])) {
                    $value['readonly'] = "false";
                }
                if (!isset($value['disabled'])) {
                    $value['disabled'] = "false";
                }
                if (!isset($value['autofocus'])) {
                    $value['autofocus'] = "false";
                }
                if (!isset($value['required'])) {
                    $value['required'] = "false";
                }
                if (!isset($value['id'])) {
                    $value['id'] = "";
                }
                if (!isset($value['class'])) {
                    $value['class'] = "";
                }
            }
            $ret = array();
            $columns = "";
            $i = 0;
            $wherekey = "";
            $wherevalue = "";
            foreach ($array["settings"]["edit"] as $key => $value) {
                foreach ($_POST as $postkey => $postvalue) {
                    if ($key == $postkey) {
                        $wherekey = $key;
                        $wherevalue = $postvalue;
                    }
                }
            }
            foreach ($array["settings"]["edit"] as $key => $value) {
                $key2 = str_replace("@", "'", $key);
                if ($i != 0) {
                    $columns .= ", " . $key . " AS " . $value["name"];
                } else {
                    $columns .= $key . " AS " . $value["name"];
                }
                $i++;
            }
            $str = "<div class='heading'><h2>Modósítás</h2></div>";
            $str .= "<div id='messages'></div>";
            $str .= "<div class='close' id='close-settings'><i class='fas fa-times'></i></div>";
            $str .= "<form class='table' method='post' data-method='save' data-waiting='0' data-result='#messages' data-url='" . $array["data-url"] . "' >";
            foreach ($array["settings"]["edit"] as $key => $value) {
                $sql = $GLOBALS['awe']->DB->fetch(array("sql" => "SELECT $columns FROM " . $array['table'] . " WHERE " . $wherekey . "=:" . $wherekey, "attr" => array($wherekey => $wherevalue)), PDO::FETCH_ASSOC);
                $readonly = (isset($value["readonly"]) && $value["readonly"] != NULL && strtolower($value["readonly"]) != "false") ? "readonly" : "";
                $disabled = (isset($value["disabled"]) && $value["disabled"] != NULL && strtolower($value["disabled"]) != "false") ? "disabled" : "";
                $required = (isset($value["required"]) && $value["required"] != NULL && strtolower($value["required"]) != "false") ? "required" : "";
                $autofocus = (isset($value["autofocus"]) && $value["autofocus"] != NULL && strtolower($value["autofocus"]) != "false") ? "autofocus" : "";
                if ((isset($value["isarray"]) && $value["isarray"] != NULL && strtolower($value["isarray"]) != "false")) {
                    $isarray = "[]";
                    $isarray_btn = "<a class='btn' id='addnew'>+</a>";
                } else {
                    $isarray = "";
                    $isarray_btn = "";
                }
                if (isset($sql[strtolower($value['name'])])) {
                    $val = $sql[strtolower($value['name'])];
                } else {
                    $val = "";
                }
                $str .= "<div class='save-form tr'><div class='name td'>" . $value['name'] . "</div>";
                if ((isset($value["isarray"]) && $value["isarray"] != NULL && strtolower($value["isarray"]) != "false")) {
                    $json = json_decode($val);
                    foreach ($json as $jsonval) {
                        if ($value["type"] == "textarea") {
                            $str .= "<div class='field-in td'><textarea id='" . $value['id'] . "' class='" . $value['class'] . "' type='" . $value['type'] . "' name='" . $value['name'] . $isarray . "' $readonly $disabled $required $autofocus>$jsonval</textarea></div>";
                        } else {
                            $str .= "<div class='field-in td'><input id='" . $value['id'] . "' class='" . $value['class'] . "' type='" . $value['type'] . "' value='" . $jsonval . "' name='" . $value['name'] . $isarray . "' $readonly $disabled $required $autofocus /></div>";
                        }
                    }
                } else {
                    if ($value["type"] == "textarea") {
                        $str .= "<div class='field-in td'><textarea id='" . $value['id'] . "' class='" . $value['class'] . "' type='" . $value['type'] . "' name='" . $value['name'] . $isarray . "' $readonly $disabled $required $autofocus>$val</textarea></div>";
                    } else {
                        $str .= "<div class='field-in td'><input id='" . $value['id'] . "' class='" . $value['class'] . "' type='" . $value['type'] . "' value='" . $val . "' name='" . $value['name'] . $isarray . "' $readonly $disabled $required $autofocus /></div>";
                    }
                }
                $str .= $isarray_btn;
                $str .= "</div>";
            }
            $str .= "<div class='btn save'><i class='far fa-save'></i>Mentés</div>";
            $str .= "</form>";

            $ret['html'] = $str;
            echo json_encode($ret);
            return TRUE;
        }
        return FALSE;
    }

    /* ------------ LISTING ------------------- */

    public function AjaxTable($array) {
        $ret = array();
        $ret['html'] = $this->JustTable($array);
        $ret['url_params'] = array();
        if ($_POST['url_params'] != NULL) {
            foreach ((array) json_decode($GLOBALS['awe']->base64url_decode(array("data" => $_POST['url_params']))) as $key => $value) {
                $ret['url_params'][$key] = $value;
            }
        }
        unset($_POST['url_params']);
        foreach ($_POST as $key => $value) {
            $ret['url_params'][$key] = $value;
        }
        $ret['url_params'] = $GLOBALS['awe']->addUrlParams(array("forced_merge" => $ret['url_params']));
        echo json_encode($ret);
    }

    private function JustTable($array = array()) {
        if (isset($array['table'])) {
            if (isset($_POST['url_params']) && $_POST['url_params'] != NULL)
                $this->Params = (array) json_decode($GLOBALS['awe']->base64url_decode(array("data" => $_POST['url_params'])));

            $str = "";

            if (!isset($array['attr'])) {
                $array['attr'] = array();
            }

//var_dump($this->Params);
            /* Where */
            $where = "";
            $i = 0;
            foreach ($array["filters"] as $key => $value) {
                $identifier = "identify" . $i;
                $add = FALSE;
                if (isset($_POST[$value['name']]) && $_POST[$value['name']] != NULL) {
                    $array['attr'][$identifier] = "%" . strtolower($_POST[$value['name']]) . "%";
                    $add = TRUE;
                } else if (isset($this->Params[$value['name']]) && $this->Params[$value['name']] != NULL) {
                    $array['attr'][$identifier] = "%" . strtolower($this->Params[$value['name']]) . "%";
                    $add = TRUE;
                } else if (isset($array['where'][$value['name']]) && $array['where'][$value['name']] != NULL) {
                    $array['attr'][$identifier] = "%" . strtolower($array['where'][$value['name']]) . "%";
                    $add = TRUE;
                }
                if ($add === true) {
                    if ($where == "")
                        $where = "WHERE ";
                    else
                        $where .= " AND ";
                    $where .= "lower(" . $key . ") LIKE " . ":" . $identifier;
                }
                $i++;
            }
//var_dump($_POST);


            /* Rendezés */
            $order = "";
            foreach ($array['columns'] as $key => $value) {
                if (isset($_POST['orderby']) && $_POST['orderby'] != NULL && $order == NULL) {
                    if ($_POST['orderby'] == strtolower($value) . ":DESC") {
                        $order = "ORDER BY " . $key . " DESC";
                        $_POST['pagenumber'] = 1;
                    } else if ($_POST['orderby'] == strtolower($value) . ":ASC") {
                        $order = "ORDER BY " . $key . " ASC";
                        $_POST['pagenumber'] = 1;
                    }
                } else if (isset($this->Params['orderby']) && $this->Params['orderby'] != NULL && $order == NULL) {
                    if ($this->Params['orderby'] == strtolower($value) . ":DESC") {
                        $order = "ORDER BY " . $key . " DESC";
                    } else if ($this->Params['orderby'] == strtolower($value) . ":ASC") {
                        $order = "ORDER BY " . $key . " ASC";
                    }
                } else if (isset($array['orderby']) && $array['orderby'] != NULL && $order == NULL) {
                    if ($array['orderby'] == strtolower($value) . ":DESC") {
                        $order = "ORDER BY " . $key . " DESC";
                    } else if ($array['orderby'] == strtolower($value) . ":ASC") {
                        $order = "ORDER BY " . $key . " ASC";
                    }
                }
            }

            $table = $array['table'];
            $max = $GLOBALS['awe']->DB->fetch(array("sql" => "SELECT COUNT(*) FROM $table $where", "attr" => $array['attr']), PDO::FETCH_ASSOC);
            $max = $max['count'];



            /* PAGER */
            $rowperpage = 10;

            if (isset($_POST['rowperpage']) && $_POST['rowperpage'] != NULL) {
                if ($_POST['rowperpage'] == "all") {
                    $rowperpage = $max;
                } else {
                    $rowperpage = $_POST['rowperpage'];
                }
            } else if (isset($this->Params['rowperpage']) && $this->Params['rowperpage'] != NULL) {
                if ($this->Params['rowperpage'] == "all") {
                    $rowperpage = $max;
                } else {
                    $rowperpage = $this->Params['rowperpage'];
                }
            } else if (isset($array['rowperpage']) && $array['rowperpage'] != NULL) {
                if ($array['rowperpage'] == "all") {
                    $rowperpage = $max;
                } else {
                    $rowperpage = $array['rowperpage'];
                }
            }

            $page = 0;
            if (isset($_POST['pagenumber']) && $_POST['pagenumber'] != NULL) {
                if (ceil($max / $rowperpage) < $_POST['pagenumber']) {
                    $page = ceil($max / $rowperpage) - 1;
                } else if ($_POST['pagenumber'] <= 0) {
                    $page = 0;
                } else {
                    $page = $_POST['pagenumber'] - 1;
                }
            } else if (isset($this->Params['pagenumber']) && $this->Params['pagenumber'] != NULL) {
                $page = $this->Params['pagenumber'] - 1;
            } else if (isset($array['pagenumber']) && $array['pagenumber'] != NULL) {
                $page = $array['pagenumber'] - 1;
            }
            $limit = "LIMIT " . $rowperpage;
            $offset = "OFFSET " . ($page * $rowperpage);

            /* Oszlopok */
            $columns = "";
            if (isset($array['columns']) && $array['columns'] != null) {
                foreach ($array['columns'] as $key => $value) {
                    $columns .= $key . " AS " . $value . ", ";
                }
                $columns = substr($columns, 0, -2);
            } else {
                $columns = "*";
            }

//var_dump($array);
            //var_dump($order);

            $rows = $GLOBALS['awe']->DB->fetchAll(array("sql" => "SELECT $columns FROM $table $where $order $limit $offset", "attr" => $array['attr']), PDO::FETCH_ASSOC);
            if (count($rows) > 0) {
                $str .= "<div class='table'>" . PHP_EOL;
                $str .= "<form class='thead' id='ajax' method='post' data-waiting='0' data-method='filter' data-result='#" . $array['data-result'] . "' data-url='" . $array['data-url'] . "'>" . PHP_EOL;
                $str .= "<div class='tr'>" . PHP_EOL;

                foreach ($rows[0] as $key => $value) {
                    $str .= "<div class='th'><div class='th-in'><div class='column-name'>" . T($key) . "</div>"
                            . "<div class='order'>"
                            . "<div class='order-in ";
                    if ($GLOBALS['awe']->stringContains(array("string" => $order, "substring" => "ASC")) && $GLOBALS['awe']->stringContains(array("string" => $order, "substring" => $key))) {
                        $str .= "active";
                    }
                    $str .= "'><label for='" . $key . "ASC'><i class='fas fa-sort-alpha-down'></i></label><input style='display:none;' id='" . $key . "ASC' name='orderby' class='asc' type='radio' value='" . $key . ":ASC'></div>"
                            . "<div class='order-in ";
                    if ($GLOBALS['awe']->stringContains(array("string" => $order, "substring" => "DESC")) && $GLOBALS['awe']->stringContains(array("string" => $order, "substring" => $key))) {
                        $str .= "active";
                    }

                    $str .= "'><label for='" . $key . "DESC'><i class='fas fa-sort-alpha-up'></i></label><input style='display:none;' id='" . $key . "DESC' name='orderby' class='desc' type='radio' value='" . $key . ":DESC'></div>"
                            . "</div></div></div>" . PHP_EOL;
                }
                $str .= "</div>" . PHP_EOL;
                $str .= "</form>" . PHP_EOL;
                $str .= "<div class='tbody'>" . PHP_EOL;

                foreach ($rows as $row) {
                    $str .= "<form class='tr settingsajax' method='post' data-waiting='0' data-method='settings' data-result='#settings' data-url='" . $array['data-url'] . "'>" . PHP_EOL;
                    $i = 0;
                    foreach ($row as $key => $value) {
                        if ($i == 0) {
                            $str .= "<div class='td'><input class='hidden' type='text' name='" . $key . "' value='" . $value . "' readonly />" . $value . "</div>" . PHP_EOL;
                        } else {
                            $str .= "<div class='td'>$value </div>" . PHP_EOL;
                        }

                        $i++;
                    }
                    $str .= "</form>" . PHP_EOL;
                }
                $str .= "</div>" . PHP_EOL;
                $str .= "</div>" . PHP_EOL;

                $str .= "<div class='pager'>" . PHP_EOL;
                $str .= "<div class='pager-block'><b>" . T("talalatok") . "</b><input type='text' value='" . $max . "' disabled /></div>" . PHP_EOL;
                $str .= "<form class='pager-block' id='pager' method='post' data-waiting='1000' data-method='filter' data-result='#" . $array['data-result'] . "' data-url='" . $array['data-url'] . "'>";
                $str .= "<div class='btn' id='first-page'><i class='fas fa-angle-double-left'></i></div>";
                $str .= "<div class='btn' id='previous-page'><i class='fas fa-angle-left'></i></div>";
                $str .= "<input id='max' value='" . ceil($max / $rowperpage) . "' disabled ><div class='per'>/</div><input id='pagenumber' type='text' name='pagenumber' value='" . ($page + 1) . "' />";
                $str .= "<div class='btn' id='next-page'><i class='fas fa-angle-right'></i></div>";
                $str .= "<div class='btn' id='last-page'><i class='fas fa-angle-double-right'></i></div>";
                $str .= "</form>" . PHP_EOL;
                $str .= "<div class='pager-block'><b>" . T("sorperoldal") . "</b><input type='text' value='" . $rowperpage . "' disabled /></div>" . PHP_EOL;
                $str .= "</div>" . PHP_EOL;
            } else {
                $str .= "<div class='message'><div class='warning'><i class='fas fa-2x fa-exclamation-triangle'></i><div class='text'>" . T("nincstalalat") . "</div></div></div>";
            }
            return $str;
        }
    }

    public function TableFrameGenerator($array) {
        if (isset($array['table']) && isset($array['columns']) && isset($array['settings']) && isset($array['settings']['edit']) && isset($array['data-result']) && isset($array['data-url']) && isset($array['filters'])) {
            foreach ($array["filters"] as $value) {
                if (!isset($value['type'])) {
                    $value['type'] = "text";
                }
                if (!isset($value['name'])) {
                    $value['name'] = "unknown_field";
                }
                if (!isset($value['id'])) {
                    $value['id'] = "";
                }
                if (!isset($value['class'])) {
                    $value['class'] = "";
                }
            }
            $this->Params = $GLOBALS['awe']->getUrlParams(array(), FALSE);
            if (!isset($this->Params['rowperpage'])) {
                $this->Params['rowperpage'] = 10;
            }
            $str = "";
            $str .= "<form class='filter' id='ajax' method='post' data-method='filter' data-result='#" . $array['data-result'] . "' data-url='" . $array['data-url'] . "'>" . PHP_EOL;
            foreach ($array["filters"] as $key => $value) {
                $val = "";

                if (isset($this->Params[$value['name']]) && $this->Params[$value['name']] != NULL) {
                    $val = $this->Params[$value['name']];
                } else if (isset($array['where'][$value['name']]) && $array['where'][$value['name']] != NULL) {
                    $val = $array['where'][$value['name']];
                }
                $str .= "<div class='filter-input'><div class='text'>" . T($value['name']) . "</div>";
                if ($value['type'] == "textarea") {
                    $str .= "<div class='field'><textarea id='" . $value['id'] . "' class='" . $value["class"] . "' type='" . $value['type'] . "' name='" . $value['name'] . "'>$val</textarea></div>" . PHP_EOL;
                } else {
                    $str .= "<div class='field'><input id='" . $value['id'] . "' class='" . $value["class"] . "' type='" . $value['type'] . "' name='" . $value['name'] . "' value='$val' /></div>" . PHP_EOL;
                }

                $str .= "</div>";
            }
            $str .= "<div class='filter-input'><div class='text'>" . T("sorperoldal") . "</div>"
                    . "<select name='rowperpage'>"
                    . "<option value='3' " . (($this->Params['rowperpage'] == 3) ? "selected" : "") . ">3</option>"
                    . "<option value='10' " . (($this->Params['rowperpage'] == 10) ? "selected" : "") . ">10</option>"
                    . "<option value='20' " . (($this->Params['rowperpage'] == 20) ? "selected" : "") . ">20</option>"
                    . "<option value='50' " . (($this->Params['rowperpage'] == 50) ? "selected" : "") . ">50</option>"
                    . "<option value='100' " . (($this->Params['rowperpage'] == 100) ? "selected" : "") . ">100</option>"
                    . "<option value='all' " . (($this->Params['rowperpage'] == "all") ? "selected" : "") . ">" . T("osszes") . "</option>"
                    . "</select>"
                    . "</div>";
            //$str .= "<div><i class='fas fa-plus-circle'></i></div>" . PHP_EOL;
            $str .= "<div class='filter-input'><a class='btn' href='" . $GLOBALS['awe']->Domain . $GLOBALS['awe']->Url . "'><i class='far fa-times-circle'></i> " . T("szuresfelteteltorles") . "</a></div>";

            $str .= "</form>" . PHP_EOL;
            // NEW SQL...


            $str .= "<form class='filter' id='ajax' method='post' data-method='new' data-result='#" . $array['data-result'] . "' data-url='" . $array['data-url'] . "'>" . PHP_EOL;
            $str .= "<div class='filter-input'><a class='btn' href='" . $GLOBALS['awe']->Domain . $GLOBALS['awe']->Url . "'><i class='far fa-times-circle'></i> " . T("UJ-ELEM") . "</a></div>";
            $str .= "</form>" . PHP_EOL;


            $str .= "<div id='" . $array['data-result'] . "'>" . PHP_EOL;
            $str .= $this->JustTable($array);
            $str .= "</div>" . PHP_EOL;
            $str .= "<div id='settings'></div>";


            return $str;
        }
        return FALSE;
    }

}
