<?php
//just output the zip file

$save_path = "/var/DL/Privé";

if(isset($_GET["dossier"])) {
	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=Privé'. $_GET["dossier"] . ".zip");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($save_path . $_GET["dossier"] . ".zip"));
    ob_clean();
    flush();
    readfile($save_path . $_GET["dossier"] . ".zip");
    exit;
}
?>