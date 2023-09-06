<?php
include_once "models/rutas.php";
$cfgProgDir = '../phpSecurePages/';
include($cfgProgDir . "secure.php");

include("script/conex_doc.php");
include("script/perfiles.php");




if ($menu_bitacora == "NO") {
    header('Location: missolicitudes.php');
    die();
}


$sql = "SELECT * FROM BITACORAS WHERE id=".$_REQUEST["id"]." ";
$resultado = mysql_query($sql, $con_doc);
$data = mysql_fetch_array($resultado);
$titulo=$data["titulo"];
$date_ini=$data["date_ini"];
$descripcion=$data["descripcion"];
if (strlen($data["bitacora"])<1) {$bitacora_bd="VACIO";} else {$bitacora_bd=$data["bitacora"]; $bitacora_old=$data["bitacora"];}






mysql_close($con_doc);

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
        <?php include "view/navbar.php"; ?>
        <!-- /.navbar -->


        <!-- menu -->
        <?php include_once "view/menu.php"; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">


            &nbsp

            <!-- Main content -->
            <form action="script/actualiza_bitacora.php" method="post">
                <small>
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Profile Image -->
                                    <div class="card card-primary card-outline" id="informe" name="informe">

                                        <div class="card-body box-profile">
                                            <!-- /.<div class="text-center">
                                            <img class="profile-user-img img-fluid img-circle" onerror="src='../portal/images/pe_pictures/117x117/SIN_FOTO.png'" src="../portal/images/pe_pictures/117x117/<?php echo $foto; ?>" alt="<?php echo $uname; ?>">
                                        </div> -->

                                            <h3 class="profile-username text-center">ACTUALIZAR BITACORA N° <b><?php echo $_REQUEST["id"]; ?></b></h3>
                                            <hr>
                                            <br>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>TITULO DE LA BITACORA</label>
                                                    <input type="text" class="form-control form-control-sm" placeholder="<?php echo $titulo; ?>" id="titulo" name="titulo" maxlength="50" disabled>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>FECHA INICIO</label>
                                                    <input type="text" class="form-control form-control-sm" value="<?php echo $date_ini; ?>" id="date_ini" name="date_ini" maxlength="50" disabled>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="form-check-input" type="checkbox" id="cambiarfecha" name="cambiarfecha">FECHA FINALIZACION</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control float-right form-control-sm" id="date_fin" name="date_fin" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <label>DESCRIPCION</label>
                                                <div class="col-sm-12">
                                                    <textarea class="form-control form-control-sm" rows="4" placeholder="<?php echo $descripcion; ?>" required id="descripcion" name="descripcion" disabled></textarea>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <label>AÑADIR EVENTOS: </label>
                                                <div class="col-sm-12">
                                                    <textarea class="form-control form-control-sm" rows="3" placeholder="ACTUALIZAR ..." required id="bitacora" name="bitacora"></textarea>
                                                </div>
                                            </div>
                                            <br>
                                            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>">
                                            <input type="hidden" id="bitacora_bd" name="bitacora_bd" value="<?php echo $bitacora_bd; ?>">
                                            <input type="hidden" id="bitacora_old" name="bitacora_old" value="<?php echo $bitacora_old; ?>">
                                            <button type="submit" class="btn btn-block btn-warning btn-xs" id="guardar" name="guardar">ACTUALIZAR</button>
                                            <br>
                                            <div class="row">
                                                <label>EVENTOS: </label>
                                                <div class="col-sm-12">
                                                    <?php echo $bitacora_old; ?>
                                                </div>
                                            </div>


                                        </div>

                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->

                                </div>
                                <!-- /.col -->



                            </div>
                            <!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </section>

                </small>
            </form>

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

    <script language="javascript">
        $(document).ready(function() {
            $("#cambiarfecha").on('change', function() {

                if ($('#cambiarfecha').is(":checked")) {

                    $("#date_fin").prop("disabled", false);
                    if ($("#date_ini").val() > $("#date_fin").val()) {
                        $("#guardar").prop("disabled", true);
                    }

                    $("#date_fin").on('change', function() {
                        if ($("#date_ini").val() > $("#date_fin").val()) {
                            $("#guardar").prop("disabled", true);
                        }
                        else {$("#guardar").prop("disabled", false);}
                    });

                    $("#date_ini").on('change', function() {
                        if ($("#date_ini").val() > $("#date_fin").val()) {
                            $("#guardar").prop("disabled", true);
                        }
                        else {$("#guardar").prop("disabled", false);}
                    });

                } else {
                    $("#date_fin").prop("disabled", true);
                    $("#guardar").prop("disabled", false);
                }

            });

        });
    </script>


</body>

</html>