<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");
include("conex_doc.php");

$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");

$id = $_POST["id"];
$id_rel = $_POST["id_rel"];
$lista = $_POST["estado"];
$obs = $_POST["obs"];
$fecha_ini = substr($_POST["reservation"], 0, 10);
$fecha_fin = substr($_POST["reservation"], -10);
$fecha_ant = $_POST["fecha_ant"];
$visible = $_POST["visible"];
$integrantes_solicitud = $_POST["integrantes_solicitud"];

$integrantes_solicitud1 = "";
for ($x = 0; $x < count($integrantes_solicitud); $x++)
    $integrantes_solicitud1 .= $integrantes_solicitud[$x] . ",";
$integrantes_solicitud2 = substr($integrantes_solicitud1, 0, -1);

$integrantes_solicitud_n = "";
$sql = "SELECT DESCRIPTION FROM APPLPORDB.USERS WHERE USER_ID IN (" . $integrantes_solicitud2 . ")";
$resultado = mysql_query($sql, $con_doc);
while ($data = mysql_fetch_array($resultado)) {
    $integrantes_solicitud_n .= "&bull; " . $data["DESCRIPTION"] . " <br> ";
}
$integrantes_solicitud_n1 = substr($integrantes_solicitud_n, 0, -6);
$integrantes_solicitud_n2 = str_replace("<br>", "-", $integrantes_solicitud_n1);


if ($integrantes_solicitud1=="") {$obs_sql=""; $integrantes_sql="";} else {$obs_sql=" CAMBIO INTEGRANTES "; $integrantes_sql=", integrantes = '".$integrantes_solicitud2."', integrantes_n = '".$integrantes_solicitud_n1."'";}

if (!$fecha_ini) {$cadenasql = "UPDATE tablero SET bitacora = CONCAT('* ', '".$var_fecha.": ', '".$uname."', '".$obs_sql." CREO LA OBSERVACION: ', '".$obs." <br><br> ', bitacora), visible = '".$visible."' ".$integrantes_sql.", obs='".$var_fecha." ".$uname.": ".$obs."' WHERE id=".$id."";}
else {$cadenasql = "UPDATE tablero SET fecha_ini='".$fecha_ini."', fecha_fin='".$fecha_fin."', bitacora = CONCAT('* ', '".$var_fecha.": ', '".$uname."', ".$obs_sql."' CAMBIO LA FECHA ANTERIOR: ', '".$fecha_ant."', '', ' MOTIVO: ', '".$obs."', '<br><br> ', bitacora), visible = '".$visible."' ".$integrantes_sql.", obs='".$var_fecha." ".$uname.": ".$obs."' WHERE id=".$id."";}

//echo $cadenasql; exit;


$sqlx = mysql_query($cadenasql, $con_doc);

mysql_close($con_doc);

header('Location: ../detalle_tarjeta.php?id='.$id_rel.'&lista='.$lista.'&tarjeta='.$id);

?>