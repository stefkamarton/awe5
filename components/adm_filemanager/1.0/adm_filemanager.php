<?php

define("BYTES", array(
    0 => array(
        "UNIT" => "PB",
        "VALUE" => pow(1024, 5)),
    1 => array(
        "UNIT" => "TB",
        "VALUE" => pow(1024, 4)),
    2 => array(
        "UNIT" => "GB",
        "VALUE" => pow(1024, 3)),
    3 => array(
        "UNIT" => "MB",
        "VALUE" => pow(1024, 3)),
    4 => array(
        "UNIT" => "KB",
        "VALUE" => pow(1024, 1)),
    5 => array(
        "UNIT" => "B",
        "VALUE" => 1)
));
define("MIME_TYPES", array(
    "application/postscript" => "ps",
    "audio/x-aiff" => array("name" => "aiff", "icon" => "far fa-file-audio"),
    "text/plain" => array("name" => "text", "icon" => "far fa-file-alt"),
    "video/x-ms-asf" => array("name" => "asx", "icon" => "far fa-file-video"),
    "audio/basic" => array("name" => "snd", "icon" => "far fa-file-audio"),
    "video/x-msvideo" => array("name" => "avi", "icon" => "far fa-file-video"),
    "application/x-bcpio" => "bcpio",
    "application/octet-stream" => array("name" => "so", "icon" => "far fa-file-alt"),
    "image/bmp" => array("name" => "bmp", "icon" => "far fa-file-image"),
    "application/x-rar" => array("name" => "rar", "icon" => "far fa-file-archive"),
    "application/x-bzip2" => array("name" => "bz2", "icon" => "far fa-file-archive"),
    "application/x-netcdf" => "nc",
    "application/x-kchart" => "chrt",
    "application/x-cpio" => "cpio",
    "application/mac-compactpro" => "cpt",
    "application/x-csh" => "csh",
    "text/css" => array("name" => "css", "icon" => "far fa-file-code"),
    "application/x-director" => "dxr",
    "image/vnd.djvu" => array("name" => "djvu", "icon" => "far fa-file-image"),
    "application/x-dvi" => "dvi",
    "image/vnd.dwg" => array("name" => "dwg", "icon" => "far fa-file-image"),
    "application/epub" => "epub",
    "application/epub+zip" => "epub",
    "text/x-setext" => array("name" => "ext", "icon" => "far fa-file-alt"),
    "application/andrew-inset" => "ez",
    "video/x-flv" => array("name" => "flv", "icon" => "far fa-file-video"),
    "image/gif" => array("name" => "gif", "icon" => "far fa-file-image"),
    "application/x-gtar" => array("name" => "gtar", "icon" => "far fa-file-archive"),
    "application/x-gzip" => array("name" => "tgz", "icon" => "far fa-file-archive"),
    "application/x-hdf" => "hdf",
    "application/mac-binhex40" => "hqx",
    "text/html" => array("name" => "html", "icon" => "far fa-file-code"),
    "text/htm" => array("name" => "htm", "icon" => "far fa-file-code"),
    "x-conference/x-cooltalk" => "ice",
    "image/ief" => array("name" => "ief", "icon" => "far fa-file-image"),
    "model/iges" => "igs",
    "text/vnd.sun.j2me.app-descriptor" => "jad",
    "application/x-java-archive" => "jar",
    "application/x-java-jnlp-file" => "jnlp",
    "image/jpeg" => array("name" => "jpg", "icon" => "far fa-file-image"),
    "application/x-javascript" => "js",
    "audio/midi" => array("name" => "midi", "icon" => "far fa-file-audio"),
    "application/x-killustrator" => "kil",
    "application/x-kpresenter" => "kpt",
    "application/x-kspread" => "ksp",
    "application/x-kword" => "kwt",
    "application/vnd.google-earth.kml+xml" => "kml",
    "application/vnd.google-earth.kmz" => "kmz",
    "application/x-latex" => "latex",
    "audio/x-mpegurl" => array("name" => "m3u", "icon" => "far fa-file-audio"),
    "application/x-troff-man" => "man",
    "application/x-troff-me" => "me",
    "model/mesh" => "silo",
    "application/vnd.mif" => "mif",
    "video/quicktime" => array("name" => "mov", "icon" => "far fa-file-video"),
    "video/x-sgi-movie" => array("name" => "movie", "icon" => "far fa-file-video"),
    "audio/mpeg" => array("name" => "mp3", "icon" => "far fa-file-audio"),
    "video/mp4" => array("name" => "mp4", "icon" => "far fa-file-video"),
    "video/mpeg" => array("name" => "mpeg", "icon" => "far fa-file-video"),
    "application/x-troff-ms" => "ms",
    "video/vnd.mpegurl" => array("name" => "mxu", "icon" => "far fa-file-video"),
    "application/vnd.oasis.opendocument.database" => "odb",
    "application/vnd.oasis.opendocument.chart" => "odc",
    "application/vnd.oasis.opendocument.formula" => "odf",
    "application/vnd.oasis.opendocument.graphics" => "odg",
    "application/vnd.oasis.opendocument.image" => "odi",
    "application/vnd.oasis.opendocument.text-master" => "odm",
    "application/vnd.oasis.opendocument.presentation" => "odp",
    "application/vnd.oasis.opendocument.spreadsheet" => "ods",
    "application/vnd.oasis.opendocument.text" => "odt",
    "application/ogg" => "ogg",
    "video/ogg" => array("name" => "ogv", "icon" => "far fa-file-video"),
    "application/vnd.oasis.opendocument.graphics-template" => "otg",
    "application/vnd.oasis.opendocument.text-web" => "oth",
    "application/vnd.oasis.opendocument.presentation-template" => "otp",
    "application/vnd.oasis.opendocument.spreadsheet-template" => "ots",
    "application/vnd.oasis.opendocument.text-template" => "ott",
    "image/x-portable-bitmap" => array("name" => "pbm", "icon" => "far fa-file-image"),
    "chemical/x-pdb" => "pdb",
    "application/pdf" => array("name" => "pdf", "icon" => "far fa-file-pdf"),
    "image/x-portable-graymap" => array("name" => "pgm", "icon" => "far fa-file-image"),
    "application/x-chess-pgn" => "pgn",
    "text/x-php" => array("name" => "php", "icon" => "far fa-file-code"),
    "image/png" => array("name" => "png", "icon" => "far fa-file-image"),
    "image/x-portable-anymap" => array("name" => "pnm", "icon" => "far fa-file-image"),
    "image/x-portable-pixmap" => array("name" => "ppm", "icon" => "far fa-file-image"),
    "application/vnd.ms-powerpoint" => "ppt",
    "audio/x-realaudio" => array("name" => "ra", "icon" => "far fa-file-audio"),
    "audio/x-pn-realaudio" => array("name" => "rms", "icon" => "far fa-file-audio"),
    "image/x-cmu-raster" => array("name" => "ras", "icon" => "far fa-file-image"),
    "image/x-rgb" => array("name" => "rgb", "icon" => "far fa-file-image"),
    "application/x-troff" => "tr",
    "application/x-rpm" => "rpm",
    "text/rtf" => array("name" => "rtf", "icon" => "far fa-file-alt"),
    "text/richtext" => array("name" => "rtx", "icon" => "far fa-file-alt"),
    "text/sgml" => array("name" => "sgml", "icon" => "far fa-file-alt"),
    "application/x-sh" => "sh",
    "application/x-shar" => "shar",
    "application/vnd.symbian.install" => "sis",
    "application/x-stuffit" => "sit",
    "application/x-koan" => "skt",
    "application/smil" => "smil",
    "image/svg+xml" => array("name" => "svg", "icon" => "far fa-file-image"),
    "application/x-futuresplash" => "spl",
    "application/x-wais-source" => "src",
    "application/vnd.sun.xml.calc.template" => "stc",
    "application/vnd.sun.xml.draw.template" => "std",
    "application/vnd.sun.xml.impress.template" => "sti",
    "application/vnd.sun.xml.writer.template" => "stw",
    "application/x-sv4cpio" => "sv4cpio",
    "application/x-sv4crc" => "sv4crc",
    "application/x-shockwave-flash" => "swf",
    "application/vnd.sun.xml.calc" => "sxc",
    "application/vnd.sun.xml.draw" => "sxd",
    "application/vnd.sun.xml.writer.global" => "sxg",
    "application/vnd.sun.xml.impress" => "sxi",
    "application/vnd.sun.xml.math" => "sxm",
    "application/vnd.sun.xml.writer" => "sxw",
    "application/x-tar" => "tar",
    "application/x-tcl" => "tcl",
    "application/x-tex" => "tex",
    "application/x-texinfo" => "texinfo",
    "image/tiff" => array("name" => "tiff", "icon" => "far fa-file-image"),
    "image/tiff-fx" => array("name" => "tiff", "icon" => "far fa-file-image"),
    "application/x-bittorrent" => "torrent",
    "text/tab-separated-values" => array("name" => "tsv", "icon" => "far fa-file-alt"),
    "application/x-ustar" => "ustar",
    "application/x-cdlink" => "vcd",
    "model/vrml" => "wrl",
    "audio/x-wav" => array("name" => "wav", "icon" => "far fa-file-audio"),
    "audio/x-ms-wax" => array("name" => "wax", "icon" => "far fa-file-audio"),
    "image/vnd.wap.wbmp" => array("name" => "wbmp", "icon" => "far fa-file-image"),
    "application/vnd.wap.wbxml" => "wbxml",
    "video/x-ms-wm" => array("name" => "wm", "icon" => "far fa-file-video"),
    "audio/x-ms-wma" => array("name" => "wma", "icon" => "far fa-file-audio"),
    "text/vnd.wap.wml" => array("name" => "wml", "icon" => "far fa-file-alt"),
    "application/vnd.wap.wmlc" => "wmlc",
    "text/vnd.wap.wmlscript" => array("name" => "wmls", "icon" => "far fa-file-alt"),
    "application/vnd.wap.wmlscriptc" => "wmlsc",
    "video/x-ms-wmv" => array("name" => "wmv", "icon" => "far fa-file-video"),
    "video/x-ms-wmx" => array("name" => "wmx", "icon" => "far fa-file-video"),
    "video/x-ms-wvx" => array("name" => "wvx", "icon" => "far fa-file-video"),
    "image/x-xbitmap" => array("name" => "xbm", "icon" => "far fa-file-image"),
    "application/xhtml+xml" => array("name" => "xhtml", "icon" => "far fa-file-code"),
    "application/xml" => array("name" => "xml", "icon" => "far fa-file-code"),
    "image/x-xpixmap" => array("name" => "xpm", "icon" => "far fa-file-image"),
    "text/xsl" => array("name" => "xsl", "icon" => "far fa-file-alt"),
    "image/x-xwindowdump" => array("name" => "xwd", "icon" => "far fa-file-image"),
    "chemical/x-xyz" => "xyz",
    "application/zip" => array("name" => "zip", "icon" => "far fa-file-archive"),
    "application/msword" => array("name" => "doc", "icon" => "far fa-file-word"),
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => array("name" => "docx", "icon" => "far fa-file-word"),
    "application/vnd.openxmlformats-officedocument.wordprocessingml.template" => array("name" => "doxt", "icon" => "far fa-file-word"),
    "application/vnd.ms-word.document.macroEnabled.12" => array("name" => "docm", "icon" => "far fa-file-word"),
    "application/vnd.ms-excel" => array("name" => "xls", "icon" => "far fa-file-excel"),
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => array("name" => "xlsx", "icon" => "far fa-file-excel"),
    "application/vnd.openxmlformats-officedocument.spreadsheetml.template" => array("name" => "xltx", "icon" => "far fa-file-excel"),
    "application/vnd.ms-excel.sheet.macroEnabled.12" => array("name" => "xlsm", "icon" => "far fa-file-excel"),
    "application/vnd.ms-excel.template.macroEnabled.12" => array("name" => "xltm", "icon" => "far fa-file-excel"),
    "application/vnd.ms-excel.addin.macroEnabled.12" => array("name" => "xlam", "icon" => "far fa-file-excel"),
    "application/vnd.ms-excel.sheet.binary.macroEnabled.12" => array("name" => "xlsb", "icon" => "far fa-file-excel"),
    "application/vnd.openxmlformats-officedocument.presentationml.presentation" => array("name" => "pptx", "icon" => "far fa-file-powerpoint"),
    "application/vnd.openxmlformats-officedocument.presentationml.template" => array("name" => "potx", "icon" => "far fa-file-powerpoint"),
    "application/vnd.openxmlformats-officedocument.presentationml.slideshow" => array("name" => "ppsx", "icon" => "far fa-file-powerpoint"),
    "application/vnd.ms-powerpoint.addin.macroEnabled.12" => array("name" => "ppam", "icon" => "far fa-file-powerpoint"),
    "application/vnd.ms-powerpoint.presentation.macroEnabled.12" => array("name" => "pptm", "icon" => "far fa-file-powerpoint"),
    "application/vnd.ms-powerpoint.template.macroEnabled.12" => array("name" => "potm", "icon" => "far fa-file-powerpoint"),
    "application/vnd.ms-powerpoint.slideshow.macroEnabled.12" => array("name" => "ppsm", "icon" => "far fa-file-powerpoint"),
    "application/json" => array("name" => "json", "icon" => "far fa-file-code")
));
define("FILEMANAGER_ROOT_DIR", getcwd() . "/sites/" . $GLOBALS['awe']->SiteAlias . "/tmp");
define("ADM_FILEMANAGER_AJAX_VIEW", array(
    "waiting" => "0",
    "method" => "view",
    "method2" => "view2",
    "url" => "/admin/adm_filemanager/ajax",
    "result" => "#directorylist"));

