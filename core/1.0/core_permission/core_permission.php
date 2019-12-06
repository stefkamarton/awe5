<?php

class core_permission {

    public $Rights;
    public $Nolimit;

    public function __construct($array) {
        $this->Rights = array();
        $this->Nolimit = false;
        $this->GetPermissions(array("user" => "admin"));
        //var_dump($this->Rights);
    }

    public function Check($array) {
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

    private function AddPermission($array) {
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

    private function GetAllPermissions($array) {
        $result = $GLOBALS['awe']->DB->fetch(array("sql" => "SELECT * FROM defaults WHERE defaults_id=:defaults_id", "attr" => array(":defaults_id" => "permissions")), PDO::FETCH_ASSOC);
        $result = (array) json_decode($result['defaults_obj']);
        return $result['permissions'];
    }

    /* Visszatér a permission-ökkel */

    private function GetPermissions($array) {
        if (isset($array['user']) && $array['user'] != NULL) {
            $result = $GLOBALS['awe']->DB->fetch(array("sql" => "SELECT * FROM core_user WHERE username=:user", "attr" => array(":user" => $array['user'])), PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $result = (array) json_decode($result['user_obj']);
                $this->AddPermission($result);
                $this->GetGroupsPermission($result);
            }
        }
    }

    private function GetGroupsPermission($array) {
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
