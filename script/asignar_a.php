<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");

include("conex.php");

$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");

$id = $_POST["id"];
$asignar_a = $_POST["asignar_a"];
$tipo = $_POST["tipo"];
$bitacora = $_POST["bitacora"];

$asignar_a1 = "";
for ($x = 0; $x < count($asignar_a); $x++)
    $asignar_a1 .= $asignar_a[$x] . ",";
$asignar_a2 = substr($asignar_a1, 0, -1);

$asignar_a_n = "";
$sql = "SELECT DESCRIPTION FROM USERS WHERE USER_ID IN (" . $asignar_a2 . ")";
$resultado = mysql_query($sql, $con);
while ($data = mysql_fetch_array($resultado)) {
    $asignar_a_n .= "&bull; " . $data["DESCRIPTION"] . " <br> ";
}
$asignar_a_n1 = substr($asignar_a_n, 0, -6);
$asignar_a_n2 = str_replace("<br>", "-", $asignar_a_n1);



mysql_close($con);

include("conex_doc.php");

if ($bitacora=="bitacora") {
    if ($tipo=="asignar") {$cadenasql = "UPDATE SOLICITUDES SET bitacora = CONCAT('".$var_fecha." * ', '".$var_ip." * ', '".$uname."', ' * ASIGNAR STAFF * ', '".$asignar_a_n2." <br>, ', bitacora), asignar_a_n = '".$asignar_a_n1."', asignar_a='".$asignar_a2."', estado='EN PROCESO' where id = ".$id."";}
    else {$cadenasql = "UPDATE SOLICITUDES SET bitacora = CONCAT('".$var_fecha." * ', '".$var_ip." * ', '".$uname."', ' * CAMBIAR STAFF * ', '".$asignar_a_n2." <br>, ', bitacora), asignar_a_n = '".$asignar_a_n1."', asignar_a='".$asignar_a2."', estado='EN PROCESO' where id = ".$id."";}
    }
else{
    if ($tipo=="asignar") {$cadenasql = "UPDATE SOLICITUDES SET bitacora = CONCAT('".$var_fecha." * ', '".$var_ip." * ', '".$uname."', ' * ASIGNAR STAFF * ', '".$asignar_a_n2." <br>, '), asignar_a_n = '".$asignar_a_n1."', asignar_a='".$asignar_a2."', estado='EN PROCESO' where id = ".$id."";}
    else {$cadenasql = "UPDATE SOLICITUDES SET bitacora = CONCAT('".$var_fecha." * ', '".$var_ip." * ', '".$uname."', ' * CAMBIAR STAFF * ', '".$asignar_a_n2." <br>, '), asignar_a_n = '".$asignar_a_n1."', asignar_a='".$asignar_a2."', estado='EN PROCESO' where id = ".$id."";}
}

$sqlx = mysql_query($cadenasql, $con_doc);



$sql_datos = "SELECT * FROM SOLICITUDES WHERE id =".$id."";
$resultado_d = mysql_query($sql_datos, $con_doc);
$data_d = mysql_fetch_array($resultado_d);

$email_asig="";
$sql_email = "SELECT EMAIL_ADDRESS FROM APPLPORDB.EMPLOYEES WHERE EMPLOYEE_ID IN (" . $asignar_a2 . ")";
$resultadoe = mysql_query($sql_email, $con_doc);
while ($datae = mysql_fetch_array($resultadoe)) {
    $email_asig .=  $datae["EMAIL_ADDRESS"] . ", ";
}
$email_asig_n=substr($email_asig, 0, -2);

mysql_close($con_doc);

$mensaje = '
        <html>
        <head>
        </head>
        <body>
        <p>SOLICITUD AL AREA: <strong>' . $data_d["area_n"] . '</strong></p>
        <p>TIPO DE SOLICITUD: <strong>' . $data_d["solicitud_n"] . '</strong></p>
        <p>TITULO: <strong>' . $data_d["titulo"] . '</strong></p>
        <p>SOLICITUD N: <strong>' . $data_d["id"] . '</strong></p>
        <p><a href="http://atc/TDI/missolicitudes.php">http://atc/TDI/missolicitudes.php</a></p>
        </body>
        </html>
        ';
        // Cabecera que especifica que es un HMTL
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // Cabeceras adicionales
        $cabeceras .= 'From: SOPORTEAPLICACIONES-DATACOM@entel.bo' . "\r\n";
        //$cabeceras .= 'Cc: archivotarifas@example.com' . "\r\n";
        //$cabeceras .= 'Bcc: copiaoculta@example.com' . "\r\n";

        mail($email_asig, 'SOLICITUD ASIGNADA N ' . $data_d["id"], $mensaje, $cabeceras);



header('Location: ../missolicitudes.php?msg=SE REALIZO LA ASIGNACION');

?>