<?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");

$var_ip = getenv("REMOTE_ADDR");
$var_fecha = date("Y-m-d H:i:s");

$id = $_POST["id"];
$bitacora = $_POST["bitacora"];
$estado = $_POST["estado"];
$obs = $_POST["obs"];

//DATOS DEL ADJUNTO
if (!$_FILES['adjunto']['tmp_name']) {
    $nombre_archivo;
} else {
    $nombre_archivo = rand(10, 1000) . "_" . $_FILES['adjunto']['name'];
}
$tipo_archivo = $_FILES['adjunto']['type'];
$tamano_archivo = $_FILES['adjunto']['size'];

//VALIDA SUBIDA DEL ARCHIVO ADJUNTO Y GUARDA SOLICITUD
if ($tamano_archivo > 50000000) {
    header('Location: ../index.php?msg=La extension o el tamaño de los archivos no es correcta. Se permiten archivos word - excel - power point se permiten archivos de 50 Mb maximo.'); exit;
}
if (move_uploaded_file($_FILES['adjunto']['tmp_name'],  '../adjuntos/' . $nombre_archivo) || !$_FILES['adjunto']['tmp_name']) {

    
    include("conex_doc.php");

    if ($bitacora=="bitacora") {
        $cadenasql = "UPDATE SOLICITUDES SET bitacora = CONCAT('".$var_fecha." * ', '".$var_ip." * ', '".$uname."', ' * CAMBIO ESTADO * ', '".$estado." * ',  'OBS.: ', '".$obs." <br>, ', bitacora), estado = '".$estado."', obs = CONCAT('".$var_fecha." ', '".$uname.": ', '".$obs."'), adjuntos_cierre='".$nombre_archivo ."' where id = ".$id."";
    }
    else {
        $cadenasql = "UPDATE SOLICITUDES SET bitacora = CONCAT('".$var_fecha." * ', '".$var_ip." * ', '".$uname."', ' * CAMBIO ESTADO * ', '".$estado." * ',  'OBS.: ', '".$obs." <br> '), estado = '".$estado."', obs = CONCAT('".$var_fecha." ', '".$uname.": ', '".$obs."'), adjuntos_cierre='".$nombre_archivo ."' where id = ".$id."";
    }
    
    $sqlx = mysql_query($cadenasql, $con_doc);
    
    $sql = "SELECT * FROM SOLICITUDES WHERE id = ".$id."";
    $resultado = mysql_query($sql, $con_doc);
    $data = mysql_fetch_array($resultado);
    
    $id_integrantes=$data["responsable"].','.$data["usuario_solicitud"].','.$data["asignar_a"];
    if($estado=="RECHAZADO"){$id_integrantes=$data["responsable"].','.$data["usuario_solicitud"];}else{$id_integrantes=$data["responsable"].','.$data["usuario_solicitud"].','.$data["asignar_a"];}
    
    
    
    $email_asig = "";
    $sql_email = "SELECT EMAIL_ADDRESS, MOBILE_TELEPHONE FROM APPLPORDB.EMPLOYEES WHERE EMPLOYEE_ID IN (" . $id_integrantes . ")";    
    $resultadoe = mysql_query($sql_email, $con_doc);
    while ($datae = mysql_fetch_array($resultadoe)) {
        $email_asig .=  $datae["EMAIL_ADDRESS"] . ", ";
       }
    $email_integrantes = substr($email_asig, 0, -2);
    
    
    mysql_close($con_doc);
    
    //echo $data["area_n"]; exit;
    
    //$email_integrantes='dolmedo@entel.bo';
    
    
    if ($estado=="EN PROCESO" || $estado=="SIN ASIGNAR") {header('Location: ../missolicitudes.php?msg=SE REALIZO EL CAMBIO DE ESTADO');} 
    elseif($estado=="COMPLETADO" || $estado=="RECHAZADO") {

        
        // 680616,680616,

        if($estado=="COMPLETADO"){
            $colorEstado='background-color:#77dd77;color:#fff';
            $atendido=str_replace('&bull','',$data["asignar_a_n"]);
            $encuesta='<br><br>Por favor ayúdenos a mejorar nuestro servicio de soporte, llenando una pequeña encuesta. Le agradecemos por su tiempo.<br>&#128077 &nbsp&nbsp&nbsp<a href="http://atc/TDI/script/guarda_encuesta.php?id='.$id.'&res=SI">Satisfecho</a><br>&#128078 &nbsp&nbsp&nbsp<a href="http://atc/TDI/script/guarda_encuesta.php?id='.$id.'&res=NO">No Satisfecho </a><p style="font-size: 12px;font-style: italic;"><b>Antes de realizar el voto, por favor debe estar logueado en el Portal</b></p> ';
            $icons='';
            $atendida='atendida';
        }else{
            $colorEstado='background-color:#ff6961;color:#fff';
            $atendidoBitacora=explode('*',$data["bitacora"]);
            $atendido=$atendidoBitacora[2].str_replace('<br>','',str_replace('</p>','',str_replace('<p>','',substr($atendidoBitacora[5],0,-9))));
            $icons='';
            $encuesta="";
            $atendida='rechazada';
        }
        
        $mensaje = '
        <!DOCTYPE html>
        <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
        <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width,initial-scale=1">
          <meta name="x-apple-disable-message-reformatting">
          '.$icons.'
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
                    <td style="padding:10px;text-align:center;font-size:12px;'.$colorEstado.';">
                      <p style="margin:0;font-size:14px;line-height:20px;"> Solicitud '.$estado.' N° ' . $id . '</p>
                      </td>
                    <tr>
                      <td style="padding:30px;background-color:#ffffff;">                       
                        Titulo: <b>' . $data["titulo"] . '</b><br>
                        Tipo de solicitud: <b>' . $data["solicitud_n"] . '</b><br>
                        Solicitud al area: <b>' . $data["area_n"] . '</b><br>
                        Solicitud '.$atendida.' por: <b>' . $atendido . '</b><br>
                        <a href="http://atc/TDI/informe_solicitud.php?id='.$id.'">Su solicitud fue '.$atendida.', favor ingresar a este enlace para verificar</a>
                        '.$encuesta.'
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
    
        // ENVIO EMAIL    
        mail($email_integrantes, 'SOLICITUD TDI '.$data["area_n"] .' N ' . $id. ' '.$estado, $mensaje, $cabeceras);
        //
        
        header('Location: ../missolicitudes_fin.php?msg=SE REALIZO EL CAMBIO DE ESTADO');
    }
    

} 
else {header('Location: ../index.php?msg=Ocurrió algún error al subir el ARCHIVO ADJUNTO. No pudo guardarse.'); exit;}





?>




