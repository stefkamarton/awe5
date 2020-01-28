<?php

class adm_urlmanger {

    public function __construct($array) {
        $arr = array(
            "table" => "core_url",
            "columns" => array(
                "url_id" => "url_id",
                "url_seq" => "url_seq",
                "url_pos" => "url_pos",
                "url_url" => "url_url",
                "url_obj->>'auth'" => "auth",
                "url_obj->>'name'" => "name",
                "url_obj->>'type'" => "type",
                "url_obj->>'params'" => "params",
                "url_obj->>'public'" => "public",
                "url_obj->>'command'" => "command",
                "multisiteid" => "multisiteid",
            ),
            "where" => array(),
            "rowperpage" => "",
            "page" => "2",
            "filters" => array(
                "url_id" => array(
                    "type" => "text",
                    "name" => "url_id",
                    "id" => "",
                    "class" => ""),
                "url_seq" => array(
                    "type" => "text",
                    "name" => "url_seq",
                    "id" => "",
                    "class" => ""),
                "url_pos" => array(
                    "type" => "text",
                    "name" => "url_pos",
                    "id" => "",
                    "class" => ""),
                "url_url" => array(
                    "type" => "text",
                    "name" => "url_url",
                    "id" => "",
                    "class" => ""),
                "url_obj->>'auth'" => array(
                    "type" => "text",
                    "name" => "auth",
                    "id" => "",
                    "class" => ""),
                "url_obj->>'name'" => array(
                    "type" => "text",
                    "name" => "name",
                    "id" => "",
                    "class" => ""),
                "url_obj->>'type'" => array(
                    "type" => "text",
                    "name" => "type",
                    "id" => "",
                    "class" => ""),
                "url_obj->>'params'" => array(
                    "type" => "text",
                    "name" => "params",
                    "id" => "",
                    "class" => ""),
                "url_obj->>'public'" => array(
                    "type" => "text",
                    "name" => "public",
                    "id" => "",
                    "class" => ""),
                "url_obj->>'command'" => array(
                    "type" => "text",
                    "name" => "command",
                    "id" => "",
                    "class" => ""),
                "multisiteid'" => array(
                    "type" => "text",
                    "name" => "multisiteid",
                    "id" => "",
                    "class" => "")),
            "data-result" => "result",
            "data-save-result" => "settings",
            "data-url" => "/admin/urlmanger/ajax",
            "settings" => array(
                "remove" => "true",
                "edit" => array(
                    "url_id" => array(
                        "type" => "text",
                        "name" => "url_id",
                        "readonly" => "true",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "url_seq" => array(
                        "type" => "text",
                        "name" => "url_seq",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "url_pos" => array(
                        "type" => "text",
                        "name" => "url_pos",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "url_url" => array(
                        "type" => "text",
                        "name" => "url_url",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "url_obj->>'auth'" => array(
                        "type" => "text",
                        "name" => "auth",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "url_obj->>'name'" => array(
                        "type" => "text",
                        "name" => "name",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "url_obj->>'type'" => array(
                        "type" => "text",
                        "name" => "type",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "url_obj->'params'->>'ajax'" => array(
                        "type" => "text",
                        "name" => "ajax",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "url_obj->'params'->>'view'" => array(
                        "type" => "text",
                        "name" => "view",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "url_obj->>'public'" => array(
                        "type" => "text",
                        "name" => "public",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "url_obj->>'command'" => array(
                        "type" => "text",
                        "name" => "command",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""),
                    "multisiteid" => array(
                        "type" => "text",
                        "name" => "multisiteid",
                        "isarray"=>"true",
                        "readonly" => "false",
                        "disabled" => "false",
                        "required" => "true",
                        "autofocus" => "false",
                        "id" => "",
                        "class" => ""))
        ));

        if (isset($array["ajax"]) && $array["ajax"] == TRUE) {
          switch ($_POST['method']) {
                case "filter":
                    $GLOBALS['awe']->Template->AjaxTable($arr);

                    break;
                case "settings":
                    $GLOBALS['awe']->Template->AjaxSettingsView($arr);

                    break;
                case "save":
                    if ($GLOBALS['awe']->Template->AjaxSettingsSave($arr)) {
                        echo json_encode(array("html" => "<div class='success'><i class='fas fa-check'></i><div class='text'>Sikeres mentés!</div></div>"));
                    }

                    break;

                default:
                    break;
            }
        } else {
           echo $GLOBALS['awe']->Template->TableFrameGenerator($arr);
        }
    }
}

?>