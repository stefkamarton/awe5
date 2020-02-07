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
        "VALUE" => pow(1024, 2)),
    4 => array(
        "UNIT" => "KB",
        "VALUE" => pow(1024, 1)),
    5 => array(
        "UNIT" => "B",
        "VALUE" => 1)
));

define("FILEMANAGER_ROOT_DIR", getcwd() . "/sites/" . $GLOBALS['awe']->SiteAlias . "/tmp");
define("ADM_FILEMANAGER_AJAX_VIEW", array(
    "waiting" => "0",
    "method" => "view",
    "method2" => "view2",
    "delete" => "confirmdelete",
    "confirmdelete" => "delete",
    "createdir" => "createdir",
    "createdirconfirm" => "createdirconfirm",
    "url" => "/admin/adm_filemanager/ajax",
    "result" => "#directorylist"));
define("DENIED_FILENAMES", array("." . "..", ".thumbs"));

class adm_filemanager1 {

    private AWE $AWE;
    private $Params;
    private $Elements = array();

    public function __construct($array) {
        $this->AWE = &$GLOBALS['awe'];

        /* Get URL parameters */
        $this->getUrlParams($array);

        /* Get Config */
    }

    /* Get Config */

    public function getConfig($array) {
        if (isset($_POST['__comid__'])) {
            $this->Elements['config'] = array_merge($this->AWE->getSettings(array("settings_id" => $_POST['__comid__'])), $array);
            if (!empty($this->Elements['config']['settings_id'])) {
                $this->Elements['config']['url_id'] = $this->Elements['config']['settings_id'];
            }
        }
    }

    /* Get URL parameters */

    public function getUrlParams($array) {
        if (isset($_POST['__urlparams__'])) {
            $this->Params = $this->AWE->getUrlParams($_POST['__urlparams__']);
        } else {
            $this->Params = $this->AWE->getUrlParams();
        }
        if (isset($_POST["__comid__"]) && isset($this->Params[$_POST["__comid__"]])) {
            $this->Params = $this->Params[$_POST["__comid__"]];
        } else {
            if (isset($this->Params[$array["url_id"]])) {
                $this->Params = $this->Params[$array["url_id"]];
            } else {
                $this->Params = NULL;
            }
        }
    }

}

class adm_filemanager {

    private AWE $AWE;
    private $Params;
    private $Elements = array();

    function __construct($array) {
        $this->AWE = &$GLOBALS['awe'];

        /* GetURL Params */

        //
        if (!empty($this->Params["filemanager_view_path"]) && isset($this->Params['filemanager_view_path'])) {
            $this->Elements['path'] = $this->Params["filemanager_view_path"];
        } else {
            $this->Elements['path'] = FILEMANAGER_ROOT_DIR;
        }

        require_once(__DIR__ . "/filemanager.extension.php");

        if (isset($array['url_obj']["params"]["ajax"]) && $array['url_obj']["params"]["ajax"] == TRUE) {
            $this->AjaxCall($array);
        } else {
            $this->SimpleCall($array);
        }
    }

