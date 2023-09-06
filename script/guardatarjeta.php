<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");
include("conex_doc.php");

$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");

$id_rel = $_POST["id_rel"];
$titulo = strtoupper($_POST["titulo"]);
$descripcion = $_POST["descripcion"];
$estado = $_POST["estado"];
$fecha_ini = substr($_POST["reservation"], 0, 10);
$fecha_fin = substr($_POST["reservation"], -10);
$integrantes_solicitud = $_POST["integrantes_solicitud"];
$visible = $_POST["visible"];
$numero = $_POST["numero"];

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

$cadenasql = "INSERT INTO tablero (id_rel, titulo, fecha_ini, fecha_fin, descripcion, estado, usuario_creacion, usuario_creacion_n, ip, fecha_creacion, obs, bitacora, integrantes, integrantes_n, visible, numero) VALUES ($id_rel, '".$titulo."', '".$fecha_ini."', '".$fecha_fin."', '".$descripcion."', '".$estado."', '".$dpilogin."', '".$uname."', '".$var_ip."', '".$var_fecha."', '', '* ".$var_fecha.": ".$uname." CREO LA TARJETA', '".$integrantes_solicitud2."', '".$integrantes_solicitud_n1."', '".$visible."',$numero)";
//echo $cadenasql; exit;
$sqlx = mysql_query($cadenasql, $con_doc);




