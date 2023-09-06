<?php
session_start();

if (!$_SESSION['login'] ){
    echo '<script type="text/javascript">
    window.open("http://atc/TDI/", "_self");
    </script>';
}

include_once "models/rutas.php";
$cfgProgDir = '../phpSecurePages/';
include($cfgProgDir . "secure.php");

include("script/conex_doc.php");
include("script/perfiles.php");



function convertirMes($mesIngles) {
    $meses = array(
        'January' => 'Enero',
        'February' => 'Febrero',
        'March' => 'Marzo',
        'April' => 'Abril',
        'May' => 'Mayo',
        'June' => 'Junio',
        'July' => 'Julio',
        'August' => 'Agosto',
        'September' => 'Septiembre',
        'October' => 'Octubre',
        'November' => 'Noviembre',
        'December' => 'Diciembre'
    );

    if (array_key_exists($mesIngles, $meses)) {
        return $meses[$mesIngles];
    } else {
        return false; // En caso de que el mes no exista en el array
    }
}

function convertirArea($area) {
    $areaN = array(
        '1' => '<b style="color:black;">Desarrollo</b>',
        '2' => '<b style="color:blue;">PBX</b>',
        '3' => '<b style="color:green;">MICROINFORMATICA</b>',
        '4' => '<b style="color:orange;">WFO</b>'
    );
    if (array_key_exists($area, $areaN)) {
        return $areaN[$area];
    } else {
        return false; // En caso de que el mes no exista en el array
    }
}


//FILTRO AREA
$filtro="";
$area=" - TODOS";
if(isset($_REQUEST["area"])){$filtro=" AND S.area=".$_REQUEST["area"] ."";}

$desarrollo= array();
$pbx= array();
$microinformatica= array();
$wfo= array();
$mes= array();


$fechaDesde="2023-04-01";

//////////////////////////            GRAFICO SOLICITUDES X MES      ///////////////////////////////////
// Obtener datos de la base de datos
$sql = "SELECT area, mes, (EN_PROCESO + COMPLETADO + SIN_ASIGNAR + RECHAZADO) TOTAL FROM (select area, mes, SUM(CASE WHEN estado='EN PROCESO' THEN total ELSE 0 END) AS EN_PROCESO, SUM(CASE WHEN estado='COMPLETADO' THEN total ELSE 0 END) AS COMPLETADO, SUM(CASE WHEN estado='SIN ASIGNAR' THEN total ELSE 0 END) AS SIN_ASIGNAR, SUM(CASE WHEN estado='RECHAZADO' THEN total ELSE 0 END) AS RECHAZADO from (SELECT A.area, A.estado, SUBSTRING(A.fecha_solicitud, 1, 7) as mes, COUNT(A.id)+count(T.TAREAS) total  FROM SOLICITUDES A left JOIN (SELECT id_rel, count(id) TAREAS from tablero WHERE solicitud_generada IS NULL group by id_rel HAVING  count(id)>1) T ON A.id=T.id_rel where A.fecha_solicitud>='".$fechaDesde."' group by A.area, SUBSTRING(A.fecha_solicitud, 1, 7), A.estado) G GROUP BY area, mes) G";
$result = mysql_query($sql, $con_doc);
while ($data = mysql_fetch_array($result)) {
    if($data['area']=="1"){$desarrollo[]=$data['TOTAL'];}
    if($data['area']=="2"){$pbx[]=$data['TOTAL'];}
    if($data['area']=="3"){$microinformatica[]=$data['TOTAL'];}
    if($data['area']=="4"){$wfo[]=$data['TOTAL'];}
    $mes[]='"'.$data['mes'].'"';
}

$desarrolloJ= "[";
for ($i = 0; $i < count($desarrollo); $i++) {
    if((count($desarrollo)-1)==$i){$desarrolloJ .= $desarrollo[$i]."]";}else{$desarrolloJ .= $desarrollo[$i].", ";}
}
$pbxJ= "[";
for ($i = 0; $i < count($pbx); $i++) {
    if((count($pbx)-1)==$i){$pbxJ .= $pbx[$i]."]";}else{$pbxJ .= $pbx[$i].", ";}
}
$microinformaticaJ= "[";
for ($i = 0; $i < count($microinformatica); $i++) {
    if((count($microinformatica)-1)==$i){$microinformaticaJ .= $microinformatica[$i]."]";}else{$microinformaticaJ .= $microinformatica[$i].", ";}
}
$wfoJ= "[";
for ($i = 0; $i < count($wfo); $i++) {
    if((count($wfo)-1)==$i){$wfoJ .= $wfo[$i]."]";}else{$wfoJ .= $wfo[$i].", ";}
}