    private function AjaxCall($array) {
        if ($_POST['__method__'] != 'fileupload') {
            if (ADM_FILEMANAGER_AJAX_VIEW['method2'] != $_POST['__method__']) {
                /* Adott mappa listájának elkészítése */
                if (empty($_POST['filename'])) {
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
                    if (empty($this->Elements['path'])) {
                        $this->Elements['path'] = FILEMANAGER_ROOT_DIR;
                    }
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
                case ADM_FILEMANAGER_AJAX_VIEW["createdir"]:
                    var_dump($this->Elements['path']);
                    mkdir($this->Elements['path'] . "/" . $_POST['dirname'], 0777, true);
                    echo json_encode(array("message" => array(0 => array("type" => "success", "title" => "Sikeres törlés!", "text" => "Sikeresen törölted ezt a fájl-t: <b>" . $_POST['dirname'] . "</b>"))));
                    break;

                case ADM_FILEMANAGER_AJAX_VIEW["createdirconfirm"]:
                    echo json_encode(array("html" => array("confirm" => array("mode" => "confirm", "html" => createDirectoryConfirm($this->Elements)))));
                    break;

                case "view":
                    echo json_encode(array("__url_params__" => $this->AWE->addUrlParams(array($this->Elements['config']['url_id'] => array("filemanager_view_path" => $this->Elements['path']))), "html" => array($this->Elements['config']['url_id'] . "_directorylist" => array("mode" => "override", "html" => listDirectoryElements($this->Elements)))));
                    break;

                case ADM_FILEMANAGER_AJAX_VIEW['method2']:
                    echo json_encode(array(
                        "__url_params__" => $this->AWE->addUrlParams(array($this->Elements['config']['url_id'] =>
                            array("filemanager_view_path" => $this->Elements['path']))),
                        "html" => array($this->Elements['config']['url_id'] . "_directorylist" => array("mode" => "override", "html" => listDirectoryElements($this->Elements)))));
                    break;
                case "confirmdelete":
                    echo json_encode(array("html" => array("confirm" => array("mode" => "confirm", "html" => confirmDeletionMessage($this->Elements)))));
                    break;

                case "delete":
                    if (is_dir($this->Elements['path'] . "/" . $_POST['filename'])) {
                        $this->delete_directory($this->Elements['path'] . "/" . $_POST['filename']);
                    } else {
                        $filetype = mime_content_type($this->Elements['path'] . "/" . $_POST['filename']);
                        if ($this->AWE->stringStartsWith(array("substring" => "image", "string" => $filetype))) {
                            $file = explode(".", $_POST['filename']);
                            unset($file[count($file) - 1]);
                            $file = implode(".", $file);
                            $file .= ".webp";
                            $thumbpath = str_replace(FILEMANAGER_ROOT_DIR, "", $this->Elements['path']);
                            $thumbpath = explode("/", $thumbpath);
                            $thumbname = "";
                            foreach ($thumbpath as $value) {
                                $thumbname .= $value . "_";
                            }
                            unlink($this->Elements['path'] . "/" . $file);
                            unlink(FILEMANAGER_ROOT_DIR . "/.thumbs/" . $thumbname . $_POST['filename'] . "_thumb.jpg");
                        }
                        unlink($this->Elements['path'] . "/" . $_POST['filename']);
                    }
                    $this->Elements['directory_elements'] = $this->directoryElements($this->Elements['path']);
                    $this->getFilesCounter($this->Elements['directory_elements']);
                    echo json_encode(
                            array("message" => array(0 => array("type" => "success", "title" => "Sikeres törlés!", "text" => "Sikeresen törölted ezt a fájl-t: <b>" . $_POST['filename'] . "</b>")),
                                "html" => array($_POST['filename'] . "_confirm" => array("mode" => "override", "html" => ""),
                                    $this->Elements['config']['url_id'] . "_directorylist" => array("mode" => "override", "html" => listDirectoryElements($this->Elements)),
                                    "upload" => array("mode" => "append", "html" => $this->Elements['path']))));


                    break;
                case "fileupload":
                    for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
                        $file = $_FILES['file']['name'][$i];
                        move_uploaded_file($_FILES['file']['tmp_name'][$i], $this->Elements['path'] . "/" . $file);
                        $filetype = mime_content_type($this->Elements['path'] . "/" . $file);

                        if ($this->AWE->stringStartsWith(array("substring" => "image", "string" => $filetype))) {
                            if ($this->AWE->stringContains(array("substring" => "webp", "string" => $filetype))) {
                                $this->convertImage($this->Elements['path'], $file);
                                $file = explode(".", $file);
                                unset($file[count($file) - 1]);
                                $file = implode(".", $file);
                                $file .= ".jpg";
                            } else {
                                $this->convertImage($this->Elements['path'], $file, MIME_TYPES[$filetype]["name"]);
                            }
                            $this->convertImage($this->Elements['path'], $file, "webp");

                            try {
                                $this->generateThumbnail($this->Elements['path'], $file, 128, 128, 90);
                            } catch (ImagickException $e) {
                                echo $e->getMessage();
                            } catch (Exception $e) {
                                echo $e->getMessage();
                            }
                        }
                    }

                    //unlink($this->Elements['path'] . "/" . $file);
                    $this->Elements['directory_elements'] = $this->directoryElements($this->Elements['path']);
                    $this->getFilesCounter($this->Elements['directory_elements']);
                    echo json_encode(array("html" => array($this->Elements['config']['url_id'] . "_directorylist" => array("mode" => "override", "html" => listDirectoryElements($this->Elements)), "upload" => array("mode" => "append", "html" => $this->Elements['path']))));
                    break;
            }
        } else {
            $this->AWE->Logger->setError(array("text" => "E0008 - Komponens template-je nem található", "line" => __LINE__, "file" => __FILE__));
        }
    }