class adm_filemanager {

    private AWE $AWE;
    private $Params;
    private $Elements = array();

    function __construct($array) {
        $this->AWE = &$GLOBALS['awe'];



        /* GetURL Params */
        if (isset($_POST['__urlparams__'])) {
            $this->Params = $this->AWE->getUrlParams($_POST['__urlparams__']);
        } else {
            $this->Params = $this->AWE->getUrlParams();
        }


        if (!empty($this->Params["filemanager_view_path"]) && isset($this->Params['filemanager_view_path'])) {
            $this->Elements['path'] = $this->Params["filemanager_view_path"];
        } else {
            $this->Elements['path'] = FILEMANAGER_ROOT_DIR;
        }
        //var_dump($this->Elements['path']);

        if (isset($array['url_obj']["params"]["ajax"]) && $array['url_obj']["params"]["ajax"] == TRUE) {
            //var_dump($_POST['__uploadmaxsize__']);
            //ini_set('upload_max_filesize', $_POST['__uploadmaxsize__']."M");
            //var_dump(ini_get('upload_max_filesize'));
            $this->AjaxCall($array);
        } else {
            $this->SimpleCall($array);
        }
    }

    private function AjaxCall($array) {
        if ($_POST['__method__'] != 'fileupload') {
            if (ADM_FILEMANAGER_AJAX_VIEW['method2'] != $_POST['__method__']) {
                /* Adott mappa listájának elkészítése */
                if (empty($_POST['path'])) {
                    if (!empty($this->Elements['path'])) {
                        $strA = explode('/', $this->Elements['path']);
                        $this->Elements['path'] = str_replace("/" . end($strA), "", $this->Elements['path']);
                    } else {
                        $this->Elements['path'] = FILEMANAGER_ROOT_DIR;
                    }
                } else {
                    $this->Elements['path'] = $this->Elements['path'] . $_POST['path'];
                }
            } else {
                $this->Elements['path'] = FILEMANAGER_ROOT_DIR . $_POST['path'];
            }
        }
        /* Konfig lekérdezés */
        $this->Elements['config'] = array_merge($this->AWE->getSettings(array("settings_id" => $_POST['__comid__'])), $array);
        if (!empty($this->Elements['config']['settings_id'])) {
            $this->Elements['config']['url_id'] = $this->Elements['config']['settings_id'];
        }
        if (!empty($this->Elements['config']["template"]) && is_file($this->Elements['config']["path"] . "templates/" . $this->Elements['config']["template"] . "/" . $this->Elements['config']["template"] . ".php")) {
            require_once($this->Elements['config']["path"] . "templates/" . $this->Elements['config']["template"] . "/" . $this->Elements['config']["template"] . ".php");
            if ($_POST['__method__'] != 'fileupload') {
                $this->Elements['directory_elements'] = $this->directoryElements($this->Elements['path']);
                $this->getFilesCounter($this->Elements['directory_elements']);
            }
            switch ($_POST['__method__']) {
                case "view":
                    echo json_encode(array("__url_params__" => $this->AWE->addUrlParams(array("filemanager_view_path" => $this->Elements['path'])), "html" => array("#directorylist" => array("mode" => "override", "html" => listDirectoryElements($this->Elements)))));
                    break;
                case ADM_FILEMANAGER_AJAX_VIEW['method2']:
                    echo json_encode(array("__url_params__" => $this->AWE->addUrlParams(array("filemanager_view_path" => $this->Elements['path'])), "html" => array("#directorylist" => array("mode" => "override", "html" => listDirectoryElements($this->Elements)))));
                    break;
                case "fileupload":
                    $file = $_FILES['file']['name'];
                    move_uploaded_file($_FILES['file']['tmp_name'], $this->Elements['path'] . "/" . $file);
                    echo json_encode(array("html" => array("#upload" => array("mode" => "append", "html" => $this->Elements['path']))));
                    break;
            }
        } else {
            $this->AWE->Logger->setError(array("text" => "E0008 - Komponens template-je nem található", "line" => __LINE__, "file" => __FILE__));
        }
    }

