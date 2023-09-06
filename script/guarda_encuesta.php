<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");
include("conex_doc.php");

$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");
$id=$_GET["id"];
$res=$_GET["res"];


$sql = "SELECT * FROM SOLICITUDES  WHERE responsable like '%".$dpilogin."%' AND id=".$id."";
$resultado = mysql_query($sql, $con_doc);
$data = mysql_fetch_array($resultado);
$id_res=$data["id"];
$voto=$data["satisfaccion"];
$usuario_voto=$data["usuario_voto"];



if(strlen($id_res)>0){
    if($voto=='SI' || $voto=='NO'){
        header('Location: ../index.php?msg=Ya existe un voto registrado por '.$usuario_voto.''); exit();
    }else{        
        $cadenasql = "UPDATE SOLICITUDES SET satisfaccion = '".$res."', usuario_voto='".$uname ."', fecha_voto='".$var_fecha."', ip_voto = '".$var_ip."' where id = ".$id."";
        $sqlx = mysql_query($cadenasql, $con_doc);
        header('Location: ../index.php?msg=Gracias por registrar su voto');exit();
    }

}else{ 
    header('Location: ../index.php?msg=Debe ser un responsable de la solicitud para poder realizar el voto');exit();
}




mysql_close($con_doc);



// if (!$_POST["date_fin"]) {header('Location: ../bitacoras_activas.php');} else {header('Location: ../bitacoras_cerradas.php');}


?>