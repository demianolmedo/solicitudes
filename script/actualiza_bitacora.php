<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");
include("conex_doc.php");

$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");


$id = $_POST["id"];
$bitacora = $_POST["bitacora"];
$bitacora_bd = $_POST["bitacora_bd"];
$bitacora_old = $_POST["bitacora_old"];
$date_fin = $_POST["date_fin"];

if ($bitacora_bd=="VACIO") {
    if (!$_POST["date_fin"]) {$cadenasql = "UPDATE BITACORAS SET bitacora = CONCAT('* ".$var_fecha." ".$uname." ACTUALIZO LA BITACORA: ".$bitacora." <br><br>') WHERE id=".$id."";}
    else {$cadenasql = "UPDATE BITACORAS SET date_fin = '".$date_fin."', bitacora = CONCAT('* ".$var_fecha." ".$uname." CERRO LA BITACORA: ".$bitacora." <br><br>') WHERE id=".$id."";}
}
else{
    if (!$_POST["date_fin"]) {$cadenasql = "UPDATE BITACORAS SET bitacora = CONCAT('* ".$var_fecha." ".$uname." ACTUALIZO LA BITACORA: ".$bitacora." <br><br>', '".$bitacora_old."') WHERE id=".$id."";}
    else {$cadenasql = "UPDATE BITACORAS SET date_fin = '".$date_fin."', bitacora = CONCAT('* ".$var_fecha." ".$uname." CERRO LA BITACORA: ".$bitacora." <br><br>', '".$bitacora_old."') WHERE id=".$id."";}
}


//echo $cadenasql; exit;
$sqlx = mysql_query($cadenasql, $con_doc);

mysql_close($con_doc);


if (!$_POST["date_fin"]) {header('Location: ../bitacoras_activas.php');} else {header('Location: ../bitacoras_cerradas.php');}

?>