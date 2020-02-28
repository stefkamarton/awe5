<?php

function display($array) {
    createWindow(array("config" => $array['config'], "file-list" => listDirectoryElements($array), "directory-tree" => directoryTree($array)));
    uploadForm($array);
    echo createDirectory($array);
    echo "<div>" . $array["__counter__"]["files"] . "</div>";
    echo "<div>" . $array["__counter__"]["directories"] . "</div>";
}

function ajaxView($array) {
    return $str;
}

function createDirectory($array) {
    $args = array(
        "{comid}" => $array['config']['url_id'],
        "{waiting}" => $array['config']['waiting'],
        "{url}" => ADM_FILEMANAGER_AJAX_VIEW["url"],
        "{method}" => ADM_FILEMANAGER_AJAX_VIEW["createdirconfirm"],
        "{newdir}" => T("newdirectory")
    );
    $str = ""
            . "<form class='ajax btn' data-comid='{comid}' data-waiting='{waiting}' data-method='{method}' data-url='{url}'>"
            . "<div class='submit new-directory'>{newdir}</div>"
            . "</form>";
    return strtr($str, $args);
}

function createDirectoryConfirm($array) {
    $args = array(
        "{comid}" => $array['config']['url_id'],
        "{waiting}" => $array['config']['waiting'],
        "{url}" => ADM_FILEMANAGER_AJAX_VIEW["url"],
        "{method}" => ADM_FILEMANAGER_AJAX_VIEW["createdir"],
        "{newdir}" => T("newdirectory"),
        "{addmegadirnevet}" => T("addmegadirnevet"),
        "{create}" => T("letrehoz"),
        "{cancel}" => T("cancel")
    );
    $str = ""
            . "<form class='close-box confirm ajax' data-progressbar='#main-bar' data-comid='{comid}' data-waiting='{waiting}' data-method='{method}' data-url='{url}'>"
            . "<div class='question-in'>{addmegadirnevet}</div>"
            . "<div class='dirname'><input type='text' name='dirname' required /></div>"
            . "<div class='answers'>"
            . "<div class='answer'>"
            . "<a class='submit'>{create}</a>"
            . "</div>"
            . "<div class='answer close'>{cancel}</div></div></form>";
    return strtr($str, $args);
}

/* --Delete Section-- */

function confirmDeletionMessage($array) {
    $args = array(
        "{filename}" => $_POST['filename'],
        "{comid}" => $array['config']['url_id'],
        "{waiting}" => $array['config']['waiting'],
        "{url}" => ADM_FILEMANAGER_AJAX_VIEW["url"],
        "{method}" => ADM_FILEMANAGER_AJAX_VIEW["confirmdelete"],
        "{yes}" => T("yes"),
        "{no}" => T("no"),
        "{areyousure}" => T("areyousure")
    );
    $question = ""
            . "<div id='{filename}_confirm' class='confirm'>"
            . "<div class='question-in'>{areyousure}<b>{filename}</b>?</div>"
            . "<div class='answers'>"
            . "<div class='answer'>"
            . "<form class='ajax close-confirm' data-progressbar='#main-bar' data-comid='{comid}' data-waiting='{waiting}' data-method='{method}' data-url='{url}'>"
            . "<a class='submit'>{yes}</a>"
            . "<input type='text' value='{filename}' name='filename' style='display:none;' readOnly />"
            . "</form>"
            . "</div>"
            . "<div class='answer close-confirm'>{no}</div></div></div>";
    return strtr($question, $args);
}

/* --Delete Section-- */

/* --Upload Section-- */

function uploadForm($array) {
    /* --- HTML --- */
    $str = "<form class='ajaxonchange' method='post' enctype='multipart/form-data' data-progressbar='#main-bar' data-comid='%s' data-waiting='0' data-method='fileupload' data-url='%s'>"
            . "<input class='input-file' id='fileInput' type='file' name='file[]' multiple />"
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

/* --Upload Section-- */

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
        $args["{rDTW" . $counter . "}"] = recursiveDirectoryTreeWriter(array_merge($array, array("directory_tree" => $value)));
        $args["{rDTWF" . $counter . "}"] = recursiveDirectoryTreeWriter(array_merge($array, array("directory_tree" => $value)), $path . "/" . $key);
        $args["{key" . $counter . "}"] = $key;
        $args["{pathWithKey" . $counter . "}"] = $path . "/" . $key;
        if (!empty($value)) {
            if ($key == "/") {
                /* --- HTML --- */
                $str .= "<li class='expanded-directory'>"
                        . "<div class='expanded-btn'>"
                        . "<i class='fas fa-angle-right'></i>"
                        . "</div>"
                        . "<div class='folder-icon'>"
                        . "<i class='fas fa-folder'></i>"
                        . "</div>"
                        . "<form {formdata} class='fullajax folder-name'>{key" . $counter . "}<input style='display:none;' name='path' type='text' value='' readOnly /></form></li>"
                        . "<ul class='tree-view expanded'>{rDTW" . $counter . "}</ul>";
                /* --- HTML --- */
            } else {
                /* --- HTML --- */
                $str .= "<li class='expanded-directory'>"
                        . "<div class='expanded-btn'>"
                        . "<i class='fas fa-angle-right'></i>"
                        . "</div>"
                        . "<div class='folder-icon'>"
                        . "<i class='fas fa-folder'></i></div><form {formdata} class='fullajax folder-name'>{key" . $counter . "}<input style='display:none;' name='path' type='text' value='{pathWithKey" . $counter . "}' readOnly /></form></li>";
                $str .= "<ul class='tree-view expanded'>{rDTWF" . $counter . "}</ul>";
                /* --- HTML --- */
            }
        } else {
            /* --- HTML --- */
            $str .= "<li class='alone'>"
                    . "<div class='folder-icon'>"
                    . "<i class='fas fa-folder'></i>"
                    . "</div>"
                    . "<form {formdata} class='fullajax folder-name'>{key" . $counter . "}<input style='display:none;' name='path' type='text' value='{pathWithKey" . $counter . "}' readOnly />"
                    . "</form>"
                    . "</li>";
            /* --- HTML --- */
        }
        $counter++;
    }
    return strtr($str, $args);
}

