<?php

function display($array) {
    createWindow(array("config" => $array['config'], "file-list" => listDirectoryElements($array), "directory-tree" => directoryTree($array)));
    uploadForm($array);
    echo "<div>" . $array["__counter__"]["files"] . "</div>";
    echo "<div>" . $array["__counter__"]["directories"] . "</div>";
}

function ajaxView($array) {
    return $str;
}

function uploadForm($array) {
    /* --- HTML --- */
    $str = "<form id='fileupload' method='post' enctype='multipart/form-data' data-progressbar='#main-bar' data-comid='%s' data-waiting='0' data-method='fileupload' data-url='%s'>"
            . "<input type='text' name='text' value='22'/>"
            . "<input class='input-file' id='fileInput' type='file' name='file'>"
            . "</form>"
            . "<div id='upload'>"
            . "</div>"
            . "<div class='progress'>"
            . "<div class='bar'></div >"
            . "<div class='percent'>0%</div>"
            . "</div>";
    /* --- HTML --- */
    echo Format($str, $array['config']['url_id'], ADM_FILEMANAGER_AJAX_VIEW["url"]);
}

function createWindow($array) {
    if (!empty($array)) {
        /* --- HTML --- */
        $str = "<div class='filemanager'>"
                . "<div class='directory-tree-view'>"
                . "<div class='directory-tree-view-in'>"
                . "%s"
                . "</div>"
                . "</div>"
                . "<div class='file-list'>"
                . "<div class='file-list-in' id='%s_%s'>"
                . "%s"
                . "</div>"
                . "</div>"
                . "</div>";
        /* --- HTML --- */
        echo Format($str, $array['directory-tree'], $array['config']['url_id'], substr(ADM_FILEMANAGER_AJAX_VIEW['result'], 1), $array['file-list']);
    } else {
        return FALSE;
    }
}

function recursiveDirectoryTreeWriter($array, $path = "") {
    $args = array(
        "{comid}" => $array['config']['url_id'],
        "{waiting}" => $array['config']['waiting'],
        "{method}" => ADM_FILEMANAGER_AJAX_VIEW["method2"],
        "{url}" => ADM_FILEMANAGER_AJAX_VIEW["url"]
    );
    $formTag = strtr("data-comid='{comid}' data-waiting='{waiting}' data-method='{method}' data-url='{url}'", $args);
    $str = "";
    $args = array("{formdata}" => $formTag);
    $counter = 0;
    foreach ($array['directory_tree'] as $key => $value) {
        if (!empty($value)) {
            if ($key == "/") {
                $args["{rDTW." . $counter . "}"] = recursiveDirectoryTreeWriter(array_merge($array, array("directory_tree" => $value)));
                /* --- HTML --- */
                $str .= "<li class='expanded-directory'>"
                        . "<div class='expanded-btn'>"
                        . "<i class='fas fa-angle-right'></i>"
                        . "</div>"
                        . "<div class='folder-icon'>"
                        . "<i class='fas fa-folder'></i>"
                        . "</div>"
                        . "<form {formdata} class='folder-name'>{key.$counter}<input style='display:none;' name='path' type='text' value='' readOnly /></form></li>"
                        . "<ul class='tree-view expanded'>{rDTW}.$counter</ul>";
                /* --- HTML --- */
            } else {
                array_push($args, $formTag, $key, $path . "/" . $key, recursiveDirectoryTreeWriter(array_merge($array, array("directory_tree" => $value)), $path . "/" . $key));

                /* --- HTML --- */
                $str .= "<li class='expanded-directory'>"
                        . "<div class='expanded-btn'>"
                        . "<i class='fas fa-angle-right'></i>"
                        . "</div>"
                        . "<div class='folder-icon'>"
                        . "<i class='fas fa-folder'></i></div><form %s class='folder-name'>%s<input style='display:none;' name='path' type='text' value='%s' readOnly /></form></li>";
                $str .= "<ul class='tree-view expanded'>%s</ul>";
                /* --- HTML --- */
            }
        } else {
            array_push($args, ...[$formTag, $key, $path . "/" . $key]);

            /* --- HTML --- */
            $str .= "<li class='alone'>"
                    . "<div class='folder-icon'>"
                    . "<i class='fas fa-folder'></i>"
                    . "</div>"
                    . "<form %s class='folder-name'>%s<input style='display:none;' name='path' type='text' value='%s' readOnly />"
                    . "</form>"
                    . "</li>";
            /* --- HTML --- */
        }
        $counter++;
    }
    return Format($str, ...$args);
}

function directoryTree($array) {
    return Format("<ul class='tree-view'>%s</ul>", recursiveDirectoryTreeWriter($array));
}