//$solicitud_generada=null;
if ($_POST["titulo_s"]){

    $sql_ta = "SELECT MAX(id) as tar FROM tablero WHERE usuario_creacion = '" . $dpilogin . "'";
    $resultado_ta = mysql_query($sql_ta, $con_doc);
    $data_ta = mysql_fetch_array($resultado_ta);

    $area_1 = $_POST["area"];
    $area_2 = explode(",", $area_1);
    $area = $area_2[0];
    $area_n = $area_2[1];

    $solicitud_1 = $_POST["solicitud"];
    $solicitud_2 = explode(",", $solicitud_1);
    $solicitud = $solicitud_2[0];
    $solicitud_n = $solicitud_2[1];

    $titulo_n_1 = $_POST["titulo_s"];
    $titulo_n_2 = explode(",", $titulo_n_1);
    $titulo_n = $titulo_n_2[0];
    $titulo_n_n = $titulo_n_2[1];
    //$titulo = $titulo_n_n;


    //echo $id_rel; exit;
    $sql_s="SELECT * FROM SOLICITUDES WHERE id=".$id_rel."";
    $resultado_s = mysql_query($sql_s, $con_doc);
    $data_s = mysql_fetch_array($resultado_s);

    $asignar_a2="";
    $asignar_a_n1="";
    $estado = "SIN ASIGNAR";
    $var_ip = getenv("REMOTE_ADDR");
    $var_fecha = date("Y-m-d H:i:s");
    $responsab = $data_s["responsable"].",".$dpilogin;
    $responsab_n = $data_s["responsable_n"]." <br> &bull; ".$uname;
    $detalle_new = "<p>Solicitud encaminada del N°: ".$id_rel." por: ".$uname." </p><p> Obs: ".$descripcion."</p> <p>Detalle de la solicitud original: ".$data_s["detalle"]."</p>";
    
    $cadenasql = "INSERT INTO SOLICITUDES (area, area_n, solicitud, solicitud_n, titulo, campo1, campo2, detalle, responsable, responsable_n, asignar_a, asignar_a_n, adjunto, estado, usuario_solicitud, usuario_solicitud_n, departamento_usuario_solicitud, fecha_solicitud, ip, id_solicitud_padre, id_tarea_padre ) VALUES (" . $area . ", '" . $area_n . "', " . $solicitud . ", '" . $solicitud_n . "', '" . strtoupper($titulo_n_n) . "', '" . $campo1 . "', '" . $campo2 . "', '" . $detalle_new . "', '" . $responsab. "', '" . $responsab_n. "', '" . $asignar_a2 . "', '" . $asignar_a_n1 . "', '" . $data_s["adjunto"] . "', '" . $estado . "', '" . $data_s["usuario_solicitud"] . "', '" . $data_s["usuario_solicitud_n"] . "', '" . $data_s["departamento_usuario_solicitud"] . "','" . $var_fecha . "', '" . $var_ip . "', ".$id_rel.", ".$data_ta["tar"].")";
    $sqlx = mysql_query($cadenasql, $con_doc);

    $sql_sol = "SELECT id as sol  FROM SOLICITUDES WHERE id_tarea_padre = ".$data_ta["tar"]."";
    $resultado_sol = mysql_query($sql_sol, $con_doc);
    $data_sol = mysql_fetch_array($resultado_sol);

    $sql_act = "UPDATE tablero SET solicitud_generada = ".$data_sol["sol"]." WHERE id=".$data_ta["tar"].";";
    $resul_act = mysql_query($sql_act, $con_doc);

    $dep="";

        if ($data_s["departamento_usuario_solicitud"]=="1"){$dep="LA PAZ";}
        if ($data_s["departamento_usuario_solicitud"]=="2"){$dep="SANTA CRUZ";}
        if ($data_s["departamento_usuario_solicitud"]=="3"){$dep="COCHABAMBA";}
        if ($data_s["departamento_usuario_solicitud"]=="4"){$dep="ORURO";}
        if ($data_s["departamento_usuario_solicitud"]=="5"){$dep="POTOSI";}
        if ($data_s["departamento_usuario_solicitud"]=="6"){$dep="TARIJA";}
        if ($data_s["departamento_usuario_solicitud"]=="7"){$dep="BENI";}
        if ($data_s["departamento_usuario_solicitud"]=="8"){$dep="PANDO";}
        if ($data_s["departamento_usuario_solicitud"]=="9"){$dep="CHUQUISACA";}

    $mensaje = '
    <html>
    <head>
    </head>
    <body>
    <p>SOLICITUD ENCAMINADA POR: <strong>' . $uname . '</strong></p>
    <p>SOLICITUD INICIAL: <strong>' . $data_sol["sol"] . '</strong></p>
    <p>SOLICITUD CREADA DE LA CIUDAD: : <strong>' . $dep . '</strong></p>
    <p>SOLICITUD AL AREA: <strong>' . $area_n . '</strong></p>
    <p>TIPO DE SOLICITUD: <strong>' . $solicitud_n . '</strong></p>
    <p>TITULO: <strong>' . $titulo . '</strong></p>
    <p>SOLICITUD N: <strong>' . $data_sol["sol"] . '</strong></p>
    <p><a href="http://atc/TDI/missolicitudes.php">http://atc/TDI/missolicitudes.php</a></p>
    </body>
    </html>
    ';
    // Cabecera que especifica que es un HMTL
    $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
    //$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    // Cabeceras adicionales
    $cabeceras .= 'From: SOPORTEAPLICACIONES-DATACOM@entel.bo' . "\r\n";
    //$cabeceras .= 'Cc: archivotarifas@example.com' . "\r\n";
    //$cabeceras .= 'Bcc: copiaoculta@example.com' . "\r\n";

    if ($area=="1") {$email_area=", SOPORTEAPLICACIONES-DATACOM@entel.bo, SOPORTEAPLICACIONES-DATACOM-N@entel.bo"; }
    elseif ($area=="2") {$email_area=", SoportePBX-IVR-DATACOM@entel.bo, SoportePBX-IVR-DATACOM-N@entel.bo"; }
    elseif ($area=="3") {$email_area=", Soporte-microinformatica-datacom@entel.bo, Soporte-microinformatica-datacom-N@entel.bo"; }
    elseif ($area=="4") {$email_area=", SOPORTE_WFO_DATACOM@entel.bo";}

    // ENVIO EMAIL
    mail('mmorales@entel.bo, ' . $email_area.', '.$correo_t, 'SOLICITUD ENCAMINADA TDI '.$area_n .' N ' . $data_sol["sol"], $mensaje, $cabeceras);
    //      

}


mysql_close($con_doc);

header('Location: ../tablero.php?id='.$id_rel.'');

?>