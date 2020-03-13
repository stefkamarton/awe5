<?php

use Core\AWE;

define("__DOMAIN__", $_SERVER['HTTP_HOST']);
define("__SITES__", "./sites/sites.json");
define("__COREPATH__", "./core");
if ($sites = file_get_contents(__SITES__)) {
    $sites = json_decode($sites, true) or die("Nem megfelelő json található: " . __SITES__);

    define("__ALIAS__", $sites["DomainToAlias"][__DOMAIN__]);

    $siteConfiguration = $sites["AliasConfigs"][__ALIAS__];

    require_once __COREPATH__ . "/" . $siteConfiguration["coreVersion"] . "/class.awe.php";

    define("AWE", AWE::getInstance($siteConfiguration));
} else {
    die("Nem lehet olvasni ezt a file-t: " . __SITES__);
}
?>