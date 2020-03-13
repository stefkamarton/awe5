<?php

class core_user {

    private $Connection;
    private $Config;

    public function __construct($array) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function isLoggedIn($array = array()) {



        if (isset($_SESSION['user_id'])) {
            // Grab user data from the database using the user_id
            // Let them access the "logged in only" pages
        } else {
            // Redirect them to the login page
            //header("Location: /admin/login");
            return FALSE;
        }
    }

    public function LoginPage($array = array()) {
        if ($this->stringStartsWith(array("string" => $this->Url, "substring" => "/admin/login"))) {
            
        } else {
            header("Location: /admin/login");
            die("header");
        }
    }

}

?>