<?php
header("Content-type: text/html; charset=UTF-8");
 
$data = $_POST['data'];

$jsonfile = fopen("resources/data/tree.json", "w") or die("Unable to open file!");
fwrite($jsonfile, $data);
fclose($jsonfile);
?>