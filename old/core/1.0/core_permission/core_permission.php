<?php

class core_permission {

    /**
     * Jogok
     * @var string
     * @global $GLOBALS['awe']->Permissions->Rights
     */
    public $Rights;

    /**
     * Korlátlan jog
     * @var string
     * @global $GLOBALS['awe']->Permissions->Nolimit
     */
    public $Nolimit;

    public function __construct($array) {
        $this->Rights = array();
        $this->Nolimit = false;
        $this->GetPermissions(array("user" => "admin"));
        //var_dump($this->Rights);
    }

    /**
     * Megnézi hogy az adott usernek van-e joga
     * @param array $array  ["permissions"]=>""
     * @global $GLOBALS["awe"]->Permissions->Check(array("permissions" => array()))
     * @return bool      
     */
    public function Check($array = array("permissions" => array())) {
        if (isset($array['permissions'])) {
            if (is_array($array['permissions'])) {
                foreach ($array['permissions'] as $perm) {
                    if (in_array($perm, $this->Rights)) {
                        return true;
                    }
                }
                return FALSE;
            } else {
                if (in_array($array['permissions'], $this->Rights)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Hozzáad egy jogot a többihez
     * @param array $array  ["permissions"]=>""
     * @global $GLOBALS["awe"]->Permissions->AddPermission(array("permissions" => array()))
     * @return void      
     */
    private function AddPermission($array = array("permissions" => array())) {
        if (isset($array['permissions'])) {
            if (in_array("*", $array['permissions']) && $this->Nolimit == FALSE) {
                $this->Rights = $this->GetAllPermissions(array());
                $this->Nolimit = true;
            } elseif ($this->Nolimit == FALSE) {
                foreach ($array['permissions'] as $perms) {
                    if (!in_array($perms, $this->Rights)) {
                        array_push($this->Rights, $perms);
                    }
                }
            }
        }
    }

    /**
     * Lekérdezi az összes létező jogot
     * @param array $array  Jelenleg semmilyen paramétert nem kap
     * @global $GLOBALS["awe"]->Permissions->GetAllPermissions(array())
     * @return void      
     */
    private function GetAllPermissions($array = array()) {
        $result = $GLOBALS['awe']->DB->fetch(array("sql" => "SELECT * FROM defaults WHERE defaults_id=:defaults_id", "attr" => array(":defaults_id" => "permissions")), PDO::FETCH_ASSOC);
        $result = (array) json_decode($result['defaults_obj']);
        return $result['permissions'];
    }

    /**
     * Lekérdezi a user jogait
     * @param array $array  ["user"]=>""
     * @global $GLOBALS["awe"]->Permissions->GetPermissions(array("user"=>""))
     * @return void      
     */
    private function GetPermissions($array = array("user" => "")) {
        if (isset($array['user']) && $array['user'] != NULL) {
            $result = $GLOBALS['awe']->DB->fetch(array("sql" => "SELECT * FROM core_user WHERE username=:user", "attr" => array(":user" => $array['user'])), PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $result = (array) json_decode($result['user_obj']);
                $this->AddPermission($result);
                $this->GetGroupsPermission($result);
            }
        }
    }

    /**
     * Lekérdezi a csoport jogait
     * @param array $array  ["permission_groups"]=>array()
     * @global $GLOBALS["awe"]->Permissions->GetGroupsPermission(array("permission_groups"=>""))
     * @return void      
     */
    private function GetGroupsPermission($array = array("permission_groups" => "")) {
        if (isset($array['permission_groups'])) {
            $results = $GLOBALS['awe']->DB->fetchAll(array("sql" => "SELECT * FROM core_groups WHERE groupname IN (:group)", "attr" => array(":group" => implode(',', $array['permission_groups']))), PDO::FETCH_ASSOC);
            if (count($results) > 0) {
                foreach ($results as $result) {
                    $result = (array) json_decode($result['group_obj']);
                    $this->AddPermission($result);
                }
            }
        }
    }

}
