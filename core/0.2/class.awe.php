<?php

namespace Core;

use Core\database;

class AWE {

    private static \AWE $Instance;

    /** Adatbázis osztály változó */
    public $DB;

    public static function getInstance($siteConfiguration) {

        return !isset(self::$Instance) ? self::$Instance = new \Core\AWE($siteConfiguration) : self::$Instance;
    }

    private function __construct($siteConfiguration) {
        new database();
    }

}

\Core\AWE::getInstance("");



