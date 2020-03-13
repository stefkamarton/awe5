<?php
class adm_login{
    public function __construct() {
        echo 'lol';
    }
    
    
    public function LoadComponent($array) {
        if ($array["path"]){
            
        }
        require_once 'view/login.php';
        die();
    }
    
    public function LoginPage() {
        require_once 'view/login.php';
        die();
    }
    
    

}