    private function SimpleCall($array) {
        $this->Elements['config'] = $array;
        if (!empty($this->Elements['config']["template"]) && is_file($this->Elements['config']["path"] . "templates/" . $this->Elements['config']["template"] . "/" . $this->Elements['config']["template"] . ".php")) {
            require_once($this->Elements['config']["path"] . "templates/" . $this->Elements['config']["template"] . "/" . $this->Elements['config']["template"] . ".php");
            $this->Elements['directory_elements'] = $this->directoryElements($this->Elements['path']);
            $this->getFilesCounter($this->Elements['directory_elements']);
            $this->Elements['directory_tree'] = $this->recursiveDirectoryTree(FILEMANAGER_ROOT_DIR);
            display($this->Elements);
        } else {
            $this->AWE->Logger->setError(array("text" => "E0008 - Komponens template-je nem található", "line" => __LINE__, "file" => __FILE__));
        }
    }

    private function getFilesCounter($array) {
        $this->Elements["__counter__"] = array();
        $this->Elements["__counter__"]["directories"] = 0;
        $this->Elements["__counter__"]["files"] = 0;
        foreach ($array as $key => $value) {
            if ($value->fileType['name'] == "folder" && $value->fileName != "..") {
                $this->Elements["__counter__"]["directories"]++;
            } else {
                $this->Elements["__counter__"]["files"]++;
            }
        }
    }