// Contar a ocorrência de cada valor no array
$contagem = array_count_values($mes);
// Array para armazenar os valores agrupados
$agrupadosmes = array();
// Iterar sobre o array de contagem
foreach ($contagem as $valor => $quantidade) {
    // Se a quantidade for maior que 1, significa que é um valor duplicado
    // Então, adicionamos ao array de valores agrupados
    if ($quantidade > 1) {
        $agrupadosmes[] = $valor;
    }
}
$agrupadosmesJ= "[";
for ($i = 0; $i < count($wfo); $i++) {
    if((count($agrupadosmes)-1)==$i){$agrupadosmesJ .= $agrupadosmes[$i]."]";}else{$agrupadosmesJ .= $agrupadosmes[$i].", ";}
}



//////////////////////////            DESARROLLO      ///////////////////////////////////
$desarrolloDia= array();
$desarrolloDiaCerradas= array();
// Obtener datos de la base de datos
$sql = "SELECT I.mes, I.total, SUM(CASE WHEN S.total IS NULL THEN 0 ELSE S.total END) AS CERRADAS FROM (SELECT area, mes, (EN_PROCESO + COMPLETADO + SIN_ASIGNAR + RECHAZADO) TOTAL FROM (select area, mes, SUM(CASE WHEN estado='EN PROCESO' THEN total ELSE 0 END) AS EN_PROCESO, SUM(CASE WHEN estado='COMPLETADO' THEN total ELSE 0 END) AS COMPLETADO, SUM(CASE WHEN estado='SIN ASIGNAR' THEN total ELSE 0 END) AS SIN_ASIGNAR, SUM(CASE WHEN estado='RECHAZADO' THEN total ELSE 0 END) AS RECHAZADO from (SELECT A.area, A.estado, SUBSTRING(A.fecha_solicitud, 1, 10) as mes, COUNT(A.id)+count(T.TAREAS) total  FROM SOLICITUDES A left JOIN (SELECT id_rel, count(id) TAREAS from tablero WHERE solicitud_generada IS NULL group by id_rel HAVING  count(id)>1) T ON A.id=T.id_rel where A.fecha_solicitud>='".$fechaDesde."' group by A.area, SUBSTRING(A.fecha_solicitud, 1, 10), A.estado) G GROUP BY area, mes) G WHERE (G.area = 1 AND (MONTH(CURDATE()) = MONTH(mes) OR MONTH(CURDATE())-1 = MONTH(mes)))  ORDER BY G.mes) I LEFT JOIN (SELECT STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d') fecha, area, count(id) total FROM SOLICITUDES where (((MONTH(CURDATE()) = MONTH(STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d'))) OR (MONTH(CURDATE())-1 = MONTH(STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d')))) and (estado='COMPLETADO' or 'RECHAZADO') and area=1) group by (STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d')) , area) S ON I.mes=S.fecha GROUP BY I.mes, I.total";
$result = mysql_query($sql, $con_doc);
while ($data = mysql_fetch_array($result)) {
    $desarrolloDia[]=$data['total'];
    $desarrolloDiaCerradas[]=$data['CERRADAS'];
    $dia[]='"'.$data['mes'].'"';
}

$desarrolloDiaJ= "[";
for ($i = 0; $i < count($desarrolloDia); $i++) {
    if((count($desarrolloDia)-1)==$i){$desarrolloDiaJ .= $desarrolloDia[$i]."]";}else{$desarrolloDiaJ .= $desarrolloDia[$i].", ";}
}
$desarrolloDiaCerradasJ= "[";
for ($i = 0; $i < count($desarrolloDiaCerradas); $i++) {
    if((count($desarrolloDiaCerradas)-1)==$i){$desarrolloDiaCerradasJ .= $desarrolloDiaCerradas[$i]."]";}else{$desarrolloDiaCerradasJ .= $desarrolloDiaCerradas[$i].", ";}
}


$diaJ= "[";
for ($i = 0; $i < count($dia); $i++) {
    if((count($dia)-1)==$i){$diaJ .= $dia[$i]."]";}else{$diaJ .= $dia[$i].", ";}
}

