<?php
//file_put_contents("/tmp/remus", $arr, FILE_APPEND);
//itt át tudjuk venni a változókat a fő class.php-s fileból.
// pl $arr[valami]
// name / title - a feljövő tab címe
$name = "values";
//tabdata - a viszaadott html be lesz töltve a tab-ba.
$tabdata = '<div id="rd1">';
//$values = $arr["edit"];
$values = $urlsettings;


foreach ($values as $key => $value) {
    if (array_key_exists($key, $arr['edit'])) {
        switch ($arr['edit'][$key]["type"]) {
            case "string":
                $tabdata.= '<label for="'.$key.' ">'.T("Name (4 to 8 characters):").'</label>';

                $tabdata.= '<input type="text" id="'.$key.'" value="' .$value. '" required minlength="4" maxlength="8" size="10">';
                
                break;

            default:
                break;
        }
    }
    //$tabdata.= "<div>" . $key . "->>" . $value . "</div>";
}
$tabdata.='<a href="#" onclick="$(\'#draggable\').remove();">
                <i class="fas fa-save"></i></a>'
        . '</div>';

return array("name" => $name, "tabdata" => $tabdata);





