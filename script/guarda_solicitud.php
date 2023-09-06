<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");

include("conex.php");


$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");

//DATOS DEL POST
$area_1 = $_POST["area"];
$area_2 = explode(",", $area_1);
$area = $area_2[0];
$area_n = $area_2[1];


$solicitud_1 = $_POST["solicitud"];
$solicitud_2 = explode(",", $solicitud_1);
$solicitud = $solicitud_2[0];
$solicitud_n = $solicitud_2[1];

$titulo_n_1 = $_POST["titulo"];
$titulo_n_2 = explode(",", $titulo_n_1);
$titulo_n = $titulo_n_2[0];
$titulo_n_n = $titulo_n_2[1];



$titulo = $titulo_n_n;
$detalle = $_POST["detalle"];
$responsable = $_POST["responsable"];
$asignar_a = $_POST["asignar_a"];
$campo1 = $_POST["campo1"];
$campo2 = $_POST["campo2"];
$sms = $_POST["sms"];



if ($sms=="SI") {
    $titulo_pbx1 = $_POST["titulo"];
    $titulo_pbx2 = explode(",", $titulo_pbx1);
    $titulo_pbx = $titulo_pbx2[0];
    $titulo = $titulo_pbx2[1];
    $titulo_pbx_sms = $titulo_pbx2[2];
}



//DATOS DEL ADJUNTO
if (!$_FILES['adjunto']['tmp_name']) {
    $nombre_archivo;
} else {
    $nombre_archivo = rand(10, 1000) . "_" . $_FILES['adjunto']['name'];
}
$tipo_archivo = $_FILES['adjunto']['type'];
$tamano_archivo = $_FILES['adjunto']['size'];



$responsable1 = "";
for ($x = 0; $x < count($responsable); $x++)
    $responsable1 .= $responsable[$x] . ",";
$responsable2 = substr($responsable1, 0, -1);



$asignar_a1 = "";
for ($x = 0; $x < count($asignar_a); $x++)
    $asignar_a1 .= $asignar_a[$x] . ",";
$asignar_a2 = substr($asignar_a1, 0, -1);

$responsable_n = "";
$sql = "SELECT DESCRIPTION FROM USERS WHERE USER_ID IN (" . $responsable2 . ")";
$resultado = mysql_query($sql, $con);
while ($data = mysql_fetch_array($resultado)) {    
    $responsable_n .= "&bull; " . $data["DESCRIPTION"] . " <br> ";
}
$responsable_n1 = substr($responsable_n, 0, -6);

//  echo $responsable_n1; exit;

$asignar_a_n = "";
$sql = "SELECT DESCRIPTION FROM USERS WHERE USER_ID IN (" . $asignar_a2 . ")";
$resultado = mysql_query($sql, $con);
while ($data = mysql_fetch_array($resultado)) {
    $asignar_a_n .= "&bull; " . $data["DESCRIPTION"] . " <br> ";
}
$asignar_a_n1 = substr($asignar_a_n, 0, -6);

$sql = "SELECT A.FULL_NAME, A.MOBILE_TELEPHONE, C.departamento  FROM EMPLOYEES A JOIN USERS B ON A.EMPLOYEE_ID=B.USER_ID JOIN L2_DEPARTAMENTO C ON B.L2_DEPARTAMENTO=C.id_auto WHERE A.EMPLOYEE_ID = ".$dpilogin."";
$resultado = mysql_query($sql, $con);
$data = mysql_fetch_array($resultado);
$nombre_crea=$data["FULL_NAME"];
$cel_crea=$data["MOBILE_TELEPHONE"];
$depa_crea=$data["departamento"];

$sql_phone = "SELECT MOBILE_TELEPHONE, PERSONAL_CELLPHONE, WORK_TELEPHONE FROM EMPLOYEES  WHERE EMPLOYEE_ID = ".$dpilogin."";
$resultado_phone = mysql_query($sql_phone, $con);
$data_phone = mysql_fetch_array($resultado_phone);
$personal_phone=$data_phone["PERSONAL_CELLPHONE"];
$mobile_phone=$data_phone["MOBILE_TELEPHONE"];
$work_phone=$data_phone["WORK_TELEPHONE"];

include("conex_doc.php");
$correo_t="";
$sql0 = "SELECT * FROM LISTA WHERE id=".$titulo_n."";
$resultado0 = mysql_query($sql0, $con_doc);
$data0 = mysql_fetch_array($resultado0);
$sms_t=$data0["sms"];
$correo_t=$data0["correo"];
$plantilla_t=$data0["plantilla"];



