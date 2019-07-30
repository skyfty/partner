<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

if (!$_REQUEST['m']) {
    exit(0);
}
$module = trim($_REQUEST['m']);
$field = "file";

$url = "/Uploads/excel/".date("Ym")."/".date("d")."/";
$upload_dir = dirname(dirname($_SERVER['SCRIPT_FILENAME'])).$url;

$options = array(
    'delete_type' => 'POST',
    'upload_dir' => $upload_dir,
    'upload_url' => $url,
    'param_name'=>$field,
);
require("upload.php");

class ExcelUploadHandler extends UploadHandler {
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,$index = null, $content_range = null) {
        $file = parent::handle_file_upload($uploaded_file, $name, $size, $type, $error, $index, $content_range);
        if (empty($file->error)) {

        }
        return $file;
    }
}

$upload_handler = new ExcelUploadHandler($options);