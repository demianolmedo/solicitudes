<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");
include("conex_doc.php");

$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");

$id = $_REQUEST["id"];
$estado = $_REQUEST["estado"];
$id_rel = $_REQUEST["id_rel"];


$cadenasql = "UPDATE tablero SET bitacora = CONCAT('* ', '".$var_fecha.": ', '".$uname."', ' CAMBIO EL ESTADO DE: ', estado, ' <br><br> ', bitacora), estado = '".$estado."' WHERE id=".$id."";

//echo $cadenasql; exit;


$sqlx = mysql_query($cadenasql, $con_doc);

mysql_close($con_doc);

header('Location: ../tablero.php?id='.$id_rel);

?>