    public function directoryElements($directory, $viewmode = "list") {
        $array = array();

        $dirs = scandir($directory);
        foreach ($dirs as $dir) {
            if ($dir != "." && ($dir != ".." || (FILEMANAGER_ROOT_DIR != $directory && FILEMANAGER_ROOT_DIR . "/" != $directory))) {
                $array[$dir] = new adm_file(array("file" => $directory . "/" . $dir));
            }
        }
        return $array;
    }

    public function recursiveDirectoryTree($directory) {
        $array = array();
        $dirs = scandir($directory);
        foreach ($dirs as $dir) {
            if ($dir != "." && $dir != "..") {
                if (is_dir($directory . "/" . $dir) && iterator_count(new FilesystemIterator($directory . "/" . $dir, FilesystemIterator::SKIP_DOTS)) > 0) {
                    $array[$dir] = $this->recursiveDirectoryTree($directory . "/" . $dir);
                } else {
                    if (!is_file($directory . "/" . $dir)) {
                        $array[$dir] = array();
                    }
                }
            }
        }
        return $array;
    }

}

class adm_file {

    public $fileName;
    public $fileSize;
    public $fileType;
    public $fileModificationTime;
    private AWE $AWE;

    function __construct($array) {
        $this->AWE = &$GLOBALS['awe'];
        $tf = explode("/", $array['file']);
        $this->fileName = end($tf);
        $this->fileModificationTime = date("Y/m/d H:i:s", filemtime($array['file']));
        if (is_file($array['file'])) {
            $this->fileType = isset(MIME_TYPES[mime_content_type($array['file'])]) ? MIME_TYPES[mime_content_type($array['file'])] : array("name" => "unknown", "icon" => "far fa-file-alt");
            $this->fileSize = $this->getFileSize($array['file']);
        } elseif (is_dir($array['file'])) {
            $this->fileType = array("name" => "folder", "icon" => "far fa-folder");
            if ($this->fileName != "." && $this->fileName != "..") {
                $this->fileSize = $this->getDirectorySize($array['file']);
            } else {
                $this->fileSize = "FOLDER";
            }
        }
    }

    public function getDirectorySize($directory) {
        $size = 0;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
            $size += $file->getSize();
        }
        foreach (BYTES as $byte) {
            if ($size >= $byte["VALUE"]) {
                $ret = $size / $byte["VALUE"];
                $ret = str_replace(".", ",", strval(round($ret, 2))) . " " . $byte["UNIT"];
                break;
            }
        }
        return $ret;
    }

    public function getFileSize($file) {
        $size = floatval(filesize($file));
        $ret = "";
        foreach (BYTES as $byte) {
            if ($size >= $byte["VALUE"]) {
                $ret = $size / $byte["VALUE"];
                $ret = str_replace(".", ",", strval(round($ret, 2))) . " " . $byte["UNIT"];
                break;
            }
        }
        return $ret;
    }

}
