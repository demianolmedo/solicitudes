<?php

// PARA SELECCIONAR SOLICITUDES
// PARA MONSTRAR STAFF Y ASIGNAR SOLICITUDES
// PARA SELECCIONAR AREA
// PARA MIS SOLICITUDES
// PARA MIS SOLICITUDES FINNALIZADAS
// VISTAS DEL MENU
// TABLERO TAREAS / INFORME_SOLICITUD SOLO PUEDEN VER LOS MIEMBROS DE LA SOLICITUD
// MIS SOLICITUDES ASIGNAR STAFF
// AGREGAR TARJETA EN TABLERO DE TAREAS / VER DETALLE
// MENU BITACORAS


//if ((($l7_cargo == "16" OR $l7_cargo == "74") and ($l3_area == "17" || $l3_area == "17")) or ($dpilogin == "680616") or ($dpilogin == "4890")) {
// if ((($l7_cargo == "16" OR $l7_cargo == "74") and ($l3_area == "17" || $l3_area == "17"))  or ($dpilogin == "4890") or ($dpilogin == "1014")) {
//     $perfil = '';
//     $asignar_a = '';
//     $sql_area = "SELECT * FROM AREA ORDER BY id";
//     $sql_missolicitudes = "SELECT * FROM SOLICITUDES WHERE ESTADO IN ('SIN ASIGNAR', 'EN PROCESO')";
//     //$sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE ESTADO IN ('COMPLETADO', 'RECHAZADO')";
//     $sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE (estado = 'COMPLETADO' OR estado = 'RECHAZADO') AND STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d %T') > DATE_SUB(NOW(),INTERVAL 24 HOUR) ORDER BY STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d %T') DESC    ";
//     $menu_reportes="SI";
//     $sql_tablero="SELECT * FROM SOLICITUDES WHERE id=".$_REQUEST["id"]."";
//     $asignar_a_n_p="staff";
//     $acceso_agregar_tarjeta="SI";
//     $menu_bitacora="SI";

// } else {

//     if ($l3_area=="30"){$area=1;};
//     if ($l3_area=="31"){$area=2;};
//     if ($l3_area=="43" || $l3_area=="17"){$area=3;};
//     if ($l3_area=="50"){$area=4;};

//     $perfil = 'AND perfil="U"';
//     if (($l5_unidad=='22' || $l5_unidad=="35") && ($l7_cargo=='29' || $l7_cargo=='12' || $l7_cargo=='75' || $l7_cargo=='74')) {$asignar_a = '';} else {$asignar_a = 'disabled  style="display: none;"';}
//     //$asignar_a = 'disabled  style="display: none;"';
//     $sql_area = "SELECT * FROM AREA WHERE perfil='U'";
//     if (($l5_unidad=='22' || $l5_unidad=="35") && ($l7_cargo=='29' || $l7_cargo=='12' || $l7_cargo=='75' || $l7_cargo=='74')) {$sql_missolicitudes = "SELECT * FROM SOLICITUDES WHERE (asignar_a_n LIKE '%".$uname."%' OR asignar_a='' OR usuario_solicitud = '$dpilogin' OR responsable LIKE '%".$dpilogin."%') AND ESTADO IN ('SIN ASIGNAR', 'EN PROCESO') AND area=".$area."";} else {$sql_missolicitudes = "SELECT * FROM SOLICITUDES WHERE (usuario_solicitud = '$dpilogin' OR responsable LIKE '%".$dpilogin."%') AND ESTADO IN ('SIN ASIGNAR', 'EN PROCESO')";}
//     //if (($l5_unidad=='22' || $l5_unidad=="35") && ($l7_cargo=='29' || $l7_cargo=='12' || $l7_cargo=='75' || $l7_cargo=='74')) {$sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE (asignar_a_n LIKE '%".$uname."%' OR asignar_a='' OR usuario_solicitud = '$dpilogin' OR responsable LIKE '%".$dpilogin."%') AND ESTADO IN ('COMPLETADO', 'RECHAZADO')";} else {$sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE (usuario_solicitud = '$dpilogin' OR responsable LIKE '%".$dpilogin."%') AND ESTADO IN ('COMPLETADO', 'RECHAZADO')";}
//     if (($l5_unidad=='22' || $l5_unidad=="35") && ($l7_cargo=='29' || $l7_cargo=='12' || $l7_cargo=='75' || $l7_cargo=='74')) 
//         {
//             if (isset($_POST["reservation"])) {$sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE   ESTADO IN ('COMPLETADO', 'RECHAZADO') AND area=".$area."  AND SUBSTRING(OBS, 1, 10) >= '".substr($_POST["reservation"], 0, 10)."' AND SUBSTRING(OBS, 1, 10) <= '".substr($_POST["reservation"], -10)."' ORDER BY STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d %T') DESC"; $titulo_fin="del ".$_POST["reservation"];}
//             else {$sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE ESTADO IN ('COMPLETADO', 'RECHAZADO') AND area=".$area."  AND STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d %T') > DATE_SUB(NOW(),INTERVAL 24 HOUR) ORDER BY STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d %T') DESC"; $titulo_fin="Ultimas 24Hrs";}
//         } 
//         else 
//         {$sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE (usuario_solicitud = '$dpilogin' OR responsable LIKE '%".$dpilogin."%') AND ESTADO IN ('COMPLETADO', 'RECHAZADO')";}
//     $menu_reportes="SI";
//     //$sql_tablero="SELECT * FROM SOLICITUDES WHERE id=".$_REQUEST["id"]." AND (asignar_a_n LIKE '%".$uname."%' OR responsable_n LIKE '%".$uname."%' OR usuario_solicitud_n LIKE '%".$uname."%')";
//     $sql_tablero="SELECT * FROM SOLICITUDES WHERE id=".$_REQUEST["id"]."";
//     if (($l5_unidad=='22' || $l5_unidad=="35") && ($l7_cargo=='29' || $l7_cargo=='12' || $l7_cargo=='75' || $l7_cargo=='74')) {$asignar_a_n_p="staff";} else {$asignar_a_n_p="";}
//     if (($l5_unidad=='22' || $l5_unidad=="35") && ($l7_cargo=='29' || $l7_cargo=='12' || $l7_cargo=='75' || $l7_cargo=='74')) {$acceso_agregar_tarjeta="SI";} else {$acceso_agregar_tarjeta="SI";}
//     if (($l5_unidad=='22' || $l5_unidad=="35") && ($l7_cargo=='29' || $l7_cargo=='12' || $l7_cargo=='75' || $l7_cargo=='74')) {$menu_bitacora="SI";} else {$menu_bitacora="NO";}
// }


