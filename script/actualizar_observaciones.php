<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");

include("conex.php");

$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");

$id = $_POST["id"];
$obs = $_POST["obs"];
$tipo = $_POST["tipo"];
$bitacora = $_POST["bitacora"];



mysql_close($con);

include("conex_doc.php");

if ($bitacora=="bitacora") {
    $cadenasql = "UPDATE SOLICITUDES SET bitacora = CONCAT('".$var_fecha." * ', '".$var_ip." * ', '".$uname."', ' * ACTUALIZAR OBSERVACIONES * ', '".$obs." <br>, ', bitacora), obs = obs = CONCAT('".$var_fecha." ', '".$uname.": ', '".$obs."') where id = ".$id."";
}

else{
    $cadenasql = "UPDATE SOLICITUDES SET bitacora = CONCAT('".$var_fecha." * ', '".$var_ip." * ', '".$uname."', ' * ACTUALIZAR OBSERVACIONES * ', '".$obs." <br>, '), obs = obs = CONCAT('".$var_fecha." ', '".$uname.": ', '".$obs."') where id = ".$id."";
}



$sqlx = mysql_query($cadenasql, $con_doc);




header('Location: ../missolicitudes.php?msg=SE ACTUALIZARON LOS RESPONSABLES');

?>