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
include("script/perfiles.php");
include("script/conex_doc.php");

$color = "primary";


// EL QUERY ESTA EN PERFILES
$resultado = mysql_query($sql_tablero, $con_doc);
$data = mysql_fetch_array($resultado);
//$tablero = $data["area_n"] . " - " . $data["solicitud_n"] . " - " . $data["titulo"];
$tablero = $data["solicitud_n"] . " - " . $data["titulo"];

$sql1 = "SELECT CONCAT(responsable, ',', asignar_a, ',', usuario_solicitud) AS miembros FROM SOLICITUDES WHERE id = " . $_REQUEST["id"] . "";
$resultado1 = mysql_query($sql1, $con_doc);
$data1 = mysql_fetch_array($resultado1);

$mienbros = array_unique(explode(",", $data1["miembros"]));

$mienbros1 = "";
for ($x = 0; $x < count($mienbros); $x++)
    $mienbros1 .= $mienbros[$x] . ",";
$mienbros2 = substr($mienbros1, 0, -1);

//echo $tablero; exit;

if ($data["estado"]=="COMPLETADO" OR $data["estado"]=="RECHAZADO") {
    header('Location: missolicitudes_fin.php');
    die();
}

if (!$data["asignar_a_n"]) {
    header('Location: missolicitudes.php');
    die();
}
if ($tablero == " -  - ") {
    header('Location: missolicitudes.php');
    die();
}