// // STAFF TDI POR CADA AREA PARA EL SELECT 

//     $sql_desa = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USERS WHERE L6_PLATAFORMA = '0' AND END_DATE ='0000-00-00 00:00:00' AND L5_UNIDAD = '22' AND (L3_AREA = '30' OR L3_AREA = '17') AND (L7_CARGO = '29' OR L7_CARGO = '29' OR L7_CARGO = '75')";
//     $sql_pbx = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USERS WHERE (L6_PLATAFORMA = '0' OR L6_PLATAFORMA = '357') AND END_DATE ='0000-00-00 00:00:00' AND L5_UNIDAD = '22' AND L3_AREA = '31' AND (L7_CARGO = '29' OR L7_CARGO = '75')";
//     $sql_micro = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USERS WHERE (L6_PLATAFORMA = '0' OR L6_PLATAFORMA = '364') AND END_DATE ='0000-00-00 00:00:00' AND (L5_UNIDAD = '22' OR L5_UNIDAD = '35') AND (L3_AREA = '43' OR L3_AREA = '17') AND (L7_CARGO = '29' OR L7_CARGO = '12')";
//     $sql_tdi = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USERS WHERE (L6_PLATAFORMA = '0' OR L6_PLATAFORMA = '364'  OR L6_PLATAFORMA = '371') AND END_DATE ='0000-00-00 00:00:00' AND (L7_CARGO = '29' OR L7_CARGO = '12' OR L7_CARGO = '75' OR L7_CARGO = '74')";
//     $sql_wfo = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USERS WHERE L6_PLATAFORMA = '371' AND END_DATE ='0000-00-00 00:00:00' AND L5_UNIDAD = '35' AND L3_AREA = '50' AND L7_CARGO = '75'";


// NUEVA ESTRUCCTURA

// PLATAFORMA 
// DESARROLLO     378
// PBX/IVR        357
// SOPORTE        364
// SISTEMAS WFO    371

