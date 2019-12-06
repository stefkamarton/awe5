<?php

//file_put_contents("/tmp/remus", $arr, FILE_APPEND);
//itt át tudjuk venni a változókat a fő class.php-s fileból.
// pl $arr[valami]
// name / title - a feljövő tab címe
$name = "values";
//tabdata - a viszaadott html be lesz töltve a tab-ba.
$tabdata = ' <form class="asd" method="post" data-waiting="0" data-method="save" data-result="" data-url="/admin/urlmanger/ajax" onsubmit="return false" >
            ';

//$tabdata = '<div id="rd1">';
//$values = $arr["edit"];
$values = $urlsettings;


foreach ($values as $key => $value) {
    if (array_key_exists($key, $arr['edit'])) {
        switch ($arr['edit'][$key]["type"]) {
            case "string":
                $tabdata.=print_r($arr['edit'][$key],true);
                $tabdata.= '<label for="' . $key . '">' . T("Name (4 to 8 characters):") . '</label>';

                $tabdata.= '<input type="text" name="' . $key . '" value="' . $value . '" ';
                if (!empty($arr['edit'][$key]["editable"]) ){
                    $tabdata.= $arr['edit'][$key]["editable"];
                    $tabdata.= 'readonly';
                }

                $tabdata.= '>';

                break;

            default:
                break;
        }
    }
    //$tabdata.= "<div>" . $key . "->>" . $value . "</div>";
}


$tabdata.='<div id=\'sidepanel-save\' >
                <i class="fas fa-save" ></i>
           </div>'
        //
        //. '</div>'
        . '</form>';

return array("name" => $name, "tabdata" => $tabdata);