if ($acceso_agregar_tarjeta=="NO") {
    $sql_proceso="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='PROCESO' AND visible='SI'";
    $sql_pruebas="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='PRUEBAS' AND visible='SI'";
    $sql_completado="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='COMPLETADO' AND visible='SI'";
    $sql_eliminado="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='ELIMINADO' AND visible='SI'";    
}
else{
    $sql_proceso="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='PROCESO'";
    $sql_pruebas="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='PRUEBAS'";
    $sql_completado="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='COMPLETADO'";
    $sql_eliminado="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='ELIMINADO'";   
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include "view/head.php"; ?>
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
        <?php include "view/navbar_tablero.php"; ?>
        <!-- /.navbar -->


        <!-- menu -->
        <?php include_once "view/menu.php"; ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">


                    <div class="row">



                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <div class="info-box-content">
                                    <span class="info-box-number">EN PROCESO</span>

                                    <div class="dropdown-divider"></div>

                                    <?php include("script/conex_doc.php");
                                    $resultadot = mysql_query($sql_proceso, $con_doc);
                                    while ($datat = mysql_fetch_array($resultadot)) {
                                    ?>

                                        <div class="card card-outline card-warning collapsed-card">
                                            <div class="card-header">
                                                <small><?php if (date("Y-m-d", strtotime($datat["fecha_fin"]."- 3 days"))<=date("Y-m-d")) {if (date("Y-m-d", strtotime($datat["fecha_fin"]."- 0 days"))<date("Y-m-d")) {echo "<i class='far fa-bell' style='color:#DC3545'></i> ";} else {echo "<i class='far fa-bell' style='color:#998b60'></i> ";}} ?><?php if (strpos($datat["integrantes_n"], $uname) !== false) {echo "<b>".$datat["titulo"]."</b>";} else {echo $datat["titulo"];} if (!$datat["obs"]) {} else {if (date("Y-m-d H:i:s", strtotime(substr($datat["obs"], 0, 19)."+ 3 days"))>=date("Y-m-d H:i:s")) {echo " <i class='far fa-comment'></i>";} }?></small>

                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: none;">
                                                <small><?php echo substr($datat["descripcion"], 0, 50); ?>...
                                                    <div class="dropdown-divider"></div>

                                                    <div class="input-group-prepend">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                                                        </button>
                                                        <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">
                                                            <small>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="detalle_tarjeta.php?id=<?php echo $_REQUEST['id']; ?>&lista=PROCESO&tarjeta=<?php echo $datat["id"]; ?>">VER DETALLE</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=PRUEBAS&id=<?php echo $datat["id"]; ?>" style="color: #28a745;">EN PRUEBAS</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=COMPLETADO&id=<?php echo $datat["id"]; ?>" style="color: #007bff;">COMPLETADO</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=ELIMINADO&id=<?php echo $datat["id"]; ?>" style="color: #dc3545;">ELIMINADO</a></li>
                                                            </small>
                                                        </ul>
                                                    </div>
                                                </small>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->

                                    <?php } ?>



                                    <div class="dropdown-divider"></div>
                                    <?php if ($acceso_agregar_tarjeta=="SI") { ?>
                                    <a href="agregar_tarjeta.php?id=<?php echo $_REQUEST['id']; ?>&lista=PROCESO"><button type="button" class="btn btn-block btn-outline-warning btn-xs">Agregar Tarea</button></a>
                                    <?php } ?>

                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <div class="info-box-content">
                                    <span class="info-box-number">EN PRUEBAS</span>

                                    <div class="dropdown-divider"></div>

                                    <?php include("script/conex_doc.php");
                                    $resultadot = mysql_query($sql_pruebas, $con_doc);
                                    while ($datat = mysql_fetch_array($resultadot)) {
                                    ?>

                                        <div class="card card-outline card-success collapsed-card">
                                            <div class="card-header">
                                                <small><?php if (date("Y-m-d", strtotime($datat["fecha_fin"]."- 3 days"))<=date("Y-m-d")) {if (date("Y-m-d", strtotime($datat["fecha_fin"]."- 0 days"))<date("Y-m-d")) {echo "<i class='far fa-bell' style='color:#DC3545'></i> ";} else {echo "<i class='far fa-bell' style='color:#998b60'></i> ";}} ?><?php if (strpos($datat["integrantes_n"], $uname) !== false) {echo "<b>".$datat["titulo"]."</b>";} else {echo $datat["titulo"];} if (!$datat["obs"]) {} else {if (date("Y-m-d H:i:s", strtotime(substr($datat["obs"], 0, 19)."+ 3 days"))>=date("Y-m-d H:i:s")) {echo " <i class='far fa-comment'></i>";} }?></small>

                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: none;">
                                                <small><?php echo substr($datat["descripcion"], 0, 50); ?>...
                                                    <div class="dropdown-divider"></div>

                                                    <div class="input-group-prepend">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                                                        </button>
                                                        <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">
                                                            <small>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="detalle_tarjeta.php?id=<?php echo $_REQUEST['id']; ?>&lista=PRUEBAS&tarjeta=<?php echo $datat["id"]; ?>">VER DETALLE</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=PROCESO&id=<?php echo $datat["id"]; ?>" style="color: #FFC107;">EN PROCESO</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=COMPLETADO&id=<?php echo $datat["id"]; ?>" style="color: #007bff;">COMPLETADO</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=ELIMINADO&id=<?php echo $datat["id"]; ?>" style="color: #dc3545;">ELIMINADO</a></li>
                                                            </small>
                                                        </ul>
                                                    </div>
                                                </small>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->

                                    <?php } ?>



                                    <div class="dropdown-divider"></div>
                                    <!-- <a href="agregar_tarjeta.php?id=<?php echo $_REQUEST['id']; ?>&lista=PRUEBAS"><button type="button" class="btn btn-block btn-outline-success btn-xs">+ Agregar Tarjeta</button></a>-->

                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <div class="info-box-content">
                                    <span class="info-box-number">COMPLETADO</span>

                                    <div class="dropdown-divider"></div>

                                    <?php include("script/conex_doc.php");
                                    $resultadot = mysql_query($sql_completado, $con_doc);
                                    while ($datat = mysql_fetch_array($resultadot)) {
                                    ?>

                                        <div class="card card-outline card-primary collapsed-card">
                                            <div class="card-header">
                                                <small><?php if (strpos($datat["integrantes_n"], $uname) !== false) {echo "<b>".$datat["titulo"]."</b>";} else {echo $datat["titulo"];} if (!$datat["obs"]) {} else {if (date("Y-m-d H:i:s", strtotime(substr($datat["obs"], 0, 19)."+ 3 days"))>=date("Y-m-d H:i:s")) {echo " <i class='far fa-comment'></i>";} }?></small>

                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: none;">
                                                <small><?php echo substr($datat["descripcion"], 0, 50); ?>...
                                                    <div class="dropdown-divider"></div>

                                                    <div class="input-group-prepend">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                                                        </button>
                                                        <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">
                                                            <small>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="detalle_tarjeta.php?id=<?php echo $_REQUEST['id']; ?>&lista=COMPLETADO&tarjeta=<?php echo $datat["id"]; ?>">VER DETALLE</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=PROCESO&id=<?php echo $datat["id"]; ?>" style="color: #FFC107;">EN PROCESO</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=PRUEBAS&id=<?php echo $datat["id"]; ?>" style="color: #28a745;">EN PRUEBAS</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=ELIMINADO&id=<?php echo $datat["id"]; ?>" style="color: #dc3545;">ELIMINADO</a></li>
                                                            </small>
                                                        </ul>
                                                    </div>
                                                </small>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->

                                    <?php } ?>



                                    <div class="dropdown-divider"></div>
                                    <!--<a href="agregar_tarjeta.php?id=<?php echo $_REQUEST['id']; ?>&lista=COMPLETADO"><button type="button" class="btn btn-block btn-outline-primary btn-xs">+ Agregar Tarjeta</button></a>-->

                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->



                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <div class="info-box-content">
                                    <span class="info-box-number">ELIMINADO</span>


                                    <div class="dropdown-divider"></div>


                                    <?php include("script/conex_doc.php");
                                    $resultadot = mysql_query($sql_eliminado, $con_doc);
                                    while ($datat = mysql_fetch_array($resultadot)) {
                                    ?>

                                        <div class="card card-outline card-danger collapsed-card">
                                            <div class="card-header">
                                                <small><?php if (strpos($datat["integrantes_n"], $uname) !== false) {echo "<b>".$datat["titulo"]."</b>";} else {echo $datat["titulo"];} if (!$datat["obs"]) {} else {if (date("Y-m-d H:i:s", strtotime(substr($datat["obs"], 0, 19)."+ 3 days"))>=date("Y-m-d H:i:s")) {echo " <i class='far fa-comment'></i>";} }?></small>

                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: none;">
                                                <small><?php echo substr($datat["descripcion"], 0, 50); ?>...
                                                    <div class="dropdown-divider"></div>

                                                    <div class="input-group-prepend">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                                                        </button>
                                                        <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">
                                                            <small>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="detalle_tarjeta.php?id=<?php echo $_REQUEST['id']; ?>&lista=ELIMINADO&tarjeta=<?php echo $datat["id"]; ?>">VER DETALLE</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=PROCESO&id=<?php echo $datat["id"]; ?>" style="color: #FFC107;">EN PROCESO</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=PRUEBAS&id=<?php echo $datat["id"]; ?>" style="color: #28a745;">EN PRUEBAS</a></li>
                                                                <li class="dropdown-item"><a class="dropdown-item" href="script/cambia_estado.php?id_rel=<?php echo $datat["id_rel"]; ?>&estado=COMPLETADO&id=<?php echo $datat["id"]; ?>" style="color: #007bff;">COMPLETADO</a></li>
                                                            </small>
                                                        </ul>
                                                    </div>
                                                </small>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->

                                    <?php } ?>



                                    <div class="dropdown-divider"></div>


                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->



                    </div>





                </div><!-- /.container-fluid -->
            </div>
            <!-- Main content -->


            <!-- Info boxes -->
            <div class="row">
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
            </div><!-- /.container-fluid -->

        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 SOPORTE DE APLICACIONES</strong>
            <div class="float-right d-none d-sm-inline-block">
                <b>DATACOM SRL</b>
            </div>
        </footer>

    </div>
    <!-- ./wrapper -->

    <?php include "view/script.php";
    mysql_close($con_doc); ?>


</body>

</html>