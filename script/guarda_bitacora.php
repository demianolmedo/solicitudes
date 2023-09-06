<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");
include("conex_doc.php");

$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");


$titulo = strtoupper($_POST["titulo"]);
$descripcion = $_POST["descripcion"];
$date_ini = $_POST["date_ini"];
$date_fin = $_POST["date_fin"];

if (!$_POST["date_fin"]) {$cadenasql = "INSERT INTO BITACORAS (titulo, descripcion, date_ini, usuario_carga, usuario_carga_n, fecha_carga, ip) VALUES ('".$titulo."', '".$descripcion."', '".$date_ini."', '".$dpilogin."', '".$uname."', '".$var_fecha."', '".$var_ip."')";}
else {$cadenasql = "INSERT INTO BITACORAS (titulo, descripcion, date_ini, date_fin, usuario_carga, usuario_carga_n, fecha_carga, ip) VALUES ('".$titulo."', '".$descripcion."', '".$date_ini."', '".$date_fin."', '".$dpilogin."', '".$uname."', '".$var_fecha."', '".$var_ip."')";}

//echo $cadenasql; exit;
$sqlx = mysql_query($cadenasql, $con_doc);

mysql_close($con_doc);

if (!$_POST["date_fin"]) {header('Location: ../bitacoras_activas.php');} else {header('Location: ../bitacoras_cerradas.php');}


?>