function directoryTree($array) {
    return Format("<ul class='tree-view'>%s</ul>", recursiveDirectoryTreeWriter($array));
}

function listDirectoryElements($array) {
    $str = "";

    $args = array(
        "{comid}" => $array['config']['url_id'],
        "{waiting}" => $array['config']['waiting'],
        "{url}" => ADM_FILEMANAGER_AJAX_VIEW["url"]
    );
    $formTag = strtr("data-comid='{comid}' data-waiting='{waiting}' data-url='{url}'", $args);
    $args = array();
    $args["{formtag}"] = $formTag;
    if (!empty($array['directory_elements'])) {
        if (substr($array['path'], -1) == "/") {
            $array['path'] = substr($array['path'], 0, -1);
        }
        $path = str_replace(FILEMANAGER_ROOT_DIR, "", $array['path']);
        $args["{fajlnev}"] = T("fajlnev");
        $args["{tipus}"] = T("tipus");
        $args["{meret}"] = T("meret");
        $args["{modositasdatuma}"] = T("modositasdatuma");
        $args["{muveletek}"] = T("muveletek");

        /* --- HTML --- */
        $str .= "<div class='table'>";
        $str .= "<form class='thead'>";
        $str .= "<div class='tr'>"
                . "<div class='th'>{fajlnev}<div class='order'><div class='order-in '><label for='filemanager_name_ASC'><i class='fas fa-sort-alpha-down'></i></label><input style='display:none;' id='filemanager_name_ASC' name='orderby' class='asc' type='radio' value='filemanager_name:ASC'></div><div class='order-in '><label for='filemanager_name_DESC'><i class='fas fa-sort-alpha-up'></i></label><input style='display:none;' id='filemanager_name_DESC' name='orderby' class='desc' type='radio' value='filemanager_name:DESC'></div></div></div>"
                . "<div class='th'>{tipus}</div>"
                . "<div class='th'>{meret}</div>"
                . "<div class='th'>{modositasdatuma}</div>"
                . "<div class='th'>{muveletek}</div>"
                . "</div>";
        $str .= "</form></div>";
        $str .= "<div class='table'><div class='tbody'>";
        /* --- HTML --- */
        $counter = 0;
        foreach ($array['directory_elements'] as $key => $value) {

            if ($value->fileType["name"] != "webp") {
                if ($key == "..") {
                    //$path = "";
                    $s = explode("/", $path);
                    unset($s[count($s) - 1]);
                    $s = implode("/", $s);
                    $spath = $s;
                } else {
                    $spath = $path . "/" . $key;
                }
                if ($value->fileType["name"] == "folder")
                    $args["{folder" . $counter . "}"] = "folder-name fullajax";
                else {
                    $args["{folder" . $counter . "}"] = "";
                }
                $thumbpath = str_replace(FILEMANAGER_ROOT_DIR, "", $array['path']);
                $relpath = "/tmp" . $thumbpath . "/";
                $thumbpath = explode("/", $thumbpath);
                $thumbname = "";
                foreach ($thumbpath as $tp) {
                    $thumbname .= $tp . "_";
                }
                if (strpos($value->fileType["icon"], "image") !== false) {
                    $args["{thumbs" . $counter . "}"] = "<img src='" . $GLOBALS['awe']->Domain . "/tmp/.thumbs/" . $thumbname . $value->fileName . "_thumb.jpg" . "'>";
                } else {
                    $args["{thumbs" . $counter . "}"] = "<i class='" . $value->fileType['icon'] . "'></i>";
                }
                $args["{pathkey" . $counter . "}"] = $spath;
                $args["{method" . $counter . "}"] = "data-method='" . ADM_FILEMANAGER_AJAX_VIEW["method2"] . "'";
                /* --- HTML --- */
                $str .= "<div class='tr'>"
                        . "<form class='td {folder" . $counter . "}' data-progressbar='#main-bar' {formtag} {method" . $counter . "}><input style='display:none;' name='path' type='text' value='{pathkey" . $counter . "}' readOnly />"
                        . "<div class='file-icon'>{thumbs" . $counter . "}</div>";
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
                $args["{filename" . $counter . "}"] = $value->fileName;
                $args["{filetype" . $counter . "}"] = $value->fileType["name"];
                $args["{filesize" . $counter . "}"] = $value->fileSize;
                $args["{filemodify" . $counter . "}"] = $value->fileModificationTime;
                $args["{methodDel" . $counter . "}"] = "data-method='" . ADM_FILEMANAGER_AJAX_VIEW["delete"] . "'";

                /* --- HTML --- */
                $str .= "</form>"
                        . "<div class='td file-name'>{filetype" . $counter . "}</div>"
                        . "<div class='td file-name'>{filesize" . $counter . "}</div>"
                        . "<div class='td file-name'>{filemodify" . $counter . "}</div>"
                        . "<div class='td file-name'>"
                        . "<form class='td fullajax' data-progressbar='#main-bar' {formtag} {methodDel" . $counter . "}>"
                        . "<input style='display:none' type='text' name='filename' value='{filename" . $counter . "}' readOnly/><i class='far fa-trash-alt'></i>"
                        . "</form>"
                        . "</div>"
                        . "</div>";
                /* --- HTML --- */
            }
            $counter++;
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
    return strtr($str, $args);
}

?>