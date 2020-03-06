<?php

class adm_filemanager_default_template extends adm_filemanager_template_abstract {

    public function __construct($obj) {
        parent::__construct($obj);
    }

    public function directoryElementsWithFrame(): string {
        $ret = "<div class='filemanager " . get_class() . "'>";
        $ret .= "<div class='filemanager-in'>";
        $ret .= $this->directoryElements();
        $ret .= "</div>";
        $ret .= "</div>";
        return $ret;
    }

    public function directoryElements(): string {
        $ret = "<div class='boxes'>";
        foreach ($this->Obj->Directory->Items as $item) {
            if (!$item->isDot()) {
                $ret .= "<div class='box'>";
                $ret .= "<div class='box-in'>";
                $ret .= "<div class='file-name'>" . $item->getFilename() . "</div>";
                $ret .= "<div class='file-last-modify'>"
                . "<div class='key'>" . "asda" . "</div>"
                . "<div class='value'>" . date("Y-m-d H:i:s", $item->getCTime()) . "</div></div>";

                $ret .= "</div>";
                $ret .= "</div>";
            }
        }
        $ret .= "</div>";
        return $ret;
    }

}
