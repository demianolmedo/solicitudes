<?php
include_once "models/rutas.php";
$cfgProgDir = '../phpSecurePages/';
include($cfgProgDir . "secure.php");
include("script/perfiles.php");
include("script/conex_doc.php");

if ($acceso_agregar_tarjeta == "NO") {
    header("location: tablero.php?id=" . $_REQUEST['id'] . "");
    exit;
}

$color = "primary";

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

//echo $x; exit;



if (!$tablero) {
    header('Location: missolicitudes.php');
    die();
}

if ($_REQUEST['lista'] == "COMPLETADO") {
    $colortarjeta = "primary";
} else if ($_REQUEST['lista'] == "PROCESO") {
    $colortarjeta = "warning";
} else if ($_REQUEST['lista'] == "PRUEBAS") {
    $colortarjeta = "success";
}

if (!$data["asignar_a_n"]) {
    header('Location: missolicitudes.php');
    die();
}
if ($tablero == " -  - ") {
    header('Location: missolicitudes.php');
    die();
}


$resultado = mysql_query($sql_area, $con_doc);
$row_cnt = mysql_num_rows($resultado);
$solicitudes = "";
while ($data = mysql_fetch_array($resultado)) {
    $area .= "<option value='" . $data['id'] . ",".$data['area']."'>&nbsp;&nbsp;&nbsp;&nbsp;" . $data['area'] . "</option>";
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
        <!-- /.navbar -->

        <!-- menu -->
        <?php include_once "view/menu.php"; ?>

        <div class="content-wrapper">

            <form action="script/guardatarjeta.php" method="post">
                <!-- Main content -->
                <section class="content">
                    <div class="card card-<?php echo $colortarjeta; ?> card-outline">
                        <div class="card-header">
                            <h5 class="card-title m-0">AGREGAR TARJETA <b><?php echo $_REQUEST['lista']; ?></b></h5>
                        </div>
                        <div class="card-body">

                            <div class="row">

                                <div class="col-sm-4">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <small><label>TITULO TAREA</label></small>
                                        <input type="text" class="form-control form-control-sm" placeholder="Enter ..." maxlength="50" id="titulo" name="titulo" required>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <small><label>FECHA INICIO FIN</label></small>
                                        <div class="input-group">
                                            <input type="text" class="form-control float-right form-control-sm" id="reservation" name="reservation">
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <small><label>INTEGRANTES DE LA TAREA</label></small>
                                        <div class="input-group">
                                            <select class="select2 form-control-sm" multiple="multiple" data-placeholder="AGREGAR RESPONSABLES..." data-dropdown-css-class="select2-blue" style="width: 100%;" id="integrantes_solicitud" name=integrantes_solicitud[] required>
                                                <?php echo $integrantes_solicitud; ?>
                                            </select>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <small><label>TAREA VISIBLE</label></small>
                                        <div class="input-group">
                                            <select class="form-control form-control-sm" id="visible" name="visible">
                                                <option value="NO">NO</option>
                                                <option value="SI">SI</option>
                                            </select>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>

                                <div class="col-sm-11">
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <small><label>DESCRIPCION TAREA</label></small>
                                        <textarea class="form-control form-control-sm" rows="3" placeholder="DESCRIPCION ..." id="descripcion" name="descripcion" required></textarea>
                                    </div>
                                </div>

                                <div class="col-sm-1">
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <small><label>CANTIDAD</label></small>
                                        <select class="form-control form-control-sm" id="numero" name="numero">
                                        <?php
                                            for ($x = 1; $x <= 100; $x++) {
                                            echo ' <option value='.$x.'>'.$x.'</option>';
                                            }
                                        ?>
                                               
                                            </select>
                                    </div>
                                </div>

                                 

                                <input id="id_rel" name="id_rel" type="hidden" value="<?php echo $_REQUEST['id']; ?>">
                                <input id="estado" name="estado" type="hidden" value="<?php echo $_REQUEST['lista']; ?>">

                                <div class="col-sm-3">
                                    <div class="form-group">                                        
                                        <select class="form-control form-control-sm" name="area" id="area" required>
                                                    <option selected disabled>CREAR NUEVA SOLICITUD</option>
                                                    <?php echo $area; ?>
                                                </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3" id="f1"> </div>
                                <div class="col-sm-3" id="f2"> </div>
                                

                                <button type="submit" class="btn btn-block btn-<?php echo $colortarjeta; ?> btn-xs" id="boton">AGREGAR TAREA</button>

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

        <!-- /.content-wrapper -->
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
                                            $("#area").on('change', function() {
                                                $("#area option:selected").each(function() {
                                                    area = $(this).val();
                                                    $.post("view/opciones_solicitud_tarea.php", {
                                                        area: area
                                                    }, function(data) {
                                                        $("#f1").html(data);
                                                        $("#f2").html("");
                                                        $("#f3").html("");
                                                        $("#boton").hide();
                                                    });
                                                });
                                            });



                                        });
                                    </script>


</body>

</html>