//mysql_close($con);

if (!$asignar_a) {
    $estado = "SIN ASIGNAR";
} else {
    $estado = "EN PROCESO";
}

//echo $correo_t; exit;

require_once("../../sms_dpi/libreria_sms.php");

//VALIDA SUBIDA DEL ARCHIVO ADJUNTO Y GUARDA SOLICITUD
if ($tamano_archivo > 50000000) {
    header('Location: ../index.php?msg=La extension o el tamaño de los archivos no es correcta. Se permiten archivos word - excel - power point se permiten archivos de 50 Mb maximo.');
} else {
    include("conex_doc.php");
    if (move_uploaded_file($_FILES['adjunto']['tmp_name'],  '../adjuntos/' . $nombre_archivo) || !$_FILES['adjunto']['tmp_name']) {
        $cadenasql = "INSERT INTO SOLICITUDES (area, area_n, solicitud, solicitud_n, titulo, campo1, campo2, detalle, responsable, responsable_n, asignar_a, asignar_a_n, adjunto, estado, usuario_solicitud, usuario_solicitud_n, departamento_usuario_solicitud, fecha_solicitud, ip ) VALUES (" . $area . ", '" . $area_n . "', " . $solicitud . ", '" . $solicitud_n . "', '" . $titulo . "', '" . $campo1 . "', '" . $campo2 . "', '" . $detalle . "', '" . $responsable2 . "', '" . $responsable_n1 . "', '" . $asignar_a2 . "', '" . $asignar_a_n1 . "', '" . $nombre_archivo . "', '" . $estado . "', '" . $dpilogin . "', '" . $uname . "  TC: ".$mobile_phone." TP: ".$personal_phone." TT: ".$work_phone."', '" . $l2_departamento . "','" . $var_fecha . "', '" . $var_ip . "')";
        $sqlx = mysql_query($cadenasql, $con_doc);

        $sql = "SELECT MAX(id) as sol, departamento_usuario_solicitud  FROM SOLICITUDES WHERE usuario_solicitud = '" . $dpilogin . "'";
        $resultado = mysql_query($sql, $con_doc);
        $data = mysql_fetch_array($resultado);

        $email_asig = "";
        $sql_email = "SELECT EMAIL_ADDRESS, MOBILE_TELEPHONE FROM APPLPORDB.EMPLOYEES WHERE EMPLOYEE_ID IN (" . $asignar_a2 . ")";    
        $resultadoe = mysql_query($sql_email, $con_doc);
        while ($datae = mysql_fetch_array($resultadoe)) {
            $email_asig .=  $datae["EMAIL_ADDRESS"] . ", ";
            $sin_asignar="NO";
            //ENVIO SMS
            if ($titulo_pbx_sms=="SI") {envio_sms_ami("SOLICITUD TDI", $datae["MOBILE_TELEPHONE"], "SOL. ASIGNADA - ".$solicitud_n." - ".$titulo." - N: ".$data["sol"]." DE: ".$nombre_crea." CEL: ".$cel_crea." - ".$depa_crea ); }
        }
        $email_asig_n = substr($email_asig, 0, -2);

       

        mysql_close($con_doc);
        $dep="";

        if ($data["departamento_usuario_solicitud"]=="1"){$dep="LA PAZ";}
        if ($data["departamento_usuario_solicitud"]=="2"){$dep="SANTA CRUZ";}
        if ($data["departamento_usuario_solicitud"]=="3"){$dep="COCHABAMBA";}
        if ($data["departamento_usuario_solicitud"]=="4"){$dep="ORURO";}
        if ($data["departamento_usuario_solicitud"]=="5"){$dep="POTOSI";}
        if ($data["departamento_usuario_solicitud"]=="6"){$dep="TARIJA";}
        if ($data["departamento_usuario_solicitud"]=="7"){$dep="BENI";}
        if ($data["departamento_usuario_solicitud"]=="8"){$dep="PANDO";}
        if ($data["departamento_usuario_solicitud"]=="9"){$dep="CHUQUISACA";}

        $mensaje = '
        <!DOCTYPE html>
        <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
        <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width,initial-scale=1">
          <meta name="x-apple-disable-message-reformatting">
          <title></title>
          <style>
            table, td, div, h1, p {
              font-family: Arial, sans-serif;
            }
          </style>
        </head>
        <body style="margin:0;padding:0;word-spacing:normal;background-color:#fff;">
          <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#f2f2f2;">
            <table role="presentation" style="width:100%;border:none; border-spacing:0;">
              <tr>
                <td align="center" style="padding:0;"  >
                  <table role="presentation" style="width:94%;max-width:950px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:13px;line-height:22px;color:#363636;" >
                    <td style="padding:10px;text-align:center;font-size:12px;background-color:#84b6f4;color:#fff;">
                      <p style="margin:0;font-size:14px;line-height:20px;"> Nueva Solicitud N° ' . $data["sol"] . '</p>
                      </td>
                    <tr>
                      <td style="padding:30px;background-color:#ffffff;">                       
                        Titulo: <b>' . $titulo . '</b><br>
                        Tipo de solicitud: <b>' . $solicitud_n . '</b><br>
                        Solicitud al area: <b>' . $area_n . '</b><br>
                        Solicitud creada de la ciudad: <b>' . $dep . '</b><br>
                        <a href="http://atc/TDI/missolicitudes.php">Ver mis Solicitudes</a>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:10px;text-align:center;font-size:12px;background-color:#04a7e2;color:#fff;">
                        <p style="margin:0;font-size:14px;line-height:20px;">  DATACOM </p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </div>
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

        if ($area=="1") {$email_area=", SOPORTEAPLICACIONES-DATACOM@entel.bo, SOPORTEAPLICACIONES-DATACOM-N@entel.bo"; $sms_area="SELECT B.MOBILE_TELEPHONE FROM APPLPORDB.USERS A INNER JOIN APPLPORDB.EMPLOYEES B ON A.USER_ID=B.EMPLOYEE_ID  WHERE A.L6_PLATAFORMA = '0' AND A.END_DATE ='0000-00-00 00:00:00' AND A.L5_UNIDAD = '22' AND A.L3_AREA = '30' AND (A.L7_CARGO = '29' OR A.L7_CARGO = '29')";}
        elseif ($area=="2") {$email_area=", SoportePBX-IVR-DATACOM@entel.bo, SoportePBX-IVR-DATACOM-N@entel.bo"; $sms_area="SELECT B.MOBILE_TELEPHONE FROM APPLPORDB.USERS A INNER JOIN APPLPORDB.EMPLOYEES B ON A.USER_ID=B.EMPLOYEE_ID  WHERE (A.L6_PLATAFORMA = '0' OR A.L6_PLATAFORMA = '357') AND A.END_DATE ='0000-00-00 00:00:00' AND A.L5_UNIDAD = '22' AND A.L3_AREA = '31' AND (A.L7_CARGO = '29' OR A.L7_CARGO = '75')";}
        elseif ($area=="3") {$email_area=", Soporte-microinformatica-datacom@entel.bo, Soporte-microinformatica-datacom-N@entel.bo"; $sms_area="SELECT B.MOBILE_TELEPHONE FROM APPLPORDB.USERS A INNER JOIN APPLPORDB.EMPLOYEES B ON A.USER_ID=B.EMPLOYEE_ID  WHERE (A.L6_PLATAFORMA = '0' OR A.L6_PLATAFORMA = '364') AND A.END_DATE ='0000-00-00 00:00:00' AND (A.L5_UNIDAD = '22' OR A.L5_UNIDAD = '35') AND (A.L3_AREA = '43' OR A.L3_AREA = '17') AND (A.L7_CARGO = '29' OR A.L7_CARGO = '12')";}
        elseif ($area=="4") {$email_area=", SOPORTE_WFO_DATACOM@entel.bo"; $sms_area="SELECT B.MOBILE_TELEPHONE FROM APPLPORDB.USERS A INNER JOIN APPLPORDB.EMPLOYEES B ON A.USER_ID=B.EMPLOYEE_ID  WHERE (A.L6_PLATAFORMA = '0' OR A.L6_PLATAFORMA = '371') AND A.END_DATE ='0000-00-00 00:00:00' AND A.L5_UNIDAD = '35' AND A.L3_AREA = '50' AND A.L7_CARGO = '75'";}

        //ENVIO SMS
        if ($titulo_pbx_sms=="SI" AND $sin_asignar != "NO") {
        $resultadosms = mysql_query($sms_area, $con_doc);
        while ($datasms = mysql_fetch_array($resultadosms)) {
            envio_sms_ami("SOLICITUD TDI", $datasms["MOBILE_TELEPHONE"], "SOL. TDI - ".$solicitud_n." - ".$titulo." - N: ".$data["sol"]." DE: ".$nombre_crea." CEL: ".$cel_crea." - ".$depa_crea ); 
            }
        }

        // ENVIO EMAIL
        if ( $sin_asignar == "NO") {mail('mmorales@entel.bo, ' . $email_asig_n, 'SOLICITUD ASIGNADA N ' . $data["sol"], $mensaje, $cabeceras);}
        else {mail('mmorales@entel.bo, ' . $email_area.', '.$correo_t, 'SOLICITUD TDI '.$area_n .' - '.$solicitud_n.' N ' . $data["sol"], $mensaje, $cabeceras);}
        //
        

                // ENVIO SMS Y ALERTA DE SOPORTE 

                function esFinDeSemana($fecha) {
                    // Convierte la fecha en formato Unix timestamp (número de segundos desde 1970-01-01)
                    $timestamp = strtotime($fecha);        
                    // Obtiene el número de día de la semana (1 para lunes, 2 para martes, etc.)
                    $numeroDiaSemana = date('N', $timestamp);        
                    // Retorna true si es sábado (6) o domingo (7), de lo contrario, retorna false
                    return ($numeroDiaSemana == 6 || $numeroDiaSemana == 7);
                }

        
                $datosSoporte="";
                $soporte="SELECT T.integrantes,  E.MOBILE_TELEPHONE, REPLACE(T.integrantes_n, '&bull;', '') nombre FROM tablero T JOIN SOLICITUDES S ON T.id_rel=S.id JOIN  APPLPORDB.EMPLOYEES E ON E.EMPLOYEE_ID IN (T.integrantes) where ((id_rel in ('2070', '2078')) and (DATE_FORMAT(NOW(), '%Y-%m-%d') BETWEEN fecha_ini and fecha_fin) and (S.area=".$area."));";
                $resultadoSoporte = mysql_query($soporte, $con_doc);
                while ($dataSmsSoporte = mysql_fetch_array($resultadoSoporte)) {
                    $datosSoporte .= $dataSmsSoporte["nombre"]." ".$dataSmsSoporte["MOBILE_TELEPHONE"]." ";
                    if (esFinDeSemana(date('Y-m-d'))) {
                        $posicion_http = strpos($titulo, ':');
                        
                        if ($posicion_http !== false) {$texto_extraido = substr($titulo, 0, $posicion_http-5);
                        }else{$texto_extraido=$titulo;}
                        envio_sms_ami("SOLICITUD TDI", $dataSmsSoporte["MOBILE_TELEPHONE"], "SOL. TDI - ".substr($solicitud_n,0,10)." - ".$texto_extraido." - N: ".$data["sol"]." DE: ".$nombre_crea."  ".$cel_crea."  ".$depa_crea ); 
                    }
                   
                }
                                
        

                $datosSoporteAlert="";
                if($datosSoporte>=""){

                    $feriado="SELECT * FROM bdd_horarios_2009.dias_festivos where fecha=CURDATE()";
                    $resultadoFeriado = mysql_query($feriado, $con_doc);                      
                    $dataFeriado = mysql_fetch_array($resultadoFeriado);                

                    if((date('H:i')>='19:00' && date('H:i')<'08:00') || esFinDeSemana(date('Y-m-d')) || $dataFeriado["tipo"]=='FERIADO'){
                        $datosSoporteAlert=",SERA ATENDIDA EN HORARIO LABORAL - SOLO CASOS DE EMERGENCIAS CONTACTAR A: ".$datosSoporte;
                    }
                    else{$datosSoporteAlert=",Personal de Soporte: ".$datosSoporte;}
                }
                $msg1='SE REALIZO LA SOLICITUD DOC N°. ' . $data["sol"].$datosSoporteAlert;
                $msgAlert='Location: ../index.php?msg='.$msg1;  
                // $msgAlert='Location: ../index.php?msg=SE REALIZO LA SOLICITUD DOC N°. ' . $data["sol"];    
        
                header($msgAlert);

        

        //header('Location: ../index.php?msg=SE REALIZO LA SOLICITUD DOC N°. ' . $data["sol"]);
    } else {
        header('Location: ../index.php?msg=Ocurrió algún error al subir el ARCHIVO ADJUNTO. No pudo guardarse.');
    }
}