//////////////////////////            PBX      ///////////////////////////////////
$pbxDia= array();
$pbxDiaCerradas= array();
// Obtener datos de la base de datos
$sql = "SELECT I.mes, I.total, SUM(CASE WHEN S.total IS NULL THEN 0 ELSE S.total END) AS CERRADAS  FROM (SELECT area, mes, (EN_PROCESO + COMPLETADO + SIN_ASIGNAR + RECHAZADO) TOTAL FROM (select area, mes, SUM(CASE WHEN estado='EN PROCESO' THEN total ELSE 0 END) AS EN_PROCESO, SUM(CASE WHEN estado='COMPLETADO' THEN total ELSE 0 END) AS COMPLETADO, SUM(CASE WHEN estado='SIN ASIGNAR' THEN total ELSE 0 END) AS SIN_ASIGNAR, SUM(CASE WHEN estado='RECHAZADO' THEN total ELSE 0 END) AS RECHAZADO from (SELECT A.area, A.estado, SUBSTRING(A.fecha_solicitud, 1, 10) as mes, COUNT(A.id)+count(T.TAREAS) total  FROM SOLICITUDES A left JOIN (SELECT id_rel, count(id) TAREAS from tablero WHERE solicitud_generada IS NULL group by id_rel HAVING  count(id)>1) T ON A.id=T.id_rel where A.fecha_solicitud>='".$fechaDesde."' group by A.area, SUBSTRING(A.fecha_solicitud, 1, 10), A.estado) G GROUP BY area, mes) G WHERE (G.area = 2 AND (MONTH(CURDATE()) = MONTH(mes) OR MONTH(CURDATE())-1 = MONTH(mes)))  ORDER BY G.mes) I LEFT JOIN (SELECT STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d') fecha, area, count(id) total FROM SOLICITUDES where (((MONTH(CURDATE()) = MONTH(STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d'))) OR (MONTH(CURDATE())-1 = MONTH(STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d')))) and (estado='COMPLETADO' or 'RECHAZADO') and area=2) group by (STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d')) , area) S ON I.mes=S.fecha GROUP BY I.mes, I.total";
$result = mysql_query($sql, $con_doc);
while ($data = mysql_fetch_array($result)) {
    $pbxDia[]=$data['total'];
    $pbxDiaCerradas[]=$data['CERRADAS'];
    $diapbx[]='"'.$data['mes'].'"';
}

$pbxDiaJ= "[";
for ($i = 0; $i < count($pbxDia); $i++) {
    if((count($pbxDia)-1)==$i){$pbxDiaJ .= $pbxDia[$i]."]";}else{$pbxDiaJ .= $pbxDia[$i].", ";}
}
$pbxDiaCerradasJ= "[";
for ($i = 0; $i < count($pbxDiaCerradas); $i++) {
    if((count($pbxDiaCerradas)-1)==$i){$pbxDiaCerradasJ .= $pbxDiaCerradas[$i]."]";}else{$pbxDiaCerradasJ .= $pbxDiaCerradas[$i].", ";}
}

$diapbxJ= "[";
for ($i = 0; $i < count($diapbx); $i++) {
    if((count($diapbx)-1)==$i){$diapbxJ .= $diapbx[$i]."]";}else{$diapbxJ .= $diapbx[$i].", ";}
}

//////////////////////////            MICRO      ///////////////////////////////////
$microDia= array();
$microDiaCerradas= array();
// Obtener datos de la base de datos
$sql = "SELECT I.mes, I.total, SUM(CASE WHEN S.total IS NULL THEN 0 ELSE S.total END) AS CERRADAS  FROM (SELECT area, mes, (EN_PROCESO + COMPLETADO + SIN_ASIGNAR + RECHAZADO) TOTAL FROM (select area, mes, SUM(CASE WHEN estado='EN PROCESO' THEN total ELSE 0 END) AS EN_PROCESO, SUM(CASE WHEN estado='COMPLETADO' THEN total ELSE 0 END) AS COMPLETADO, SUM(CASE WHEN estado='SIN ASIGNAR' THEN total ELSE 0 END) AS SIN_ASIGNAR, SUM(CASE WHEN estado='RECHAZADO' THEN total ELSE 0 END) AS RECHAZADO from (SELECT A.area, A.estado, SUBSTRING(A.fecha_solicitud, 1, 10) as mes, COUNT(A.id)+count(T.TAREAS) total  FROM SOLICITUDES A left JOIN (SELECT id_rel, count(id) TAREAS from tablero WHERE solicitud_generada IS NULL group by id_rel HAVING  count(id)>1) T ON A.id=T.id_rel where A.fecha_solicitud>='".$fechaDesde."' group by A.area, SUBSTRING(A.fecha_solicitud, 1, 10), A.estado) G GROUP BY area, mes) G WHERE (G.area = 3 AND (MONTH(CURDATE()) = MONTH(mes) OR MONTH(CURDATE())-1 = MONTH(mes)))  ORDER BY G.mes) I LEFT JOIN (SELECT STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d') fecha, area, count(id) total FROM SOLICITUDES where (((MONTH(CURDATE()) = MONTH(STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d'))) OR (MONTH(CURDATE())-1 = MONTH(STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d')))) and (estado='COMPLETADO' or 'RECHAZADO') and area=3) group by (STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d')) , area) S ON I.mes=S.fecha GROUP BY I.mes, I.total";
$result = mysql_query($sql, $con_doc);
while ($data = mysql_fetch_array($result)) {
    $microDia[]=$data['total'];
    $microDiaCerradas[]=$data['CERRADAS'];
    $diamicro[]='"'.$data['mes'].'"';
}

