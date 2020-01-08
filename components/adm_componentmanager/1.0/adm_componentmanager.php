<?php

class adm_componentmanager {

    public function __construct($array) {
        $arr = array(
            "table" => "adm_componentmanager",
            "columns" => array(
                "id" => "id",
                "version" => "version",
                "obj" => "obj"
            ),
            "where" => array(),
            "rowperpage" => "",
            "page" => "1",
            "filters" => array(
                "id" => array(
                    "type" => "text",
                    "name" => "id",
                    "id" => "",
                    "class" => ""),
                "version" => array(
                    "type" => "text",
                    "name" => "version",
                    "id" => "",
                    "class" => ""),
                "obj->>'list'->>'columns'" => array(
                    "type" => "text",
                    "name" => "list",
                    "id" => "",
                    "class" => "")),
            "data-result" => "result",
            "data-save-result" => "settings",
            "data-url" => "/admin/adm_componentmanager/ajax",
            "settings" => array(
                "remove" => "true",
                "edit" => array(
                    "id" => array(
                        "type" => "text",
                        "name" => "id",
                        "readonly" => "true",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "value"=>$GLOBALS['awe']->idGenerate(array()),
                        "class" => ""),
                    "obj->'list'->>'columns'" => array(
                        "type" => "text",
                        "name" => "version",
                        "isarray"=>"true",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""))
        ));

        if (isset($array["ajax"]) && $array["ajax"] == TRUE) {
//$this->Ajax($array);

            switch ($_POST['method']) {
                case "filter":
                    $GLOBALS['awe']->Template->AjaxTable($arr);

                    break;
                case "modification":
                    $GLOBALS['awe']->Template->AjaxSettingsView($arr);

                    break;
                case "new":
                    $GLOBALS['awe']->Template->AjaxSettingsView($arr);
                    break;
                case "add":
                    if ($GLOBALS['awe']->Template->AjaxSettingsSave($arr)) {
                        echo json_encode(array("html" => "<div class='success'><i class='fas fa-check'></i><div class='text'>Sikeres mentés!</div></div>"));
                    }

                    break;
                case "update":
                    if ($GLOBALS['awe']->Template->AjaxSettingsSave($arr)) {
                        echo json_encode(array("html" => "<div class='success'><i class='fas fa-check'></i><div class='text'>Sikeres mentés!</div></div>"));
                    }

                    break;

                default:
                    break;
            }
        } else {
            echo $GLOBALS['awe']->Template->TableFrameGenerator($arr);
//$this->Viewer($array);
        }
    }

}
