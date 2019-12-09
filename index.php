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
/**
 * @name $awe
 * @global class $GLOBALS
 * @var string $GLOBALS AWE
 * @see Example::getDemoData()
 */
$GLOBALS = new AWE(array());
/* Core osztályok hívása */
$GLOBALS['awe']->coreInit(array());
//$awe->Translator->Viewer(array());
//var_dump($GLOBALS['awe']->LoadComponents(array("name"=>"adm_url", "params"=>array("type"=>"edit"))));

/* Template betöltő hívása */
$GLOBALS['awe']->Template->Load(array());


?>