$microDiaJ= "[";
for ($i = 0; $i < count($microDia); $i++) {
    if((count($microDia)-1)==$i){$microDiaJ .= $microDia[$i]."]";}else{$microDiaJ .= $microDia[$i].", ";}
}
$microDiaCerradasJ= "[";
for ($i = 0; $i < count($microDiaCerradas); $i++) {
    if((count($microDiaCerradas)-1)==$i){$microDiaCerradasJ .= $microDiaCerradas[$i]."]";}else{$microDiaCerradasJ .= $microDiaCerradas[$i].", ";}
}

$diamicroJ= "[";
for ($i = 0; $i < count($diamicro); $i++) {
    if((count($diamicro)-1)==$i){$diamicroJ .= $diamicro[$i]."]";}else{$diamicroJ .= $diamicro[$i].", ";}
}


//////////////////////////            WFO      ///////////////////////////////////
$wfoDia= array();
$wfoDiaCerradas= array();
// Obtener datos de la base de datos
$sql = "SELECT I.mes, I.total, SUM(CASE WHEN S.total IS NULL THEN 0 ELSE S.total END) AS CERRADAS  FROM (SELECT area, mes, (EN_PROCESO + COMPLETADO + SIN_ASIGNAR + RECHAZADO) TOTAL FROM (select area, mes, SUM(CASE WHEN estado='EN PROCESO' THEN total ELSE 0 END) AS EN_PROCESO, SUM(CASE WHEN estado='COMPLETADO' THEN total ELSE 0 END) AS COMPLETADO, SUM(CASE WHEN estado='SIN ASIGNAR' THEN total ELSE 0 END) AS SIN_ASIGNAR, SUM(CASE WHEN estado='RECHAZADO' THEN total ELSE 0 END) AS RECHAZADO from (SELECT A.area, A.estado, SUBSTRING(A.fecha_solicitud, 1, 10) as mes, COUNT(A.id)+count(T.TAREAS) total  FROM SOLICITUDES A left JOIN (SELECT id_rel, count(id) TAREAS from tablero WHERE solicitud_generada IS NULL group by id_rel HAVING  count(id)>1) T ON A.id=T.id_rel where A.fecha_solicitud>='".$fechaDesde."' group by A.area, SUBSTRING(A.fecha_solicitud, 1, 10), A.estado) G GROUP BY area, mes) G WHERE (G.area = 4 AND (MONTH(CURDATE()) = MONTH(mes) OR MONTH(CURDATE())-1 = MONTH(mes)))  ORDER BY G.mes) I LEFT JOIN (SELECT STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d') fecha, area, count(id) total FROM SOLICITUDES where (((MONTH(CURDATE()) = MONTH(STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d'))) OR (MONTH(CURDATE())-1 = MONTH(STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d')))) and (estado='COMPLETADO' or 'RECHAZADO') and area=4) group by (STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d')) , area) S ON I.mes=S.fecha GROUP BY I.mes, I.total";
$result = mysql_query($sql, $con_doc);
while ($data = mysql_fetch_array($result)) {
    $wfoDia[]=$data['total'];
    $wfoDiaCerradas[]=$data['CERRADAS'];
    $diawfo[]='"'.$data['mes'].'"';
}

$wfoDiaJ= "[";
for ($i = 0; $i < count($wfoDia); $i++) {
    if((count($wfoDia)-1)==$i){$wfoDiaJ .= $wfoDia[$i]."]";}else{$wfoDiaJ .= $wfoDia[$i].", ";}
}
$wfoDiaCerradasJ= "[";
for ($i = 0; $i < count($wfoDiaCerradas); $i++) {
    if((count($wfoDiaCerradas)-1)==$i){$wfoDiaCerradasJ .= $wfoDiaCerradas[$i]."]";}else{$wfoDiaCerradasJ .= $wfoDiaCerradas[$i].", ";}
}