function listDirectoryElements($array) {
    $str = "";
    $args = array();
    if (!empty($array['directory_elements'])) {
        if (substr($array['path'], -1) == "/") {
            $array['path'] = substr($array['path'], 0, -1);
        }
        $path = str_replace(FILEMANAGER_ROOT_DIR, "", $array['path']);

        array_push($args, ...["fajlnev", "tipus", "meret", "modositasdatuma", "muveletek"]);
        /* --- HTML --- */
        $str .= "<div class='table'>";
        $str .= "<form class='thead'>";
        $str .= "<div class='tr'>"
                . "<div class='th'>%t<div class='order'><div class='order-in '><label for='filemanager_name_ASC'><i class='fas fa-sort-alpha-down'></i></label><input style='display:none;' id='filemanager_name_ASC' name='orderby' class='asc' type='radio' value='filemanager_name:ASC'></div><div class='order-in '><label for='filemanager_name_DESC'><i class='fas fa-sort-alpha-up'></i></label><input style='display:none;' id='filemanager_name_DESC' name='orderby' class='desc' type='radio' value='filemanager_name:DESC'></div></div></div>"
                . "<div class='th'>%t</div>"
                . "<div class='th'>%t</div>"
                . "<div class='th'>%t</div>"
                . "<div class='th'>%t</div>"
                . "</div>";
        $str .= "</form></div>";
        $str .= "<div class='table'><div class='tbody'>";
        /* --- HTML --- */

        foreach ($array['directory_elements'] as $key => $value) {
            if ($value->fileType["name"] != "webp") {
                if ($key == "..") {
                    $path = "";
                    $key = "";
                } else {
                    $key = "/" . $key;
                }
                if ($value->fileType["name"] == "folder")
                    $folder = "folder-name";
                else {
                    $folder = "";
                }
                $thumbpath = str_replace(FILEMANAGER_ROOT_DIR, "", $array['path']);
                $relpath = "/tmp" . $thumbpath . "/";
                $thumbpath = explode("/", $thumbpath);
                $thumbname = "";
                foreach ($thumbpath as $tp) {
                    $thumbname .= $tp . "_";
                }
                if (strpos($value->fileType["icon"], "image") !== false) {

                    $thumb = "<img src='" . $GLOBALS['awe']->Domain . "/tmp/.thumbs/" . $thumbname . $value->fileName . "_thumb.jpg" . "'>";
                } else {
                    $thumb = "<i class='" . $value->fileType['icon'] . "'></i>";
                }
                /* --- HTML --- */
                $str .= "<div class='tr'>"
                        . "<form class='td $folder' data-progressbar='#main-bar' data-comid='" . $array['config']['url_id'] . "' data-waiting='" . ADM_FILEMANAGER_AJAX_VIEW['waiting'] . "' data-method='" . ADM_FILEMANAGER_AJAX_VIEW["method"] . "' data-url='" . ADM_FILEMANAGER_AJAX_VIEW["url"] . "'><input style='display:none;' name='path' type='text' value='" . $path . $key . "' readOnly />"
                        . "<div class='file-icon'>$thumb</div>";
                /* --- HTML --- */
                if (empty($folder)) {
                    /* --- HTML --- */
                    $str .= "<a href='" . $GLOBALS['awe']->Domain . $relpath . $value->fileName . "' class='file-name'>" . $value->fileName . "</a>";
                    /* --- HTML --- */
                } else {
                    /* --- HTML --- */
                    $str .= "<div class='file-name'>" . $value->fileName . "</div>";
                    /* --- HTML --- */
                }
                /* --- HTML --- */
                $str .= "</form>"
                        . "<div class='td file-name'>" . $value->fileType["name"] . "</div>"
                        . "<div class='td file-name'>" . $value->fileSize . "</div>"
                        . "<div class='td file-name'>" . $value->fileModificationTime . "</div>"
                        . "<div class='td file-name'>"
                        . "<form class='td ajax' id='ajaxclick' data-progressbar='#main-bar' data-comid='" . $array['config']['url_id'] . "' data-waiting='" . ADM_FILEMANAGER_AJAX_VIEW['waiting'] . "' data-method='" . ADM_FILEMANAGER_AJAX_VIEW["delete"] . "' data-url='" . ADM_FILEMANAGER_AJAX_VIEW["url"] . "'>"
                        . "<input style='display:none' type='text' name='filename' value='" . $value->fileName . "' readOnly/>xx"
                        . "</form>"
                        . "</div>"
                        . "</div>";
                /* --- HTML --- */
            }
        }/* --- HTML --- */
        $str .= "</div>";
        $str .= "</div>";
        /* --- HTML --- */
    } else {
        /* --- HTML --- */
        $str .= "<div class='table'>";
        $str .= "<div class='tbody'>";
        $str .= "<div class='tr'><div class='td'>Nincs mit megjeleníteni!</div></div>";
        $str .= "</div>";
        $str .= "</div>";
        /* --- HTML --- */
    }
    return $str;
}

?>