    function delete_directory($dirname) {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file))
                    unlink($dirname . "/" . $file);
                else
                    delete_directory($dirname . '/' . $file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }

    function convertImage($path, $img, $format = "jpg") {
        $imgPath = $path . "/" . $img;
        if (is_file($imgPath)) {
            $imagick = new Imagick(realpath($imgPath));
            $imagick->setImageFormat($format);
            $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
            $imagick->setImageCompressionQuality(90);
            $filename_no_ext = explode('.', $img);
            unset($filename_no_ext[count($filename_no_ext) - 1]);
            $filename_no_ext = implode(".", $filename_no_ext);
            if (file_put_contents($path . "/" . $filename_no_ext . "." . $format, $imagick) === false) {
                throw new Exception("Could not put contents.");
            }

            //$imagick->writeImage('webp:' . $path . "/" . $filename_no_ext . ".webp");
            return true;
        } else {
            throw new Exception("No valid image provided with {$img}.");
        }
    }

    function convertToWebP($path, $img) {
        $imgPath = $path . "/" . $img;
        if (is_file($imgPath)) {
            $imagick = new Imagick(realpath($imgPath));
            $imagick->setImageFormat('webp');
            //$imagick->setOption('webp:method', '6');
            $imagick->setImageCompression(Imagick::COMPRESSION_BZIP);
            $imagick->setImageCompressionQuality(90);
            $filename_no_ext = explode('.', $img);
            unset($filename_no_ext[count($filename_no_ext)]);
            $filename_no_ext = implode(".", $filename_no_ext);
            if (file_put_contents($path . "/" . $filename_no_ext . ".webp", $imagick) === false) {
                throw new Exception("Could not put contents.");
            }

            //$imagick->writeImage('webp:' . $path . "/" . $filename_no_ext . ".webp");
            return true;
        } else {
            throw new Exception("No valid image provided with {$img}.");
        }
    }

    function generateThumbnail($path, $img, $width, $height, $quality = 90) {
        $imgPath = $path . "/" . $img;
        if (is_file($imgPath)) {
            $imagick = new Imagick($imgPath);
            $imagick->setImageFormat('jpg');
            $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
            $imagick->setImageCompressionQuality($quality);
            $imagick->thumbnailImage($width, $height, true, false);
            $filename_no_ext = explode('.', $img);
            unset($filename_no_ext[count($filename_no_ext)]);
            $filename_no_ext = implode(".", $filename_no_ext);
            $thumbpath = str_replace(FILEMANAGER_ROOT_DIR, "", $path);
            $thumbpath = explode("/", $thumbpath);
            $thumbname = "";
            foreach ($thumbpath as $value) {
                $thumbname .= $value . "_";
            }
            if (!file_exists(FILEMANAGER_ROOT_DIR . "/.thumbs")) {
                mkdir(FILEMANAGER_ROOT_DIR . "/.thumbs", 0777, true);
            }
            //$imagick->writeImage('webp:' . FILEMANAGER_ROOT_DIR . "/.thumbs/" . $thumbname . $filename_no_ext . '.webp_thumb' . '.webp');
            if (file_put_contents(FILEMANAGER_ROOT_DIR . "/.thumbs/" . $thumbname . $filename_no_ext . '_thumb' . '.jpg', $imagick) === false) {
                throw new Exception("Could not put contents.");
            }

            //$imagick->writeImage('webp:' . $path . "/" . $filename_no_ext . ".webp");

            return true;
        } else {
            throw new Exception("No valid image provided with {$img}.");
        }
    }

    private function SimpleCall($array) {
        $this->Elements['config'] = $array;
        if (!empty($this->Elements['config']["template"]) && is_file($this->Elements['config']["path"] . "templates/" . $this->Elements['config']["template"] . "/" . $this->Elements['config']["template"] . ".php")) {
            require_once($this->Elements['config']["path"] . "templates/" . $this->Elements['config']["template"] . "/" . $this->Elements['config']["template"] . ".php");
            $this->Elements['directory_elements'] = $this->directoryElements($this->Elements['path']);
            $this->getFilesCounter($this->Elements['directory_elements']);
            $this->Elements['directory_tree'] = array("/" => $this->recursiveDirectoryTree(FILEMANAGER_ROOT_DIR));
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
            if ($dir != "." && ($dir != ".." && $dir != ".thumbs" || (FILEMANAGER_ROOT_DIR != $directory && FILEMANAGER_ROOT_DIR . "/" != $directory))) {
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
    public $Commands;
    private AWE $AWE;

    function __construct($array) {
        $this->AWE = &$GLOBALS['awe'];
        $this->Commands = array();
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
                $ret = str_replace(".", ",", strval(round($ret, 1, PHP_ROUND_HALF_EVEN))) . " " . $byte["UNIT"];
                break;
            }
        }
        return $ret;
    }

}