$diawfoJ= "[";
for ($i = 0; $i < count($diawfo); $i++) {
    if((count($diawfo)-1)==$i){$diawfoJ .= $diawfo[$i]."]";}else{$diawfoJ .= $diawfo[$i].", ";}
}




mysql_close($con_doc);

?>
<!DOCTYPE html>
<html>

<head>
    <?php include "view/head.php"; ?>
    <style>
        table {
        border-collapse: collapse;
        width: 100%;
        }

        td, th {
        border: 1px solid #ebecf2;
        text-align: left;
        padding: 8px;
        }

        tr:nth-child(even) {
        background-color: #ebecf2;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <?php
    if (!$_GET["msg"]) {
    } else {
        echo "<script>alert('" . $_GET["msg"] . "')</script>";
    }
    ?>

    <div class="wrapper">

        <!-- Navbar -->
        <?php include "view/navbar.php"; ?>
        <!-- /.navbar -->


        <!-- menu -->
        <?php include_once "view/menu.php"; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">


            &nbsp

            <!-- Main content -->

            <small>





                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">SOLICITUDES AL AREA (SOLICITUDES NUEVAS Y/O MODIFICACIONES - PROBLEMAS O INCIDENTES)</h3>
                                    </div>
                                    <div class="card-body">
                                    
                                        <table id="example1" class="table table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>AREA</th>
                                                    <th>MES</th>
                                                    <th title='EN PROCESO'>PROC</th>
                                                    <th title='COMPLETADO'>COMP</th>
                                                    <th title='SIN ASIGNAR'>SIN</th>
                                                    <th title='RECHAZADO'>RECH</th> 
                                                    <th title='TOTAL SOLICTUDES + TAREAS'>TOTAL</th>
                                                    <th title='SOLICITUDES'>SOL</th>
                                                    <th title='TAREAS'>TAR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                include("script/conex_doc.php");
                                                $sql = "select area, mes, SUM(CASE WHEN estado='EN PROCESO' THEN total ELSE 0 END) AS EN_PROCESO, SUM(CASE WHEN estado='COMPLETADO' THEN total ELSE 0 END) AS COMPLETADO, SUM(CASE WHEN estado='SIN ASIGNAR' THEN total ELSE 0 END) AS SIN_ASIGNAR, SUM(CASE WHEN estado='RECHAZADO' THEN total ELSE 0 END) AS RECHAZADO, SUM(soli) SOLICITUDES, SUM(tar) TAREAS from (SELECT A.area, A.estado, SUBSTRING(A.fecha_solicitud, 1, 7) as mes, COUNT(A.id)+count(T.TAREAS) total, COUNT(A.id) soli, count(T.TAREAS) tar  FROM SOLICITUDES A left JOIN (SELECT id_rel, count(id) TAREAS from tablero WHERE solicitud_generada IS NULL group by id_rel HAVING  count(id)>1) T ON A.id=T.id_rel where A.fecha_solicitud>='".$fechaDesde."' group by A.area, SUBSTRING(A.fecha_solicitud, 1, 7), A.estado) G GROUP BY area, mes";
                                                $resultado = mysql_query($sql, $con_doc);
                                                while ($data = mysql_fetch_array($resultado)) {

                                                    $areaNombre=convertirArea($data["area"]);

                                                ?>
                                                    <tr>
                                                        <td><?php echo $areaNombre; ?></td>
                                                        <td><?php echo $data["mes"] ; ?></td>
                                                        <td><?php echo $data["EN_PROCESO"]; ?></td>  
                                                        <td><?php echo $data["COMPLETADO"]; ?></td>  
                                                        <td><?php echo $data["SIN_ASIGNAR"]; ?></td>  
                                                        <td><?php echo $data["RECHAZADO"]; ?></td>  
                                                        <td><?php echo $data["EN_PROCESO"]+$data["COMPLETADO"]+$data["SIN_ASIGNAR"]+$data["RECHAZADO"]; ?></td>  
                                                        <td><?php echo $data["SOLICITUDES"]; ?></td> 
                                                        <td><?php echo $data["TAREAS"]; ?></td>                                                                                                
                                                        
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                <th>AREA</th>
                                                    <th>MES</th>
                                                    <th>PROC</th>
                                                    <th>COMP</th>
                                                    <th>SIN</th>
                                                    <th>RECH</th> 
                                                    <th>TOTAL</th>
                                                    <th>SOL</th>
                                                    <th>TAR</th>

                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">SOLICITUDES AL AREA (SOLICITUDES NUEVAS Y/O MODIFICACIONES)</h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="example2" class="table table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>AREA</th>
                                                    <th>MES</th>
                                                    <th>EN PROCESO</th>
                                                    <th>COMPLETADO</th>
                                                    <th>SIN ASIGNAR</th>
                                                    <th>RECHAZADO</th> 
                                                    <th>TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                include("script/conex_doc.php");
                                                $sql  ="select area, mes, SUM(CASE WHEN estado='EN PROCESO' THEN total ELSE 0 END) AS EN_PROCESO, SUM(CASE WHEN estado='COMPLETADO' THEN total ELSE 0 END) AS COMPLETADO, SUM(CASE WHEN estado='SIN ASIGNAR' THEN total ELSE 0 END) AS SIN_ASIGNAR, SUM(CASE WHEN estado='RECHAZADO' THEN total ELSE 0 END) AS RECHAZADO from (SELECT A.area, A.solicitud_n, A.estado, SUBSTRING(A.fecha_solicitud, 1, 7) as mes, COUNT(A.id)+count(T.TAREAS) total  FROM SOLICITUDES A left JOIN (SELECT id_rel, count(id) TAREAS from tablero WHERE solicitud_generada IS NULL group by id_rel HAVING  count(id)>1) T ON A.id=T.id_rel where A.fecha_solicitud>='".$fechaDesde."' and A.solicitud_n like 'SOLICITUDES%' group by A.area, SUBSTRING(A.fecha_solicitud, 1, 7), A.estado, A.solicitud_n) G GROUP BY area, mes";
                                                $resultado = mysql_query($sql, $con_doc);
                                                while ($data = mysql_fetch_array($resultado)) {

                                                    $areaNombre=convertirArea($data["area"]);

                                                ?>
                                                    <tr>
                                                        <td><?php echo $areaNombre; ?></td>
                                                        <td><?php echo $data["mes"] ; ?></td>
                                                        <td><?php echo $data["EN_PROCESO"]; ?></td>  
                                                        <td><?php echo $data["COMPLETADO"]; ?></td>  
                                                        <td><?php echo $data["SIN_ASIGNAR"]; ?></td>  
                                                        <td><?php echo $data["RECHAZADO"]; ?></td>  
                                                        <td><?php echo $data["EN_PROCESO"]+$data["COMPLETADO"]+$data["SIN_ASIGNAR"]+$data["RECHAZADO"]; ?></td>                                                                                                  
                                                        
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                <th>AREA</th>
                                                    <th>MES</th>
                                                    <th>EN PROCESO</th>
                                                    <th>COMPLETADO</th>
                                                    <th>SIN ASIGNAR</th>
                                                    <th>RECHAZADO</th> 
                                                    <th>TOTAL</th>  
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>     
                                
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">SOLICITUDES AL AREA (PROBLEMAS O INCIDENTES)</h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="example3" class="table table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>AREA</th>
                                                    <th>MES</th>
                                                    <th>EN PROCESO</th>
                                                    <th>COMPLETADO</th>
                                                    <th>SIN ASIGNAR</th>
                                                    <th>RECHAZADO</th> 
                                                    <th>TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $areaCelda="1";
                                                $colorFilas="#ffffff";
                                                include("script/conex_doc.php");
                                                $sql = "select area, mes, SUM(CASE WHEN estado='EN PROCESO' THEN total ELSE 0 END) AS EN_PROCESO, SUM(CASE WHEN estado='COMPLETADO' THEN total ELSE 0 END) AS COMPLETADO, SUM(CASE WHEN estado='SIN ASIGNAR' THEN total ELSE 0 END) AS SIN_ASIGNAR, SUM(CASE WHEN estado='RECHAZADO' THEN total ELSE 0 END) AS RECHAZADO from (SELECT A.area, A.solicitud_n, A.estado, SUBSTRING(A.fecha_solicitud, 1, 7) as mes, COUNT(A.id)+count(T.TAREAS) total  FROM SOLICITUDES A left JOIN (SELECT id_rel, count(id) TAREAS from tablero WHERE solicitud_generada IS NULL group by id_rel HAVING  count(id)>1) T ON A.id=T.id_rel where A.fecha_solicitud>='".$fechaDesde."' and A.solicitud_n like 'PROBLEMAS%' group by A.area, SUBSTRING(A.fecha_solicitud, 1, 7), A.estado, A.solicitud_n) G GROUP BY area, mes";
                                                $resultado = mysql_query($sql, $con_doc);
                                                while ($data = mysql_fetch_array($resultado)) {                                                

                                                    $areaNombre=convertirArea($data["area"]);
                                                    if($areaCelda != $data["area"]){$colorFilas="#F2F2F2";}
                                                    $areaCelda = $data["area"];

                                                ?>
                                                    <tr>
                                                        <td><?php echo $areaNombre; ?></td>
                                                        <td><?php echo $data["mes"] ; ?></td>
                                                        <td><?php echo $data["EN_PROCESO"]; ?></td>  
                                                        <td><?php echo $data["COMPLETADO"]; ?></td>  
                                                        <td><?php echo $data["SIN_ASIGNAR"]; ?></td>  
                                                        <td><?php echo $data["RECHAZADO"]; ?></td>  
                                                        <td><?php echo $data["EN_PROCESO"]+$data["COMPLETADO"]+$data["SIN_ASIGNAR"]+$data["RECHAZADO"]; ?></td>                                                                                                  
                                                        
                                                    </tr>
                                                <?php } mysql_close($con_doc);?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                <th>AREA</th>
                                                    <th>MES</th>
                                                    <th>EN PROCESO</th>
                                                    <th>COMPLETADO</th>
                                                    <th>SIN ASIGNAR</th>
                                                    <th>RECHAZADO</th> 
                                                    <th>TOTAL</th>  

                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div> 

                            </div>

                            <div class="col-md-6">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">TOTAL DE SOLICITUDES GENERADAS X MES</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="myChart" width="400" height="200"></canvas>
                                    </div>
                                </div>

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">TOTAL SOL. DESARROLLO GENERADAS vs CERRADAS DIARIO</h3>
                                    </div>
                                    <div class="card-body">
                                    <canvas id="myChartDesarrollo" width="400" height="200"></canvas>
                                    </div>
                                </div>    
                                
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">TOTAL SOL. PBX GENERADAS vs CERRADAS DIARIO</h3>
                                    </div>
                                    <div class="card-body">
                                    <canvas id="myChartPbx" width="400" height="200"></canvas>
                                    </div>
                                </div>  

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">TOTAL SOL. MICRO GENERADAS vs CERRADAS DIARIO</h3>
                                    </div>
                                    <div class="card-body">
                                    <canvas id="myChartMicro" width="400" height="200"></canvas>
                                    </div>
                                </div> 

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">TOTAL SOL. WFO GENERADAS vs CERRADAS DIARIO</h3>
                                    </div>
                                    <div class="card-body">
                                    <canvas id="myChartWFO" width="400" height="200"></canvas>
                                    </div>
                                </div> 

                            </div>


                    

                    </div>

                    </div>
                    </section>



                <script>
                    // Datos de ejemplo para las ventas de dos productos (en miles)
                    // const desarrollo = [10, 12, 15, 20, 18, 22];
                    // const pbx = [8, 9, 11, 14, 12, 16];
                    // const microinformatica = [7, 3, 18, 10, 7, 10];
                    // const wfo = [1, 20, 7, 5, 10, 18];

                   


                    // Fechas de ejemplo (semanas)
                    // const mes = ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Semana 6'];

                    // Configuración del gráfico
                    const config = {
                    type: 'line',
                    data: {
                        labels: <?php echo $agrupadosmesJ; ?>,
                        datasets: [
                        {
                            label: 'DESARROLLO',
                            data: <?php echo $desarrolloJ; ?>,
                            borderColor: 'black',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'PBX',
                            data: <?php echo $pbxJ; ?>,
                            borderColor: 'blue',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'MICROINFORMATICA',
                            data: <?php echo $microinformaticaJ; ?>,
                            borderColor: 'green',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'WFO',
                            data: <?php echo $wfoJ; ?>,
                            borderColor: 'orange',
                            borderWidth: 2,
                            fill: false
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                        x: {
                            display: true,
                            title: {
                            display: true,
                            text: 'MES'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                            display: true,
                            text: 'SOLICITUDES'
                            }
                        }
                        }
                    }
                    };

                    // Crear el gráfico
                    var myChart = new Chart(document.getElementById('myChart'), config);
            </script>




            <script>
                    const configD = {
                    type: 'line',
                    data: {
                        labels: <?php echo $diaJ; ?>,
                        datasets: [
                        {
                            label: 'GENERADAS',
                            data: <?php echo $desarrolloDiaJ; ?>,
                            borderColor: 'black',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'CERRADAS',
                            data: <?php echo $desarrolloDiaCerradasJ; ?>,
                            borderColor: '#F95454',
                            borderWidth: 2,
                            fill: false
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                        x: {
                            display: true,
                            title: {
                            display: true,
                            text: 'MES'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                            display: true,
                            text: 'SOLICITUDES'
                            }
                        }
                        }
                    }
                    };

                    // Crear el gráfico
                    var myChartDesarrollo = new Chart(document.getElementById('myChartDesarrollo'), configD);
            </script>

            <script>
                    const configP = {
                    type: 'line',
                    data: {
                        labels: <?php echo $diapbxJ; ?>,
                        datasets: [
                        {
                            label: 'GENERADAS',
                            data: <?php echo $pbxDiaJ; ?>,
                            borderColor: 'green',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'CERRADAS',
                            data: <?php echo $pbxDiaCerradasJ; ?>,
                            borderColor: '#F95454',
                            borderWidth: 2,
                            fill: false
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                        x: {
                            display: true,
                            title: {
                            display: true,
                            text: 'DIA'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                            display: true,
                            text: 'SOLICITUDES'
                            }
                        }
                        }
                    }
                    };

                    // Crear el gráfico
                    var myChartPbx = new Chart(document.getElementById('myChartPbx'), configP);
            </script>


            <script>
                    const configM = {
                    type: 'line',
                    data: {
                        labels: <?php echo $diamicroJ; ?>,
                        datasets: [
                        {
                            label: 'GENERADAS',
                            data: <?php echo $microDiaJ; ?>,
                            borderColor: 'blue',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'CERRADAS',
                            data: <?php echo $microDiaCerradasJ; ?>,
                            borderColor: '#F95454',
                            borderWidth: 2,
                            fill: false
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                        x: {
                            display: true,
                            title: {
                            display: true,
                            text: 'DIA'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                            display: true,
                            text: 'SOLICITUDES'
                            }
                        }
                        }
                    }
                    };

                    // Crear el gráfico
                    var myChartMicro = new Chart(document.getElementById('myChartMicro'), configM);
            </script>

<script>
                    const configW = {
                    type: 'line',
                    data: {
                        labels: <?php echo $diawfoJ; ?>,
                        datasets: [
                        {
                            label: 'GENERADAS',
                            data: <?php echo $wfoDiaJ; ?>,
                            borderColor: 'orange',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'CERRADAS',
                            data: <?php echo $wfoDiaCerradasJ; ?>,
                            borderColor: '#F95454',
                            borderWidth: 2,
                            fill: false
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                        x: {
                            display: true,
                            title: {
                            display: true,
                            text: 'DIA'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                            display: true,
                            text: 'SOLICITUDES'
                            }
                        }
                        }
                    }
                    };

                    // Crear el gráfico
                    var myChartWFO= new Chart(document.getElementById('myChartWFO'), configW);
            </script>

            </small>

            <!-- Info boxes -->
            <div class="row">
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
            </div><!-- /.container-fluid -->

            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 SOPORTE DE APLICACIONES</strong>
            <div class="float-right d-none d-sm-inline-block">
                <b>DATACOM SRL</b>
            </div>
        </footer>
    </div>

    </div>
    <!-- ./wrapper -->

    <?php include "view/script.php"; ?>

<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js">
</script>
<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
</script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js">
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">

<!-- <script type="text/javascript" language="javascript" src="script/dataTables.buttons.min.js">
</script>
<script type="text/javascript" language="javascript" src="script/jszip.min.js">
</script>
<script type="text/javascript" language="javascript" src="script/buttons.html5.min.js">
</script>
<link rel="stylesheet" type="text/css" href="script/buttons.dataTables.min.css"> -->


<script>        
    $(document).ready(function() {
       

        $("#example1").DataTable({                    
    
            paging: false,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5'
            ],     
            
            "language": {
                "url": "script/datatable_Spanish.json",
            },
            initComplete: function() {
                // Apply the search
                this.api()
                    .columns()
                    .every(function() {
                        var that = this;

                        $('input', this.footer()).on('keyup change clear', function() {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
            }
                     
        });    
        
        $("#example2").DataTable({                    
    
    paging: false,
    dom: 'Bfrtip',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5'
    ],     
    
    "language": {
        "url": "script/datatable_Spanish.json",
    },
    initComplete: function() {
        // Apply the search
        this.api()
            .columns()
            .every(function() {
                var that = this;

                $('input', this.footer()).on('keyup change clear', function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
    }
             
}); 

$("#example3").DataTable({                    
    
    paging: false,
    dom: 'Bfrtip',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5'
    ],     
    
    "language": {
        "url": "script/datatable_Spanish.json",
    },
    initComplete: function() {
        // Apply the search
        this.api()
            .columns()
            .every(function() {
                var that = this;

                $('input', this.footer()).on('keyup change clear', function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
    }
             
}); 

    })
</script>







</body>

</html>