if ($l7_cargo == "112" OR $l7_cargo == "90") {
    $perfil = '';
    $asignar_a = '';
    $sql_area = "SELECT * FROM AREA ORDER BY id";
    $sql_missolicitudes = "SELECT * FROM SOLICITUDES WHERE ESTADO IN ('SIN ASIGNAR', 'EN PROCESO')";
    //$sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE ESTADO IN ('COMPLETADO', 'RECHAZADO')";
    $sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE (estado = 'COMPLETADO' OR estado = 'RECHAZADO') AND STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d %T') > DATE_SUB(NOW(),INTERVAL 24 HOUR) ORDER BY STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d %T') DESC    ";
    $menu_reportes="SI";
    $sql_tablero="SELECT * FROM SOLICITUDES WHERE id=".$_REQUEST["id"]."";
    $asignar_a_n_p="staff";
    $acceso_agregar_tarjeta="SI";
    $menu_bitacora="SI";

} else {

    if ($userLevel=="378"){$area=1;};
    if ($userLevel=="357"){$area=2;};
    if ($userLevel=="364"){$area=3;};
    if ($userLevel=="371"){$area=4;};

    $perfil = 'AND perfil="U"';
    if ($userLevel=='378' || $userLevel=='357' || $userLevel=='364' || $userLevel=='371') {$asignar_a = '';} else {$asignar_a = 'disabled  style="display: none;"';}

    $sql_area = "SELECT * FROM AREA WHERE perfil='U'";
    if ($userLevel=='378' || $userLevel=='357' || $userLevel=='364' || $userLevel=='371') {$sql_missolicitudes = "SELECT * FROM SOLICITUDES WHERE (asignar_a_n LIKE '%".$uname."%' OR asignar_a='' OR usuario_solicitud = '$dpilogin' OR responsable LIKE '%".$dpilogin."%') AND ESTADO IN ('SIN ASIGNAR', 'EN PROCESO') AND area=".$area."";} else {$sql_missolicitudes = "SELECT * FROM SOLICITUDES WHERE (usuario_solicitud = '$dpilogin' OR responsable LIKE '%".$dpilogin."%') AND ESTADO IN ('SIN ASIGNAR', 'EN PROCESO')";}
    if ($userLevel=='378' || $userLevel=='357' || $userLevel=='364' || $userLevel=='371') 
        {
            if (isset($_POST["reservation"])) {$sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE   ESTADO IN ('COMPLETADO', 'RECHAZADO') AND area=".$area."  AND SUBSTRING(OBS, 1, 10) >= '".substr($_POST["reservation"], 0, 10)."' AND SUBSTRING(OBS, 1, 10) <= '".substr($_POST["reservation"], -10)."' ORDER BY STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d %T') DESC"; $titulo_fin="del ".$_POST["reservation"];}
            else {$sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE ESTADO IN ('COMPLETADO', 'RECHAZADO') AND area=".$area."  AND STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d %T') > DATE_SUB(NOW(),INTERVAL 24 HOUR) ORDER BY STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d %T') DESC"; $titulo_fin="Ultimas 24Hrs";}
        } 
        else 
        {$sql_missolicitudes_fin = "SELECT * FROM SOLICITUDES WHERE (usuario_solicitud = '$dpilogin' OR responsable LIKE '%".$dpilogin."%') AND ESTADO IN ('COMPLETADO', 'RECHAZADO')";}
    $menu_reportes="SI";
    $sql_tablero="SELECT * FROM SOLICITUDES WHERE id=".$_REQUEST["id"]."";
    if ($userLevel=='378' || $userLevel=='357' || $userLevel=='364' || $userLevel=='371') {$asignar_a_n_p="staff";} else {$asignar_a_n_p="";}
    if ($userLevel=='378' || $userLevel=='357' || $userLevel=='364' || $userLevel=='371') {$acceso_agregar_tarjeta="SI";} else {$acceso_agregar_tarjeta="SI";}
    if ($userLevel=='378' || $userLevel=='357' || $userLevel=='364' || $userLevel=='371') {$menu_bitacora="SI";} else {$menu_bitacora="NO";}
}


// STAFF TDI POR CADA AREA PARA EL SELECT 

    $sql_desa = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USERS WHERE L6_PLATAFORMA = '378' AND END_DATE ='0000-00-00 00:00:00'";
    $sql_pbx = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USERS WHERE L6_PLATAFORMA = '357' AND END_DATE ='0000-00-00 00:00:00'";
    $sql_micro = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USERS WHERE L6_PLATAFORMA = '364' AND END_DATE ='0000-00-00 00:00:00'";
    $sql_tdi = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USERS WHERE (L6_PLATAFORMA = '378' OR L6_PLATAFORMA = '357' OR L6_PLATAFORMA = '364' OR L6_PLATAFORMA = '371') AND END_DATE ='0000-00-00 00:00:00'";
    $sql_wfo = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USERS WHERE L6_PLATAFORMA = '371' AND END_DATE ='0000-00-00 00:00:00'";


?>