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

if ($acceso_agregar_tarjeta == "NO") {
    header("location: tablero.php?id=" . $_REQUEST['id'] . "");
    exit;
}

$resultado = mysql_query($sql_tablero, $con_doc);
$data = mysql_fetch_array($resultado);
//$tablero = $data["area_n"] . " - " . $data["solicitud_n"] . " - " . $data["titulo"];
$tablero = $data["solicitud_n"] . " - " . $data["titulo"];

$sql1 = "SELECT CONCAT(responsable, ',', asignar_a, ',', usuario_solicitud) AS miembros, estado FROM SOLICITUDES WHERE id = " . $_REQUEST["id"] . "";
$resultado1 = mysql_query($sql1, $con_doc);
$data1 = mysql_fetch_array($resultado1);


if ($data1["estado"]=="COMPLETADO" OR $data["estado"]=="RECHAZADO") {
    header('Location: missolicitudes_fin.php');
    die();
}


$mienbros = array_unique(explode(",", $data1["miembros"]));

$mienbros1 = "";
for ($x = 0; $x < count($mienbros); $x++)
    $mienbros1 .= $mienbros[$x] . ",";
$mienbros2 = substr($mienbros1, 0, -1);

if ($_REQUEST['lista'] == "ELIMINADO") {
    $colortarjeta = "danger";
} else if ($_REQUEST['lista'] == "PROCESO") {
    $colortarjeta = "warning";
} else if ($_REQUEST['lista'] == "PRUEBAS") {
    $colortarjeta = "success";
} else {
    $colortarjeta = "primary";
}

if (!$data["asignar_a_n"]) {
    header('Location: missolicitudes.php');
    die();
}
if ($tablero == " -  - ") {
    header('Location: missolicitudes.php');
    die();
}

?>
<!DOCTYPE html>
<html>

<head>
    <?php include "view/head.php"; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed ">

    <div class="wrapper">

        <!-- Navbar -->
        <?php include "view/navbar_tablero.php"; ?>
        <!-- /.navbar -->>

        <!-- menu -->
        <?php include_once "view/menu.php"; ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">


            <!-- Main content -->

            <?php include("script/conex_doc.php");
            $sql2 = "SELECT * FROM tablero WHERE id = " . $_REQUEST["tarjeta"] . "";
            $resultado2 = mysql_query($sql2, $con_doc);
            $data2 = mysql_fetch_array($resultado2);
            ?>
            <form action="script/actualizatarjeta.php" method="post">

                <section class="content">
                    <div class="card card-<?php echo $colortarjeta; ?> card-outline">
                        <div class="card-header">
                            <h5 class="card-title m-0"><?php echo " STATUS: <b>" . $_REQUEST['lista'] ; ?></small></b></h5>
                        </div>
                        <div class="card-body">

                            <div class="row">

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <small><label>TITULO DE LA TARJETA</label></small>
                                        <div class="select2-purple">
                                            <small><?php


                                                    echo $data2["titulo"]; ?></small>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <small><label>FECHA INICIO</label></small>
                                        <div class="input-group">
                                            <small><?php echo $data2["fecha_ini"]." AL ".$data2["fecha_fin"]; ?></small>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>                                

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <small><input class="form-check-input" type="checkbox" id="cambiarfecha" name="cambiarfecha"><label>CAMBIAR FECHA INICIO FIN</label></small>
                                        <div class="input-group">
                                            <input type="text" class="form-control float-right form-control-sm" id="reservation" name="reservation" disabled>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>

                                <?php if ($acceso_agregar_tarjeta=="SI") { ?>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <small><label>VISIBLE</label></small>
                                        <div class="input-group">
                                        <select class="form-control form-control-sm" id="visible" name="visible">
                                                <option value="NO" <?php if ($data2["visible"]=="NO") {echo "selected";} ?>>NO</option>
                                                <option value="SI" <?php if ($data2["visible"]=="SI") {echo "selected";} ?>>SI</option>
                                            </select>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                                <?php } ?>

                                

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <small><label>INTEGRANTES DE LA TAREA</label></small>
                                        <div class="input-group">
                                            <small><?php echo $data2["integrantes_n"]; ?></small>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>

                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <small><input class="form-check-input" type="checkbox" id="cambiarintegrantes" name="cambiarintegrantes"><label>MODIFICAR INTEGRANTES DE LA TAREA</label></small>
                                        <div class="input-group">
                                        <select class="select2 form-control-sm" multiple="multiple" data-placeholder="MODIFICAR INTEGRANTES..." data-dropdown-css-class="select2-blue" style="width: 100%;" id="integrantes_solicitud" name=integrantes_solicitud[] required disabled>
                                                <?php echo $integrantes_solicitud; ?>
                                            </select>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>

                               
                                
                                <div class="col-sm-8">
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <small><label>DESCRIPCION DE LA TAREA</label><br>
                                        <?php echo $data2["descripcion"]; ?></small>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <small><label>CANTIDAD</label><br>
                                        <?php echo $data2["numero"]; ?></small>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <?php if ($data2["solicitud_generada"]>""){?>
                                        <small><label>SOLICITUD ENCAMINADA</label><br>
                                        N°: <a href="informe_solicitud.php?id=<?php echo $data2["solicitud_generada"]; ?>"><?php echo $data2["solicitud_generada"]; ?></a></small>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <small><label>OBSERVACIONES</label></small><br>
                                        <textarea class="form-control form-control-sm" rows="3" placeholder="OBSERVACIONES / NOTAS ..." id="obs" name="obs" required></textarea>
                                    </div>
                                </div>
                                <input id="id" name="id" type="hidden" value="<?php echo $data2["id"]; ?>">
                                <input id="id_rel" name="id_rel" type="hidden" value="<?php echo $data2["id_rel"]; ?>">
                                <input id="estado" name="estado" type="hidden" value="<?php echo $data2["estado"]; ?>">
                                <input id="fecha_ant" name="fecha_ant" type="hidden" value="<?php echo $data2["fecha_ini"] . " A " . $data2["fecha_fin"]; ?>">
                                <button type="submit" class="btn btn-block btn-<?php echo $colortarjeta; ?> btn-xs">ACTUALIZAR TARJETA</button>
                                <div class="col-sm-12">
                                    <!-- textarea -->
                                    <div class="form-group">
                                    <?php if ($acceso_agregar_tarjeta=="SI") { ?>
                                        <small><label>BITACORA</label><br>
                                        <?php echo $data2["bitacora"]; ?></small>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </section>

            </form>

        </div>

        <!-- Info boxes -->
        <div class="row">
            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>
        </div><!-- /.container-fluid -->

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 SOPORTE DE APLICACIONES</strong>
            <div class="float-right d-none d-sm-inline-block">
                <b>DATACOM SRL</b>
            </div>
        </footer>

    </div>
    <!-- ./wrapper -->

    <?php include "view/script.php"; ?>

    <script language="javascript">
        $(document).ready(function() {
            $("#cambiarfecha").on('change', function() {
                if ($('#cambiarfecha').is(":checked")) {
                    $("#reservation").prop("disabled", false);
                } else {
                    $("#reservation").prop("disabled", true);
                }
            });

            $("#cambiarintegrantes").on('change', function() {
                if ($('#cambiarintegrantes').is(":checked")) {
                    $("#integrantes_solicitud").prop("disabled", false);
                } else {
                    $("#integrantes_solicitud").prop("disabled", true);
                }
            });

        });
    </script>

</body>

</html>