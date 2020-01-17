<?php


function display($array) {
    createWindow(array("file-list" => listDirectoryElements($array), "directory-tree"=>directoryTree($array)));
    echo "asd";
}
function ajaxView($array){
    return $str;
}
function createWindow($array) {
    if (!empty($array)) {
        echo "<div class='filemanager'>";
        /* Directory Tree */
        echo "<div class='directory-tree-view'>";
        echo "<div class='directory-tree-view-in'>";
        echo $array['directory-tree'];
        echo "</div>";
        echo "</div>";

        /* File-list */
        echo "<div class='file-list'>";
        echo "<div class='file-list-in' id='" . substr(ADM_FILEMANAGER_AJAX_VIEW['result'], 1) . "'>";
        echo $array['file-list'];
        echo "</div>";
        echo "</div>";
        echo "</div>";
    } else {
        return FALSE;
    }
}

function recursiveDirectoryTreeWriter($array, $path = "") {
    $str = "";
    echo "<br>";
    foreach ($array['directory_tree'] as $key => $value) {
        if (!empty($value)) {
            $str .= "<li class='expanded-directory'><div class='expanded-btn'><i class='fas fa-angle-right'></i></div><div class='folder-icon'><i class='fas fa-folder'></i></div><form data-comid='".$array['config']['url_id']."' data-waiting='" . $array['config']['waiting'] . "' data-method='" . ADM_FILEMANAGER_AJAX_VIEW["method"] . "' data-result='" . ADM_FILEMANAGER_AJAX_VIEW["result"] . "' data-url='" . ADM_FILEMANAGER_AJAX_VIEW["url"] . "' class='folder-name'>" . $key . "<input style='display:none;' name='path' type='text' value='" . $path . "/" . $key . "' readOnly /></form></li>";
            $str .= "<ul class='tree-view expanded'>" . recursiveDirectoryTreeWriter(array_merge($array,array("directory_tree"=>$value)), $path . "/" . $key) . "</ul>";
        } else {
            $str .= "<li class='alone'><div class='folder-icon'><i class='fas fa-folder'></i></div><form class='folder-name' data-comid='".$array['config']['url_id']."' data-waiting='" . ADM_FILEMANAGER_AJAX_VIEW['waiting'] . "' data-method='" . ADM_FILEMANAGER_AJAX_VIEW["method"] . "' data-result='" . ADM_FILEMANAGER_AJAX_VIEW["result"] . "' data-url='" . ADM_FILEMANAGER_AJAX_VIEW["url"] . "'>" . $key . "<input style='display:none;' name='path' type='text' value='" . $path . "/" . $key . "' readOnly /></form></li>";
        }
    }
    return $str;
}

function directoryTree($array) {
    return "<ul class='tree-view'>" . recursiveDirectoryTreeWriter($array) . "</ul>";
}

function listDirectoryElements($array) {
    $str = "";
    if (!empty($array['directory_elements'])) {
        $path = str_replace(FILEMANAGER_ROOT_DIR, "", $array['path']);
        $str .= "<div class='table'>";
        $str .= "<form class='thead'>";
        $str .= "<div class='tr'>"
                . "<div class='th'>fajlnev<div class='order'><div class='order-in '><label for='filemanager_name_ASC'><i class='fas fa-sort-alpha-down'></i></label><input style='display:none;' id='filemanager_name_ASC' name='orderby' class='asc' type='radio' value='filemanager_name:ASC'></div><div class='order-in '><label for='filemanager_name_DESC'><i class='fas fa-sort-alpha-up'></i></label><input style='display:none;' id='filemanager_name_DESC' name='orderby' class='desc' type='radio' value='filemanager_name:DESC'></div></div></div>"
                . "<div class='th'>tipus</div>"
                . "<div class='th'>meret</div>"
                . "<div class='th'>modositas</div>"
                . "</div>";
        $str .= "</form>";
        $str .= "<div class='tbody'>";
        foreach ($array['directory_elements'] as $key => $value) {
            if ($key == "..") {
                $sPath = explode("/", $path);
                $i = 0;
                $path = "";
                foreach ($sPath as $v2) {
                    if ($i < (count($sPath) - 1)) {
                        if (!empty($v2)) {
                            $path .= "/" . $v2;
                        }
                    }
                    $i++;
                }
                $key = "";
            } else {
                $key = "/" . $key;
            }
            if ($value->fileType["name"] == "folder")
                $folder = "folder-name";
            else
                $folder = "";
            $str .= "<div class='tr'>"
                    . "<form class='td $folder' data-comid='".$array['config']['url_id']."' data-waiting='" . ADM_FILEMANAGER_AJAX_VIEW['waiting'] . "' data-method='" . ADM_FILEMANAGER_AJAX_VIEW["method"] . "' data-result='" . ADM_FILEMANAGER_AJAX_VIEW["result"] . "' data-url='" . ADM_FILEMANAGER_AJAX_VIEW["url"] . "'><input style='display:none;' name='path' type='text' value='" . $path . $key . "' readOnly /><div class='file-icon'><i class='" . $value->fileType['icon'] . "'></i></div><div class='file-name'>" . $value->fileName . "</div></form>"
                    . "<div class='td file-name'>" . $value->fileType["name"] . "</div>"
                    . "<div class='td file-name'>" . $value->fileSize . "</div>"
                    . "<div class='td file-name'>" . $value->fileModificationTime . "</div>"
                    . "</div>";
        }
        $str .= "</div>";
        $str .= "</div>";
    } else {
        $str .= "<div class='table'>";
        $str .= "<div class='tbody'>";
        $str .= "<div class='tr'><div class='td'>Nincs mit megjeleníteni!</div></div>";
        $str .= "</div>";
        $str .= "</div>";
    }
    return $str;
}

?>