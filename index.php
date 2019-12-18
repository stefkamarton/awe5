<?php

function HTTP_AUTH() {
    $AUTH_USER = 'uzletembermagazin';
    $AUTH_PASS = '123456';
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
    $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW'] != $AUTH_PASS
            );
    if ($is_not_authenticated) {
        header('HTTP/1.1 401 Authorization Required');
        header('WWW-Authenticate: Basic realm="Access denied"');
        die("Nincs hozzáférés");
        exit;
    }
}

HTTP_AUTH();

session_start();

require_once 'core/autoload.php';
global $awe;
/**
 * @name $awe
 * @global class $GLOBALS
 * @var string $GLOBALS AWE
 * @see Example::getDemoData()
 */
$awe = new AWE(array());
/* Core osztályok hívása */
$awe->coreInit(array());
//$awe->Translator->Viewer(array());
//var_dump($GLOBALS['awe']->LoadComponents(array("name"=>"adm_url", "params"=>array("type"=>"edit"))));



$A=array(
    "table"=>"core_url",
   /* "limit"=>"3",
    "offset"=>"5",*/
    /*"orderby"=>array(
        "url_id" => "ASC", 
        "url_pos" => "DESC"),
    "groupby" => array(
        "url_pos"), 
    "having" => array(
        "url_pos = 'content'"),*/
    "distinct" => FALSE,
    "projection" => array(),
    "where" => array(
        "url_url" => "admin",
        "AND" => array(
            "."=>">",
            "url_seq" => "2")),
    /*"joins" => array(
        0 => array(
            "type" => "INNER",
            "table" => "core_url_template",
            "ON" => "core_url.url_url = core_url_template.url_url")
        )*/
    );
/* Template betöltő hívása */
$awe->Template->Load(array());
var_dump($awe->DB->Select($A));

/*
$A = array(
    "AND NOT" => array(
        "hello" => "asd",
        "OR" => array(
            "."=>"LIKE",
            "or1" => "%or 1%",
            "or2" => "or2"
        ),
        "asd" => "asdda"
    )
);
var_dump(tree($A));*/
?>