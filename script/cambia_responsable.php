<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");

include("conex.php");

$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");

$id = $_POST["id"];
$responsable = $_POST["responsable"];
$tipo = $_POST["tipo"];
$bitacora = $_POST["bitacora"];

$responsable1 = "";
for ($x = 0; $x < count($responsable); $x++)
    $responsable1 .= $responsable[$x] . ",";
$responsable2 = substr($responsable1, 0, -1);

$responsable_n = "";
$sql = "SELECT DESCRIPTION FROM USERS WHERE USER_ID IN (" . $responsable2 . ")";
$resultado = mysql_query($sql, $con);
while ($data = mysql_fetch_array($resultado)) {
    $responsable_n .= "&bull; " . $data["DESCRIPTION"] . " <br> ";
}
$responsable_n1 = substr($responsable_n, 0, -6);
$responsable_n2 = str_replace("<br>", "-", $responsable_n1);



mysql_close($con);

include("conex_doc.php");

if ($bitacora=="bitacora") {
    $cadenasql = "UPDATE SOLICITUDES SET bitacora = CONCAT('".$var_fecha." * ', '".$var_ip." * ', '".$uname."', ' * ACTUALIZAR RESPONSABLE * ', '".$responsable_n2." <br>, ', bitacora), responsable_n = '".$responsable_n1."', responsable='".$responsable2."' where id = ".$id."";
}

else{
    $cadenasql = "UPDATE SOLICITUDES SET bitacora = CONCAT('".$var_fecha." * ', '".$var_ip." * ', '".$uname."', ' * ACTUALIZAR RESPONSABLE * ', '".$responsable_n2." <br>, '), responsable_n = '".$responsable_n1."', responsable='".$responsable2."' where id = ".$id."";
}



$sqlx = mysql_query($cadenasql, $con_doc);




header('Location: ../missolicitudes.php?msg=SE ACTUALIZARON LOS RESPONSABLES');

?>